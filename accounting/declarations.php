<?php

echo '<table class="transparent"><tr><td><h2>Déclaration TVA</h2><p>Votre TVA est sur: ';
if ($_SESSION['ds_tva_encaissement'] == 1) { echo 'Encaissements'; }
elseif ($_SESSION['ds_tva_encaissement'] == 2) { echo 'Mixte'; }
else { echo 'Débit'; }
echo '<br>Régime: ';
if ($_SESSION['ds_tva_decl_type'] == 1) { echo 'Simplifié'; }
else { echo 'Réel'; }
echo '</p>';

# if the form below is changed, also change declaration.php
?>
<form method="post" action="declaration.php" target="_blank"><table>
<tr><td>Déclaration:<td>
<select name=report>
<?php if ($_SESSION['ds_tva_decl_type'] == 0) { ?>
<option value=1010>TVA 1010 (RÉGIME RÉEL)</option>
<?php } else { ?>
<option value=1020>TVA 1020 (RÉGIME SIMPLIFIÉ - ACOMPTE)</option>
<option value=1030>TVA 1030 (RÉGIME SIMPLIFIÉ - RÉCAPITULATIVE ANNUELLE)</option>
<?php } ?>
</select>
<?php
echo '<tr><td>Début :<td>';
$datename = 'startdate';
require('inc/datepicker.php');
echo '<tr><td>Fin :<td>';
$datename = 'stopdate';
require('inc/datepicker.php');
echo '<tr><td><td><select name="credit_or_reimburse"><option value=0>Crédit à reporter</option><option value=1>Remboursement demandé</option></select>';
echo '<tr><td>Prorata :<td><input type=text name="prorata">';
echo '<tr><td align=right><input type="checkbox" name="explain" value=1><td>Expliquer les calculs';
?>
<tr><td colspan="2" align="center">
<input type="submit" value="Valider"></td></tr></table></form>


<td width=30px><td width=50% valign=top><h2>Bilan</h2>
<form method="post" action="declaration.php" target="_blank"><table>
<tr><td>Déclaration:<td>
<select name=report>
<option value=impottransactions>Page garde</option>
<option value=ca1>Chiffre d'affaires, page 1</option>
<option value=ca2>Chiffre d'affaires, page 2</option>
<option value=relevecharges>Relevé détaillé des charges</option>
<option value=bilanentreprise>Bilan de l'entreprise</option>
<option value=compteresultat>Compte de Resultat</option>
</select>
<?php
$curyear = mb_substr($_SESSION['ds_curdate'],0,4);
echo '<tr><td>Année:<td><select name="year">';
for ($i_temp=$_SESSION['ds_startyear']; $i_temp <= $_SESSION['ds_endyear']; $i_temp++)
{
if ($i_temp == $curyear) { echo '<option value="' . $i_temp . '" SELECTED>' . $i_temp . '</option>'; }
else { echo '<option value="' . $i_temp . '">' . $i_temp . '</option>'; }
}
echo '</select>';
?>
<tr><td colspan="2" align="center">
<input type="submit" value="Valider"></table></form></table>

<?php
if ($_SESSION['ds_adminaccess'] == 1)
{
?>
<br><br><h2>Formulaires non-remplis</h2>
<form method="post" action="declaration.php" target="_blank"><table>
    <tr><td>Déclaration:<td>
        <select name=report>
          <option value=dicpcsts>CSTS</option>
          <option value=modelebilan>Modele bilan</option>
          <option value=modelecompteresultat>Modele compte résultat</option>
          <option value=revenuenonsalariescps>RNS</option>
          <option value=soldeintermediairegestion>Solde intermédiaire gestion</option>
          <option value=tableauemploiressource>Tableau emploi ressource</option>
          <option value=regularisation>Régularisation du prorata de déduction en fin d'exercice</option>
          <?php
          echo '<tr><td>Début:</td><td>';
          $datename = 'startdateautres';
          require('inc/datepicker.php');
          echo '</td></tr><tr><td>Fin:</td><td>';
          $datename = 'stopdateautres';
          require('inc/datepicker.php');
          echo '</td></tr><tr><td>';
          ?>
          <tr><td colspan="2" align="center">
              <input type="submit" value="Valider"></td></tr></table></form>
<?php
}
?>