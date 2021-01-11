<h2>Déclaration importations en valeur</h2>
<form method="post" action="reportwindow.php" target="_blank">
<table><tr><td>Année:<td>
<select name="year"><?php
$year = mb_substr($_SESSION['ds_curdate'],0,4)+0;
for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
{
  if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
  else { echo '<option value="' . $i . '">' . $i . '</option>'; }
}
?></select>
<tr><td colspan=2><input type=hidden name="report" value="importvalue">
<input type="submit" value="Valider"></td></tr>
</table>
</form>