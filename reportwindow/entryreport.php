<?php

if (!function_exists('showtitle_new'))
{
  function showtitle_new($title) # TODO each report MUST use showtitle()
  {
    echo '<title>'.d_output($_SESSION['ds_customname']).' ',d_output($title),'</title><h2>',$title,'</h2>';
  }
}

# cannot use d_td, colors alternate by group (TODO add param to d_td to manually input odd/even)
if ($_SESSION['ds_nbtablecolors'] > 1)
{
  echo '<style>
  tr.odd {
    background-color : '.$_SESSION['ds_tablecolor1'].'; 
  }
  tr.even {
    background-color : '.$_SESSION['ds_tablecolor2'].'; 
  }
  </style>';
}

require('preload/accountinggroup.php');
require('preload/accountingnumber.php');
require('preload/user.php');
require('preload/accounting_simplified.php');
require('preload/employee.php');

$PA['show_clientcode'] = 'int';
$PA['integrated5_anid'] = 'int';
$PA['issupplierid'] = 'uint';
$PA['special_accountinggroupid'] = '';
$PA['todate'] = 'int';
$PA['alldates'] = 'int';
$PA['shortenfields'] = 'int';
$PA['space_lines'] = 'int';
$PA['extrafields'] = 'int';
$PA['numbered_extrafields'] = 'uint';
$PA['integrated'] = 'int';
$PA['journalid'] = 'int';
$PA['adjustmentgroup_tagid'] = 'int';
$PA['accountingnumberid'] = 'int';
$PA['accountingnumbertoid'] = 'int';
$PA['id'] = '';
$PA['idto'] = '';
$PA['accounting_simplifiedid'] = 'int';
$PA['accounting_simplifiedgroupid'] = 'int';
$PA['adjustmentcomment'] = '';
$PA['reference'] = '';
$PA['userid'] = 'int';
$PA['accountinggroupid'] = 'int';
$PA['amount'] = 'decimal';
$PA['amountto'] = 'decimal';
$PA['matched'] = 'int';
$PA['closing'] = 'int';
$PA['reconciled'] = 'int';
$PA['needreference'] = 'uint';
$PA['orderby'] = 'int';
require('inc/readpost.php');

if (!isset($_POST['reconciled'])) { $reconciled = -1; }
if (!isset($_POST['closing'])) { $closing = -1; }
if (!isset($_POST['matched']) && !isset($_GET['matched'])) { $matched = -1; }
if (!isset($_POST['accounting_simplifiedgroupid'])) { $accounting_simplifiedgroupid = -1; }
$amount2 = $amountto;
if (d_compare($amount2, '0') == 1)
{
  if (d_compare($amount, '0') && d_compare($amount2, $amount) == 1)
  {
    # ok
  }
  else
  {
    $amount2 = 0;
  }
}

