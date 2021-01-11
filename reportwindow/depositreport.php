<?php

require('preload/employee.php');
require('preload/paymenttype.php');
require('preload/bankaccount.php');

$PA['showclients'] = 'uint';
$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
require('inc/readpost.php');

$title = 'Rapport Dépôt ' . datefix($startdate,'short') . ' à ' . datefix($stopdate,'short');
showtitle($title);
echo '<h2>' . $title . '</h2>';

$query = 'select depositid,depositdate,username,employeeid,depositcomment,paymenttypeid,num_payments,depositbankaccountid,value from deposit,usertable
where deposit.userid=usertable.userid
and depositdate>=? and depositdate<=? order by depositdate,depositid';
$query_prm = array($startdate,$stopdate);
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

echo d_table('report'),'<thead><th>Numéro</th><th>Date</th><th>Utilisateur</th><th>Infos</th><th>Paiements</th><th>Valeur<th>Employé</th><th>Compte';
if ($showclients) { echo '<th>Clients'; }
echo '</thead>';

for ($i=0; $i < $num_results_main; $i++)
{
  echo d_tr(),'<td align=right><a href="printwindow.php?report=showdeposit&depositid='.$main_result[$i]['depositid'].'" target=_blank>' . $main_result[$i]['depositid'] . '</a>
  <td>' . datefix2($main_result[$i]['depositdate']) . '</td><td>' . d_output($main_result[$i]['username']) . '
  <td>' . d_output($main_result[$i]['depositcomment']) . '</td>
  <td align=right>' . d_output($main_result[$i]['num_payments']);
  if ($main_result[$i]['paymenttypeid'] > 0) { echo ' ',d_output($paymenttypeA[$main_result[$i]['paymenttypeid']]),'s'; }
  echo '<td align=right>' . myfix($main_result[$i]['value']);
  echo '<td>'; if (isset($employeeA[$main_result[$i]['employeeid']])) { echo d_output($employeeA[$main_result[$i]['employeeid']]); }
  echo '<td>'; if (isset($bankaccountA[$main_result[$i]['depositbankaccountid']])) { echo d_output($bankaccountA[$main_result[$i]['depositbankaccountid']]); }
  if ($showclients)
  {
    $query = 'select distinct payment.clientid,clientname
    from payment,client
    where payment.clientid=client.clientid
    and depositid=?
    order by clientname';
    $query_prm = array($main_result[$i]['depositid']);
    require('inc/doquery.php');
    $clientlist = '';
    for ($y=0; $y < $num_results; $y++)
    {
      $clientlist .= ', '.d_decode($query_result[$y]['clientname']).' ['.$query_result[$y]['clientid'].']';
    }
    $clientlist = ltrim($clientlist,',');
    echo d_td($clientlist);
  }
}
echo '</table>';

?>