<?php
# load $productfamilygroupA[$productfamilygroupid]
if (!isset($productfamilygroupA))
{
  $query = 'select productfamilygroupid,productfamilygroupname,productdepartmentid from productfamilygroup order by familygrouprank,productfamilygroupname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  { 
    $productfamilygroupid_temp = (int) ($query_result[$kladd_i]['productfamilygroupid']+0);
    $productfamilygroupA[$productfamilygroupid_temp] = $query_result[$kladd_i]['productfamilygroupname'];
    $productfamilygroup_pdidA[$productfamilygroupid_temp] = $query_result[$kladd_i]['productdepartmentid']; 
  }
}
unset ($productfamilygroupid_temp);
?>