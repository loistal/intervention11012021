<?php

require ('inc/standard.php');
require ('inc/top.php');
require ('inc/logo.php');
require ('inc/menu.php');

?>
</div><div id="wrapper">
<title>Fenua AC Cleaner</title>
<div id="leftmenu">
  <div id="selectactionbar">
    <div class="selectaction">
      <?php
      if ($_SESSION['ds_deliveryaccess'])
      {
      echo '&nbsp; <a href="custom.php?custommenu=prepare">À Livrer (devis)</a><br>';
      echo '<br>';
      echo '&nbsp; <a href="custom.php?custommenu=deliverylist">Afficher livraison (devis)</a><br>';
      echo '<br>';
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

$currentstep = 0;
if (isset($_POST['step'])) { $currentstep = $_POST['step']+0; }

# Go to the menuitem
switch($custommenu)
{
  case 'prepare':
  require('fenua ac cleaner_prepare.php');
  break;
  
  case 'deliverylist':
  ?><h2>Afficher livraison:</h2>
  <form method="post" action="customreportwindow.php" target="_blank"><table>
  <tr><td>Numéro:</td>
  <td><input autofocus type="number" min="0" step="1" STYLE="text-align:right" name="invoicegroupid" size=10></td></tr>
  <tr><td>&nbsp;
  <tr><td colspan="2" align="center"><input type=hidden name="report" value="deliverylist"><input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;
  
  default:

  break;
}
?>

<?php
require ('inc/bottom.php');
?>