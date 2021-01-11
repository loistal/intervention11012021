<?php

if ($_POST['a_sgname'] != "")
{
  $query = 'insert into accounting_simplifiedgroup (accounting_simplifiedgroupname,`rank`) values (?,?)';
  $query_prm = array($_POST['a_sgname'],$_POST['rank']);
  require('inc/doquery.php');
  echo '<p>Rubrique ' . d_output($_POST['a_sgname']) . ' ajouté.</p>';
}

?>

<h2>Ajouter rubrique compta simplifiée</h2>
<form method="post" action="accounting.php"><table>
<tr><td>Description:</td><td align=right><input autofocus type="text" STYLE="text-align:right" name="a_sgname" size=40></td></tr>
<tr><td>Rang:</td><td align=right><input type="text" STYLE="text-align:right" name="rank" size=8></td></tr>
<tr><td colspan="2" align="center"><input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>"><input type="submit" value="Valider"></td></tr>
</table></form>