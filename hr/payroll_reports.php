<h2>Déclaration CST</h2>
<form method="post" action="declaration.php" target="_blank">
<table>
<?php
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
?></select>
<tr><td>Non validées:<td><select name="bystatus"><option value=0>Incluses</option><option value=1>Non incluses</option></select>
<?php
#TODO <tr><td>Trimestre:<td><input type=checkbox name="quarter" value=1>
?>
<tr><td>Format:<td><select name="built_format"><option value=1>Adapté</option><option value=0>Importé du PDF</option></select>
<tr>
<td colspan=5 align=center>
<input type=hidden name="report" value="cst_report"> 
<input type="submit" value="<?php echo d_trad('validate');?>">
</td>
</tr>
</table>
</form>

<br><br>

<h2>Annexe CST</h2>
<form method="post" action="reportwindow.php" target="_blank">
<table>
<?php
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
?></select>
<tr><td>Non validées:<td><select name="bystatus"><option value=0>Incluses</option><option value=1>Non incluses</option></select>
<tr>
<td colspan=5 align=center>
<input type=hidden name="report" value="cst_report_annex"> 
<input type="submit" value="<?php echo d_trad('validate');?>">
</td>
</tr>
</table>
</form>

<br><br>

<h2>Rapport CST</h2>
<form method="post" action="reportwindow.php" target="_blank">
<table>
<?php
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
?></select>
<tr><td>Non validées:<td><select name="bystatus"><option value=0>Incluses</option><option value=1>Non incluses</option></select>
<tr>
<td colspan=5 align=center>
<input type=hidden name="report" value="cst_report"> 
<input type="submit" value="<?php echo d_trad('validate');?>">
</td>
</tr>
</table>
</form>

<br><br><h2>DECLARATION DE SALAIRES ET DE MAIN D'OEUVRE</h2>
<form method="post" action="reportwindow.php" target="_blank">
<table>
<?php
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
?></select>
<tr><td>Non validées:<td><select name="bystatus"><option value=0>Incluses</option><option value=1>Non incluses</option></select>
<tr><td>Formats:<td><select name="format"><option value=0>Défaut</option><option value=1>Alternatif</option></select>
<tr><td>Dates vides:<td><input type=checkbox name="noentrydate" value=1>
<tr>
<td colspan=5 align=center>
<input type=hidden name="report" value="dmo_report"> 
<input type="submit" value="<?php echo d_trad('validate');?>">
</td>
</tr>
</table>
</form>

<br><br><h2>Coût moyen des employés</h2>
<form method="post" action="reportwindow.php" target="_blank">
<table>
<?php
echo '<tr><td>Début:</td><td>';
$datename = 'startdate'; if (isset($startdate)) { $selecteddate = $startdate; }
else { $startdate = d_builddate(1,1,mb_substr($_SESSION['ds_curdate'],0,4)); }
require('inc/datepicker.php');
echo '</td></tr><tr><td>Fin:</td><td>';
$datename = 'stopdate'; if (isset($stopdate)) { $selecteddate = $stopdate; }
else { $stopdate = d_builddate(31,12,mb_substr($_SESSION['ds_curdate'],0,4)); }
require('inc/datepicker.php');
?>
<tr>
<tr><td><td><select name="gross"><option value=1>Brut</option><option value=2>Net</option></select>
<tr><td align=right><input type=checkbox name="deduct_rank_50" value=1><td>Déduire Congès payés
<tr><td colspan=5 align=center>
<input type=hidden name="report" value="hr_salary_cost"> 
<input type="submit" value="<?php echo d_trad('validate');?>">
</td>
</tr>
</table>
</form>

<br><br><h2>Rapport Solde Congès</h2>
<form method="post" action="reportwindow.php" target="_blank">
<table>
<?php
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
?></select>
<tr>
<td colspan=5 align=center>
<input type=hidden name="report" value="vacationdays_report"> 
<input type="submit" value="<?php echo d_trad('validate');?>">
</table>
</form>

<br><br><h2>Livre de Paie</h2>
<form method="post" action="reportwindow.php" target="_blank">
<table>
<?php
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
?></select>
<tr>
<td colspan=5 align=center>
<input type=hidden name="report" value="payroll_report"> 
<input type="submit" value="<?php echo d_trad('validate');?>">
</table>
</form>

<?php

require('preload/bankaccount.php');
if (empty($bankaccountA)) { echo '<p>Veuillez definir un compte bancaire (Admin).</p>'; }
else {

?>

<br><br><h2>Rapport paiements salaire</h2>
<form method="post" action="reportwindow.php" target="_blank">
<table>
<?php
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
?></select>
<tr><td>Compte:<?php
$dp_itemname = 'bankaccount'; $dp_allowall = 1;
require('inc/selectitem.php');
?>
<tr><td>Type paiment:<td>
<select name="paymenttypeid">
<option value=-1><?php echo d_trad('selectall'); ?></option>
<option value=0></option>
<option value=3>Virement</option>
<option value=2>Cheque</option>
</select>
<tr><td>Inclure non-verrouillés:<td><input type=checkbox name="all" value=1>
<tr>
<td colspan=5 align=center>
<input type=hidden name="report" value="pay_transfer_report"> 
<input type="submit" value="<?php echo d_trad('validate');?>">
</td>
</tr>
</table>
</form>

<?php } ?>

<br><br><h2>Rapport Cotisations Salariales</h2>
<form method="post" action="reportwindow.php" target="_blank">
<table>
<?php
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
?></select>
<tr>
<td colspan=5 align=center>
<input type=hidden name="report" value="payroll_salarytax"> 
<input type="submit" value="<?php echo d_trad('validate');?>">
</table>
</form>