<?php
# load $clientcategoryA[$clientcategoryid]
if (!isset($clientcategoryA))
{
  $query = 'select clientcategoryid,clientcategoryname,clientcategorygroupid,deleted from clientcategory order by deleted,clientcategoryname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $clientcategoryid_temp = (int) ($query_result[$kladd_i]['clientcategoryid']+0);
    $clientcategoryA[$clientcategoryid_temp] = $query_result[$kladd_i]['clientcategoryname'];
    $clientcategory_groupidA[$clientcategoryid_temp] = $query_result[$kladd_i]['clientcategorygroupid'];		
    $clientcategory_deletedA[$clientcategoryid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>