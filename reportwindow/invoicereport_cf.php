<?php

$reportid = 3;
$ifield = 1;
$dp_fieldnameA[$ifield] = 'clientid';$dp_fielddescrA[$ifield] = d_trad('clientnumber');$ifield ++;
$dp_fieldnameA[$ifield] = 'clientname';$dp_fielddescrA[$ifield] = d_trad('client');$ifield ++;
$dp_fieldnameA[$ifield] = 'invoiceid';$dp_fielddescrA[$ifield] = d_trad('invoicenumber');$ifield ++;
$dp_fieldnameA[$ifield] = 'accountingdate';$dp_fielddescrA[$ifield] = d_output($_SESSION['ds_term_accountingdate']);$ifield ++;
$dp_fieldnameA[$ifield] = 'deliverydate';$dp_fielddescrA[$ifield] = d_output($_SESSION['ds_term_deliverydate']);$ifield ++;
$dp_fieldnameA[$ifield] = 'invoicedate';$dp_fielddescrA[$ifield] = d_trad('invoicedate');$ifield ++;
$dp_fieldnameA[$ifield] = 'paybydate';$dp_fielddescrA[$ifield] = d_trad('paybydate');$ifield ++;
$dp_fieldnameA[$ifield] = 'userid';$dp_fielddescrA[$ifield] = d_trad('user');$ifield ++;
$dp_fieldnameA[$ifield] = 'employeeid';$dp_fielddescrA[$ifield] = d_trad('invoiceemployee');$ifield ++;
$dp_fieldnameA[$ifield] = 'employee1id';$dp_fielddescrA[$ifield] = d_trad('employee') . ' ' . $_SESSION['ds_term_clientemployee1'];$ifield ++;
$dp_fieldnameA[$ifield] = 'employee2id';$dp_fielddescrA[$ifield] = d_trad('employee') . ' ' . $_SESSION['ds_term_clientemployee2'];$ifield ++;
$dp_fieldnameA[$ifield] = 'clientcategoryid';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_clientcategory'];$ifield ++;
$dp_fieldnameA[$ifield] = 'clientcategory2id';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_clientcategory2'];$ifield ++;
$dp_fieldnameA[$ifield] = 'clienttermid';$dp_fielddescrA[$ifield] = d_trad('clientterm');$ifield ++;
$dp_fieldnameA[$ifield] = 'invoicetagid';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_invoicetag'];$ifield ++;
$dp_fieldnameA[$ifield] = 'reference';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_reference'];$ifield ++;
$dp_fieldnameA[$ifield] = 'extraname';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_extraname'];$ifield ++;
$dp_fieldnameA[$ifield] = 'field1';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_field1'];$ifield ++;
$dp_fieldnameA[$ifield] = 'field2';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_field2'];$ifield ++;
$dp_fieldnameA[$ifield] = 'invoiceprice';$dp_fielddescrA[$ifield] = d_trad('includingtax');$ifield ++;
$dp_fieldnameA[$ifield] = 'invoicepricenet';$dp_fielddescrA[$ifield] = d_trad('pricenet');$ifield ++;
$dp_fieldnameA[$ifield] = 'invoicevat';$dp_fielddescrA[$ifield] = d_trad('VAT');$ifield ++;
$dp_fieldnameA[$ifield] = 'invoicecomment';$dp_fielddescrA[$ifield] = d_trad('comment');$ifield ++;
$dp_fieldnameA[$ifield] = 'returnreasonid';$dp_fielddescrA[$ifield] = d_trad('returnreason');$ifield++;
$dp_fieldnameA[$ifield] = 'invoicestatus';$dp_fielddescrA[$ifield] = d_trad('status');$ifield ++;
$dp_fieldnameA[$ifield] = 'invoicetype';$dp_fielddescrA[$ifield] = 'Type de facture';$ifield ++;
$dp_fieldnameA[$ifield] = 'islandid';$dp_fielddescrA[$ifield] = d_trad('island');$ifield ++;
$dp_fieldnameA[$ifield] = 'townid';$dp_fielddescrA[$ifield] = d_trad('city');$ifield ++;
$dp_fieldnameA[$ifield] = 'invoicetag2id';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_invoicetag2'];$ifield ++;
$dp_fieldnameA[$ifield] = 'custominvoicedate';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_custominvoicedate'];$ifield ++;
$dp_fieldnameA[$ifield] = 'custominvoicedateplus';$dp_fielddescrA[$ifield] = 'Date delai détention';$ifield ++; # TODO generalise, for customdate +X days from invoicetag2
$dp_fieldnameA[$ifield] = 'invoicetime';$dp_fielddescrA[$ifield] = 'Heure de saisie';$ifield ++;
$dp_fieldnameA[$ifield] = 'localvesselid';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_localvessel'];$ifield ++;
$dp_fieldnameA[$ifield] = 'clientcategory3id';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_clientcategory3'];$ifield ++;
$dp_fieldnameA[$ifield] = 'email';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_client_email'];$ifield ++;
$dp_fieldnameA[$ifield] = 'batchemail';$dp_fielddescrA[$ifield] = 'Email pour Factures/Relevés';$ifield ++;

$dp_numfields = $_SESSION['ds_maxconfig']; # max fields allowed in the report, doesn't have to match number of possible fields
d_sortarray($dp_fielddescrA);

?>