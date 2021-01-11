<?php
echo '<h2>Stock par utilisateur</h2>';
?>

<p class="alert">Attention ce rapport est très lourd.</p>
<form method="post" action="reportwindow.php" target=_blank><table>

<?php

$dp_itemname = 'user'; $dp_allowall = 1; $dp_noblank = 1; $dp_description = 'Stock pour';
require('inc/selectitem.php');
echo '&nbsp;<input type=checkbox name="to_zero" value=1> <span class="alert">Mettre à zéro</span>';

$dp_description = d_trad('subfamily'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamily.php');

?>
<tr><td colspan=2><input type=checkbox name="calc_value" value=1> <span class="alert">Calculer valeurs</span>
<tr><td colspan=2>&nbsp;
<tr><td colspan=2 align=center><input type=checkbox name="calcglobal" value=1> Mettre stock global = somme des utilisateurs <input type=checkbox name="calcglobal2" value=1>
<tr><td colspan=2>&nbsp;
<tr><td colspan="2" align="center"><input type=hidden name="report" value="stockperuserreport"><input type="submit" value="<?php echo d_trad('validate');?>">
</table></form>