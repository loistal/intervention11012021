<?php

# TODO refactor

require('preload/paymentcategory.php');
require('preload/clientcategory.php');
require('preload/clientcategory2.php');

if (isset($_POST['saveme']) && $_POST['saveme'] == 1)
{
  $query = 'update usertable set accounting_simplified_keepdate=?,user_datepicker=?,invoicereport_menus=?,matching_extended_info=?,show_hideprices_after_confirm=?,accounting_simplified_showac=?,accounting_matchempty=?,definvoiceemployeeid=?,accounting_accountbyselect=?,maxopeninvoices=?,decimalmark=?,user_date_format=?,autocomplete=?,showdeleteditems=?,definvoicetagid=?,defclientcatid=?,defclientcat2id=?,enterttcq=?,defpaymenttypeid=?,hidetop=?,persistentdates=?,balanceonsearch=?,defpaymcatid=?';
  $query_prm = array((int) $_POST['accounting_simplified_keepdate'], (int) $_POST['user_datepicker'],(int) $_POST['invoicereport_menus'],(int) $_POST['matching_extended_info'],(int) $_POST['show_hideprices_after_confirm'],(int)$_POST['accounting_simplified_showac'],(int)$_POST['accounting_matchempty'],(int)$_POST['employeeid'],(int)$_POST['accounting_accountbyselect'],(int)$_POST['maxopeninvoices'],$_POST['decimalmark'],$_POST['user_date_format'],$_POST['autocomplete']+0,$_POST['showdeleteditems']+0,$_POST['invoicetagid']+0,$_POST['defclientcatid']+0,$_POST['defclientcat2id']+0,$_POST['enterttcq']+0,$_POST['defpaymenttypeid']+0,$_POST['hidetop']+0,$_POST['persistentdates']+0,$_POST['balanceonsearch']+0,$_POST['defpaymcatid']+0);
  $query .= ',defrebate_type=?,definvoicequantity=?,defdeliverytypeid=?';
  array_push($query_prm, (int)$_POST['defrebate_type'],(int)$_POST['definvoicequantity'],(int)$_POST['deliverytypeid']);
  $query .= ' where userid=?';
  array_push($query_prm, $_SESSION['ds_userid']);
  require ('inc/doquery.php');
  $_SESSION['ds_accounting_simplified_keepdate'] = $_POST['accounting_simplified_keepdate']+0;
  $_SESSION['ds_user_datepicker'] = $_POST['user_datepicker']+0;
  $_SESSION['ds_invoicereport_menus'] = $_POST['invoicereport_menus']+0;
  $_SESSION['ds_matching_extended_info'] = $_POST['matching_extended_info']+0;
  $_SESSION['ds_defrebate_type'] = $_POST['defrebate_type']+0;
  $_SESSION['ds_definvoicequantity'] = $_POST['definvoicequantity']+0;
  $_SESSION['ds_defdeliverytypeid'] = $_POST['deliverytypeid']+0;
  $_SESSION['ds_autocomplete'] = $_POST['autocomplete']+0;
  $_SESSION['ds_showdeleteditems'] = $_POST['showdeleteditems']+0;  
  $_SESSION['ds_enterttcq'] = $_POST['enterttcq']+0;
  $_SESSION['ds_defpaymenttypeid'] = $_POST['defpaymenttypeid']+0;
  $_SESSION['ds_hidetop'] = $_POST['hidetop']+0;
  $_SESSION['ds_persistentdates'] = $_POST['persistentdates']+0;
  $_SESSION['ds_balanceonsearch'] = $_POST['balanceonsearch']+0;
  $_SESSION['ds_accounting_accountbyselect'] = $_POST['accounting_accountbyselect']+0;
  $_SESSION['ds_accounting_matchempty'] = $_POST['accounting_matchempty']+0;
  $_SESSION['ds_accounting_simplified_showac'] = $_POST['accounting_simplified_showac']+0;
  $_SESSION['ds_defpaymcatid'] = $_POST['defpaymcatid']+0;
  $_SESSION['ds_defclientcatid'] = $_POST['defclientcatid']+0;
  $_SESSION['ds_defclientcat2id'] = $_POST['defclientcat2id']+0;
  $_SESSION['ds_definvoicetagid'] = $_POST['invoicetagid']+0;
  $_SESSION['ds_decimalmark'] = $_POST['decimalmark'];
  $_SESSION['ds_user_date_format'] = (int) $_POST['user_date_format'];
  $_SESSION['ds_maxopeninvoices'] = $_POST['maxopeninvoices']+0;
  $_SESSION['ds_definvoiceemployeeid'] = $_POST['employeeid']+0;
  $_SESSION['ds_show_hideprices_after_confirm'] = (int) $_POST['show_hideprices_after_confirm'];
  if ($_SESSION['ds_purchaseaccess'] == 1)
  {
    $query = 'update usertable set purchaselines=? where userid=?';
    $query_prm = array((int)$_POST['purchaselines'],$_SESSION['ds_userid']);
    require ('inc/doquery.php');
    $_SESSION['ds_purchaselines'] = $_POST['purchaselines']+0;
  }
  if ($_SESSION['ds_accountingaccess'] == 1)
  {
    $query = 'update usertable set accountinglines=? where userid=?';
    $query_prm = array($_POST['accountinglines'],$_SESSION['ds_userid']);
    require ('inc/doquery.php');
    $_SESSION['ds_accountinglines'] = $_POST['accountinglines']+0;
  }
  if ($_SESSION['ds_adminaccess'] == 1)
  {
    $query = 'update usertable set num_resources=? where userid=?';
    $query_prm = array($_POST['num_resources'],$_SESSION['ds_userid']);
    require ('inc/doquery.php');
    $_SESSION['ds_num_resources'] = $_POST['num_resources']+0;
  }

  if ($num_results) { echo '<p>' . d_trad('optionssaved') . '</p><br>'; }
}

