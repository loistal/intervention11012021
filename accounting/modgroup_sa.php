<?php

if ($_POST['accounting_simplifiedgroupid'] > 0)
{
  $a_sgid = $_POST['accounting_simplifiedgroupid'];
  if ($_POST['saveme'] == 1)
  {
    $deleted = (int) $_POST['deleted'];
    $query = 'update accounting_simplifiedgroup set accounting_simplifiedgroupname=?,`rank`=?,deleted=? where accounting_simplifiedgroupid=?';
    $query_prm = array($_POST['a_sgname'],$_POST['rank'],$deleted,$a_sgid);
    require('inc/doquery.php');
    echo '<p>Rubrique compta simplifiée '.d_output($_POST['a_sgname']).' modifié.</p>';
  }
  else
  {
    $query = 'select * from accounting_simplifiedgroup where accounting_simplifiedgroupid=?';
    $query_prm = array($a_sgid);
    require('inc/doquery.php');
    if ($num_results)
    {
      echo '<h2>Modifier rubrique compta simplifiée '.$query_result[0]['accounting_simplifiedgroupname'].'</h2>';
      ?>
      <form method="post" action="accounting.php"><table>
      <tr><td>Description:</td><td align=right><input autofocus type="text" STYLE="text-align:right" name="a_sgname" value="<?php echo $query_result[0]['accounting_simplifiedgroupname']; ?>" size=40></td></tr>
      <tr><td>Rang:</td><td align=right><input type="text" STYLE="text-align:right" name="rank" size=8 value="<?php echo $query_result[0]['rank']; ?>"></td></tr>
      <tr><td>Supprimé:</td><td align=right><input type="checkbox" name="deleted" value=1 <?php if ($query_result[0]['deleted'] == 1) { echo 'checked'; } ?>></td></tr>
      <tr><td colspan="2" align="center"><input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>"><input type="submit" value="Valider"></td></tr>
      <input type=hidden name="accounting_simplifiedgroupid" value="<?php echo $query_result[0]['accounting_simplifiedgroupid']; ?>"><input type=hidden name="saveme" value=1>
      </table></form>
      <?php
    }
  }
}
else
{
  ?><h2>Modifier rubrique compta simplifiée</h2>
  <form method="post" action="accounting.php"><table>
  <?php
  $dp_itemname = 'accounting_simplifiedgroup'; $dp_description = 'Groupe'; $dp_noblank = 1; $dp_showdeleted = 1; require('inc/selectitem.php');
  ?>
  <tr><td colspan="2" align="center">
  <input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form>
  <?php
}
?>