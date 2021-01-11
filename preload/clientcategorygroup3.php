<?php
if (!isset($clientcategorygroup3A))
{
  $query = 'select clientcategorygroup3id,clientcategorygroup3name,`rank`,deleted from clientcategorygroup3 order by deleted,clientcategorygroup3name';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $clientcategorygroup3id_temp = (int) ($query_result[$kladd_i]['clientcategorygroup3id']+0);
    $clientcategorygroup3A[$clientcategorygroup3id_temp] = $query_result[$kladd_i]['clientcategorygroup3name'];
    $clientcategorygroup3_rankA[$clientcategorygroup3id_temp] = $query_result[$kladd_i]['rank'];
    $clientcategorygroup3_deletedA[$clientcategorygroup3id_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>