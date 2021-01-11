<?php

$STEP_FORM_ADD = 2;
$STEP_FORM_MODIFY = 3;

$title = 'Modifier employé';
echo '<h2>' . $title . '</h2>';
echo '<form method="post" action="hr.php"><table><tr><td>';
$dp_itemname = 'employee'; $dp_description = d_trad('employee'); $dp_noblank = 1; require('inc/selectitem.php');
echo '<input type=hidden name="hrmenu" value="modemployee"><input type=hidden name="step" value="' . $STEP_FORM_MODIFY . '">
<tr><td colspan=3 align=right>
<button type="submit" name="add" value="1">' . d_trad('add') . '</button>
<input type="submit" value="', d_trad('modify') ,'"></td></tr>
</table></form>';

echo '<br><br>';
require('hr/replaceemployee.php');

?>