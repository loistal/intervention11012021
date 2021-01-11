<h2>Containers / mois:</h2>
<form method="post" action="reportwindow.php" target="reportwindow"><table><tr><td>Année:</td><td><select name="year"><?php
$currentyear = substr($_SESSION['ds_curdate'],0,4)+0;
for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
{
  if ($i == $currentyear) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
  else { echo '<option value="' . $i . '">' . $i . '</option>'; }
}
?></select></td></tr>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="containerspermonth"><input type="submit" value="Valider"></td></tr>
</table></form>