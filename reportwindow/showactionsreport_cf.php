<?php
$reportid = 5;
$ifield = 1;

$dp_fieldnameA[$ifield] = 'clientid';
$dp_fielddescrA[$ifield] = 'Numéro client';
$ifield++;

$dp_fieldnameA[$ifield] = 'clientname';
$dp_fielddescrA[$ifield] = 'Nom du client';
$ifield++;

$dp_fieldnameA[$ifield] = 'clientactioncatid';
$dp_fielddescrA[$ifield] = 'Catégorie Action';
$ifield++;

$dp_fieldnameA[$ifield] = 'actionname';
$dp_fielddescrA[$ifield] = 'Evènement';
$ifield++;

$dp_fieldnameA[$ifield] = 'clientactionfield1';
$dp_fielddescrA[$ifield] = d_output($_SESSION['ds_term_clientactionfield1']);
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

$dp_fieldnameA[$ifield] = 'imageid';
$dp_fielddescrA[$ifield] = 'Image';
$ifield++;

$dp_fieldnameA[$ifield] = 'originid';
$dp_fielddescrA[$ifield] = 'Provenance';
$ifield++;

$dp_fieldnameA[$ifield] = 'contact_typeid';
$dp_fielddescrA[$ifield] = 'Type d\'intéraction';
$ifield++;

$dp_numfields = $_SESSION['ds_maxconfig'];
d_sortarray($dp_fielddescrA);
?>