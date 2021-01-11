<?php
if ($_SESSION['ds_menustyle'] == 5)
{
?>
<main role="main" >
<nav id="side-nav">
<div>
<div class="close" id="closeSidenav"><span class="marker marker-open"></span></div>
<ul>
<li <?php if ($optionsmenu=='myoptions')echo' class="active"';?>>
<a href="options.php?optionsmenu=myoptions"><i class="fad fa-file-edit"></i><?php echo d_trad('myoptions'); ?></a></li>
<li class="separator"></li>
<li><span class="subtitle">Apparence</span></li>
<li <?php if ($optionsmenu=='changeappeareance')echo' class="active"';?>><a href="options.php?optionsmenu=changeappeareance"><i class="fad fa-file-edit"></i>Modifier apparence</a></li>
<li <?php if ($optionsmenu=='modifycolors')echo' class="active"';?>><a href="options.php?optionsmenu=modifycolors"><i class="fad fa-file-edit"></i>Modifier vos palettes</a></li>
<li <?php if ($optionsmenu=='addcolors')echo' class="active"';?>><a href="options.php?optionsmenu=addcolors"><i class="fad fa-file-edit"></i>Créer une palette</a></li>
<li <?php if ($optionsmenu=='font')echo' class="active"';?>><a href="options.php?optionsmenu=font"><i class="fad fa-file-edit"></i><?php echo d_trad('font'); ?></a></li>
<li <?php if ($optionsmenu=='backgroundimage')echo' class="active"';?>><a href="options.php?optionsmenu=backgroundimage"><i class="fad fa-file-edit"></i>Fond d'écran</a></li>
<li <?php if ($optionsmenu=='password')echo' class="active"';?>><a href="options.php?optionsmenu=password"><i class="fad fa-file-edit"></i><?php echo d_trad('password'); ?></a></li>
<?php if(isset($_SESSION['ds_hidetop']) && $_SESSION['ds_hidetop'] == 1) { ?>
<li class="separator"></li>
<li><a href="logout.php"><i class="fa fa-power-off"></i>Déconnexion</a></li>
<?php } ?>
<?php /* example
<li><span class="subtitle">Facutres</span></li>
<li><a href="#" class="button button-outline"><i class="fa fa-plus-circle"></i>Créer</a></li>
<li><a href="#"><i class="fad fa-file-edit"></i>Modifier</a></li>
<li><a href="#"><i class="fad fa-copy"></i>Copier</a></li>
<li><a href="#"><i class="fad fa-file-edit"></i>Recommander</a></li>
<li class="active"><a href="#"><i class="fad fa-image"></i>Images</a></li>
<li><a href="#"><i class="fad fa-check-circle"></i>Confirmer / Annuler</a></li>
<li class="separator"></li>
<li><a href="#"><i class="fad fa-money-bill-wave"></i>Paiement</a></li>
<li><a href="#"><i class="fad fa-calendar-alt"></i>Évènement</a></li>
<li><a href="#"><i class="fad fa-handshake"></i>Prendre RDV</a></li>
<li class="separator"></li>
<li><a href="#"><i class="fad fa-file-invoice"></i>Afficher facture</a></li>
<li><a href="#"><i class="fad fa-id-card"></i>Fiche client</a></li>
<li><a href="#"><i class="fad fa-id-card"></i>Compte client</a></li>
<li class="separator"></li>
<li><a href="#"><i class="fad fa-file-invoice-dollar"></i>Rapport factures</a></li>
<li><a href="#"><i class="fad fa-list-alt"></i>Produits vendus</a></li>
<li><a href="#"><i class="fad fa-file-invoice-dollar"></i>Rapport paiements</a></li>
<li><a href="#"><i class="fad fa-calendar"></i>Rapport évènements</a></li>
*/ ?>
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
</div>
<div id="wrapper">
<div id="leftmenu">
<div id="selectactionbar">
<div class="selectaction">
<div class="selectactionlist">
<?php

if($_SESSION['ds_displayicons'] == 1) {
  echo '<img src="pics/wrench_orange.png">';
}
echo '<a class="leftmenu" href="options.php?optionsmenu=myoptions">' . d_trad('myoptions') . '</a><br><br>';

echo '<span style="font-weight: bold;">Apparence</span><br>';

if($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/palette.png">'; }
echo '<a class="leftmenu" href="options.php?optionsmenu=changeappeareance">Modifier apparence</a><br/>';

if($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/palette.png">'; }
echo '<a class="leftmenu" href="options.php?optionsmenu=modifycolors">' . 'Modifier vos palettes' . '</a><br/>';

if($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/palette.png">'; }
echo '<a class="leftmenu" href="options.php?optionsmenu=addcolors">' . 'Créer une palette' . '</a><br/><br/>';

if($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/style.png"> '; }
echo '<a class="leftmenu" href="options.php?optionsmenu=font">' . d_trad('font') . '</a><br><br>';

if($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/picture.png"> '; }
echo '<a class="leftmenu" href="options.php?optionsmenu=backgroundimage">Fond d\'écran</a><br><br>';

if($_SESSION['ds_displayicons'] == 1) { echo '<img src="pics/key.png">'; }
echo '<a class="leftmenu" href="options.php?optionsmenu=password">' . d_trad('password') . '</a>';

if(isset($_SESSION['ds_hidetop']) && $_SESSION['ds_hidetop'] == 1) {
  echo '<br><br><form class="loginbox" method="post" action="logout.php"><button type="submit">Déconnexion</button></form>';
}
?>
</div>
</div>
</div>
<?php
require('inc/copyright.php');
?>
</div>
<div id="mainprogram">
<?php
}
?>