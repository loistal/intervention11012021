<h2>Rapport des ajustements:</h2>
<form method="post" action="reportwindow.php" target="_blank"><table><?php
echo '<tr><td>De:</td><td>';
$datename = 'startdate'; require('inc/datepicker.php');
echo '</td></tr><tr><td>A:</td><td>';
$datename = 'stopdate'; require('inc/datepicker.php');
echo '</td></tr>';
echo '<tr><td>';
require('inc/selectproduct.php');
$dp_itemname = "user"; $dp_description = 'Utilisateur'; $dp_allowall = 1; $dp_noblank = 1; require('inc/selectitem.php');

?>
<tr><td>Famille de produit:</td>
<td><select name="productfamilygroupid"><?php
echo '<option value=-1>'. d_trad('selectall') .'</option>';
$query = 'select productfamilygroupid,productfamilygroupname,productdepartmentname from productdepartment,productfamilygroup where productfamilygroup.productdepartmentid=productdepartment.productdepartmentid order by departmentrank,familygrouprank';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  echo '<option value="' . $row['productfamilygroupid'] . '">' . $row['productdepartmentname'] . '/' . $row['productfamilygroupname'] . '</option>';
}
?></select>
<?php
if ($_SESSION['ds_stockperuser'])
{
  echo '<tr><td>Stock pour:<td><select name="foruserid"><option value=0></option>';
  $query = 'select userid,username from usertable where deleted=0 and stockperthisuser=1 order by username';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    echo '<option value='.$query_result[$i]['userid'];
    echo '>'.$query_result[$i]['username'].'</option>';
    $stockperuserA[$query_result[$i]['userid']] = $query_result[$i]['username'];
  }
  echo '</select>';
}
?>
<tr><td>Rangé par:</td><td><select name="myorder">
<option value=1>Date</option>
<option value=2>Produit</option>
<option value=3>Utilisateur</option>
</select></td></tr>
<tr><td colspan="2" align="center">
<input type=hidden name="report" value="modifiedstockreport">
<input type="submit" value="Valider"></td></tr></table></form>