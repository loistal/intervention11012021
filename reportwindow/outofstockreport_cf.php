<?php

$reportid = 8;

#fields used in order presented here

// STOCK=0
$ifield = 1;
if ($_SESSION['ds_useproductcode']) 
{ 
  $dp_fieldnameA[$ifield] = 'suppliercode';$dp_fielddescrA[$ifield] = d_trad('code');$ifield++;
}
else 
{ 
  $dp_fieldnameA[$ifield] = 'productid';$dp_fielddescrA[$ifield] = d_trad('number');$ifield++; 
}
$productnamefield=$ifield;$dp_fieldnameA[$ifield] = 'productname';$dp_fielddescrA[$ifield] = d_trad('product');$ifield++;
$dp_fieldnameA[$ifield] = 'month';$dp_fielddescrA[$ifield] = d_trad('packaging');$ifield++;
$dp_fieldnameA[$ifield] = 'year';$dp_fielddescrA[$ifield] = d_trad('family');$ifield++;
$stockfield=$ifield;$dp_fieldnameA[$ifield] = 'currentstock';$dp_fielddescrA[$ifield] = d_trad('stock');$ifield++;
$dp_fieldnameA[$ifield] = 'leadtime';$dp_fielddescrA[$ifield] = d_trad('leadtime');$ifield++;
$dp_fieldnameA[$ifield] = 'supplierid';$dp_fielddescrA[$ifield] = d_trad('supplier');
//in report
$dp_numfields = $ifield;

unset($ifield);


# TODO see http://stackoverflow.com/questions/7929796/how-can-i-sort-a-utf-8-string-in-php
# for sorting Î correctly

?>