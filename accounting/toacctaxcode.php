<?php

# TODO important define 411000 instead of hardcode (hardcoded all over the place, many different files/modules)

if (isset($_POST['saveme']) && $_POST['saveme'] == 1)
{
  $query = 'select * from taxcode order by taxcodeid';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($y = 0; $y < $num_results_main; $y++)
  {
    $i = $main_result[$y]['taxcodeid'];
    if (isset($_POST['accountingnumberb'.$i.'id']))
    {
      $query = 'update taxcode set base_accountingnumberid=? where taxcodeid=?';
      $query_prm = array($_POST['accountingnumberb'.$i.'id'], $i);
      require('inc/doquery.php');
    }
    if (isset($_POST['accountingnumber'.$i.'id']))
    {
      $query = 'update taxcode set accountingnumberid=? where taxcodeid=?';
      $query_prm = array($_POST['accountingnumber'.$i.'id'], $i);
      require('inc/doquery.php');
    }
  }
  $query = 'update globalvariables_accounting set integrated_journalid=?,onbehalf_anid=?';
  $query_prm = array($_POST['journalid'],$_POST['accountingnumberonbehalfid']);
  require('inc/doquery.php');
  echo 'Valeurs modifiées.<br><br>';
}

$query = 'select integrated_journalid,onbehalf_anid from globalvariables_accounting';
$query_prm = array();
require('inc/doquery.php');
$integrated_journalid = $query_result[0]['integrated_journalid'];
$onbehalf_anid = $query_result[0]['onbehalf_anid'];

$query = 'select * from taxcode order by taxcodeid';
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

echo '<h2>Intégration factures</h2>
<form method="post" action="accounting.php"><table class=report>
<thead><td>TVA<td>Compte base<td>Compte</thead>';
for ($i = 0; $i < $num_results_main; $i++)
{
  echo d_tr();
  echo d_td_old($main_result[$i]['taxcode']+0,1);
  $taxcodeid = $main_result[$i]['taxcodeid']+0;
  echo '<td align=right>'; $dp_notable = 1;
  $dp_itemname = 'accountingnumber'; $dp_selectedid = $main_result[$i]['base_accountingnumberid']; $dp_addtoid = 'b'.$taxcodeid; require('inc/selectitem.php');
  echo '<td align=right>'; $dp_notable = 1;
  $dp_itemname = 'accountingnumber'; $dp_selectedid = $main_result[$i]['accountingnumberid']; $dp_addtoid = $taxcodeid; require('inc/selectitem.php');
}
echo '<tr><td>Journal:<td>';
$dp_itemname = 'journal'; $dp_selectedid = $integrated_journalid; require('inc/selectitem.php');
echo '<td><tr><td>Débours:<td>';
$dp_itemname = 'accountingnumber'; $dp_selectedid = $onbehalf_anid; $dp_noblank = 1;
$dp_addtoid = 'onbehalf'; require('inc/selectitem.php');
echo '<td>
</table>
<input type=hidden name="saveme" value="1"><input type=hidden name="accountingmenu" value="'. $accountingmenu .'">
<input type="submit" value="Valider"></form>';
?>