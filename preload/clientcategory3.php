<?php
if (!isset($clientcategory3A))
{
  $query = 'select clientcategory3id,clientcategory3name,clientcategorygroup3id,deleted from clientcategory3 order by deleted,clientcategory3name';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $clientcategory3id_temp = (int) ($query_result[$kladd_i]['clientcategory3id']+0);
    $clientcategory3A[$clientcategory3id_temp] = $query_result[$kladd_i]['clientcategory3name'];
    $clientcategory3_groupidA[$clientcategory3id_temp] = $query_result[$kladd_i]['clientcategorygroup3id'];
    $clientcategory3_deletedA[$clientcategory3id_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>