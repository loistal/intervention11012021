<?php

$reportid = 1;
$ifield = 1;
$dp_fieldnameA[$ifield] = 'clientid';$dp_fielddescrA[$ifield] = d_trad('number');$ifield ++;
$dp_fieldnameA[$ifield] = 'clientname';$dp_fielddescrA[$ifield] = d_trad('name');$ifield ++;
$dp_fieldnameA[$ifield] = 'address';$dp_fielddescrA[$ifield] = d_trad('address');$ifield ++;
$dp_fieldnameA[$ifield] = 'postaladdress';$dp_fielddescrA[$ifield] = d_trad('postaladdress');$ifield ++;
$dp_fieldnameA[$ifield] = 'quarter';$dp_fielddescrA[$ifield] = d_trad('geoaddress');$ifield ++;
$dp_fieldnameA[$ifield] = 'townname';$dp_fielddescrA[$ifield] = d_trad('city');$ifield ++;
$dp_fieldnameA[$ifield] = 'islandname';$dp_fielddescrA[$ifield] = d_trad('island');$ifield ++;
$dp_fieldnameA[$ifield] = 'telephone';$dp_fielddescrA[$ifield] = d_trad('tel');$ifield ++;
$dp_fieldnameA[$ifield] = 'cellphone';$dp_fielddescrA[$ifield] = d_trad('mobile');$ifield ++;
$dp_fieldnameA[$ifield] = 'fax';$dp_fielddescrA[$ifield] = d_trad('fax');$ifield ++;
$dp_fieldnameA[$ifield] = 'contact';$dp_fielddescrA[$ifield] = d_trad('contact');$ifield ++;
$dp_fieldnameA[$ifield] = 'contact2';$dp_fielddescrA[$ifield] = d_trad('contact2');$ifield ++;
$dp_fieldnameA[$ifield] = 'contact3';$dp_fielddescrA[$ifield] = d_trad('contact3');$ifield ++;
$dp_fieldnameA[$ifield] = 'clientcategoryid';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_clientcategory'];$ifield ++;
$dp_fieldnameA[$ifield] = 'clientcategory2id';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_clientcategory2'];$ifield ++;
$dp_fieldnameA[$ifield] = 'employeeid';$dp_fielddescrA[$ifield] = d_trad('employee') . ' ' .$_SESSION['ds_term_clientemployee1'];$ifield ++;
$dp_fieldnameA[$ifield] = 'employeeid2';$dp_fielddescrA[$ifield] = d_trad('employee') . ' ' . $_SESSION['ds_term_clientemployee2'];$ifield ++;
$dp_fieldnameA[$ifield] = 'clienttermid';$dp_fielddescrA[$ifield] = d_trad('clientterm');$ifield ++;
$dp_fieldnameA[$ifield] = 'email';$dp_fielddescrA[$ifield] = d_trad('email');$ifield ++;
$dp_fieldnameA[$ifield] = 'clientbalance';$dp_fielddescrA[$ifield] = d_trad('clientbalance');$ifield++;
$dp_fieldnameA[$ifield] = 'image';$dp_fielddescrA[$ifield] = d_trad('image');$ifield++;
$dp_fieldnameA[$ifield] = 'postalcode';$dp_fielddescrA[$ifield] = 'Code Postal';$ifield++;
$dp_fieldnameA[$ifield] = 'clientcategory3id';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_clientcategory3'];$ifield++;
$dp_fieldnameA[$ifield] = 'clientfield1';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_clientfield1'];$ifield++;
$dp_fieldnameA[$ifield] = 'clienttype';$dp_fielddescrA[$ifield] = 'Type de client';$ifield ++;

$dp_numfields = $_SESSION['ds_maxconfig'];
d_sortarray($dp_fielddescrA);

?>