$shortenfields_length = 10; $showcomments = 0; $anid2 = -1;
$lastid = -1; $showtiers = 0; $colspan = 2; $colspanb = 5; $odd_even = 'even'; $showreconciliation = 0; $showmatching = 0; $showclientname = 0;
$onlysubtotals = 0; $totaldebit = 0; $totalcredit = 0; $subtotaldebit = 0; $subtotalcredit = 0;
$clientstring = ''; $orderbyclientid = 0; $subtotaldebit2 = 0; $subtotalcredit2 = 0;
$totalcumuldebit = 0; $totalcumulcredit = 0; $grandlivre = 0; $result = 0;
if (!isset($startdate))
{
  $datename = 'startdate'; require('inc/datepickerresult.php');
  $datename = 'stopdate'; require('inc/datepickerresult.php');
}
if ($todate == 1) { $startdate = $_SESSION['ds_startyear'] . '-01-01'; $stopdate = $_SESSION['ds_curdate']; }
if ($alldates == 1) { $startdate = $_SESSION['ds_startyear'] . '-01-01'; $stopdate = $_SESSION['ds_endyear'] . '-12-31';; }
if (isset($_POST['accountingnumber']))
{
  $query = 'select accountingnumberid from accountingnumber where acnumber=?';
  $query_prm = array($_POST['accountingnumber']);
  require('inc/doquery.php');
  if ($num_results) { $anid = $query_result[0]['accountingnumberid']; }
  else { $anid = -1; }
  if (isset($_POST['accountingnumberto']))
  {
    $query = 'select accountingnumberid from accountingnumber where acnumber=?';
    $query_prm = array($_POST['accountingnumberto']);
    require('inc/doquery.php');
    if ($num_results) { $anid2 = $query_result[0]['accountingnumberid']; }
  }
}
else
{
  $anid = $accountingnumberid;
  $anid2 = $accountingnumbertoid;
}
if ($anid > 0)
{
  if ($accountingnumber_isbankA[$anid]) { $showreconciliation = 1; }
  if ($accountingnumber_matchableA[$anid]) { $showmatching = 1; }
}
if ($anid2 > 0)
{
  if ($anid > 0 && $accountingnumberA[$anid2] > $accountingnumberA[$anid])
  {
    $showreconciliation = 0;
    $showmatching = 0;
  }
  else
  {
    $anid2 = 0;
  }
}
$adjustmentgroupid = $id;
$adjustmentgroupid2 = $idto;
if ($adjustmentgroupid2 > 0)
{
  if ($adjustmentgroupid > 0 && $adjustmentgroupid2 > $adjustmentgroupid)
  {
    # ok
  }
  else
  {
    $adjustmentgroupid2 = 0;
  }
}

if (isset($_POST['client']) && $_POST['client'] != '')
{
  require('inc/findclient.php');
  if ($clientid > 0) { $showmatching = 1; }
  elseif ($num_clients > 0) { $clientstring = $_POST['client']; }
}
if (isset($_POST['isclientid']) && $_POST['isclientid'] > 0)
{
  $client = $_POST['isclientid'];
  require('inc/findclient.php');
  $showmatching = 1;
}
if ($issupplierid > 0)
{
  $client = $issupplierid;
  require('inc/findclient.php');
  $showmatching = 1;
}
if (!isset($clientid)) { $clientid = -2; }

if ($orderby == 3) { $orderby = 2; $onlysubtotals = 1; }
if ($orderby == 4) { $orderby = 2; $orderbyclientid = 1; }

$special_accountinggroup_list = '';
if ($special_accountinggroupid == '40_41_42')
{
  $query = 'select accountingnumberid from accountingnumber where (acnumber like "40%" or acnumber like "41%" or acnumber like "42%")';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i = 0; $i < $num_results; $i++)
  {
    if ($i > 0) { $special_accountinggroup_list .= ','; }
    $special_accountinggroup_list .= $query_result[$i]['accountingnumberid'];
  }
}
elseif ($special_accountinggroupid == '512xxx')
{
  $query = 'select accountingnumberid from accountingnumber where acnumber like "512%"';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i = 0; $i < $num_results; $i++)
  {
    if ($i > 0) { $special_accountinggroup_list .= ','; }
    $special_accountinggroup_list .= $query_result[$i]['accountingnumberid'];
  }
}
elseif ($special_accountinggroupid == '401000/404000')
{
  $query = 'select accountingnumberid from accountingnumber where acnumber="401000" or acnumber="401000"';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i = 0; $i < $num_results; $i++)
  {
    if ($i > 0) { $special_accountinggroup_list .= ','; }
    $special_accountinggroup_list .= $query_result[$i]['accountingnumberid'];
  }
}

if ($extrafields)
{
  $showmatching = 1;
  $showreconciliation = 1;
  require('preload/accounting_simplifiedgroup.php');
}

if ($_SESSION['ds_reconciliation_type'] == 0) { $showreconciliation = 0; } 

