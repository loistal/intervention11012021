<?php
if (isset($_POST['term_accountingdate']))
{
  $query = 'update globalterms set term_invoicetag2=?,term_custominvoicedate=?,term_accounting_comment=?,term_accounting_reference=?,term_paymfield1=?,term_paymfield2=?,term_clientactionfield1=?,term_localvessel=?,term_field1=?,term_field2=?,term_prixalternatif=?,term_invoicenotice=?,term_clientemployee1=?,term_clientemployee2=?,term_servedby=?,term_accountingdate=?,term_deliverydate=?,term_extraname=?,term_reference=?,term_invoicetag=?,term_productsubunit=?,term_manager=?,term_employeedepartment=?,term_employeesection=? where primaryunique=1';
  $query_prm = array($_POST['term_invoicetag2'],$_POST['term_custominvoicedate'],$_POST['term_accounting_comment'],$_POST['term_accounting_reference'],$_POST['term_paymfield1'],$_POST['term_paymfield2'],$_POST['term_clientactionfield1'],$_POST['term_localvessel'],$_POST['term_field1'],$_POST['term_field2'],$_POST['term_prixalternatif'],$_POST['term_invoicenotice'],$_POST['term_clientemployee1'],$_POST['term_clientemployee2'],$_POST['term_servedby'],$_POST['term_accountingdate'],$_POST['term_deliverydate'],$_POST['term_extraname'],$_POST['term_reference'],$_POST['term_invoicetag'],$_POST['term_productsubunit'],$_POST['term_manager'],$_POST['term_employeedepartment'],$_POST['term_employeesection']);   
  require('inc/doquery.php');
  
  $query = 'update globalterms set term_client_customdate1=?,term_client_customdate2=?,term_client_customdate3=?,term_productactiontag=?,term_productactionfield1=?,term_clientfield1=?,term_clientfield2=?,term_clientfield3=?,term_clientfield4=?,term_clientfield5=?,term_clientfield6=?,term_discontinued=?,term_clientcategory=?,term_clientcategory2=?,term_clientcategory3=? where primaryunique=1';
  $query_prm = array($_POST['term_client_customdate1'],$_POST['term_client_customdate2'],$_POST['term_client_customdate3'],$_POST['term_productactiontag'],$_POST['term_productactionfield1'],$_POST['term_clientfield1'],$_POST['term_clientfield2'],$_POST['term_clientfield3'],$_POST['term_clientfield4'],$_POST['term_clientfield5'],$_POST['term_clientfield6'],$_POST['term_discontinued'],$_POST['term_clientcategory'],$_POST['term_clientcategory2'],$_POST['term_clientcategory3']);
  require('inc/doquery.php');
  
  $query = 'update globalterms set term_accounting_tag=?,term_invoice_priceoption1=?,term_invoice_priceoption2=?,term_invoice_priceoption3=?,term_invoice=?,term_client_telephone=?,term_client_cellphone=?,term_client_telephone3=?,term_client_telephone4=?,term_client_email=?,term_client_email2=?,term_client_email3=?,term_client_email4=? where primaryunique=1';
  $query_prm = array($_POST['term_accounting_tag'],$_POST['term_invoice_priceoption1'],$_POST['term_invoice_priceoption2'],$_POST['term_invoice_priceoption3'],$_POST['term_invoice'],$_POST['term_client_telephone'],$_POST['term_client_cellphone'],$_POST['term_client_telephone3'],$_POST['term_client_telephone4'],$_POST['term_client_email'],$_POST['term_client_email2'],$_POST['term_client_email3'],$_POST['term_client_email4']);
  require('inc/doquery.php');

  $query = 'update globalterms set term_interventionfield1=?,term_interventionfield2=?,term_interventionfield3=?,term_interventionfield4=?, term_intervention_tag1=?, term_intervention_tag2=?, term_intervention_value1=?, term_intervention_value2=?, term_intervention_value3=?, term_intervention_value4=? where primaryunique=1';
  $query_prm = array($_POST['term_interventionfield1'],$_POST['term_interventionfield2'],$_POST['term_interventionfield3'],$_POST['term_interventionfield4'],$_POST['term_intervention_tag1'],$_POST['term_intervention_tag2'],$_POST['term_intervention_value1'],$_POST['term_intervention_value2'],$_POST['term_intervention_value3'],$_POST['term_intervention_value4'],);
  require('inc/doquery.php');

  echo '<p>Termes globaux enregistrés.</p>';
}

