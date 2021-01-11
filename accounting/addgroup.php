<?php
$step = 0;
if (isset($_POST['step'])) { $step = (int) $_POST['step']; }
switch($step)
{

  case 0:
  ?><h2>Ajouter groupe</h2>
  <form method="post" action="accounting.php"><table>
  <tr><td>Description:</td><td align=right><input type="text" STYLE="text-align:right" name="agname" size=20></td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>"><input type="submit" value="Valider"></td></tr>
  </table></form>
  <?php
  break;

  case 1:
  if ($_POST['agname'] == "")
  {
    echo 'La Description est obligatoire.'; exit;
  }
  $query = 'insert into accountinggroup (agname) values (?)';
  $query_prm = array($_POST['agname']);
  require('inc/doquery.php');
  echo '<h2>Groupe ' . $_POST['agname'] . ' ajouté</h2>';
  break;

}
?>