echo '<h2>' . d_trad('myoptions:') . '</h2>';
echo '<form method="post" action="options.php"><table>';

echo '<tr><td>' . d_trad('hidetop:') . '</td><td><input type="checkbox" name="hidetop" value="1"'; if ($_SESSION['ds_hidetop']) echo ' CHECKED'; echo '></td></tr>';
echo '<tr><td>' . d_trad('decimalmark:') . '</td><td><select name="decimalmark">
<option value=""></option>
<option value=" "'; if ($_SESSION['ds_decimalmark'] == " ") { echo ' selected'; } ; echo '>' . d_trad('space') . '</option>
<option value=","'; if ($_SESSION['ds_decimalmark'] == ",") { echo ' selected'; } ; echo '>' . d_trad('coma') . '</option>
<option value="."'; if ($_SESSION['ds_decimalmark'] == ".") { echo ' selected'; } ; echo '>' . d_trad('point') . '</option>
</select></td></tr>';
echo '<tr><td colspan=2>&nbsp;';
echo '<tr><td>Format date:</td><td><select name="user_date_format">
<option value=0>Par défaut</option>
<option value=1'; if ($_SESSION['ds_user_date_format'] == 1) { echo ' selected'; } ; echo '>2018-12-31</option>
<option value=2'; if ($_SESSION['ds_user_date_format'] == 2) { echo ' selected'; } ; echo '>31/12/2018</option>
</select>';
echo '<tr><td>Champs date:</td><td><select name="user_datepicker">
<option value=0>Géré par le navigateur</option>
<option value=1'; if ($_SESSION['ds_user_datepicker'] == 1) { echo ' selected'; } ; echo '>Trois champs</option>
<option value=2'; if ($_SESSION['ds_user_datepicker'] == 2) { echo ' selected'; } ; echo '>Format scientifique</option>
</select>';
echo '<tr><td>' . d_trad('persistentdates:') . '</td><td><input type="checkbox" name="persistentdates" value="1"'; if ($_SESSION['ds_persistentdates']) echo ' CHECKED'; echo '></td></tr>';
echo '<tr><td colspan=2>&nbsp;';
echo '<tr><td>Javascript :</td><td><input type="checkbox" name="autocomplete" value="1"'; if ($_SESSION['ds_autocomplete']) echo ' CHECKED'; echo '>';

echo '<tr><td>' . d_trad('showdeleteditems:') . '</td><td><input type="checkbox" name="showdeleteditems" value="1"'; if ($_SESSION['ds_showdeleteditems']) echo ' CHECKED'; echo '></td></tr>';
echo '<tr><td>' . d_trad('maxopeninvoices:') . '</td><td><input type="number" STYLE="text-align:right" name="maxopeninvoices" min=0 value=' . $_SESSION['ds_maxopeninvoices'] . '></td></tr>';

