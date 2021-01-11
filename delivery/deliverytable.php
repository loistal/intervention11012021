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
if ($_SESSION['ds_usedelivery'] == 1)
{
  if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1)
  {
    echo '<li><a href="delivery.php?deliverymenu=prepare">À Recevoir</a></li>';
  }
  elseif ($_SESSION['ds_deliveryaccessinvoices'] == 1)
  {
    echo '<li><a href="delivery.php?deliverymenu=prepare">À Livrer</a></li>';
  }
  if ($_SESSION['ds_deliveryaccessinvoices'])
  {
    echo '<li><a href="delivery.php?deliverymenu=moddelivery">Relivrer</a></li>
    <li class="separator"></li>';
  }
  if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1)
  {
    echo '<li><a href="delivery.php?deliverymenu=deliverylist">Afficher Réception</a></li>';
  }
  elseif ($_SESSION['ds_deliveryaccessinvoices'] == 1)
  {
    echo '<li><a href="delivery.php?deliverymenu=deliverylist">Afficher Livraison</a></li>';
  }
  if ($_SESSION['ds_deliveryaccessinvoices'])
  {
    echo '<li class="separator"></li>
    <li><a href="delivery.php?deliverymenu=deliverylist3">Feuille Entrepôt</a></li>';
    echo '<li><a href="delivery.php?deliverymenu=transportdelivery">Afficher Tournée</a></li>';
    if ($_SESSION['ds_customname'] == 'Wing Chong') # this report needs to go away!
    {
      echo '<li><a href="delivery.php?deliverymenu=deliverylist4">Feuille Entrepôt (Matrice)</a></li>';
    }
  }
  echo '<li class="separator"></li>';
  if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1)
  {
    echo '<li><a href="delivery.php?deliverymenu=deliveryreport">Rapport Réception</a></li>';
  }
  elseif ($_SESSION['ds_deliveryaccessinvoices'] == 1)
  {
    echo '<li><a href="delivery.php?deliverymenu=deliveryreport">Rapport Livraison</a></li>';
  }
  if ($_SESSION['ds_deliveryaccessinvoices'])
  {
    echo '<li><a href="delivery.php?deliverymenu=moddeliveryreport">Rapport Relivraison</a></li>';
  }
  ?>
  <li class="separator"></li>
  <li><a href="delivery.php?deliverymenu=picking_mod">Modifier Livraison</a></li>
<?php
}
else
{
  if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1)
  {
    echo '<li><a href="delivery.php?deliverymenu=prepare_line">A Recevoir</a></li>';
  }
  elseif ($_SESSION['ds_deliveryaccessinvoices'] == 1)
  {
    echo '<li><a href="delivery.php?deliverymenu=prepare_line">A Livrer</a></li>';
  }
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
<?php
if ($_SESSION['ds_usedelivery'] == 1)
{
?>
  <div class="selectaction">
  <div class="selectactionlist">
  <?php
  if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1)
  {
    if ($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/cart_edit.png">'; }
    echo '<a class="leftmenu" href="delivery.php?deliverymenu=prepare">À Recevoir</a>';
  }
  elseif ($_SESSION['ds_deliveryaccessinvoices'] == 1)
  {
    if ($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/cart_edit.png">'; }
    echo '<a class="leftmenu" href="delivery.php?deliverymenu=prepare">À Livrer</a>';
  }
  if ($_SESSION['ds_deliveryaccessinvoices'])
  {
    echo '<br>';
    if ($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/cart_edit.png">'; }
    echo '<a class="leftmenu" href="delivery.php?deliverymenu=moddelivery">Relivrer</a>';
  }?>
  </div>
  </div>
  <div class="selectaction">
  <div class="selectactionlist">
  <?php
  if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1)
  {
    if ($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/report.png">'; }
    echo '<a class="leftmenu" href="delivery.php?deliverymenu=deliverylist">Afficher Réception</a>';
  }
  elseif ($_SESSION['ds_deliveryaccessinvoices'] == 1)
  {
    if ($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/report.png">'; }
    echo '<a class="leftmenu" href="delivery.php?deliverymenu=deliverylist">Afficher Livraison</a><br>';
  }
  if ($_SESSION['ds_deliveryaccessinvoices'])
  {
    echo '<br>';
    if ($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/report.png">'; }
    echo '<a class="leftmenu" href="delivery.php?deliverymenu=deliverylist3">Feuille Entrepôt</a><br>';
    if ($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/report.png">'; }  
    echo '<a class="leftmenu" href="delivery.php?deliverymenu=transportdelivery">Afficher Tournée</a>';
    if ($_SESSION['ds_customname'] == 'Wing Chong') # this report needs to go away!
    {
      echo '<br>';
      if ($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/report.png">'; }
      echo '<a class="leftmenu" href="delivery.php?deliverymenu=deliverylist4">Feuille Entrepôt (Matrice)</a><br>';
    }
  }?>
  </div>
  </div>
  <div class="selectaction">
  <div class="selectactionlist">
  <?php
  if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1)
  {
    if ($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/report.png">'; }
    echo '<a class="leftmenu" href="delivery.php?deliverymenu=deliveryreport">Rapport Réception</a>';
  }
  elseif ($_SESSION['ds_deliveryaccessinvoices'] == 1)
  {
    if ($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/report.png">'; }
    echo '<a class="leftmenu" href="delivery.php?deliverymenu=deliveryreport">Rapport Livraison</a>';
  }
  if ($_SESSION['ds_deliveryaccessinvoices'])
  {
    echo '<br>';
    if ($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/report.png">'; }  
    echo '<a class="leftmenu" href="delivery.php?deliverymenu=moddeliveryreport">Rapport Relivraison</a>';
  }?>
  <br>
  <br>
  <a class="leftmenu" href="delivery.php?deliverymenu=picking_mod">Modifier Livraison</a><br>
  </div>
  </div>
<?php
}
else
{
  ?>
  <div class="selectaction">
  <div class="selectactionlist">
  <?php
  if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1)
  {
    if ($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/cart_edit.png">'; }
    echo '<a class="leftmenu" href="delivery.php?deliverymenu=prepare_line">A Recevoir</a>';
  }
  elseif ($_SESSION['ds_deliveryaccessinvoices'] == 1)
  {
    if ($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/cart_edit.png">'; }
    echo '<a class="leftmenu" href="delivery.php?deliverymenu=prepare_line">A Livrer</a>';
  }
  ?>
  </div>
  </div>
  <?php
}
?>
</div>
<?php
require ('inc/searchbox.php');
require ('inc/copyright.php');
?>
</div><div id="mainprogram">
<?php
}
?>