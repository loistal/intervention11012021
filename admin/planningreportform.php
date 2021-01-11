<h2><?php echo d_trad('planning:'); ?></h2>

<?php 
$ds_curdate = $_SESSION['ds_curdate'];
$ds_userid = $_SESSION['ds_userid'];

$currentday = mb_substr($ds_curdate,8,2);
$currentmonth = mb_substr($ds_curdate,5,2);
$currentyear = mb_substr($ds_curdate,0,4);
$currenttimestamp = mktime(0,0,0,$currentmonth,$currentday,$currentyear);
$currentweek = date(W,$currenttimestamp);
?>

<form method="post" action="admin.php">
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
      <?php $dp_itemname = 'employee'; $dp_selectedid = $ds_userid; $dp_description = d_trad('employee');?>
      <td><?php require('inc/selectitem.php');?></td>
    </tr>
    <tr>
      <td><?php require('inc/selectclient.php');?></td>
    </tr>    
    <tr>
      <?php $dp_itemname = 'resource'; $dp_description = d_trad('resource'); ?>
      <td><?php require('inc/selectitem.php');?></td>
    </tr>
    <tr><td colspan=2 align=right><input type=hidden name="adminmenu" value="planningreport"><input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
  </table>
</form>
