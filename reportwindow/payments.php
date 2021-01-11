<?php

# TODO refactor

require('reportwindow/payments_cf.php');
require('preload/paymenttype.php');

$PAYMENT_DATE = 0;
$DEPOSIT_DATE = 1;
$ACCOUNTING_DATE = 0;
$DELIVERY_DATE = 1;
$TOBEPAIDBEFORE_DATE = 3;

$ALL = -1;
$INVOICETYPE_INVOICE = 1;
$INVOICETYPE_RETURN = 2;
$INVOICETYPE_PROFORMA = 3;
$INVOICETYPE_INVOICENOTICE = 4;
$INVOICETYPE_INVOICENOTICERETURN = 5;

$INVOICESTATUS_CONFIRMED1 = 0;
$INVOICESTATUS_CONFIRMEDANDNOTMATCHED = 1;
$INVOICESTATUS_MATCHED = 2;
$INVOICESTATUS_NOTCONFIRMED = 3;
$INVOICESTATUS_CANCELLED = 4;

$ORDERBY_BANK = 1;
$ORDERBY_DEPOSITBANK = 2;
$ORDERBY_PAYMENTDATE = 3;
$ORDERBY_DEPOSITDATE = 4;
$ORDERBY_VATTOTAL = 5;

$history = 'history';

$PA['reimbursement'] = 'int';
$PA['bynumber'] = 'int';
require('inc/readpost.php');

$paymentdatefield = (int) $_POST['paymentdatefield'];
$datename = 'paymentstartdate'; require('inc/datepickerresult.php');
if ($_SESSION['ds_restrict_sales_reports'] && $paymentstartdate < (mb_substr($_SESSION['ds_curdate'],0,4)-1).'-01-01')
{ $paymentstartdate = (mb_substr($_SESSION['ds_curdate'],0,4)-1).'-01-01'; }
$datename = 'paymentstopdate'; require('inc/datepickerresult.php');
$paymenttypeid = (int) $_POST['paymenttypeid'] + 0;
$paymentcategoryid = (int) $_POST['paymentcategoryid'] + 0;
$bankid = (int) $_POST['bankid'] + 0;
$depositbankid = (int) $_POST['bankdepositid'] + 0;
require('inc/findclient.php');
$islandid = (int) $_POST['islandid'];
$userid = (int) $_POST['userid'];
if (isset($_POST['employeeid'])) { $employeeid = (int) $_POST['employeeid']; }
else { $employeeid = -1; }
if (isset($_POST['employee1id'])) { $employee1id = (int) $_POST['employee1id']; }
else { $employee1id = -1; }
if (isset($_POST['employee2id'])) { $employee2id = (int) $_POST['employee2id']; }
else { $employee2id = -1; }
if (isset($_POST['clientcategoryid'])) { $clientcategoryid = (int) $_POST['clientcategoryid']; }
else { $clientcategoryid = -1; }
if (isset($_POST['clientcategory2id'])) { $clientcategory2id = (int) $_POST['clientcategory2id']; }
else { $clientcategory2id = -1; }
$clienttermid = (int) $_POST['clienttermid'];
$orderby = (int) $_POST['orderby'];
$startid = (int) $_POST['startid'];
$stopid = (int) $_POST['stopid'];
$chequeno = $_POST['chequeno'];
$starttime = $_POST['starttime'];
$stoptime = $_POST['stoptime'];

$title = d_trad('paymentreport');
if ($bynumber == 1 && $startid > 0 && $stopid > 0 && $stopid >= $startid)
{
  $paymentstartdate = '';
  $paymentstopdate = '';
  $title .= ' de numéro ' . myfix($startid) . ' à '.myfix($stopid);
}
else { $bynumber = 0; }
$paymentdatecolum = 'paymentdate';
if ( $paymentstartdate != '' && $paymentstopdate != '')
{
	$title .= ' ' . d_trad('between',array(datefix2($paymentstartdate),datefix2($paymentstopdate)));
  if ($paymentdatefield == $PAYMENT_DATE) { $title .= ' (' . d_trad('paymentdate') . ')'; }
  else { $title .= ' (' . d_trad('depositdate') . ')'; $paymentdatecolum = 'depositdate'; }
}

session_write_close(); 
showtitle_new($title);

require('inc/showparams.php');

