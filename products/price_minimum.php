<?php

$PA['product'] = '';
$PA['min_invoiceprice'] = 'udecimal';
$PA['deleted'] = 'uint';
require('inc/readpost.php');
require('inc/findproduct.php');

if ($productid > 0)
{
  $query = 'update product set min_invoiceprice=? where productid=?';
  $query_prm = array($min_invoiceprice, $productid);
  require('inc/doquery.php');
  if ($num_results)
  {
    echo '<p>Prix minimum pour produit '.$productid.' modifié à '.$min_invoiceprice.' XPF.</p><br>';
  }
}

echo '<h2>Prix minimum par facture:</h2>';
echo '<form method="post" action="products.php"><table><tr><td>';
require('inc/selectproduct.php');
echo '<tr><td>Prix minimum:</td><td><input type="text" STYLE="text-align:right" name="min_invoiceprice" value="',d_input($min_invoiceprice,'decimal'),'" size=10></td></tr>';
echo '<tr><td colspan="2" align="center">
<input type=hidden name="productsmenu" value="' . $productsmenu . '">
<input type="submit" value="Valider"></td></tr>
</table></form>';

echo '<br><br><table class="report"><thead><th colspan=2>Produit<th>Prix minimum par facture</thead>';
$query = 'select suppliercode,productid,min_invoiceprice from product where notforsale=0 and min_invoiceprice>0';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  echo d_tr();
  echo d_td($query_result[$i]['productid']);
  echo d_td($query_result[$i]['suppliercode']);
  echo d_td($query_result[$i]['min_invoiceprice'], 'currency');
}
echo '<table>';

?>