echo '<tr><td colspan=2>&nbsp;</td></tr><tr><td colspan=2><b>' . d_trad('invoices') . '</b>';
echo '<tr><td>' . d_trad('enterttcq:') . '"</td><td><input type="checkbox" name="enterttcq" value="1"'; if ($_SESSION['ds_enterttcq']) echo ' CHECKED'; echo '></td></tr>';
if ($_SESSION['ds_useinvoicetag'])
{
  $dp_itemname = 'invoicetag'; $dp_description = $_SESSION['ds_term_invoicetag'] . ' ' . d_trad('default'); $dp_selectedid = $_SESSION['ds_definvoicetagid'];
  require('inc/selectitem.php');
}
$dp_itemname = 'employee'; $dp_issales = 1; $dp_description = 'Employé (facture) ' . d_trad('default'); $dp_selectedid = $_SESSION['ds_definvoiceemployeeid'];
require('inc/selectitem.php');
$dp_itemname = 'deliverytype'; $dp_description = 'Type de livraison ' . d_trad('default'); $dp_selectedid = $_SESSION['ds_defdeliverytypeid']; $dp_noblank = 1;
require('inc/selectitem.php');
echo '<tr><td>Quantité par défaut:<td><input type="number" name="definvoicequantity" value="' . $_SESSION['ds_definvoicequantity'] . '">';
#
echo '<tr><td>Type de remise par défaut:<td><select style="text-align:right;" name="defrebate_type">
<option value=1';
if ($_SESSION['ds_defrebate_type'] == 1) { echo ' selected'; }
echo '>%</option>
<option value=0';
if ($_SESSION['ds_defrebate_type'] == 0) { echo ' selected'; }
echo '>'.$_SESSION['ds_currencyname'].'</option>';
echo '<option value=2';
if ($_SESSION['ds_defrebate_type'] == 2) { echo ' selected'; }
echo '>Quan.</option>';
if ($_SESSION['ds_use_loyalty_points'])
{
  echo '<option value=3';
  if ($_SESSION['ds_defrebate_type'] == 3) { echo ' selected'; }
  echo '>Points</option>';
}
echo '</select>';
echo '<tr><td>Menus dans Rapport factures :<td><select style="text-align:right;" name="invoicereport_menus">
<option value=1';
if ($_SESSION['ds_invoicereport_menus'] == 1) { echo ' selected'; }
echo '>Afficher</option>
<option value=0';
if ($_SESSION['ds_invoicereport_menus'] == 0) { echo ' selected'; }
echo '>Ne pas afficher</option></select>';

echo '<tr><td colspan=2>&nbsp;</td></tr><tr><td colspan=2><b>' . d_trad('payments') . '</b></td></tr>';
echo '<tr><td>' . d_trad('defaultpayment:') . '</td><td><select name="defpaymenttypeid">';

$query = 'select paymenttypeid,paymenttypename from paymenttype order by paymenttypename';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row2 = $query_result[$i];
  if ($row2['paymenttypeid'] == $_SESSION['ds_defpaymenttypeid']) { echo '<option value="' . $row2['paymenttypeid'] . '" selected>' . $row2['paymenttypename'] . '</option>'; }
  else { echo '<option value="' . $row2['paymenttypeid'] . '">' . $row2['paymenttypename'] . '</option>'; }
}
echo '</select></td></tr>';
if (isset($paymentcategoryA))
{
  echo '<tr><td>' . d_trad('defaultpaymentcategory:') . '</td><td><select name="defpaymcatid"><option value="0"></option>';
  foreach ($paymentcategoryA as $paymentcategoryid => $paymentcategoryname)
  {
    echo '<option value="' . $paymentcategoryid . '"';
    if ($_SESSION['ds_defpaymcatid'] == $paymentcategoryid) echo ' selected';
    echo '>' . $paymentcategoryname . '</option>';
  }
  echo '</td></tr>';
}

echo '<tr><td colspan=2>&nbsp;<tr><td colspan=2><b>Confirmer / Annuler</b>';
echo '<tr><td>Lien vers factures prix masqués<td><input type="checkbox" name="show_hideprices_after_confirm" value="1"'; if ($_SESSION['ds_show_hideprices_after_confirm']) echo ' CHECKED'; echo '>';