echo '<h2>Termes globaux</h2>';
$query = 'select * from globalterms where primaryunique=1';
$query_prm = array();
require('inc/doquery.php');
$row = $query_result[0];
echo '<form method="post" action="system.php"><table>';
echo '<tr><td>Facture:</td><td><input type="text" STYLE="text-align:right" name="term_invoice" value="' . $row['term_invoice'] . '" size=30></td></tr>';
echo '<tr><td>Date comptable (facture):</td><td><input type="text" STYLE="text-align:right" name="term_accountingdate" value="' . $row['term_accountingdate'] . '" size=30></td></tr>';
echo '<tr><td>Date livraison (facture):</td><td><input type="text" STYLE="text-align:right" name="term_deliverydate" value="' . $row['term_deliverydate'] . '" size=30></td></tr>';
echo '<tr><td>Date optionnel (facture):</td><td><input type="text" STYLE="text-align:right" name="term_custominvoicedate" value="' . $row['term_custominvoicedate'] . '" size=30></td></tr>';
echo '<tr><td>Extension du nom (facture):</td><td><input type="text" STYLE="text-align:right" name="term_extraname" value="' . $row['term_extraname'] . '" size=30></td></tr>';
echo '<tr><td>Référence à afficher (facture):</td><td><input type="text" STYLE="text-align:right" name="term_reference" value="' . $row['term_reference'] . '" size=30></td></tr>';
echo '<tr><td>Tag (facture):</td><td><input type="text" STYLE="text-align:right" name="term_invoicetag" value="' . $row['term_invoicetag'] . '" size=30></td></tr>';
echo '<tr><td>Tag 2 (facture):</td><td><input type="text" STYLE="text-align:right" name="term_invoicetag2" value="' . $row['term_invoicetag2'] . '" size=30></td></tr>';
echo '<tr><td>Servi par:</td><td><input type="text" STYLE="text-align:right" name="term_servedby" value="' . $row['term_servedby'] . '" size=30></td></tr>';
echo '<tr><td>Employee client 1:</td><td><input type="text" STYLE="text-align:right" name="term_clientemployee1" value="' . $row['term_clientemployee1'] . '" size=30></td></tr>';
echo '<tr><td>Employee client 2:</td><td><input type="text" STYLE="text-align:right" name="term_clientemployee2" value="' . $row['term_clientemployee2'] . '" size=30></td></tr>';
echo '<tr><td>Bon:</td><td><input type="text" STYLE="text-align:right" name="term_invoicenotice" value="' . $row['term_invoicenotice'] . '" size=30></td></tr>';
echo '<tr><td>Prix Alternatif:</td><td><input type="text" STYLE="text-align:right" name="term_prixalternatif" value="' . $row['term_prixalternatif'] . '" size=30></td></tr>';
echo '<tr><td>Discontinued:</td><td><input type="text" STYLE="text-align:right" name="term_discontinued" value="' . $row['term_discontinued'] . '" size=30></td></tr>';
echo '<tr><td>Bateau de livraison:</td><td><input type="text" STYLE="text-align:right" name="term_localvessel" value="' . $row['term_localvessel'] . '" size=30></td></tr>';
echo '<tr><td>Champs optionnel sur facture 1:</td><td><input type="text" STYLE="text-align:right" name="term_field1" value="' . $row['term_field1'] . '" size=30></td></tr>';
echo '<tr><td>Champs optionnel sur facture 2:</td><td><input type="text" STYLE="text-align:right" name="term_field2" value="' . $row['term_field2'] . '" size=30> &nbsp; Ne pas utiliser avec factures échelonnées';
echo '<tr><td>Champs optionnel sur paiement 1:</td><td><input type="text" STYLE="text-align:right" name="term_paymfield1" value="' . $row['term_paymfield1'] . '" size=30></td></tr>';
echo '<tr><td>Champs optionnel sur paiement 2:</td><td><input type="text" STYLE="text-align:right" name="term_paymfield2" value="' . $row['term_paymfield2'] . '" size=30></td></tr>';
echo '<tr><td>Option des Prix 1:<td><input type="text" STYLE="text-align:right" name="term_invoice_priceoption1" value="' . $row['term_invoice_priceoption1'] . '" size=30>';
echo '<tr><td>Option des Prix 2:<td><input type="text" STYLE="text-align:right" name="term_invoice_priceoption2" value="' . $row['term_invoice_priceoption2'] . '" size=30>';
echo '<tr><td>Option des Prix 3:<td><input type="text" STYLE="text-align:right" name="term_invoice_priceoption3" value="' . $row['term_invoice_priceoption3'] . '" size=30>';
echo '<tr><td>Champs optionnel sur évènement:</td><td><input type="text" STYLE="text-align:right" name="term_clientactionfield1" value="' . $row['term_clientactionfield1'] . '" size=30></td></tr>';
echo '<tr><td>Champs optionnel sur évènement produit:</td><td><input type="text" STYLE="text-align:right" name="term_productactionfield1" value="' . $row['term_productactionfield1'] . '" size=30></td></tr>';
echo '<tr><td>Tag évènement produit:</td><td><input type="text" STYLE="text-align:right" name="term_productactiontag" value="' . $row['term_productactiontag'] . '" size=30></td></tr>';
echo '<tr><td>(Compta) Libellé:</td><td><input type="text" STYLE="text-align:right" name="term_accounting_comment" value="' . $row['term_accounting_comment'] . '" size=30></td></tr>';
echo '<tr><td>(Compta) Référence:</td><td><input type="text" STYLE="text-align:right" name="term_accounting_reference" value="' . $row['term_accounting_reference'] . '" size=30></td></tr>';
echo '<tr><td>(Compta) Tag:</td><td><input type="text" STYLE="text-align:right" name="term_accounting_tag" value="' . $row['term_accounting_tag'] . '" size=30>';
$productsubunit = $row['term_productsubunit'];  
if($productsubunit == '')
{
  $productsubunit = d_trad('units');
}
echo '<tr><td>' . d_trad('productsubunits:') . '</td><td><input type="text" STYLE="text-align:right" name="term_productsubunit" value="' . $productsubunit . '" size=30></td></tr>';
echo '<tr><td colspan=2>&nbsp;
<tr><td colspan=2><b>Fiche client
<tr><td>Champs optionnel sur client 1:</td><td><input type="text" STYLE="text-align:right" name="term_clientfield1" value="' . $row['term_clientfield1'] . '" size=30>
<tr><td>Champs optionnel sur client 2:</td><td><input type="text" STYLE="text-align:right" name="term_clientfield2" value="' . $row['term_clientfield2'] . '" size=30>
<tr><td>Champs optionnel sur client 3:</td><td><input type="text" STYLE="text-align:right" name="term_clientfield3" value="' . $row['term_clientfield3'] . '" size=30>
<tr><td>Champs optionnel sur client 4:</td><td><input type="text" STYLE="text-align:right" name="term_clientfield4" value="' . $row['term_clientfield4'] . '" size=30>
<tr><td>Champs optionnel sur client 5:</td><td><input type="text" STYLE="text-align:right" name="term_clientfield5" value="' . $row['term_clientfield5'] . '" size=30>
<tr><td>Champs optionnel sur client 6:</td><td><input type="text" STYLE="text-align:right" name="term_clientfield6" value="' . $row['term_clientfield6'] . '" size=30>
<tr><td>Date optionnelle sur client 1:</td><td><input type="text" STYLE="text-align:right" name="term_client_customdate1" value="' . $row['term_client_customdate1'] . '" size=30>
<tr><td>Date optionnelle sur client 2:</td><td><input type="text" STYLE="text-align:right" name="term_client_customdate2" value="' . $row['term_client_customdate2'] . '" size=30>
<tr><td>Date optionnelle sur client 3:</td><td><input type="text" STYLE="text-align:right" name="term_client_customdate3" value="' . $row['term_client_customdate3'] . '" size=30>
<tr><td>Téléphone 1:</td><td><input type="text" STYLE="text-align:right" name="term_client_telephone" value="' . $row['term_client_telephone'] . '" size=30>
<tr><td>Téléphone 2:</td><td><input type="text" STYLE="text-align:right" name="term_client_cellphone" value="' . $row['term_client_cellphone'] . '" size=30>
<tr><td>Téléphone 3:</td><td><input type="text" STYLE="text-align:right" name="term_client_telephone3" value="' . $row['term_client_telephone3'] . '" size=30>
<tr><td>Téléphone 4:</td><td><input type="text" STYLE="text-align:right" name="term_client_telephone4" value="' . $row['term_client_telephone4'] . '" size=30>
<tr><td>Email 1:</td><td><input type="text" STYLE="text-align:right" name="term_client_email" value="' . $row['term_client_email'] . '" size=30>
<tr><td>Email 2:</td><td><input type="text" STYLE="text-align:right" name="term_client_email2" value="' . $row['term_client_email2'] . '" size=30>
<tr><td>Email 3:</td><td><input type="text" STYLE="text-align:right" name="term_client_email3" value="' . $row['term_client_email3'] . '" size=30>
<tr><td>Email 4:</td><td><input type="text" STYLE="text-align:right" name="term_client_email4" value="' . $row['term_client_email4'] . '" size=30>';
echo '<tr><td>Catégorie client:</td><td><input type="text" STYLE="text-align:right" name="term_clientcategory" value="' . $row['term_clientcategory'] . '" size=30></td></tr>';
echo '<tr><td>Catégorie client 2:</td><td><input type="text" STYLE="text-align:right" name="term_clientcategory2" value="' . $row['term_clientcategory2'] . '" size=30></td></tr>';
echo '<tr><td>Catégorie client 3:</td><td><input type="text" STYLE="text-align:right" name="term_clientcategory3" value="' . $row['term_clientcategory3'] . '" size=30></td></tr>';

