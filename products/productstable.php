<?php

$_SESSION['ds_canmainproducts'] = 1;
if ($_SESSION['ds_customname'] == 'Wing Chong' && $_SESSION['ds_userid'] == 65) { $_SESSION['ds_canmainproducts'] = 0; }

$temp_detail_access = 1;
if ($_SESSION['ds_customname'] == 'Wing Chong' && $_SESSION['ds_userid'] == 65) { $temp_detail_access = 0; }

if ($_SESSION['ds_menustyle'] == 5)
{
?>
<main role="main" >
<nav id="side-nav">
<div>
<div class="close" id="closeSidenav"><span class="marker marker-open"></span></div>
<ul>
<?php
if ($_SESSION['ds_canmainproducts']) 
{
  echo '<li><span class="subtitle">'.d_trad('products').'</span></li>'; ?>
  <li><a href="products.php?productsmenu=addproduct"><?php echo d_trad('add');?></a></li>
  <li><a href="products.php?productsmenu=modproduct"><?php echo d_trad('modify');?></a></li>
  <li><a href="products.php?productsmenu=productreport"><?php echo d_trad('report');?></a></li>
  <?php
  echo '<li><a href="products.php?productsmenu=images">' . d_trad('images') . '</a></li>';
  echo '<li><a href="products.php?productsmenu=productaction">Évènement</a></li>';
  echo '<li><a href="products.php?productsmenu=showproductactions">Rapport Évènements</a></li>';
}
if ($_SESSION['ds_canchangeprice']) 
{ 
  if ($temp_detail_access)
  {
    echo '<li class="separator"></li>
    <li><span class="subtitle">'.d_trad('price').'</span></li>'; ?>   
    <li><a href="products.php?productsmenu=salesprice"><?php echo d_trad('price');?></a></li>
    <li><a href="products.php?productsmenu=salespricelog"><?php echo d_trad('pricehistory');?></a></li><?php
  }
  echo '<li class="separator"></li>
  <li><span class="subtitle">'.d_trad('exceptions').'</span></li>'; ?>
  <li><a href="products.php?productsmenu=categorypricenew"><?php echo 'Prix/',$_SESSION['ds_term_clientcategory'];?></a></li>
  <li><a href="products.php?productsmenu=categorypricenew&tm=2"><?php echo 'Prix/',$_SESSION['ds_term_clientcategory2'];?></a></li>
  <li><a href="products.php?productsmenu=categorypricenew&tm=3"><?php echo 'Prix/',$_SESSION['ds_term_clientcategory3'];?></a></li>
  <?php 
  if ($temp_detail_access)
  { ?>
    <li><a href="products.php?productsmenu=dateprice"><?php echo d_trad('pricebydate');?></a></li>
    <li><a href="products.php?productsmenu=regionprice"><?php echo d_trad('pricebyregion');?></a></li>
    <li><a href="products.php?productsmenu=islandprice"><?php echo d_trad('pricebyisland');?></a></li><?php
  } ?>
  <li><a href="products.php?productsmenu=clientprice"><?php echo d_trad('pricebyclient');?></a></li>
  <?php
  if ($temp_detail_access)
  { ?>
    <li><a href="products.php?productsmenu=freemonthly"><?php echo d_trad('freebymonth');?></a></li>
    <li><a href="products.php?productsmenu=price_minimum">Prix minimum</a></li>
    <li><a href="products.php?productsmenu=listprice"><?php echo d_trad('pricebylist');?></a></li>
    <li><a href="products.php?productsmenu=listpricecat"><?php echo d_trad('modifylists');?></a></li>
    <li><a href="products.php?productsmenu=calcprice"><?php echo d_trad('pricebycalculation');?></a></li><?php
  } ?>
  <li><a href="products.php?productsmenu=productpriceexceptions"><?php echo d_trad('listofexceptions');?></a></li>
  <?php
}
if ($_SESSION['ds_canchangestock'])
{
  echo '<li class="separator"></li>
  <li><span class="subtitle">'.d_trad('stock').'</span></li>'; ?>
  <li><a href="products.php?productsmenu=modifystock"><?php echo d_trad('stock');?></a></li>
  <?php
  if ($_SESSION['ds_stockperuser'] && $_SESSION['ds_stockperthisuser'])
  { ?>
    <li><a href="products.php?productsmenu=receiveuserstock">Réception</a></li><?php
  }
  if ($_SESSION['ds_canchangestock'] == 1)
  { ?>
    <li><a href="products.php?productsmenu=countstock">Comptage / Inventaire</a></li><?php
  }
  if ($_SESSION['ds_canchangestock'] == 1 || $_SESSION['ds_customname'] == 'ANIMALICE')
  { ?>
    <li><a href="products.php?productsmenu=modifiedstockreport">Rapport <?php echo d_trad('adjustments');?></a></li>
    <?php
  }
  if ($_SESSION['ds_stockperuser'])
  { ?>
    <li><a href="products.php?productsmenu=stockperuserreport">Rapport Stock Utilisateur</a></li><?php
  }
  if ($_SESSION['ds_canchangestock'] == 1)
  { ?>
    <li class="separator"></li>
    <li><a href="products.php?productsmenu=consignment">Dépôt-vente</a></li>
    <li class="separator"></li>
    <li><a href="products.php?productsmenu=endyearstock"><?php echo d_trad('endyearstock');?></a></li>
    <li><a href="products.php?productsmenu=endyearreport"><?php echo d_trad('endyearreport');?></a></li>
    <?php
    if ($_SESSION['ds_customname'] == 'Vaimato') { ?>
    <li class="separator"></li>
    <li><span class="subtitle"><?php echo d_trad('clientstock') ?></span></li>
    <li><a href="products.php?productsmenu=clientstock"><?php echo d_trad('clientstock');?></a></li>
    <li><a href="products.php?productsmenu=reportclientstock"><?php echo d_trad('report');?></a></li>
    <?php }
  }
} ?>
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
  <?php if ($_SESSION['ds_canmainproducts']) 
  { ?>
  <div class="selectaction">
    <div class="selectactiontitle"><?php echo d_trad('products');?></div>
    <div class="selectactionlist">
      <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/tag_blue_add.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=addproduct"><?php echo d_trad('add');?></a><br>
      <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/tag_blue_edit.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=modproduct"><?php echo d_trad('modify');?></a><br>
      <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/tag_blue.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=productreport"><?php echo d_trad('report');?></a><br>
      <?php
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/picture_add.png"> ';} 
      echo '<a class="leftmenu" href="products.php?productsmenu=images">' . d_trad('images') . '</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/exclamation.png">'; }
      echo '<a class="leftmenu" href="products.php?productsmenu=productaction">Évènement</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/report.png">'; }
      echo '<a class="leftmenu" href="products.php?productsmenu=showproductactions">Rapport Évènements</a><br>';
      ?>
     </div>
  </div>
  <?php
  }
  if ($_SESSION['ds_canchangeprice']) 
  {
    if ($temp_detail_access)
    {
    ?>
    <div class="selectaction">
      <div class="selectactiontitle"><?php echo d_trad('price');?></div>
      <div class="selectactionlist">    
        <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/money_add.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=salesprice"><?php echo d_trad('price');?></a><br>
        <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=salespricelog"><?php echo d_trad('pricehistory');?></a><br>
      </div>
    </div>
    <?php
    }
    ?>
    <div class="selectaction">
      <div class="selectactiontitle"><?php echo d_trad('exceptions');?></div>
      <div class="selectactionlist">
        <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/money_add.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=categorypricenew"><?php echo 'Prix/',$_SESSION['ds_term_clientcategory'];?></a><br>
        <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/money_add.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=categorypricenew&tm=2"><?php echo 'Prix/',$_SESSION['ds_term_clientcategory2'];?></a><br>
        <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/money_add.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=categorypricenew&tm=3"><?php echo 'Prix/',$_SESSION['ds_term_clientcategory3'];?></a><br>
        <?php 
        if ($temp_detail_access)
        {     if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/money_add.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=dateprice"><?php echo d_trad('pricebydate');?></a><br>
        <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/money_add.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=regionprice"><?php echo d_trad('pricebyregion');?></a><br>
        <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/money_add.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=islandprice"><?php echo d_trad('pricebyisland');?></a><br>
        <?php
        }
              if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/money_add.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=clientprice"><?php echo d_trad('pricebyclient');?></a><br>
        <?php
        if ($temp_detail_access)
        {
              if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/money_add.png"> <?php } ?><a class="leftmenu" href="products.php?productsmenu=freemonthly"><?php echo d_trad('freebymonth');?></a><br>
        <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/money_add.png"> <?php } ?><a class="leftmenu" href="products.php?productsmenu=price_minimum">Prix minimum</a><br>
        <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/money_add.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=listprice"><?php echo d_trad('pricebylist');?></a><br>
        <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/application_form_add.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=listpricecat"><?php echo d_trad('modifylists');?></a><br>
        <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/money_add.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=calcprice"><?php echo d_trad('pricebycalculation');?></a><br>
        <?php
        }
        if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/information.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=productpriceexceptions"><?php echo d_trad('listofexceptions');?></a><br>
      </div>
    </div>
    <?php
  }

  if ($_SESSION['ds_canchangestock'])
  { ?>
    <div class="selectaction">
      <div class="selectactiontitle"><?php echo d_trad('stock');?></div>
      <div class="selectactionlist"> 
        <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/lorry.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=modifystock"><?php echo d_trad('stock');?></a><br>
        <?php
        if ($_SESSION['ds_stockperuser'] && $_SESSION['ds_stockperthisuser'])
        {
          if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/lorry.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=receiveuserstock">Réception</a><br><?php
        }
        if ($_SESSION['ds_canchangestock'] == 1)
        {
          if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/lorry.png">'; }; echo '<a class="leftmenu" href="products.php?productsmenu=countstock">Comptage / Inventaire</a><br>';
        }
        if ($_SESSION['ds_canchangestock'] == 1 || $_SESSION['ds_customname'] == 'ANIMALICE')
        {
          if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=modifiedstockreport">Rapport <?php echo d_trad('adjustments');?></a><br>
          <?php
        }
        if ($_SESSION['ds_stockperuser'])
        {
          if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=stockperuserreport">Rapport Stock Utilisateur</a><br><?php
        }
        ?>
      </div>
    </div>
    <?php
    if ($_SESSION['ds_canchangestock'] == 1)
    {
    ?>
    <div class="selectaction">
      <div class="selectactionlist"> 
        <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/lorry.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=consignment">Dépôt-vente</a><br>
      </div>
    </div>
    <div class="selectaction">
      <div class="selectactionlist"> 
        <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/application_form_add.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=endyearstock"><?php echo d_trad('endyearstock');?></a><br>
        <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=endyearreport"><?php echo d_trad('endyearreport');?></a><br>
      </div>
    </div>
    <?php
    if ($_SESSION['ds_customname'] == 'Vaimato') { ?>
    <div class="selectaction">
      <div class="selectactiontitle"><?php echo d_trad('clientstock');?></div>   
      <div class="selectactionlist">     
        <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/lorry_go.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=clientstock"><?php echo d_trad('clientstock');?></a><br>
        <?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png"><?php } ?> <a class="leftmenu" href="products.php?productsmenu=reportclientstock"><?php echo d_trad('report');?></a><br>
      </div>
    </div>
    <?php }
    }
  }
?>
</div>
<?php
require ('inc/copyright.php');
?>
</div><div id="mainprogram">
<?php
}
?>