<?php
if ($_SESSION['ds_menustyle'] == 5)
{
?>
<main role="main">
<nav id="side-nav">
<div>
<div class="close" id="closeSidenav"><span class="marker marker-open"></span></div>
<ul>
<?php
require('preload/accounting_simplifiedgroup.php');
if (isset($accounting_simplifiedgroupA))
{
  echo '<li><span class="subtitle">Modèles simplifiés</span></li>';
  foreach ($accounting_simplifiedgroupA as $temp_id => $name)
  {
    if ($accounting_simplifiedgroup_deletedA[$temp_id] == 0)
    {
      echo '<li><a href="accounting.php?accountingmenu=simplified&id='.$temp_id.'">' . d_output($name) . '</a></li>';
    }
  }
  echo '<li class="separator"></li>';
}
?>
<li><a href="accounting.php?accountingmenu=entry">Écriture</a></li>
<li><a href="accounting.php?accountingmenu=modentry">Modifier Écriture</a></li>
<li><a href="accounting.php?accountingmenu=entryreport">Rapport Écritures</a></li>
<li class="separator"></li>
<li><a href="accounting.php?accountingmenu=match_accounting">Lettrage</a></li>
<?php
if ($_SESSION['ds_reconciliation_type'] == 0)
{
  echo '<li><a href="accounting.php?accountingmenu=bankstatement">Relevés bancaires</a></li>';
}
else
{
  echo '<li><a href="accounting.php?accountingmenu=reconciliation';
  if ($_SESSION['ds_reconciliation_type'] == 1) { echo '_new'; }
  echo '">Rapprochement</a></li>';
}
?>
<li><a href="accounting.php?accountingmenu=undo">Défaire</a></li>
<li class="separator"></li>
<li><a href="accounting.php?accountingmenu=declarations">Déclarations</a></li>
<li class="separator"></li>
<li><span class="subtitle">Interface commerciale</span></li>
<li><a href="accounting.php?accountingmenu=deposit">Dépôt</a></li>
<li><a href="accounting.php?accountingmenu=depositreport">Rapport Dépôt</a></li>
<?php
if ($_SESSION['ds_customname'] == "Wing Chong" || $_SESSION['ds_customname'] == "Vaimato") # TODO remove or at least move to custom
{
  ?><li><a href="accounting.php?accountingmenu=chequebank">Remise chèques</a></li><?php
}
if ($_SESSION['ds_acc_canmodinvoice']) 
{ 
  echo '<li><a href="accounting.php?accountingmenu=accdate">Modif facture</a></li>'; 
}
if ($_SESSION['ds_acc_canmodpayment'])
{
  echo '<li><a href="accounting.php?accountingmenu=accdatepay">Modif paiement</a></li>';
}
?>
<li><a href="accounting.php?accountingmenu=split_invoice">Échelonner facture</a></li>
<li><a href="accounting.php?accountingmenu=paymentdetail">Détail paiement</a></li>
<li><a href="accounting.php?accountingmenu=vatreport">Rapport TVA</a></li>
<li class="separator"></li>
<li><span class="subtitle">Compta simplifiée</span></li>
<li><a href="accounting.php?accountingmenu=add_sa">Ajouter modèle</a></li>
<li><a href="accounting.php?accountingmenu=mod_sa">Modifier modèle</a></li>
<li><a href="accounting.php?accountingmenu=addgroup_sa">Ajouter rubrique</a></li>
<li><a href="accounting.php?accountingmenu=modgroup_sa">Modifier rubrique</a></li>
<li><a href="accounting.php?accountingmenu=list_sa">Lister</a></li>
<li><a href="accounting.php?accountingmenu=accounting_simplifiedreport">Rapport</a></li>
<li class="separator"></li>
<li><span class="subtitle">Plan Comptable Général</span></li>
<li><a href="accounting.php?accountingmenu=add">Ajouter</a></li>
<li><a href="accounting.php?accountingmenu=mod">Modifier</a></li>
<li><a href="accounting.php?accountingmenu=addgroup">Ajouter groupe</a></li>
<li><a href="accounting.php?accountingmenu=modgroup">Modifier groupe</a></li>
<li><a href="accounting.php?accountingmenu=changeacnid">Remplacer compte</a></li>
<li><a href="accounting.php?accountingmenu=list">Rapport</a></li>
<?php
if ($_SESSION['ds_directtoacc'] > 0) 
{
  ?>
  <li class="separator"></li>
  <li><span class="subtitle">Intégration</span></li>
  <li><a href="accounting.php?accountingmenu=manual_toacc">Manuelle</a></li>
  <li><a href="accounting.php?accountingmenu=manual_toacc_payroll">Manuelle (Paie)</a></li>
  <li><a href="accounting.php?accountingmenu=toacctaxcode">Comptes Facture</a></li>
  <li><a href="accounting.php?accountingmenu=toaccpaymenttype">Comptes Paiement</a></li>
  <li><a href="accounting.php?accountingmenu=toaccpayslip">Comptes Paie</a></li>
  <?php
}
?>
<li class="separator"></li>
<li><span class="subtitle">Clôture</span></li>
<li><a href="accounting.php?accountingmenu=closing&accountingmenu_sa=control">Clôture</a></li>
<li><a href="accounting.php?accountingmenu=closing_undo&accountingmenu_sa=control">"Dé-Clôture"</a></li>
<li><a href="accounting.php?accountingmenu=toaccspecials">Comptes Clôture</a></li>
</ul>
<?php require('inc/copyright.php'); ?>
</div>
</nav>
<div class="container" id="main-container">
<?php
}
else
{
?></div>
<div id="wrapper">
<div id="leftmenu">
<div id="selectactionbar">
<?php
require('preload/accounting_simplifiedgroup.php');
if (isset($accounting_simplifiedgroupA))
{
  echo '<div class="selectaction"><div class="selectactiontitle">Modèles simplifiés</div><div class="selectactionlist">';
  foreach ($accounting_simplifiedgroupA as $temp_id => $name)
  {
    if ($accounting_simplifiedgroup_deletedA[$temp_id] == 0)
    {
      if ($_SESSION['ds_displayicons'])
      {
        echo '<img src="pics/application_form_add.png">';
      }
      echo '<a class="leftmenu" href="accounting.php?accountingmenu=simplified&id='.$temp_id.'">' . d_output($name) . '</a><br>';
    }
  }
  echo '</div></div><br>';
}
?><div class="selectaction">
<div class="selectactionlist">
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/application_form_add.png"> <?php } ?> <a class="leftmenu" href="accounting.php?accountingmenu=entry">Écriture</a><br>
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/report_edit.png"> <?php } ?>  <a class="leftmenu" href="accounting.php?accountingmenu=modentry">Modifier Écriture</a><br>
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/report.png"> <?php } ?>  <a class="leftmenu" href="accounting.php?accountingmenu=entryreport">Rapport Écritures</a><br>
</div>
</div>

<div class="selectaction">
<div class="selectactionlist">
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/application_form_add.png"> <?php } ?> <a class="leftmenu" href="accounting.php?accountingmenu=match_accounting">Lettrage</a><br>
<?php


if ($_SESSION['ds_reconciliation_type'] == 0)
{
  if ($_SESSION['ds_displayicons']) { echo '<img src="pics/application_form_add.png"> '; }
  echo ' <a class="leftmenu" href="accounting.php?accountingmenu=bankstatement">Relevés bancaires</a><br>';
}
else
{
  if ($_SESSION['ds_displayicons']) { echo '<img src="pics/application_form_add.png"> '; }
  echo ' <a class="leftmenu" href="accounting.php?accountingmenu=reconciliation';
  if ($_SESSION['ds_reconciliation_type'] == 1) { echo '_new'; }
  echo '">Rapprochement</a><br>';
}

if ($_SESSION['ds_displayicons']) { ?><img src="pics/cancel.png"> <?php } ?> <a class="leftmenu" href="accounting.php?accountingmenu=undo">Défaire</a><br><?php
echo '<br>';
?>
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/report.png"> <?php } ?>  <a class="leftmenu" href="accounting.php?accountingmenu=declarations">Déclarations</a><br>
</div>
<div class="selectactiontitle">Interface commerciale</div>
<div class="selectactionlist">
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/application_form_add.png"> <?php } ?> <a class="leftmenu" href="accounting.php?accountingmenu=deposit">Dépôt</a><br>
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/report.png"> <?php } ?> <a class="leftmenu" href="accounting.php?accountingmenu=depositreport">Rapport Dépôt</a><br>
<?php
if ($_SESSION['ds_customname'] == "Wing Chong" || $_SESSION['ds_customname'] == "Vaimato")
{
  if ($_SESSION['ds_displayicons']) { ?><img src="pics/printer.png"> <?php } ?>  <a class="leftmenu" href="accounting.php?accountingmenu=chequebank">Remise chèques</a><br><?php
}
if ($_SESSION['ds_acc_canmodinvoice']) 
{ 
  if ($_SESSION['ds_displayicons']) { echo '<img src="pics/page_edit.png">';}
  echo '<a class="leftmenu" href="accounting.php?accountingmenu=accdate">Modif facture</a><br>'; 
}
if ($_SESSION['ds_acc_canmodpayment']) { if ($_SESSION['ds_displayicons']) { echo '<img src="pics/page_edit.png">';} echo '<a class="leftmenu" href="accounting.php?accountingmenu=accdatepay">Modif paiement</a><br>'; }
if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/report.png">';} ?>
<a class="leftmenu" href="accounting.php?accountingmenu=split_invoice">Échelonner facture</a><br>
<a class="leftmenu" href="accounting.php?accountingmenu=paymentdetail">Détail paiement</a><br>
<?php if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/report.png">';}
echo ' <a class="leftmenu" href="accounting.php?accountingmenu=vatreport">Rapport TVA</a><br>'; ?>
</div>  
</div>

<div class="selectaction">
<div class="selectactiontitle">Compta simplifiée</div>
<div class="selectactionlist">
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/report_edit.png"><?php } ?>  <a class="leftmenu" href="accounting.php?accountingmenu=add_sa">Ajouter modèle</a><br>
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/report_edit.png"> <?php } ?> <a class="leftmenu" href="accounting.php?accountingmenu=mod_sa">Modifier modèle</a><br>
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/note_add.png"><?php } ?>  <a class="leftmenu" href="accounting.php?accountingmenu=addgroup_sa">Ajouter rubrique</a><br>
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/note_edit.png"><?php } ?>  <a class="leftmenu" href="accounting.php?accountingmenu=modgroup_sa">Modifier rubrique</a><br>
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/information.png"> <?php } ?> <a class="leftmenu" href="accounting.php?accountingmenu=list_sa">Lister</a><br>
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/report.png"> <?php } ?> <a class="leftmenu" href="accounting.php?accountingmenu=accounting_simplifiedreport">Rapport</a><br>
</div>
</div>

<div class="selectaction">
<div class="selectactiontitle">Plan Comptable Général</div>
<div class="selectactionlist">
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/report_edit.png"><?php } ?>  <a class="leftmenu" href="accounting.php?accountingmenu=add">Ajouter</a><br>
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/report_edit.png"><?php } ?>  <a class="leftmenu" href="accounting.php?accountingmenu=mod">Modifier</a><br>
<?php
if ($_SESSION['ds_displayicons']) { ?><img src="pics/note_add.png"><?php } ?>  <a class="leftmenu" href="accounting.php?accountingmenu=addgroup">Ajouter groupe</a><br>
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/note_edit.png"> <?php } ?> <a class="leftmenu" href="accounting.php?accountingmenu=modgroup">Modifier groupe</a><br>
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/page_edit.png"><?php } ?>  <a class="leftmenu" href="accounting.php?accountingmenu=changeacnid">Remplacer compte</a><br>
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/information.png"><?php } ?>  <a class="leftmenu" href="accounting.php?accountingmenu=list">Rapport</a><br>
</div>
</div>

<?php
if ($_SESSION['ds_directtoacc'] > 0) 
{
  ?>
  
  <div class="selectaction">
  <div class="selectactiontitle">Intégration</div>
  <div class="selectactionlist">
  <?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/table_go.png"> <?php } ?> <a class="leftmenu" href="accounting.php?accountingmenu=manual_toacc">Manuelle</a><br>
  <?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/table_go.png"> <?php } ?> <a class="leftmenu" href="accounting.php?accountingmenu=manual_toacc_payroll">Manuelle (Paie)</a><br>
  <?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/report_edit.png"> <?php } ?> <a class="leftmenu" href="accounting.php?accountingmenu=toacctaxcode">Comptes Facture</a><br>
  <?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/report_edit.png"> <?php } ?> <a class="leftmenu" href="accounting.php?accountingmenu=toaccpaymenttype">Comptes Paiement</a><br>
  <?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/report_edit.png"> <?php } ?> <a class="leftmenu" href="accounting.php?accountingmenu=toaccpayslip">Comptes Paie</a><br>
  </div>
  </div>
  <?php
}

?>
<div class="selectaction">
<div class="selectactiontitle">Clôture</div>
<div class="selectactionlist">
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/application_form_add.png"> <?php } ?> <a class="leftmenu" href="accounting.php?accountingmenu=closing&accountingmenu_sa=control">Clôture</a><br>
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/application_form_add.png"> <?php } ?> <a class="leftmenu" href="accounting.php?accountingmenu=closing_undo&accountingmenu_sa=control">"Dé-Clôture"</a><br>
<?php if ($_SESSION['ds_displayicons']) { ?><img src="pics/report_edit.png"> <?php } ?> <a class="leftmenu" href="accounting.php?accountingmenu=toaccspecials">Comptes Clôture</a><br>
</div>
</div>
</div>
<?php
require ('inc/copyright.php');
?>
</div>
<div id="mainprogram">
<?php
}
?>