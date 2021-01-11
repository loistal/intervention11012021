<?php
if(!isset($resourceA)){require('preload/resource.php');}  
if(!isset($employeeA)){require('preload/employee.php');}

$PA['planningid'] = 'int';
require('inc/readpost.php');

if ($planningid > 0)
{
  $planningid = $planningid+0;
  $query = 'select * from planning where planningid=?';
  $query_prm = array($planningid);
  require('inc/doquery.php');
  $planningstart = $query_result[0]['planningstart'];
  $planningstop = $query_result[0]['planningstop'];
  $planningtimestart = $query_result[0]['planningtimestart'];
  $planningtimestop = $query_result[0]['planningtimestop'];
  $planningdate = $query_result[0]['planningdate'];
  $periodic = $query_result[0]['periodic'];
  $planningname = $query_result[0]['planningname'];
  $planningcomment = $query_result[0]['planningcomment'];
  $periodic_spec_weekly = $query_result[0]['periodic_spec'];
  $periodic_spec_monthly = $query_result[0]['periodic_spec'];
  $dayofweek = $query_result[0]['dayofweek'];
  $day_monthly = mb_substr($planningdate,8,2)+0;
  $day_yearly = mb_substr($planningdate,8,2)+0;
  $month_yearly = mb_substr($planningdate,5,2)+0;
  $deleted = $query_result[0]['deleted'];
}

$title = d_trad('planning');
showtitle($title);
echo '<h2>' . $title . '</h2>';

echo '<form method="post" action="admin.php"><table>';
echo '<tr><td><b>' . d_trad('planningname:') . '</b></td><td colspan=2>' . $planningname . '</td></tr>';
echo '<tr><td><b>' . d_trad('validity:') . '</b></td><td colspan=2>' .$planningstart .' &nbsp; '.d_trad('validity_to') .' &nbsp; ' . $planningstop . '</td></tr>';
echo '<tr><td><b>' . d_trad('planningtype') . ':</b></td><td>';
if($periodic == 0)
{
	echo d_trad('punctual') .'</td><td>' . d_trad('theday',$planningdate) . '</td></tr><tr><td></td><td>';
}
else if($periodic == 1)
{
	switch($periodic_spec_weekly)
	{
		case 0:
			echo d_trad('everydayofweek' . $dayofweek);
			break;
		case 1:
			echo d_trad('periodic_spec_weekly_1') . '&nbsp;' . d_trad('everydayofweeksing' . $dayofweek);
			break;
		case 2:
			echo d_trad('periodic_spec_weekly_2') . '&nbsp;' . d_trad('everydayofweeksing' . $dayofweek);			
			break;
		case 3:
			echo d_trad('periodic_spec_weekly_3') . '&nbsp;' . d_trad('everydayofweeksing' . $dayofweek);		
			break;
		case 4:
			echo d_trad('periodic_spec_weekly_4') . '&nbsp;' . d_trad('everydayofweeksing' . $dayofweek);			
			break;
		case 5:
			echo d_trad('periodic_spec_weekly_5') . '&nbsp;' . d_trad('everydayofweeksing' . $dayofweek);			
			break;
		case 6:
			echo d_trad('periodic_spec_weekly_6') . '&nbsp;' . d_trad('everydayofweeksing' . $dayofweek);			
			break;				
	}
	echo '</td></tr><tr><td></td><td>';
}
else if($periodic == 2)
{
	switch($periodic_spec_weekly)
	{
		case 0:
			echo d_trad('everydayofmonth',$day_monthly);
			break;
		case 1:
			echo d_trad('periodic_spec_monthly1') . '&nbsp;' . d_trad('theday',$day_monthly);	
			break;
		case 2:
			echo d_trad('periodic_spec_monthly2') . '&nbsp;' . d_trad('theday',$day_monthly);			
			break;
		case 3:
			echo d_trad('periodic_spec_monthly3') . '&nbsp;' . d_trad('theday',$day_monthly);			
			break;
		case 4:
			echo d_trad('periodic_spec_monthly4') . '&nbsp;' . d_trad('theday',$day_monthly);			
			break;
		case 5:
			echo d_trad('periodic_spec_monthly5') . '&nbsp;' . d_trad('theday',$day_monthly);			
			break;
		case 6:
			echo d_trad('periodic_spec_monthly6') . '&nbsp;' . d_trad('theday',$day_monthly);			
			break;				
	}
	echo '</td></tr><tr><td></td><td>';
}
else if($periodic == 3)
{
	echo d_trad('yearly') . '&nbsp' . d_trad('theday2',array($day_yearly,d_trad('month2_' . $month_yearly )));
	echo '</td></tr>';
}

