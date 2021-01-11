<?php
if ($_SESSION['ds_menustyle'] == 5)
{
?>
<main role="main" >
<nav id="side-nav">
<div>
<div class="close" id="closeSidenav"><span class="marker marker-open"></span></div>
<ul>
<li><span class="subtitle">Achat direct</span></li>
<li><a href="purchase.php?purchasemenu=localpurchase">Achat direct</a></li>
<li><a href="purchase.php?purchasemenu=localpurchase_mod">Modifier</a></li>
<li><a href="purchase.php?purchasemenu=purchasereport">Rapport</a></li>
<li class="separator"></li> 
<li><span class="subtitle">FENIX</span></li>
<li><a href="purchase.php?purchasemenu=shipmentarrival">Commande (a)</a></li>
<li><a href="purchase.php?purchasemenu=notedetail">Note de Detail (b)</a></li>
<li><a href="purchase.php?purchasemenu=createfenix">Créer fichier FENIX (c)</a></li>
<li><a href="purchase.php?purchasemenu=finalize_fenix">Finalize (d)</a></li>
<li><a href="purchase.php?purchasemenu=calculprix">Calcul de Prix (e)</a></li>
<li><a href="purchase.php?purchasemenu=prixproduits">Prix et produits</a></li>
<li class="separator"></li>
<li><a href="purchase.php?purchasemenu=definalize">De-Finalize</a></li>
<li><a href="purchase.php?purchasemenu=currencyrates">Modifier taux douane</a></li>
<li class="separator"></li> 
<li><span class="subtitle">Rapports</span></li>
<li><a href="purchase.php?purchasemenu=toorder">A commander</a></li>
<li><a href="purchase.php?purchasemenu=shipmentlist">Rapport Achat</a></li>
<li><a href="purchase.php?purchasemenu=packinglist">Packing List</a></li>
<li><a href="purchase.php?purchasemenu=containerspermonth">Containers/mois</a></li>
<li><a href="purchase.php?purchasemenu=monthlyreport">Rapport mensuel</a></li>
<li><a href="purchase.php?purchasemenu=monthlyload">Chargement mensuel</a></li>
<li><a href="purchase.php?purchasemenu=advantagereport">Déclaration Prix PPN</a></li>
<li><a href="purchase.php?purchasemenu=importvalue">Décl. import en valeur</a></li>
</ul>
<?php require ('inc/searchbox.php');require('inc/copyright.php'); ?>
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
  <div class="selectactiontitle">Achat direct</div>
    <div class="selectactionlist">
      <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/application_form_add.png"><?php } ?>  <a class="leftmenu" href="purchase.php?purchasemenu=localpurchase">Achat direct</a><br>
      <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/application_form_add.png"><?php } ?>  <a class="leftmenu" href="purchase.php?purchasemenu=localpurchase_mod">Modifier</a><br>
      <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png"><?php } ?>  <a class="leftmenu" href="purchase.php?purchasemenu=purchasereport">Rapport</a><br>
    </div>
  </div>
  <div class="selectaction">
    <div class="selectactiontitle">FENIX</div>
    <div class="selectactionlist">
      <a class="leftmenu" href="purchase.php?purchasemenu=shipmentarrival">Commande (a)</a><br>
      <a class="leftmenu" href="purchase.php?purchasemenu=notedetail">Note de Detail (b)</a><br>
      <a class="leftmenu" href="purchase.php?purchasemenu=createfenix">Créer fichier FENIX(c)</a><br>
      <a class="leftmenu" href="purchase.php?purchasemenu=finalize_fenix">Finalize (d)</a><br>
      <a class="leftmenu" href="purchase.php?purchasemenu=calculprix">Calcul de Prix (e)</a><br>
      <a class="leftmenu" href="purchase.php?purchasemenu=prixproduits">Prix et produits</a><br>
      <br>
      <a class="leftmenu" href="purchase.php?purchasemenu=definalize">De-Finalize</a><br>
      <a class="leftmenu" href="purchase.php?purchasemenu=currencyrates">Modifier taux douane</a><br>
    </div>
  </div>
  <div class="selectaction">
      <div class="selectactiontitle">Rapports</div>
      <div class="selectactionlist">
        <a class="leftmenu" href="purchase.php?purchasemenu=toorder">A commander</a><br>
        <a class="leftmenu" href="purchase.php?purchasemenu=shipmentlist">Rapport Achat</a><br>
        <a class="leftmenu" href="purchase.php?purchasemenu=packinglist">Packing List</a><br>
        <a class="leftmenu" href="purchase.php?purchasemenu=containerspermonth">Containers/mois</a><br>
        <a class="leftmenu" href="purchase.php?purchasemenu=monthlyreport">Rapport mensuel</a><br>
        <a class="leftmenu" href="purchase.php?purchasemenu=monthlyload">Chargement mensuel</a><br>
        <a class="leftmenu" href="purchase.php?purchasemenu=advantagereport">Déclaration Prix PPN</a><br>
        <a class="leftmenu" href="purchase.php?purchasemenu=importvalue">Décl. import en valeur</a><br>
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