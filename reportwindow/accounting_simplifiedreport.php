<?php

require('preload/accountingnumber.php');

echo '<h2>Rapport Compta Simplifiée</h2>';
showtitle('Rapport Compta Simplifiée');

$query = 'select *
from accounting_simplified,accounting_simplifiedgroup
where accounting_simplified.accounting_simplifiedgroupid=accounting_simplifiedgroup.accounting_simplifiedgroupid
and accounting_simplified.deleted=0
order by `rank`,accounting_simplifiedgroupname,accounting_simplifiedname';
$query_prm = array();  
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

echo '<table class=report><thead><th>Groupe<th>Menu<th>Comptes Débit<th>Comptes Crédit<th>Compte Balance</thead>';
for ($i=0;$i<$num_results_main;$i++)
{
  $debit_list = '';
  $credit_list = '';
  $balance_list = '';
  $to_add_list = '';
  if ($main_result[$i]['usebalanceline'] == 1) { $balance_list = $accountingnumber_longA[$main_result[$i]['balanceline_accountingnumberid']]; }
  for ($y=1; $y <= 16; $y++)
  {
    if ($main_result[$i]['line'.$y.'_show'] == 1)
    {
      if ($main_result[$i]['line'.$y.'_choices'] != '')
      {
        $to_add_list = $main_result[$i]['line'.$y.'_choices'];
      }
      else
      {
        $to_add_list = $accountingnumber_longA[$main_result[$i]['line'.$y.'_accountingnumberid']];
      }
      if ($main_result[$i]['line'.$y.'_debit'] == 1)
      {
        if ($debit_list != '') { $debit_list .= ' / '; }
        $debit_list .= $to_add_list;
      }
      else
      {
        if ($credit_list != '') { $credit_list .= ' / '; }
        $credit_list .= $to_add_list;
      }
    }
  }
  echo d_tr();
  echo d_td_old($main_result[$i]['accounting_simplifiedgroupname']);
  echo d_td_old($main_result[$i]['accounting_simplifiedname']);
  echo d_td_old($debit_list);
  echo d_td_old($credit_list);
  echo d_td_old($balance_list);
}
echo '<tr><td align=center colspan=10><b>' . myfix($num_results_main) .' menus';
echo '</table>';
?>