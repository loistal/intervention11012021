<?php
if ($_SESSION['ds_menustyle'] == 5)
{
?>
<main role="main" >
<nav id="side-nav">
<div>
<div class="close" id="closeSidenav"><span class="marker marker-open"></span></div>
<ul>
<li><span class="subtitle">Factures</span></li>
<li><a class="button button-outline" href="sales.php?salesmenu=invoicing"><i class="fa fa-plus-circle"></i>Créer</a></li>
<?php
if ($_SESSION['ds_canmodinvoice'] == 1)
{
  echo '<li><a href="sales.php?salesmenu=modinv">Modifier</a></li>';
}
echo '<li><a href="sales.php?salesmenu=copyinv">Copier</a></li>';
echo '<li><a href="sales.php?salesmenu=fillinv">Recommander</a></li>';
echo '<li><a href="sales.php?salesmenu=images_invoice">Images</a></li>';
if ($_SESSION['ds_canconfirm'] == 1)
{
  echo '<li><a href="sales.php?salesmenu=confirm">Confirmer / Annuler</a></li>';
}
echo '<li class="separator"></li>';
if ($_SESSION['ds_canpayments'] == 1) 
{
  echo '<li><a href="sales.php?salesmenu=payment">Paiement</a></li>';
}
if ($_SESSION['ds_userrepresentsclientid'] == 0)
{
  echo '<li><a href="sales.php?salesmenu=clientaction">Évènement</a></li>';
}
echo '<li><a href="sales.php?salesmenu=planning_simple">Prendre RDV</a></li>';
if ($_SESSION['ds_use_interventions'] == 1)
{
  echo '<li><a href="sales.php?salesmenu=intervention">Intervention</a></li>';
}

echo '<li class="separator"></li>';
echo '<li><a href="sales.php?salesmenu=showinvoice">Afficher facture</a></li>';
if ($_SESSION['ds_uselocalbol'] == 1 && $_SESSION['ds_nolocalbol'] == 0)
{
  echo '<li><a href="sales.php?salesmenu=showlocalbol">Connaissement</a></li>';
}
echo '<li><a href="sales.php?salesmenu=showclient">Fiche client</a></li>';
if ($_SESSION['ds_userrepresentsclientid'] < 1) 
{ 
  echo '<li><a href="sales.php?salesmenu=showaccount">Compte client</a></li>'; 
}
echo '<li class="separator"></li>';
echo '<li><a href="sales.php?salesmenu=invoicereport2">Rapport&nbsp;factures</a></li>';
echo '<li><a href="sales.php?salesmenu=soldproduct">Produits&nbsp;vendus</a></li>';
if ($_SESSION['ds_canpayments'] == 1)
{
  echo '<li><a href="sales.php?salesmenu=paymentreport">Rapport&nbsp;paiements</a></li>';
}
if ($_SESSION['ds_userrepresentsclientid'] == 0)
{
  echo '<li><a href="sales.php?salesmenu=showactions">Rapport&nbsp;évènements</a></li>';
}
?>
</ul>
<?php require('inc/searchbox.php');require('inc/copyright.php'); ?>
</div>
</nav>
<div class="container" id="main-container">
<?php
}
else
{
?>
</div><div id="wrapper">
<div id="leftmenu">
<div id="selectactionbar">
  <div class="selectaction">
    <div class="selectactionlist">
      <div class="selectactiontitle">Factures</div>
      <?php if ( $_SESSION['ds_displayicons'] == 1 ) {?><img src="pics/cart_add.png"><?php } ?> <a class="leftmenu" href="sales.php?salesmenu=invoicing">Créer</a><br>
      <?php
      if ($_SESSION['ds_canmodinvoice'] == 1)
      {
        if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/cart_edit.png">'; }
        echo '<a class="leftmenu" href="sales.php?salesmenu=modinv">Modifier</a><br>';
      }
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/cart_edit.png">'; }
      echo '<a class="leftmenu" href="sales.php?salesmenu=copyinv">Copier</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/cart_edit.png">'; }
      echo '<a class="leftmenu" href="sales.php?salesmenu=fillinv">Recommander</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/picture_add.png">'; }
      echo '<a class="leftmenu" href="sales.php?salesmenu=images_invoice">Images</a><br>';
      if ($_SESSION['ds_canconfirm'] == 1)
      {
        if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/accept.png">'; }
        echo '<a class="leftmenu" href="sales.php?salesmenu=confirm">Confirmer / Annuler</a><br>';
      }
      echo '<br>';
      if ($_SESSION['ds_canpayments'] == 1) 
      {
        if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/money.png">'; }
        echo '  <a class="leftmenu" href="sales.php?salesmenu=payment">Paiement</a><br>';
      }
      if ($_SESSION['ds_userrepresentsclientid'] == 0)
      {
        if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/exclamation.png">'; }
        echo '<a class="leftmenu" href="sales.php?salesmenu=clientaction">Évènement</a><br>';
      }
      if ( $_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/exclamation.png">'; }
      echo '<a class="leftmenu" href="sales.php?salesmenu=planning_simple">Prendre RDV</a><br>';

      if ($_SESSION['ds_use_interventions'] == 1)
      {
        if ( $_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/user_add.png">'; }
        echo '<a class="leftmenu" href="sales.php?salesmenu=intervention">Intervention</a><br>';
      }
      ?>
    </div>
  </div>

  <div class="selectaction">
    <div class="selectactionlist">
      <?php
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/printer.png">'; }
      echo '<a class="leftmenu" href="sales.php?salesmenu=showinvoice">Afficher facture</a><br>';
      if ($_SESSION['ds_uselocalbol'] == 1 && $_SESSION['ds_nolocalbol'] == 0)
      {
        if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/printer.png">'; } 
        echo '<a class="leftmenu" href="sales.php?salesmenu=showlocalbol">Connaissement</a><br>';
      }
      
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/printer.png">'; } 
      echo '<a class="leftmenu" href="sales.php?salesmenu=showclient">Fiche client</a><br>';

      if ($_SESSION['ds_userrepresentsclientid'] < 1) 
      { 
        if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/printer.png">'; } 
        echo '<a class="leftmenu" href="sales.php?salesmenu=showaccount">Compte client</a><br>'; 
      }
      ?>
    </div>
  </div>

  <div class="selectaction">
    <div class="selectactionlist">
      <?php
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/report.png">'; } 
      echo '<a class="leftmenu" href="sales.php?salesmenu=invoicereport2">Rapport&nbsp;factures</a><br>';
      
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/report.png">'; } 
      echo '<a class="leftmenu" href="sales.php?salesmenu=soldproduct">Produits&nbsp;vendus</a><br>';
      
      if ($_SESSION['ds_canpayments'] == 1)
      {
        if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/report.png">'; } 
        echo '<a class="leftmenu" href="sales.php?salesmenu=paymentreport">Rapport&nbsp;paiements</a><br>';
      }

      if ($_SESSION['ds_userrepresentsclientid'] == 0)
      {
        if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/report.png">'; } 
        echo '<a class="leftmenu" href="sales.php?salesmenu=showactions">Rapport&nbsp;évènements</a><br>';
      }

      ?>
    </div>
  </div>
</div>
<?php
require ('inc/searchbox.php');
require ('inc/copyright.php');
?>
</div><div id="mainprogram">
<?php
}
?>