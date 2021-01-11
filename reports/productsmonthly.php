<h2>Produits vendus (mensuel):</h2>
<form method="post" action="reportwindow.php" target="_blank"><table><?php
$month = mb_substr($_SESSION['ds_curdate'],5,2);
$year = mb_substr($_SESSION['ds_curdate'],0,4);
?><tr><td>Mois:</td><td><select name="month"><?php
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
<tr><td>Quelles factures?</td><td><select name="mychoice"><option value=2>Confirmées</option><option value=1>Toutes</option></select></td></tr>
<tr><td>Séparer par 
<?php
if ($_SESSION['ds_term_invoicetag'] != "") { echo $_SESSION['ds_term_invoicetag']; }
else { echo 'Tags'; }
?></td><td><input type=checkbox name="bytag" value=1></td></tr>
<tr><td>Afficher graphique circulaire</td><td><input type=checkbox name="showgooglecharts" value=1></td></tr>
<tr><td colspan="2" align="center">
<input type=hidden name="report" value="productsmonthly">
<input type="submit" value="Valider"></td></tr></table></form>