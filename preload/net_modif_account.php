<?php
if (!isset($net_modif_accountA))
{
  $query = 'select net_modif_accountid,net_modif_accountname,deleted,accountingnumberid from net_modif_account order by deleted,net_modif_accountname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['net_modif_accountid']+0);
    $net_modif_accountA[$id_temp] = $query_result[$kladd_i]['net_modif_accountname'];
    $net_modif_account_deletedA[$id_temp] = $query_result[$kladd_i]['deleted'];
    $net_modif_account_anidA[$id_temp] = $query_result[$kladd_i]['accountingnumberid'];
  }
}
?>