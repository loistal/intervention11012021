<?php

require('preload/taxcode.php');
require('preload/accountingnumber.php');

$max_simplified_lines = 16;
$wasadded = 0;
$readonly = 0;

$PA['readme'] = 'uint';
$PA['for_bankstatement'] = 'uint';
$PA['use_adjustmentgroup_tag'] = 'uint';
$PA['a_sid'] = 'uint';
require('inc/readpost.php');

if (isset($_POST['a_sname']) && $_POST['a_sname'] != "" && !isset($_POST['a_sid']))
{
  $query = 'insert into accounting_simplified (accounting_simplifiedname) values (?)';
  $query_prm = array($_POST['a_sname']);
  require('inc/doquery.php');
  $a_sid = $query_insert_id;
  $wasadded = 1;
}

if ($a_sid > 0)
{
  $client = $_POST['clientbalance']; require('inc/findclient.php');
  $mainquery = 'update accounting_simplified set default_adjustmentcomment=?,default_reference=?,journalid=?,explanation=?
  ,inversedebit=?,inversedebit_title=?,accounting_simplifiedname=?,accounting_simplifiedgroupid=?,usebalanceline=?
  ,balanceline_accountingnumberid=?,balance_partyfill=?,deleted=?,linkto_accounting_simplifiedid=?,linkto_name=?
  ,for_bankstatement=?,use_adjustmentgroup_tag=?';
  $query_prm_main = array($_POST['default_adjustmentcomment']
  ,$_POST['default_reference']
  ,$_POST['journalid']
  , $_POST['explanation']
  , $_POST['inversedebit']
  , $_POST['inversedebit_title']
  , $_POST['a_sname']
  , $_POST['accounting_simplifiedgroupid']
  , (int) $_POST['usebalanceline']
  , $_POST['accountingnumber0id']
  , (int) $clientid
  , (int) $_POST['deleted']
  , $_POST['accounting_simplifiedlinkid']
  , $_POST['linkto_name']
  , $for_bankstatement,$use_adjustmentgroup_tag);
  for ($i = 1; $i <= $max_simplified_lines; $i++)
  {
    ### verifying "choices", should be space separated list of accounts and title
    $choiceline_error[$i] = 0;
    if ($_POST['line'.$i.'_choices'] == '*')
    {
      $choiceline = $_POST['line'.$i.'_choices'];
    }
    else
    {
      $choiceline = preg_replace( '/\s+/', ' ', trim($_POST['line'.$i.'_choices'])); # remove excess whitespace
      if ($choiceline != '')
      {
        $choice_partA = explode(" ", $choiceline);
        foreach ($choice_partA as $id => $part)
        {
          #echo $id . ' '.$part;
          if ($id%2 == 0)
          {
            #echo ' need to check account';
            if (in_array($part, $accountingnumberA))
            {
              #echo 'found it ok!';
            }
            else { $choiceline_error[$i] = 1; $choiceline = ''; }
          }
          #echo '<br>';
        }
        if ($id < 2 || $id%2 == 0) { $choiceline_error[$i] = 1; $choiceline = ''; }
      }
    }
    ###
    $client = $_POST['client'.$i]; require('inc/findclient.php');
    $mainquery .= ',line'.$i.'_readonly=?,line'.$i.'_choices=?,line'.$i.'_show=?,line'.$i.'_title=?,line'.$i.'_debit=?,line'.$i.'_accountingnumberid=?,line'.$i.'_partyfill=?,line'.$i.'_vatcalc=?';
    array_push($query_prm_main
    , ($_POST['line'.$i.'_readonly']+0)
    , $choiceline
    , ($_POST['line'.$i.'_show']+0)
    , $_POST['line'.$i.'_title']
    , $_POST['line'.$i.'_debit']
    , $_POST['accountingnumber'.$i.'id']
    , (int) $clientid
    , $_POST['line'.$i.'_vatcalc']);
  }
  $query = $mainquery . ' where accounting_simplifiedid=?'; array_push($query_prm_main, $a_sid);
  $query_prm = $query_prm_main;
  require('inc/doquery.php');
  if ($wasadded == 1) { echo '<p>Modèle compta simplifiée ' . d_output($_POST['a_sname']) . ' ajouté.</p>'; }
  else { echo '<p>Modèle compta simplifiée ' . d_output($_POST['a_sname']) . ' modifié.</p>'; }
}
if ($readme)
{
  if (isset($_GET['a_sid']) && $_GET['a_sid'] > 0) { $a_sid = (int) $_GET['a_sid']; }
  else { $a_sid = $_POST['accounting_simplifiedid']; }
}
if ($a_sid > 0)
{
  $query = 'select * from accounting_simplified where accounting_simplifiedid=?';
  $query_prm = array($a_sid);
  require('inc/doquery.php');
  $row = $query_result[0];
  $query = 'select adjustmentgroupid from adjustmentgroup where adjustmentgroup.accounting_simplifiedid=? limit 1';
  $query_prm = array($a_sid);
  require('inc/doquery.php');
  #if ($num_results) { $readonly = 1; } 2020 10 27 allowing modification of all fields
  if ($num_results)
  { echo '<p class=alert>Ce modèle est en utilisation.</p><br>'; }
  echo '<h2>Modifier modèle compta simplifiée '.d_output($row['accounting_simplifiedname']).'</h2>';
  if ($readonly)
  {
    echo '<p class=alert>Ce modèle est utilisé. Certains champs ne peuvent être modifiés.</p>';
  }
}
else { echo '<h2>Ajouter modèle compta simplifiée</h2>'; }

