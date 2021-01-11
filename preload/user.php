<?php
# load $userA[$userid]
if (!isset($userA))
{
  $query = 'select userid,name,initials,deleted from usertable order by deleted,name';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $userid_temp = (int) ($query_result[$kladd_i]['userid']+0);
    $userA[$userid_temp] = $query_result[$kladd_i]['name'];
    $user_initialsA[$userid_temp] = $query_result[$kladd_i]['initials'];
    $user_deletedA[$userid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>