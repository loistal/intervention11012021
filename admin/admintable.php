<?php
if ($_SESSION['ds_menustyle'] == 5)
{
?>
<main role="main" >
  <nav id="side-nav">
  <div>
    <div class="close" id="closeSidenav"><span class="marker marker-open"></span></div>
    <ul>
      <li><span class="subtitle">Listes</span></li>
      <li><a href="admin.php?adminmenu=additemlist&type=0"><?php echo d_trad('add'); ?></a></li>
      <li><a href="admin.php?adminmenu=additemlist&type=1"><?php echo d_trad('modify'); ?></a></li>
      <li><a href="admin.php?adminmenu=additemlist&type=2"><?php echo d_trad('list'); ?></a></li>
      <li class="separator"></li>
      <li><span class="subtitle"><?php echo d_trad('planning'); ?></span></li>
      <li><a href="admin.php?adminmenu=planning"><?php echo d_trad('add'); ?></a></li>
      <li><a href="admin.php?adminmenu=planningform&actionform=admin"><?php echo d_trad('modify'); ?></a></li>
      <li><a href="admin.php?adminmenu=planningform&actionform=reportwindow"><?php echo d_trad('report'); ?></a></li>
      <li><a href="admin.php?adminmenu=calendarform"><?php echo d_trad('calendar'); ?></a></li>
      <li class="separator"></li>
      <?php if (!isset($enterprisename[2])) { ?>
      <li><a href="admin.php?adminmenu=publicpage"><?php echo d_trad('publicpage'); ?></a></li> <?php } ?>
      <li><a href="admin.php?adminmenu=frontpage"><?php echo d_trad('frontpage'); ?></a></li>
      <li class="separator"></li>
      <li><a href="admin.php?adminmenu=images"><?php echo d_trad('images'); ?></a></li>
      <li class="separator"></li>
      <li><a href="admin.php?adminmenu=palette_color">Couleurs Palette</a></li>
      <li><a href="admin.php?adminmenu=invoice_priceoption2_filter">Filtre
      <?php echo d_output($_SESSION['ds_term_invoice_priceoption2']); ?></a></li>
      <li class="separator"></li>
      <li><a href="admin.php?adminmenu=deleteaction"><?php echo d_trad('deleteaction'); ?></a></li>
      <li class="separator"></li>
      <li><span class="subtitle">Délais Paiement</span></li>
      <li><a href="admin.php?adminmenu=addterm">Ajouter</a></li>
      <li><a href="admin.php?adminmenu=modterm">Modifier</a></li>
      <li><a href="admin.php?adminmenu=listterm">Liste</a></li>
      <li class="separator"></li>
      <li><span class="subtitle">Géographie</span></li>
      <li><a href="admin.php?adminmenu=listtown">Liste</a></li>
      <li><a href="admin.php?adminmenu=addzone">Ajouter région</a></li>
      <li><a href="admin.php?adminmenu=modzone">Modifier région</a></li>
      <li><a href="admin.php?adminmenu=addisland">Ajouter île</a></li>
      <li><a href="admin.php?adminmenu=modisland">Modifier île</a></li>
      <li><a href="admin.php?adminmenu=addtown">Ajouter ville</a></li>
      <li><a href="admin.php?adminmenu=modtown">Modifier ville</a><li>
      <li class="separator"></li>
      <li><span class="subtitle">Familles produit</span></li>
      <li><a href="admin.php?adminmenu=listprodfam">Liste</a></li>
      <li><a href="admin.php?adminmenu=addproddep">Ajouter département</a></li>
      <li><a href="admin.php?adminmenu=modproddep">Modifier département</a></li>
      <li><a href="admin.php?adminmenu=addprodfamgrp">Ajouter famille</a></li>
      <li><a href="admin.php?adminmenu=modprodfamgrp">Modifier famille</a></li>
      <li><a href="admin.php?adminmenu=addprodfam">Ajouter sous-famille</a></li>
      <li><a href="admin.php?adminmenu=modprodfam">Modifier sous-famille</a></li>
      <li class="separator"></li>
      <li><span class="subtitle">Entrepôts</span></li>
      <li><a href="admin.php?adminmenu=warehouselist">Liste</a></li>
      <li><a href="admin.php?adminmenu=addwarehouse">Ajouter entrepôt</a></li>
      <li><a href="admin.php?adminmenu=modwarehouse">Modifier entrepôt</a></li>
      <li><a href="admin.php?adminmenu=addplace">Ajouter emplacement</a></li>
      <li><a href="admin.php?adminmenu=modplace">Modifier emplacement</a></li>
      <li class="separator"></li>
      <li><span class="subtitle">Régl. Prix Détail</span></li>
      <li><a href="admin.php?adminmenu=addregulationtype">Ajouter type</a></li>
      <li><a href="admin.php?adminmenu=modregulationtype">Modifier type</a></li>
      <li><a href="admin.php?adminmenu=listregulationtype">Lister type</a></li>
      <li><a href="admin.php?adminmenu=modregulationmatrix">Modifier valeurs</a></li>
      <li class="separator"></li>
      <li><span class="subtitle">Régl. Prix Détail</span></li> 
      <li><a href="admin.php?adminmenu=addevent"><?php echo d_trad('addevent') ?></a></li>
      <li><a href="admin.php?adminmenu=modevent"><?php echo d_trad('modifyevent') ?></a></li>
      <li><a href="admin.php?adminmenu=listevent"><?php echo d_trad('listevent') ?></a></li>
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
  <div class="selectactiontitle">Listes</div>
  <div class="selectactionlist"> 
    <a class="leftmenu" href="admin.php?adminmenu=additemlist&type=0"><?php echo d_trad('add'); ?></a><br>
    <a class="leftmenu" href="admin.php?adminmenu=additemlist&type=1"><?php echo d_trad('modify'); ?></a><br>
    <a class="leftmenu" href="admin.php?adminmenu=additemlist&type=2"><?php echo d_trad('list'); ?></a>
  </div>
</div>

<div class="selectaction">
  <div class="selectactiontitle"><?php echo d_trad('planning'); ?></div>
  <div class="selectactionlist"> 
  <a class="leftmenu" href="admin.php?adminmenu=planning"><?php echo d_trad('add'); ?></a><br>
  <a class="leftmenu" href="admin.php?adminmenu=planningform&actionform=admin"><?php echo d_trad('modify'); ?></a><br>
  <?php
  echo '<a class="leftmenu" href="admin.php?adminmenu=planningform&actionform=reportwindow">' . d_trad('report') . '</a><br>';
  ?>
  <a class="leftmenu" href="admin.php?adminmenu=calendarform"><?php echo d_trad('calendar'); ?></a><br>

  </div>
</div>

<div class="selectaction">
  <div class="selectactionlist">
  <?php
  if (!isset($enterprisename[2])) { if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo '<a class="leftmenu" href="admin.php?adminmenu=publicpage">' . d_trad('publicpage') . '</a><br>'; }
  if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png"> ';} 
  echo '<a class="leftmenu" href="admin.php?adminmenu=frontpage">' . d_trad('frontpage'). '</a><br><br>';
  if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/picture_add.png"> ';} 
  echo '<a class="leftmenu" href="admin.php?adminmenu=images">' . d_trad('images') . '</a><br>';
  ?><br>
  <a class="leftmenu" href="admin.php?adminmenu=palette_color">Couleurs Palette</a><br>
  <?php
  echo '<a class="leftmenu" href="admin.php?adminmenu=invoice_priceoption2_filter">Filtre '
  .d_output($_SESSION['ds_term_invoice_priceoption2']).'</a><br>';
  ?>
  <br>
  <a class="leftmenu" href="admin.php?adminmenu=deleteaction"><?php echo d_trad('deleteaction'); ?></a>
  </div>
</div>

  <div class="selectaction">
    <div class="selectactiontitle">Délais Paiement</div>  
    <div class="selectactionlist">
      <?php if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo '<a class="leftmenu" href="admin.php?adminmenu=addterm">Ajouter</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=modterm">Modifier</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/information.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=listterm">Liste</a>';?>
    </div>
  </div>
  
  <div class="selectaction">
    <div class="selectactiontitle">Géographie</div>  
    <div class="selectactionlist">
      <?php if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/information.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=listtown">Liste</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=addzone">Ajouter région</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=modzone">Modifier région</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=addisland">Ajouter île</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=modisland">Modifier île</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=addtown">Ajouter ville</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=modtown">Modifier ville</a>';?>
    </div>
  </div>
  
    <div class="selectaction">
    <div class="selectactiontitle">Familles produit</div>  
    <div class="selectactionlist">
      <?php if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/information.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=listprodfam">Liste</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=addproddep">Ajouter département</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=modproddep">Modifier département</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=addprodfamgrp">Ajouter famille</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=modprodfamgrp">Modifier famille</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=addprodfam">Ajouter sous-famille</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=modprodfam">Modifier sous-famille</a>';?>
    </div>
  </div>
  
  <div class="selectaction">
    <div class="selectactiontitle">Entrepôts</div>  
    <div class="selectactionlist">      
      <?php if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/information.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=warehouselist">Liste</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=addwarehouse">Ajouter entrepôt</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=modwarehouse">Modifier entrepôt</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=addplace">Ajouter emplacement</a><br>';
      if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/note_edit.png">'; } echo ' <a class="leftmenu" href="admin.php?adminmenu=modplace">Modifier emplacement</a>';?>
    </div>
  </div>
  
  <div class="selectaction">
    <div class="selectactiontitle">Régl. Prix Détail</div>  
    <div class="selectactionlist">  
      <a class="leftmenu" href="admin.php?adminmenu=addregulationtype">Ajouter type</a><br>
      <a class="leftmenu" href="admin.php?adminmenu=modregulationtype">Modifier type</a><br>
      <a class="leftmenu" href="admin.php?adminmenu=listregulationtype">Lister type</a><br>
      <a class="leftmenu" href="admin.php?adminmenu=modregulationmatrix">Modifier valeurs</a>
    </div>
  </div>
  
  <div class="selectaction">
    <div class="selectactiontitle"><?php echo d_trad('polynesiacalendar') ?></div>  
    <div class="selectactionlist">  
      <a class="leftmenu" href="admin.php?adminmenu=addevent"><?php echo d_trad('addevent') ?></a><br>
      <a class="leftmenu" href="admin.php?adminmenu=modevent"><?php echo d_trad('modifyevent') ?></a><br>
      <a class="leftmenu" href="admin.php?adminmenu=listevent"><?php echo d_trad('listevent') ?></a>
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