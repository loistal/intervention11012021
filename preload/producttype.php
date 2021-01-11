<?php
# load $producttypeA[$producttypeid]
if (!isset($producttypeA))
{
  $query = 'select producttypeid,producttypename from producttype order by producttypename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $producttypeid_temp = (int) ($query_result[$kladd_i]['producttypeid']+0);
    $producttypeA[$producttypeid_temp] = $query_result[$kladd_i]['producttypename'];
  }
}
?>