<?php

$width = 760;
$items_left = 20;
$temlogo_offset = 0;
$items_top = 300; if ($invoice_title_below) { $items_top = 260; }
if ($_SESSION['ds_customname'] == 'TAHITI MARQUAGES') { $items_top += 2; }
if ($_SESSION['ds_customname'] == 'SARL TEHEI') { $items_top += 50; }

if(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false) { $width -= 30; $temlogo_offset = 30; }

$invoiceitems_bordercolor = '#F5F5F5';
if($_SESSION['ds_customname'] == 'Espace Paysages') { $invoiceitems_bordercolor = 'black'; }

$infofact_width = $width-70;
if ($summary_top == 0) { $infofact_width = $width; }

if ($narrow_lines) { $width = 700; $items_left = 50; }

?>
<style type="text/css">
/*********************************************/
/*          Base rules                       */
/*********************************************/
body {
  background-color: <?php echo $_SESSION['ds_tablecolor1']; ?>;
  font-family: <?php echo $_SESSION['ds_user_font_print']; ?>;
  line-height: 1.2;
}

.logo {
  text-align: left;
  position: absolute;
  left: 5px;
  top: 5px;
}

.companyinfo {
  position: absolute;
  left: 15px;
  top: 160px;
  font-size: small;
}

.clientinfo {
  position: absolute;
  left: 400px;
  <?php
  if ($_SESSION['ds_customname'] == 'TAHITI MARQUAGES') { echo 'top: 80px; height: 125px;'; }
  else { echo 'top: 160px; height: 125px;'; }
  ?>
  width: 350px;
  font-size: small;
  padding: 5px;
  border: 1px solid;
  -moz-border-radius: 8px;
  -webkit-border-radius: 8px;
  -opera-border-radius: 8px;
  -khtml-border-radius: 8px;
  border-radius: 8px;
}

.clientname {
  font-size: medium;
  font-weight: bold;
}

.invoicetitle {
  position: absolute;
  left: 260px;
  top: 20px;
  font-size: x-large;
  font-weight: bold;
}

.invoicetitle_below {
  font-size: x-large;
  font-weight: bold;
}

.invoicedate {
  position: absolute;
  right: 50px;
  top: 20px;
  font-size: large;
  text-align: right;
}

.items {
  position: absolute;
  left: <?php echo $items_left; ?>px;
  top: <?php echo $items_top; ?>px;
  width: <?php echo $width; ?>px;
}

table.invoiceitems {
  border-collapse:collapse;
  white-space: nowrap;
  border: 1px solid <?php echo $invoiceitems_bordercolor; ?> !important;
}

table.invoiceitems_header {
  width: <?php echo $width; ?>px;
  border-collapse:collapse;
  white-space: nowrap;
  border: none;
}

table.invoiceitems_sub
{
  width: <?php echo $width; ?>px;
  border-collapse:collapse;
  white-space: nowrap;
}

table.invoiceitems td {
  border: 1px solid <?php echo $invoiceitems_bordercolor; ?> !important;
}

td.breakme {
  white-space: normal;
}

table.invoiceitems th {
  border: 1px solid <?php echo $invoiceitems_bordercolor; ?> !important;
  border-left: none;
  border-right: none;
  padding: 1px;
  text-align: center;
}

.small {
  font-size: small;
}

span.header_title {
  font-weight: bold;
}

.infofact {
  position: absolute;
  left: <?php echo $items_left; ?>px;
  bottom: 5px;
  width: <?php echo $infofact_width; ?>px;
}

.logo-tem2 {
  position: absolute;
  bottom: 0;
  right: <?php echo (10+$temlogo_offset); ?>px;
}

.sig-image {
  position: absolute;
  bottom: 50px;
  right: <?php echo (10+$temlogo_offset); ?>px;
}

.sign_box {
  float: right;
  width: 200px;
  height: 100px;
  border: 2px solid black;
  border-radius: 5px;
}

</style>