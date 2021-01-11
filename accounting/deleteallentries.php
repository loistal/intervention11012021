<?php

if (
      $dauphin_instancename == 'solcag_3' ||
      $dauphin_instancename == 'solcag_4' ||
      $dauphin_instancename == 'solcag_5' ||
      $dauphin_instancename == 'solcag_6' ||
      $dauphin_instancename == 'solcag_7' ||
      $dauphin_instancename == 'solcag_8' ||
      $dauphin_instancename == 'solcag_9' ||
      $dauphin_instancename == 'solcag_10' ||
      $dauphin_instancename == 'solcag_11' ||
      $dauphin_instancename == 'solcag_12' ||
      $dauphin_instancename == 'solcag_13' ||
      $dauphin_instancename == 'solcag_14'
     )
{

if (isset($_POST['delete']) && $_POST['delete'] == 1)
{
  $query = '
  truncate table adjustment;
  truncate table adjustmentgroup;
  truncate table matching;
  truncate table reconciliation;
  truncate table client;
  ';
  $query_prm = array();
  require('inc/doquery.php');
  echo 'Ecritures supprimés.<br><br>';
}

?>

<h2 class=alert>Supprimer tous écritures</h2>
<p class=alert>Cette opération est irréversible.</p>
<form method="post" action="accounting.php"><input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
<table>
<tr><td colspan="2" align="center"><input type=hidden name=delete value=1>
<input type="submit" value="Supprimer"></td></tr>
</table></form>
<?php
}
?>
