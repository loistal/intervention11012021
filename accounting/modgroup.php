<?php
$step = 0;
if (isset($_POST['step'])) { $step = (int) $_POST['step']; }
switch($step)
{

  # 
  case 0:
  ?><h2>Modifier groupe</h2>
  <form method="post" action="accounting.php"><table>
  <tr><td>Groupe:</td>
  <td><select name="accountinggroupid"><?php
  $query = 'select accountinggroupid,agname from accountinggroup order by agname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<option value="' . $row['accountinggroupid'] . '">' . $row['agname'] . '</option>';
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # 
  case 1:
  $query = 'select agname from accountinggroup where accountinggroupid=?';
  $query_prm = array($_POST['accountinggroupid']);
  require('inc/doquery.php');
  $row = $query_result[0];
  ?><h2>Modifier groupe</h2>
  <form method="post" action="accounting.php"><table>
  <tr><td>Description:</td><td><input type="text" name="agname" value="<?php echo $row['agname']; ?>" size=20></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="2"><input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
  <input type=hidden name="accountinggroupid" value="<?php echo $_POST['accountinggroupid']; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # 
  case 2:
  $query = 'update accountinggroup set agname=? where accountinggroupid=?';
  $query_prm = array($_POST['agname'], $_POST['accountinggroupid']);
  require('inc/doquery.php');
  echo 'Groupe modifié.';
  break;

}
?>