<?php
echo '<h2>' . d_trad('topclients') . '</h2>';

$ORDER_BY_SALES = 1;
$ORDER_BY_CLIENTNAME = 2;
$ORDER_BY_CLIENTCATEGORY = 3;
$ORDER_BY_ISLANDNAME = 4;
?>

<form method="post" action="reportwindow.php" target="_blank">
  <table>
    <tr>
      <td><?php echo d_trad('choice:'); ?></td>   
      <td><?php echo d_trad('top') . ' '; ?>
      <input type=text name="choice" value=10 size=8 style="text-align: right">
      </td>
    </tr> 
    <?php   $dp_itemname = 'employee'; $dp_issales = 1; $dp_allowall = 1;$dp_description = d_trad('employee'); require('inc/selectitem.php'); ?>   
    <?php   $dp_itemname = 'island';$dp_allowall = 1; $dp_description = d_trad('island'); require('inc/selectitem.php'); ?>   
    <?php   $dp_itemname = 'clientcategory';$dp_allowall = 1; $dp_description = d_trad('clientcategory'); require('inc/selectitem.php'); ?>   
    <tr><td><?php echo d_trad('startdate:'); ?></td>
    <td><?php $datename = 'startdate';$selecteddate = '';$dp_setempty = 1;require('inc/datepicker.php');?></td></tr>
    
    <tr><td><?php echo d_trad('stopdate:'); ?></td>
    <td><?php $datename = 'stopdate';$selecteddate = '';$dp_setempty = 1;require('inc/datepicker.php');?></td></tr>     
    <tr>
      <td><?php echo d_trad('orderby:'); ?></td>
      <td><select name=orderby>
            <?php
            echo '<option value=' . $ORDER_BY_SALES . '  selected>' .  d_trad('sales') . '</option>';
            echo '<option value=' . $ORDER_BY_CLIENTNAME . '>' .  d_trad('clientname') . '</option>';
            echo '<option value=' . $ORDER_BY_CLIENTCATEGORY . '>' .  d_trad('clientcategory') . '</option>';
            echo '<option value=' . $ORDER_BY_ISLANDNAME . '>' .  d_trad('island') . '</option>';
            ?>
          </select>
      </td>
    </tr>
    <tr>
			<td colspan=2 align=center>
				<input type=hidden name="hrmenu" value="topclientsreport">
				<input type=hidden name="report" value="topclientsreport">                      
				<input type="submit" value="<?php echo d_trad('validate');?>">
			</td>
		</tr>
  </table>
</form>