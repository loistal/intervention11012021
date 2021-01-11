<?php

$PA['month'] = 'uint';
$PA['year'] = 'uint';
$PA['employeeid'] = 'uint';
$PA['advance'] = 'currency';
$PA['update'] = 'uint';
require('inc/readpost.php');

if ($employeeid)
{
  $query = 'select advance from payslip_advance where employeeid=? and month=? and year=?';
  $query_prm = array($employeeid,$month,$year);
  require('inc/doquery.php');
  if ($num_results)
  {
    if ($update)
    {
      $query = 'update payslip_advance set advance=? where employeeid=? and month=? and year=?';
      $query_prm = array($advance,$employeeid,$month,$year);
      require('inc/doquery.php');
      echo '<p>Enregistré.</p><br>';
    }
    else
    {
      $advance = $query_result[0]['advance']+0;
    }
  }
  else
  {
    $query = 'insert into payslip_advance (employeeid,month,year) values (?,?,?)';
    $query_prm = array($employeeid,$month,$year);
    require('inc/doquery.php');
  }
  require('preload/employee.php');
  echo '<h2>Avance pour '.$employeeA[$employeeid].' '.d_trad('shortmonth'.$month).' '.$year.'</h2>';
  echo '<form method="post" action="hr.php"><table><tr><td>';
  echo '<input type=number name="advance" value="'.$advance.'">';
  echo '<tr><td colspan="2" align="center">
  <input type=hidden name="hrmenu" value="' . $hrmenu . '">
  <input type=hidden name="employeeid" value="' . $employeeid . '">
  <input type=hidden name="month" value="' . $month . '">
  <input type=hidden name="year" value="' . $year . '">
  <input type=hidden name="update" value=1>
  <input type="submit" value="Valider"></td></tr>
  </table></form>';
}
else
{
  echo '<h2>Avance:</h2>';
  echo '<form method="post" action="hr.php"><table><tr><td>';
  $dp_itemname = 'employee'; $dp_description = 'Employé(e)'; $dp_noblank = 1;require('inc/selectitem.php');
  if ($month == 0)
  {
    $month = mb_substr($_SESSION['ds_curdate'],5,2);
    $year = mb_substr($_SESSION['ds_curdate'],0,4);
  }
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
  ?></select><?php
  echo '<tr><td colspan="2" align="center">
  <input type=hidden name="hrmenu" value="' . $hrmenu . '">
  <input type="submit" value="Valider"></td></tr>
  </table></form>';
}
?>

<br><br><br><h2>Rapport avances</h2>
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
<tr><td colspan=5 align=center>
<input type=hidden name="report" value="payroll_advance_report"> 
<input type="submit" value="<?php echo d_trad('validate');?>">
</td>
</tr>
</table>
</form>