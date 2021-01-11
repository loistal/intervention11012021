<?php
# load $clientcategorygroupA[$clientcategorygroupid]
if (!isset($clientcategorygroupA))
{
  $query = 'select clientcategorygroupid,clientcategorygroupname,`rank`,deleted from clientcategorygroup order by deleted,clientcategorygroupname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $clientcategorygroupid_temp = (int) ($query_result[$kladd_i]['clientcategorygroupid']+0);
    $clientcategorygroupA[$clientcategorygroupid_temp] = $query_result[$kladd_i]['clientcategorygroupname'];
    $clientcategorygroup_rankA[$clientcategorygroupid_temp] = $query_result[$kladd_i]['rank'];
    $clientcategorygroup_deletedA[$clientcategorygroupid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>