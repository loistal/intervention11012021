<?php
if ($_SESSION['ds_menustyle'] == 5)
{
?>
<main role="main" >
<nav id="side-nav">
<div>
<div class="close" id="closeSidenav"><span class="marker marker-open"></span></div>
<ul>
<?php
if ($_SESSION['ds_myemployeeid'] > 0 && $_SESSION['ds_warehouseaccesstype'] > 0)
{
  require('preload/employee.php');
  if ($employee_ispickingA[$_SESSION['ds_myemployeeid']])
  {
    echo '<li><a href="reportwindow.php?report=picking_interface" target=_blank>Interface Picking</a></li>
    <li class="separator"></li>';
  }
}
if ($_SESSION['ds_warehouseaccesstype'] != 2)
{
  echo '<li><a href="reportwindow.php?report=warehouseinterface" target=_blank>Interface Entrepôt</a></li>
  <li class="separator"></li>';
}
if ($_SESSION['ds_warehouseaccesstype'] == 1)
{
  ?>
  <li><span class="subtitle">Conteneur</span></li>
  <li><a href="warehouse.php?warehousemenu=prearrival">Préparation Arrivage</a></li>
  <li><a href="warehouse.php?warehousemenu=startarrival">Ouverture</a></li>
  <li><a href="warehouse.php?warehousemenu=arrival">Dépotage</li> (mise en palettes)</a></li>
  <li><a href="warehouse.php?warehousemenu=stoparrival">Fermeture</a></li>
  <li class="separator"></li>
  <li><span class="subtitle">Palette</span></li>
  <li><a href="warehouse.php?warehousemenu=modpallet">Correction</a></li>
  <li class="separator"></li>
  <li><span class="subtitle">Code Barres Palette</span></li>
  <li><a href="warehouse.php?warehousemenu=createpalletbarcode">Création</a></li>
  <li><a href="warehouse.php?warehousemenu=displaypalletbarcode">Edition</a></li>
  <li><a href="warehouse.php?warehousemenu=barcodereport">Rapport</a></li>
  <li class="separator"></li>
  <li><span class="subtitle">Rapports Stock</span></li>
  <li><a href="warehouse.php?warehousemenu=placementreport">Par emplacement</a></li>
  <li><a href="warehouse.php?warehousemenu=foundplacement">Par produit</a></li>
  <li><a href="warehouse.php?warehousemenu=logpalletreport">Correction / Déplacement</a></li>
  <li class="separator"></li>
  <li><span class="subtitle">Picking</span></li>
  <a href="warehouse.php?warehousemenu=picking_report">Rapport</a></li>
  <?php
}
?>
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
</div><div id="wrapper">
<div id="leftmenu">
<div id="selectactionbar">
<div class="selectaction">
<div class="selectactionlist">
<?php
if ($_SESSION['ds_myemployeeid'] > 0 && $_SESSION['ds_warehouseaccesstype'] > 0)
{
  require('preload/employee.php');
  if ($employee_ispickingA[$_SESSION['ds_myemployeeid']])
  {
    echo '<b><a class="leftmenu" href="reportwindow.php?report=picking_interface" target=_blank>Interface Picking</a></b><br><br>';
  }
}
if ($_SESSION['ds_warehouseaccesstype'] != 2)
{
  echo '<b><a class="leftmenu" href="reportwindow.php?report=warehouseinterface" target=_blank>Interface Entrepôt</a></b><br>';
}
if ($_SESSION['ds_warehouseaccesstype'] == 1)
{
  ?>
  <br>
  <b>Conteneur</b><br>
  <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png"><?php } ?><a class="leftmenu" href="warehouse.php?warehousemenu=prearrival">Préparation Arrivage</a><br>
  <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png"><?php } ?><a class="leftmenu" href="warehouse.php?warehousemenu=startarrival">Ouverture</a><br>
  <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png"><?php } ?><a class="leftmenu" href="warehouse.php?warehousemenu=arrival">Dépotage<br> (mise en palettes)</a><br>
  <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png"><?php } ?><a class="leftmenu" href="warehouse.php?warehousemenu=stoparrival">Fermeture</a><br>
  <br>
  <b>Palette</b><br>
  <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?> <img src="pics/lorry_add.png"> <?php } ?><a class="leftmenu" href="warehouse.php?warehousemenu=modpallet">Correction</a><br>
  <br>
  <b>Code Barres Palette</b><br>
  <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/note.png"><?php } ?><a class="leftmenu" href="warehouse.php?warehousemenu=createpalletbarcode">Création</a><br>
  <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/printer.png"><?php } ?><a class="leftmenu" href="warehouse.php?warehousemenu=displaypalletbarcode">Edition</a><br>
  <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png"><?php } ?><a class="leftmenu" href="warehouse.php?warehousemenu=barcodereport">Rapport</a><br>
  <br>
  <b>Rapports Stock</b><br>
  <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png"><?php } ?><a class="leftmenu" href="warehouse.php?warehousemenu=placementreport">Par emplacement</a><br>
  <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png"><?php } ?><a class="leftmenu" href="warehouse.php?warehousemenu=foundplacement">Par produit</a><br>
  <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png"><?php } ?><a class="leftmenu" href="warehouse.php?warehousemenu=logpalletreport">Correction / Déplacement</a><br>
  <br>
  <b>Picking</b><br>
  <a class="leftmenu" href="warehouse.php?warehousemenu=picking_report">Rapport</a><br>
  <?php
}
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