echo '<tr><td colspan=2>&nbsp;
<tr><td colspan=2><b>Interventions</b>
<tr><td>Champ optionnel 1:</td><td><input type="text" STYLE="text-align:right" name="term_interventionfield1" value="' . $row['term_interventionfield1'] . '" size=30>
<tr><td>Champ optionnel 2:</td><td><input type="text" STYLE="text-align:right" name="term_interventionfield2" value="' . $row['term_interventionfield2'] . '" size=30>
<tr><td>Champ optionnel 3:</td><td><input type="text" STYLE="text-align:right" name="term_interventionfield3" value="' . $row['term_interventionfield3'] . '" size=30>
<tr><td>Champ optionnel 4:</td><td><input type="text" STYLE="text-align:right" name="term_interventionfield4" value="' . $row['term_interventionfield4'] . '" size=30>
<tr><td>Tag optionnel 1:</td><td><input type="text" STYLE="text-align:right" name="term_intervention_tag1" value="' . $row['term_intervention_tag1'] . '" size=30>
<tr><td>Tag optionnel 2:</td><td><input type="text" STYLE="text-align:right" name="term_intervention_tag2" value="' . $row['term_intervention_tag2'] . '" size=30>
<tr><td>Valeur optionnelle 1:</td><td><input type="text" STYLE="text-align:right" name="term_intervention_value1" value="' . $row['term_intervention_value1'] . '" size=30>
<tr><td>Valeur optionnelle 2:</td><td><input type="text" STYLE="text-align:right" name="term_intervention_value2" value="' . $row['term_intervention_value2'] . '" size=30>
<tr><td>Valeur optionnelle 3:</td><td><input type="text" STYLE="text-align:right" name="term_intervention_value3" value="' . $row['term_intervention_value3'] . '" size=30>
<tr><td>Valeur optionnelle 4:</td><td><input type="text" STYLE="text-align:right" name="term_intervention_value4" value="' . $row['term_intervention_value4'] . '" size=30>';
echo '<tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="systemmenu" value="' . $systemmenu . '"><input type="submit" value="Valider"></td></tr></table></form>';

?>