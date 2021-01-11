<?php

echo '<h2>Marge par utilisateur</h2>';
?>
<form method="post" action="reportwindow.php" target="_blank"><table><?php
echo '<tr><td>Début:</td><td>';
$datename = 'startdate';
require ('inc/datepicker.php');
echo '</td></tr>';
echo '<tr><td>Fin:</td><td>';
$datename = 'stopdate';
require ('inc/datepicker.php');
echo '</td></tr>';
?>
<tr><td>Utilisateur:</td>
<td><select name="userid"><?php

$query = 'select userid,name from usertable where deleted=0 order by name';
$query_prm = array();
require('inc/doquery.php');
echo '<option value="-1">Tous</option>';
for ($i=0; $i < $num_results; $i++)
{
  $row2 = $query_result[$i];
  echo '<option value="' . $row2['userid'] . '">' . $row2['name'] . '</option>';
}
?></select></td></tr>
<tr><td colspan="2" align="center"><input type="submit" value="Valider"></td></tr>
<input type=hidden name="report" value="usermargin">
</table></form>