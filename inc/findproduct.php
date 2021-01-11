<?php

# input: $product
# output:
unset($productid); unset($productname); unset($productbarcode); unset($productprice); unset($productcode); unset($productvatrate); unset($product_npu);
$num_products = 0;

if (!isset($product)) { $product = ''; }
if ($product != "")
{
  $keeplooking = 1;

  if ($keeplooking == 1)
  {
    $query = 'select productid,eancode,productname,numberperunit,netweightlabel,salesprice,suppliercode,taxcode
    from product,taxcode where product.taxcodeid=taxcode.taxcodeid and ';
    if ($_SESSION['ds_useproductcode'] == 1) { $query = $query . 'suppliercode=?'; }
    else { $query = $query . 'productid=?'; }
    $query_prm = array($product);
    require ('inc/doquery.php');
    if ($num_results == 1)
    {
      $productid = $query_result[0]['productid'];
      $productbarcode = $query_result[0]['eancode'];
      $productname = d_decode($query_result[0]['productname']) . ' ';
      if ($_SESSION['ds_useunits'] && $query_result[0]['numberperunit'] > 1) { $productname = $productname . $query_result[0]['numberperunit'] . ' x '; }
      $productname = trim($productname . $query_result[0]['netweightlabel']);
      $productprice = $query_result[0]['salesprice'];
      $productcode = $query_result[0]['suppliercode'];
      $productvatrate= $query_result[0]['taxcode'];
      $product_npu = $query_result[0]['numberperunit'];
    }
    if ($num_results > 0) { $keeplooking = 0; $num_products = $num_results; }
  }

  if ($keeplooking == 1)
  {
    $query = 'select productid,eancode,productname,numberperunit,netweightlabel,salesprice,suppliercode,taxcode
    from product,taxcode
    where product.taxcodeid=taxcode.taxcodeid
    and (eancode=? or eancode2=? or lower(productname) LIKE ? or lower(suppliercode) LIKE ? or lower(suppliercode2) LIKE ?)
    order by productname limit 1';
    $query_prm = array($product,$product,'%' .  mb_strtolower(d_encode($product)) . '%','%' .  mb_strtolower($product) . '%','%' .  mb_strtolower($product) . '%');
    require ('inc/doquery.php');
    if ($num_results)
    {
      $productid = $query_result[0]['productid'];
      $productbarcode = $query_result[0]['eancode'];
      $productname = d_decode($query_result[0]['productname']) . ' ';
      if ($_SESSION['ds_useunits'] && $query_result[0]['numberperunit'] > 1) { $productname = $productname . $query_result[0]['numberperunit'] . ' x '; }
      $productname = trim($productname . $query_result[0]['netweightlabel']);
      $productprice = $query_result[0]['salesprice'];
      $productcode = $query_result[0]['suppliercode'];
      $productvatrate= $query_result[0]['taxcode'];
      $product_npu = $query_result[0]['numberperunit'];
    }
    if ($num_results > 0) { $keeplooking = 0; $num_products = $num_results; }
  }

}

?>