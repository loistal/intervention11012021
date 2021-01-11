<?php
if (!isset($adjustmentgroup_tagA))
{
  $query = 'select adjustmentgroup_tagid,adjustmentgroup_tagname,deleted from adjustmentgroup_tag order by adjustmentgroup_tagname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($temp_i=0;$temp_i<$num_results;$temp_i++)
  {
    $temp_id = (int) $query_result[$temp_i]['adjustmentgroup_tagid'];
    $adjustmentgroup_tagA[$temp_id] = $query_result[$temp_i]['adjustmentgroup_tagname'];
    $adjustmentgroup_tag_deletedA[$temp_id] = $query_result[$temp_i]['deleted'];
  }
}
?>