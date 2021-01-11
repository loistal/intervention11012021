<?php
$reportid = 6;
$ifield = 1;

#Form parameters
$dp_fieldnameA[$ifield] = 'productdepartmentid';
$dp_fielddescrA[$ifield] = 'Département';
$ifield++;

$dp_fieldnameA[$ifield] = 'suppliercode';
$dp_fielddescrA[$ifield] = d_trad('code');
$ifield++;

$dp_fieldnameA[$ifield] = 'productname';
$dp_fielddescrA[$ifield] = 'Nom du produit';
$ifield++;

$dp_fieldnameA[$ifield] = 'productfamilyid';
$dp_fielddescrA[$ifield] = 'Sous-famille';
$ifield++;

$dp_fieldnameA[$ifield] = 'countryid';
$dp_fielddescrA[$ifield] = 'Pays';
$ifield++;

$dp_fieldnameA[$ifield] = 'supplierid';
$dp_fielddescrA[$ifield] = 'Numéro Fournisseur';
$ifield++;

$dp_fieldnameA[$ifield] = 'unittypeid';
$dp_fielddescrA[$ifield] = 'Type d\'unité';
$ifield++;

$dp_fieldnameA[$ifield] = 'suppliercode';
$dp_fielddescrA[$ifield] = 'Code fournisseur';
$ifield++;

$dp_fieldnameA[$ifield] = 'fullproductname';
$dp_fielddescrA[$ifield] = 'Nom complet du produit';
$ifield++;

$dp_fieldnameA[$ifield] = 'accountingdate';
$dp_fielddescrA[$ifield] = $_SESSION['ds_term_accountingdate'];
$ifield++;

$dp_fieldnameA[$ifield] = 'deliverydate';
$dp_fielddescrA[$ifield] = $_SESSION['ds_term_deliverydate'];
$ifield++;

$dp_fieldnameA[$ifield] = 'invoicedate';
$dp_fielddescrA[$ifield] = 'Date facture';
$ifield++;

$dp_fieldnameA[$ifield] = 'paybydate';
$dp_fielddescrA[$ifield] = 'A payer avant';
$ifield++;

$dp_fieldnameA[$ifield] = 'productfamilygroupid';
$dp_fielddescrA[$ifield] = 'Famille';
$ifield++;

$dp_fieldnameA[$ifield] = 'clientid';
$dp_fielddescrA[$ifield] = 'Numéro client';
$ifield++;

$dp_fieldnameA[$ifield] = 'clientname';
$dp_fielddescrA[$ifield] = 'Nom du client';
$ifield++;

$dp_fieldnameA[$ifield] = 'islandid';
$dp_fielddescrA[$ifield] = 'Ile';
$ifield++;

$dp_fieldnameA[$ifield] = 'employeeid';
$dp_fielddescrA[$ifield] = 'Employé';
$ifield++;

$dp_fieldnameA[$ifield] = 'clientcategoryid';
$dp_fielddescrA[$ifield] = 'Categorie client';
$ifield++;

$dp_fieldnameA[$ifield] = 'clientcategory2id';
$dp_fielddescrA[$ifield] = 'Categorie client 2';
$ifield++;

$dp_fieldnameA[$ifield] = 'clienttermid';
$dp_fielddescrA[$ifield] = 'Terme client ';
$ifield++;

$dp_fieldnameA[$ifield] = 'temperatureid';
$dp_fielddescrA[$ifield] = 'Temperature';
$ifield++;

$dp_fieldnameA[$ifield] = 'producttypeid';
$dp_fielddescrA[$ifield] = 'Type du produit';
$ifield++;

$dp_fieldnameA[$ifield] = 'brand';
$dp_fielddescrA[$ifield] = 'Marque';
$ifield++;

$dp_fieldnameA[$ifield] = 'notused';
$dp_fielddescrA[$ifield] = '';
$ifield++;

$dp_fieldnameA[$ifield] = 'extraname';
$dp_fielddescrA[$ifield] = $_SESSION['ds_term_extraname'];
$ifield++;

$dp_fieldnameA[$ifield] = 'field1';
$dp_fielddescrA[$ifield] = $_SESSION['ds_term_field1'];
$ifield++;

$dp_fieldnameA[$ifield] = 'field2';
$dp_fielddescrA[$ifield] = $_SESSION['ds_term_field2'];
$ifield++;

$dp_fieldnameA[$ifield] = 'invoiceid';
$dp_fielddescrA[$ifield] = 'Numéro facture';
$ifield++;

$dp_fieldnameA[$ifield] = 'lineprice';
$dp_fielddescrA[$ifield] = 'Prix HT';
$ifield++;

$dp_fieldnameA[$ifield] = 'linetaxcodeid';
$dp_fielddescrA[$ifield] = 'Code taxe';
$ifield++;

$dp_fieldnameA[$ifield] = 'townid';
$dp_fielddescrA[$ifield] = 'Ville';
$ifield++;

$dp_fieldnameA[$ifield] = 'quantity';
$dp_fielddescrA[$ifield] = 'Quantité';
$ifield++;

$dp_fieldnameA[$ifield] = 'invoicetype';$dp_fielddescrA[$ifield] = 'Type de facture';$ifield ++;

$dp_fieldnameA[$ifield] = 'proforma';
$dp_fielddescrA[$ifield] = 'Proforma';
$ifield++;

$dp_fieldnameA[$ifield] = 'isnotice';
$dp_fielddescrA[$ifield] = 'Notice';
$ifield++;

$dp_fieldnameA[$ifield] = 'returntostock';
$dp_fielddescrA[$ifield] = 'Retour stock';
$ifield++;

$dp_fieldnameA[$ifield] = 'numberperunit';
$dp_fielddescrA[$ifield] = 'Nombre à l\'unité';
$ifield++;

$dp_fieldnameA[$ifield] = 'netweightlabel';
$dp_fielddescrA[$ifield] = 'Poids net';
$ifield++;

$dp_fieldnameA[$ifield] = 'userid';$dp_fielddescrA[$ifield] = d_trad('user');$ifield++;

$dp_fieldnameA[$ifield] = 'reference';
$dp_fielddescrA[$ifield] = 'Référence'; if ($_SESSION['ds_term_reference'] != '') { $dp_fielddescrA[$ifield] = $_SESSION['ds_term_reference']; }
$ifield++;

$dp_fieldnameA[$ifield] = 'invoicecomment';$dp_fielddescrA[$ifield] = d_trad('comment');$ifield++;
$dp_fieldnameA[$ifield] = 'invoicestatus';$dp_fielddescrA[$ifield] = d_trad('status');$ifield++;
$dp_fieldnameA[$ifield] = 'basecartonprice';$dp_fielddescrA[$ifield] = 'Prix/Unité';$ifield++;
$dp_fieldnameA[$ifield] = 'givenrebate';$dp_fielddescrA[$ifield] = 'Remise';$ifield++;
$dp_fieldnameA[$ifield] = 'productid';$dp_fielddescrA[$ifield] = 'Numéro produit';$ifield++;
$dp_fieldnameA[$ifield] = 'itemcomment';$dp_fielddescrA[$ifield] = 'Commentaire ligne';$ifield++;
$dp_fieldnameA[$ifield] = 'returnreasonid';$dp_fielddescrA[$ifield] = d_trad('returnreason');$ifield++;
$dp_fieldnameA[$ifield] = 'linevat';$dp_fielddescrA[$ifield] = 'TVA';$ifield++;

$dp_numfields = $_SESSION['ds_maxconfig'];
d_sortarray($dp_fielddescrA);
?>