<?php

# TODO refactor, remove currentstep

switch($currentstep)
{

  # 
  case 0:
  echo '<h2>Options globales</h2>';

  $query = 'select * from globalvariables where primaryunique=1';
  $query_prm = array();
  require('inc/doquery.php');
  $row = $query_result[0];
  echo '<form method="post" action="system.php"><table class=report>';

  echo '<tr><td>Activer le module RH seulement :</td><td><input type="checkbox" name="standalone_hr" value="1"';
  if ($row['standalone_hr']) { echo ' checked'; }
  echo '></td></tr>'; # TODO remove this option
  echo '<tr><td>Nom de l\'entreprise (menu personnalisé):</td><td><input type="text" STYLE="text-align:right" name="customname" value="' . $row['customname'] . '" size=30></td></tr>';
  echo '<tr><td>Devise:</td><td><select name=currencyname>';
  echo '<option value="XPF"'; if ($row['currencyname'] == "XPF") { echo ' selected'; }
  echo '>XPF</option>';
  #echo '<option value="EUR"'; if ($row['currencyname'] == "EUR") { echo ' selected'; }
  #echo '>EUR</option>';
  #echo '</select></td></tr>';
  echo '<tr><td>Limiter toutes dates - Date de début :</td><td><input type="text" STYLE="text-align:right" name="startyear" value="' . $row['startyear'] . '" size=30></td></tr>';
  echo '<tr><td>Limiter toutes dates - Date de fin :</td><td><input type="text" STYLE="text-align:right" name="endyear" value="' . $row['endyear'] . '" size=30></td></tr>';
  echo '<tr><td>Activer le module SOFIX :</td><td><input type="checkbox" name="usesofix" value="1"';
  if ($row['usesofix']) { echo ' checked'; }
  echo '></td></tr>';
  /*
  echo '<tr><td>Activer le module Livraison :</td><td><input type="checkbox" name="usedelivery" value="1"';
  if ($row['usedelivery']) { echo ' checked'; }
  echo '></td></tr>';
  */
  echo '<tr><td>Activer le module Livraison :</td><td><select name="usedelivery">
  <option value=0>Non</option>
  <option value=1'; if ($row['usedelivery'] == 1) { echo ' selected'; } echo '>Oui</option>
  <option value=2'; if ($row['usedelivery'] == 2) { echo ' selected'; } echo '>Par ligne</option>
  </select>';
  
  echo '<tr><td>Time management :<td><select name="time_management"><option value=0>Manuel</option><option value=1';
  if ($row['time_management'] == 1) { echo ' selected'; }
  echo '>BioStar</option></select>';
  
  echo '<tr><td>Afficher date et heure:</td><td><input type="checkbox" name="displaydateandtime" value="1"';
  if ($row['displaydateandtime']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Activer le contrôle de la connexion des utilisateurs :<br>
TEM vérifie l\'heure de connexion des utilisateurs (jours et horaires autorisés)
</td><td><input type="checkbox" name="checktimes" value="1"';
  if ($row['checktimes']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Maximum de lignes recherchées :<br>
Lors d\'une recherche, le nombre maximum de lignes recherchées est limité
</td><td><input type="text" STYLE="text-align:right" name="maxresults" value="' . $row['maxresults'] . '" size=10></td></tr>';
  echo '<tr><td>Contrôle des doublons (dans le cas d\'export vers SAGE) :<br>
TEM fait un contrôle sur les lignes déjà exportées<br>(nécessite opération dans la base de données - contacter TEM avant de cocher)
</td><td><input type="checkbox" name="exportfields" value="1"';
  if ($row['exportfields']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Limiter la recherche dans le menu déroulant :<br>
Le menu déroulant propose uniquement les termes (produit/client/fournisseur)<br> qui commencent par les caractères que vous aurez renseigné
</td><td><input type="checkbox" name="autocompleteoption" value="1"';
  if ($row['autocompleteoption']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Utiliser Points de fidelité
</td><td><input type="checkbox" name="use_loyalty_points" value="1"';
  if ($row['use_loyalty_points']) { echo ' checked'; }
  echo '></td></tr>';

  echo '<tr><td colspan=2>&nbsp;</td></tr><tr><td align=center colspan=2><b>Factures / Vente</td></tr>';
  echo '<tr><td>Signature électronique<td><input type="checkbox" name="use_invoice_sig" value="1"';
  if ($row['use_invoice_sig']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Regroupement manuel des lignes<td><input type="checkbox" name="use_invoiceitemgroup" value="1"';
  if ($row['use_invoiceitemgroup']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Globaliser TVA sur facture (attention, la TVA par ligne serait incorrecte)</td><td><input type="checkbox" name="globalise_vat" value="1"';
  if ($row['globalise_vat']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Déductions</td><td><input type="checkbox" name="invoicedeductions" value="1"';
  if ($row['invoicedeductions']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Autoriser la modification de la date d\'échéance :</td><td><input type="checkbox" name="paybydateselect" value="1"';
  if ($row['paybydateselect']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Activer le partage des factures :<br>
Vous autorisez la visualisation des factures partagées sans mot de passe
</td><td><input type="checkbox" name="allowinvoiceshare" value="1"';
  if ($row['allowinvoiceshare']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Modèle facture:</td><td><select name=invoicetemplate>';
  echo '<option value=1'; if ($row['invoicetemplate'] == 1) { echo ' selected'; }
  echo '>1: Basique</option>';
  echo '<option value=2'; if ($row['invoicetemplate'] == 2) { echo ' selected'; }
  echo '>2: Classique</option>';
  echo '<option value=6'; if ($row['invoicetemplate'] == 6) { echo ' selected'; }
  echo '>6: 2017</option>';
  echo '<option value=7'; if ($row['invoicetemplate'] == 7) { echo ' selected'; }
  echo '>7: Standard</option>';
  echo '</select></td></tr>';
  echo '<tr><td>Afficher le numéro de série :</td><td><input type="checkbox" name="useserialnumbers" value="1"';
  if ($row['useserialnumbers']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Activer l\'alerte popup impayé :<br>
Au moment de la saisie d\'une facture, si le client est en impayé, une popup d\'alerte apparaît
</td><td><input type="checkbox" name="badpayeralert" value="1"';
  if ($row['badpayeralert']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>"Vérifier la date lors de la saisie d\'une facture ou d\'un paiement :<br>
Les limiter au présent et futur
</td><td><input type="checkbox" name="noretrodates" value="1"';
  if ($row['noretrodates']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Afficher uniquement la facture personnalisée :</td><td><input type="checkbox" name="hidedefaultinvoice" value="1"';
  if ($row['hidedefaultinvoice']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Activer la saisie du paiement après la saisie d\'une facture :</td><td><input type="checkbox" name="invoicedirecttopayment" value="1"';
  if ($row['invoicedirecttopayment']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Autoriser la saisie d\'un prix "libre" pour les avoirs :<br>
Lors de la saisie d\'un avoir, il est possible de mettre un prix différent de celui de la facture de départ
</td><td><input type="checkbox" name="returnproductsaregeneric" value="1"';
  if ($row['returnproductsaregeneric']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Afficher les informations supplémentaires à chaque ligne produit :
</td><td><input type="checkbox" name="useitemadd" value="1"';
  if ($row['useitemadd']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Afficher le prix de détail reglementé :</td><td><input type="checkbox" name="useretailprice" value="1"';
  if ($row['useretailprice']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Connaissement :</td><td><select name="uselocalbol">';
  echo '<option value=0>Non</option>';
  echo '<option value=1'; if ($row['uselocalbol'] == 1) { echo ' selected'; } echo '>Oui</option>';
  echo '<option value=2'; if ($row['uselocalbol'] == 2) { echo ' selected'; } echo '>Integré dans la facture</option>';
  echo '</select>';
  echo '<tr><td>Nombre de lignes par défaut :</td><td><input type="text" STYLE="text-align:right" name="invoicelines" value="' . $row['invoicelines'] . '" size=30></td></tr>';
  echo '<tr><td>Client par défaut:</td><td><input type="text" STYLE="text-align:right" name="defaultclientid" value="' . $row['defaultclientid'] . '" size=30></td></tr>';
  echo '<tr><td>Infos haut:<br>Votre adresse et numéro Tahiti doivent figurer soit dans Infos haut, soit dans Infos bas.</td><td><textarea cols=80 rows=5 name="companyinfo" size=80>' . $row['companyinfo'] . '</textarea></td></tr>';
  echo '<tr><td>Infos bas:<br>(Conditions d\'escompte; taux de pénalités, indemnité forfaitaire pour frais de recouvrement)</td><td><textarea cols=80 rows=5 name="infofact" size=80>' . $row['infofact'] . '</textarea></td></tr>';
  echo '<tr><td>Infos devis:<br><td><textarea cols=80 rows=5 name="quote_info" size=80>' . $row['quote_info'] . '</textarea>';
  echo '<tr><td>Commentaires Proforma par défaut:</td><td><textarea cols=80 rows=5 name="proformadefaultcomment" size=80>' . $row['proformadefaultcomment'] . '</textarea></td></tr>';
  
  echo '<tr><td>Avoir le type "proforma" par défaut :</td><td><input type="checkbox" name="autoproforma" value="1"';
  if ($row['autoproforma']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Afficher la TVA lors de la saisie d\'une facture :</td><td><input type="checkbox" name="showlinevat" value="1"';
  if ($row['showlinevat']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Ne pas afficher le stock disponible lors de la saisie d\'une facture :</td><td><input type="checkbox" name="dontshowstock" value="1"';
  if ($row['dontshowstock']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Afficher les commentaires produits par défaut :</td><td><input type="checkbox" name="defshowcomments" value="1"';
  if ($row['defshowcomments']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Autoriser, lors de la confirmation d\'une facture, le changement de date de la facture :<br>
Si la date de la facture est différente de la date à laquelle vous confirmez la facture,<br> la date de la facture se met à jour (prend la date de la confirmation)
</td><td><input type="checkbox" name="confirmchangesdate" value="1"';
  if ($row['confirmchangesdate']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Autoriser l\'utilisation des "tags" :<br>
Les tags sont des champs que vous pouvez personnaliser (mettre un libellé personnalisé)<br> et faire apparaître dans la facture
</td><td><input type="checkbox" name="useinvoicetag" value="1"';
  if ($row['useinvoicetag']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Ne pas autoriser la modification de la date comptable :</td><td><input type="checkbox" name="hideaccountingdate" value="1"';
  if ($row['hideaccountingdate']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Ne pas afficher la date de livraison sur la facture :</td><td><input type="checkbox" name="hidedeliverydate" value="1"';
  if ($row['hidedeliverydate']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Utiliser le bon de livraison :<br>
Permet de créer un bon de livraison (pas de prix affichés)
</td><td><input type="checkbox" name="usenotice" value="1"';
  if ($row['usenotice']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Afficher l\'heure d\'impression:</td><td>
  <select name="showtimeprinted">
  <option value=0></option>
  <option value=1'; if ($row['showtimeprinted'] == 1) { echo ' selected'; } echo '>Afficher</option>
  <option value=2'; if ($row['showtimeprinted'] == 2) { echo ' selected'; } echo '>Afficher Création/Rédacteur</option>
  </select>';
  echo '<tr><td>A l\'affichage de la facture, utiliser le modèle personalisé par défaut :
</td><td><input type="checkbox" name="custominvoiceisdefault" value="1"';
  if ($row['custominvoiceisdefault']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Afficher la remise dans le cas d\'utilisation des prix par listes :
</td><td><input type="checkbox" name="rebate_listpricing" value="1"';
  if ($row['rebate_listpricing']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Utiliser les interventions :
</td><td><input type="checkbox" name="use_interventions" value="1"';
  if ($row['use_interventions']) { echo ' checked'; }
  echo '></td></tr>';
  
  echo '<tr><td colspan=2>&nbsp;</td></tr><tr><td align=center colspan=2><b>Paiement</td></tr>';
  echo '<tr><td>Rendre les informations chèques obligatoires :<br>
(banque, numéro de chèque et le tireur)
</td><td><input type="checkbox" name="musthavecheckinput" value="1"';
  if ($row['musthavecheckinput']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Activer la fonction impression chèque :<br>
Lors d\'un paiement par chèque, TEM propose d\'imprimer le chèque
</td><td><input type="checkbox" name="printcheck" value="1"';
  if ($row['printcheck']) { echo ' checked'; }
  echo '></td></tr>';
  
  echo '<tr><td colspan=2>&nbsp;</td></tr><tr><td align=center colspan=2><b>Produits</td></tr>';
  echo '<tr><td>Utiliser les codes produits (au lieu des numéros) :
  </td><td><input type="checkbox" name="useproductcode" value="1"';
  if ($row['useproductcode']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Activer les unités/sous-unités :<br>
Distinguer les unités des sous-unités lors de la saisie des quantités dans la facture
</td><td><input type="checkbox" name="useunits" value="1"';
  if ($row['useunits']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Activer le stock/DLV :<br>
TEM sélectionne le stock avec la DLV la plus proche
</td><td><input type="checkbox" name="usedlv" value="1"';
  if ($row['usedlv']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Stock/Emplacement:</td><td><input type="checkbox" name="useemplacement" value="1"'; # TODO remove
  if ($row['useemplacement']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Prendre en compte le stock réservé par les factures en attente de confirmation :</td><td><input type="checkbox" name="unconfirmedcountsinstock" value="1"';
  if ($row['unconfirmedcountsinstock']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Traçabilité théorique:<br>
  (enregistrer le lot actuel lors de la saise d\'une facture)</td><td><input type="checkbox" name="salestrace" value="1"';
  if ($row['salestrace']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Continuous stock calculation:</td><td><input type="checkbox" name="continuousstock" value="1"';
  if ($row['continuousstock']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Stock par utilisateur:</td><td><input type="checkbox" name="stockperuser" value="1"';
  if ($row['stockperuser']) { echo ' checked'; }
  echo '></td></tr>';
  #echo '<tr><td colspan=2>&nbsp;</td></tr><tr><td align=center colspan=2><b>Reports</td></tr>';

  echo '<tr><td colspan=2>&nbsp;</td></tr><tr><td align=center colspan=2><b>Compta</td></tr>';
  echo '<tr><td>Intégration compta :</td><td><select name="directtoacc">';
  echo '<option value=0>Non</option>';
  echo '<option value=1'; if($row['directtoacc'] == 1) { echo ' selected'; }; echo '>Automatique</option>';
  echo '<option value=2'; if($row['directtoacc'] == 2) { echo ' selected'; }; echo '>Manuel</option>';
  echo '<tr><td>Rapprochement :<td><select name="reconciliation_type">';
  echo '<option value=0>Relevé bancaire</option>';
  echo '<option value=1'; if($row['reconciliation_type'] == 1) { echo ' selected'; }; echo '>Classique</option>';
  echo '<option value=2'; if($row['reconciliation_type'] == 2) { echo ' selected'; }; echo '>Simple</option>';
  echo '<tr><td>Activer l\'alerte facture et l\'alerte chèques à déposer:<br>
En cas de facture dont la date d\'échéance dépasse les 30 jours, une alerte apparaît sur la page d\'accueil
</td><td><input type="checkbox" name="accountingalert" value="1"';
  if ($row['accountingalert']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Bénéficiaire (remise chèques) :</td><td><input type="text" STYLE="text-align:right" name="beneficiary" value="' . $row['beneficiary'] . '" size=80></td></tr>';
  echo '<tr><td>Info haut (compte):</td><td><textarea cols=80 name="accounttop" rows=5>' . $row['accounttop'] . '</textarea></td></tr>';
  echo '<tr><td>Info bas (compte):</td><td><textarea cols=80 name="accountbottom" rows=5>' . $row['accountbottom'] . '</textarea></td></tr>';
  
  echo '<tr><td colspan=2>&nbsp;</td></tr><tr><td align=center colspan=2><b>Achat</td></tr>';
  echo '<tr><td>Info haut (Packing List):</td><td><textarea cols=80 name="packinglisttop" rows=5>' . $row['packinglisttop'] . '</textarea></td></tr>';
  echo '<tr><td>Info bas (Packing List):</td><td><textarea cols=80 name="packinglistbottom" rows=5>' . $row['packinglistbottom'] . '</textarea></td></tr>';
  
  
/*
  echo '<tr><td colspan=2>&nbsp;</td></tr><tr><td align=center colspan=2><b>Logs</td></tr>';
  echo '<tr><td>Annulation facture:</td><td><input type="checkbox" name="loginvoicecancel" value="1"';
  if ($row['loginvoicecancel']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Création/modification facture:</td><td><input type="checkbox" name="loginvoice" value="1"';
  if ($row['loginvoice']) { echo ' checked'; }
  echo '></td></tr>';
  echo '<tr><td>Confirmation facture:</td><td><input type="checkbox" name="loginvoiceconfirm" value="1"';
  if ($row['loginvoiceconfirm']) { echo ' checked'; }
  echo '></td></tr>';
*/  
  #echo '<tr><td colspan=2>&nbsp;</td></tr><tr><td align=center colspan=2><b>Imprimantes</td></tr>';
  #echo '<tr><td>Imprimantes système:</td><td><input type="checkbox" name="systemprinters" value="1"';
  #if ($row['systemprinters']) { echo ' checked'; }
  #echo '></td></tr>';
  #echo '<tr><td>Imprimantes systeme en tete (sep. §):</td><td><input type="text" STYLE="text-align:right" name="invoiceprintheader" value="' . $row['invoiceprintheader'] . '" size=80></td></tr>';
  echo '<tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="systemmenu" value="' . $systemmenu . '"><input type="submit" value="Valider"></td></tr></table></form>';
  break;

  case 1:

  $dontshowstock = $_POST['dontshowstock']; if ($dontshowstock == "") { $dontshowstock = 0; }
  $unconfirmedcountsinstock = $_POST['unconfirmedcountsinstock']; if ($unconfirmedcountsinstock == "") { $unconfirmedcountsinstock = 0; }
  $showlinevat = $_POST['showlinevat']; if ($showlinevat == "") { $showlinevat = 0; }
  $usesofix = $_POST['usesofix']; if ($usesofix == "") { $usesofix = 0; }
$usedelivery = $_POST['usedelivery']+0;
  #$restrictconfirm = $_POST['restrictconfirm']; if ($restrictconfirm == "") { $restrictconfirm = 0; }
  #$directconfirm = $_POST['directconfirm']; if ($directconfirm == "") { $directconfirm = 0; }
  $directconfirm = 0; # not in use, possibly make all invoices directly confirmed
  $autoproforma = $_POST['autoproforma']; if ($autoproforma == "") { $autoproforma = 0; }
  $usedlv = $_POST['usedlv']; if ($usedlv == "") { $usedlv = 0; }
  $useemplacement = $_POST['useemplacement']; if ($useemplacement == "") { $useemplacement = 0; }
  if ($useemplacement)
  {
    $query = 'update purchasebatch set placementid=1 where placementid IS NULL';
    $query_prm = array();
    require('inc/doquery.php');
  }
  $useunits = $_POST['useunits']; if ($useunits == "") { $useunits = 0; }
  $useproductcode = $_POST['useproductcode']; if ($useproductcode == "") { $useproductcode = 0; }
  $defshowcomments = $_POST['defshowcomments']; if ($defshowcomments == "") { $defshowcomments = 0; }
  $confirmchangesdate = $_POST['confirmchangesdate']; if ($confirmchangesdate == "") { $confirmchangesdate = 0; }
  #$loginvoicecancel = $_POST['loginvoicecancel']; if ($loginvoicecancel == "") { $loginvoicecancel = 0; }
  #$loginvoice = $_POST['loginvoice']; if ($loginvoice == "") { $loginvoice = 0; }
  #$loginvoiceconfirm = $_POST['loginvoiceconfirm']; if ($loginvoiceconfirm == "") { $loginvoiceconfirm = 0; }
  $defaultclientid = $_POST['defaultclientid'] + 0;
  #$systemprinters = $_POST['systemprinters'] + 0;
  $useinvoicetag = $_POST['useinvoicetag'] + 0;
  $displaydateandtime = $_POST['displaydateandtime'] + 0;
  $hideaccountingdate = $_POST['hideaccountingdate'] + 0;
  $hidedeliverydate = $_POST['hidedeliverydate'] + 0;
  #$invoiceprintheader = $_POST['invoiceprintheader'];
  $checktimes = $_POST['checktimes']+0;
$accountingalert = $_POST['accountingalert']+0;
$beneficiary = $_POST['beneficiary'];
  $useitemadd = $_POST['useitemadd'] + 0;
$uselocalbol = $_POST['uselocalbol'] + 0;
  $usenotice = $_POST['usenotice'] + 0;
  $showtimeprinted = $_POST['showtimeprinted'] + 0;
  $custominvoiceisdefault = $_POST['custominvoiceisdefault']+0;
  $accounttop = $_POST['accounttop'];
  $accountbottom = $_POST['accountbottom'];
  $returnproductsaregeneric = $_POST['returnproductsaregeneric'] + 0;
  $hidedefaultinvoice = $_POST['hidedefaultinvoice'] + 0;
  $useretailprice = $_POST['useretailprice'] + 0;
  $packinglistbottom = $_POST['packinglistbottom'];
  $packinglisttop = $_POST['packinglisttop'];
  $musthavecheckinput = (int) $_POST['musthavecheckinput'];
  $badpayeralert = (int) $_POST['badpayeralert'];
  $invoicedirecttopayment = (int) $_POST['invoicedirecttopayment'];
  $noretrodates = $_POST['noretrodates'] + 0;
  $maxresults = (int) $_POST['maxresults'];
  $useserialnumbers = (int) $_POST['useserialnumbers']; if ($useserialnumbers == "") { $useserialnumbers = 0; }
  $proformadefaultcomment = $_POST['proformadefaultcomment'];
  $invoicetemplate = (int) $_POST['invoicetemplate'];
  $allowinvoiceshare = (int) $_POST['allowinvoiceshare'];
  $salestrace = (int) $_POST['salestrace'];
  $continuousstock = (int) $_POST['continuousstock'];
  $stockperuser = (int) $_POST['stockperuser'];
  $paybydateselect = (int) $_POST['paybydateselect'];
  $invoicedeductions = (int) $_POST['invoicedeductions'];
  $exportfields = (int) $_POST['exportfields'];
  $directtoacc = (int) $_POST['directtoacc'];
  #$standalone_accounting = (int) $_POST['standalone_accounting']; ,standalone_accounting=? ,$standalone_accounting
  $standalone_hr = (int) $_POST['standalone_hr'];
  $printcheck = (int) $_POST['printcheck'];
  $autocompleteoption = (int) $_POST['autocompleteoption'];
  $time_management = (int) $_POST['time_management'];
  $use_loyalty_points = (int) $_POST['use_loyalty_points'];
  $rebate_listpricing = (int) $_POST['rebate_listpricing'];
  $use_interventions = (int) $_POST['use_interventions'];
  $reconciliation_type = (int) $_POST['reconciliation_type'];
  $globalise_vat = (int) $_POST['globalise_vat'];
  $use_invoiceitemgroup = (int) $_POST['use_invoiceitemgroup'];
  $use_invoice_sig = (int) $_POST['use_invoice_sig'];
  $quote_info = $_POST['quote_info'];

  $query = 'update globalvariables set use_invoice_sig=?,quote_info=?,reconciliation_type=?,use_invoiceitemgroup=?,globalise_vat=?,stockperuser=?,rebate_listpricing=?,use_interventions=?,use_loyalty_points=?,time_management=?,autocompleteoption=?,printcheck=?,continuousstock=?,directtoacc=?,exportfields=?,invoicedeductions=?,paybydateselect="' . $paybydateselect . '",salestrace="' . $salestrace . '",allowinvoiceshare="' . $allowinvoiceshare . '",invoicetemplate="' . $invoicetemplate . '",proformadefaultcomment="' . $proformadefaultcomment . '",useserialnumbers="' . $useserialnumbers . '",maxresults="' . $maxresults . '",noretrodates="' . $noretrodates . '",invoicedirecttopayment="' . $invoicedirecttopayment . '",badpayeralert="' . $badpayeralert . '",musthavecheckinput="' . $musthavecheckinput . '",packinglisttop="' . $packinglisttop . '",packinglistbottom="' . $packinglistbottom . '",useretailprice="' . $useretailprice . '",hidedefaultinvoice="' . $hidedefaultinvoice . '",returnproductsaregeneric="' . $returnproductsaregeneric . '",accounttop="' . $accounttop . '",accountbottom="' . $accountbottom . '",custominvoiceisdefault="' . $custominvoiceisdefault . '",showtimeprinted="' . $showtimeprinted . '",usenotice="' . $usenotice . '",uselocalbol="' . $uselocalbol . '",usedelivery="' . $usedelivery . '",useitemadd="' . $useitemadd . '",beneficiary="' . $beneficiary . '",accountingalert="' . $accountingalert . '",checktimes="' . $checktimes . '",hidedeliverydate="' . $hidedeliverydate . '",hideaccountingdate="' . $hideaccountingdate . '",displaydateandtime="' . $displaydateandtime . '",useinvoicetag="' . $useinvoicetag . '",unconfirmedcountsinstock="' . $unconfirmedcountsinstock . '",dontshowstock="' . $dontshowstock . '",defaultclientid="' . $defaultclientid . '",usesofix="' . $usesofix . '",confirmchangesdate="' . $confirmchangesdate . '",defshowcomments="' . $defshowcomments . '",showlinevat="' . $showlinevat . '",directconfirm="' . $directconfirm . '",autoproforma="' . $autoproforma . '",useproductcode="' . $useproductcode . '",infofact=?,companyinfo=?,customname="' . $_POST['customname'] . '",currencyname="' . $_POST['currencyname'] . '",usedlv="' . $usedlv . '",useemplacement="' . $useemplacement . '",useunits="' . $useunits . '",baseurl="' . $_POST['baseurl'] . '",startyear="' . $_POST['startyear'] . '",endyear="' . $_POST['endyear'] . '",invoicelines="' . $_POST['invoicelines'] . '",standalone_hr=? where primaryunique=1';
  $query_prm = array($use_invoice_sig,$quote_info,$reconciliation_type,$use_invoiceitemgroup,$globalise_vat,$stockperuser,$rebate_listpricing,$use_interventions,$use_loyalty_points,$time_management,$autocompleteoption,$printcheck,$continuousstock,$directtoacc,$exportfields,$invoicedeductions,$_POST['infofact'],$_POST['companyinfo'],$standalone_hr);
  require('inc/doquery.php');
  echo '<p>Options globales enregistrées.</p>';
  break;

}
?>