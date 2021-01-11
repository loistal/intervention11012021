<?php

$reportid = 10;
$ifield = 1;
$dp_fieldnameA[$ifield] = 'dateofbirth';$dp_fielddescrA[$ifield] = 'Date de naissance';$ifield ++;
$dp_fieldnameA[$ifield] = 'employeename';$dp_fielddescrA[$ifield] = 'Nom';$ifield ++;
$dp_fieldnameA[$ifield] = 'employeefirstname';$dp_fielddescrA[$ifield] = 'Prénom';$ifield ++;
$dp_fieldnameA[$ifield] = 'employeemiddlename';$dp_fielddescrA[$ifield] = 'Second prénom';$ifield ++;
$dp_fieldnameA[$ifield] = 'dn';$dp_fielddescrA[$ifield] = 'DN';$ifield ++;
$dp_fieldnameA[$ifield] = 'referencenumber';$dp_fielddescrA[$ifield] = 'Matricule';$ifield ++;
$dp_fieldnameA[$ifield] = 'badgenumber'; $dp_fielddescrA[$ifield] = 'Numéro de badge'; $ifield++;
$dp_fieldnameA[$ifield] = 'teamid'; $dp_fielddescrA[$ifield] = 'Équipe'; $ifield++;
$dp_fieldnameA[$ifield] = 'ismanager'; $dp_fielddescrA[$ifield] = 'Manager pour équipe'; $ifield++;
$dp_fieldnameA[$ifield] = 'employeecategoryid'; $dp_fielddescrA[$ifield] = 'Catégorie d\'employé'; $ifield++;
$dp_fieldnameA[$ifield] = 'weeklyhoursid'; $dp_fielddescrA[$ifield] = 'Horaires par défaut'; $ifield++;
$dp_fieldnameA[$ifield] = 'unionrep'; $dp_fielddescrA[$ifield] = 'Délégué du personnel'; $ifield++;
$dp_fieldnameA[$ifield] = 'jobid'; $dp_fielddescrA[$ifield] = 'Emploi'; $ifield++;
$dp_fieldnameA[$ifield] = 'contractid'; $dp_fielddescrA[$ifield] = 'Contrat'; $ifield++;
$dp_fieldnameA[$ifield] = 'hiringdate'; $dp_fielddescrA[$ifield] = 'Date d\'embauche'; $ifield++;
$dp_fieldnameA[$ifield] = 'basesalary'; $dp_fielddescrA[$ifield] = 'Salaire de base'; $ifield++;
$dp_fieldnameA[$ifield] = 'hourspermonth'; $dp_fielddescrA[$ifield] = 'Horaire de référence'; $ifield++;
$dp_fieldnameA[$ifield] = 'payslipinfo'; $dp_fielddescrA[$ifield] = 'Infos salaire'; $ifield++;
$dp_fieldnameA[$ifield] = 'employeeemail'; $dp_fielddescrA[$ifield] = 'E-mail'; $ifield++;
$dp_fieldnameA[$ifield] = 'countryid'; $dp_fielddescrA[$ifield] = 'Nationalité'; $ifield++;
$dp_fieldnameA[$ifield] = 'townid'; $dp_fielddescrA[$ifield] = 'Ville'; $ifield++;
$dp_fieldnameA[$ifield] = 'telnumber1'; $dp_fielddescrA[$ifield] = 'Téléphone 1'; $ifield++;
$dp_fieldnameA[$ifield] = 'telnumber2'; $dp_fielddescrA[$ifield] = 'Téléphone 2'; $ifield++;
$dp_fieldnameA[$ifield] = 'geoaddress'; $dp_fielddescrA[$ifield] = 'Adresse géo'; $ifield++;
$dp_fieldnameA[$ifield] = 'postaladdress1'; $dp_fielddescrA[$ifield] = 'Adresse postale 1'; $ifield++;
$dp_fieldnameA[$ifield] = 'postaladdress2'; $dp_fielddescrA[$ifield] = 'Adresse postale 2'; $ifield++;
$dp_fieldnameA[$ifield] = 'postalcode'; $dp_fielddescrA[$ifield] = 'Code postal'; $ifield++;
$dp_fieldnameA[$ifield] = 'vacationdays'; $dp_fielddescrA[$ifield] = 'Solde des congés'; $ifield++;

$dp_numfields = $_SESSION['ds_maxconfig']; # max fields allowed in the report, doesn't have to match number of possible fields
d_sortarray($dp_fielddescrA);

?>