<?php

$offset = $_POST['offset']+0;
$_SESSION['ds_vaimato_decaler'] = $offset;

### Set Default Colors ###
$bgcolor = $_SESSION['ds_bgcolor']; 
if ($bgcolor == "")
{
  if ($_COOKIE['bgcolor'] != "") { $bgcolor = $_COOKIE['bgcolor']; } 
  else { $bgcolor = 'white'; }
}

$fgcolor = $_SESSION['ds_fgcolor'];
if ($fgcolor == "")
{
  if ($_COOKIE['fgcolor'] != "") { $fgcolor = $_COOKIE['fgcolor']; } 
  else { $fgcolor = 'black'; }
}

$linkcolor = $_SESSION['ds_linkcolor'];
if ($linkcolor == "")
{
  if ($_COOKIE['linkcolor'] != "") { $linkcolor = $_COOKIE['linkcolor']; } 
  else { $linkcolor = '#00008b'; }
}

$menucolor = $_SESSION['ds_menucolor'];
if ($menucolor == "")
{
  if ($_COOKIE['menucolor'] != "") { $menucolor = $_COOKIE['menucolor']; } 
  else { $menucolor = '#1e90ff'; }
}

$alertcolor = $_SESSION['ds_alertcolor'];
if ($alertcolor == "")
{
  if ($_COOKIE['alertcolor'] != "") { $alertcolor = $_COOKIE['alertcolor']; } 
  else { $alertcolor = 'red'; }
}

$infocolor = $_SESSION['ds_infocolor'];
if ($infocolor == "")
{
  if ($_COOKIE['infocolor'] != "") { $infocolor = $_COOKIE['infocolor']; } 
  else { $infocolor = 'green'; }
}

$formcolor = $_SESSION['ds_formcolor'];
if ($formcolor == "")
{
  if ($_COOKIE['formcolor'] != "") { $formcolor = $_COOKIE['formcolor']; } 
  else { $formcolor = '#ffdead'; }
}

### End Set Default Colors ###

?>


<STYLE type="text/css">

* {
  margin: 0;
}
html, body {
#  height: 100%;
}

body {
  background-color: transparent;
  color: #000000;
  font-family: 'Times New Roman',Times,serif;
  font-size: 14pt; /* was 13pt */
  text-align: justify;
  margin-top: 0px;
  margin-left: 0px;
  border-collapse: collapse;
}

.tiny {
  font-size: 75%
}

p {
  text-indent: 0em;
}

h1 {
  background: transparent;
  font-weight: bold;
  margin-right: 4%;
  font-size: 200%;
  text-align: right;
  white-space: nowrap;
  margin: 1
}

h2 {
  background: transparent;
  margin-left: 4%;
  margin-right: 4%;
  text-align: left;
  font-size: 150%;
  font-weight: bold;
  white-space: nowrap;
  margin: 1
}

h6 {
  background: <?php echo $menucolor; ?>;
  margin-left: 0%;
  margin-right: 0%;
  vertical-align: bottom;
  font-size: 100%;
  font-weight: bold;
  text-align: center;
  margin-top: 0px;
  margin-left: 0px;
  font-family: "Verdana" 
}

table {
  border-collapse: collapse;
  font-size: 14pt; /* was default */
}

input { 
  background-color: <?php echo $formcolor; ?>;
  color: <?php echo $fgcolor; ?> 
}
select { 
  background-color: <?php echo $formcolor; ?>; 
  color: <?php echo $fgcolor; ?> 
}
textarea { 
  background-color: <?php echo $formcolor; ?>; 
  color: <?php echo $fgcolor; ?> 
}

blockquote {
  font-style: italic;
  margin-left: 6%;
  margin-right: 6%
}

a:link {
  color: <?php echo $linkcolor; ?>;
  background: transparent;
  text-decoration: none
}

a:visited {
  color: <?php echo $linkcolor; ?>;
  background: transparent;
  text-decoration: none
}

a:active {
  color: <?php echo $linkcolor; ?>;
  background: transparent;
  text-decoration: none
}

a:hover {
  color: <?php echo $linkcolor; ?>;
  background: transparent;
  text-decoration: none
}

.menu {
  text-indent: 0em;
  font-size: 65%
}

