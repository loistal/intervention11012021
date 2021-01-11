<?php

# TODO reimbursement counts as negative!

$depositid = (int) $_GET['depositid'];

$query = 'select depositdate,employeeid,paymenttypeid,depositcomment,fullbankname,bankaccountname,name from deposit,bankaccount,bank,usertable
where deposit.depositbankaccountid=bankaccount.bankaccountid and bankaccount.bankid=bank.bankid and deposit.userid=usertable.userid and depositid=?';
$query_prm = array($depositid);
require('inc/doquery.php');
if (!$num_results) { $depositid = -1; }
if ($depositid < 1) { exit; }
$depositdate = $query_result[0]['depositdate'];
$employeeid = $query_result[0]['employeeid'];
#num_payments smallint unsigned not null default 0,
$paymenttypeid = $query_result[0]['paymenttypeid'];
$depositcomment = $query_result[0]['depositcomment'];
$fullbankname = $query_result[0]['fullbankname'];
$bankaccountname = $query_result[0]['bankaccountname'];
$username = $query_result[0]['name'];

require('preload/paymenttype.php');
require('preload/bank.php');

$query = 'SELECT beneficiary FROM globalvariables WHERE primaryunique=1';
$query_prm = array();
require('inc/doquery.php');
$beneficiary = $query_result[0]['beneficiary'];

$title = 'Dépot';
if ($paymenttypeid > 0) { $title .= ' '.d_output($paymenttypeA[$paymenttypeid]); }
if ($paymenttypeid == 2) { $title = 'Remise de chèques'; }



showtitle($title .' '. $depositid);
echo '<div class="main">';

# TODO define thead and th for printwindow
# TODO font and size
echo '<style>body {
    font-family: '. $_SESSION['ds_user_font'] .';
    font-size: '. $_SESSION['ds_user_font_size'] .';
  }</style>';
echo '<center><h2>'.$title.'</h2><p>'. $fullbankname .' - '. datefix($depositdate) .'</p>
<h3>Bénéficiaire: '. $beneficiary .'</h3><h3>N<superscript>o</superscript> compte: '. $bankaccountname .'</h3><table class=report>
<tr><td><td><td><b>Banque<td><b>N<sup>o</sup> Cheque<td><b>Tireur<td><b>Montant</thead>';

$total = 0;
$query = 'select chequeno,payer,value,bankid,paymenttypeid,reimbursement from payment
where depositid=? order by bankid,chequeno'; # TODO order by bankname with d_sortresults
$query_prm = array($depositid);
require('inc/doquery.php');
for ($i = 0; $i < $num_results; $i++)
{
  $value = $query_result[$i]['value'];
  if ($query_result[$i]['reimbursement'] == 1) { $value = d_subtract(0, $value); }
  echo d_tr();
  echo d_td_old($i+1);
  echo d_td_old($paymenttypeA[$query_result[$i]['paymenttypeid']]);
  echo d_td_old($bankA[$query_result[$i]['bankid']]);
  echo d_td_old($query_result[$i]['chequeno']);
  echo d_td_old($query_result[$i]['payer']);
  echo d_td_old(myfix($value),1);
  $total = d_add($total, $value);
}
echo d_tr();
echo d_td_old('Total:',0,2,5);
echo d_td_old(myfix($total),1,2);

echo '</table><p>Crée par: '. d_output($username);
if ($employeeid > 0) { require('preload/employee.php'); echo ' - Déposé par: '. d_output($employeeA[$employeeid]); }
if ($depositcomment != '') { echo '</p><p>Infos: ' . d_output($depositcomment); }
echo '</p></center></div>';

?>