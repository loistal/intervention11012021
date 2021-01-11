<h2>Info lettrage:</h2>
<form method="post" action="reportwindow.php" target="_blank"><table>
<?php
echo '<tr><td>';
require('inc/selectclient.php');
echo ' <span class=alert>Obligatoire</span></td></tr>';
echo '<tr><td>De:</td><td>';
$datename = 'startdate'; $dp_datepicker_min = '2013-12-25';
require('inc/datepicker.php');
echo '</td></tr><tr><td>A:</td><td>';
$datename = 'stopdate';
require('inc/datepicker.php');
echo '</td></tr><tr><td>Utilisateur:';
$dp_itemname = 'user'; $dp_allowall = 1; $dp_noblank = 1;
require('inc/selectitem.php');
echo '</td></tr>';
?>
<tr><td colspan="2" align="center">
<input type=hidden name="report" value="matchingreport">
<input type="submit" value="Valider"></td></tr></table></form>

<br><br><br><br>

<h2>Vérification Lettrage</h2>
<form method="post" action="clients.php"><table>
<tr>
  <td>A partir de l'année :</td>
  <td colspan=2><select name="year"><?php
  $startyear = substr($_SESSION['ds_curdate'],0,4);
      for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
      {
        if ($i == $startyear) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
        else { echo '<option value="' . $i . '">' . $i . '</option>'; }
      }
      ?></select>
<tr><td colspan="2" align="center">
<input type=hidden name="clientsmenu" value="verifymatching">
<input type="submit" value="Valider"></td></tr></table></form>
