<?php
echo '<h2>' . d_trad('endofyearstock2:') . '</h2>';
$currentyear = mb_substr($_SESSION['ds_curdate'],0,4);

echo '<form method="post" action="reportwindow.php" target="_blank">';
echo '<table>';
echo '<tr><td>' . d_trad('year:') . '&nbsp</td>';
echo '<td><select name="currentyear">';
#if ($_SESSION['ds_customname'] == 'Wing Chong') { echo '<option value=2015>2015</option>'; }
for ($i=0;$i<3;$i++)
{
  echo '<option value="'. $currentyear . '">'. $currentyear . '</option>';
  $currentyear--;
}
echo '</select></td></tr>';
echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="endyearstock"><input type="submit" value="' . d_trad('validate') . '"></td></tr>';
echo '</table></form>';
?>