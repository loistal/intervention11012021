<?php

require('preload/training.php');

$ALL = 'ALL';
$MYTEAM = 'MYTEAM';
$TEAMIMANAGE = 'TEAMIMANAGE';
$OTHERTEAM = '-';
$MAX_LENGTH_DISPLAYED = 60;

$title = d_trad('trainingreport');
echo '<h2>' . $title . '</h2>';?>

<form method="post" action="printwindow.php" target="_blank"><table>
<tr>
	<td><?php echo d_trad('startdate:'); ?></td>
	<td><?php $datename = 'startdate'; $dp_datepicker_min = '2014-01-01';$dp_datepicker_max = $_SESSION['ds_curdate'];$dp_setempty=1;require('inc/datepicker.php');?></td>
</tr>	
<tr>
	<td><?php echo d_trad('stopdate:'); ?></td>
	<td><?php $datename = 'stopdate'; $dp_datepicker_min = '2014-01-01';$dp_datepicker_max = $_SESSION['ds_curdate'];$dp_setempty=1;require('inc/datepicker.php');?></td>
</tr>	
<?php   
$dp_itemname = 'employee'; $dp_noblank = 1; $dp_description = 'Employé(e)';
        require('inc/selectitem.php');

$dp_itemname = 'employeecategory'; $dp_allowall = 1;$dp_noblank=1;$dp_description = d_trad('employeecategory');
require('inc/selectitem.php');

$dp_itemname = 'training'; $dp_allowall = 1;$dp_noblank=1;$dp_description = d_trad('trainingname');
require('inc/selectitem.php');?>

<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan="2" align="center">
	<input type=hidden name="hrmenu" value="trainingreportform">
	<input type=hidden name="report" value="hr_trainingreport">                   
	<input type=hidden name="usedefaultstyle" value="1">             
	<input type=hidden name="ismanager" value="<?php echo $ismanager;?>">             
	<input type=hidden name="myemployeedepartmentid" value="<?php echo $myemployeedepartmentid;?>">             
	<input type=hidden name="myemployeesectionid" value="<?php echo $myemployeesectionid;?>">          
	<input type="submit" value="<?php echo d_trad('validate');?>">
</td></tr>
</table>
</form>