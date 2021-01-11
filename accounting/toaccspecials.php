<?php

if ($_POST['saveme'] == 1)
{
  echo 'Valeurs modifiée.';
  $query = 'update globalvariables_accounting set benefit_accountinggroupid=?,loss_accountinggroupid=?,benefit_accountingnumberid=?,loss_accountingnumberid=? where primaryunique=1';
  $query_prm = array($_POST['accountinggroupbenefitid'], $_POST['accountinggrouplossid'],$_POST['accountingnumberbenefitid'], $_POST['accountingnumberlossid']);
  require('inc/doquery.php');
}

$query = 'select benefit_accountinggroupid,loss_accountinggroupid,benefit_accountingnumberid,loss_accountingnumberid from globalvariables_accounting where primaryunique=1';
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

echo '<h2>Comptes clôture</h2>
<form method="post" action="accounting.php"><table class=report>
<thead><td>Type<td>Compte</thead>';

echo d_tr();
echo d_td_old('Groupe bénéfice');
$dp_itemname = 'accountinggroup'; $dp_selectedid = $main_result[0]['benefit_accountinggroupid']; $dp_addtoid = 'benefit'; require('inc/selectitem.php');

echo d_tr();
echo d_td_old('Groupe perte');
$dp_itemname = 'accountinggroup'; $dp_selectedid = $main_result[0]['loss_accountinggroupid']; $dp_addtoid = 'loss'; require('inc/selectitem.php');

echo d_tr();
echo d_td_old('Compte bénéfice');
$dp_itemname = 'accountingnumber'; $dp_selectedid = $main_result[0]['benefit_accountingnumberid']; $dp_addtoid = 'benefit'; require('inc/selectitem.php');

echo d_tr();
echo d_td_old('Compte perte');
$dp_itemname = 'accountingnumber'; $dp_selectedid = $main_result[0]['loss_accountingnumberid']; $dp_addtoid = 'loss'; require('inc/selectitem.php');

echo '</table>
<input type=hidden name="saveme" value="1"><input type=hidden name="accountingmenu" value="'. $accountingmenu .'">
<input type="submit" value="Valider"></form>';

?>