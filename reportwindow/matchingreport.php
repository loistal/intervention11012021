<?php

require('preload/user.php');

$datename = 'startdate'; require('inc/datepickerresult.php');
if ($startdate < '2013-12-25') { $startdate = '2013-12-25'; }
$datename = 'stopdate'; require('inc/datepickerresult.php');
$userid = (int) $_POST['userid'];
require('inc/findclient.php');
if ($clientid <= 0)
{
  echo '<p class=alert>Client non trouvé.</p>';
  exit;
}
$showuserid = 0;

$scrap = 'Info lettrage';
if ($client > 0) { $scrap .= ' client ' .  $clientid . ': ' . d_output($clientname); }
if ($userid > 0)
{
  if ($client > 0) { $scrap .= ','; }
  $scrap .= ' utilisateur ' .  d_output($userA[$userid]);
}
else { $showuserid = 1; }
showtitle($scrap);
echo '<h2>' . $scrap . '</h2>';
echo '<h2>' . datefix($startdate) . ' à ' . datefix($stopdate) . '</h2>';

$query = 'select matchingid,date,userid from matching where clientid=? and date>=? and date<=?';
$query_prm = array($clientid, $startdate, $stopdate);
if ($userid > 0) { $query .= ' and userid=?'; array_push($query_prm,$userid); }
$query .= ' order by date,matchingid';
require('inc/doquery.php');
$main_result = $query_result; unset($query_result); $num_results_main = $num_results;

if ($num_results_main)
{
  echo '<table class=report>';
  for ($i=0;$i<$num_results_main;$i++)
  {
    echo '<thead><th colspan=4>' . $main_result[$i]['matchingid'] . ' - ' . datefix2($main_result[$i]['date']);
    if ($showuserid) { echo ' (' . d_output($userA[$main_result[$i]['userid']]) . ')'; }
    echo '</th></thead><thead><th>Débit</th><th>Montant</th><th>Crédit</th><th>Montant</th></thead><tr><td valign=top>';
    
    $td1 = ''; $td2 = ''; $td3 = ''; $td4 = '';
    $query = 'select invoiceid,invoiceprice from invoicehistory where isreturn=0 and matchingid=?';
    $query_prm = array($main_result[$i]['matchingid']);
    require('inc/doquery.php');
    for ($y=0;$y<$num_results;$y++)
    {
      $td1 .= 'Facture ' . $query_result[$y]['invoiceid'] . '<br>';
      $td2 .= myfix($query_result[$y]['invoiceprice']) . '<br>';
    }
    $query = 'select paymentid,value from payment where reimbursement=1 and matchingid=?';
    $query_prm = array($main_result[$i]['matchingid']);
    require('inc/doquery.php');
    for ($y=0;$y<$num_results;$y++)
    {
      $td1 .= 'Remboursement ' . $query_result[$y]['paymentid'] . '<br>';
      $td2 .= myfix($query_result[$y]['value']) . '<br>';
    }
    $query = 'select adjustmentgroupid,value from adjustment where debit=1 and accountingnumberid=1 and matchingid=?';
    $query_prm = array($main_result[$i]['matchingid']);
    require('inc/doquery.php');
    for ($y=0;$y<$num_results;$y++)
    {
      $td1 .= 'Ecriture ' . $query_result[$y]['adjustmentgroupid'] . '<br>';
      $td2 .= myfix($query_result[$y]['value']) . '<br>';
    }
    $query = 'select paymentid,value from payment where reimbursement=0 and matchingid=?';
    $query_prm = array($main_result[$i]['matchingid']);
    require('inc/doquery.php');
    for ($y=0;$y<$num_results;$y++)
    {
      $td3 .= 'Paiement ' . $query_result[$y]['paymentid'] . '<br>';
      $td4 .= myfix($query_result[$y]['value']) . '<br>';
    }
    $query = 'select invoiceid,invoiceprice from invoicehistory where isreturn=1 and matchingid=?';
    $query_prm = array($main_result[$i]['matchingid']);
    require('inc/doquery.php');
    for ($y=0;$y<$num_results;$y++)
    {
      $td3 .= 'Avoir ' . $query_result[$y]['invoiceid'] . '<br>';
      $td4 .= myfix($query_result[$y]['invoiceprice']) . '<br>';
    }
    $query = 'select adjustmentgroupid,value from adjustment where debit=0 and accountingnumberid=1 and matchingid=?';
    $query_prm = array($main_result[$i]['matchingid']);
    require('inc/doquery.php');
    for ($y=0;$y<$num_results;$y++)
    {
      $td3 .= 'Ecriture ' . $query_result[$y]['adjustmentgroupid'] . '<br>';
      $td4 .= myfix($query_result[$y]['value']) . '<br>';
    }
    
    echo $td1 . '</td><td valign=top align=right>' . $td2 . '</td><td valign=top>' . $td3 . '</td><td valign=top align=right>' . $td4 . '</td></tr>';
  }
  echo '</table>';
}

?>