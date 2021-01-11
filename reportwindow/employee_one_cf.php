<?php

#fields used in order presented here

//for trad
$temp = $_SESSION['ds_term_clientemployee1'];
$_SESSION['ds_lang'][$temp] = $temp;
$temp = $_SESSION['ds_term_clientemployee2'];
$_SESSION['ds_lang'][$temp] = $temp;

//TABLE 1 clients (employee 1)
$ifield = 1;
$dp_tab1fieldnameA[$ifield] = 'clientid';$dp_tab1fielddescrA[$ifield] = d_trad('clientnumber');$ifield++;
$dp_tab1fieldnameA[$ifield] = 'clientname';$dp_tab1fielddescrA[$ifield] = d_trad('client');$ifield++;
$dp_tab1fieldnameA[$ifield] = 'clientcategoryid';$dp_tab1fielddescrA[$ifield] = d_trad('clientcategory');$ifield++;
$dp_tab1fieldnameA[$ifield] = 'employeeid2';$dp_tab1fielddescrA[$ifield] = $_SESSION['ds_term_clientemployee2'];
//in tab1
$dp_tab1numfields = $ifield;

//TABLE 2 clients (employee 2)
$ifield = 1;
$dp_tab2fieldnameA[$ifield] = 'clientid';$dp_tab1fielddescrA[$ifield] = d_trad('clientnumber');$ifield++;
$dp_tab2fieldnameA[$ifield] = 'clientname';$dp_tab2fielddescrA[$ifield] = d_trad('client');$ifield++;
$dp_tab2fieldnameA[$ifield] = 'clientcategoryid';$dp_tab2fielddescrA[$ifield] = d_trad('clientcategory');$ifield++;
$dp_tab2fieldnameA[$ifield] = 'employeeid';$dp_tab2fielddescrA[$ifield] = $_SESSION['ds_term_clientemployee1'];
//in tab2
$dp_tab2numfields = $ifield;

//TABLE 3 invoices/assets
$ifield = 1;
$dp_tab3fieldnameA[$ifield] = 'invoiceid';$dp_tab3fielddescrA[$ifield] = d_trad('invoice');$ifield++;
$dp_tab3fieldnameA[$ifield] = 'clientname';$dp_tab3fielddescrA[$ifield] = d_trad('client');$ifield++;
#$dp_tab3fieldnameA[$ifield] = 'paybydate';$dp_tab3fielddescrA[$ifield] = d_trad('paybydate');$ifield++;
$dp_tab3fieldnameA[$ifield] = 'accountingdate';$dp_tab3fielddescrA[$ifield] = d_trad('accountingdate');$ifield++;
#$dp_tab3fieldnameA[$ifield] = 'deliverydate';$dp_tab3fielddescrA[$ifield] = d_trad('deliverydate');$ifield++;
#$dp_tab3fieldnameA[$ifield] = 'invoicedate';$dp_tab3fielddescrA[$ifield] = d_trad('invoicedate');$ifield++;
$dp_tab3fieldnameA[$ifield] = 'invoiceprice';$dp_tab3fielddescrA[$ifield] = d_trad('invoiceprice');$ifield++;
$dp_tab3fieldnameA[$ifield] = 'invoicetype';$dp_tab3fielddescrA[$ifield] = d_trad('type');$ifield++;
$dp_tab3fieldnameA[$ifield] = 'invoicestatus';$dp_tab3fielddescrA[$ifield] = d_trad('status');
//in tab3
$dp_tab3numfields = $ifield;

//TABLE 4 payments
$ifield = 1;
$dp_tab4fieldnameA[$ifield] = 'clientname';$dp_tab4fielddescrA[$ifield] = d_trad('client');$ifield++;
$dp_tab4fieldnameA[$ifield] = 'value';$dp_tab4fielddescrA[$ifield] = d_trad('value');$ifield++;
$dp_tab4fieldnameA[$ifield] = 'paymentdate';$dp_tab4fielddescrA[$ifield] = d_trad('paymentdate');$ifield++;
$dp_tab4fieldnameA[$ifield] = 'payer';$dp_tab4fielddescrA[$ifield] = d_trad('payer');$ifield++;
$dp_tab4fieldnameA[$ifield] = 'paymentcomment';$dp_tab4fielddescrA[$ifield] = d_trad('paymentcomment');$ifield++;
$dp_tab4fieldnameA[$ifield] = 'paymenttypeid';$dp_tab4fielddescrA[$ifield] = d_trad('paymenttype');
#$dp_tab4fieldnameA[$ifield] = 'reimbursement';$dp_tab4fielddescrA[$ifield] = d_trad('reimbursement');$ifield++;
#$dp_tab4fieldnameA[$ifield] = 'depositdate';$dp_tab4fielddescrA[$ifield] = d_trad('depositdate');
//in tab4
$dp_tab4numfields = $ifield;

//TABLE 5 expenses
$ifield = 1;
$dp_tab5fieldnameA[$ifield] = 'clientname';$dp_tab5fielddescrA[$ifield] = d_trad('client');$ifield++;
$dp_tab5fieldnameA[$ifield] = 'value';$dp_tab5fielddescrA[$ifield] = d_trad('value');$ifield++;
$dp_tab5fieldnameA[$ifield] = 'paymentdate';$dp_tab5fielddescrA[$ifield] = d_trad('paymentdate');$ifield++;
$dp_tab5fieldnameA[$ifield] = 'payer';$dp_tab5fielddescrA[$ifield] = d_trad('payer');$ifield++;
$dp_tab5fieldnameA[$ifield] = 'paymentcomment';$dp_tab5fielddescrA[$ifield] = d_trad('paymentcomment');$ifield++;
$dp_tab5fieldnameA[$ifield] = 'paymenttypeid';$dp_tab5fielddescrA[$ifield] = d_trad('paymenttype');$ifield++;
$dp_tab5fieldnameA[$ifield] = 'reimbursement';$dp_tab5fielddescrA[$ifield] = d_trad('reimbursement');$ifield++;
$dp_tab5fieldnameA[$ifield] = 'depositdate';$dp_tab5fielddescrA[$ifield] = d_trad('depositdate');
//in tab5
$dp_tab5numfields = $ifield;

//TABLE 6 planning
$ifield = 1;
$dp_tab6fieldnameA[$ifield] = 'planningname';$dp_tab6fielddescrA[$ifield] = d_trad('planningname');$ifield++;
$dp_tab6fieldnameA[$ifield] = 'periodic';$dp_tab6fielddescrA[$ifield] = d_trad('periodic');$ifield++;
$dp_tab6fieldnameA[$ifield] = 'planningcomment';$dp_tab6fielddescrA[$ifield] = d_trad('planningcomment');

//in tab6
$dp_tab6numfields = $ifield;

unset($ifield);
?>