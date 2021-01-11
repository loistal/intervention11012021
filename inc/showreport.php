<?php

# input: $row $num_rows $showtable $reportid $subtfield1
# for subtotals set $subtfield1 like this: # example: if ($orderby == 1) { $query .= ' order by clientid,invoiceid'; $subtfield1 = 'clientid'; }

$invoiceA = array();
if(!isset($showtable)){$showtable=true;}

if (isset($_POST['showreport_all_columns']) && $_POST['showreport_all_columns'] == 1)
{
  $fieldnum = max(array_keys($dp_fieldnameA));
  for ($i = 1; $i <= $fieldnum; $i++)
  {
    if (isset($dp_fielddescrA[$i]))
    {
      $showfieldA[$i] = $i;
      $showtitleA[$i] = $dp_fielddescrA[$i];
    }
  }
}
else
{
  $fieldnum = 0;
  $query = 'select fieldnum,showfield,showtitle from cf_report where reportid=? and userid=? order by fieldnum';
  $query_prm = array($reportid, $_SESSION['ds_userid']);
  require('inc/doquery.php');
  for ($i = 0; $i < $num_results; $i++)
  {
    if ($query_result[$i]['showfield'] > 0)
    {
      $fieldnum++;
      $showfieldA[$fieldnum] = $query_result[$i]['showfield'];
      $showtitleA[$fieldnum] = $query_result[$i]['showtitle'];
      if ($showtitleA[$fieldnum] == '') { $showtitleA[$fieldnum] = $dp_fielddescrA[$showfieldA[$fieldnum]]; }
    }
  }
}
if ($fieldnum == 0)
{
  echo '<br><br><p>' . d_trad('pleaseconfigurefieldstable') . '</p>';
  $showtable = false;
}

if($showtable)
{
  if($num_rows > 0)
  {
    echo '<table class=report><thead>';
    for ($i = 1; $i <= $fieldnum; $i++)
    {
      echo '<th>' . $showtitleA[$i] . '</th>';
    }
    echo '</thead><tbody>';

    for ($i = 0; $i < $num_rows; $i++)
    {
      if ($_SESSION['ds_maxopeninvoices'] > 0 && $reportid == 3)
      {
        if ($i < $_SESSION['ds_maxopeninvoices']) { array_push($invoiceA, $row[$i]['invoiceid']); } # create unique list of invoices
      }
      if (isset($dp_updatestock) && $dp_updatestock == 1 && $row[$i]['countstock'] == 1)
      {
        $productid = $row[$i]['productid'];
        $numberperunit = $row[$i]['numberperunit'];
        if ($productid > 0 && $numberperunit > 0)
        {
          require('inc/calcstock.php');
          $row[$i]['currentstock'] = $currentstock;
          $row[$i]['currentstockrest'] = $unitstock;
        }
      }
      echo d_tr();
      for ($y = 1; $y <= $fieldnum; $y++)
      {
        $fieldname = $dp_fieldnameA[$showfieldA[$y]]; # name of field, example: clientname
        if (isset($row[$i][$fieldname])) { $showfield = $row[$i][$fieldname]; } # field to be formatted, example: "ABC Store"
        else { $showfield = ''; }
        require('inc/configfield.php');
        if ($temp_unfiltered) { echo d_td_unfiltered($showfield); }
        else { echo d_td_old($showfield, $rightalign_temp, $break_temp, 0, $link_temp); } # TODO get rid of old function
      }
      require('inc/showsubtotal.php');
    }

    $i = ($num_rows -1);
    require('inc/showgrandtotal.php');
    echo '</tbody></table>';

    if ($_SESSION['ds_maxopeninvoices'] > 0 && $reportid == 3)
    {
      $lastinvoicetoopen = array_pop($invoiceA);
      ?>
      <script language="javascript" type="text/javascript">
      function openinvoices()
      {
        a = <?php echo json_encode($invoiceA) ?>;
        a.forEach(function(entry) {
          var result = "printwindow.php?report=showinvoice&invoiceid="+ entry;
          window.open(result);
        });
      }
      </script>
      <?php
      if ($lastinvoicetoopen > 0)
      {
        echo '<a href="printwindow.php?report=showinvoice&invoiceid=' . $lastinvoicetoopen . '" onclick="openinvoices()">Ouvrir toutes les factures</a> (Ctrl + clic, max '.d_output($_SESSION['ds_maxopeninvoices']).')';
      }
    }
  }
  else
  {
    echo '<p>' . d_trad('noresult') . '</p>';
  }
}
?>