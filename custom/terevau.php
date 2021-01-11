<?php

# Build web page
require ('inc/standard.php');
require ('inc/top.php');
require ('inc/logo.php');
require ('inc/menu.php');

# table
?>
</div><div id="wrapper">
<title>Terevau</title>
<div id="leftmenu">
  <div id="selectactionbar">
    <div class="selectaction">
      <?php
      if ($_SESSION['ds_accountingaccess'])
      {
        echo '&nbsp; <a href="custom.php?custommenu=export">Export SAGE</a><br>';
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
  
  case 'export':
    echo '<h2>Export SAGE</h2>';
    ?>
    <form method="post" action="customreportwindow.php" target="_blank"><table><?php
    echo '<tr><td>Début:</td><td>';
    $datename = 'startdate';
    require ('inc/datepicker.php');
    echo '</td></tr>';
    echo '<tr><td>Fin:</td><td>';
    $datename = 'stopdate';
    require ('inc/datepicker.php');
    echo '</td></tr>';
    ?>
    <tr><td colspan="2" align="center"><input type="submit" value="Valider"></td></tr>
    <input type=hidden name="report" value="export">
    </table></form><?php
  break;

  default:

  break;
}
?>

<?php
require ('inc/bottom.php');
?>