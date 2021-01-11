<?php
if (!isset($select_itemcommentA))
{
  $query = 'select select_itemcommentid,select_itemcommentname,deleted from select_itemcomment order by deleted,select_itemcommentname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['select_itemcommentid']+0);
    $select_itemcommentA[$id_temp] = $query_result[$kladd_i]['select_itemcommentname'];
    $select_itemcomment_deletedA[$id_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>