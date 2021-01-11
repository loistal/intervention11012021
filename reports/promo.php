<h2><?php echo d_trad('promotions:'); ?></h2>

<form method="post" action="reportwindow.php" target="_blank">
  <table>
    <tr>
        <td><?php echo d_trad('startdate:'); ?></td>
        <td><?php $datename = 'startdate'; require('inc/datepicker.php');?></td>
    </tr>
    <tr>
      <td><?php echo d_trad('stopdate:'); ?></td>
      <td><?php $datename = 'stopdate'; require('inc/datepicker.php');?></td>
    </tr>
    <tr>
      <td><?php echo d_trad('percentage:'); ?><td>
      <select name="percentage">
        <option value="0"><?php echo d_trad('selectall');?></option>   
        <option value="1"><?php echo d_trad('free');?></option>
        <option value="2" CHECKED>>1% <100%</option>
      </select>
    </tr>
    <tr>
    <?php
    $dp_description = d_trad('productfamily'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamily.php');
    ?>
    </tr>
    <tr>
      <td><?php require('inc/selectproduct.php');?></td>
    </tr>
    <tr>
      <td><?php require('inc/selectclient.php');?></td>
    </tr>    
    <tr>
      <td><?php $dp_addtoid = '1'; $dp_description = d_trad('supplier'); require('inc/selectclient.php');?> <input type=checkbox name="excludesupplier" value=1> Exclure
    </tr>   
    <tr>
      <td><?php echo d_trad('orderby:'); ?></td>
      <td><select name="promosort">
        <option value=0><?php echo d_trad('percentage');?></option>
        <option value=1><?php echo d_trad('productfamily');?></option>
        <option value=2><?php echo d_trad('product');?></option>
        <option value=3><?php echo d_trad('client');?></option>
        <option value=4><?php echo d_trad('invoice');?></option>    
        <option value=5><?php echo d_trad('supplier');?></option>   
        <option value=6><?php echo $_SESSION['ds_term_accountingdate'];?></option>  
        <option value=7><?php echo d_trad('employee');?></option>     
        </select></td>
    </tr>
    <tr><td colspan=2 align=right><input type=hidden name="report" value="promoreport"><input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
  </table>
</form>
<?php 
unset($showproduct);
?>