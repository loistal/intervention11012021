<?php

$width = 760;
$items_left = 20;
$temlogo_offset = 0;
$items_top = 300; if ($invoice_title_below) { $items_top = 260; }

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
  font-family: <?php /*echo $_SESSION['ds_user_font_print'];*/ ?>PT Sans Narrow;
}

.line1 {
  border-left: 1px solid black;
  height: 700px;
  position: absolute;
  left: 35px;
  top: 275px;
}

.line2 {
  border-left: 1px solid black;
  height: 700px;
  position: absolute;
  left: 749px;
  top: 275px;
}

.logo {
  text-align: left;
  position: absolute;
  left: 35px;
  top: 50px;
}

td.border{
  border: 1px solid #000000;
  font-size: small;
}

td.border_right{
  border: 1px solid #000000;
  font-size: small;
  padding-left: 20px;
}

td.bottom{
  border: 1px solid #000000;
  font-size: xx-small;
}

td.items{
  border-left: 1px solid #000000;
  border-right: 1px solid #000000;
}

td.header{
  border: 1px solid #000000;
  text-align: center;
  font-weight: bold;
}

.companyinfo {
  position: absolute;
  left: 15px;
  bottom: 20px;
  font-size: x-small;
  width: <?php echo $width; ?>px;
  text-align: center;
}

.totals {
  position: absolute;
  left: 35px;
  bottom: 70px;
  font-size: small;
}

.clientinfo {
  position: absolute;
  left: 35.5px;
  top: 170px;
  font-size: small;
}

.clientname {
  font-size: medium;
  font-weight: bold;
}

td.invoicetitle {
  font-size: x-large;
  font-weight: bold;
  padding-left: 10px;
}

.invoicetitle_below {
  font-size: x-large;
  font-weight: bold;
}

.invoicedate {
  position: absolute;
  right: 120px;
  top: 75px;
  font-size: medium;
  text-align: right;
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

</style>