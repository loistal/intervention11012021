<?php # TODO REMOVE

require('preload/employee.php');

#input dp_isform
if (!isset($dp_isform)){ $dp_isform = 1;}

if ($dp_isform === 1)
{
  echo '<h2>' . $title . '</h2>'; ?>
  <form method="post" action="hr.php"><table><?php
}?>

<tr><td>Employé(e) :<?php

if (!$_SESSION['ds_ishrsuperuser']) { $dp_groupid = $_SESSION['ds_teamid']; }
$dp_itemname = 'employee'; $dp_noblank = 1; require('inc/selectitem.php');
  
if ($dp_isform === 1)
{?>
  <tr><td colspan=2>&nbsp;</td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
  <input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
  </table></form><?php
}
?>