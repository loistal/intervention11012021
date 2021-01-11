<?php

echo '<h2>' . d_trad('modplanning:') . '</h2>';

$periodic = $_POST['periodic'];
$datename = 'startdate'; require('inc/datepickerresult.php');
$datename = 'stopdate'; require('inc/datepickerresult.php');
$employeeid = $_POST['employeeid'];
$resourceid = $_POST['resourceid'];
$num_results=0;$client = $_POST['client'];require('inc/findclient.php');$clientnum_results=$num_results;
$MAX_RESULTS = 100;
session_write_close();

#SELECT
$query = 'select p.planningid,p.planningdate,p.planningstart,p.planningstop,p.planningtimestart,p.planningtimestop,p.planningname,p.planningcomment,p.dayofweek,p.periodic,p.periodic_spec ';
$query_prm = array();
$employeeidempty = 1;
$clientempty = 1;
$clientidempty = 1;
$resourceidempty = 1;
if(!empty($employeeid) && $employeeid > -1)
{
	$employeeidempty = 0;
	$query .= ',concat(employeename," ",employeefirstname) as employeename';
} 
if(!empty($client))
{
	$clientempty = 0;
	if(!empty($clientid) && $clientid > -1)
	{ 
		$clientidempty = 0;
	}
	$query .= ',c.clientname';
}
if(!empty($resourceid) && $resourceid > -1)
{ 
	$resourceidempty = 0; 
	$query .= ',r.resourcename';
}

#FROM
$query .= ' from planning p';
if (!$employeeidempty) { $query .= ',planning_employee pe, employee e'; }
if (!$clientidempty || !$clientempty) { $query .= ',planning_client pc,client c'; }
if(!$resourceidempty) { $query .= ',planning_resource pr,resource r'; }

#WHERE
$query .= ' where p.deleted = 0';
if($startdate > 0)
{
		$query .= ' and p.planningstop >= ?';
    array_push($query_prm,$startdate);
}
if($stopdate > 0)
{
		$query .= ' and p.planningstart <= ?';
    array_push($query_prm,$stopdate);
}
if(!$employeeidempty)
{
    $query .= ' and p.planningid = pe.planningid and pe.employeeid = e.employeeid and pe.employeeid=?';
    array_push($query_prm,$employeeid);
}
if(!$clientidempty)
{
  $query .= ' and p.planningid = pc.planningid and c.clientid=pc.clientid and pc.clientid = ?'; 
  array_push($query_prm,$clientid);
}
elseif(!$clientempty)
{
  $query .= ' and p.planningid = pc.planningid and c.clientid=pc.clientid and c.clientname LIKE ?';    
  array_push($query_prm,'%' . $client . '%');
}
if(!$resourceidempty)
{
    $query .= ' and p.planningid = pr.planningid and pr.resourceid = r.resourceid and pr.resourceid=?'; 
    array_push($query_prm,$resourceid);    
}
if($periodic != -1)
{
  $query .= ' and p.periodic = ?';
  array_push($query_prm,$periodic);
}

#ORDER BY
$query .= ' order by periodic,planningstart,planningdate limit '.$MAX_RESULTS;   

require('inc/doquery.php');

if(!$employeeidempty){ $ourparams .= '<p>'. d_trad('employeeparam',$query_result[0]['employeename']) .'</p>';}
if(!$clientempty)
{
  if(!$clientidempty)
  { 
    $ourparams .= '<p>'. d_trad('clientparams',array($query_result[0]['clientname'],$clientid)) .'</p>';
  }
  else 
  {
    $ourparams .= '<p>'. d_trad('clientparam',$client) .'</p>';  
  }
}
if(!$resourceidempty){ $ourparams .= '<p>'. d_trad('resourceparam',$query_result[0]['resourcename']) .'</p><br>';}
echo $ourparams;

echo '<form method="post" action="admin.php"><table class=report>';

$lastperiodic = -1;
for ($i=0;$i<$num_results;$i++)
{
  $periodic = $query_result[$i]['periodic'];
  if ($i == 0 || $periodic != $lastperiodic)
  {
    echo '<thead>';
    if ($periodic == 0) { echo '<th colspan=2>' . d_trad('punctual') . '</th><th colspan=2>' . d_trad('date') . '</th><th>' . d_trad('validity') .'</th><th>' . d_trad('planningcomment'); }
    if ($periodic == 1) { echo '<th colspan=4>' . d_trad('weekly'). '</th><th>' . d_trad('validity') .'</th><th>' . d_trad('planningcomment'); }
    if ($periodic == 2) { echo '<th colspan=4>' . d_trad('monthly'). '</th><th>' . d_trad('validity') .'</th><th>' . d_trad('planningcomment'); }
    if ($periodic == 3) { echo '<th colspan=4>' . d_trad('yearly'). '</th><th>' . d_trad('validity') .'</th><th>' . d_trad('planningcomment'); }
    echo '</th></thead>';
  }
  echo d_tr();
  if($planningmenu == 'list')
  {
    echo '<td colspan=2>';  
  }
  else
  {
    echo '<td><input type=radio name=modplanningid value="' . $query_result[$i]['planningid'] . '"><td>';
  }
  echo $query_result[$i]['planningname'] . '</td>'; 
  if ($periodic == 0)
  {
    echo '<td colspan=2>' . datefix2($query_result[$i]['planningdate']) . '</td>';
  }
  if ($periodic == 1)
  {
    if ($query_result[$i]['periodic_spec'] == 0) { $kladd = 'allweeks'; }
    else { $kladd = 'periodic_spec_weekly_' . $query_result[$i]['periodic_spec']; }
    echo '<td>' . d_trad($kladd) . '</td><td>' . d_trad('dayofweek'. $query_result[$i]['dayofweek']) . '</td>';
  }
  if ($periodic == 2)
  {
    # starting which month? $kladd2
    $kladd2 = '';
    if ($query_result[$i]['periodic_spec'] == 0) { $kladd = 'allmonths'; }
    else { $kladd = 'periodic_spec_monthly' . $query_result[$i]['periodic_spec']; }
    echo '<td>' . d_trad($kladd) . $kladd2 . '</td><td>' . d_trad('prefix_specificdate') . ' ' . (mb_substr($query_result[$i]['planningdate'],8,2)+0) . '</td>';
  }
  if ($periodic == 3)
  {
    echo '<td colspan=2>' . (mb_substr($query_result[$i]['planningdate'],8,2)+0) . ' ' . d_trad('month2_' . (mb_substr($query_result[$i]['planningdate'],5,2)+0)) . '</td>';
  }
  echo '<td>' . datefix2($query_result[$i]['planningstart']) . ' &nbsp; ' . d_trad('validity_to') . ' &nbsp; ' . datefix2($query_result[$i]['planningstop']) . '</td>';
  echo '<td>' . $query_result[$i]['planningcomment'] . '</td></tr>';
  $lastperiodic = $periodic;
}
if($planningmenu != 'list')
{
  echo '<tr><td colspan="6" align="center"><input type=hidden name="adminmenu" value="planning"><input type="submit" value="' . d_trad('modify') . '"></td></tr>';
}
echo '</table></form>';

?>