<?php
$step = 0;
if (isset($_POST['step'])) { $step = (int) $_POST['step']; }
switch($step)
{

  case 0:
  ?><h2>Ajouter compte</h2>
  <form method="post" action="accounting.php"><table>
  <tr><td>Numéro:</td><td align=right><input autofocus type="text" STYLE="text-align:right" name="acnumber" size=20></td></tr>
  <tr><td>Description:</td><td align=right><input type="text" STYLE="text-align:right" name="acname" size=30></td></tr>
  <tr><td>Compte banque:</td><td><input type="checkbox" name="isbank" value="1"></td></tr>
  <tr><td>Lettrage:</td><td><input type="checkbox" name="matchable" value="1"></td></tr>
  <tr><td>Groupe:</td><td><select name="accountinggroupid"><?php

  $query = 'select accountinggroupid,agname from accountinggroup order by agname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<option value="' . $row['accountinggroupid'] . '">' . $row['agname'] . '</option>';
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>"><input type="submit" value="Valider"></td></tr>
  </table></form>
  <?php
  break;

  case 1:
  if ($_POST['acname'] == "" || $_POST['acnumber'] == "")
  {
    echo 'Les champs sont obligatoires.'; exit;
  }
  $isbank = $_POST['isbank']; if ($isbank == "") { $isbank = 0; }
  $matchable = $_POST['matchable']; if ($matchable == "") { $matchable = 0; }
  $query = 'insert into accountingnumber (acname,acnumber,isbank,matchable,accountinggroupid) values (?, ?, ?, ?, ?)';
  $query_prm = array($_POST['acname'], $_POST['acnumber'], $isbank, $matchable, $_POST['accountinggroupid']);
  require('inc/doquery.php');
  echo '<h2>Compte ' . d_output($_POST['acnumber']) . ' ajouté</h2>';
  break;

}
?>