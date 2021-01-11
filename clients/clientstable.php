<?php
if ($_SESSION['ds_menustyle'] == 5)
{
?>
<main role="main">
<nav id="side-nav">
<div>
<div class="close" id="closeSidenav"><span class="marker marker-open"></span></div>
<ul>
<li><a href="clients.php?clientsmenu=modclient">Client<?php
if ($_SESSION['ds_purchaseaccess'] || $_SESSION['ds_accountingaccess']) { echo '/Fournisseur'; }
?></a></li>
<li><a href="clients.php?clientsmenu=clientreport">Rapport clients</a></li>
<li><a href="clients.php?clientsmenu=images"><?php echo d_trad('images'); ?></a></li>
<li class="separator"></li>
<li><a href="clients.php?clientsmenu=client_accounts">Comptes Client</a></li>
<li><a href="clients.php?clientsmenu=releves">Relevés</a></li>
<li><a href="clients.php?clientsmenu=behind">Retards paiement</a></li>
<li><a href="clients.php?clientsmenu=balanceage">Balance Âgée</a></li>
<?php
if ($_SESSION['ds_can_send_emails'])
{
  ?><li class="separator"></li>
  <li><a href="clients.php?clientsmenu=email_body">E-mails</a></li><?php
} ?>
<li class="separator"></li>
<li><span class="subtitle">Lettrage</span></li>
<li><a href="clients.php?clientsmenu=match">Lettrer client</a></li>
<li><a href="clients.php?clientsmenu=unmatch">Délettrer</a></li>
<?php
if ($_SESSION['ds_tva_encaissement'] > 0)
{
  echo '<li><a href="clients.php?clientsmenu=mod_matching">Modifier date</a></li>';
}
?>
<li><a href="clients.php?clientsmenu=matchingreport">Info</a></li>
<li class="separator"></li>
<li><span class="subtitle">Adresses suppl</span></li>
<li><a href="clients.php?clientsmenu=addextaddr">Ajouter</a></li>
<li><a href="clients.php?clientsmenu=modextaddr">Modifier</a></li>
<li><a href="clients.php?clientsmenu=delextaddr">Supprimer</a></li>
<li><a href="clients.php?clientsmenu=listextaddr">Adresses suppl</a></li>
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
if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/vcard_edit.png">'; }
echo '<a class="leftmenu" href="clients.php?clientsmenu=modclient">Client';
if ($_SESSION['ds_purchaseaccess'] || $_SESSION['ds_accountingaccess'])
{
  echo '/Fournisseur';
}
?>
</a><br>
<?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/vcard.png">  <?php } ?><a class="leftmenu" href="clients.php?clientsmenu=clientreport">Rapport clients</a><br>
<?php
if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/picture_add.png"> ';} 
echo '<a class="leftmenu" href="clients.php?clientsmenu=images">' . d_trad('images') . '</a><br>';
?>
</div>
</div>
<div class="selectaction">
<div class="selectactionlist">
<?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png"> <?php } ?> <a class="leftmenu" href="clients.php?clientsmenu=client_accounts">Comptes Client</a><br>
<?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png"> <?php } ?> <a class="leftmenu" href="clients.php?clientsmenu=releves">Relevés</a><br>
<?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png">  <?php } ?><a class="leftmenu" href="clients.php?clientsmenu=behind">Retards paiement</a><br>
<?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png"> <?php } ?> <a class="leftmenu" href="clients.php?clientsmenu=balanceage">Balance Âgée</a><br>
</div>
</div>
<?php if ($_SESSION['ds_can_send_emails']) { ?>
<div class="selectaction">
<div class="selectactionlist">
<?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/email_go.png"> <?php } ?> <a class="leftmenu" href="clients.php?clientsmenu=email_body">E-mails</a><br>
</div>
</div>
<?php } ?>
<div class="selectaction">
<div class="selectactiontitle">Lettrage</div>
<div class="selectactionlist">
<?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/tick.png">  <?php } ?><a class="leftmenu" href="clients.php?clientsmenu=match">Lettrer client</a><br>
<?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/cancel.png"> <?php } ?> <a class="leftmenu" href="clients.php?clientsmenu=unmatch">Délettrer</a><br>

<?php
if ($_SESSION['ds_tva_encaissement'] > 0)
{
  if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; }
  echo '<a class="leftmenu" href="clients.php?clientsmenu=mod_matching">Modifier date</a><br>';
}

if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/report.png">  <?php } ?><a class="leftmenu" href="clients.php?clientsmenu=matchingreport">Info</a><br>
</div>
</div>
<div class="selectaction">
<div class="selectactiontitle">Adresses suppl</div>
<div class="selectactionlist">
<?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/note_add.png">  <?php } ?><a class="leftmenu" href="clients.php?clientsmenu=addextaddr">Ajouter</a><br>
<?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/note_edit.png">  <?php } ?><a class="leftmenu" href="clients.php?clientsmenu=modextaddr">Modifier</a><br>
<?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/note_delete.png">  <?php } ?><a class="leftmenu" href="clients.php?clientsmenu=delextaddr">Supprimer</a><br>
<?php if ( $_SESSION['ds_displayicons'] == 1 ) { ?><img src="pics/information.png">  <?php } ?><a class="leftmenu" href="clients.php?clientsmenu=listextaddr">Adresses suppl</a><br>
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