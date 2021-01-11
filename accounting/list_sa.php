<?php

$query = 'select accounting_simplifiedid,accounting_simplifiedname,accounting_simplifiedgroupname,accounting_simplified.deleted from accounting_simplified,accounting_simplifiedgroup
where accounting_simplified.accounting_simplifiedgroupid=accounting_simplifiedgroup.accounting_simplifiedgroupid';
$query = $query . ' order by accounting_simplified.deleted,rank,accounting_simplifiedname';
$query_prm = array();
require ('inc/doquery.php');
?><h2>Liste des menus simplifiés</h2>
<table class=report><thead><th>Groupe</th><th>Menu</th><th>Supprimé</th></thead><?php
$main_result = $query_result; $num_results_main = $num_results; unset($query_result, $num_results);
for ($i=0; $i < $num_results_main; $i++)
{
  $row = $main_result[$i];
  echo d_tr();
  $link = '##accounting.php?accountingmenu=add_sa&readme=1&a_sid='.$row['accounting_simplifiedid'];
  echo d_td_old($row['accounting_simplifiedgroupname']);
  echo d_td_old($row['accounting_simplifiedname'],0,0,0,$link);
  $kladd = ''; if ($row['deleted']) { $kladd = '&radic;'; }
  echo d_td_old($kladd,4);
}
?></table>