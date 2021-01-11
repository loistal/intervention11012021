<?php
### keep this
if ($_SESSION['ds_systemaccess'] != 1) { require('logout.php'); exit; }
require ('inc/standard.php');
$dauphin_currentmenu = basename(__FILE__, '.php');?>

<link rel="stylesheet" href="printwindow/hr_report.css">
<link rel="stylesheet" href="declaration/bootstrap.css">
<link rel="stylesheet" href="declaration/print.css">

<?php
require('inc/func_planning.php');
if (!isset($employeesortedbyteamA)) {require ('preload/employeesortedbyteam.php');}
require ('preload/employeecategory.php');
require ('preload/training.php');

$ALL = 'ALL';
$MYTEAM = 'MYTEAM';
$TEAMIMANAGE = 'TEAMIMANAGE';
$OTHERTEAM = '-';
$STEP_FORM_MODIFYEMPLOYEE = 3;

$STATE_SAVED = 0;
$STATE_ACCEPTED = 1;
$STATE_PROCESSED = 2;

#MANAGER ACCESS
$ds_systemaccess = $_SESSION['ds_systemaccess']+0;
$ds_myemployeeid = $_SESSION['ds_myemployeeid']+0;
$ds_ishrsuperuser = $_SESSION['ds_ishrsuperuser']+0;

#export file
$CSV_DELIMITER = ';';

#don't deplace it
session_write_close();

#get parameters
#if page reloaded by click on export
$isclickexport = $_GET['isclick'] +0;
if ($isclickexport == 1) 
{
  $startdate = $_GET['startdate'];
  $stopdate = $_GET['stopdate'];
  $employeeid_save = $_GET['empid'];
  $employeeid = $employeeid_save;
  $employeecategoryid = $_GET['empcatid'] +0;
  $trainingid =  $_GET['trid'] +0;
  $ismanager = $_GET['ism']+0;
  $myemployeedepartmentid = $_GET['mydid']+0;
  $myemployeesectionid = $_GET['mysid']+0;

  $filename = 'hr_trainingreport_dpt' . $myemployeedepartmentid .'_section' . $myemployeesectionid .'_' . date("Y_m_d_H_i_s") . '.csv';
  $filepath = 'customfiles/' . $filename;

  $file = fopen($filepath, "w");
  if (!$file) { echo '<p class=alert>' . d_trad('technicalerrorfilecreation') . '</p>';}
}
else
{
  $datename = 'startdate'; require('inc/datepickerresult.php');   
  $datename = 'stopdate'; require('inc/datepickerresult.php');  
  $employeeid = $employeeid_save = $_POST['employeeid'];
  $ismanager = $_POST['ismanager']+0;
  $myemployeedepartmentid = $_POST['myemployeedepartmentid'] +0;
  $myemployeesectionid = $_POST['myemployeesectionid']+0;  
  $employeecategoryid = $_POST['employeecategoryid'] +0;
  $trainingid =  $_POST['trainingid'] +0;	
  $datename = 'startdate'; require('inc/datepickerresult.php');	
  $datename = 'stopdate'; require('inc/datepickerresult.php');	
}

$dp_employeecategoryid = $employeecategoryid;require('hr/chooseemployeewithteams.php');

#display params
$title = d_trad('trainingreport');
showtitle($title);
#set in chooseemployeewithteams
if ($ourparams == '') { $ourparams = $title;}
if ($employeecategoryid > 0)
{
	$ourparams2 .= ' ' . $employeecategoryA[$employeecategoryid];	
}	
if ($trainingid > 0)
{
	$ourparams2 .= ' ' . $trainingA[$trainingid];
}
if ($stopdate > 0)
{
	$ourparams2 .= ' ' .d_trad('fromto',array(datefix2($startdate),datefix2($stopdate)));	
}
#to create xls file
$xlsA = array();

?>

