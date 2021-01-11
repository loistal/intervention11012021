<?php

###
# this is now a model example of how a report should be coded
###

session_write_close(); # we will not be editing SESSION variables

# title
$title = 'Rapport : Ma journée de travail';
showtitle($title);

# preloads
require('preload/team.php');
require('preload/employee.php');

# POST and GET
$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
$PA['employeeid'] = 'int';
$PA['teamid'] = 'int';
$PA['journal'] = '';
$PA['managerjournal'] = '';
require('inc/readpost.php');

# permissions
if (!$_SESSION['ds_ishrsuperuser'] && !$_SESSION['ds_ismanager']) { $employeeid = $_SESSION['ds_myemployeeid']; }

# headers (further subtitles in query construction)
echo '<h2>',$title,'</h2>';
echo '<p>De: ' .datefix2($startdate) . ' à ' .datefix2($stopdate); 

# query construction
$query = 'select employeeday,employee_day.employeeid,journal,managerjournal
from employee_day,employee
where employee_day.employeeid=employee.employeeid
and employeeday>=? and employeeday<=?';
$query_prm = array($startdate,$stopdate);
if ($_SESSION['ds_ismanager'] && !$_SESSION['ds_ishrsuperuser']) 
{
  $query .= ' and (teamid=? or employee_day.employeeid=?)'; array_push($query_prm, $_SESSION['ds_ismanager'], $_SESSION['ds_myemployeeid']);
}
if ($teamid >= 0) 
{
  $query .= ' and teamid=?'; array_push($query_prm, $teamid);
  echo '<p>Équipe : ' . d_output($teamA[$teamid]);
}
if ($employeeid >= 0) 
{
  $query .= ' and employee_day.employeeid=?'; array_push($query_prm, $employeeid);
  echo '<p>Employé(e) : ' . d_output($employeeA[$employeeid]);
}
if ($journal != '')
{
  $query .= ' and journal like ?'; array_push($query_prm, '%'.$journal.'%');
  echo '<p>Journal : "', d_output($journal), '"';
}
if ($managerjournal != '')
{
  $query .= ' and managerjournal like ?'; array_push($query_prm, '%'.$managerjournal.'%');
  echo '<p>Remarques manager : "', d_output($managerjournal), '"';
}
$query .= ' order by employeeid,employeeday,employee_dayid';
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

# table
echo d_table('report');

# headers
echo '<thead><th>Date<th>Employé(e)<th>Journal'; # TODO there should be functions for this, see func.php
if ($_SESSION['ds_ishrsuperuser'] || $_SESSION['ds_ismanager']) { echo '<th>Remarques manager'; }
echo '</thead>';

# report
for ($i=0; $i<$num_results_main; $i++)
{
  echo d_tr();
  echo d_td_old(datefix2($main_result[$i]['employeeday']),1);
  echo d_td_old($employeeA[$main_result[$i]['employeeid']]);
  echo d_td_old($main_result[$i]['journal']);
  if ($_SESSION['ds_ishrsuperuser'] || $_SESSION['ds_ismanager']) { echo d_td_old($main_result[$i]['managerjournal']); }
}

# totals and end table
echo d_table_end();
?>