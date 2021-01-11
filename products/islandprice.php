<?php

# TODO refactor

require('preload/island.php');

$ds_useretailprice = $_SESSION['ds_useretailprice'] =1;
$product = $_POST['product'];
require('inc/findproduct.php');
$islandid = $_POST['islandid']+0;
$islandprice = $_POST['islandprice']; if (!isset($islandprice) || $islandprice < 0) { $islandprice = 0; }
$retailprice = $_POST['retailprice']; if (!isset($retailprice) || $retailprice < 0) { $retailprice = 0; }

echo '<h2>' .d_trad('pricebyisland:') . '</h2>';

if (($productid > 0) && ($islandid > 0 || $retailprice > 0))
{
  if ($_SESSION['ds_useproductcode']) { $showproductid = $productcode; }
  else { $showproductid = $productid; }
	echo '<p>' . d_trad('product(island): ',array($showproductid,$islandA[$islandid]));

  if ($islandprice > 0 || $retailprice > 0)
  {
    #insert or update
    $query = 'select islandpricingid from islandpricing where productid=? and islandid=?';
    $query_prm = array($productid,$islandid);
    require('inc/doquery.php');
    if ($num_results)
    {
      $query = 'update islandpricing set deleted=0,islandprice=?,retailprice=? where productid=? and islandid=?';
    }
    else
    {
      $query = 'insert into islandpricing (islandprice,retailprice,productid,islandid) values (?,?,?,?)';
    }
    $query_prm = array($islandprice,$retailprice,$productid,$islandid);
    require('inc/doquery.php');
    echo myfix($islandprice);
  }
  else
  {
    #mark as deleted
    $query = 'update islandpricing set islandprice=0,retailprice=0,deleted=1 where productid=? and islandid=?';
    $query_prm = array($productid,$islandid);
    require('inc/doquery.php');
    echo d_trad('deleted');
  }
  echo '</p><br>';
}
?>

<form method="post" action="products.php"><table>
<tr><td>
<?php
require('inc/selectproduct.php');
?></td></tr>
<?php
$dp_description = d_trad('island');$dp_itemname = 'island'; $dp_selectedid = $islandid;$dp_noblank=1;require('inc/selectitem.php');
?>
<tr><td><?php echo d_trad('saleprice:');?></td><td><input type="text" STYLE="text-align:right" name="islandprice" size=10 value="<?php echo d_input($islandprice,'decimal'); ?>"></td></tr>
<?php
if ($ds_useretailprice)
{
  echo '<tr><td>' . d_trad('retailprice:') . '</td><td><input type="text" STYLE="text-align:right" name="retailprice" size=10 value="' . d_input($retailprice,'decimal') . '"></td></tr>';
}
?>
<tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="productsmenu" value="<?php echo $productsmenu; ?>"><input type="submit" value="Valider"></td></tr>
</table></form>

<?php
?>