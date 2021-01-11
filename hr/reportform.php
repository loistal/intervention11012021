<h2><?php echo d_trad('report'); ?></h2>

<?php
require('inc/func_planning.php'); 
unset($planningteamvalueA);$hr_orderby_absence=1;require('preload/planningteamvalue.php');
$ds_curdate = $_SESSION['ds_curdate'];

$currentday = mb_substr($ds_curdate,8,2);
$currentmonth = mb_substr($ds_curdate,5,2);
$currentyear = mb_substr($ds_curdate,0,4);
$currenttimestamp = mktime(0,0,0,$currentmonth,$currentday,$currentyear);
$currentweek = date(W,$currenttimestamp);
if(startswith($currentweek,'0')){$currentweek = mb_substr($currentweek,1,1);}
$ALL = 'ALL';
$MYTEAM = 'MYTEAM';
$TEAMIMANAGE = 'TEAMIMANAGE';
$OTHERTEAM = '-';
?>

<form method="post" action="printwindow.php" target="_blank">
  <table>
    <tr>
      <td><?php echo d_trad('planningtype:'); ?></td>   
      <td colspan=4><input type='radio' name='period' value=1 checked /><?php echo d_trad('week:'); ?>
      <select name="week_year">
            <?php 
            for($year=$currentyear-1;$year<=$currentyear+1;$year++)
            {
              $numweeks = d_getnbweeksinyear($year);
              for($week=1;$week<=$numweeks;$week++)
              {       
                echo '<option value=' . $week. '_' . $year;
                if ($currentweek == $week && $currentyear == $year) { echo ' selected'; }
                echo '>';    
                $dateA[0] = d_getmonday_todisplay($week,$year);
                $dateA[1] = d_getsunday_todisplay($week,$year);
                echo d_trad('weekparam:',array($week,$dateA[0],$dateA[1])) . '</option>';    
              }
            }
            ?>

      </td>
    </tr>
   <tr>
      <td></td>
      <td><input type='radio' name='period' value=2 /><?php echo d_trad('month:'); ?></td>
      <td><select name="month">
          <?php 
          for($i=1;$i<=12;$i++)
          {
            echo '<option value=' . $i;
            if ($currentmonth == $i) { echo ' selected'; }
            echo '>' .$i. '</option>';
          }
          ?>
      </td>
      <td style="align:left"><?php echo d_trad('year:'); ?></div></td>
      <td><select name="year">
          <?php 
          for($i=$currentyear-1;$i<=$currentyear+1;$i++)
          {
            echo '<option value=' . $i;
            if ($currentyear == $i) { echo ' selected'; }
            echo '>' .$i. '</option>';
          }
          ?>
      </td>    
    </tr>
    
    <tr>
      <td><?php echo d_trad('planningteamvalue:');?></td>
      <td colspan=4>
        <select name= "planningteamvalueid">
          <?php 
          echo '<option value="-1">' . d_trad('all') . '</option>';
          foreach($planningteamvalueA as $planningtvid =>$planningtvname)
          {
            echo '<option value="'. $planningtvid .'">' . $planningtvname  . '</option>';
          }?>
      </td>
    </tr>

    <?php require('hr/chooseemployeewithteamsform.php'); ?>
    
    <tr>
			<td colspan=5 align=center>
				<input type=hidden name="hrmenu" value="badgereportform">
				<input type=hidden name="report" value="hr_planningreport">                   
				<input type=hidden name="usedefaultstyle" value="1">                    
				<input type=hidden name="ismanager" value="<?php echo $ismanager;?>">             
				<input type=hidden name="myemployeedepartmentid" value="<?php echo $myemployeedepartmentid;?>">             
				<input type=hidden name="myemployeesectionid" value="<?php echo $myemployeesectionid;?>">          
				<input type="submit" value="<?php echo d_trad('validate');?>">
			</td>
		</tr>
  </table>
</form>
