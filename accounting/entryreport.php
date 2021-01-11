<?php

require('inc/autocomplete_accounting.php');
require('preload/adjustmentgroup_tag.php');

$PA['option'] = '';
require('inc/readpost.php');

echo '<table class="transparent"><tr><td>';

echo '<h2>Rapport Écritures:</h2>';
echo '<form method="post" action="reportwindow.php" target="_blank"><table>';
echo '<tr><td>Début:</td><td>';
$datename = 'startdate'; if (isset($startdate)) { $selecteddate = $startdate; }
else { $startdate = d_builddate(1,1,mb_substr($_SESSION['ds_curdate'],0,4)); }
require('inc/datepicker.php');
echo '</td></tr><tr><td>Fin:</td><td>';
$datename = 'stopdate'; if (isset($stopdate)) { $selecteddate = $stopdate; }
else { $stopdate = d_builddate(31,12,mb_substr($_SESSION['ds_curdate'],0,4)); }
require('inc/datepicker.php');
echo '<tr>';
if ($_SESSION['ds_accounting_accountbyselect'] == 1)
{
  $dp_itemname = 'accountingnumber'; $dp_allowall = 1; $dp_noblank = 1; $dp_description = 'Compte';
  require('inc/selectitem.php');
  echo ' (à ';
  $dp_itemname = 'accountingnumber'; $dp_addtoid = 'to'; $dp_notable = 1;
  require('inc/selectitem.php');
  echo ')';
}
else
{
  echo '<tr><td>Compte:<td><input type="text" name="accountingnumber" id="accounting_autocomplete" autocomplete="off" size=10>
  (à <input type="text" name="accountingnumberto" id="accounting_autocomplete2" autocomplete="off" size=10>)';
}
echo '<tr><td>Écriture No:</td><td><input type="text" STYLE="text-align:right" name="id" size=10> (à <input type="text" STYLE="text-align:right" name="idto" size=10>)</td></tr>';
$dp_itemname = 'journal'; $dp_description = 'Journal'; $dp_allowall = 1; require('inc/selectitem.php');
if (isset($adjustmentgroup_tagA))
{
  $dp_itemname = 'adjustmentgroup_tag'; $dp_description = $_SESSION['ds_term_accounting_tag']; $dp_allowall = 1; require('inc/selectitem.php');
}
else { echo '<input type=hidden name="adjustmentgroup_tagid" value=-1>'; }
$dp_itemname = 'accountinggroup'; $dp_description = 'Groupe'; $dp_allowall = 1; $dp_noblank = 1; require('inc/selectitem.php');
$dp_itemname = 'accounting_simplified'; $dp_description = 'Modèle simplifié'; $dp_allowall = 1; require('inc/selectitem.php');
$dp_itemname = 'accounting_simplifiedgroup'; $dp_description = 'Rubrique simplifié'; $dp_allowall = 1; require('inc/selectitem.php');

echo '<tr><td>Écritures intégrées:<td><select name="integrated">
<option value=-1>'. d_trad('selectall') .'</option>
<option value=0></option>
<option value=1>Factures/Avoirs</option>
<option value=2>Paiements</option>
<option value=3>Dépôt</option>
<option value=4>Paie</option>
<option value=5>Paiements Paie</option>
</select>';

###
$query = 'select distinct accountingnumberid from bankaccount';
$query_prm = array();
require('inc/doquery.php');
if ($num_results)
{
  require('preload/accountingnumber.php');
  echo ' &nbsp; <select name="integrated5_anid">
  <option value=-1></option>';
  for ($i=0;$i<$num_results;$i++)
  {
    echo '<option value='.$query_result[$i]['accountingnumberid'].'>Paiements Paie: '.$accountingnumberA[$query_result[$i]['accountingnumberid']].'</option>';
  }
  echo '</select>';
}

###