if(!empty($planningtimestart)){echo '<tr><td><b>'.d_trad('time:').'</b></td><td colspan=2>'. $planningtimestart . '&nbsp; '.d_trad('time_to'). '&nbsp;' . $planningtimestop . '</td></tr>';}
if(!empty($planningcomment)){echo '<tr><td><b>' . d_trad('planningcomment') . ':</b></td><td colspan=2>' . $planningcomment .'</td></tr>';}
if ($deleted) {echo '<tr><td colspan=2><b>' . d_trad('deleted') . '</b></td></tr>';}
echo '</table><br><br><table border=0 cellspacing=1 cellpadding=1>';
echo '<tr><td><b>' . d_trad('employee') . '</b></td><td>&nbsp;&nbsp;&nbsp;</td><td><b>' . d_trad('client') . '</b></td><td>&nbsp;&nbsp;&nbsp;</td><td><b>' . d_trad('resource') . '</b></td></tr>';

$num_resources = $_SESSION['ds_num_resources'];
$query = 'select planning_employeeid,employeeid,linenr from planning_employee where planningid=?';
$query_prm = array($planningid);
require('inc/doquery.php');
if ($num_results > $num_resources) { $num_resources = $num_results; }
for ($i=0;$i<$num_results;$i++)
{
  $linenr = $query_result[$i]['linenr'];
  $p_eidA[$linenr] = $query_result[$i]['planning_employeeid'];
  $eidA[$linenr] = $query_result[$i]['employeeid'];
}
$query = 'select planning_clientid,clientid,linenr from planning_client where planningid=?';
$query_prm = array($planningid);
require('inc/doquery.php');
if ($num_results > $num_resources) { $num_resources = $num_results; }
for ($i=0;$i<$num_results;$i++)
{
  $linenr = $query_result[$i]['linenr'];
  $p_cidA[$linenr] = $query_result[$i]['planning_clientid'];
  $cidA[$linenr] = $query_result[$i]['clientid'];
}
$query = 'select planning_resourceid,resourceid,linenr from planning_resource where planningid=?';
$query_prm = array($planningid);
require('inc/doquery.php');
if ($num_results > $num_resources) { $num_resources = $num_results; }
for ($i=0;$i<$num_results;$i++)
{
  $linenr = $query_result[$i]['linenr'];
  $p_ridA[$linenr] = $query_result[$i]['planning_resourceid'];
  $ridA[$linenr] = $query_result[$i]['resourceid'];
}

for ($i=1;$i<=$num_resources;$i++)
{
  echo '<tr><td>';
  if (isset($eidA[$i]) && isset($employeeA[$eidA[$i]])) { echo $employeeA[$eidA[$i]]; }
  echo '<td>';
  
  echo '<td>';
	$client = $cidA[$i];
	if ($client == 0) { $client = ''; } 
	require ('inc/findclient.php');
	if($clientid > 0) {echo $clientid . ': ';}
  if (!isset($clientname)) { $clientname = ''; }
  echo '</td><td><a href="reportwindow.php?report=showclient&usedefaultstyle=1&client=' . $clientid . '" target=_blank>' . d_output($clientname) .'</a>';
  echo '</td><td>';
  
  if (isset($ridA[$i]) && isset($resourceA[$ridA[$i]])) { echo $resourceA[$ridA[$i]]; }
  
  echo '</td><td></td>';
  echo '</tr>';
}
echo '</table></form>';

#var_dump($_REQUEST);

?>