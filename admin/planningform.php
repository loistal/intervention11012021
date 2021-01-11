<?php 
$ds_curdate = $_SESSION['ds_curdate'];
$ds_userid = $_SESSION['ds_userid'];
if($_SESSION['ds_myemployeeid'] > 0){ $ds_userid = $_SESSION['ds_myemployeeid'];}
$currentday = mb_substr($ds_curdate,8,2);
$currentmonth = mb_substr($ds_curdate,5,2);
$currentyear = mb_substr($ds_curdate,0,4);
$currenttimestamp = mktime(0,0,0,$currentmonth,$currentday,$currentyear); # TODO don't use mktime
$currentweek = date("W",$currenttimestamp);
$actionform = $_GET['actionform'];
$target = '';
$form = 1;
if($actionform == 'reportwindow')
{
	$target = 'target="_blank"';
	echo '<h2>' . d_trad('planningreport:') . '</h2>';
}
else if($actionform == 'admin')
{
	echo '<h2>' . d_trad('modifyplanning:') . '</h2>';	
}

if($form)
{
	echo '<form method="post" action="' . $actionform . '.php" ' .$target .' >';
	?>
		<table>
			<tr>
				<td><?php echo d_trad('planningtype:'); ?></td>    
				<td><input type='radio' name='periodic' value=-1 checked /><?php echo d_trad('all'); ?></td>
			</tr>
			<tr>
				<td></td>
				<td><input type='radio' name='periodic' value=0 /><?php echo d_trad('punctual'); ?></td>
			</tr>    
			<tr>
				<td></td>
				<td><input type='radio' name='periodic' value=1 /><?php echo d_trad('weekly'); ?></td>
			</tr>
			<tr>
				<td></td>
				<td><input type='radio' name='periodic' value=2 /><?php echo d_trad('monthly'); ?></td>
			</tr>
			<tr>
				<td></td>
				<td><input type='radio' name='periodic' value=3 /><?php echo d_trad('yearly'); ?></td>
			</tr>
			<tr>
        <td><?php echo d_trad('startdate:'); ?></td>
        <td><?php $datename = 'startdate'; $dp_setempty=1;require('inc/datepicker.php');?></td>
			</tr>
			<tr>
				<td><?php echo d_trad('stopdate:'); ?></td>
				<td><?php $datename = 'stopdate'; $dp_setempty=1;require('inc/datepicker.php');?></td>
			</tr>
			<tr>
				<?php $dp_itemname = 'employee'; $dp_selectedid = $ds_userid; $dp_allowall= 1; $dp_noblank=1;$dp_description = d_trad('employee');?>
				<td><?php require('inc/selectitem.php');?></td>
			</tr>
			<tr>
				<td><?php require('inc/selectclient.php');?></td>
			</tr>    
			<tr>
				<?php $dp_itemname = 'resource'; $dp_allowall= 1; $dp_noblank=1;$dp_description = d_trad('resource'); ?>
				<td><?php require('inc/selectitem.php');?></td>
			</tr>
			<tr>
			<td colspan=2 align=right>
				<input type=hidden name="report" value="planningreport">
				<input type=hidden name="adminmenu" value="modplanning">			
				<input type="submit" value="<?php echo d_trad('validate');?>">
			</td></tr>
		</table>
	</form>
<?php }?>
