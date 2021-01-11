<?php
# load $clienttermA[$clienttermid]
if (!isset($clienttermA))
{
  $query = 'select clienttermid,clienttermname,daystopay from clientterm order by clienttermname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $clienttermid_temp = (int) ($query_result[$kladd_i]['clienttermid']+0);
    $clienttermA[$clienttermid_temp] = $query_result[$kladd_i]['clienttermname'];
    $clientterm_daystopayA[$clienttermid_temp] = $query_result[$kladd_i]['daystopay'];
  }
}
?>