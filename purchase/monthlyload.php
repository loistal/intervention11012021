<h2>Chargements mensuel par navire:</h2>
<form method="post" action="reportwindow.php" target="reportwindow"><table><?php
$query = 'select vesselname,vesselid from vessel order by vesselname';
$query_prm = array();
require('inc/doquery.php');
echo '<tr><td>Bateau:</td><td><select name="vesselid">';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  echo '<option value="' . $row['vesselid'] . '">' . $row['vesselname'] . '</option>';
}
?></select></td></tr>
<tr><td>Année:</td><td><select name="year"><?php
for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
{
  if ($i == $_SESSION['ds_year']) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
  else { echo '<option value="' . $i . '">' . $i . '</option>'; }
}
?></select></td></tr>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="monthlyload"><input type="submit" value="Valider"></td></tr>
</table></form>