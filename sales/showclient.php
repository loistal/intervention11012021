<h2>Fiche client:</h2>
<form method="post" action="reportwindow.php" target="_blank">
<table>

<tr><td>
Client:<td><input autofocus type="text" STYLE="text-align:right" name="client" size=20>

<tr><td>Infos:<td><select name="ficheinfos">
<option value=1>10 derniers</option>
<option value=2>Cette année</option>
<option value=3>L'année passée</option>
<option value=4>5 ans</option>
</select></td></tr>

<tr>
<td><?php echo d_trad('week:'); ?></td>
<td><select name="week">
<?php 

require('inc/func_planning.php'); 
$ds_curdate = $_SESSION['ds_curdate'];
$ds_userid = $_SESSION['ds_userid'];
if($_SESSION['ds_myemployeeid'] > 0){ $ds_userid = $_SESSION['ds_myemployeeid'];}
$currentday = mb_substr($ds_curdate,8,2);
$currentmonth = mb_substr($ds_curdate,5,2);
$currentyear = mb_substr($ds_curdate,0,4);
$currenttimestamp = mktime(0,0,0,$currentmonth,$currentday,$currentyear);
$currentweek = date('W',$currenttimestamp)+0;

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

<tr><td colspan="2" align="center"><input type=hidden name="step" value="1">
<input type=hidden name="report" value="showclient">
<input type="submit" value="Valider"></table></form>