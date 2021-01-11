<?php
switch($currentstep)
{
  # Which price to set?
  case 0:
  ?><h2>Gratuité par mois:</h2>
  <form method="post" action="products.php"><table>
  <tr><td>Produit:</td>
  <td><select name="productid" id="myfocus"><?php
  $query = 'select productid,productname from product where generic=0 order by productname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row2 = $query_result[$i];
    echo '<option value="' . $row2['productid'] . '">' . $row2['productid'] . ': ' . $row2['productname'] . '</option>';
  }
  ?></select></td></tr>
  <tr><td>Client:</td><td><input type="text" id="myfocus" STYLE="text-align:right" name="clientid" size=5> : <input type="text" id="clientlist" STYLE="text-align:right" name="clientname" size=20></td></tr>
  <tr><td>Quantité:</td><td><input type="text" STYLE="text-align:right" name="freequantity" size=10></td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="productsmenu" value="<?php echo $productsmenu; ?>"><input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  case 1:
  $productid = $_POST['productid'];
  $clientid = $_POST['clientid']; $clientname = $_POST['clientname'];
  $query = 'select clientid,clientname from client where clientid="' . $clientid . '"';
  if ($clientid == "") { $query = 'select clientid,clientname from client where clientname="' . $clientname . '"'; }
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results == 0) { echo '<font color="' . $_SESSION['ds_alertcolor'] . '">Client inexistant.</font>'; exit; }
  $row = $query_result[0];
  $clientid = $row['clientid'];
  $clientname = $row['clientname'];
  $freequantity = (int) $_POST['freequantity'];
  $query = 'select productname from product where productid=' . $productid;
  $query_prm = array();
  require('inc/doquery.php');
  $row = $query_result[0];
  $productname = $row['productname'];
  $query = 'select freequantity from freemonthly where productid=' . $productid . ' and clientid=' . $clientid;
  $query_prm = array();
  require('inc/doquery.php');
  $modify = 0; if ($num_results) { $modify = 1; $row = $query_result[0]; $oldfreequantity = $row['freequantity']; }

  if ($modify) { $query = 'update freemonthly set deleted=0,freequantity="' . $freequantity . '" where productid="' . $productid . '" and clientid="' . $clientid . '"'; }
  else { $query = 'insert into freemonthly (productid,clientid,freequantity) values ("' . $productid . '","' . $clientid . '","' . $freequantity . '")'; }
  $delete = 0; if ($freequantity == 0 || $freequantity == "") { $delete = 1; }
  if ($delete) { $query = 'update freemonthly set deleted=1 where productid="' . $productid . '" and clientid="' . $clientid . '"'; }
  $query_prm = array();
  require('inc/doquery.php');

  echo '<h2>Gratuité par mois établie:</h2>';
  echo '<table><tr><td>Produit:</td><td>' . $productname . '</td></tr>';
  echo '<tr><td>Client:</td><td>' . $clientname . '</td></tr>';
  echo '<tr><td>Quantité:</td><td>' . $freequantity;
  if ($modify && !$delete) { echo ' (modifié de ' . $oldfreequantity . ')'; }
  if ($delete) { echo ' supprimé'; }
  echo '</td></tr></table>';
  break;

}
?>