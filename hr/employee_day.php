<?php

require('preload/employee.php');

$PA['employeeid'] = 'int';
$PA['saveme'] = 'int';
$PA['time1'] = ''; $PA['time2'] = ''; $PA['time3'] = ''; $PA['time4'] = ''; $PA['time5'] = ''; $PA['time6'] = ''; # hardcode 6
$PA['journal'] = ''; $PA['managerjournal'] = '';
$PA['employeeday'] = 'date';
require('inc/readpost.php');

if ($_SESSION['ds_ismanager'] && !$_SESSION['ds_ishrsuperuser'])
{
  if ($employee_teamidA[$employeeid] != $_SESSION['ds_ismanager']) { $employeeid = 0; }
}
elseif (!$_SESSION['ds_ishrsuperuser']) { $employeeid = 0; }
if ($employeeid == 0 && $_SESSION['ds_myemployeeid'] > 0) { $employeeid = $_SESSION['ds_myemployeeid']; }
if ($employeeday == '') { $employeeday = $_SESSION['ds_curdate']; }

if ($employeeid)
{
  if ($saveme)
  {
    $query = 'select employee_dayid from employee_day where employeeid=? and employeeday=?';
    $query_prm = array($employeeid, $employeeday);
    require('inc/doquery.php');
    if ($num_results)
    {
      $query = 'update employee_day set journal=?,managerjournal=? where employeeid=? and employeeday=?';
      $query_prm = array($journal,$managerjournal,$employeeid,$employeeday);
      require('inc/doquery.php');
    }
    else
    {
      $query = 'insert into employee_day (employeeid,employeeday,journal,managerjournal) values (?,?,?,?)';
      $query_prm = array($employeeid,$employeeday,$journal,$managerjournal);
      require('inc/doquery.php');
    }
    
    # save to badgelog
    $badgelogid = array();
    $query = 'select badgelogid from badgelog where badgetime is not null and employeeid=? and badgedate=? order by deleted,badgedate,badgetime';
    $query_prm = array($employeeid,$employeeday);
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $badgelogid[($i+1)] = $query_result[$i]['badgelogid'];
    }
    for ($i=1; $i <= 6; $i++) # hardcode 6
    {
      $time = 'time' . $i;
      $deleted = 0; if ($$time == '') { $deleted = 1; }
      if (isset($badgelogid[$i]))
      {
        $query = 'update badgelog set badgetime=?,deleted=? where badgelogid=?';
        $query_prm = array($$time,$deleted,$badgelogid[$i]);
        require('inc/doquery.php');
      }
      else
      {
        $query = 'insert into badgelog (ismanual,badgedate,badgetime,employeeid,deleted) values (1,?,?,?,?)';
        $query_prm = array($employeeday,$$time,$employeeid,$deleted);
        require('inc/doquery.php');
      }
    }
  }

  $journal = ''; $managerjournal = '';
  $query = 'select journal,managerjournal from employee_day where employeeid=? and employeeday=?';
  $query_prm = array($employeeid, $employeeday);
  require('inc/doquery.php');
  if ($num_results)
  {
    $journal = $query_result[0]['journal'];
    $managerjournal = $query_result[0]['managerjournal'];
  }

  $query = 'select badgetime from badgelog where deleted=0 and badgetime is not null and employeeid=? and badgedate=? order by badgedate,badgetime';
  $query_prm = array($employeeid,$employeeday);
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $timeA[($i+1)] = $query_result[$i]['badgetime'];
  }


  echo '<h2>Journée de travail - ',$employeeA[$employeeid],' - ',datefix2($employeeday),'</h2>';

  ?>
  <form method="post" action="hr.php">
  <table>
  <tr>
  <td><input type="time" step=60 name="time1" value="<?php echo $timeA[1]; ?>"><input type="time" step=60 name="time2" value="<?php echo $timeA[2]; ?>">
  <td><input type="time" step=60 name="time3" value="<?php echo $timeA[3]; ?>"><input type="time" step=60 name="time4" value="<?php echo $timeA[4]; ?>">
  <td><input type="time" step=60 name="time5" value="<?php echo $timeA[5]; ?>"><input type="time" step=60 name="time6" value="<?php echo $timeA[6]; ?>">
  <?php
  # || $_SESSION['ds_ismanager'] == $employee_teamidA[$employeeid]
  if ($_SESSION['ds_ishrsuperuser']) { echo '<tr><td colspan=3>Remarques manager : <input type=text size=68 name="managerjournal" value="',$managerjournal,'">'; } # TODO managers
  ?>
  <tr><td colspan=3><textarea name="journal" rows=20 cols=100><?php echo d_input($journal);?></textarea>
  <tr><td colspan=3 align=center><input name="modify" type="submit" value="Valider"></td></tr>
  <input type=hidden name="employeeid" value="<?php echo $employeeid; ?>">
  <input type=hidden name="employeeday" value="<?php echo $employeeday; ?>">
  <input type=hidden name="saveme" value="1">
  <input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
  </table></form>

  <br><br>
  <?php
}
?>

<form method="post" action="hr.php">
<table><thead><th>Autre journée:</thead>
<tr><td>
<?php
$datename = 'employeeday'; require('inc/datepicker.php');
if ($_SESSION['ds_ishrsuperuser'] || $_SESSION['ds_ismanager'])
{
  if (!$_SESSION['ds_ishrsuperuser']) { $dp_groupid = $_SESSION['ds_ismanager']; }
  if ($employeeid > 0) { $dp_selectedid = $employeeid; }
  else { $dp_selectedid = $_SESSION['ds_myemployeeid']; }
  $dp_itemname = 'employee'; $dp_noblank = 1; require('inc/selectitem.php');
}
?>
<tr><td colspan=2 align=center><input name="modify" type="submit" value="Valider"></td></tr>
<input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
</table></form>

<?php
echo '<br><br><h2>Rapport</h2><form method="post" action="reportwindow.php" target="_blank">
<table><tr><td>' . d_trad('date:') . '<td>';
$datename = 'startdate'; require('inc/datepicker.php');
echo '<tr><td>'.d_trad('validity_to') .'<td>';
$datename = 'stopdate'; require('inc/datepicker.php');
if ($_SESSION['ds_ishrsuperuser'] || $_SESSION['ds_ismanager'])
{
  echo '<tr><td>Employé(e) :';
  if (!$_SESSION['ds_ishrsuperuser']) { $dp_groupid = $_SESSION['ds_ismanager']; }
  $dp_itemname = 'employee'; $dp_allowall = 1; $dp_noblank = 1; require('inc/selectitem.php');
}
if ($_SESSION['ds_ishrsuperuser'])
{
  echo '<tr><td>Équipe :';
  $dp_itemname = 'team'; $dp_allowall = 1; require('inc/selectitem.php');
}
else { echo '<input type=hidden name="teamid" value=-1>'; }
echo '<tr><td>Journal :<td><input type=text name=journal>';
if ($_SESSION['ds_ishrsuperuser'])
{
  echo '<tr><td>Remarques manager :<td><input type=text name=managerjournal>';
}
echo '<tr><td colspan=2 align=center><input type="submit" value="Valider"></td></tr>
<input type=hidden name="report" value="employee_dayreport">
</table></form>';

?>