.normal {
  text-align: justify;
  text-indent: 0em
}

.bar {
  text-indent: 0em;
  font-size: 75%
}

.selectaction {
  text-align: left
}

.clientbox {
  text-align: left;
  position: absolute;
  left: 425px;
  top: 150px;
}

.datebox {
  text-align: left;
  position: absolute;
  left: 250px;
  top: <?php echo (275+($offset/2)); ?>px /* was 270 */
}

.invoiceidbox {
  text-align: left;
  position: absolute;
  left: 45px; /* was 35 */
  top: <?php echo (360+$offset); ?>px;
  font-size: 90%;
}

.relevebox {
  text-align: left;
  position: absolute;
  left: 200px;
  top: <?php echo (360+$offset); ?>px;
  font-size: 90%;
}

.clientidbox {
  text-align: left;
  position: absolute;
  left: 460px; /* was 480 */
  top: <?php echo (360+$offset); ?>px;
  font-size: 90%;
}

.employeebox {
  text-align: left;
  position: absolute;
  left: 565px; /* was 590 */
  top: <?php echo (360+$offset); ?>px;
  font-size: 90%;
}

<?php
$itemfontsize = '75%';
if ($_POST['itemfontsize'] != '') { $itemfontsize = $_POST['itemfontsize'] + 0; $itemfontsize = $itemfontsize . '%'; }
?>

.items {
  position: absolute;
  left: 55px; /* was 25,35 */
  top: <?php echo (420+$offset); ?>px;
  width: 500px;
  font-size: <?php echo $itemfontsize; ?>;
}

.itemsR {
  position: absolute;
  left: 25px; /* was 25,35 */
  top: <?php echo (420+$offset); ?>px;
  width: 500px;
  font-size: <?php echo $itemfontsize; ?>;
}

.items2 {
  position: absolute;
  left: 600px; /* was 635,650 */
  top: <?php echo (420+$offset); ?>px;
  font-size: <?php echo $itemfontsize; ?>;
}

.itemsline {
  font-family: 'Courier New';
  font-size: <?php echo $itemfontsize; ?>;
}

.totalhtbox {
  position: absolute;
  left: 570px;
  top: <?php echo (895+$offset); ?>px; /* was 900 */
}

.totalhtboxR {
  position: absolute;
  left: 570px; /* was 610 */
  top: <?php echo (925+$offset); ?>px; /* was 910 */
}

.vatbox {
  position: absolute;
  left: 570px;
  top: <?php echo (945+$offset); ?>px /* was 950 */
}

.vatboxR {
  position: absolute;
  left: 570px; /* was 610 */
  top: <?php echo (975+$offset); ?>px /* was 960 */
}

.vatbox2 {
  position: absolute;
  left: 420px;
  top: <?php echo (945+$offset); ?>px
}

.vatbox2R {
  position: absolute;
  left: 420px; /* was 500 */
  top: <?php echo (975+$offset); ?>px /* was 965 */
}

.totalttcbox {
  position: absolute;
  left: 570px;
  top: <?php echo (995+$offset); ?>px /* was 1000 */
}

.totalttcboxR {
  position: absolute;
  left: 570px; /* was 610 */
  top: <?php echo (1025+$offset); ?>px /* was 1010 */
}

.daystopaybox {
  position: absolute;
  left: 65px; /* was 25 */
  top: <?php echo (835+$offset); ?>px; /* was 840 */
  font-size: 80%;
  font-weight: bold;
}

.daystopayboxR {
  position: absolute;
  left: 35px; /* was 25 */
  top: <?php echo (835+$offset); ?>px; /* was 840 */
  font-size: 80%;
  font-weight: bold;
}

.infofact {
  position: absolute;
  left: 0px;
  bottom: 0;
  text-align: center;
  font-size: 50%;
  height: 80px;
  width: 80%
}

.alert {
  color: <?php echo $alertcolor; ?>;
}

.sup {
  vertical-align: super;
  font-size: 75%;
}

.releve1 {
  position: absolute;
  left: 400px;
  top: 0px;
  width: 250px;
  height: 125px
}

.releve2 {
  position: absolute;
  left: 400px;
  top: 75px;
  width: 250px;
  height: 250px
}

P.breakhere {page-break-before: always}

</STYLE>