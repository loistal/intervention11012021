<?php
if ($_SESSION['ds_menustyle'] == 5)
{
?>
<main role="main" >
  <nav id="side-nav">
  <div>
    <div class="close" id="closeSidenav"><span class="marker marker-open"></span></div>
    <ul>
      <li><a href="qr.php?qrmenu=qrbadges">Badges QR</a></li>
      <li class="separator"></li>
      <li><a href="qr.php?qrmenu=qr_locations">Sites QR</a></li>
      <li><a href="qr.php?qrmenu=qr_locations_report">Rapport Sites QR</a></li>
    </ul>
    <?php require('inc/copyright.php'); ?>
  </div>
</nav>
<div class="container" id="main-container">
<?php
}
else
{
?></div><div id="wrapper">

<div id="leftmenu">
<div class="selectaction">
  <div class="selectactiontitle"></div>  
  <div class="selectactionlist">
  <?php
  echo '<a class="leftmenu" href="qr.php?qrmenu=qrbadges">Badges QR</a><br>';
  ?>
  </div>
  <div class="selectactionlist">
  <?php
  echo '<a class="leftmenu" href="qr.php?qrmenu=qr_locations">Sites QR</a><br>';
  echo '<a class="leftmenu" href="qr.php?qrmenu=qr_locations_report">Rapport Sites QR</a><br>';
  ?>
  </div>
</div>
</div>

<div id="mainprogram">
<?php
}
?>