if ($integrated == 5 && $integrated5_anid > 0)
{
  $integrated5_list_agidA = array();
  $query = 'select adjustmentgroup.adjustmentgroupid from adjustment,adjustmentgroup
  where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and adjustmentdate>=? and adjustmentdate<=? and adjustmentgroup.deleted=0 and value>0
  and accountingnumberid=?';
  $query_prm = array($startdate, $stopdate, $integrated5_anid);
  require('inc/doquery.php');
  for ($i=0;$i<$num_results;$i++)
  {
    $integrated5_list_agidA[$i] = $query_result[$i]['adjustmentgroupid'];
  }
  $integrated5_list_agid = '(';
  foreach ($integrated5_list_agidA as $kladd)
  {
    $integrated5_list_agid .= $kladd . ',';
  }
  $integrated5_list_agid = rtrim($integrated5_list_agid,',') . ')';
  if ($integrated5_list_agid == '()') { $integrated5_list_agid = '(-1)'; }
}

$query = 'select adjustmentgroup.accounting_simplifiedid,adjustmentgroup.adjustmentgroupid,userid,adjustmentdate,adjustmentcomment
,reference,debit,integrated,adjustmentcomment_line
,value,referenceid,adjustment.accountingnumberid,reconciliationid,matchingid,acnumber,reconciliationid';
if ($clientstring != '') { $query .= ',clientname'; }
$query .= ' from adjustmentgroup,adjustment,accountingnumber';
if ($clientstring != '') { $query .= ',client'; }
if ($accounting_simplifiedgroupid > 0) { $query .= ',accounting_simplified'; }
$query .= ' where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and adjustment.accountingnumberid=accountingnumber.accountingnumberid';
if ($clientstring != '') { $query .= ' and adjustment.referenceid=client.clientid'; }
if ($accounting_simplifiedgroupid > 0) { $query .= ' and adjustmentgroup.accounting_simplifiedid=accounting_simplified.accounting_simplifiedid'; }
$query .= ' and adjustmentdate>=? and adjustmentdate<=? and adjustmentgroup.deleted=0 and value>0';
$query_prm = array($startdate, $stopdate);
if ($integrated >= 0)
{
  $query .= ' and integrated=?'; array_push($query_prm, $integrated);
  if ($integrated == 5 && $integrated5_anid > 0)
  {
    $query .= ' and adjustmentgroup.adjustmentgroupid in ' . $integrated5_list_agid;
  }
}
if ($journalid >= 0) { $query .= ' and adjustmentgroup.journalid=?'; array_push($query_prm, $journalid); }
if ($adjustmentgroup_tagid >= 0) { $query .= ' and adjustmentgroup.adjustmentgroup_tagid=?'; array_push($query_prm, $adjustmentgroup_tagid); }
if ($anid2 > 0) { $query .= ' and acnumber>=? and acnumber<=?'; array_push($query_prm, $accountingnumberA[$anid], $accountingnumberA[$anid2]); }
elseif ($anid > 0) { $query .= ' and adjustment.accountingnumberid=?'; array_push($query_prm, $anid); }
if ($needreference) { $query .= ' and accountingnumber.needreference=1'; }
if ($accounting_simplifiedid >= 0) { $query .= ' and adjustmentgroup.accounting_simplifiedid=?'; array_push($query_prm, $accounting_simplifiedid); }
if ($accounting_simplifiedgroupid > 0) { $query .= ' and accounting_simplifiedgroupid=?'; array_push($query_prm, $accounting_simplifiedgroupid); }
elseif ($accounting_simplifiedgroupid == 0) { $query .= ' and adjustmentgroup.accounting_simplifiedid=0'; }
if ($adjustmentgroupid2 > 0) { $query .= ' and adjustment.adjustmentgroupid>=? and adjustment.adjustmentgroupid<=?'; array_push($query_prm, $adjustmentgroupid, $adjustmentgroupid2); }
elseif ($adjustmentgroupid > 0) { $query .= ' and adjustment.adjustmentgroupid=?'; array_push($query_prm, $adjustmentgroupid); }
if ($amount2 > 0) { $query .= ' and value>=? and value<=?'; array_push($query_prm, $amount, $amount2); }
elseif ($amount > 0) { $query .= ' and value=?'; array_push($query_prm, $amount); }
if ($adjustmentcomment != '') { $query .= ' and adjustmentcomment like ?'; array_push($query_prm, '%'.$adjustmentcomment.'%'); }
if ($reference != '') { $query .= ' and reference like ?'; array_push($query_prm, '%'.$reference.'%'); }
if ($clientid > 0) { $query .= ' and referenceid=?'; array_push($query_prm, $clientid); }
elseif ($clientstring != '') { $query .= ' and clientname like ?'; array_push($query_prm, '%'.d_encode($clientstring).'%'); }
if ($userid > 0) { $query .= ' and userid=?'; array_push($query_prm, $userid); }
if ($accountinggroupid > 0) { $query .= ' and accountinggroupid=?'; array_push($query_prm, $accountinggroupid); }
if ($special_accountinggroup_list != '') { $query .= ' and adjustment.accountingnumberid in ('.$special_accountinggroup_list.')'; }
if ($matched == 0) { $query .= ' and (matchingid=0 or matchingid is null)'; } # some old installations errenously allow null in this field
if ($matched > 0) { $query .= ' and matchingid>0'; }
if ($closing == 0) { $query .= ' and closing=0'; }
if ($closing > 0) { $query .= ' and closing>0'; }
if ($reconciled == 0) { $query .= ' and reconciliationid=0'; }
if ($reconciled > 0) { $query .= ' and reconciliationid>0'; }
if ($orderby == 2) { $query .= ' order by acnumber,referenceid,adjustmentdate,adjustmentgroupid,debit desc'; }
else { $query .= ' order by adjustmentdate,adjustmentgroupid,acnumber,debit desc'; }
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i = 0; $i < $num_results_main; $i++)
{
  if ($integrated == 5 && $integrated5_anid > 0 && $main_result[$i]['referenceid'] == 0)
  {
    # TODO merge all entries that do not take referenceid
    if ($main_result[$i]['debit'])
    {
      # this should not happen
      echo 'error';
      if (!isset($merged_dA[$main_result[$i]['accountingnumberid']])) { $merged_dA[$main_result[$i]['accountingnumberid']] = 0; }
      $merged_dA[$main_result[$i]['accountingnumberid']] += $main_result[$i]['value'];
    }
    else
    {
      if (!isset($merged_cA[$main_result[$i]['accountingnumberid']])) { $merged_cA[$main_result[$i]['accountingnumberid']] = 0; }
      $merged_cA[$main_result[$i]['accountingnumberid']] += $main_result[$i]['value'];
    }
  }
  else
  {
    $idA[$i] = $main_result[$i]['adjustmentgroupid'];
    $username[$i] = $userA[$main_result[$i]['userid']];
    $date[$i] = $main_result[$i]['adjustmentdate'];
    $debit[$i] = $main_result[$i]['debit'];
    $value[$i] = $main_result[$i]['value'];
    $anidA[$i] = $main_result[$i]['accountingnumberid'];
    $integratedA[$i] = $main_result[$i]['integrated'];
    $ref[$i] = '';
    if ($main_result[$i]['referenceid'] > 0)
    { 
      $showtiers = 1;
      $ref[$i] = $main_result[$i]['referenceid'];
    }
    $simplified_id[$i] = $main_result[$i]['accounting_simplifiedid'];
    $matchingid[$i] = $main_result[$i]['matchingid'];
    $reconciliationid[$i] = $main_result[$i]['reconciliationid'];
    $adjustmentcomment_line[$i] = $main_result[$i]['adjustmentcomment_line'];
    if ($adjustmentcomment_line[$i] != '') { $showcomments = 1; }
  }
}

