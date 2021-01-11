<?php
if (!isset($listpricingcatA))
{
  $query = 'select listpricingcatid,listpricingcatname,deleted from listpricingcat order by deleted,listpricingcatname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $temp_id = (int) ($query_result[$kladd_i]['listpricingcatid']+0);
    $listpricingcatA[$temp_id] = $query_result[$kladd_i]['listpricingcatname'];
    $listpricingcat_deletedA[$temp_id] = $query_result[$kladd_i]['deleted'];
  }
}
?>