$query = 'select p.paymentid,p.value,p.clientid,c.clientname,p.paymenttime,p.paymentdate,p.userid,p.chequeno,p.bankid,p.depositbankid,p.payer,p.matchingid,p.paymentcomment,p.paymenttypeid,';
$query .= 'p.reimbursement,p.forinvoiceid,p.employeeid,p.paymentcategoryid,p.depositdate,p.vattotal,p.paymfield1,p.paymfield2,p.toacc';
$query .= ',c.employeeid as employee1id,c.employeeid2 as employee2id,c.clientcategoryid,c.clientcategory2id,c.clienttermid,c.townid,t.islandid';
$query .= ' from payment p,client c,town t';
$query .= ' where p.clientid=c.clientid and c.townid=t.townid ';
$query_prm = array();

if ($starttime != '' && $stoptime != '') { $query .= ' and paymenttime>=? and paymenttime<=?'; array_push($query_prm, $starttime, $stoptime); }
if ($chequeno != '') { $query .= ' and p.chequeno like "%'.$chequeno.'%"'; }
if ($paymentstartdate != '') { $query .= ' and p.' . $paymentdatecolum . '>=?';  array_push($query_prm, $paymentstartdate); }
if ($paymentstopdate != '') { $query .= ' and p.' . $paymentdatecolum . '<=?'; array_push($query_prm, $paymentstopdate); }
if ($bynumber == 1) { $query .= ' and paymentid>=? and paymentid<=?'; array_push($query_prm, $startid, $stopid); }
if ($paymenttypeid > 0 ) { $query .= ' and p.paymenttypeid = ?'; array_push($query_prm, $paymenttypeid); }
if ($paymentcategoryid > 0 ) { $query .= ' and p.paymentcategoryid = ?'; array_push($query_prm, $paymentcategoryid); }
if ($bankid > 0 ) { $query .= ' and p.bankid = ?'; array_push($query_prm, $bankid); }
if ($depositbankid > 0 ) { $query .= ' and p.depositbankid = ?'; array_push($query_prm, $depositbankid); }
if ($reimbursement > 0 ) { $query .= ' and p.reimbursement = ?'; array_push($query_prm, $reimbursement); }
if ($clientid > 0) { $query .= ' and p.clientid=?'; array_push($query_prm, $clientid); }
if ($userid > 0) { $query .= ' and p.userid=?'; array_push($query_prm, $userid); }
if ($employeeid >= 0) { $query .= ' and p.employeeid=?'; array_push($query_prm, $employeeid); }
if ($employee1id >= 0) { $query .= ' and c.employeeid=?'; array_push($query_prm, $employee1id); }
if ($employee2id >= 0) { $query .= ' and c.employeeid2=?'; array_push($query_prm, $employee2id); }
if ($clientcategoryid >= 0) { $query .= ' and c.clientcategoryid=?'; array_push($query_prm, $clientcategoryid); }
if ($clientcategory2id >= 0) { $query .= ' and c.clientcategory2id=?'; array_push($query_prm, $clientcategory2id); }
if ($clienttermid > 0) { $query .= ' and c.clienttermid=?'; array_push($query_prm, $clienttermid); }
if ($islandid > 0) { $query .= ' and t.islandid=?'; array_push($query_prm, $islandid); }

switch ($orderby)
{
  case 6:
    $query .= ' order by p.paymenttypeid,p.paymentid'; $subtfield1 = 'paymenttypeid';
  break;
	case $ORDERBY_BANK:
		$query .= ' order by p.bankid,p.forinvoiceid'; # forinvoiceid ?
		break;	
	case $ORDERBY_DEPOSITBANK:
		$query .= ' order by p.bankid,p.forinvoiceid';
		break;	
	case $ORDERBY_PAYMENTDATE:
		$query .= ' order by p.paymentdate,p.forinvoiceid';
		break;	
	case $ORDERBY_DEPOSITDATE:
		$query .= ' order by p.depositdate,p.forinvoiceid';
		break;	
	case $ORDERBY_VATTOTAL:
		$query .= ' order by p.vattotal,p.forinvoiceid';
		break;
	default:
		$query .= ' order by p.forinvoiceid';
		break;
}

if ($_SESSION['ds_sqllimit'] > 0) { $query .= ' limit ' . $_SESSION['ds_sqllimit']; }

require('inc/doquery.php');
$row = $query_result; $num_rows = $num_results; unset($query_result, $num_results);

require('inc/showreport.php');

?>