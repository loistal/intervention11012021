<?php

$nullify_previous_period = 1; # create entry to set prvious period (year) to zero

if ($_POST['adjustmentgroupid'] > 0)
{
  $query = 'update adjustmentgroup set deleted=1 where closing=1 and deleted=0 and adjustmentgroupid=?';
  $query_prm = array($_POST['adjustmentgroupid']);
  require('inc/doquery.php');
  if ($num_results == 0)
  {
    echo '<p>Erreur suppression clôture.</p>';
  }
  else
  {
    echo '<p>Clôture supprimé.</p>';
  }
  if ($nullify_previous_period)
  {
    $query = 'select adjustmentgroupid,adjustmentdate from adjustmentgroup where closing=2 and deleted=0 order by adjustmentdate desc limit 1';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results > 0)
    {
      $query = 'update adjustmentgroup set deleted=1 where closing=2 and deleted=0 and adjustmentgroupid=?';
      $query_prm = array($query_result[0]['adjustmentgroupid']);
      require('inc/doquery.php');
    }
  }
}
else
{
  $query = 'select adjustmentgroupid,adjustmentdate from adjustmentgroup where closing=1 and deleted=0 order by adjustmentdate desc limit 1';
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results == 0)
  {
    echo '<p>Aucune clôture existant.</p>';
  }
  else
  {
    ?><h2>"Dé-Clôturer" <?php echo datefix($query_result[0]['adjustmentdate']); ?></h2>
    <form method="post" action="accounting.php"><table>
    <tr><td colspan="2" align="center">
    <input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
    <input type=hidden name="accountingmenu_sa" value="control">
    <input type=hidden name="adjustmentgroupid" value="<?php echo $query_result[0]['adjustmentgroupid']; ?>">
    <input type="submit" value="Valider"></td></tr>
    </table></form>
    <?php
  }
}

?>