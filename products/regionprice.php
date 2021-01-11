<?php

require('preload/regulationzone.php');

$product = $_POST['product'];
$productid = (int) $product;
$regulationzoneid = $_POST['regulationzoneid'];
$regionprice = $_POST['regionprice']; if ((!isset($regionprice)) || $regionprice < 0) { $regionprice = 0; }
$retailprice = $_POST['retailprice']; if ((!isset($retailprice)) || $retailprice < 0) { $retailprice = 0; }

if ($productid > 0 && ($regulationzoneid > 0 || $retailprice > 0))
{
  if ($regulationzoneid == 9999) { echo '<p class=alert>Produit ' . $productid . ' (Zone 2 : autres îles): '; }
  else { echo '<p class=alert>Produit ' . $productid . ' (' . $regulationzoneA[$regulationzoneid] . '): '; }
  if ($regionprice > 0 || $retailprice > 0)
  {
    #insert or update
    $query = 'select regionpricingid from regionpricing where productid=? and regulationzoneid=?';
    $query_prm = array($productid,$regulationzoneid);
    require('inc/doquery.php');
    if ($num_results)
    {
      $query = 'update regionpricing set deleted=0,regionprice=?,retailprice=? where productid=? and regulationzoneid=?';
    }
    else
    {
      $query = 'insert into regionpricing (regionprice,retailprice,productid,regulationzoneid) values (?,?,?,?)';
    }
    $query_prm = array($regionprice,$retailprice,$productid,$regulationzoneid);
    require('inc/doquery.php');
    echo myfix($regionprice);
  }
  else
  {
    #mark as deleted
    $query = 'update regionpricing set regionprice=0,retailprice=0,deleted=1 where productid=? and regulationzoneid=?';
    $query_prm = array($productid,$regulationzoneid);
    require('inc/doquery.php');
    echo ' Supprimé';
  }
  echo '</p><br>';
}

?>
<h2>Prix par région:</h2>
<form method="post" action="products.php"><table>
<tr><td>
<?php
require('inc/selectproduct.php');
?></td></tr>
<tr><td>Région:</td><td>
<?php
$dp_itemname = regulationzone; $dp_selectedid = $regulationzoneid; $dp_noblank = 1; $dp_ultimate = 1;
require('inc/selectitem.php');
?></td></tr>
<tr><td>Prix de vente:</td><td><input type="text" STYLE="text-align:right" name="regionprice" size=10 value="<?php echo d_input($regionprice,'decimal'); ?>"></td></tr>
<?php
if ($_SESSION['ds_useretailprice'])
{#HERE
  echo '<tr><td>Prix réglementé:</td><td><input type="text" STYLE="text-align:right" name="retailprice" size=10 value="' . d_input($retailprice,'decimal') . '"></td></tr>';
}
?>
<tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="productsmenu" value="<?php echo $productsmenu; ?>"><input type="submit" value="Valider"></td></tr>
</table></form>

<?php
?>