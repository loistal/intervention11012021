<?php
$reportid = 9;
$ifield = 1;

$dp_fieldnameA[$ifield] = 'productid';
$dp_fielddescrA[$ifield] = 'Numéro produit';
$ifield++;

$dp_fieldnameA[$ifield] = 'productname';
$dp_fielddescrA[$ifield] = 'Nom du produit';
$ifield++;

$dp_fieldnameA[$ifield] = 'productactioncatid';
$dp_fielddescrA[$ifield] = 'Catégorie Action';
$ifield++;

$dp_fieldnameA[$ifield] = 'actionname';
$dp_fielddescrA[$ifield] = 'Evènement';
$ifield++;

$dp_fieldnameA[$ifield] = 'userid';
$dp_fielddescrA[$ifield] = 'Utilisateur';
$ifield++;

$dp_fieldnameA[$ifield] = 'actiondate';
$dp_fielddescrA[$ifield] = 'Date évènement';
$ifield++;

$dp_fieldnameA[$ifield] = 'employeeid';
$dp_fielddescrA[$ifield] = 'Employé';
$ifield++;

$dp_fieldnameA[$ifield] = 'priceinfo';
$dp_fielddescrA[$ifield] = 'Info prix';
$ifield++;

$dp_fieldnameA[$ifield] = 'competitorid';
$dp_fielddescrA[$ifield] = 'Entreprise concurrente';
$ifield++;

$dp_fieldnameA[$ifield] = 'productactiontagid';
$dp_fielddescrA[$ifield] = $_SESSION['ds_term_productactiontag'];
$ifield++;

$dp_fieldnameA[$ifield] = 'productactionfield1';
$dp_fielddescrA[$ifield] = $_SESSION['ds_term_productactionfield1'];
$ifield++;

$dp_fieldnameA[$ifield] = 'imageid';
$dp_fielddescrA[$ifield] = 'Image';
$ifield++;

$dp_numfields = $_SESSION['ds_maxconfig'];
d_sortarray($dp_fielddescrA);
?>