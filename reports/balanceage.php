<?php
require('preload/employee.php');
require('preload/island.php');
require('preload/town.php');
?>

<h2>Balance Âgée:</h2>
<form method="post" action="reportwindow.php" target="_blank"><table>
<tr><td>A la date du:</td><td><?php
$datename = 'ourdate'; $selecteddate = $_SESSION['ds_curdate'];
require('inc/datepicker.php');
?><tr><td>Date :<td><select name="datetype">
<option value=0>Payable</option>
<option value=1>Comptable</option>
</select>
<tr><td>Catégorie client:
<td><select name="clientcategoryid"><?php
$query = 'select clientcategoryid,clientcategoryname from clientcategory order by clientcategoryname';
$query_prm = array();
require('inc/doquery.php');
echo '<option value="0">'.d_trad('selectall').'</option>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  echo '<option value="' . $row['clientcategoryid'] . '">' . $row['clientcategoryname'] . '</option>';
}
?></select></td></tr>
<tr><td>Catégorie client 2:</td>
<td><select name="clientcategory2id"><?php
$query = 'select clientcategory2id,clientcategory2name from clientcategory2 order by clientcategory2name';
$query_prm = array();
require('inc/doquery.php');
echo '<option value="0">'.d_trad('selectall').'</option>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  echo '<option value="' . $row['clientcategory2id'] . '">' . $row['clientcategory2name'] . '</option>';
}
?></select></td></tr>

<?php
$dp_itemname = 'clientterm'; $dp_allowall=1; $dp_noblank=1; $dp_description='Délai de paiement'; $dp_selectedid=-1;
require('inc/selectitem.php');

$dp_itemname = 'employee'; $dp_allowall=1; $dp_selectedid=-1; $dp_description= $_SESSION['ds_term_clientemployee1'];
require('inc/selectitem.php');

$dp_itemname = 'employee'; $dp_allowall=1; $dp_selectedid=-1; $dp_description= $_SESSION['ds_term_clientemployee2']; $dp_addtoid = 2;
require('inc/selectitem.php');

?>
<tr><td>Île:
<td><?php
if (isset($islandA))
{
  echo '<select name="islandid">';
  echo '<option value=-1>'. d_trad('selectall') .'</option>';
  foreach ($islandA as $islandidS => $islandname)
  {
    echo '<option value="' . $islandidS . '">' . $islandname . '</option>';
  }
  echo '</select>';
}
?>
<tr><td>Ville:
<td><?php
if (isset($townA))
{
  echo '<select name="townid">';
  echo '<option value=-1>'. d_trad('selectall') .'</option>';
  foreach ($townA as $townidS => $townname)
  {
    $islandid = $town_islandidA[$townidS];
    echo '<option value="' . $townidS . '">' . $islandA[$islandid] . ' - ' . $townname . '</option>';
  }
  echo '</select>';
}
?>
<tr><td>Ranger par:
<td><select name="islandsort">
<option value=0>Employé</option>
<option value=1>Île</option>
<option value=2 selected>Client</option>
</select>
<tr><td align=right><input type=checkbox name="months24" value=1><td>Format 24 mois
<tr><td align=right><input type=checkbox name="months24" value=2><td>Format 48 mois
<tr><td colspan="2" align="center">
<input type=hidden name="report" value="balanceage">
<input type="submit" value="Valider"></table></form>