echo '<tr><td>Montant:</td><td><input type="text" STYLE="text-align:right" name="amount" size=10> (à <input type="text" STYLE="text-align:right" name="amountto" size=10>)</td></tr>';
echo '<tr><td>'.$_SESSION['ds_term_accounting_comment'].':</td><td><input type="text" name="adjustmentcomment" size=20></td></tr>';
echo '<tr><td>'.$_SESSION['ds_term_accounting_reference'].':</td><td><input type="text" name="reference" size=20></td></tr>';
echo '<tr><td>'; $dp_description = 'Tiers:'; $noautofocus = 1; require('inc/selectclient.php');
## select client (clients only) +tous
$query = 'select clientid,clientname from client where deleted=0 and isclient=1 order by clientname';
$query_prm = array();
require('inc/doquery.php');
if ($num_results)
{
  echo '<tr><td><td>Client: <select name="isclientid"><option value=-1>'. d_trad('selectall') .'</option>';
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<option value="' . $row['clientid'] . '">' . d_output(d_decode($row['clientname'])) . '</option>';
  }
  echo '</select></td></tr>';
}
## select client (supplier only)
$query = 'select clientid,clientname from client where deleted=0 and issupplier=1 order by clientname';
$query_prm = array();
require('inc/doquery.php');
if ($num_results)
{
  echo '<tr><td><td>Fournisseur: <select name="issupplierid"><option value=-1>'. d_trad('selectall') .'</option>';
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<option value="' . $row['clientid'] . '">' . d_output(d_decode($row['clientname'])) . '</option>';
  }
  echo '</select></td></tr>';
}
echo '<tr>';
$dp_itemname = 'user'; $dp_allowall = 1; $dp_noblank = 1; $dp_description = 'Utilisateur';
require('inc/selectitem.php');
echo '<tr><td>Lettré:<td><select name="matched"><option value=-1>Tous</option><option value=1>Oui</option><option value=0>Non</option></select>';
echo '<tr><td>A nouveau:</td><td><select name="closing"><option value=-1>Tous</option><option value=1>Oui</option><option value=0>Non</option></select></td></tr>';
echo '<tr><td>Rapproché:</td><td><select name="reconciled"><option value=-1>Tous</option><option value=1>Oui</option><option value=0>Non</option></select></td></tr>';
echo '<tr><td>Ranger par:</td><td><select name="orderby"><option value=1>Date</option><option value=2>Compte</option><option value=4>Compte, Tiers</option><option value=3>Balance</option></select></td></tr>';
echo '<tr><td colspan=2><input type=checkbox name="shortenfields" value=1> Rétrécir les champs';
echo '<tr><td colspan=2><input type=checkbox name="space_lines" value=1> Ligne vide entre écritures';
echo '<tr><td colspan=2><input type=checkbox name="extrafields" value=1> Infos supplémentaires
&nbsp; <input type=checkbox name="numbered_extrafields" value=1> Numéros Lettrage et Rapprochement';
echo '<tr><td colspan=2><input type=checkbox name="show_clientcode" value=1> Afficher Code Client pour Tiers';
?>
<tr><td colspan="2" align="center">
<input type=hidden name="report" value="entryreport"><input type=hidden name="report" value="entryreport">
<input type="submit" value="Valider"></td></tr></table></form>

<td width=10>&nbsp;&nbsp;
<td valign=top>

