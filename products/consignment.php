<?php

require('inc/findclient.php');

$consignmentA = array();

echo '<h2>Dépôt-vente';
if ($clientid > 0) { echo ' client ',$clientname,' [',$clientid,']'; }
echo '</h2><br>';

$query = 'select invoiceitemhistory.productid,sum(quantity) as amount,productname
from invoicehistory,invoiceitemhistory,product
where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and invoiceitemhistory.productid=product.productid
and isnotice=1 and cancelledid=0 and isreturn=0';
$query_prm = array();
if ($clientid > 0) { $query .= ' and clientid=?'; array_push($query_prm, $clientid); }
$query .= ' group by productid
order by productname';
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $consignmentA[$query_result[$i]['productid']] += $query_result[$i]['amount'];
}
$query = 'select invoiceitemhistory.productid,sum(quantity) as amount,productname
from invoicehistory,invoiceitemhistory,product
where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and invoiceitemhistory.productid=product.productid
and isnotice=1 and cancelledid=0 and isreturn=1';
$query_prm = array();
if ($clientid > 0) { $query .= ' and clientid=?'; array_push($query_prm, $clientid); }
$query .= ' group by productid
order by productname';
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $consignmentA[$query_result[$i]['productid']] -= $query_result[$i]['amount'];
}

echo d_table('report');
echo '<thead><th>Produit<th>En dépôt</thead>';
if (!empty($consignmentA))
{
  require('preload/product.php');
  foreach ($consignmentA as $pid => $amount)
  {
    if ($amount != 0)
    {
      echo d_tr();
      echo d_td($productA[$pid].' ['.$pid.']');
      echo d_td($amount,'int');
    }
  }
}
echo d_table_end();

echo '<br><br><form method="post" action="products.php"><table><tr><td>';
require('inc/selectclient.php');
echo '<tr><td colspan=2 align="center"><input type=hidden name="productsmenu" value="' . $productsmenu . '"><input type="submit" value="Valider"></td></tr>
</table></form>';

?>