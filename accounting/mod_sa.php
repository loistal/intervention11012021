<h2>Modifier modèle compta simplifiée</h2>
<form method="post" action="accounting.php"><table>
<?php
$dp_itemname = 'accounting_simplified'; $dp_description = 'Menu'; $dp_noblank = 1; $dp_showdeleted = 1; require('inc/selectitem.php');
?>
<tr><td colspan="2" align="center">
<input type=hidden name="accountingmenu" value="add_sa"><input type=hidden name="readme" value=1>
<input type="submit" value="Valider"></td></tr>
</table></form>