?>
<form method="post" action="accounting.php"><table>
<tr><td>Description :<td><input autofocus type="text" STYLE="text-align:right" name="a_sname" size=40
<?php if (isset($row['accounting_simplifiedname'])) { echo ' value="'.$row['accounting_simplifiedname'].'"'; } ?>>
<tr><td>Explication :<td><input type="text" STYLE="text-align:right" name="explanation" size=40
<?php if (isset($row['explanation'])) { echo ' value="'.$row['explanation'].'"'; } ?>>
<tr><td>Libellé par défaut :</td><td><input type="text" STYLE="text-align:right" name="default_adjustmentcomment" size=40
<?php if (isset($row['default_adjustmentcomment'])) { echo ' value="'.$row['default_adjustmentcomment'].'"'; } ?>>
<tr><td>Référence par défaut :</td><td><input type="text" STYLE="text-align:right" name="default_reference" size=40
<?php if (isset($row['default_reference'])) { echo ' value="'.$row['default_reference'].'"'; } ?>>
<?php
$dp_itemname = 'journal'; $dp_description = 'Journal';
if (isset($row['journalid'])) { $dp_selectedid = $row['journalid']; }
require('inc/selectitem.php');
$dp_itemname = 'accounting_simplifiedgroup'; $dp_description = 'Groupe'; $dp_noblank = 1;
if (isset($row['accounting_simplifiedgroupid'])) { $dp_selectedid = $row['accounting_simplifiedgroupid']; }
require('inc/selectitem.php');
?>
<?php
echo '<tr><td><input type="checkbox" name="usebalanceline" value=1';
if (isset($row['usebalanceline']) && $row['usebalanceline'] == 1) { echo ' checked'; }
if ($readonly) { echo ' disabled="disabled"'; }
echo '>'; if ($readonly && $row['usebalanceline'] == 1) { echo '<input type=hidden name="usebalanceline" value=1>'; }
echo ' "Compte Balance"';
if ($readonly) { $dp_readonly = 1; }
$dp_itemname = 'accountingnumber'; $dp_noblank = 1; $dp_addtoid = 0; $dp_long = 1;
if (isset($row['balanceline_accountingnumberid'])) { $dp_selectedid = $row['balanceline_accountingnumberid']; }
require('inc/selectitem.php');
echo ' &nbsp; Tiers pré-rempli: '; $dp_nodescription = 1; $dp_addtoid = 'balance';
if (isset($row['balance_partyfill'])) { $client = $row['balance_partyfill']; }
if (!isset($client) || $client == 0) { $client = ''; }
require('inc/selectclient.php');
echo '</td></tr>';

echo '<tr><td><input type="checkbox" name="inversedebit" value=1';
if (isset($row['inversedebit']) && $row['inversedebit'] == 1) { echo ' checked'; }
if ($readonly) { echo ' disabled="disabled"'; }
echo '>'; if ($readonly && isset($row['inversedebit']) && $row['inversedebit'] == 1) { echo '<input type=hidden name="inversedebit" value=1>'; }
echo ' "Avoir"</td><td><input type="text" STYLE="text-align:right" name="inversedebit_title" size=40';
if (isset($row['inversedebit_title'])) { echo ' value="' . $row['inversedebit_title'] . '"'; }
echo '>';

echo '<tr><td>"Link to"<sup>***</sup>';
$dp_itemname = 'accounting_simplified'; $dp_addtoid = 'link'; $dp_long = 1;
if (isset($row['linkto_accounting_simplifiedid'])) { $dp_selectedid = $row['linkto_accounting_simplifiedid']; }
require('inc/selectitem.php');
echo ' &nbsp; Texte: <input type="text" STYLE="text-align:right" name="linkto_name" size=40';
if (isset($row['linkto_name'])) { echo ' value="' . $row['linkto_name'] . '"'; }
echo '>';

echo '<tr><td align=right><input type="checkbox" name="for_bankstatement" value=1';
if (isset($row['for_bankstatement']) && $row['for_bankstatement'] == 1) { echo ' checked'; }
echo '><td>Utiliser ce modèle pour Relevés bancaires';

echo '<tr><td align=right><input type="checkbox" name="use_adjustmentgroup_tag" value=1';
if (isset($row['use_adjustmentgroup_tag']) && $row['use_adjustmentgroup_tag'] == 1) { echo ' checked'; }
echo '><td>Utiliser le Tag Compta : '.d_output($_SESSION['ds_term_accounting_tag']);