$ourparams = '';
if ($journalid == 0) { $ourparams .= '<p>Hors journal</p>'; }
elseif ($journalid > 0)
{
  require('preload/journal.php');
  $ourparams .= '<p>Journal: ' . $journalA[$journalid] . '</p>';
}
if ($adjustmentgroup_tagid == 0) { $ourparams .= '<p>Hors '.$_SESSION['ds_term_accounting_tag'].'</p>'; }
elseif ($adjustmentgroup_tagid > 0)
{
  require('preload/adjustmentgroup_tag.php');
  $ourparams .= '<p>'.$_SESSION['ds_term_accounting_tag'].' : ' . $adjustmentgroup_tagA[$adjustmentgroup_tagid] . '</p>';
}
if ($anid2 > 0) { $ourparams .= '<p>Comptes: ' . $accountingnumberA[$anid] . ' à ' . $accountingnumberA[$anid2] . '</p>'; }
elseif ($anid > 0) { $ourparams .= '<p>Compte: ' . $accountingnumberA[$anid] . '</p>'; }
if ($special_accountinggroupid != '') { $ourparams .= '<p>Comptes: ' . $special_accountinggroupid . '</p>'; }
if ($accounting_simplifiedid == 0) { $ourparams .= '<p>Hors menu simplifié</p>'; }
elseif ($accounting_simplifiedid > 0)
{
  require('preload/accounting_simplified.php');
  $ourparams .= '<p>Modèle simplifié: ' . $accounting_simplifiedA[$accounting_simplifiedid] . '</p>';
}
if ($accounting_simplifiedgroupid == 0) { $ourparams .= '<p>Hors menu simplifié</p>'; }
elseif ($accounting_simplifiedgroupid > 0)
{
  require('preload/accounting_simplifiedgroup.php');
  $ourparams .= '<p>Modèle simplifié: ' . $accounting_simplifiedgroupA[$accounting_simplifiedgroupid] . '</p>';
}
if ($adjustmentgroupid2 > 0) { $ourparams .= '<p>Écritures: ' . $adjustmentgroupid . ' à ' . $adjustmentgroupid2 . '</p>'; }
elseif ($adjustmentgroupid > 0) { $ourparams .= '<p>Écriture: ' . $adjustmentgroupid . '</p>'; }
if ($accountinggroupid > 0) { require('preload/accountinggroup.php'); $ourparams .= '<p>Groupe: ' . $accountinggroupA[$accountinggroupid] . '</p>'; }
if ($amount2 > 0) { $ourparams .= '<p>Montant: ' . d_output($amount) . ' à ' . d_output($amount2) . '</p>'; }
elseif ($amount > 0) { $ourparams .= '<p>Montant: ' . d_output($amount) . '</p>'; }
if ($adjustmentcomment != '') { $ourparams .= '<p>'.$_SESSION['ds_term_accounting_comment'].': "' . d_output($adjustmentcomment) . '"</p>'; }
if ($reference != '') { $ourparams .= '<p>'.$_SESSION['ds_term_accounting_reference'].': "' . d_output($reference) . '"</p>'; }
if ($clientid > 0) { $ourparams .= '<p>Tiers: ' . d_output($clientname) . '</p>'; }
elseif ($clientstring != '') { $ourparams .= '<p>Tiers: "' . d_output($clientstring) . '"</p>'; }
if ($userid > 0) { $ourparams .= '<p>Utilisateur: ' . $userA[$userid] . '</p>'; }
if ($matched == 0) { $ourparams .= '<p>Non Lettrées</p>'; }
if ($matched == 1) { $ourparams .= '<p>Lettrées</p>'; }
if ($closing == 0) { $ourparams .= '<p>A nouveau exclu</p>'; }
if ($closing == 1) { $ourparams .= '<p>A nouveau</p>'; }
if ($reconciled == 0) { $ourparams .= '<p>Non Rapprochées</p>'; }
if ($reconciled == 1) { $ourparams .= '<p>Rapprochées</p>'; }

