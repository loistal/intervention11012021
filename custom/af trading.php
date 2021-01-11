<?php

# Build web page
require ('inc/standard.php');
require ('inc/top.php');
require ('inc/logo.php');
require ('inc/menu.php');

# table
?>
</div><div id="wrapper">
<title>AF Trading</title>
<div id="leftmenu">
  <div id="selectactionbar">
    <div class="selectaction">
      <?php
      if ($_SESSION['ds_systemaccess'])
      {
      echo '&nbsp; <a href="custom.php?custommenu=import">Import Shopify</a><br>';
      }
      ?>
      <br>
    </div>
  </div>

<?php
require ('inc/copyright.php');
?>
</div><div id="mainprogram">
<?php

$custommenu = "";
if (isset($_GET['custommenu'])) { $custommenu = $_GET['custommenu']; }
if (isset($_POST['custommenu'])) { $custommenu = $_POST['custommenu']; }
$custommenu = d_safebasename($custommenu);

# Go to the menuitem
switch($custommenu)
{
  
  case 'import':
  require('af trading_import.php');
  break;
  
  default:

  break;
}
?>

<?php
require ('inc/bottom.php');
?>