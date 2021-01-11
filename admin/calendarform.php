<h2><?php echo d_trad('calendar:'); ?></h2>

<?php
require('inc/func_planning.php'); 
$ds_curdate = $_SESSION['ds_curdate'];
$ds_userid = $_SESSION['ds_userid'];
if($_SESSION['ds_myemployeeid'] > 0){ $ds_userid = $_SESSION['ds_myemployeeid'];}
$currentday = mb_substr($ds_curdate,8,2);
$currentmonth = mb_substr($ds_curdate,5,2);
$currentyear = mb_substr($ds_curdate,0,4);
$currenttimestamp = mktime(0,0,0,$currentmonth,$currentday,$currentyear);
$currentweek = date("W",$currenttimestamp)+0;
?>

<form method="post" action="reportwindow.php" target="_blank">
  <table>
    <tr>
        <td><?php echo d_trad('planningtype:'); ?></td>    
        <td><input type='radio' name='period' value=0 checked /><?php echo d_trad('date:'); ?></td>
        <td><?php $datename = 'date'; require('inc/datepicker.php');?></td>
    </tr>
    <tr>
      <td></td>
      <td><input type='radio' name='period' value=1 /><?php echo d_trad('week:'); ?></td>
      <td><select name="week">
            <?php 
            for($week=1;$week<=52;$week++)
            {
              echo '<option value=' . $week;
              if ($currentweek == $week) { echo ' selected'; }
              echo '>';
							$year = $currentyear;
							if($week < $currentweek){$year = $currentyear +1;}
							$dateA[0] = d_getmonday_todisplay($week,$year);
							$dateA[1] = d_getsunday_todisplay($week,$year);
							echo d_trad('weekparam:',array($week,$dateA[0],$dateA[1])) . '</option>';    
            }
            ?>

      </td>
    </tr>
   <!--<tr>
      <td></td>
      <td><input type='radio' name='period' value=2 /><?php //echo d_trad('month:'); ?></td>
      <td><select name="month">
            <?php 
            /*for($i=1;$i<=12;$i++)
            {
              echo '<option value=' . $i;
              if ($currentmonth == $i) { echo ' selected'; }
              echo '>' .$i. '</option>';
            }*/
            ?>
      </td>
    </tr>-->
   <tr>
      <td><?php echo d_trad('time:'); ?></td>
      <td><select name="starthour">
            <?php 
            for($i=0;$i<=24;$i++)
            {
              for($m=0;$m<=45;$m+=15)
              {
                echo '<option value="' . $i . ':' . $m .'"';
                if ($i == 5 && $m == 0) { echo ' selected'; }
                echo '>'. d_displayhourmin($i,$m) . '</option>';
              }
            }
			echo '</select>';
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . d_trad('time_to');
			?>
      </td>
      <td><select name="stophour">
            <?php 
            for($i=0;$i<=24;$i++)
            {
              for($m=0;$m<=45;$m+=15)
              {          
                echo '<option value="' . $i . ':' . $m .'"';
                if ($i == 17 && $m == 0) { echo ' selected'; }
                echo '>'. d_displayhourmin($i,$m) . '</option>';
                echo '</option>';
              }
            }
			echo '</select>';
            ?>
      </td>      
    </tr>    
    <tr>
      <?php	$dp_itemname = 'employee'; $dp_allowall= 1; $dp_noblank=1; $dp_description = d_trad('employee'); $dp_colspan=2; #$dp_selectedid = $ds_userid; 
      ?>
      <td><?php require('inc/selectitem.php');?></td>
    </tr>
    <tr>
      <td><?php $dp_colspan=2;require('inc/selectclient.php');?></td>
    </tr>    
    <tr>
      <?php $dp_itemname = 'resource'; $dp_allowall= 1; $dp_noblank=1; $dp_description = d_trad('resource');$dp_colspan=2; ?>
      <td><?php require('inc/selectitem.php');?></td>
    </tr>
    <tr>
			<td colspan=2 align=right>
				<input type=hidden name="adminmenu" value="calendar">
				<input type=hidden name="report" value="calendar">
				<input type=hidden name="iscalendarform" value="1">        
				<input type="submit" value="<?php echo d_trad('validate');?>">
			</td>
		</tr>
  </table>
</form>
