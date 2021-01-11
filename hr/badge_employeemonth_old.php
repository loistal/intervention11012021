<h2>Tableau de pointage</h2>

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
<?php
if (!$_SESSION['ds_ismanager'] && !$_SESSION['ds_ishrsuperuser'])
{
  echo '<input type=hidden name="employeeid" value="' . $_SESSION['ds_myemployeeid'] . '">';
}
else
{
  echo '<tr><td>';
  if (!$_SESSION['ds_ishrsuperuser']) { $dp_groupid = $_SESSION['ds_ismanager']; }
  $dp_itemname = 'employee'; $dp_noblank = 1; require('inc/selectitem.php');
}
?>
<tr>
<td colspan=5 align=center>
<input type=hidden name="report" value="badge_employeemonth_old"> 
<input type="submit" value="<?php echo d_trad('validate');?>">
</td>
</tr>
</table>
</form>
<?php

if ($_SESSION['ds_ishrsuperuser'] || $_SESSION['ds_ismanager'])
{
  $PA['badgedate'] = 'date';
  require('inc/readpost.php');
  if ($badgedate == '') { $badgedate = $_SESSION['ds_curdate']; }

  echo '<br><br><table class="report"><thead><th colspan=7><form method="post" action="hr.php">Pointages du ';
  $datename = 'badgedate'; require('inc/datepicker.php');
  echo '<input type=hidden name="hrmenu" value="', $hrmenu, '"> <input type="submit" value="Changer"></form></th></thead>';

  $query = 'select employeeid,badgetime from badgelog where deleted=0 and badgetime is not null and badgedate=? order by employeeid,badgetime';
  $query_prm = array($badgedate);
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $ok = 0;
    if ($_SESSION['ds_ishrsuperuser']) { $ok = 1; }
    if ($_SESSION['ds_ismanager'] == $employee_teamidA[$query_result[$i]['employeeid']]) { $ok = 1; }
    if ($_SESSION['ds_myemployeeid'] == $query_result[$i]['employeeid']) { $ok = 1; }
    if ($ok)
    {    
      if ($i == 0 || $query_result[$i]['employeeid'] != $query_result[($i-1)]['employeeid'])
      {
        if ($query_result[$i]['employeeid'] > 0) { echo '<tr><td>', $employeeA[$query_result[$i]['employeeid']]; }
        else { echo '<tr><td><i>Employé non reconnu</i>'; }
      }
      echo '<td>', substr($query_result[$i]['badgetime'],0,5);
    }
  }
  if ($num_results == 0) { echo '<tr><td colspan=7>Aucun pointage.'; }
  echo '</table>';
}

?>