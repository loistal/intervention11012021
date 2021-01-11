<?php

$product = $_POST['product'];
if (isset($_POST['calcpricingid']))
{
  $calcpricingid = (int) $_POST['calcpricingid'];
  $product = (int) $_POST['productid'];
  $algorithm = (int) $_POST['algorithm'];
  if ($calcpricingid > 0) { $query = 'update calcpricing set algorithm=? where productid=?'; }
  else { $query = 'insert into calcpricing (algorithm,productid) values (?,?)'; }
  $query_prm = array($algorithm, $product);
  require('inc/doquery.php');
}
require('inc/findproduct.php');
if ($_SESSION['ds_useproductcode']) { $showproductid = $productcode; }
else { $showproductid = $productid; }

if ($productid > 0)
{
  require('preload/productfamily.php');
  $query = 'select calcpricingid,algorithm from calcpricing where productid=?';
  $query_prm = array($productid);
  require('inc/doquery.php');
  $algorithm = $query_result[0]['algorithm'];
  $calcpricingid = $query_result[0]['calcpricingid'];
  echo '<h2>Prix par calcul</h2>
  <form method="post" action="products.php"><table>
  <tr><td>' . d_output($productname) . ' (' . $showproductid . ')</td></tr>
  <tr><td><select name="algorithm">
  <option value=0>&lt;Aucun&gt</option>
  <option value=1'; if ($algorithm == 1) { echo ' selected'; }; echo '>Frêt GC MG Interisles</option>
  <option value=2'; if ($algorithm == 2) { echo ' selected'; }; echo '>Assurance Interisles</option>
  <option value=3'; if ($algorithm == 3) { echo ' selected'; }; echo '>Frêt Frigo Interisles</option>
  <option value=4'; if ($algorithm == 4) { echo ' selected'; }; echo '>Frêt Terrestre</option>
  <option value=5'; if ($algorithm == 5) { echo ' selected'; }; echo '>2% sur '.$productfamilyA[1].'</option>
  <option value=6'; if ($algorithm == 6) { echo ' selected'; }; echo '>10% sur '.$productfamilyA[2].'</option>
  <option value=7'; if ($algorithm == 7) { echo ' selected'; }; echo '>20% sur '.$productfamilyA[3].'</option>
  <option value=8'; if ($algorithm == 8) { echo ' selected'; }; echo '>1,5% sur '.$productfamilyA[1].'</option>
  <option value=9'; if ($algorithm == 9) { echo ' selected'; }; echo '>5% sur '.$productfamilyA[1].'</option>
  <option value=10'; if ($algorithm == 10) { echo ' selected'; }; echo '>10% sur '.$productfamilyA[3].'</option>
  </select>
  <tr><td colspan="2" align="center">
  <input type=hidden name="calcpricingid" value="' . $calcpricingid . '">
  <input type=hidden name="productid" value="' . $productid . '">
  <input type=hidden name="productsmenu" value="' . $productsmenu . '">
  <input type="submit" value="Valider"></td></tr>
  </table></form>';
}
else
{
  echo '<h2>Prix par calcul</h2>
  <form method="post" action="products.php"><table>
  <tr><td>';
  require('inc/selectproduct.php');
  echo '</td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="productsmenu" value="' . $productsmenu . '"><input type="submit" value="Valider"></td></tr>
  </table></form>';
}

?>