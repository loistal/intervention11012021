<?php

$num_slots = 25;
$load_db = 'solcag_demo';

$saveslot = (int) $_POST['saveslot'];

if (!$saveslot)
{
  ?>
  <h2>Comparer menus simplifies</h2>
  <form method="post" action="accounting.php"><input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
  <table>
  <?php
  echo '<select name="saveslot" size='.$num_slots.'>';
  $query = 'select * from '.$load_db.'.save_name order by rank,save_name';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    echo '<option value='.$query_result[$i]['save_nameid'].'>'.$query_result[$i]['save_name'].'</option>';
  }
  ?>
  </select>
  <tr><td colspan="2" align="center">
  <input type="submit" value="Comparer"></td></tr>
  </table></form>
  <?php
}
else
{
  $query = 'select accounting_simplifiedid,accounting_simplifiedname,accounting_simplifiedgroupname,accounting_simplified.deleted from accounting_simplified,accounting_simplifiedgroup
  where accounting_simplified.accounting_simplifiedgroupid=accounting_simplifiedgroup.accounting_simplifiedgroupid';
  $query = $query . ' order by accounting_simplified.deleted,rank,accounting_simplifiedname';
  $query_prm = array();
  require ('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results; unset($query_result, $num_results);
  
  $query = 'select accounting_simplifiedid,accounting_simplifiedname,accounting_simplifiedgroupname,accounting_simplified.deleted from '.$load_db.'.accounting_simplified,'.$load_db.'.accounting_simplifiedgroup
  where accounting_simplified.accounting_simplifiedgroupid=accounting_simplifiedgroup.accounting_simplifiedgroupid';
  $query = $query . ' order by accounting_simplified.deleted,rank,accounting_simplifiedname';
  $query_prm = array();
  require ('inc/doquery.php');
  if ($num_results > $num_results_main) { $num_results_main = $num_results; }
  
  ?><h2>Liste des menus simplifiés</h2>
  <table class=report><thead><th>Groupe</th><th>Menu</th><th>Supprimé</th><th><th>Groupe</th><th>Menu</th><th>Supprimé</th></thead><?php
  for ($i=0; $i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    $row2 = $query_result[$i];
    echo d_tr();
    $link = '##accounting.php?accountingmenu=add_sa&readme=1&a_sid='.$row['accounting_simplifiedid'];
    echo d_td_old($row['accounting_simplifiedgroupname']);
    echo d_td_old($row['accounting_simplifiedname'],0,0,0,$link);
    $kladd = ''; if ($row['deleted']) { $kladd = '&radic;'; }
    echo d_td_old($kladd,4);
    echo d_td_old();
    echo d_td_old($row2['accounting_simplifiedgroupname']);
    echo d_td_old($row2['accounting_simplifiedname'],0,0,0,$link);
    $kladd = ''; if ($row2['deleted']) { $kladd = '&radic;'; }
    echo d_td_old($kladd,4);
  }
  ?></table><?php
}