<link rel="stylesheet" href="printwindow/hr_report.css">
<link rel="stylesheet" href="declaration/bootstrap.css">
<link rel="stylesheet" href="declaration/print.css">

<?php

# complete mess, might have to redo everything
# TODO start over in reportwindow, dont even bother to look at this junk

require('inc/func_planning.php');
require ('preload/employee.php');

$ALL = 'ALL';
$MYTEAM = 'MYTEAM';
$TEAMIMANAGE = 'TEAMIMANAGE';
$OTHERTEAM = '-';
$STEP_FORM_MODIFYEMPLOYEE = 3;

$STATE_SAVED = 0;
$STATE_ACCEPTED = 1;
$STATE_PROCESSED = 2;

#MANAGER ACCESS
#$ds_systemaccess = $_SESSION['ds_systemaccess']+0;
$ds_myemployeeid = $_SESSION['ds_myemployeeid']+0;
$ds_ishrsuperuser = $_SESSION['ds_ishrsuperuser']+0;

#export file
$CSV_DELIMITER = ';';

#don't deplace it
session_write_close();

#get parameters
#if page reloaded by click son export
$isclickexport = $_GET['isclick'] +0;
if ($isclickexport == 1) 
{
  $startdate = $_GET['startdate'];
  $stopdate = $_GET['stopdate'];
  $employeeid_save = $_GET['empid'];
  $employeeid = $employeeid_save;
  $ismanager = $_GET['ism']+0;
  $myemployeedepartmentid = $_GET['mydid']+0;
  $myemployeesectionid = $_GET['mysid']+0;

  $filename = 'hr_travelexpensesreport_dpt' . $myemployeedepartmentid .'_section' . $myemployeesectionid .'_' . date("Y_m_d_H_i_s") . '.csv';
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
}

#require('hr/chooseemployeewithteams.php');   ?????????????

#display params
#$employeeidempty = 1;

$title = d_trad('travelexpenses');
showtitle($title);
#set in chooseemployeewithteams
if ($ourparams == '') { $ourparams = $title;}
$ourparams2 = d_trad('fromto',array(datefix2($startdate),datefix2($stopdate)));
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
    echo '<a href="printwindow.php?report=hr_travelexpensesreport&isclick=1&startdate=' . $startdate . '&stopdate=' . $stopdate . '&empid=' . $employeeid_save . '&ism=' .$ismanager . '&mydid=' . $myemployeedepartmentid . '&mysid=' . $myemployeesectionid . '" class="btn btn-success">' . d_trad('export') .'</a>';
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
                echo '<th>' . d_trad('name') .'</th>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('name')));
              }
              ?>
              <th><?php echo d_trad('numtravels'); ?></th><?php array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('numtravels')));?>
              <th><?php echo d_trad('numdays'); ?></th><?php array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('numdays')));?>
              <th><?php echo d_trad('totalrefundamount'); ?></th><?php array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('totalrefundamount')));?>
              <th><?php echo d_trad('totalrefundamountvat'); ?></th><?php array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('totalrefundamountvat')));?>
            </thead>
						<tbody>						
            <?php
            if (($isclickexport == 1) && $file) { fputcsv ( $file , $xlsA ,$CSV_DELIMITER); }
            #for a new line in file
            $xlsA = array();

