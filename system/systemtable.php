<?php
if ($_SESSION['ds_menustyle'] == 5)
{
?>
<main role="main" >
  <nav id="side-nav">
  <div>
    <div class="close" id="closeSidenav"><span class="marker marker-open"></span></div>
    <ul>
      <li><span class="subtitle">Utilisateurs</span></li>
      <li><a href="system.php?systemmenu=adduser"><i class="fad fa-file-edit"></i>Ajouter</a></li>
      <li><a href="system.php?systemmenu=moduser">Modifier</a></li>
      <li><a href="system.php?systemmenu=listusers">Lister</a></li>
      <li class="separator"></li>
      <li><a href="system.php?systemmenu=modglobal">Options globales</a></li>
      <li><a href="system.php?systemmenu=modterms">Termes globaux</a></li>
      <li><a href="system.php?systemmenu=companyinfo">Infos entreprise</a></li>
      <li class="separator"></li>
      <li><a href="system.php?systemmenu=showlogs">Rapport accès</a></li>
      <li><a href="system.php?systemmenu=showlogs2">Rapport log</a></li>
      <li class="separator"></li>
      <li><span class="subtitle">Partie Technique</span></li>
      <li><a href="system.php?systemmenu=debug">Debug mode</a></li>
      <li><a class="leftmenu" href="adminer" target="_blank">Adminer</a></li>
    </ul>
    <?php require('inc/copyright.php'); ?>
  </div>
</nav>
<div class="container" id="main-container">
<?php
}
else
{
?>
</div><div id="wrapper"><?php
$ds_displayicons = $_SESSION['ds_displayicons'];
?>
<div id="leftmenu">
<div id="selectactionbar">
  <div class="selectaction">
    <div class="selectactiontitle">Utilisateurs</div>
    <div class="selectactionlist">
      <?php
      if ( $ds_displayicons == 1 ) { echo '<img src="pics/user_add.png">'; } 
      echo ' <a class="leftmenu" href="system.php?systemmenu=adduser">Ajouter</a><br>';
      if ( $ds_displayicons == 1 ) { echo '<img src="pics/user_edit.png">'; } 
      echo '<a class="leftmenu" href="system.php?systemmenu=moduser">Modifier</a><br>';
      if ( $ds_displayicons == 1 ) { echo '<img src="pics/user.png">'; } 
      echo '<a class="leftmenu" href="system.php?systemmenu=listusers">Liste</a><br>';
      ?>
    </div>
  </div>

  <div class="selectaction">
    <div class="selectactionlist">
      <?php
      if ( $ds_displayicons == 1 ) { echo '<img src="pics/world_edit.png">'; } echo '<a class="leftmenu" href="system.php?systemmenu=modglobal">Options globales</a><br>';
      if ( $ds_displayicons == 1 ) { echo '<img src="pics/world_edit.png">'; } echo '<a class="leftmenu" href="system.php?systemmenu=modterms">Termes globaux</a><br>';
      if ( $ds_displayicons == 1 ) { echo '<img src="pics/world_edit.png">'; } echo '<a class="leftmenu" href="system.php?systemmenu=companyinfo">Infos entreprise</a><br>';
      ?>
    </div>
  </div>
  <div class="selectaction">
    <div class="selectactionlist">
      <?php if ( $ds_displayicons == 1 ) { echo '<img src="pics/report.png">'; } echo '<a class="leftmenu" href="system.php?systemmenu=showlogs">Rapport accès</a><br>';
      if ( $ds_displayicons == 1 ) { echo '<img src="pics/report.png">'; } echo '<a class="leftmenu" href="system.php?systemmenu=showlogs2">Rapport log</a>';?>
    </div>
  </div>

  <br><div class="selectaction">
  <div class="selectactiontitle"> --- Partie Technique ---</div><br>
    <div class="selectactionlist">
    <a class="leftmenu" href="system.php?systemmenu=debug">Debug mode</a><br><?php
    if ( $ds_displayicons == 1 ) { echo '<img src="pics/world_edit.png">'; } echo '<a class="leftmenu" href="adminer" target="_blank">Adminer</a>';?>
    </div>
    <div class="selectactionlist"> 
      <?php
      #echo '<a class="leftmenu" href="system.php?systemmenu=importproducts">Import produits</a><br>';
      #<br>
      #if ( $ds_displayicons == 1 ) { echo '<img src="pics/report.png">'; } echo '<a class="leftmenu" href="system.php?systemmenu=checkinvoices">Vérifier factures</a><br>';
      echo '<a class="leftmenu" href="system.php?systemmenu=importclient">[client import]</a><br>';
      #echo '<a class="leftmenu" href="system.php?systemmenu=importclient2">[client import2]</a><br>';
      #echo '<a class="leftmenu" href="system.php?systemmenu=importbarcode">[barcode import]</a><br>';
      #echo '<a class="leftmenu" href="system.php?systemmenu=importbarcodecatalogue">[barcode import catalogue]</a><br>';
      echo '<a class="leftmenu" href="system.php?systemmenu=importproduct">[product import]</a><br>';
      #echo '<a class="leftmenu" href="system.php?systemmenu=importproduct2">[product import 2]</a><br>';
      #echo '<a class="leftmenu" href="system.php?systemmenu=importproduct3">[product import 3]</a><br>';
      echo '<a class="leftmenu" href="system.php?systemmenu=importproduct4">[product import 4]</a><br>';
      #echo '<a class="leftmenu" href="system.php?systemmenu=importgeneral">[employé import]</a><br>';
      #echo '<a class="leftmenu" href="system.php?systemmenu=importgeneral2">[employe import 2]</a><br>';
      #echo '<a class="leftmenu" href="system.php?systemmenu=importgeneral3">[general import 3]</a><br>';
      echo '<a class="leftmenu" href="system.php?systemmenu=importgeneral4">[general import 4]</a><br>';
      #echo '<a class="leftmenu" href="system.php?systemmenu=importaccounting">[plan comptable import]</a><br>';
      echo '<a class="leftmenu" href="system.php?systemmenu=importaccounting2">[accounting import]</a><br>';
      #echo '<a class="leftmenu" href="system.php?systemmenu=debug1">[modifiedstock]</a><br>';
      #echo '<a class="leftmenu" href="system.php?systemmenu=tohistoryfix">[tohistoryfix]</a><br>';
      #echo '<a class="leftmenu" href="system.php?systemmenu=verifymatching">[verifymatching]</a><br>';
      #echo '<a class="leftmenu" href="system.php?systemmenu=verifymatching2">[fix matching]</a><br>';
      echo '<a class="leftmenu" href="system.php?systemmenu=verifymatching3">[fix ALL matching]</a><br>';
      #echo '<a class="leftmenu" href="system.php?systemmenu=verifymatchingretro">[verifymatchingretro by cid]</a><br>'; dont use this
      echo '<a class="leftmenu" href="system.php?systemmenu=verify_decimals">[verify écritures]</a><br>';
      echo '<a class="leftmenu" href="system.php?systemmenu=def_val_mod_stock">[determine value mod stock]</a><br>';
      echo '<a class="leftmenu" href="system.php?systemmenu=import_acnumber">[basic ACNUMBER import]</a><br>';
      echo '<br><a class="leftmenu" href="system.php?systemmenu=import_comete">Import COMÈTE</a>';
      echo '<br>'; # below made/refactored after 2020 09 10
      echo '<br><a class="leftmenu" href="system.php?systemmenu=verify_invoiceprice">Verify invoiceprice</a>';
      ?>
    </div>
  </div>
  
</div>
<?php
require ('inc/copyright.php');
?>
</div><div id="mainprogram">
<?php
}
?>