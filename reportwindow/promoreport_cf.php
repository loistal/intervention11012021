<?php

#fields used in order presented here
$ifield= 1;
$dp_fieldnameA[$ifield] = 'invoiceid';$dp_fielddescrA[$ifield] = d_trad('invoice');$ifield++;
$dp_fieldnameA[$ifield] = 'accountingdate';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_accountingdate'];$ifield++;
$dp_fieldnameA[$ifield] = 'clientid';$dp_fielddescrA[$ifield] = d_trad('clientnumber');$ifield++;
$dp_fieldnameA[$ifield] = 'clientname';$dp_fielddescrA[$ifield] = d_trad('client');$ifield++;
if ($_SESSION['ds_useproductcode']) 
{ 
  $dp_fieldnameA[$ifield] = 'suppliercode';$dp_fielddescrA[$ifield] = d_trad('code');$ifield++;
}
else 
{ 
  $dp_fieldnameA[$ifield] = 'productid';$dp_fielddescrA[$ifield] = d_trad('number');$ifield++; 
}
$dp_fieldnameA[$ifield] = 'productname';$dp_fielddescrA[$ifield] = d_trad('product');$ifield++;
$dp_fieldnameA[$ifield] = 'packaging';$dp_fielddescrA[$ifield] = d_trad('packaging');$ifield++;
$dp_fieldnameA[$ifield] = 'quantity';$dp_fielddescrA[$ifield] = d_trad('quantity');$ifield++;
$givenrebatefield=$ifield;$dp_fieldnameA[$ifield] = 'givenrebate';$dp_fielddescrA[$ifield] = d_trad('givenrebate');$ifield++;
$linepricefield=$ifield;$dp_fieldnameA[$ifield] = 'lineprice';$dp_fielddescrA[$ifield] = d_trad('totalpriceht');$ifield++;
$percentagefield=$ifield;$dp_fieldnameA[$ifield] = 'percentage';$dp_fielddescrA[$ifield] = d_trad('percentage');$ifield++;
$dp_fieldnameA[$ifield] = 'employeeid';$dp_fielddescrA[$ifield] = d_trad('employee');
//in report
$dp_numfields = $ifield;

unset($ifield);
?>