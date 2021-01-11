<?php

$PA['accountingnumberid'] = 'int';
$PA['accountingnumber1id'] = 'int';
$PA['update_simplified'] = 'int';
require('inc/readpost.php');

require('preload/accountingnumber.php');

if ($accountingnumberid > 0 && $accountingnumber1id > 0 && $accountingnumberid != $accountingnumber1id)
{
  $changed = 0;
  $query = 'update adjustment set accountingnumberid=? where accountingnumberid=?';
  $query_prm = array($accountingnumber1id, $accountingnumberid);
  require('inc/doquery.php');
  if ($num_results) { $changed = 1; }
  if ($update_simplified)
  {
    $query = 'update accounting_simplified set balanceline_accountingnumberid=? where balanceline_accountingnumberid=?';
    $query_prm = array($accountingnumber1id, $accountingnumberid);
    require('inc/doquery.php');
    if ($num_results) { $changed = 1; }
    for ($i=1; $i <= 16; $i++)
    {
      $query = 'update accounting_simplified set line'.$i.'_accountingnumberid=? where line'.$i.'_accountingnumberid=?';
      $query_prm = array($accountingnumber1id, $accountingnumberid);
      require('inc/doquery.php');
      if ($num_results) { $changed = 1; }
    }
  }
  if ($changed)
  {
    echo '<p class="alert">Compte : ' . $accountingnumber_longA[$accountingnumberid] . '<br>Remplacé par : ' . $accountingnumber_longA[$accountingnumber1id] . '</p><br>';
  }
}

?><h2 class="alert">Cette opération est irréversible</h2><h2>Remplacer compte</h2>
<form method="post" action="accounting.php"><table>
<tr><td>Changer:<?php $dp_itemname = 'accountingnumber'; $dp_long = 1; $dp_noblank = 1; require('inc/selectitem.php'); ?>
<tr><td>A:<?php $dp_itemname = 'accountingnumber'; $dp_long = 1; $dp_noblank = 1; $dp_addtoid = 1; require('inc/selectitem.php'); ?>
<tr><td colspan=2><input type=checkbox name="update_simplified" value=1> Modifier menus simplifiés
<tr><td colspan="2" align="center">
<input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
<input type=hidden name="accountingmenu_sa" value="admin">
<input type="submit" value="Valider"></td></tr>
</table></form>