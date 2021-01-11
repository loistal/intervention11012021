<?php

$product = $_POST['product'];
require('inc/findproduct.php');
if ($productid > 0 && $_POST['listpricingcatid'] > 0)
{
  $query = 'select listpricingcatname from listpricingcat where listpricingcatid=?';
  $query_prm = array($_POST['listpricingcatid']);
  require ('inc/doquery.php');
  $listpricingcatname = $query_result[0]['listpricingcatname'];
  if ($_POST['price'] > 0)
  {
    $query = 'select listpricingid from listpricing where productid=? and listpricingcatid=?';
    $query_prm = array($productid,$_POST['listpricingcatid']);
    require ('inc/doquery.php');
    $listpricingid = $query_result[0]['listpricingid'];
    if ($listpricingid > 0)
    {
      $query = 'update listpricing set price=? where listpricingid=?';
      $query_prm = array($_POST['price'],$listpricingid);
      require ('inc/doquery.php');
    }
    else
    {
      $query = 'insert into listpricing (productid,listpricingcatid,price) values (?,?,?)';
      $query_prm = array($productid,$_POST['listpricingcatid'],$_POST['price']);
      require ('inc/doquery.php');
    }
    echo '<p>Prix par liste "' . $productid . ': ' . $productname . '" / "' . $listpricingcatname . '": ' . d_output($_POST['price']) . '.</p><br>';
  }
  if ($_POST['price'] == "")
  {
    $query = 'update listpricing set price=0 where productid=? and listpricingcatid=?';
    $query_prm = array($productid,$_POST['listpricingcatid']);
    require ('inc/doquery.php');
    echo '<p>Prix par liste "' . $productid . ': ' . $productname . '" / "' . $listpricingcatname . '" supprimé.</p><br>';
  }
}

if ($_SESSION['ds_useproductcode']) { $productid = $productcode; }
echo '<h2>Prix par liste:</h2><form method="post" action="products.php"><table>';
echo '<tr><td>Produit:</td><td><input autofocus type="text" STYLE="text-align:right" name="product" size=10 value="' . $productid . '"></td></tr>';

$query = 'select listpricingcatid,listpricingcatname from listpricingcat where deleted=0 order by listpricingcatname';
$query_prm = array();
require('inc/doquery.php');
if ($num_results > 0)
{
  echo '<tr><td>Liste:</td><td><select name="listpricingcatid">';
  for ($i=0; $i < $num_results; $i++)
  {
    $row2 = $query_result[$i];
    echo '<option value="' . $row2['listpricingcatid'] . '"';
    if ($row2['listpricingcatid'] == $_POST['listpricingcatid']) { echo ' selected'; }
    echo '>' . $row2['listpricingcatname'] . '</option>';
  }
  echo '</select></td></tr>';
  echo '<tr><td>Prix de vente:</td><td><input type="text" STYLE="text-align:right" name="price" size=10 value="' . $_POST['price'] . '"></td></tr>';
  echo '<tr><td colspan="2" align="center"><input type=hidden name="productsmenu" value="' . $productsmenu . '"><input type="submit" value="Valider"></td></tr>';
}
else { echo '<tr><td colspan="2" align="center"><span class="alert"><a href="products.php?productsmenu=calcprice">Veuillez définir une liste.</a></span></td></tr>'; }
echo '</table></form>';
#    echo '<p>Les prix par liste ne fonctionnent que pour les produits <i>generiques</i>.</p>';

?>