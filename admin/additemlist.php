<?php

switch($_GET['type'])
{
  case 0:
    $adminmenu= 'additem';
    $title = d_trad('add');
    break;
  case 1:
    $adminmenu= 'moditem';
    $title = d_trad('modify');    
    break;
  default:
    $adminmenu= 'listitem';
    $title = d_trad('list');
    break;
}

echo '<h2>' . $title . '</h2>';                     

$descrA[] = d_trad('bank'); $valueA[] = 'bank';
$descrA[] = d_trad('clientcategory'); $valueA[] = 'clientcategory';
$descrA[] = d_trad('clientcategory2'); $valueA[] = 'clientcategory2';
$descrA[] = d_trad('clientcategory3'); $valueA[] = 'clientcategory3';
$descrA[] = d_trad('clientcategorygroup'); $valueA[] = 'clientcategorygroup';
$descrA[] = d_trad('clientcategorygroup2'); $valueA[] = 'clientcategorygroup2';
$descrA[] = d_trad('clientcategorygroup3'); $valueA[] = 'clientcategorygroup3';
$descrA[] = d_trad('clientactioncat'); $valueA[] = 'clientactioncat';
$descrA[] = 'Catégorie évènement produits'; $valueA[] = 'productactioncat';
$descrA[] = d_trad('paymentcategory'); $valueA[] = 'paymentcategory';
$descrA[] = d_trad('clientschedulecat'); $valueA[] = 'clientschedulecat';
$descrA[] = d_trad('commissionrate'); $valueA[] = 'commissionrate';
$descrA[] = d_trad('bankaccount'); $valueA[] = 'bankaccount';
$descrA[] = 'Entreprise concurrente'; $valueA[] = 'competitor';
$descrA[] = d_trad('currency'); $valueA[] = 'currency';
$descrA[] = d_trad('country'); $valueA[] = 'country';
$descrA[] = d_trad('returnreason'); $valueA[] = 'returnreason';
$descrA[] = d_trad('warehousereason'); $valueA[] = 'warehousereason';
$descrA[] = d_trad('resource'); $valueA[] = 'resource';
$descrA[] = d_trad('modifiedstockreason'); $valueA[] = 'modifiedstockreason';
$descrA[] = d_trad('clientsector'); $valueA[] = 'clientsector';
$descrA[] = d_trad('vessel'); $valueA[] = 'vessel';
$descrA[] = d_trad('localvessel'); $valueA[] = 'localvessel';
$descrA[] = d_trad('companytransport'); $valueA[] = 'companytransport';
$descrA[] = d_trad('unittype'); $valueA[] = 'unittype';
$descrA[] = 'Équipe (RH)'; $valueA[] = 'team';
$descrA[] = 'Tag évènement produit'; $valueA[] = 'productactiontag';
$descrA[] = 'Raison modif paiement'; $valueA[] = 'reason_payment_modify';
$descrA[] = 'Journal'; $valueA[] = 'journal';
$descrA[] = 'Tag 1 intervention'; $valueA[] = 'interventionitemtag1';
$descrA[] = 'Tag 2 intervention'; $valueA[] = 'interventionitemtag2';
$descrA[] = 'Compte modif net (Paie)'; $valueA[] = 'net_modif_account';
#$descrA[] = 'Commentaire ligne'; $valueA[] = 'select_itemcomment'; # not used
$descrA[] = $_SESSION['ds_term_invoice_priceoption1']; $valueA[] = 'invoice_priceoption1';
$descrA[] = $_SESSION['ds_term_invoice_priceoption2']; $valueA[] = 'invoice_priceoption2';
$descrA[] = $_SESSION['ds_term_invoice_priceoption3']; $valueA[] = 'invoice_priceoption3';
$descrA[] = d_trad('palette'); $valueA[] = 'palette';
$descrA[] = d_trad('advance'); $valueA[] = 'advance';
$descrA[] = d_trad('color'); $valueA[] = 'color';
if ($_SESSION['ds_useinvoicetag'])
{
  $descrA[] = $_SESSION['ds_term_invoicetag']; $valueA[] = 'invoicetag';
  $descrA[] = $_SESSION['ds_term_invoicetag2']; $valueA[] = 'invoicetag2';
}
if ($_SESSION['ds_use_invoiceitemgroup'])
{
  $descrA[] = 'Lieu QR'; $valueA[] = 'qr_location';
}
if ($_SESSION['ds_term_accounting_tag'] != "")
{
  $descrA[] = 'Tag Compta'; $valueA[] = 'adjustmentgroup_tag';
}

d_sortarray($descrA);
foreach ($descrA as $i => $fielddescr)
{
  if ($fielddescr != '')
  {
    echo '<br><a class="leftmenu" href="admin.php?adminmenu=' . $adminmenu . '&item=' . $valueA[$i] . '">' . $fielddescr . '</a>';
  }
}

?>