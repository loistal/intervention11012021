<?php

# input: $dp_clientid $dp_payabledate
# output:
$dr_balance = 0; # client account balance

# debit
$query = 'select sum(invoiceprice) as balance from invoicehistory where cancelledid<1 and matchingid=0 and isreturn=0 and confirmed=1 and clientid=?';
if (isset($dp_payabledate) && $dp_payabledate) { $query .= ' and paybydate<=curdate()'; }
$query_prm = array($dp_clientid);
require ('inc/doquery.php');
$dr_balance = $dr_balance + $query_result[0]['balance'];

$query = 'select sum(value) as balance from payment where value>0 and reimbursement=1 and matchingid=0 and clientid=?';
$query_prm = array($dp_clientid);
require ('inc/doquery.php');
$dr_balance = $dr_balance + $query_result[0]['balance'];

$query = 'select sum(value) as balance from adjustment,adjustmentgroup where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and integrated=0 and debit=1 and matchingid=0 and accountingnumberid=1 and referenceid=?';
$query_prm = array($dp_clientid);
require ('inc/doquery.php');
$dr_balance = $dr_balance + $query_result[0]['balance'];

# credit
$query = 'select sum(value) as balance from payment where value>0 and reimbursement=0 and matchingid=0 and clientid=?';
$query_prm = array($dp_clientid);
require ('inc/doquery.php');
$dr_balance = $dr_balance - $query_result[0]['balance'];

$query = 'select sum(invoiceprice) as balance from invoicehistory where cancelledid<1 and matchingid=0 and isreturn=1 and confirmed=1 and clientid=?';
$query_prm = array($dp_clientid);
require ('inc/doquery.php');
$dr_balance = $dr_balance - $query_result[0]['balance'];

$query = 'select sum(value) as balance from adjustment,adjustmentgroup where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and integrated=0 and debit=0 and matchingid=0 and accountingnumberid=1 and referenceid=?';
$query_prm = array($dp_clientid);
require ('inc/doquery.php');
$dr_balance = $dr_balance - $query_result[0]['balance'];

unset($dp_clientid, $dp_payabledate);

?>