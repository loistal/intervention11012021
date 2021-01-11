<?php

echo '<h2>Valeur du Stock sur date précise</h2>';

echo '<form method="post" action="reportwindow.php" target=_blank><table>';

echo '<tr><td>Date :<td>';
$datename = 'stockdate';
require('inc/datepicker.php');

echo '<tr><td>'; require('inc/selectproduct.php');
$dp_itemname='productdepartment';$dp_description = d_trad('department'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
$dp_description = d_trad('family'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamilygroup.php');
$dp_description = d_trad('subfamily'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamily.php');

?><tr><td><?php $dp_description = d_trad('supplier').':'; $dp_supplier = 1; require('inc/selectclient.php');?> &nbsp;<?php echo d_trad('exclude');?><input type="checkbox" name="excludesupplier" value="1">

<?php

if ($_SESSION['ds_stockperuser'])
{
  echo '<tr><td>Stock pour:<td><select name="userid"><option value=0></option>';
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

echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="productvalue"><input type="submit" value="' . d_trad('validate') .'"></td></tr></table></form>';

?>