<?php
if (!isset($clientcategory2A))
{
  $query = 'select clientcategory2id,clientcategory2name,clientcategorygroup2id,deleted from clientcategory2 order by deleted,clientcategory2name';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $clientcategory2id_temp = (int) ($query_result[$kladd_i]['clientcategory2id']+0);
    $clientcategory2A[$clientcategory2id_temp] = $query_result[$kladd_i]['clientcategory2name'];
    $clientcategory2_groupidA[$clientcategory2id_temp] = $query_result[$kladd_i]['clientcategorygroup2id'];
    $clientcategory2_deletedA[$clientcategory2id_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>