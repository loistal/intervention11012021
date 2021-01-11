<h2><?php echo d_trad('report'); ?></h2>

<?php
require('inc/func_planning.php'); 
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
    <tr><td><?php echo d_trad('startdate:'); ?></td>
    <td><?php $datename = 'startdate';$selecteddate = $startdate;$dp_datepicker_min='2014-01-01';require('inc/datepicker.php');?></td></tr>
    
    <tr><td><?php echo d_trad('stopdate:'); ?></td>
    <td><?php $datename = 'stopdate';$selecteddate = $stopdate;require('inc/datepicker.php');?></td></tr> 
    
    <?php
    $dp_isform = 0;
    require('hr/chooseemployee.php');
    #require('hr/chooseemployeewithteamsform.php');
    ?>
    
    <tr>
			<td colspan=5 align=center>
				<input type=hidden name="hrmenu" value="travelexpensesreportform">
				<input type=hidden name="report" value="hr_travelexpensesreport">                   
				<input type=hidden name="usedefaultstyle" value="1">             
				<input type=hidden name="ismanager" value="<?php echo $ismanager;?>">                             
				<input type=hidden name="myemployeedepartmentid" value="<?php echo $myemployeedepartmentid;?>">             
				<input type=hidden name="myemployeesectionid" value="<?php echo $myemployeesectionid;?>">          
				<input type="submit" value="<?php echo d_trad('validate');?>">
			</td>
		</tr>
  </table>
</form>
