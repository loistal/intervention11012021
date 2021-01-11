<?php

# TODO important define 411000 instead of hardcode

if ($_POST['saveme'] == 1)
{
  echo 'Valeurs modifiées.';
  $query = 'select * from paymenttype where deleted=0 order by paymenttypeid';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($y = 0; $y < $num_results_main; $y++)
  {
    $i = $main_result[$y]['paymenttypeid'];
    if (isset($_POST['accountingnumber'.$i.'id']))
    {
      $query = 'update paymenttype set accountingnumberid=? where paymenttypeid=?';
      $query_prm = array($_POST['accountingnumber'.$i.'id'], $i);
      require('inc/doquery.php');
    }
  }
}

$query = 'select * from paymenttype where deleted=0 order by paymenttypeid';
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

echo '<h2>Intégration paiements</h2>
<form method="post" action="accounting.php"><table class=report>
<thead><td>Type<td>Compte</thead>';
for ($i = 0; $i < $num_results_main; $i++)
{
  echo d_tr();
  echo d_td_old($main_result[$i]['paymenttypename'],1);
  $paymenttypeid = $main_result[$i]['paymenttypeid']+0;
  echo '<td align=right>'; $dp_notable = 1;
  $dp_itemname = 'accountingnumber'; $dp_selectedid = $main_result[$i]['accountingnumberid']; $dp_addtoid = $paymenttypeid; require('inc/selectitem.php');
}
echo '</table>
<input type=hidden name="saveme" value="1"><input type=hidden name="accountingmenu" value="'. $accountingmenu .'">
<input type="submit" value="Valider"></form>';

?>