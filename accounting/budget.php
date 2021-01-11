<?php

if ($_POST['savebudget'] == 1)
{
  $r = (int) $_POST['r'];
  $e = (int) $_POST['e'];
  $query = 'update companyinfo set budget_revenue_monthly=?,budget_expense_monthly=? where companyinfoid=1';
  $query_prm = array($r, $e);
  require('inc/doquery.php');
  echo '<p>Budget modifié.</p>';
}

$query = 'select budget_revenue_monthly,budget_expense_monthly from companyinfo where companyinfoid=1';
$query_prm = array();
require('inc/doquery.php');
if ($num_results == 0)
{
  # create empty line
  $query = 'insert into companyinfo (companyinfoid) values (1)';
  $query_prm = array();
  require('inc/doquery.php');
}
$r = myround($query_result[0]['budget_revenue_monthly']);
$e = myround($query_result[0]['budget_expense_monthly']);

?>

<h2>Budget</h2>
<form method="post" action="accounting.php"><table>
<tr><td>Revenue par mois:</td><td align=right><input autofocus type="text" STYLE="text-align:right" name="r" value="<?php echo $r; ?>" size=20></td></tr>
<tr><td>Charges par mois:</td><td align=right><input type="text" STYLE="text-align:right" name="e" value="<?php echo $e; ?>" size=20></td></tr>
<tr><td colspan="2" align="center"><input type=hidden name="savebudget" value="1"><input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
<input type=hidden name="accountingmenu_sa" value="admin">
<input type="submit" value="Valider"></td></tr></table></form>
