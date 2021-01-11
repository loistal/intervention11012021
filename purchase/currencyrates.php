<?php
switch($currentstep)
{
  # Enter data
  case '0':
  echo '<h2>Modifier taux de douane</h2>';
  $query = 'select currencyid,currencyacronym,currencyrate from currency where deleted=0 order by currencyacronym';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<form method="post" action="purchase.php"><table border=1 cellspacing=1 cellpadding=2><tr><td><b>Devise</b></td><td><b>Taux</b></td></tr>';
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $scrap = "currencyid" . $i;
    $_SESSION[$scrap] = $row['currencyid']; # TODO remove session variable, use post
    echo '<tr><td>' . $row['currencyacronym'] . '</td><td align=right><input type="text" STYLE="text-align:right" name="currencyrate' . $i . '" value="' . $row['currencyrate'] . '" size=10></td></tr>';
  }
  ?><tr><td colspan="5" align="center"><input type=hidden name="step" value="1">
  <input type=hidden name="num_results" value="<?php echo $num_results; ?>">
  <input type=hidden name="purchasemenu" value="<?php echo $purchasemenu; ?>"><input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # Save data
  case '1':
  for ($i=0; $i < $_POST['num_results']; $i++)
  {
    $scrap = "currencyid" . $i;
    $currencyid = $_SESSION[$scrap];
    unset($_SESSION[$scrap]);
    $scrap = "currencyrate" . $i;
    $query = 'update currency set currencyrate="' . ($_POST[$scrap]+0) . '" where currencyid="' . $currencyid . '"';
    $query_prm = array();
    require('inc/doquery.php');
  }
  echo 'Taux modifiés.';
  break;

}
?>