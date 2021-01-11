<?php
### keep this
if ($_SESSION['ds_systemaccess'] != 1) { require('logout.php'); exit; }
require ('inc/standard.php');
require('inc/func_planning.php');
$dauphin_currentmenu = basename(__FILE__, '.php');?>

<link rel="stylesheet" href="printwindow/hr_report.css">
<link rel="stylesheet" href="declaration/bootstrap.css">
<link rel="stylesheet" href="declaration/print.css">

<?php
require ('preload/employee.php');
require ('preload/planningteamvalue.php');

#nb values a day
$ds_planningteamnbvalues = $_SESSION['ds_planningteamnbvalues'];
for($v=1;$v<=$ds_planningteamnbvalues;$v++)
{
  $ds_termname = 'ds_term_planningteamvalue' . $v;
  $ds_termplanningvalueA[$v] = $_SESSION[$ds_termname]; 
}

#get parameters
$planningteamid = $id;

$title = d_trad('addabsencepresence');
showtitle($title);

#don't deplace it
session_write_close();

#try first if is is a complex absence/presence
$query = 'select * from planningteam where planningteamcomplexid=? order by planningdate';
$query_prm = array($planningteamid);
require('inc/doquery.php');
$numdays = $num_results;
if ($numdays > 0)
{ 
	for ($n=0;$n<$numdays;$n++)
	{
		$planningteamidA[$n] = $query_result[$n]['planningteamid'];
		for($v=1;$v<=$ds_planningteamnbvalues;$v++)
		{
			$pteamvalueA[$n][$v] = $query_result[$n]['planningteamvalueid'.$v];
		}   
	}
	$planningstart = $query_result[0]['planningdate']; 	
	$planningstop = $query_result[$numdays-1]['planningdate']; 	
}

if ($numdays == 0)
{
	#get info from Database
	$query = 'select * from planningteam where planningteamid=?';
	$query_prm = array($planningteamid);
	require('inc/doquery.php');
	$numdays = 1;

	if ($num_results > 0)
	{
		$planningdate = $query_result[0]['planningdate']; 	
		if ($planningdate != NULL)
		{
			$planningstart = $planningstop = $planningdate; 
		}
		else
		{
			$planningstart = $query_result[0]['planningstart']; 	
			$planningstop = $query_result[0]['planningstop']; 
		}

		$pteamvalueA = array();
		for($v=1;$v<=$ds_planningteamnbvalues;$v++)
		{
			$pteamvalueA[$v] = $query_result[0]['planningteamvalueid'.$v];
		}  
	}
}

if ($numdays > 0)
{
	$employeeid = $query_result[0]['employeeid'];
	$employeename = $employeeA[$employeeid];	       
	$state = $query_result[0]['state'];
	$statecomment = $query_result[0]['statecomment'];
	$tointerimmanager = $query_result[0]['tointerimmanager'];          
	$deleted = $query_result[0]['deleted'];

?>

	<section id="share">
		<a href="javascript:window.print()" class="btn btn-success"><?php echo d_trad('print');?></a>
	</section>
	<div id="main">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-2">
					<div class="logo">
						<img class="img-responsive" alt="logo" src="../pics/logo.jpg">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-offset-1 col-xs-15 text-center document-title">
					<h3 class="title text-uppercase"><b>
						<?php 
						echo d_trad('absencepresencerequest');?>
					</b></h3>
				</div>
			</div>                
			<div class="row">
				<h3>
					<div class="col-xs-offset-1 col-xs-12">
						<table>
							<tr><td><b><?php echo d_trad('employee:');?></b></td>
									<td><?php echo $employeename;?></td></tr>
							<tr><td colspan=2>&nbsp;</td></tr>
							<tr><td><b><?php echo d_trad('date:');?></b></td>
									<td><?php 
											if ($planningdate != NULL) { echo datefix2($planningdate);}
											else { echo d_trad('fromto',array(datefix2($planningstart),datefix2($planningstop)));}?></td></tr>	
							<tr><td colspan=2>&nbsp;</td></tr>	
							<?php if ($numdays == 1)
							{?>							
								<tr><td colspan = 2><b><?php echo d_trad('planningteamvalue:');?></b></td></tr>						
										<?php
										for($v=1;$v<=$ds_planningteamnbvalues;$v++)
										{
											echo '<tr><td></td><td>' .$ds_termplanningvalueA[$v] . ': ' .$planningteamvalueA[$pteamvalueA[$v]] .'</td></tr>';
										}
										?>
								<tr><td colspan=2>&nbsp;</td></tr>		
							<?php }
							else
							{
								for ($n=0;$n<$numdays;$n++)
								{
									$planningdate = d_getdateadddays($n,$planningstart); 
									echo '<tr><td colspan=3><b>' . datefix2($planningdate) . ':</b></td></tr>';
									for($v=1;$v<=$ds_planningteamnbvalues;$v++)
									{
										echo '<tr><td></td>';
										echo '<td>' . $ds_termplanningvalueA[$v] . ': ' . $planningteamvalueA[$pteamvalueA[$n][$v]] . '</td>';
									}
									echo '<tr><td colspan=2>&nbsp;</td></tr>	';
								}
							}
							?>
							<tr><td><b><?php echo d_trad('state:');?></b></td>
									<td><?php echo d_trad('absencestate' . $state);?></td></tr>
							<tr><td colspan=2>&nbsp;</td></tr>									
							<?php if ($statecomment != '')
							{?>
								<tr><td><b><?php echo d_trad('comment:');?></b></td>
										<td><?php echo $statecomment;?></td></tr>
								<tr><td colspan=2>&nbsp;</td></tr>										
							<?php }
							if ($tointerimmanager == 1)
							{?>
								<tr><td><b><?php echo d_trad('absentmanager:') . '&nbsp;';?></b></td>
										<td><input type=checkbox checked disabled="disabled"></td></tr>
								<tr><td colspan=2>&nbsp;</td></tr>										
							<?php }
							if ($deleted == 1)
							{?>
								<tr><td><b><?php echo d_trad('deleted:') . '&nbsp;';?></b></td>
										<td><input type=checkbox checked disabled="disabled"></td></tr>
								<tr><td colspan=2>&nbsp;</td></tr>										
							<?php }?>							
					</div>							
				<h3>
			</div>
		</div>
	</div>
<?php
}
else
{
	echo '<p class=alert>' . d_trad('noresult') . '</p>';
}
