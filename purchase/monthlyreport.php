<h2>Rapport des achats mensuel:</h2>
<form method="post" action="reportwindow.php" target=_blank><table><?php
$month = mb_substr($_SESSION['ds_curdate'],5,2)+0;
$year = mb_substr($_SESSION['ds_curdate'],0,4)+0;
?><tr><td>Mois/Année:</td><td><select name="month"><?php
for ($i=1; $i <= 12; $i++)
{
  if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
  else { echo '<option value="' . $i . '">' . $i . '</option>'; }
}
?></select><select name="year"><?php
for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
{
  if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
  else { echo '<option value="' . $i . '">' . $i . '</option>'; }
}
?></select></td></tr>
<tr><td><?php echo d_trad('orderby:'); ?></td><td><select name="orderby"><option value=0>Dossier</option><option value=1>ETA</option><option value=2>Navire</option></select></td></tr>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="monthlyreport"><input type="submit" value="Valider"></td></tr>
</table></form>