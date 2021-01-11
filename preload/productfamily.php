<?php
# load $productfamilyA[$productfamilyid]
if (!isset($productfamilyA))
{
  $query = 'select productfamilyid,productfamilyname,productfamilygroupid from productfamily order by familyrank,productfamilyname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  { 
    $productfamilyid_temp = (int) ($query_result[$kladd_i]['productfamilyid']+0);
    $productfamilyA[$productfamilyid_temp] = $query_result[$kladd_i]['productfamilyname'];
    $productfamily_pfgidA[$productfamilyid_temp] = $query_result[$kladd_i]['productfamilygroupid'];
  }
}
unset ($productfamilyid_temp);
?>