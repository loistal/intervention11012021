<?php

$num_slots = 25;

if (isset($_POST['saveslotname1']))
{
  for ($i=1; $i <= $num_slots; $i++)
  {
    $query = 'update save_name set save_name=?,rank=? where save_nameid=?';
    $query_prm = array($_POST['saveslotname'.$i],$_POST['rank'.$i],$i);
    require('inc/doquery.php');
  }
}

?>

<h2>Noms des enregistrements (rang)</h2>
<form method="post" action="accounting.php"><input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
<table>
<?php
$query = 'select * from save_name';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $y = $query_result[$i]['save_nameid'];
  $nameA[$y] = $query_result[$i]['save_name'];
  $rankA[$y] = $query_result[$i]['rank'];
}
for ($i=1; $i <= $num_slots; $i++)
{
  echo '<tr><td>'.$i.'. <input type=text size=40 name="saveslotname'.$i.'" value="'.$nameA[$i].'"> <input type=text size=5 name="rank'.$i.'" value="'.$rankA[$i].'">';
}
?>
</select>
<tr><td colspan="2" align="center">
<input type="submit" value="Enregistrer"></td></tr>
</table></form>