echo '<tr><td colspan=2>&nbsp;</td></tr><tr><td colspan=2><b>' . d_trad('clients') . '</b></td></tr>';
if (isset($clientcategoryA))
{
  echo '<tr><td>' . d_trad('defaultclientcategory:') . '</td><td><select name="defclientcatid"><option value="0"></option>';
  foreach ($clientcategoryA as $clientcategoryid => $clientcategoryname)
  {
    echo '<option value="' . $clientcategoryid . '"';
    if ($_SESSION['ds_defclientcatid'] == $clientcategoryid) echo ' selected';
    echo '>' . $clientcategoryname . '</option>';
  }
}
if (isset($clientcategory2A))
{
  echo '<tr><td>' . d_trad('defaultclientcategory2:') . '</td><td><select name="defclientcat2id"><option value="0"></option>';
  foreach ($clientcategory2A as $clientcategory2id => $clientcategory2name)
  {
    echo '<option value="' . $clientcategory2id . '"';
    if ($_SESSION['ds_defclientcat2id'] == $clientcategory2id) echo ' selected';
    echo '>' . $clientcategory2name . '</option>';
  }
  echo '</td></tr>';
}
echo '<tr><td>' . d_trad('balanceonsearch:') . '</td><td><input type="checkbox" name="balanceonsearch" value="1"'; if ($_SESSION['ds_balanceonsearch']) echo ' CHECKED'; echo '>';
echo '<tr><td>Lettrage: Infos supplémentaires<td><input type="checkbox" name="matching_extended_info" value="1"'; if ($_SESSION['ds_matching_extended_info']) echo ' CHECKED'; echo '>';

if ($_SESSION['ds_purchaseaccess'] == 1)
{
  echo '<tr><td colspan=2>&nbsp;</td></tr><tr><td colspan=2><b>Achat</b></td></tr>';
  echo '<tr><td>Lignes achat:</td><td><input type="number" STYLE="text-align:right" name="purchaselines" min=0 value=' . $_SESSION['ds_purchaselines'] . '></td></tr>';
}
if ($_SESSION['ds_accountingaccess'] == 1)
{
  echo '<tr><td colspan=2>&nbsp;</td></tr><tr><td colspan=2><b>Compta</b></td></tr>';
  echo '<tr><td>Lignes écriture:</td><td><input type="number" STYLE="text-align:right" name="accountinglines" min=0 value=' . $_SESSION['ds_accountinglines'] . '></td></tr>';
  echo '<tr><td>Écriture: Comptes par liste</td><td><input type="checkbox" name="accounting_accountbyselect" value="1"'; if ($_SESSION['ds_accounting_accountbyselect']) echo ' CHECKED'; echo '></td></tr>';
  echo '<tr><td>Lettrage: Afficher comptes vides</td><td><input type="checkbox" name="accounting_matchempty" value="1"'; if ($_SESSION['ds_accounting_matchempty']) echo ' CHECKED'; echo '></td></tr>';
  echo '<tr><td>Menus simplifiés: Afficher comptes<td><input type="checkbox" name="accounting_simplified_showac" value="1"'; if ($_SESSION['ds_accounting_simplified_showac']) echo ' CHECKED'; echo '></td></tr>';
  echo '<tr><td>Menus simplifiés: Garder dates sur liens<td><input type="checkbox" name="accounting_simplified_keepdate" value="1"'; if ($_SESSION['ds_accounting_simplified_keepdate']) echo ' CHECKED'; echo '>';
}
if ($_SESSION['ds_adminaccess'] == 1)
{
  echo '<tr><td colspan=2>&nbsp;</td></tr><tr><td colspan=2><b>Admin</b></td></tr>';
  echo '<tr><td>Lignes planning:</td><td><input type="number" STYLE="text-align:right" name="num_resources" min=0 value=' . $_SESSION['ds_num_resources'] . '></td></tr>';
}

echo '<tr><td colspan=2>&nbsp;</td></tr><tr><td colspan=2><input type=hidden name="saveme" value="1"><input type=hidden name="optionsmenu" value="' . $optionsmenu . '">';
echo '<input type="submit" value="' . d_trad('validate') . '"></td></tr>';
echo '</table></form>';

?>