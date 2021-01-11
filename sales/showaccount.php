<h2>Afficher compte client:</h2>
<form method="post" action="printwindow.php" target="_blank"><table>
<table><tr><td>Client:</td><td><input autofocus type="text" STYLE="text-align:right" name="client" size=20></td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2><input type=checkbox name="showoperations" value="1"> <b>Relevé:<b></td></tr>
<?php
$day = mb_substr($_SESSION['ds_curdate'],8,2);
$month = mb_substr($_SESSION['ds_curdate'],5,2);
$year = mb_substr($_SESSION['ds_curdate'],0,4);
?><tr><td>Début:</td><td><select name="day"><?php
for ($i=1; $i <= 31; $i++)
{ 
  if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
  else { echo '<option value="' . $i . '">' . $i . '</option>'; }
}
?></select><select name="month"><?php
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
<tr><td>Fin:</td><td><select name="stopday"><?php
for ($i=1; $i <= 31; $i++)
{ 
  if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
  else { echo '<option value="' . $i . '">' . $i . '</option>'; }
}
?></select><select name="stopmonth"><?php
for ($i=1; $i <= 12; $i++)
{
  if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
  else { echo '<option value="' . $i . '">' . $i . '</option>'; }
}
?></select><select name="stopyear"><?php
for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
{
  if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
  else { echo '<option value="' . $i . '">' . $i . '</option>'; }
}
?></select></td></tr>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="showaccount"><input type=hidden name="usedefaultstyle" value="1"><input type="submit" value="Valider"></td></tr>
</table></form>