if ($a_sid > 0)
{
  echo '<tr><td align=right><input type="checkbox" name="deleted" value=1';
  if ($row['deleted'] == 1) { echo ' checked'; }
  echo '><td>';
  if ($row['deleted'] == 1) { echo 'Supprimé'; } else { echo 'Supprimer'; }
}
echo '<tr><td colspan=2><table class=report><thead><th><th>Compte</th><th>D / C</th><th>Titre</th><th>Tiers pré-rempli</th><th>Calcul TVA<sup>*</sup></th><th>Lecture seule</th></thead>';
for ($i = 1; $i <= $max_simplified_lines; $i++)
{
  echo '<tr><td align=right><input type=checkbox name="line'.$i.'_show" value=1 ';
  if (isset($row['line'.$i.'_show']) && $row['line'.$i.'_show'] == 1) { echo 'checked'; }
  if ($readonly) { echo ' disabled="disabled"'; }
  echo '></td>';
  if ($readonly && $row['line'.$i.'_show'] == 1) { echo '<input type=hidden name="line'.$i.'_show" value=1>'; }
  if ($readonly) { $dp_readonly = 1; }
  $dp_itemname = 'accountingnumber'; $dp_noblank = 1; $dp_addtoid = $i; $dp_long = 1;
  if (isset($row['line'.$i.'_accountingnumberid'])) { $dp_selectedid = $row['line'.$i.'_accountingnumberid']; }
  require('inc/selectitem.php');
  echo '</td><td><select name="line'.$i.'_debit"'; if ($readonly) { echo ' disabled'; }
  echo '><option value=1>Débit</option><option value=0';
  if ($row['line'.$i.'_debit'] == 0) { echo ' selected'; }
  echo '>Crédit</option></select></td>';
  if ($readonly) { echo '<input type=hidden name="line'.$i.'_debit" value="' . $row['line'.$i.'_debit'] . '">'; }
  echo '<td><input type="text" STYLE="text-align:right" name="line'.$i.'_title"';
  if (isset($row['line'.$i.'_title'])) { echo ' value="' . d_input($row['line'.$i.'_title']) . '"'; }
  echo ' size=40>';
  echo '<td>'; $dp_nodescription = 1; $dp_addtoid = $i;
  if (isset($row['line'.$i.'_partyfill'])) { $client = $row['line'.$i.'_partyfill']; }
  if (!isset($client) || $client == 0) { $client = ''; }
  require('inc/selectclient.php');
  echo '<td><select name="line'.$i.'_vatcalc"><option value=0></option><option value=9001'; if ($row['line'.$i.'_vatcalc'] == 9001) { echo ' selected'; }
  echo '>TTC</option><option value=9002'; if ($row['line'.$i.'_vatcalc'] == 9002) { echo ' selected'; }
  echo '>HT</option><option value=9003'; if ($row['line'.$i.'_vatcalc'] == 9003) { echo ' selected'; }
  echo '>Sans Taxe</option><option value=9100'; if ($row['line'.$i.'_vatcalc'] == 9100) { echo ' selected'; }
  echo '>Somme</option>';
  foreach ($taxcodeA as $taxcodeid => $taxcode)
  {
    if ($taxcode > 0 && $taxcode_deletedA[$taxcodeid] == 0)
    {
      echo '<option value='.$taxcodeid;
      if ($row['line'.$i.'_vatcalc'] == $taxcodeid) { echo ' selected'; }
      echo '>'.$taxcode.'</option>';
    }
  }
  echo '</select>';
  echo '<td align=right><input type=checkbox name="line'.$i.'_readonly" value=1 ';
  if (isset($row['line'.$i.'_readonly']) && $row['line'.$i.'_readonly'] == 1) { echo 'checked'; }
  echo '>';
  if ((!isset($row['line'.$i.'_choices']) || $row['line'.$i.'_choices'] == '') && isset($_POST['line'.$i.'_choices']))
  { $row['line'.$i.'_choices'] = $_POST['line'.$i.'_choices']; }
  echo '<tr><td colspan=10> &nbsp; &nbsp; Choix des comptes<sup>**</sup>: <input type="text"';
  if (isset($choiceline_error[$i]) && $choiceline_error[$i] == 1) { echo ' STYLE="color: red"'; }
  echo '  name="line'.$i.'_choices"';
  if (isset($row['line'.$i.'_choices'])) { echo ' value="' . d_input($row['line'.$i.'_choices']) . '"'; }
  echo ' size=100>';
}
if ($a_sid > 0) { echo '<input type=hidden name="a_sid" value="' . $a_sid . '">'; }
?></table></td></tr>
<tr><td colspan=2><sup>*</sup> Pour le calcul de TVA il faut soit:<br>a) les 3 champs TVA, HT et TTC<br>b) les 4 champs TVA, HT, sans taxe et TTC<br>c) juste le champs Somme
<tr><td colspan=2><sup>**</sup> Lister comptes + titres séparés par espace, exemple: <i>215400 Alpha 281800 Beta 443800 Gamma (ou * pour tous comptes)</i>
<tr><td colspan=2><sup>***</sup> Définir les 2 champs + avoir un seul compte avec Tiers sur les 2 modèles (un seul champs pour valeur/montant sur modèle cible)
<tr><td colspan="2" align="center"><input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>"><input type="submit" value="Valider"></td></tr>
</table></form>