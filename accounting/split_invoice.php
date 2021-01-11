<script type='text/javascript' src='jq/jquery.js'></script>

<script type='text/javascript'>
  function calcSum() {
    var sum = 0;
      $(".v").each(function(){
          sum += +$(this).val();
      });
    return sum;
  }

  function checkTotal() {
    var actualSum = $("#invoiceTotal").text();

    // Remove spaces, and transform to int
    actualSum = parseInt(actualSum.replace(/\s+/g, ''));  

    const sum = calcSum();
    if(sum < actualSum) {
      $(".total").css("color", "red");
      
      $("#valueOverAlert").hide();
      $("#valueUnder").empty();
      $("#valueUnder").append(actualSum - sum);
      $("#valueUnderAlert").show();
    } else if(sum > actualSum) {
      $(".total").css("color", "red");

      $("#valueUnderAlert").hide();
      $("#valueOver").empty();
      $("#valueOver").append(sum - actualSum);
      $("#valueOverAlert").show();
    } else if (sum == actualSum) {
      $(".total").css("color", "black");
      $("#valueOverAlert").hide();
      $("#valueUnderAlert").hide();
    }
  }

  $(document).ready(function() {
      checkTotal();
  });

  $(document).on("change keyup", ".v", function() {
      const sum = calcSum();
      $(".total").val(sum);
      
      checkTotal();
  });
</script>

<?php

$PA['invoiceid'] = 'int';
$PA['partition'] = 'int';
$PA['lines'] = 'int';
require('inc/readpost.php');

for ($i=0; $i < $lines; $i++)
{
  $PA['value'.$i] = 'int';
  $PA['date'.$i] = 'date';
}

# Prevent notice 'PA is not defined' in readpost
if ($lines !== 0) { require('inc/readpost.php'); }

$showmenu = 1;

