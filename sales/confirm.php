<script type='text/javascript' src='jq/jquery.js'></script>

<script type='text/javascript'>
$(document).ready(function(){

// source http://www.formget.com/checkuncheck-all-checkboxes-using-jquery/

$("#confirmall").attr("data-type","check");
$("#confirmall").click(function(){
if($("#confirmall").attr("data-type")==="check")
{
$(".confirm").prop("checked",true);
$("#confirmall").attr("data-type","uncheck");
}
else
{
$(".confirm").prop("checked",false);
$("#confirmall").attr("data-type","check");
}
})
});
</script>

<script type='text/javascript'>
$(document).ready(function(){
$("#cancelall").attr("data-type","check");
$("#cancelall").click(function(){
if($("#cancelall").attr("data-type")==="check")
{
$(".cancel").prop("checked",true);
$("#cancelall").attr("data-type","uncheck");
}
else
{
$(".cancel").prop("checked",false);
$("#cancelall").attr("data-type","check");
}
})
});
</script>
<?php

require('preload/localvessel.php');

$PA['invoicetagid'] = 'int';
$PA['proforma'] = 'int';
$PA['myuserid'] = 'int';
$PA['confirm'] = 'uint';
require('inc/readpost.php');

if ($myuserid == 0)
{
  ?><h2>Confirmer / annuler factures:</h2>
  <form method="post" action="sales.php"><table>
  <tr><td>De :<td><?php $datename = 'startdate'; $dp_setempty = 1; require('inc/datepicker.php');
  echo '<tr><td>À :<td>'; $datename = 'stopdate'; $dp_setempty = 1; require('inc/datepicker.php');
  if ($_SESSION['ds_confirmonlyown']) { echo '<input type=hidden name="myuserid" value='.$_SESSION['ds_userid'].'>'; }
  else
  {
    echo '<tr><td>Utilisateur :<td><select autofocus name="myuserid">';
    $query = 'select userid,name from usertable where salesaccess=1 order by name';
    $query_prm = array();
    require('inc/doquery.php');
    echo '<option value="-1">' . d_trad('selectall') . '</option>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row2['userid'] == $_SESSION['ds_userid'])
      { echo '<option value="' . $row2['userid'] . '" selected>' . $row2['name'] . '</option>'; }
      else { echo '<option value="' . $row2['userid'] . '">' . $row2['name'] . '</option>'; }
    }
    ?></select><?php
  }
  ?><tr><td>Proforma :<td><?php
  echo '<select name="proforma">
  <option value=-1>' . d_trad('selectall') . '</option>
  <option value=1>Proforma</option>
  <option value=0>Hors proforma</option>
  </select>'; ?>
  <tr><td><?php echo $_SESSION['ds_term_invoicetag'];
  $dp_itemname = 'invoicetag'; $dp_allowall = 1; require('inc/selectitem.php');
  ?>
  <tr><td colspan="2" align="center">
  <input type=hidden name="salesmenu" value="<?php echo $salesmenu; ?>">
  <input type="submit" value="Valider">
  </table></form><?php
}
else
{
  if ($confirm)
  {
    $listconfirmed = ''; $listcancelled = ''; $in_confirmed = '('; $listconfirmed_hideprices = '';
    $all_results = $_POST['results']+0;
    for ($i=0; $i < $all_results; $i++)
    {
      if (!isset($_POST['confirmed' . $i])) { $_POST['confirmed' . $i] = 0; }
      if (!isset($_POST['cancelled' . $i])) { $_POST['cancelled' . $i] = 0; }
      if (!isset($_POST['store' . $i])) { $_POST['store' . $i] = 0; }
      if ($_POST['confirmed' . $i] && !$_POST['cancelled' . $i])
      {
        ### check if sum of lines equals invoiceprice
        $ok = 0;
        $query = 'select invoiceprice from invoice where invoiceid=?';
        $query_prm = array($_POST['confirmed' . $i]);
        require('inc/doquery.php');
        $invoicetotal = $query_result[0]['invoiceprice']+0;
        $query = 'select sum(lineprice+linevat) as linetotals from invoiceitem where invoiceid=?';
        $query_prm = array($_POST['confirmed' . $i]);
        require('inc/doquery.php');
        $linetotals = $query_result[0]['linetotals']+0;
        $linetotals = myround($linetotals); $invoicetotal = myround($invoicetotal); # 2017 01 25
        if ($linetotals == $invoicetotal) { $ok = 1; }
        else
        {
          $errortext = '<span class="alert">Erreur sur facture '.$_POST['confirmed' . $i]
          .'</span> Veuillez la remodifier.<br>';
          echo $errortext;
          if (!isset($_SESSION['last_sqlerror_time']) || time() > ($_SESSION['last_sqlerror_time']+60))
          {
            if (d_sendemail('svein.tjonndal@gmail.com','svein.tjonndal@gmail.com',$errortext,$errortext))
            { echo '<p class=alert>Un e-mail a été envoyé au service technique.</p>'; }
            else { echo '<p class=alert>Veuillez contacter le service technique.</p>'; }
            $_SESSION['last_sqlerror_time'] = time();
          }
          #$_POST['cancelled' . $i] = $_POST['confirmed' . $i];
          $_POST['confirmed' . $i] = '';
        }
        ###
        if ($ok)
        {
          $querymain = 'update invoice set confirmed=1,proforma=0,invoicedate=curdate(),invoicetime=curtime()';
          if ($_SESSION['ds_confirmchangesdate'] == 1)
          {
            $query = 'select daystopay,special from client,invoice,clientterm where invoice.clientid=client.clientid and client.clienttermid=clientterm.clienttermid and invoiceid=?';
            $query_prm = array($_POST['confirmed' . $i]);
            require('inc/doquery.php');
            $rowEXTRA = $query_result[0];
            $daystopay = $rowEXTRA['daystopay']+0;
            if ($rowEXTRA['special'] == 1) # end of month
            {
              $endofmonthdate = new DateTime($_SESSION['ds_curdate']);
              $endofmonthdate->modify('last day of this month');
              $daystopay = ((int) $endofmonthdate->format('d')) - ((int) substr($_SESSION['ds_curdate'],8,2));
            }
            if ($daystopay < 1) { $daystopay = 0; }
            $querymain = $querymain . ',accountingdate=curdate(),paybydate=DATE_ADD(curdate(), INTERVAL ' . $daystopay . ' DAY)';
          }
          $query = $querymain . ' where invoiceid=?';
          $query_prm = array($_POST['confirmed' . $i]);
          require('inc/doquery.php');
          if($num_results == count($query_prm))
          {
            $listconfirmed .= ' <a href="printwindow.php?report=showinvoice&invoiceid=' . $_POST['confirmed' . $i] . '" target=_blank>' . $_POST['confirmed' . $i] . '</a>';
            $listconfirmed_hideprices .= ' <a href="printwindow.php?report=showinvoice&hideprices=1&invoiceid=' . $_POST['confirmed' . $i] . '" target=_blank>' . $_POST['confirmed' . $i] . '</a>';
            $in_confirmed .= $_POST['confirmed' . $i] . ',';
          }
        }
      }
      if ($_POST['cancelled' . $i] && !$_POST['confirmed' . $i])
      {
        $query = 'update invoice set cancelledid=1,invoicedate=curdate(),invoicetime=curtime() where invoiceid=?';
        $query_prm = array($_POST['cancelled' . $i]);
        require('inc/doquery.php');
        if($num_results == count($query_prm))
        {
          $listcancelled .= ' <a href="printwindow.php?report=showinvoice&invoiceid=' . $_POST['cancelled' . $i] . '" target=_blank>' . $_POST['cancelled' . $i] . '</a>';
        }
      }
      if ($_POST['store' . $i] && !$_POST['confirmed' . $i] && !$_POST['cancelled' . $i])
      {
        $query = 'update invoice set cancelledid=2 where invoiceid=?';
        $query_prm = array($_POST['store' . $i]);
        require('inc/doquery.php');
      }
    }
    echo 'Factures confirmées:'.$listconfirmed;
    if ($_SESSION['ds_show_hideprices_after_confirm'] == 1) { echo '<br>Factures confirmées (masquer les prix):'.$listconfirmed_hideprices; }
    echo '<br>Factures annulées:'.$listcancelled.'<br><br>';
    
    require('inc/move_to_history.php');

  }

  $listall = '';
  $query = 'select localvesselid,reference,isnotice,proforma,isreturn,invoiceid,accountingdate,clientname
  ,invoice.clientid as clientid,invoiceprice,initials
  from invoice,client,usertable
  where invoice.userid=usertable.userid and invoice.clientid=client.clientid
  and cancelledid=0 and confirmed=0';
  $query_prm = array();
  if ($_SESSION['ds_cannotconfirmnotice']) { $query .= ' and isnotice=0'; }
  if ($myuserid > 0)
  {
    $query .= ' and invoice.userid=?'; array_push($query_prm, $myuserid);
  }
  $datename = 'startdate'; require('inc/datepickerresult.php');
  if (isset($startdate) && $startdate != '')
  {
    $query .= ' and invoice.accountingdate>=?'; array_push($query_prm, $startdate);
  }
  $datename = 'stopdate'; require('inc/datepickerresult.php');
  if (isset($stopdate) && $stopdate != '')
  {
    $query .= ' and invoice.accountingdate<=?'; array_push($query_prm, $stopdate);
  }
  if ($proforma >= 0) { $query .= ' and invoice.proforma=?'; array_push($query_prm, $proforma); }
  if ($invoicetagid >= 0) { $query .= ' and invoice.invoicetagid=?'; array_push($query_prm, $invoicetagid); }
  $query = $query . ' order by invoiceid';
  require('inc/doquery.php');
  echo '<form method="post" action="sales.php"><table class="detailinput"><tr><td><b>Confirmer</td><td><td><b>Facture</td>';
  echo '<td><b>' . $_SESSION['ds_term_accountingdate'] . '</td><td><b>Client</td><td><b>Prix total</td>';
  if ($_SESSION['ds_term_reference'] != "") { echo '<td><b>' . d_output($_SESSION['ds_term_reference']) . '</td>'; }
  else { echo '<td><b>Référence</td>'; }
  echo '<td><b>Facturier</td>';
  if (isset($localvesselA)) { echo '<td><b>Bateau</b></td>'; }
  echo '<td><b>Annuler';
  if ($_SESSION['ds_store_quotes']) { echo '<td><b>Archiver'; }
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $listall = $listall . ' ' . $row['invoiceid'];
    $returntext = ""; if ($row['isreturn'] == 1) { $returntext = '(Avoir) '; }
    if ($row['proforma'] == 1) { $returntext = '(Proforma) '; }
    if ($row['isnotice'] == 1) { $returntext = '('.$_SESSION['ds_term_invoicenotice'].') '; }
    echo '<tr><td> &nbsp; <input type="checkbox" class="confirm" name="confirmed' . $i . '" value="' . $row['invoiceid'] . '">
    <td><a href="sales.php?salesmenu=invoicing&modify=1&invoiceid='
    . $row['invoiceid'] . '" target=_blank>Modifier</a>
    <td align=right>' . $returntext . '<a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid='
    . $row['invoiceid'] . '" target=_blank>' . myfix($row['invoiceid']) . '</a>
    <td align=right>'
    . datefix2($row['accountingdate']) . '</td><td><a href="reportwindow.php?report=showclient&usedefaultstyle=1&client='
    . $row['clientid'] . '" target=_blank>' . $row['clientid'] . ': ' . d_output(d_decode($row['clientname']))
    . '</a></td><td align=right>' . myfix($row['invoiceprice']) . '</td><td>' . $row['reference'] . '</td><td>'
    . $row['initials'];
    if (isset($localvesselA)) { echo '<td>' . $localvesselA[$row['localvesselid']] . '</td>'; }
    echo '<td> &nbsp; <input type="checkbox" class="cancel" name="cancelled' . $i . '" value="' . $row['invoiceid'] . '">';
    if ($_SESSION['ds_store_quotes'])
    { echo '<td> &nbsp; <input type="checkbox" class="store" name="store' . $i . '" value="' . $row['invoiceid'] . '">'; }
  }
  $colspan=7; if (isset($localvesselA)) { $colspan++; }
  echo '<tr><td> &nbsp; <input type="button" id="confirmall" value="Tous" /></td><td colspan='.$colspan.'></td>
  <td> &nbsp;  <input type="button" id="cancelall" value="Tous" />';
  if ($_SESSION['ds_store_quotes']) { echo '<td>'; }
  echo '<tr><td colspan="20" align="center"><input type=hidden name="proforma" value="'.$proforma.'">
  <input type=hidden name="invoicetagid" value="'.$invoicetagid.'">
  <input type="hidden" name="listall" value="' . $listall . '"><input type=hidden name="salesmenu" value="' . $salesmenu . '">
  <input type=hidden name="confirm" value="1"><input type=hidden name="myuserid" value="' . $_POST['myuserid'] . '">
  <input type=hidden name="results" value="' . $num_results . '"><input type="submit" value="Confirmer / annuler facture(s)">';
  if (isset($startdate))
  {
    echo '<input type=hidden name=startdate value="' . $startdate . '">';
  }
  if (isset($stopdate))
  {
    echo '<input type=hidden name=stopdate value="' . $stopdate . '">';
  }
  echo '</table></form>';
}
?>