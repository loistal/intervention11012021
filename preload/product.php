<?php
if (!isset($productA))
{
  $query = 'select productid,productname,suppliercode,unittypeid,brand,netweightlabel,numberperunit from product order by productname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $productid_temp = (int) ($query_result[$kladd_i]['productid']+0);
    if ($_SESSION['ds_useproductcode']) { $pid_temp = $query_result[$kladd_i]['suppliercode']; }
    else { $pid_temp = $query_result[$kladd_i]['productid']; }
    $productA[$productid_temp] = "";
    if($pid_temp != "")
    {
      $productA[$productid_temp] .= $pid_temp . ': ';
    }
    $productA[$productid_temp] .= d_decode($query_result[$kladd_i]['productname']);
    $product_unittypeidA[$productid_temp] = $query_result[$kladd_i]['unittypeid'];
    $product_brandA[$productid_temp] = $query_result[$kladd_i]['brand'];
    $product_packagingA[$productid_temp] = $query_result[$kladd_i]['netweightlabel'];
    $product_npuA[$productid_temp] = $query_result[$kladd_i]['numberperunit'];
    if ($query_result[$kladd_i]['numberperunit'] > 1) { $product_packagingA[$productid_temp] = $query_result[$kladd_i]['numberperunit'] . ' x ' . $product_packagingA[$productid_temp]; }
  }
}
?>