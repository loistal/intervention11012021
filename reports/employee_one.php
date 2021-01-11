<h2>Activité d'un Employé</h2>

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
      <td><?php $dp_itemname = 'employee'; $dp_description = d_trad('employee'); $dp_noblank = 1; require('inc/selectitem.php');?><td>
    </tr>
    <tr>
      <td><?php echo d_trad('show:'); ?></td>
      <td><input type="checkbox" name="showemployee1clients" value="1" checked>&nbsp;&nbsp;<?php echo d_trad('employee1clients',array($_SESSION['ds_term_clientemployee1'])); ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="checkbox" name="showemployee2clients" value="1" checked>&nbsp;&nbsp;<?php echo d_trad('employee1clients',array($_SESSION['ds_term_clientemployee2'])); ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="checkbox" name="showinvoicesassets" value="1">&nbsp;&nbsp;<?php echo d_trad('invoicesassets'); ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td> &nbsp; <input type="checkbox" name="showinvoicesassets1" value="1">&nbsp;&nbsp;<?php echo d_trad('invoicesassets'); ?> pour <?php echo d_trad('employee1clients',array($_SESSION['ds_term_clientemployee1'])); ?></td>
    </tr> 
    <tr>
      <td>&nbsp;</td>
      <td> &nbsp; <input type="checkbox" name="showinvoicesassets2" value="1">&nbsp;&nbsp;<?php echo d_trad('invoicesassets'); ?> pour <?php echo d_trad('employee1clients',array($_SESSION['ds_term_clientemployee2'])); ?></td>
    </tr> 
    <tr>
      <td>&nbsp;</td>
      <td><input type="checkbox" name="showpayments" value="1">&nbsp;&nbsp;<?php echo d_trad('payments'); ?></td>
    </tr> 
    <tr>
      <td>&nbsp;</td>
      <td><input type="checkbox" name="showexpenses" value="1">&nbsp;&nbsp;<?php echo d_trad('expenses'); ?></td>
    </tr>  
    <tr>
      <td>&nbsp;</td>
      <td><input type="checkbox" name="showplanning" value="1">&nbsp;&nbsp;<?php echo d_trad('planning'); ?></td>
    </tr>       
    <tr><td colspan=2 align=right><input type=hidden name="report" value="employee_one"><input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
  </table>
</form>
