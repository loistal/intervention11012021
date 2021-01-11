<?php

# input: $dp_selectedid $dp_allowall $dp_nonempty $dp_noblank $dp_description $dp_notr $dp_colspan
# output: $_POST['departmentsection']

if($dp_notr != 1) { echo '<tr>'; }
if ($dp_description != '') { echo '<td>' . $dp_description . ':</td>'; }
if(isset($dp_colspan)) { echo '<td colspan=' . $dp_colspan . '>';}
else {echo '<td>';}
echo '<select name="employeesectionid">';
if ($dp_allowall == 1) { echo '<option value=-1>'. d_trad('selectall') .'</option>'; }
if ($dp_noblank != 1)
{
  echo '<option value=0';
  if ($dp_selectedid === 0) { echo ' selected'; }
  echo '></option>';
}
if ($dp_nonempty == 1)
{
  echo '<option value=-2';
  if ($dp_selectedid == -2) { echo ' selected'; }
  echo '>'.d_trad('nonempty').'</option>';
}
$query = 'select s.employeesectionid,s.employeesectionname,d.employeedepartmentname from employeesection s,employeedepartment d where s.employeedepartmentid = d.employeedepartmentid and d.deleted = 0 and s.deleted = 0 order by d.employeedepartmentname,s.employeesectionname';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row2 = $query_result[$i];
  $employeedepartmentname = $row2['employeedepartmentname'];
  if (isset($employeedepartmentname) && !empty($employeedepartmentname)) { $employeesectionname = $employeedepartmentname . '/';}
  $employeesectionname .= $row2['employeesectionname'];
  $employeesectionid = $row2['employeesectionid'];
  $selected = '';
  if ($employeesectionid == $dp_selectedid) { $selected = ' SELECTED';}
  echo '<option value="' . $employeesectionid . '"' . $selected . '>' . $employeesectionname . '</option>';
}
$query = 'select s.employeesectionid,s.employeesectionname from employeesection s where s.employeedepartmentid = 0 and s.deleted = 0 order by employeesectionname';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row3 = $query_result[$i];
  $employeesectionid = $row3['employeesectionid'];  
  $selected = '';  
  if ($employeesectionid == $dp_selectedid) { $selected = ' SELECTED';}  
  echo '<option value="' . $employeesectionid . '"' . $selected . '>' . $row3['employeesectionname'] . '</option>';
}
?></select><?php
if ($dp_description != '') { echo '</td>'; }
if($dp_notr != 1) { echo '</tr>'; }

unset($dp_description);
unset($dp_allowall,$dp_nonempty);
unset($dp_selectedid);
unset($dp_noblank);

?>