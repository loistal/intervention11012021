<?php

require('preload/accountinggroup.php');
require('preload/accountingnumber.php');
require('preload/user.php');
require('preload/accounting_simplified.php');
require('preload/employee.php');

$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
$PA['orderby'] = 'uint';
$PA['type'] = 'int';
require('inc/readpost.php');

$clientA = array(); $colspanb = 8; $show_clientcode = 0; $shortenfields = 0; $lastid = -1; $space_lines = 0;
$onlysubtotals = 0; $showtiers = 1; $show_cumul = 1; $showcomments = 0;
$showreconciliation = 0; $showmatching = 0; $extrafields = 0;
$totalcredit = 0; $totaldebit = 0;
$subtotalcredit = 0; $subtotalcredit2 = 0; $subtotaldebit = 0; $subtotaldebit2 = 0;
$showclientname = 0; $orderbyclientid = 1;
$ttd = $ttc = $td = $tc = 0;

$title = d_output($_SESSION['ds_customname']) . ' ' .'Balance des Tiers ' . datefix($startdate,'short') . ' à ' . datefix($stopdate,'short');
showtitle_new($title);
echo '<p>Édité le ',datefix($_SESSION['ds_curdate'],'short'),' à ',substr($_SESSION['ds_curtime'],0,5),'</p>';

$query = 'select adjustmentgroup.accounting_simplifiedid,adjustmentgroup.adjustmentgroupid,userid,adjustmentdate,adjustmentcomment
,reference,debit,integrated,adjustmentcomment_line,value,referenceid,adjustment.accountingnumberid,reconciliationid,matchingid
,acnumber,reconciliationid,clientid,clientname,clientcode
from adjustmentgroup,adjustment,accountingnumber,client
where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
and adjustment.accountingnumberid=accountingnumber.accountingnumberid
and adjustment.referenceid=client.clientid
and adjustmentdate>=? and adjustmentdate<=? and adjustmentgroup.deleted=0 and value>0 and accountingnumber.needreference=1';
$query_prm = array($startdate, $stopdate);
if ($type == 1) { $query .= ' and isclient=1'; }
elseif ($type == 2) { $query .= ' and issupplier=1'; }
elseif ($type == 3) { $query .= ' and isemployee=1'; }
elseif ($type == 4) { $query .= ' and isother=1'; }
if ($orderby == 1) { $query .= ' order by clientname,adjustmentdate,adjustmentgroupid,debit desc'; }
else { $query .= ' order by acnumber,clientname,adjustmentdate,adjustmentgroupid,debit desc'; }
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  $id[$i] = $main_result[$i]['adjustmentgroupid'];
  $username[$i] = $userA[$main_result[$i]['userid']];
  $date[$i] = $main_result[$i]['adjustmentdate'];
  $debit[$i] = $main_result[$i]['debit'];
  $value[$i] = $main_result[$i]['value'];
  $anidA[$i] = $main_result[$i]['accountingnumberid'];
  $integratedA[$i] = $main_result[$i]['integrated'];
  $ref[$i] = $main_result[$i]['referenceid'];
  $clientA[] = $ref[$i];
  $simplified_id[$i] = $main_result[$i]['accounting_simplifiedid'];
  $matchingid[$i] = $main_result[$i]['matchingid'];
  $reconciliationid[$i] = $main_result[$i]['reconciliationid'];
  $adjustmentcomment_line[$i] = $main_result[$i]['adjustmentcomment_line'];
  if ($adjustmentcomment_line[$i] != '') { $showcomments = 1; }
}

$clientA = array_filter(array_unique($clientA));
sort($clientA);
$clientlist = '(';
foreach ($clientA as $temp)
{
  $clientlist .= $temp . ',';
}
$clientlist = rtrim($clientlist,',') . ')';
if ($clientlist == '()') { $clientlist = '(-1)'; }
unset($clientA);
$query = 'select clientid,clientname,clientcode from client where clientid in '.$clientlist;
$query_prm = array();
require('inc/doquery.php');
$client_result = $query_result; $num_results_client= $num_results;
for ($i = 0; $i < $num_results_client; $i++)
{
  if ($show_clientcode)
  {
    if ($client_result[$i]['clientcode'] != '')
    { $clientA[$client_result[$i]['clientid']] = $client_result[$i]['clientcode']; }
    else { $clientA[$client_result[$i]['clientid']] = $client_result[$i]['clientid']; }
  }
  else { $clientA[$client_result[$i]['clientid']] = d_decode($client_result[$i]['clientname']); }
  /*$query = 'select referencenumber from employee where employee_is_clientid=?'; # TODO check if needed
  $query_prm = array($client_result[$i]['clientid']);
  require('inc/doquery.php');
  if ($num_results)
  {
    $clientA[$client_result[$i]['clientid']] = $query_result[0]['referencenumber'];
  }*/
}

echo '<table class=report STYLE="min-width: 800px">';
if ($orderby == 2) { echo '<tr><th rowspan=2>Compte'; }
echo '<th rowspan=2>Tiers<th colspan=2>Total<th colspan=2>Solde
<tr><th>Débit<th>Crédit<th>Débit<th>Crédit';

for ($i = 0; $i < $num_results_main; $i++)
{
  if (isset($id[$i]))
  {
    if ($id[$i] != $lastid)
    {
      if ($space_lines && $i > 0) { echo '<tr><td colspan=100>&nbsp;'; }
    }
    if ($debit[$i]) { $subtotaldebit2 = d_add($subtotaldebit2,$value[$i]); }
    else { $subtotalcredit2 = d_add($subtotalcredit2,$value[$i]); }
    if (!isset($ref[($i+1)]) || $ref[$i] != $ref[($i+1)])
    {
      echo d_tr(0);
      if ($orderby == 2)
      {
        $output = $accountingnumber_longA[$anidA[$i]];
        if ($shortenfields) { $output = $accountingnumberA[$anidA[$i]]; }
        echo '<td>',$output;
      }
      echo '<td>'; if (isset($clientA[$ref[$i]])) { echo $clientA[$ref[$i]]; }
      echo '<td align=right>' . myfix($subtotaldebit2);
      echo '<td align=right>' . myfix($subtotalcredit2);
      $c = $d = 0;
      if ($subtotaldebit2 > $subtotalcredit2) { $d = $subtotaldebit2 - $subtotalcredit2; $td += $d; }
      elseif ($subtotalcredit2 > $subtotaldebit2) { $c = $subtotalcredit2 - $subtotaldebit2; $tc += $c; }
      echo d_td($d, 'currency');
      echo d_td($c, 'currency');
      $ttd += $subtotaldebit2; $ttc += $subtotalcredit2;
      $subtotaldebit2 = 0; $subtotalcredit2 = 0;
    }
    if ($orderby == 2 && (!isset($anidA[($i+1)]) || $anidA[$i] != $anidA[($i+1)])
    || $orderby == 1 && !isset($anidA[($i+1)]))
    {
      $colspan = 1; if ($orderby == 2) { $colspan++; }
      echo d_tr(1),'<td colspan='.$colspan.'><b>' . $accountingnumber_longA[$anidA[$i]];
      echo '<td align=right><b>' . myfix($ttd);
      echo '<td align=right><b>' . myfix($ttc);
      echo '<td align=right><b>' . myfix($td);
      echo '<td align=right><b>' . myfix($tc);
      $ttd = $ttc = $td = $tc = 0;
    }
    $lastid = $id[$i];
  }
}
echo '</table>';

?>