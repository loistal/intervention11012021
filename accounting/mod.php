<?php

$accountingnumberid = -1;
if (isset($_POST['accountingnumberid'])) { $accountingnumberid = $_POST['accountingnumberid']; }
elseif (isset($_GET['accountingnumberid'])) { $accountingnumberid = $_GET['accountingnumberid']; }

if (isset($_POST['saveme']) && $_POST['saveme'] == 1 && $dauphin_instancename == 'solcag_demo' && $_POST['delete_permanent'] == 1) # allow permanent deletion
{
  $query = 'delete from accountingnumber where accountingnumberid=? limit 1';
  $query_prm = array($_POST['accountingnumberid']);
  require('inc/doquery.php');
}
elseif (isset($_POST['saveme']) && $_POST['saveme'] == 1)
{
  if ($_POST['isbank'] == 1) { $isbank = 1; }
  else { $isbank = 0; }
  if ($_POST['matchable'] == 1) { $matchable = 1; }
  else { $matchable = 0; }
  if ($_POST['needreference'] == 1) { $needreference = 1; }
  else { $needreference = 0; }
  if ($_POST['vatnegative'] == 1) { $vatnegative = 1; }
  else { $vatnegative = 0; }
  if ($_POST['deleted'] == 1) { $deleted = 1; }
  else { $deleted = 0; }
  $query = 'update accountingnumber set deleted=?,acname=?,acnumber=?,isbank=?,matchable=?,needreference=?,vatnegative=?,accountinggroupid=?,vatindexid=?,turnoverindexid=?,balancesheetindexid=? where accountingnumberid=?';
  $query_prm = array($deleted, $_POST['acname'], $_POST['acnumber'], $isbank, $matchable, $needreference, $vatnegative, $_POST['accountinggroupid'], $_POST['vatindexid'], $_POST['turnoverindexid'], $_POST['balancesheetindexid'], $_POST['accountingnumberid']);
  require('inc/doquery.php');
  echo 'Compte modifié.';
}
elseif ($accountingnumberid > 0)
{
  $query = 'select * from accountingnumber where accountingnumberid=?';
  $query_prm = array($accountingnumberid);
  require('inc/doquery.php');
  $row = $query_result[0];
  $accountinggroupid = $row['accountinggroupid'];
  $vatindexid = $row['vatindexid'];
  $turnoverindexid = $row['turnoverindexid'];
  $balancesheetindexid = $row['balancesheetindexid'];
  #$alt_balancesheetindexid = $row['alt_balancesheetindexid'];
  $vatnegative = $row['vatnegative'];
  $deleted = $row['deleted'];
  ?><h2>Modifier compte</h2>
  <form method="post" action="accounting.php"><table>
  <tr><td>Numéro:</td><td><input type="text" name="acnumber" value="<?php echo $row['acnumber']; ?>" size=20></td></tr>
  <tr><td>Description:</td><td><input type="text" name="acname" value="<?php echo $row['acname']; ?>" size=30></td></tr>
  <tr><td>Compte banque:</td><td><input type="checkbox" name="isbank" value="1"
  <?php if ($row['isbank']) { echo 'CHECKED'; } ?>
  ></td></tr>
  <?php
  if ($accountingnumberid != 1) {
  ?>
    <tr><td>Lettrage:</td><td><input type="checkbox" name="matchable" value="1"
    <?php if ($row['matchable']) { echo 'CHECKED'; } ?>
    ></td></tr>
  <?php } ?>
  <tr><td>Tiers:</td><td><input type="checkbox" name="needreference" value="1"
  <?php if ($row['needreference']) { echo 'CHECKED'; } ?>
  ></td></tr>
  <tr><td>Groupe:</td><td><select name="accountinggroupid"><?php
  $query = 'select accountinggroupid,agname from accountinggroup order by agname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    if ($row['accountinggroupid'] == $accountinggroupid) { echo '<option value="' . $row['accountinggroupid'] . '" SELECTED>' . $row['agname'] . '</option>'; }
    else { echo '<option value="' . $row['accountinggroupid'] . '">' . $row['agname'] . '</option>'; }
  }
  ?></select></td></tr>
  <tr><td>Rapport TVA:</td><td><select name="vatindexid"><option value=0></option><?php
  $query = 'select vatindexid,vatindexname from vatindex order by vatindexname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    if ($row['vatindexid'] == $vatindexid) { echo '<option value="' . $row['vatindexid'] . '" SELECTED>' . $row['vatindexname'] . '</option>'; }
    else { echo '<option value="' . $row['vatindexid'] . '">' . $row['vatindexname'] . '</option>'; }
  }
  ?></select> (<input type=checkbox name="vatnegative" value=1 <?php if ($vatnegative == 1) { echo ' checked'; } ?>> Débit moins Crédit)</td></tr>
  <tr><td>Déclaration CA:</td><td><select name="turnoverindexid"><option value=0></option><?php
  $query = 'select turnoverindexid,turnoverindexname from turnoverindex order by turnoverindexname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    if ($row['turnoverindexid'] == $turnoverindexid) { echo '<option value="' . $row['turnoverindexid'] . '" SELECTED>' . $row['turnoverindexname'] . '</option>'; }
    else { echo '<option value="' . $row['turnoverindexid'] . '">' . $row['turnoverindexname'] . '</option>'; }
  }
  ?></select></td></tr>
  <tr><td>Bilan:</td><td><select name="balancesheetindexid"><?php
  $query = 'select balancesheetindexid,balancesheetindexname from balancesheetindex order by balancesheetindexname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    if ($row['balancesheetindexid'] == $balancesheetindexid) { echo '<option value="' . $row['balancesheetindexid'] . '" SELECTED>' . $row['balancesheetindexname'] . '</option>'; }
    else { echo '<option value="' . $row['balancesheetindexid'] . '">' . $row['balancesheetindexname'] . '</option>'; }
  }
  ?></select></td></tr>
  <?php /*
  <tr><td>Bilan (si négatif):</td><td><select name="alt_balancesheetindexid"><?php
  $query = 'select balancesheetindexid,balancesheetindexname from balancesheetindex order by balancesheetindexname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    if ($row['balancesheetindexid'] == $alt_balancesheetindexid) { echo '<option value="' . $row['balancesheetindexid'] . '" SELECTED>' . $row['balancesheetindexname'] . '</option>'; }
    else { echo '<option value="' . $row['balancesheetindexid'] . '">' . $row['balancesheetindexname'] . '</option>'; }
  }
  ?></select></td></tr>*/ ?>
  <tr><td>Supprimé:</td><td><input type="checkbox" name="deleted" value="1"
  <?php if ($deleted == 1) { echo 'CHECKED'; } ?>
  >
  <?php
  if ($dauphin_instancename == 'solcag_demo') # allow permanent deletion
  {
    echo ' &nbsp; &nbsp; &nbsp; Supprimer (permanent) <input type="checkbox" name="delete_permanent" value=1>';
  }
  ?>
  <tr><td colspan="2" align="center">
  <input type=hidden name="saveme" value="1"><input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
  <input type=hidden name="accountingnumberid" value="<?php echo $accountingnumberid; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
}
else
{
  ?><h2>Modifier compte</h2>
  <form method="post" action="accounting.php"><table>
  <tr><td>Numéro:</td>
  <td><select name="accountingnumberid"><?php
  $query = 'select accountingnumberid,acnumber,acname,deleted from accountingnumber order by deleted,acnumber,acname'; # where accountingnumberid>=1000
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<option value="' . $row['accountingnumberid'] . '">' . $row['acnumber'] . ': ' . $row['acname'];
    if ($row['deleted'] == 1) { echo ' [Supprimé]'; }
    echo '</option>';
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
}

?>