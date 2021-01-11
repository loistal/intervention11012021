<?php

?><h2>Afficher planning:</h2>
<form method="post" action="reportwindow.php" target=_blank><table>

<?php

echo '<tr><td>Date:</td><td>';
$datename = 'scheduledate';
require('inc/datepicker.php');
echo '</td></tr>';

$dp_itemname = 'employee'; $dp_description = 'Employé'; $dp_allowall = 1;
require('inc/selectitem.php');

echo '<tr><td>&nbsp;</td><td>';
echo '<select name="employeelink"><option value=1>' . $_SESSION['ds_term_clientemployee1'] . '</option><option value=2>' . $_SESSION['ds_term_clientemployee2'] . '</option></select>';
echo '</td></tr>';

$dp_itemname = 'clientschedulecat'; $dp_description = 'Catégorie'; $dp_allowall = 1;
require('inc/selectitem.php');
?>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="showplanning"><input type="submit" value="Valider"></td></tr>
</table></form><?php

?>