$title = d_output($_SESSION['ds_customname']);
if ($onlysubtotals == 1) { $title .= ' Balance '; }
elseif ($orderby == 2 && $ourparams == '') { $title .= ' Grand Livre '; $grandlivre = 1; $orderbyclientid = 1; }
else { $title .= ' Écritures '; }
$title .= datefix2($startdate) . ' à ' . datefix2($stopdate);
showtitle_new($title);
echo '<p>Édité le ',datefix($_SESSION['ds_curdate'],'short'),' à ',substr($_SESSION['ds_curtime'],0,5),'</p>';
echo $ourparams;

if ($showtiers == 1)
{
  $query = 'select clientid,clientname,clientcode from client';
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
    $query = 'select referencenumber from employee where employee_is_clientid=?';
    $query_prm = array($client_result[$i]['clientid']);
    require('inc/doquery.php');
    if ($num_results && $query_result[0]['referencenumber'] != '')
    {
      $clientA[$client_result[$i]['clientid']] = $query_result[0]['referencenumber'];
    }
  }
}
if ($onlysubtotals == 1)
{
  echo '<table class=report STYLE="min-width: 800px"><thead><th>Compte</th><th>Cumul Débit</th><th>Cumul Crédit</th><th>Solde Débit</th><th>Solde Crédit</th></thead>';
  $colspanb = 1;
}
else
{
  echo '<table class=report STYLE="min-width: 800px"><thead><th>Ecriture</th><th>Date</th>';
  if ($showtiers == 1) { echo '<th>Tiers</th>'; $colspanb++; }
  echo '<th>Compte</th><th>'.$_SESSION['ds_term_accounting_comment'].'</th><th>'.$_SESSION['ds_term_accounting_reference'].'</th>';
  echo '<th>Utilisateur</th>'; $colspan++; $colspanb++;
  echo '<th>Débit</th><th>Crédit</th>';
  if ($showcomments) { echo '<th>Infos</th>'; }
  if ($grandlivre == 1) { echo '<th>Solde Cumulé</th>'; }
  if ($showreconciliation == 1) { echo '<th>Rapprochement</th>'; }
  if ($showmatching == 1) { echo '<th>Lettrage</th>'; }
  if ($extrafields) { echo '<td colspan=2>Modèle simplifié'; }
  echo '</thead>';
}
for ($i = 0; $i < $num_results_main; $i++)
{
  if (isset($idA[$i]))
  {
    if ($idA[$i] != $lastid)
    {
      if ($space_lines && $i > 0) { echo '<tr><td colspan=100>&nbsp;'; }
      if ($odd_even == 'odd') { $odd_even = 'even'; }
      else { $odd_even = 'odd'; }
    }
    if ($onlysubtotals == 0)
    {
      if ($orderby == 2) { echo d_tr(); }
      else { echo '<tr class="' . $odd_even . '">'; }
      if ($idA[$i] == $lastid && $orderby != 2)
      {
        if ($showtiers == 1)
        {
          echo '<td colspan=2>';
          $output = '';
          if ($showclientname == 1 || $show_clientcode) { $output = d_output($clientA[$ref[$i]]); }
          elseif ($ref[$i] > 0) { $output = d_output($clientA[$ref[$i]]); } #  . ' (' . $ref[$i] . ')'
          if ($shortenfields) { $output = mb_substr($output, 0, $shortenfields_length); }
          echo '<td>',$output;
          $output = $accountingnumber_longA[$anidA[$i]];
          if ($shortenfields) { $output = $accountingnumberA[$anidA[$i]]; }
          echo '<td>', $output;
          echo '<td colspan=' . $colspan . '>';
        }
        else { echo '<td colspan=2><td>' . $accountingnumber_longA[$anidA[$i]] . '</td><td colspan=' . $colspan . '>'; }
      }
      else
      {
        if ($simplified_id[$i] > 0)
        {
          # simplified
          $link = 'accounting.php?accountingmenu=simplified&modify=1&accountingmenu_sa=simplified&simplified_currentid='.$accounting_simplifiedgroupidA[$simplified_id[$i]].'&asid='.$simplified_id[$i].'&agid='.$idA[$i];
        }
        else
        {
          $link = 'accounting.php?accountingmenu=entry&accountingmenu_sa=simplified&readme=1&agid='.$idA[$i];
        }
        echo '<td align=right><a href="'.$link.'" target=_blank>' . $idA[$i] . '</a></td><td>' . datefix2($date[$i]) . '</td>';
        if ($showtiers == 1)
        {
          $output = '';
          if ($showclientname == 1 || $show_clientcode) { $output = d_output($clientA[$ref[$i]]); }
          elseif ($ref[$i] > 0) { $output = d_output($clientA[$ref[$i]]); } #  . ' (' . $ref[$i] . ')'
          if ($shortenfields) { $output = mb_substr($output, 0, $shortenfields_length); }
          echo '<td>',$output;
        }
        $output = $accountingnumber_longA[$anidA[$i]];
        if ($shortenfields) { $output = $accountingnumberA[$anidA[$i]]; }
        echo '<td>', $output;
        $output = d_output($main_result[$i]['adjustmentcomment']);
        if ($shortenfields) { $output = mb_substr($output, 0, $shortenfields_length); }
        echo '<td>', $output;
        $output = d_output($main_result[$i]['reference']);
        if ($shortenfields) { $output = mb_substr($output, 0, $shortenfields_length); }
        echo '<td>', $output;
        echo '<td>' . d_output($username[$i]) . '</td>';
      }
    }
    if ($debit[$i])
    {
      if ($onlysubtotals == 0) { echo '<td align=right>' . myfix($value[$i]) . '</td><td></td>'; }
      $totaldebit = d_add($totaldebit,$value[$i]);
      $subtotaldebit = d_add($subtotaldebit,$value[$i]);
      if ($ref[$i] > 0) { $subtotaldebit2 = d_add($subtotaldebit2,$value[$i]); }
      ###
      if ($accountingnumber_accountinggroupidA[$anidA[$i]] == 6) { $result -= $value[$i]; }
      if ($accountingnumber_accountinggroupidA[$anidA[$i]] == 7) { $result -= $value[$i]; }
      ###
    }
    else
    {
      if ($onlysubtotals == 0) { echo '<td>&nbsp;</td><td align=right>' . myfix($value[$i]) . '</td>'; }
      $totalcredit = d_add($totalcredit,$value[$i]);
      $subtotalcredit = d_add($subtotalcredit,$value[$i]);
      if ($ref[$i] > 0) { $subtotalcredit2 = d_add($subtotalcredit2,$value[$i]); }
      ###
      if ($accountingnumber_accountinggroupidA[$anidA[$i]] == 6) { $result += $value[$i]; }
      if ($accountingnumber_accountinggroupidA[$anidA[$i]] == 7) { $result += $value[$i]; }
      ###
    }
    if ($grandlivre == 1)
    {
      echo '<td align=right>' . myfix($subtotaldebit - $subtotalcredit);
    }
    if ($onlysubtotals == 0)
    {
      if ($showcomments) { echo '<td>',$adjustmentcomment_line[$i]; }
      if ($showreconciliation == 1)
      {
        if ($main_result[$i]['reconciliationid'] > 0)
        {
          if ($numbered_extrafields) { echo '<td align=center>'.myfix($main_result[$i]['reconciliationid']); }
          else { echo '<td align=center>&radic;'; }
        }
        else { echo '<td>'; }
      }
      if ($showmatching == 1)
      {
        if ($main_result[$i]['matchingid'] > 0)
        {
          if ($numbered_extrafields) { echo '<td align=center>'.myfix($main_result[$i]['matchingid']); }
          else { echo '<td align=center>&radic;'; }
        }
        else { echo '<td>'; }
      }
      if ($extrafields)
      {
        echo '<td>'; if ($simplified_id[$i] > 0
        && isset($accounting_simplifiedgroupA[$accounting_simplifiedgroupidA[$simplified_id[$i]]]))
        { echo $accounting_simplifiedgroupA[$accounting_simplifiedgroupidA[$simplified_id[$i]]]; }
        echo '<td>'; if (isset($accounting_simplifiedA[$simplified_id[$i]]))
        { echo $accounting_simplifiedA[$simplified_id[$i]]; }
      }
      echo '</tr>';
    }
    if ($orderbyclientid == 1 && $orderby == 2 && $ref[$i] > 0 && (!isset($ref[($i+1)]) || $ref[$i] != $ref[($i+1)]))
    {
      echo d_tr(1),'<td colspan=2><td colspan=5><b>';
      echo $clientA[$ref[$i]];
      echo '<td align=right><b>' . myfix($subtotaldebit2);
      echo '<td align=right><b>' . myfix($subtotalcredit2);
      if ($onlysubtotals == 0)
      {
        if ($showreconciliation == 1) { echo '<td>'; }
        if ($showmatching == 1) { echo '<td>'; }
      }
      $subtotaldebit2 = 0; $subtotalcredit2 = 0;
      if ($grandlivre == 1)
      {
        echo '<td align=right><b>' . myfix($subtotaldebit - $subtotalcredit);
        $subtotaldebit = $subtotalcredit = 0;
      }
    }
    if ($orderby == 2 && (!isset($anidA[($i+1)]) || $anidA[$i] != $anidA[($i+1)]))
    {
      echo d_tr(1),'<td colspan='.$colspanb.'><b>' . $accountingnumber_longA[$anidA[$i]];
      echo '<td align=right><b>' . myfix($subtotaldebit);
      echo '<td align=right><b>' . myfix($subtotalcredit);
      if ($grandlivre == 1)
      {
        echo '<td align=right><b>' . myfix($subtotaldebit - $subtotalcredit);
      }
      if ($onlysubtotals == 1)
      {
        if (d_compare($subtotaldebit, $subtotalcredit) == 1) # gt
        {
          echo '<td align=right><b>' . myfix(d_subtract($subtotaldebit,$subtotalcredit));
          echo '<td align=right>';
          $totalcumuldebit = d_add($totalcumuldebit, d_subtract($subtotaldebit,$subtotalcredit));
        }
        elseif (d_compare($subtotaldebit, $subtotalcredit) == -1) # lt
        {
          echo '<td align=right>';
          echo '<td align=right><b>' . myfix(d_subtract($subtotalcredit,$subtotaldebit));
          $totalcumulcredit = d_add($totalcumulcredit, d_subtract($subtotalcredit,$subtotaldebit));
        }
        else { echo '<td align=right><td align=right>'; }
      }
      if ($onlysubtotals == 0)
      {
        if ($showreconciliation == 1) { echo '<td>'; }
        if ($showmatching == 1) { echo '<td>'; }
      }
      $subtotaldebit = 0; $subtotalcredit = 0;
    }
    $lastid = $idA[$i];
  }
}
if ($integrated == 5) # consider this BETA 2019 06 12
{
  foreach ($merged_cA as $anid => $value)
  {
    echo '<tr><td colspan=2>Regroupement<td><td>',$accountingnumber_longA[$anid];
    echo '<td><td><td><td><td align=right>',myfix($value),'<td>';
    $totalcredit += $value;
  }
}
echo '<tr><td colspan='.$colspanb.'><b>Total';
echo '<td align=right><b>' .  myfix($totaldebit) . '<td align=right><b>' .  myfix($totalcredit);
if ($onlysubtotals == 1)
{
  echo '<td align=right><b>' .  myfix($totalcumuldebit) . '<td align=right><b>' .  myfix($totalcumulcredit);
}
if ($grandlivre == 1) { echo '<td>'; }
if ($onlysubtotals == 0)
{
  if ($showcomments) { echo '<td>'; }
  if ($showreconciliation == 1) { echo '<td>'; }
  if ($showmatching == 1) { echo '<td>'; }
}
if (d_compare($totaldebit,$totalcredit) != 0)
{
  if (d_compare($totaldebit,$totalcredit) == 1)
  {
    echo '<tr><td colspan='.$colspanb.'><b>Total débiteur<td align=right><b>' . myfix(d_subtract($totaldebit,$totalcredit));
    $colspanend = 1;
  }
  else
  {
    echo '<tr><td colspan='.($colspanb+1).'><b>Total créditeur<td align=right><b>' . myfix(d_subtract($totalcredit,$totaldebit));
    $colspanend = 0;
  }
  if ($onlysubtotals == 0)
  {
    if ($showcomments) { $colspanend++; }
    if ($showreconciliation == 1) { $colspanend++; }
    if ($showmatching == 1) { $colspanend++; }
    if ($colspanend > 0) { echo '<td colspan='.$colspanend.'>'; }
  }
}
echo '</table>';

if ($onlysubtotals == 1 && $result != 0) { echo '<p>Résultat: '.myfix($result) .'</p>'; }

?>