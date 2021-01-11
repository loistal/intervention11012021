<?php
# load $clientcategorygroup2A[$clientcategorygroup2id]
if (!isset($clientcategorygroup2A))
{
  $query = 'select clientcategorygroup2id,clientcategorygroup2name,`rank`,deleted from clientcategorygroup2 order by deleted,clientcategorygroup2name';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $clientcategorygroup2id_temp = (int) ($query_result[$kladd_i]['clientcategorygroup2id']+0);
    $clientcategorygroup2A[$clientcategorygroup2id_temp] = $query_result[$kladd_i]['clientcategorygroup2name'];
    $clientcategorygroup2_rankA[$clientcategorygroup2id_temp] = $query_result[$kladd_i]['rank'];
    $clientcategorygroup2_deletedA[$clientcategorygroup2id_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>