<?php
echo '<h2>Grand Livre</h2><p>Le Grand Livre est le Rapport Écritures rangé par compte.</p>';
$query = 'select adjustmentgroupid,adjustmentdate from adjustmentgroup
where closing=1 and deleted=0 order by adjustmentdate desc limit 1';
$query_prm = array();
require('inc/doquery.php');
if ($num_results) { $startdate = $query_result[0]['adjustmentdate']; }
else { $startdate = d_builddate(1,1,mb_substr($_SESSION['ds_curdate'],0,4)); }
$stopdate = d_builddate(31,12,mb_substr($_SESSION['ds_curdate'],0,4));
echo '<form method="post" action="reportwindow.php" target="_blank"><table>';
echo '<tr><td>Début:</td><td>';
$datename = 'startdate'; if (isset($startdate)) { $selecteddate = $startdate; }
require('inc/datepicker.php');
echo '</td></tr><tr><td>Fin:</td><td>';
$datename = 'stopdate'; if (isset($stopdate)) { $selecteddate = $stopdate; }
require('inc/datepicker.php');
echo '<tr><td>Lettré:</td><td><select name="matched"><option value=-1>Tous</option><option value=1>Oui</option><option value=0>Non</option></select>';
echo '<input type=hidden name="accounting_simplifiedid" value="-1">
<input type=hidden name="orderby" value="2">
<input type=hidden name="integrated" value="-1">
<input type=hidden name="journalid" value="-1">';
echo '<tr><td colspan=2><input type=checkbox name="shortenfields" value=1> Rétrécir les champs';
echo '<tr><td colspan=2><input type=checkbox name="space_lines" value=1> Ligne vide entre écritures';
echo '<tr><td colspan=2><input type=checkbox name="extrafields" value=1> Infos supplémentaires
&nbsp; <input type=checkbox name="numbered_extrafields" value=1> Numéros Lettrage et Rapprochement';
echo '<tr><td colspan=2><input type=checkbox name="show_clientcode" value=1> Afficher Code Client pour Tiers';
?>
<tr><td colspan="2" align="center">
<input type=hidden name="report" value="entryreport"><input type=hidden name="report" value="entryreport">
<input type=hidden name="adjustmentgroup_tagid" value=-1>
<input type="submit" value="Valider"></td></tr></table></form>

<br><br>

<?php
echo '<h2>Balance</h2><p>La Balance est le Rapport Écritures rangé par "Balance".</p>';
echo '<form method="post" action="reportwindow.php" target="_blank"><table>';
echo '<tr><td>Début:</td><td>';
$datename = 'startdate'; if (isset($startdate)) { $selecteddate = $startdate; }
require('inc/datepicker.php');
echo '</td></tr><tr><td>Fin:</td><td>';
$datename = 'stopdate'; if (isset($stopdate)) { $selecteddate = $stopdate; }
require('inc/datepicker.php');
echo '</td></tr>';
echo '<input type=hidden name="accounting_simplifiedid" value="-1">
<input type=hidden name="orderby" value="3">
<input type=hidden name="integrated" value="-1">
<input type=hidden name="journalid" value="-1">';
?>
<tr><td colspan="2" align="center">
<input type=hidden name="report" value="entryreport"><input type=hidden name="report" value="entryreport">
<input type=hidden name="adjustmentgroup_tagid" value=-1>
<input type="submit" value="Valider"></td></tr></table></form>

<br><br>

<?php
echo '<h2>Balance des Tiers</h2>';
echo '<form method="post" action="reportwindow.php" target="_blank"><table>';
echo '<tr><td>Début:</td><td>';
$datename = 'startdate'; if (isset($startdate)) { $selecteddate = $startdate; }
require('inc/datepicker.php');
echo '</td></tr><tr><td>Fin:</td><td>';
$datename = 'stopdate'; if (isset($stopdate)) { $selecteddate = $stopdate; }
require('inc/datepicker.php');
echo '<tr><td>Tiers:<td><select name="type">
<option value=-1>'. d_trad('selectall') .'</option>
<option value=1>Client</option>
<option value=2>Fournisseur</option>
<option value=3>Salarié</option>
<option value=4>Autre</option>
</select>';
echo '<tr><td>Ranger par:
<td><select name="orderby"><option value=2>Compte, Tiers</option><option value=1>Tiers</option></select>';
/*
echo '<input type=hidden name="accounting_simplifiedid" value="-1">
<input type=hidden name="orderby" value="4">
<input type=hidden name="integrated" value="-1">
<input type=hidden name="journalid" value="-1">
<input type=hidden name="needreference" value="1">';*/
?>
<tr><td colspan="2" align="center">
<input type=hidden name="report" value="entryreport"><input type=hidden name="report" value="entryreport_thirdparty">
<input type=hidden name="adjustmentgroup_tagid" value=-1>
<input type="submit" value="Valider"></td></tr></table></form>

</table>