<section id="share">
  <?php
  if ($isclickexport == 1) 
  {
    echo '<a href="' . $filepath .'" download="' .$filename .'" class="btn btn-success">' . d_trad('download') . '</a>';
  }
  else
  {
    echo '<a href="printwindow.php?report=hr_trainingreport&isclick=1&startdate=' . $startdate . '&stopdate=' . $stopdate . '&empid=' . $employeeid_save. '&empcatid=' . $employeecategoryid. '&trid=' . $trainingid . '&ism=' .$ismanager . '&mydid=' . $myemployeedepartmentid . '&mysid=' . $myemployeesectionid . '" class="btn btn-success">' . d_trad('export') .'</a>';
  }
  ?>
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
        <h1 class="title text-uppercase">
          <?php echo $ourparams; ?>
        </h1>
      </div>
      <div class="col-xs-offset-1 col-xs-15 text-center document-title">
        <p>
          <strong>
            <?php echo $ourparams2; ?>
          </strong>
        </p>
      </div>
    </div>                
    <div class="row">
      <div class="col-xs-12">
        <table class="table-bordered full-width-table">
					<thead>
              <?php
              if($nbemployees > 1)
              {
                #employee name column
                echo '<th>' . d_trad('name') . '</th>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('name')));
              }
              ?>
              <th><?php echo d_trad('employeecategory'); ?></th><?php array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('employeecategory')));?>
              <th><?php echo d_trad('startdate'); ?></th><?php array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('startdate')));?>
              <th><?php echo d_trad('stopdate'); ?></th><?php array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('stopdate')));?>
              <th><?php echo d_trad('training'); ?></th><?php array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('training')));?>
            </tr>
				</thead>
				<tbody>
            <?php
            if (($isclickexport == 1) && $file) { fputcsv ( $file , $xlsA , $CSV_DELIMITER); }
            #for a new line in file
            $xlsA = array();

# DISPLAY RESULTS
#for each employee  a query
$ismanager_prev = 0;

for($e=0;$e<$nbemployees;$e++)
{
  $eid = $employee_todisplayA[$e]['employeeid'];
  $ename = $employeesortedbyteamA[$eid];

	$query = 'select * from trainingemployeeplanning tep,trainingplanning tp ';
	$query .= ' where tep.trainingplanningid=tp.trainingplanningid and tep.deleted = 0 and tp.deleted = 0 and tep.employeeid =?'; 
	$query_prm = array($eid);

  // if ($employeecategoryid > 0)
	// {
		 //already taken ito account in chooseemployeewithteams.php in query
	// }	
	if ($trainingid > 0)
	{
		$query .= ' and tep.trainingid =?';array_push($query_prm,$trainingid);
	}
	if ($startdate > 0)
	{
		$query .= ' and tp.startdate >=?';array_push($query_prm,$startdate);
	}
	if ($stopdate > 0)
	{
		$query .= ' and tp.stopdate <=?';array_push($query_prm,$stopdate);
	}
	$query .= ' order by tp.startdate,tep.trainingid';
	$query .= ',tep.employeeid';
	require('inc/doquery.php');

	for($n=0;$n<$num_results;$n++)
	{
		$row = $query_result[$n];
		$trainingidresult = $row['trainingid'];	
		$trainingnameresult = $trainingA[$trainingidresult];	
		$startdateresult = $row['startdate'];	
		$stopdateresult = $row['stopdate'];	
		$employeecategoryidresult = $employeesortedbyteam_categoryidA[$eid];	
		$employeecategorynameresult = $employeecategoryA[$employeecategoryidresult];	
	
			
		echo '<tr>';
		if ($nbemployees > 1)    
		{
			echo '<td class="text-right">';
			if ($ismanager) { echo '<b>'; }
			if ($ds_systemaccess) { echo '<a href="hr.php?hrmenu=modemployee&step=' . $STEP_FORM_MODIFYEMPLOYEE . '&id=' . $eid . '" target=_blank>' .  $ename . '</a>';}
			else { echo $ename; } 
			echo '</td>';array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',$ename));
		}
		echo '<td class="text-right">' . $employeecategorynameresult . '</td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',$employeecategorynameresult)); 
		echo '<td class="text-right">' . datefix2($startdateresult) . '</td>'; array_push($xlsA,$startdateresult);
		echo '<td class="text-right">' . datefix2($stopdateresult) . '</td>'; array_push($xlsA,$stopdateresult);
		echo '<td class="text-right">' . $trainingnameresult . '</td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',$trainingnameresult));     

		if (($isclickexport == 1) && $file) { fputcsv ( $file , $xlsA , $CSV_DELIMITER); }
		$xlsA = array();
	}
}//for $employeeid


if (($isclickexport == 1) && $file) { fclose($file);}
?>
          </tbody>
        </table>
</div>
</div>
</body>
</html>
</table>
