<?php

$reportid = 7;
$ifield = 1;
$dp_fieldnameA[$ifield] = 'clientid';$dp_fielddescrA[$ifield] = d_trad('clientnumber');$ifield ++;
$dp_fieldnameA[$ifield] = 'clientname';$dp_fielddescrA[$ifield] = d_trad('client');$ifield ++;
$dp_fieldnameA[$ifield] = 'forinvoiceid';$dp_fielddescrA[$ifield] = d_trad('invoicenumber');$ifield ++;
$dp_fieldnameA[$ifield] = 'paymentdate';$dp_fielddescrA[$ifield] = d_trad('paymentdate');$ifield ++;
$dp_fieldnameA[$ifield] = 'paymenttime';$dp_fielddescrA[$ifield] = d_trad('paymenttime');$ifield ++;
$dp_fieldnameA[$ifield] = 'depositdate';$dp_fielddescrA[$ifield] = d_trad('depositdate');$ifield ++;
$dp_fieldnameA[$ifield] = 'value';$dp_fielddescrA[$ifield] = d_trad('value');$ifield ++;
$dp_fieldnameA[$ifield] = '';$dp_fielddescrA[$ifield] = '';$ifield ++; # this field should not exist, replace it (was "vattotal")
$dp_fieldnameA[$ifield] = 'userid';$dp_fielddescrA[$ifield] = d_trad('user');$ifield ++;
$dp_fieldnameA[$ifield] = 'chequeno';$dp_fielddescrA[$ifield] = d_trad('chequenum');$ifield ++;
$dp_fieldnameA[$ifield] = 'bankid';$dp_fielddescrA[$ifield] = d_trad('bank');$ifield ++;
$dp_fieldnameA[$ifield] = 'depositbankid';$dp_fielddescrA[$ifield] = d_trad('depositbank');$ifield ++;
$dp_fieldnameA[$ifield] = 'payer';$dp_fielddescrA[$ifield] = d_trad('payer');$ifield ++;
$dp_fieldnameA[$ifield] = 'matchingid';$dp_fielddescrA[$ifield] = d_trad('matching');$ifield ++;
$dp_fieldnameA[$ifield] = 'paymentid';$dp_fielddescrA[$ifield] = d_trad('paymentid');$ifield ++;
$dp_fieldnameA[$ifield] = 'paymentcomment';$dp_fielddescrA[$ifield] = 'Info';$ifield ++;
$dp_fieldnameA[$ifield] = 'paymenttypeid';$dp_fielddescrA[$ifield] = d_trad('paymenttype');$ifield ++;
$dp_fieldnameA[$ifield] = 'reimbursement';$dp_fielddescrA[$ifield] = d_trad('reimbursement');$ifield ++;
$dp_fieldnameA[$ifield] = 'paymentcategoryid';$dp_fielddescrA[$ifield] = d_trad('paymentcategory');$ifield ++;
if ($_SESSION['ds_term_paymfield1'] != '' ) { $dp_fieldnameA[$ifield] = 'paymfield1';$dp_fielddescrA[$ifield] = d_output($_SESSION['ds_term_paymfield1']);$ifield ++;}
if ($_SESSION['ds_term_paymfield1'] != '' ) { $dp_fieldnameA[$ifield] = 'paymfield2';$dp_fielddescrA[$ifield] = d_output($_SESSION['ds_term_paymfield2']);$ifield ++;}
$dp_fieldnameA[$ifield] = 'toacc';$dp_fielddescrA[$ifield] = d_trad('toacc');$ifield ++;
$dp_fieldnameA[$ifield] = 'employeeid';$dp_fielddescrA[$ifield] = d_trad('employee');$ifield ++;
if ($_SESSION['ds_term_clientemployee1'] != '' ) { $dp_fieldnameA[$ifield] = 'employee1id';$dp_fielddescrA[$ifield] = d_trad('employee') . ' ' . $_SESSION['ds_term_clientemployee1'];$ifield ++; }
if ($_SESSION['ds_term_clientemployee2'] != '' ) { $dp_fieldnameA[$ifield] = 'employee2id';$dp_fielddescrA[$ifield] = d_trad('employee') . ' ' . $_SESSION['ds_term_clientemployee2'];$ifield ++; }
$dp_fieldnameA[$ifield] = 'clientcategoryid';$dp_fielddescrA[$ifield] = d_trad('clientcategory');$ifield ++;
$dp_fieldnameA[$ifield] = 'clientcategory2id';$dp_fielddescrA[$ifield] = d_trad('clientcategory2');$ifield ++;
$dp_fieldnameA[$ifield] = 'clienttermid';$dp_fielddescrA[$ifield] = d_trad('clientterm');$ifield ++;
$dp_fieldnameA[$ifield] = 'islandid';$dp_fielddescrA[$ifield] = d_trad('island');$ifield ++;
$dp_fieldnameA[$ifield] = 'townid';$dp_fielddescrA[$ifield] = d_trad('city');$ifield ++;

$dp_numfields = $_SESSION['ds_maxconfig']; # max fields allowed in the report, doesn't have to match number of possible fields
d_sortarray($dp_fielddescrA);

?>