# DISPLAY RESULTS
#for each employee = each line
$ismanager_prev = 0;
$totaltravels = 0; $totaldays = 0; $totalrefundamount= 0; $totalrefundamountvat= 0;
#for($e=0;$e<$nbemployees;$e++)
{
  #$eid = $employee_todisplayA[$e]['employeeid'];
  $eid = $employeeid;
  $ename = $employeeA[$eid];
  $eismanager = $employeesortedbyteam_ismanagerA[$eid];
  $numtravels = 0; $numdays = 0; $refundamount = 0; $refundamountvat = 0;
  
  #number of travel expenses by employee
  $query = 'select * from travelexpense where startdate>= ? and stopdate<= ? and deleted=0 and employeeid=?'; # and state in (?,?)
  $query_prm = array($startdate,$stopdate,$eid); # ,$STATE_ACCEPTED,$STATE_PROCESSED
  require('inc/doquery.php');
  $numtravelexpenses = $num_results;$travelexpensesA = $query_result;
  if ($numtravelexpenses > 0)
  {
    $numtravels = count($travelexpensesA);
    $totaltravels += $numtravels;
    for($n=0;$n<$numtravelexpenses;$n++)
    {
      $numdays += d_numdays($travelexpensesA[$n]['startdate'],$travelexpensesA[$n]['stopdate']);   
      $query = 'select * from travelexpenseitem where deleted=0 and travelexpenseid=?';
      $query_prm = array($travelexpensesA[$n]['travelexpenseid']);
      require('inc/doquery.php');
      $numtravelexpenseitems = $num_results;$travelexpenseitemsA = $query_result;
      if ($num_results > 0)
      {
        for($i=0;$i<$numtravelexpenseitems;$i++)
        {         
          $refundamount += $travelexpenseitemsA[$i]['refundamount'];
          $refundamountvat += $travelexpenseitemsA[$i]['refundamountVAT'];
        }
      }
    }
    $totaldays += $numdays;       
    $totalrefundamount += $refundamount;       
    $totalrefundamountvat += $refundamountvat;
  }

  
  #display total for each team
  /*if($e != 0 && (($ismanager_prev == 0 && $ismanager == 1)))
  {
    echo '<tr>';   
    echo '<td><b>' . d_trad('teamtotal') . '</b></td>';

    for($c=0;$c<$nbcol;$c++)
    {
      echo '<td><b>' . $totalA[$c] . '</b></td>';
      $totalA[$c] = 0;
    }
    
    #reinitialize totals by team
    $grandtotalpresence = $grandtotalabsence = $grandtotalnotvalidated = $smalltotalovertime = $grandtotalovertime = 0;
    
    $nbtotal ++;
  }*/
    
  echo '<tr>';
  if($nbemployees > 1)
  {      
    echo '<td class="text-right">';
    if ($ismanager) { echo '<b>'; }
    #if ($ds_systemaccess) { echo '<a href="hr.php?hrmenu=modemployee&step=' . $STEP_FORM_MODIFYEMPLOYEE . '&id=' . $eid . '" target=_blank>' .  $ename . '</a>';}
    #else 
    #{ 
    echo $ename; 
    #} 
    echo '</td>';array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',$ename));
  }
   
  echo '<td class="text-center">' . $numtravels . '</td>'; array_push($xlsA,$numtravels);  
  #increased hours
  echo '<td class="text-center">' . $numdays . '</td>'; array_push($xlsA,$numdays); 
  echo '<td class="text-center">' . myfix($refundamount) . '</td>'; array_push($xlsA,$refundamount);
  echo '<td class="text-center">' . myfix($refundamountvat) . '</td>'; array_push($xlsA,$refundamountvat);     
  $ismanager_prev = $ismanager;
  
  if (($isclickexport == 1) && $file) { fputcsv ( $file , $xlsA ,$CSV_DELIMITER); }
  $xlsA = array();
}//for $employeeid

#TOTAL
if($nbemployees > 1)
{   
  #total for last team
  echo '<tr><td class="text-right"><b>' . d_trad('total') . '</b></td>';array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('total')));  
  echo '<td class="text-center">' . $totaltravels . '</td>'; array_push($xlsA,$totaltravels);  
  #increased hours
  echo '<td class="text-center">' . $totaldays . '</td>'; array_push($xlsA,$totaldays); 
  echo '<td class="text-center">' . myfix($totalrefundamount) . '</td>'; array_push($xlsA,$totalrefundamount);
  echo '<td class="text-center">' . myfix($totalrefundamountvat) . '</td>'; array_push($xlsA,$totalrefundamountvat);  
  if (($isclickexport == 1) && $file) { fputcsv ( $file , $xlsA ,$CSV_DELIMITER); }  
}
if (($isclickexport == 1) && $file) { fclose($file);}
?>
          </tbody>
        </table>
</div>
</div>
</body>
</html>
</table>
