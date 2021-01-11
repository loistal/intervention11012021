<?php

require('preload/clientterm.php');
require('preload/regulationzone.php');
require('preload/bank.php');

$startdate = d_builddate($_POST['day'],$_POST['month'],$_POST['year']);
$stopdate = d_builddate($_POST['stopday'],$_POST['stopmonth'],$_POST['stopyear']);

$client = $_POST['client'];
if (!isset($client)) { $client = $_GET['client']; }
require ('inc/findclient.php');
  

if ($clientid < 1)
{
  echo '<form method="post" action="printwindow.php"><table><tr><td>';
  require ('inc/selectclient.php');
  echo '</td></tr><tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="usedefaultstyle" value="1">';
  echo '<input type=hidden name="report" value="showaccount"><input type="submit" value="Valider"></td></tr></table></form>';
}

else
{
  $query = 'select clienttermid,client.clientid,clientname,telephone,cellphone,tahitinumber,companytypename,address,quarter
  ,postaladdress,townname,postalcode,islandname,regulationzoneid
  from client,town,island where
  town.islandid=island.islandid and client.townid=town.townid and clientid=?';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  if ($num_results == 0) { exit; }
  $row = $query_result[0];
  $clientid = $row['clientid'];
  $clientname = $row['clientname'];

  echo '<div class="releve1">';
  if ($_POST['showoperations'] == 1) { echo '<p><b>Relevé client</b></p><p>Toutes transactions<br>' . datefix($startdate) . ' au ' . datefix($stopdate) . '</p>'; }
  else { echo '<p><b>Compte client</b></p><p>' . datefix($_SESSION['ds_curdate']) . '</p>'; }
  echo '</div>';

  echo '<div class="logo">';
  $ourlogofile = 'custom/' . mb_strtolower($_SESSION['ds_customname']) . '.jpg';
  if (file_exists($ourlogofile)) { echo '<p><img src="' . $ourlogofile . '"></p>'; }
  echo '</div>';

  echo '<div class="dlogo">';
  echo '<p><img src="pics/logo.png" height="50"></p>';
  echo '</div>';

  echo '<div class="box1"><p>';
  echo $_SESSION['ds_accounttop'];
  echo '</p></div>';

  echo '<div class="company">';
  echo '<p>' . d_output(d_decode($row['clientname'])) . ' ' . $row['companytypename'];
  if ($row['address'] != "") { echo '<br>' . $row['address']; }
  if ($row['postaladdress'] != "") { echo '<br>' . $row['postaladdress']; }
  echo '<br>' . $row['postalcode'] . ' ' . $row['townname'];
  echo '<br>' . $row['islandname'];
  $zone = $regulationzoneA[$row['regulationzoneid']];
  if ($zone != '' and $zone != $row['islandname']) { echo '<br>' . $regulationzoneA[$row['regulationzoneid']]; }
  echo '</p></div>';

  echo '<div class="releve2">';
  echo '<p>Client n<span class=sup>o</span> ' . $row['clientid'] . '</p>';
  if ($row['tahitinumber'] != "") { echo '<p>Numéro Tahiti ' . $row['tahitinumber'] . '</p>'; }
  else { echo '<p>&nbsp;</p>'; }
  if ($row['telephone'] != "" || $row['cellphone'] != "") { echo '<p>Tél ' . $row['telephone'] . ' ' . $row['cellphone'] . '</p>'; }
  echo '</div>';

  echo '<div class="items">';
  $outputstring = '<b>Règlement : &nbsp; </b>' . $clienttermA[$row['clienttermid']];
  ################# same as in reportwindow ### edited, no longer the same
  $outputstring = $outputstring . '<table><tr><td valign=top>';
  $totaldebit = 0; $totalcredit = 0;


  # DEBIT
  $outputstring = $outputstring . '<table class="report">';
  $outputstring = $outputstring . '<tr><td colspan=5><font size=+1><b>DEBIT</td></tr>';
  $query = 'select invoicevat,accountingdate as date,invoiceid as id,invoiceprice as totalprice,reference from invoicehistory where cancelledid<1 and isreturn=0 and invoiceprice>0 and clientid="' . $clientid . '"';
  if ($_POST['showoperations'] == 1) { $query = $query . ' and accountingdate>="' . $startdate . '" and accountingdate<="' . $stopdate . '" and confirmed=1'; }
  else { $query = $query . ' and matchingid=0'; }
  $query = $query . ' order by accountingdate';
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results > 0) { $outputstring = $outputstring . '<tr><td><b>Facture</b></td><td><b>Date</b></td><td><b>Montant</b></td><td><b>dont TVA</td><td><b>Référence</b></td></tr>'; }
  $t1 = 0; $t2 = 0;
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $outputstring = $outputstring . '<tr><td align=right>' . myfix($row['id']) . '</td><td>' . datefix2($row['date']) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td align=right>' . myfix($row['invoicevat']) . '</td><td>' . $row['reference'] . '</td></tr>';
    $totaldebit = $totaldebit + $row['totalprice'];
    $t1 = $t1 + $row['totalprice']; $t2 = $t2 + $row['invoicevat'];
  }
  if ($t1 > 0 || $t2 > 0) { $outputstring = $outputstring . '<tr><td><i>Total</i></td><td>&nbsp;</td><td align=right><i>' . myfix($t1) . '</i></td><td align=right><i>' . myfix($t2) . '</i></td><td>&nbsp;</td></tr>'; }
  $query = 'select forinvoiceid,paymenttypeid,paymentdate as date,paymentid as id,value as totalprice,chequeno,bankid,paymentcomment from payment where value>0 and reimbursement=1 and clientid="' . $clientid . '"';
  if ($_POST['showoperations'] == 1) { $query = $query . ' and paymentdate>="' . $startdate . '" and paymentdate<="' . $stopdate . '"'; }
  else { $query = $query . ' and matchingid=0'; }
  $query = $query . ' order by paymentdate';
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results > 0) { $outputstring = $outputstring . '<tr><td><b>Remb.</b></td><td><b>Date</b></td><td><b>Montant</b></td><td><b>Cheque</td><td><b>Info</b></td></tr>'; }
  $t1 = 0; $t2 = 0;
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $myid = myfix($row['id']);
    $chequeno = $row['chequeno'];
    if ($chequeno != '')
    {
      $chequeno = $bankA[$row['bankid']] . '&nbsp;' . $chequeno;
    }
    $paymentcomment = '';
    if ($row['forinvoiceid'] > 0) { $paymentcomment = $paymentcomment . 'Fact ' . $row['forinvoiceid'] . ' '; }
    $paymentcomment .= $row['paymentcomment'];
    $outputstring = $outputstring . '<tr><td align=right>' . $myid . '</td><td>' . datefix2($row['date']) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td><font size=-1>' . $chequeno . '</font></td><td>' . $paymentcomment . '</td></tr>';
    $totaldebit = $totaldebit + $row['totalprice'];
    $t1 = $t1 + $row['totalprice'];
  }
  if ($t1 > 0) { $outputstring = $outputstring . '<tr><td><i>Total</i></td><td>&nbsp;</td><td align=right><i>' . myfix($t1) . '</i></td><td>&nbsp;</td><td>&nbsp;</td></tr>'; }
  $query = 'select adjustmentcomment,adjustmentdate as date,adjustmentid as id,value as totalprice from adjustment,adjustmentgroup where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and nomatch=0 and debit=1 and accountingnumberid=1 and referenceid="' . $clientid . '"';
  if ($_POST['showoperations'] == 1) { $query = $query . ' and adjustmentdate>="' . $startdate . '" and adjustmentdate<="' . $stopdate . '"'; }
  else { $query = $query . ' and matchingid=0'; }
  $query = $query . ' order by adjustmentdate';
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results > 0) { $outputstring = $outputstring . '<tr><td><b>Ecriture</b></td><td><b>Date</b></td><td><b>Montant</b></td><td>&nbsp;</td><td><b>Commentaire</b></td></tr>'; }
  $t1 = 0; $t2 = 0;
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $date = $row['date'];
    $outputstring = $outputstring . '<tr><td align=right>' . myfix($row['id']) . '</td><td>' . datefix2($date) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td>&nbsp;</td><td>' . $row['adjustmentcomment'] . '</td></tr>';
    $totaldebit = $totaldebit + $row['totalprice'];
    $t1 = $t1 + $row['totalprice'];
  }
  if ($t1 > 0) { $outputstring = $outputstring . '<tr><td><i>Total</i></td><td>&nbsp;</td><td align=right><i>' . myfix($t1) . '</i></td><td>&nbsp;</td><td>&nbsp;</td></tr>'; }
  $outputstring = $outputstring . '</table>';

  $outputstring = $outputstring . '</td><td width=25></td><td valign=top>';

  # CREDIT
  $outputstring = $outputstring . '<table class="report">';
  $outputstring = $outputstring . '<tr><td colspan=5><font size=+1><b>CREDIT</td></tr>';
  $query = 'select forinvoiceid,paymentdate as date,paymentid as id,value as totalprice,chequeno,bankid,paymentcomment from payment where value>0 and reimbursement=0 and clientid="' . $clientid . '"';
  if ($_POST['showoperations'] == 1) { $query = $query . ' and paymentdate>="' . $startdate . '" and paymentdate<="' . $stopdate . '"'; }
  else { $query = $query . ' and matchingid=0'; }
  $query = $query . ' order by paymentdate';
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results > 0) { $outputstring = $outputstring . '<tr><td><b>Payment</b></td><td><b>Date</b></td><td><b>Montant</b></td><td><b>Cheque</td><td><b>Info</b></td></tr>'; }
  $t1 = 0; $t2 = 0;
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $chequeno = $row['chequeno'];
    if ($chequeno != '')
    {
      $chequeno = $bankA[$row['bankid']] . '&nbsp;' . $chequeno;
    }
    $paymentcomment = '';
    if ($row['forinvoiceid'] > 0) { $paymentcomment = $paymentcomment . 'Fact ' . $row['forinvoiceid'] . ' '; }
    $paymentcomment .= $row['paymentcomment'];
    $outputstring = $outputstring . '<tr><td align=right>' . myfix($row['id']) . '</td><td>' . datefix2($row['date']) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td><font size=-1>' . $chequeno . '</font></td><td>' . $paymentcomment . '</td></tr>';
    $totalcredit = $totalcredit + $row['totalprice'];
    $t1 = $t1 + $row['totalprice'];
  }
  if ($t1 > 0) { $outputstring = $outputstring . '<tr><td><i>Total</i></td><td>&nbsp;</td><td align=right><i>' . myfix($t1) . '</i></td><td>&nbsp;</td><td>&nbsp;</td></tr>'; }
  $query = 'select invoicevat,accountingdate as date,invoiceid as id,invoiceprice as totalprice,reference from invoicehistory where cancelledid<1 and isreturn=1 and invoiceprice>0 and clientid="' . $clientid . '"';
  if ($_POST['showoperations'] == 1) { $query = $query . ' and accountingdate>="' . $startdate . '" and accountingdate<="' . $stopdate . '" and confirmed=1'; }
  else { $query = $query . ' and matchingid=0'; }
  $query = $query . ' order by accountingdate';
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results > 0) { $outputstring = $outputstring . '<tr><td><b>Avoir</b></td><td><b>Date</b></td><td><b>Montant</b></td><td><b>dont TVA</b></td><td><b>Référence</b></td></tr>'; }
  $t1 = 0; $t2 = 0;
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $outputstring = $outputstring . '<tr><td align=right>' . myfix($row['id']) . '</td><td>' . datefix2($row['date']) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td align=right>' . myfix($row['invoicevat']) . '</td><td>' . $row['reference'] . '</td></tr>';
    $totalcredit = $totalcredit + $row['totalprice'];
    $t1 = $t1 + $row['totalprice']; $t2 = $t2 + $row['invoicevat'];
  }
  if ($t1 > 0 || $t2 > 0) { $outputstring = $outputstring . '<tr><td><i>Total</i></td><td>&nbsp;</td><td align=right><i>' . myfix($t1) . '</i></td><td align=right><i>' . myfix($t2) . '</i></td><td>&nbsp;</td></tr>'; }
  $query = 'select adjustmentcomment,adjustmentdate as date,adjustmentid as id,value as totalprice from adjustment,adjustmentgroup where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and nomatch=0 and debit=0 and accountingnumberid=1 and referenceid="' . $clientid . '"';
  if ($_POST['showoperations'] == 1) { $query = $query . ' and adjustmentdate>="' . $startdate . '" and adjustmentdate<="' . $stopdate . '"'; }
  else { $query = $query . ' and matchingid=0'; }
  $query = $query . ' order by adjustmentdate';
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results > 0) { $outputstring = $outputstring . '<tr><td><b>Ecriture</b></td><td><b>Date</b></td><td><b>Montant</b></td><td>&nbsp;</td><td><b>Commentaire</b></td></tr>'; }
  $t1 = 0; $t2 = 0;
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $date = $row['date']; if (substr($row['operationdate'],0,4) > 0) { $date = $row['operationdate']; }
    $outputstring = $outputstring . '<tr><td align=right>' . myfix($row['id']) . '</td><td>' . datefix2($date) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td>&nbsp;</td><td>' . $row['adjustmentcomment'] . '</td></tr>';
    $totalcredit = $totalcredit + $row['totalprice'];
    $t1 = $t1 + $row['totalprice'];
  }
  if ($t1 > 0) { $outputstring = $outputstring . '<tr><td><i>Total</i></td><td>&nbsp;</td><td align=right><i>' . myfix($t1) . '</i></td><td>&nbsp;</td><td>&nbsp;</td></tr>'; }
  $outputstring = $outputstring . '</table>';
  ###############
  echo $outputstring;

  ?></td></tr>
  <tr><td colspan="2" align="center">
  </td></tr>
  </table><?php
  echo '<br>';
  echo '<table><tr><td><b>Total debit</b>:</td><td align=right>' . myfix($totaldebit);
  echo '</td></td><td></tr><tr><td><b>Total credit</b>:</td><td align=right>' . myfix($totalcredit);
  echo '</td></td><td></tr>';
  if ($_POST['showoperations'] == 1)
  {
    # do nothing
  }
  else
  {
    echo '<tr><td><b>Balance</b>:</td><td align=right>' . myfix(abs($totalcredit-$totaldebit));
    if ($totalcredit > $totaldebit) { echo '</td><td>(credit)'; }
    if ($totaldebit > $totalcredit) { echo '</td><td>(debit)'; }
    echo '</td></tr>';
  }
  echo '</table></div>';
  echo '<div class="infofact">';
  echo $_SESSION['ds_accountbottom'];
  echo '</div>';
  }
?>