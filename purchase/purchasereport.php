<?php
echo '<h2>' . d_trad('localpurchase:') . '</h2>';
echo '<form method="post" action="reportwindow.php" target="_blank"><table>';
echo '<td>' . d_trad('startdate:') . '</td>';
echo '<td>';
$datename = 'startdate'; require('inc/datepicker.php');
echo '</td></tr>';
echo '<tr><td>' . d_trad('stopdate:') . '</td>';
echo '<td>';
$datename = 'stopdate'; require('inc/datepicker.php');
echo '</td></tr>';
echo '<tr><td>' . d_trad('user:') . '</td>';
echo '<td><select name="userid">';
$query = 'select userid,name from usertable where purchaseaccess=1 order by name';
$query_prm = array();
require('inc/doquery.php');
echo '<option value="-1"> </option>';
for ($i=0; $i < $num_results; $i++)
{
  $row2 = $query_result[$i];
  echo '<option value="' . $row2['userid'] . '">' . $row2['name'] . '</option>';
}
echo '</select></td></tr>';
$dp_itemname='productdepartment';$dp_description = d_trad('department'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
$dp_description = d_trad('family'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamilygroup.php');
$dp_description = d_trad('subfamily'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamily.php');
echo '<tr><td>';
require("inc/selectproduct.php");
echo '</td></tr>';
echo '<tr><td>' . d_trad('brand:') . '</td><td><input type=text STYLE="text-align:right" name=brand size=20></td></tr>';
if ($_SESSION['ds_useemplacement']) 
{ 
  echo '<tr><td>' . d_trad('warehouse:') . '</td>';
  echo '<td><select name="warehouseid">';
  $query = 'select warehouseid,warehousename from warehouse order by warehousename';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<option value="-1"> </option>';
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<option value="' . $row['warehouseid'] . '">' . $row['warehousename'] . '</option>';
  }
  echo '</select></td></tr>';
}

echo '<tr><td>' . d_trad('orderby:') . '</td><td><select name="orderby">';
echo '<option value=1>' . d_trad('arrivaldate') . '</option>';
if ($_SESSION['ds_useproductcode']){echo '<option value=3>' . d_trad('productcode') . '</option>'; }
else { echo '<option value=2>' . d_trad('productnumber') . '</option>'; }
echo '<option value=4>' . d_trad('productfamily') . '</option>';
echo '<option value=5>' . d_trad('brand') . '</option>';
?>
</select></td></tr>
<tr><td colspan="2" align="center"><input type=hidden name="step" value="1">
<input type=hidden name="report" value="purchasereport">
<input type="submit" value="<?php echo d_trad('validate');?>"></td></tr></table></form>