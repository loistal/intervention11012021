<h2>Remise de chèques:</h2>
<?php
#<p class="alert">Ce menu est obsolète. Veuillez utiliser le menu "Dépôt".</p>
?>
<form method="post" action="printwindow.php" target="_blank"><table>

<?php
$dp_itemname = 'bankaccount'; $dp_description = 'Compte du depôt'; $dp_noblank = 1;
require('inc/selectitem.php');
?>

<tr><td>Chèques de</td><td><?php
$datename = "startdate";
require('inc/datepicker.php');
?></td></tr>

<tr><td>à</td><td><?php
$datename = "stopdate";
require('inc/datepicker.php');
?></td></tr>

<tr><td>Matin/Après-midi:</td><td><select name="time">
<option value="D" SELECTED></option>
<option value="M">Matin</option>
<option value="A">Après-midi</option>
</select></td></tr>

<tr><td colspan=2>&nbsp;</td></tr>

<tr><td>Banque du tireur:</td><td><select name="bankid"><?php
echo '<option value="0"></option>';
$query = 'select bankid,bankname from bank where bankid>0 order by bankname';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  echo '<option value="' . $row['bankid'] . '">' . $row['bankname'] . '</option>';
}
?></select></td></tr>

<tr><td>Qui a saisi le paiement:</td><td><select name="userid"><?php
echo '<option value="0"></option>';
$query = 'select userid,name from usertable order by name';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  echo '<option value="' . $row['userid'] . '">' . $row['name'] . '</option>';
}
?></select></td></tr>

<tr><td>Employé:
<?php
$dp_itemname = 'employee'; $dp_issales = 1; $dp_allowall = 1; require('inc/selectitem.php');
?>

<tr><td colspan=2>&nbsp;</td></tr>

<tr><td colspan="2"><input type=radio name="overwrite" value=0 checked> Ignorer chèques déja remis</td></tr>
<tr><td colspan="2"><input type=radio name="overwrite" value=1> Afficher chèques déja remis</td></tr>
<tr><td colspan="2"><input type=radio name="overwrite" value=2> Modifier chèques déja remis</td></tr>


<tr><td colspan="2" align="center"><input type=hidden name="report" value="chequebank"><input type="submit" value="Valider"></td></tr>
</table></form>