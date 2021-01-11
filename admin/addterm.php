<?php
switch($currentstep)
{

  # Enter data
  case 0:
  ?>
  <h2>Ajouter un délai de paiement:</h2>
  <form method="post" action="admin.php">
  <table>
  <tr><td>Nom:</td><td><input type="text" name="name" size=50></td></tr>
  <tr><td>Jours:</td><td><input type="text" name="days" size=50></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1">
<?php echo '<input type=hidden name="adminmenu" value="' . $adminmenu . '">'; ?>
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # Save data
  case 1:
  $clienttermname = $_POST['name'];
  $daystopay = (int) $_POST['days'];
  $query = 'insert into clientterm (clienttermname,daystopay) values ("' . $clienttermname . '","' . $daystopay . '")';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<p>Délai ' . $clienttermname . ' ajouté.</p>';
  break;

}
?>