if ($invoiceid > 0)
{
  $query = 'select clientid,invoiceprice,accountingdate,matchingid,reference,invoicetagid,
            custominvoicedate from invoicehistory where isreturn=0 and cancelledid=0 and 
            matchingid=0 and invoiceid=?';
  $query_prm = array($invoiceid);
  require('inc/doquery.php');
  
  if($num_results === 0) 
  {
    echo '<p class="alert">La facture ' . $invoiceid . ' n\'existe pas, ou ne peut pas être 
          échelonnée.</p><br>';
  } 
  else if ($num_results > 1) 
  {
    echo '<p class="alert">Problème critique : certaines factures ont le même identifiant.</p><br>';
  }
  elseif ($num_results == 1)
  {
    $invoicetagid = $query_result[0]['invoicetagid'];
    $invoicevalue = $query_result[0]['invoiceprice'];
    $accountingdate = $query_result[0]['accountingdate'];
    $clientid = $query_result[0]['clientid'];
    $matchingid = $query_result[0]['matchingid'];
    $reference = $query_result[0]['reference'];
    $custominvoicedate = $query_result[0]['custominvoicedate'];

    if (!isset($custominvoicedate)) 
    {
      if (!isset($accountingdate)) { $custominvoicedate = date('Y-m-d'); }
      else { $custominvoicedate = $accountingdate; } 
    }

    if ($partition)
    {
      $check_value = 0;
      for ($i=0; $i < $lines; $i++)
      {
        $temp = 'value'.$i;
        if ($$temp > 0) { $check_value += (double) $$temp; }
      }
      if ($check_value != $invoicevalue) 
      { 
        echo '<p class="alert">Il faut répartitionner le montant total.</p><br>'; 
        $showmenu = 0; 
      }
      else
      {
        $query = 'update invoicehistory set field2="Échelonnée" where invoiceid=?';
        $query_prm = array($invoiceid);
        require('inc/doquery.php');

        for ($i=0; $i < $lines; $i++)
        {
          $temp = 'value'.$i;
          if ($$temp > 0) { $val = (double) $$temp; } else { $val = 0; }
          if ($val > 0)
          {
            #insert invoice with val
            $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoice"';
            $query_prm = array();
            require('inc/doquery.php');
            
            $insertid = $query_insert_id;
            if ($insertid < 1) 
            { 
              echo '<p class=alert>critical error attributing invoiceid</p>'; 
              exit; 
            }

            $date = 'date'.$i;
            $query = 'insert into invoice (invoiceid,confirmed,invoiceprice,clientid,accountingdate,
                      userid,cancelledid,matchingid,isreturn,reference,invoicecomment,paybydate)
                      values (?,1,?,?,?,?,0,0,0,?,?,?)';
            $query_prm = array($insertid,$val,$clientid,$$date,$_SESSION['ds_userid'],$reference,
                               $invoiceid.' Échelonnée',$$date);
            require('inc/doquery.php');

            #also insert invoiceitem TESTING productid=0
            $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) 
                      where seqname="invoiceitem"';
            $query_prm = array();
            require('inc/doquery.php');

            $insertid2 = $query_insert_id;
            $query = 'insert into invoiceitem (invoiceitemid,invoiceid,productid,quantity,
                      givenrebate,basecartonprice,lineprice,linevat,linetaxcodeid,rebate_type) 
                      values (?,?,?,?,?,?,?,?,?,?)';
            $query_prm = array($insertid2, $insertid, 0, 1, 0, $val, $val, 0, 1, 0);
            require('inc/doquery.php');
          }
        }

        # insert invoice with checkval
        $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoice"';
        $query_prm = array();
        require('inc/doquery.php');

        $insertid = $query_insert_id;
        if ($insertid < 1) 
        { 
          echo '<p class=alert>critical error attributing invoiceid</p>'; 
          exit; 
        }
        $query = 'insert into invoice (invoiceid,confirmed,invoiceprice,clientid,accountingdate,
                  userid,cancelledid,matchingid,isreturn,reference,invoicecomment,paybydate,
                  invoicetagid)
                  values (?,1,?,?,?,?,0,0,1,?,?,?,?)';
        $query_prm = array($insertid,$check_value,$clientid,$accountingdate,$_SESSION['ds_userid'],
                           $reference,$invoiceid.' Échelonnée',$accountingdate,$invoicetagid);
        require('inc/doquery.php');
        
        #also insert invoiceitem TESTING productid=0
        $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoiceitem"';
        $query_prm = array();
        require('inc/doquery.php');

        $insertid2 = $query_insert_id;
        $query = 'insert into invoiceitem (invoiceitemid,invoiceid,productid,quantity,givenrebate,
                  basecartonprice,lineprice ,linevat,linetaxcodeid,rebate_type) values 
                  (?,?,?,?,?,?,?,?,?,?)';
        $query_prm = array($insertid2, $insertid, 0, 1, 0, $check_value, $check_value, 0, 1, 0);
        require('inc/doquery.php');
        
        echo '<p>Facture '.$invoiceid.' partionnée.</p><br>';
        require('inc/move_to_history.php');

        if ($matchingid == 0)
        {
          $query = 'insert into matching (userid,date,clientid) values (?,CURDATE(),?)';
          $query_prm = array($_SESSION['ds_userid'], $clientid);
          require ('inc/doquery.php');

          $matchingid = $query_insert_id;
          $query = 'update invoicehistory set matchingid=? where invoiceid=? or invoiceid=?';
          $query_prm = array($matchingid, $insertid, $invoiceid);
          require ('inc/doquery.php');
        }
      }
    }
    else { $showmenu = 0; }
  } 
}
if (!$showmenu)
{
  echo '<h2>Échelonner facture '.$invoiceid.'</h2>
  <form method="post" action="accounting.php"><table>';
  
  $is_values_set = 0;
  for ($i=0; $i < $lines; $i++)
  {
    $temp_value = 'value' . $i;
    if ($$temp_value !== 0) 
    {
      $is_values_set = 1;
    }
  }

  # Save line values to compute total
  $line_valuesA = array();

  # The user has NOT defined custom values / dates, so we need to compute default values and dates
  if(!$is_values_set) 
  {
    $line_value = myround($invoicevalue / $lines);

    # Because of the rounding, this total may not be equal to $invoicevalue
    $temp_total = $line_value * $lines;

    # The value we will need to add to the last line
    $adjust_value = $invoicevalue - $temp_total;

    $day = substr($custominvoicedate, 8, 2);
    $month = substr($custominvoicedate, 5, 2);
    $year = substr($custominvoicedate, 0, 4);

    for ($i=0; $i < $lines; $i++)
    {
      # Adjust the value of the last line to make up for the imprecisions due to rounding
      if ($i === ($lines - 1)) { $line_value += $adjust_value; }
      array_push($line_valuesA, $line_value);

      echo '<tr><td><input onblur="findTotal()"';
      if ($i == 0) { echo ' autofocus'; }
      echo ' value="' . $line_value . '" type="number" min="0" STYLE="text-align:right" 
             name="value'.$i.'" class="v" size=10><td>';

      $is_multiple_12 = ($month + $i) % 12 === 0;
      $new_month = $is_multiple_12 ? 12 : ($month + $i) % 12;

      $num_years_added = floor(($month + $i) / 12);
      if ($is_multiple_12) { $num_years_added -= 1; }
      $new_year = $year + $num_years_added;

      # d_builddate will take care of adjusting $day (ex: Apr 31 --> Apr 30)
      $selecteddate = d_builddate($day, $new_month, $new_year);
      $datename = 'date'.$i; 
      require('inc/datepicker.php');
    }
  } 
  else # Values and dates have already been set, use them
  {
    for ($i=0; $i < $lines; $i++)
    {
      echo '<tr><td><input onblur="findTotal()"';
      if ($i == 0) { echo ' autofocus'; }
      $temp_value = 'value' . $i;
      array_push($line_valuesA, $$temp_value);
      echo ' value="' . $$temp_value . '"type="number" min="0" STYLE="text-align:right" 
             name="value'. $i .'" class="v" size=10><td>';

      $temp_date = 'date' . $i;
      $selecteddate = $$temp_date;
      $datename = $temp_date; 
      require('inc/datepicker.php');
    }
  }

  $sum_lines = array_sum($line_valuesA);
  echo '<tr><td align=center><input disabled type="text" STYLE="text-align:center" class="total" 
  size=12 value="' . myfix($sum_lines) . '">
  <td id="invoiceTotal" align=center size=12>'. myfix($invoicevalue) .'
  <tr><td colspan="2" align="center">
  <input type=hidden name="invoiceid" value='.$invoiceid.'>
  <input type=hidden name="partition" value=1>
  <input type=hidden name="accountingmenu" value="'.$accountingmenu.'">
  <input type=hidden name="lines" value="'. $lines .'">
  <br>
  <p STYLE="display: none" id="valueUnderAlert" class="alert">Il manque <span id="valueUnder">
  </span> CFP aux échéances.</p>
  <p STYLE="display: none" id="valueOverAlert" class="alert">Il y a un surplus de 
  <span id="valueOver"></span> CFP aux échéances.</p>
  <input type="submit" value="Valider">
  </table></form>';
}

if ($showmenu)
{
  ?><h2>Échelonner facture</h2>
  <form method="post" action="accounting.php">
    <table>
      <tr>
        <td>Échelonner facture : </td>
        <td>
          <input autofocus type="text" STYLE="text-align:right" name="invoiceid" size=10>
        </td>
      </tr>
      <tr>
        <td>Nombre d'échéances : </td>
        <td>
          <input type="number" min="1" STYLE="text-align:right" name="lines" value=12>
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center">
          <input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
          <input type="submit" value="Valider">
    </table>
  </form>
<?php } ?>