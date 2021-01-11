<h2><?php echo d_trad('badgeerrors'); ?></h2><br>
<p class=alert><?php echo d_trad('heavyreport.beselective'); ?></p>

<?php
require ('preload/employeesimanage.php');
$STEP_ALERTS_MODIFYEMPLOYEE = 3;
require('inc/func_planning.php'); 
unset($planningteamvalueA);$hr_orderby_absence=1;require('preload/planningteamvalue.php');
$ds_curdate = $_SESSION['ds_curdate'];

$currentday = mb_substr($ds_curdate,8,2);
$currentmonth = mb_substr($ds_curdate,5,2);
$currentyear = mb_substr($ds_curdate,0,4);
$currenttimestamp = mktime(0,0,0,$currentmonth,$currentday,$currentyear);
$currentweek = date(W,$currenttimestamp);
if(startswith($currentweek,'0')){$currentweek = mb_substr($currentweek,1,1);}

$STEP_FORM = 0;
$STEP_ALERTS = 1;

if ($currentstep == $STEP_FORM)
{   
  ?>    
  <form method="post" action="hr.php"><table>
  <?php require('hr/chooseemployeewithteamsform.php');
  echo '<tr><td>' . d_trad('date:') . '</td><td colspan=2>';
  $datename = 'alertstart'; 
  #date by default: beginning and end of civil year
  $ds_curdate = $_SESSION['ds_curdate'];
  $currentmonth = mb_substr($ds_curdate,5,2);
  $currentyear = mb_substr($ds_curdate,0,4);
  $selecteddate = d_getfirstdayofmonth($currentmonth,$currentyear);    
  require('inc/datepicker.php');
  echo ' &nbsp; '.d_trad('validity_to') .' &nbsp; ';
  $datename = 'alertstop'; 
  $selecteddate = $ds_curdate;      
  require('inc/datepicker.php');
  echo '</td></tr>';?>
  
  <tr><td colspan=2>&nbsp;</td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value=<?php echo $STEP_ALERTS;?>><input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
  <input type="submit" value="<?php echo d_trad('list');?>"></td></tr>
  </table></form><?php
}
else if($currentstep == $STEP_ALERTS)
{
  $employeeid_save = $employeeid = $_POST['employeeid'];
  require('hr/chooseemployeewithteams.php');  
  $datename = 'alertstart';
  require('inc/datepickerresult.php');
  
  $datename = 'alertstop';
  require('inc/datepickerresult.php');
  
  #calculate number of days
  $numdays = d_numdays($alertstart,$alertstop);
  
  $numerrors = 0; $numomission = 0;
  $erroremployeeidA = array();$omissionemployeeidA = array();
  $erroremployeenameA = array();$omissionemployeenameA = array();
  $errorbadgeuseridA = array();$omissionbadgeuseridA = array();
  $errorbadgedateA = array();$omissionbadgedateA = array();
  for($e=0;$e<$nbemployees;$e++)
  {
    $eid = $employee_todisplayA[$e]['employeeid'];
    $ename = $employeesortedbyteamA[$eid];
    #get the number of daily checking for this category of employee
    $query = 'select ec.numdailylogs from employeecategory ec,employee e where ec.deleted=0 and ec.employeecategoryid = e.employeecategoryid and e.employeeid=? and e.deleted=0';
    $query_prm = array($eid);
    require('inc/doquery.php');
    $numdailylogs = $ds_defaultnumdailylogs;
    if ($num_results > 0)
    {
      $numdailylogs = $query_result[0]['numdailylogs'];
    }
      
    $badgeuid = $employeeimanage_badgenumberA[$eid];
    $query = 'select count(*),badgedate from badgelog where deleted=0 and badgeuserid=? and badgedate >= ? and badgedate <= ? group by badgedate';
    $query_prm = array($badgeuid,$alertstart,$alertstop);
    require ('inc/doquery.php');
    $badgelog = $query_result;    
    
    for($n=0;$n<$num_results;$n++)
    { 
      if ($badgelog[$n]['count(*)'] != $numdailylogs)
      {
        array_push($erroremployeeidA,$eid);
        array_push($erroremployeenameA,$ename);     
        array_push($errorbadgeuseridA,$badgeuid);
        array_push($errorbadgedateA,$badgelog[$n]['badgedate']);
        $numerrors ++;
      }
    }
    
    #get badgelog for each day of period 
    for($d=1;$d<=$numdays;$d++)
    {
      $date = d_getdateadddays($d-1,$alertstart) ;      
      $day = mb_substr($date,8,2);
      $month = mb_substr($date,5,2);
      $year = mb_substr($date,0,4);
      $dayofweek = d_getdayofweek($date);
      
      #to know if the day must be worked or not
      require('hr/badge_hastobeworked.php');
      
      #verify if there are badgelogs for this date
      $query = 'select badgelogid from badgelog where deleted=0 and badgeuserid=? and badgedate = ?';
      $query_prm = array($badgeuid,$date);
      require ('inc/doquery.php');

      if (($num_results == 0) && ($hastobeworked > 0))
      {
        array_push($omissionemployeeidA,$eid);
        array_push($omissionemployeenameA,$ename);     
        array_push($omissionbadgeuseridA,$badgeuid);
        array_push($omissionbadgedateA,$date);
        $numomission ++;
      }
    }
  }
  
  if ($numerrors > 0)
  {
    ?>
    <table class=report>
    <thead>
    <th><?php echo d_trad('name'); ?></th>
    <th><?php echo d_trad('date'); ?></th>
    </thead>
    <?php
    for($a=0;$a<$numerrors;$a++)
    {
      echo d_tr();
      $eid = $erroremployeeidA[$a];
      $ename = $erroremployeenameA[$a];
      $badgeuid = $errorbadgeuseridA[$a];
      $badgedate = $errorbadgedateA[$a];
      $href = 'hr.php?hrmenu=badgemanualentry&badgeemployeeid=' .$eid . '&badgeuserid=' . $badgeuid . '&badgedate=' . $badgedate; 

      echo '<td><a href="' .$href . '">' . d_output($ename) . '</a></td>';    
      echo '<td><a href="' .$href . '">'  . datefix2($badgedate) . '</a></td>';
    }
    ?>
    </table>
  <?php
  }
  else
  {
    echo '<p>' . d_trad('noresult') . '</p>';
  }?>
  
  <br><h2><?php echo d_trad('badgeomissions'); ?></h2><br>
   
  <?php if ($numomission > 0)
  {
    ?>
    <table class=report>
    <thead>
    <th><?php echo d_trad('name'); ?></th>
    <th><?php echo d_trad('date'); ?></th>
    </thead>
    <?php
    for($a=0;$a<$numomission;$a++)
    {
      echo d_tr();
      $eid = $omissionemployeeidA[$a];
      $ename = $omissionemployeenameA[$a];
      $badgeuid = $omissionbadgeuseridA[$a];
      $badgedate = $omissionbadgedateA[$a];
      $href = 'hr.php?hrmenu=badgemanualentry&badgeemployeeid=' .$eid . '&badgeuserid=' . $badgeuid . '&badgedate=' . $badgedate; 

      echo '<td><a href="' .$href . '">' . d_output($ename) . '</a></td>';    
      echo '<td><a href="' .$href . '">'  . datefix2($badgedate) . '</a></td>';
    }
    ?>
    </table>
  <?php
  }
  else
  {
    echo '<p>' . d_trad('noresult') . '</p>';
  }
}
