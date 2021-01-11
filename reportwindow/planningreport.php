<?php 

require ('preload/employee.php');
require ('preload/resource.php');

require ('inc/func_planning.php');

$periodic = $_POST['periodic'];
if(empty($periodic)){$periodic = $_GET['periodic'];}
$datename = 'startdate'; require('inc/datepickerresult.php');
$datename = 'stopdate'; require('inc/datepickerresult.php');
$employeeid = $_POST['employeeid'];
if(empty($employeeid)){$periodic = $_GET['employeeid'];}
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
	$query .= ',concat(IFNULL(concat(e.employeename," "),""),IFNULL(e.employeefirstname,"")) as employeename';
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
$planningA = $query_result;$num_plannings = $num_results;unset($query_result,$num_results);
$ourparams = '';
if($startdate > 0 && $stopdate > 0)
{
    $ourparams .= '<p>' . d_trad('between',array(datefix2($startdate),datefix2($stopdate))) . '</p>'; 
}
if(!$employeeidempty){ $ourparams .= '<p>'. d_trad('employeeparam',$employeeA[$employeeid]) .'</p>';}
if(!$clientempty)
{
  if(!$clientidempty)
  { 
    $ourparams .= '<p>'. d_trad('clientparams',array(d_decode($planningA[0]['clientname']),$clientid)) .'</p>';
  }
  else 
  {
    $ourparams .= '<p>'. d_trad('clientparam',$client) .'</p>';  
  }
}
if(!$resourceidempty){ $ourparams .= '<p>'. d_trad('resourceparam',$planningA[0]['resourcename']) .'</p><br>';}

#TITLE
$title = d_trad('planningreport:');
showtitle($title);
echo '<h2>' . $title . '</h2>';
echo $ourparams;

if($num_plannings > 0)
{
  if ($num_plannings >= $MAX_RESULTS)
  {
    echo d_tr();
    echo '<p class=alert>' . d_trad('maxresultsparam',$MAX_RESULTS) . '<p>';
  }
  $lastperiodic = -1;
  echo '<table class=report>';
  for ($i=0;$i<$num_plannings;$i++)
  {
    $row = $planningA[$i];
    $periodic = $row['periodic'];
    if ($i == 0 || $periodic != $lastperiodic)
    {
      echo '<thead>';
			echo '<th>' . d_trad('time') . '</th>';
      if ($periodic == 0) { echo '<th>' . d_trad('punctual') . '</th><th colspan=2>' . d_trad('date') . '</th>';  }
      if ($periodic == 1) { echo '<th colspan=3>' . d_trad('weekly'). '</th>'; }
      if ($periodic == 2) { echo '<th colspan=3>' . d_trad('monthly'). '</th>'; }
      if ($periodic == 3) { echo '<th colspan=3>' . d_trad('yearly'). '</th>'; }
      echo '<th>' . d_trad('validity') .'</th><th>' . d_trad('planningcomment') .'</th><th>' . d_trad('employee') .'</th><th>' . d_trad('client') .'</th><th>'. d_trad('resource') . '</th></thead>';
    }
    echo d_tr();
		$starttime = $row['planningtimestart'];
		$stoptime = $row['planningtimestop'];
    echo '<td>' . d_displaytimeinterval($starttime,$stoptime) . '</td>';
		echo '<td>' . $row['planningname'] . '</td>'; 
    if ($periodic == 0)
    {
      echo '<td colspan=2>' . datefix2($row['planningdate']) . '</td>';
    }
    if ($periodic == 1)
    {
      if ($row['periodic_spec'] == 0) { $kladd = 'allweeks'; }
      else { $kladd = 'periodic_spec_weekly_' . $row['periodic_spec']; }
      echo '<td>' . d_trad($kladd) . '</td><td>' . d_trad('dayofweek'. $row['dayofweek']) . '</td>';
    }
    if ($periodic == 2)
    {
      # starting which month? $kladd2
      $kladd2 = '';
      if ($row['periodic_spec'] == 0) { $kladd = 'allmonths'; }
      else { $kladd = 'periodic_spec_monthly' . $row['periodic_spec']; }
      echo '<td>' . d_trad($kladd) . $kladd2 . '</td><td>' . d_trad('prefix_specificdate') . ' ' . (mb_substr($row['planningdate'],8,2)+0) . '</td>';
    }
    if ($periodic == 3)
    {
      echo '<td colspan=2>' . (mb_substr($row['planningdate'],8,2)+0) . ' ' . d_trad('month2_' . (mb_substr($row['planningdate'],5,2)+0)) . '</td>';
    }
    echo '<td>' . datefix2($row['planningstart']) . ' &nbsp; ' . d_trad('validity_to') . ' &nbsp; ' . datefix2($row['planningstop']) . '</td>';
    echo '<td>' . $row['planningcomment'] . '</td>';
		
		#get employees for each planning line
		$query = 'select employeeid from planning_employee where planningid = ? order by employeeid';
		$query_prm = array($row['planningid']);
		require('inc/doquery.php');
		$planning_employeeA = $query_result;$num_planning_employees = $num_results;unset($query_result,$num_results);
		
		echo '<td>';
		for($j=0;$j<$num_planning_employees;$j++)
		{
			$employeeid = $planning_employeeA[$j]['employeeid'];
			if($employeeid > 0){echo $employeeA[$employeeid] . '<br>';}
		}
		echo '</td>';
		
		#get clients for each planning line
		$query = 'select c.clientid,c.clientname from planning_client p,client c where p.planningid = ? and p.clientid = c.clientid order by c.clientname';
		$query_prm = array($row['planningid']);
		require('inc/doquery.php');
		$planning_clientA = $query_result;$num_planning_clients = $num_results;unset($query_result,$num_results);
		
		echo '<td>';
		for($j=0;$j<$num_planning_clients;$j++)
		{
			$clientid = $planning_clientA[$j]['clientid'];
			if($clientid > 0) { echo d_decode($planning_clientA[$j]['clientname']) . '<br>';}
		}
		echo '</td>';
		
		#get resources for each planning line
		$query = 'select resourceid from planning_resource where planningid = ? order by resourceid';
		$query_prm = array($row['planningid']);
		require('inc/doquery.php');
		$planning_resourceA = $query_result;$num_planning_resources = $num_results;unset($query_result,$num_results);
		
		echo '<td>';
		for($j=0;$j<$num_planning_resources;$j++)
		{
			$resourceid = $planning_resourceA[$j]['resourceid'];
			if($resourceid > 0){echo $resourceA[$resourceid] . '<br>';}
		}
		echo '</td>';


		echo '</tr>';
		$lastperiodic = $periodic;
  }
  if ($num_plannings >= $MAX_RESULTS)
  {
    echo d_tr();
    echo '<td><td colspan=5>' . d_trad('maxresultsdisplayparam',$MAX_RESULTS) . '</td></tr>';
  }  
  echo '</table></form>';
}
else
{
  echo '<p>' . d_trad('noresult') . '</p>';
}

?>