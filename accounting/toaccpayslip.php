<?php

$PA['saveme'] = 'uint';
require('inc/readpost.php');

if ($saveme == 1)
{
  $query = 'select * from payslip_toacc order by payslip_toaccid';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($y = 0; $y < $num_results_main; $y++)
  {
    $i = $main_result[$y]['payslip_toaccid'];
    if (isset($_POST['accountingnumber'.$i.'id']))
    {
      $query = 'update payslip_toacc set accountingnumberid=? where payslip_toaccid=?';
      $query_prm = array($_POST['accountingnumber'.$i.'id'], $i);
      require('inc/doquery.php');
    }
  }
  echo 'Valeurs modifiées.<br><br>';
}

$query = 'select * from payslip_toacc order by payslip_toaccid';
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

echo '<h2>Intégration paie</h2>
<form method="post" action="accounting.php"><table class=report>
<thead><td>Description<td>Compte</thead>';
for ($i = 0; $i < $num_results_main; $i++)
{
  echo d_tr();
  echo d_td($main_result[$i]['description']);
  $id = $main_result[$i]['payslip_toaccid']+0;
  if ($id == 3 || $id == 5) { echo ' <span class="alert">Ce compte doit inclure Tiers</span>'; }
  echo '<td align=right>';
  $dp_notable = 1;
  $dp_itemname = 'accountingnumber';
  $dp_selectedid = $main_result[$i]['accountingnumberid'];
  $dp_addtoid = $id;
  require('inc/selectitem.php');
}
echo '</table>
<input type=hidden name="saveme" value="1"><input type=hidden name="accountingmenu" value="'. $accountingmenu .'">
<input type="submit" value="Valider"></form>';

?>