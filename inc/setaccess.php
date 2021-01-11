<?php

# TODO refactor

# run sql update
$query = 'select last_sql_update from globalvariables where primaryunique=1';
$query_prm = array();
require('inc/doquery.php');
$last_sql_update = (int) $query_result[0]['last_sql_update'];
require('inc/sql_update.php');
if (isset($uA))
{
  $new_last_sql_update = max(array_keys($uA));
  array_splice($uA, 0, $last_sql_update);
  foreach ($uA as $query)
  {
    require('inc/doquery.php');
  }
  if ($new_last_sql_update > $last_sql_update)
  {
    $query = 'update globalvariables set last_sql_update=? where primaryunique=1';
    $query_prm = array($new_last_sql_update);
    require('inc/doquery.php');
  }
}

$_SESSION['ds_defaultnumdailylogs'] = 6; # TODO read from hroptions

$_SESSION['debug'] = 0; # should be removed everywhere
$_SESSION['ds_showsqldebug'] = 0;

$_SESSION['ds_language'] = 'fr';

$_SESSION['ds_dauphininstance'] = $dauphin_instancename; # used ?
$_SESSION['ds_enterprisename'] = $ourenterprisename; # used ?

$_SESSION['ds_maxconfig'] = 20;      # should be a parameter in the table globalvariables
$_SESSION['ds_shipmentstatic'] = 50;  # should be a parameter in the table globalvariables

$_SESSION['ds_margintokeepPPN'] = 0.40;
$_SESSION['ds_margintokeepPGC'] = 0.40;

$_SESSION['ds_tem_currencyprecision'] = '0'; # no decimals for French Pacific Francs

# modules #### NOT IN USE need to find these in code and remove the references
$_SESSION['ds_m_distr'] = 0; # distribution
$_SESSION['ds_rental'] = 1; # location

$_SESSION['ds_lastselecteddate'] = '';
$_SESSION['ds_lastselecteddeliverydate'] = '';
$_SESSION['ds_lastemployeeid'] = '';

$query = 'select * from companyinfo where companyinfoid=1';
$query_prm = array();
require ('inc/doquery.php');
if ($num_results)
{
  $_SESSION['ds_tva_encaissement'] = $query_result[0]['tva_encaissement'];
  $_SESSION['ds_socialsecuritysectorid'] = $query_result[0]['socialsecuritysectorid'];
  $_SESSION['ds_accounting_closingdate'] = $query_result[0]['accounting_closingdate'];
  $_SESSION['ds_companyname'] = $query_result[0]['companyname'];
  $companyinfo_companyname = $query_result[0]['companyname'];
  $_SESSION['ds_tva_decl_type'] = $query_result[0]['tva_decl_type'];
  $_SESSION['seniority_bonus_calc'] = (int) $query_result[0]['seniority_bonus_calc'];
}
else
{
  $_SESSION['ds_tva_encaissement'] = NULL;
  $_SESSION['ds_socialsecuritysectorid'] = NULL;
  $_SESSION['ds_accounting_closingdate'] = NULL;
  $_SESSION['ds_companyname'] = NULL;
  $companyinfo_companyname = NULL;
  $_SESSION['ds_tva_decl_type'] = NULL;
  $_SESSION['seniority_bonus_calc'] = 0;
}

# usertable
$query = 'select * from usertable where userid=?';
$query_prm = array($_SESSION['ds_userid']);
require ('inc/doquery.php');
$_SESSION['ds_accounting_simplified_keepdate'] = (int) $query_result[0]['accounting_simplified_keepdate'];
$_SESSION['ds_user_datepicker'] = (int) $query_result[0]['user_datepicker'];
$_SESSION['ds_invoicereport_menus'] = (int) $query_result[0]['invoicereport_menus'];
$_SESSION['ds_use_invoiceitemgroup'] = (int) $query_result[0]['use_invoiceitemgroup'];
$_SESSION['ds_can_send_emails'] = (int) $query_result[0]['can_send_emails'];
$_SESSION['ds_user_date_format'] = (int) $query_result[0]['user_date_format'];
$_SESSION['ds_stockperthisuser'] = (int) $query_result[0]['stockperthisuser'];
$_SESSION['ds_mywarehouseid'] = (int) $query_result[0]['mywarehouseid'];
$_SESSION['ds_cannotconfirmnotice'] = (int) $query_result[0]['cannotconfirmnotice'];
$_SESSION['ds_purchaselines'] = (int) $query_result[0]['purchaselines'];
$_SESSION['ds_defrebate_type'] = (int) $query_result[0]['defrebate_type'];
$_SESSION['ds_definvoicequantity'] = (int) $query_result[0]['definvoicequantity'];
$_SESSION['ds_defdeliverytypeid'] = (int) $query_result[0]['defdeliverytypeid'];
$_SESSION['ds_deliveryorderby'] = $query_result[0]['deliveryorderby']+0;
$_SESSION['ds_accounting_simplified_showac'] = $query_result[0]['accounting_simplified_showac']+0;
$_SESSION['ds_accounting_matchempty'] = $query_result[0]['accounting_matchempty']+0;
$_SESSION['ds_hide_invoice_fields'] = $query_result[0]['hide_invoice_fields']+0;
$_SESSION['ds_maxopeninvoices'] = $query_result[0]['maxopeninvoices'];
$_SESSION['ds_decimalmark'] = $query_result[0]['decimalmark'];
$_SESSION['ds_menustyle'] = (int) $query_result[0]['menustyle'];
$_SESSION['ds_displayicons'] = 1;
if ($_SESSION['ds_menustyle'] > 2)
{
  $_SESSION['ds_displayicons'] = 0;
}
$_SESSION['ds_autocomplete'] = (int) $query_result[0]['autocomplete'];
$_SESSION['ds_showdeleteditems'] = (int) $query_result[0]['showdeleteditems'];  
$_SESSION['ds_sqllimit'] = $query_result[0]['sqllimit']+0;
$_SESSION['ds_confirmonlyown'] = $query_result[0]['confirmonlyown']+0;
$_SESSION['ds_definvoicetagid'] = $query_result[0]['definvoicetagid'];
$_SESSION['ds_defpaymcatid'] = $query_result[0]['defpaymcatid'];
$_SESSION['ds_defclientcatid'] = $query_result[0]['defclientcatid'];
$_SESSION['ds_defclientcat2id'] = $query_result[0]['defclientcat2id'];
$_SESSION['ds_user_font'] = $query_result[0]['user_font'];
$_SESSION['ds_user_font_print'] = $query_result[0]['user_font_print'];
$_SESSION['ds_user_font_size'] = $query_result[0]['user_font_size'];
$_SESSION['ds_salesaccess'] = $query_result[0]['salesaccess'];
$_SESSION['ds_clientsaccess'] = $query_result[0]['clientsaccess'];
$_SESSION['ds_purchaseaccess'] = $query_result[0]['purchaseaccess'];
$_SESSION['ds_usebyaccess'] = $query_result[0]['usebyaccess'];
$_SESSION['ds_adminaccess'] = $query_result[0]['adminaccess'];
$_SESSION['ds_deliveryaccess'] = $query_result[0]['deliveryaccess'];
$_SESSION['ds_deliveryaccessinvoices'] = $query_result[0]['deliveryaccessinvoices'];
$_SESSION['ds_deliveryaccessreturns'] = $query_result[0]['deliveryaccessreturns'];
$_SESSION['ds_warehouseaccess'] = $query_result[0]['warehouseaccess'];
$_SESSION['ds_warehouseaccesstype'] = $query_result[0]['warehouseaccesstype'];
$_SESSION['ds_accountingaccess'] = $query_result[0]['accountingaccess'];
$_SESSION['ds_optionsaccess'] = $query_result[0]['optionsaccess'];
$_SESSION['ds_systemaccess'] = $query_result[0]['systemaccess'];
$_SESSION['ds_reportsaccess'] = $query_result[0]['reportsaccess'];
$_SESSION['ds_bgcolor'] = '#' . $query_result[0]['bgcolor'];
$_SESSION['ds_fgcolor'] = '#' . $query_result[0]['fgcolor'];
$_SESSION['ds_linkcolor'] = '#' . $query_result[0]['linkcolor'];
$_SESSION['ds_menucolor'] = '#' . $query_result[0]['menucolor'];
$_SESSION['ds_menubordercolor'] = '#' . $query_result[0]['menubordercolor'];
$_SESSION['ds_menufontcolor'] = '#' . $query_result[0]['menufontcolor'];
$_SESSION['ds_alertcolor'] = '#' . $query_result[0]['alertcolor'];
$_SESSION['ds_infocolor'] = '#' . $query_result[0]['infocolor'];
$_SESSION['ds_formcolor'] = '#' . $query_result[0]['formcolor'];
$_SESSION['ds_tablecolor'] = '#' . $query_result[0]['tablecolor'];
$_SESSION['ds_inputcolor'] = '#' . $query_result[0]['inputcolor'];
$_SESSION['ds_tablecolor1'] = '#' . $query_result[0]['tablecolor1'];
$_SESSION['ds_tablecolor2'] = '#' . $query_result[0]['tablecolor2'];
$_SESSION['ds_nbtablecolors'] = $query_result[0]['nbtablecolors'];  
$_SESSION['ds_usetablecolorsub'] = $query_result[0]['usetablecolorsub'];  
$_SESSION['ds_tablecolorsub'] = '#' . $query_result[0]['tablecolorsub'];  
#$_SESSION['ds_emphasiscolor'] = '#' . $query_result[0]['emphasiscolor']; not used by user session, used in reports
$_SESSION['ds_name'] = $query_result[0]['name'];
$_SESSION['ds_initials'] = $query_result[0]['initials'];
$_SESSION['ds_useremail'] = $query_result[0]['useremail'];
$_SESSION['ds_caninvoicedate'] = 1; if ($query_result[0]['noinvoicedate'] == 1) { $_SESSION['ds_caninvoicedate'] = 0; }
$_SESSION['ds_canpaymentdate'] = 1; if ($query_result[0]['nopaymentdate'] == 1) { $_SESSION['ds_canpaymentdate'] = 0; }
$_SESSION['ds_canpayments'] = 1; if ($query_result[0]['nopayments'] == 1) { $_SESSION['ds_canpayments'] = 0; }
$_SESSION['ds_canreturns'] = 1; if ($query_result[0]['noreturns'] == 1) { $_SESSION['ds_canreturns'] = 0; }
$_SESSION['ds_canmodinvoice'] = 1; if ($query_result[0]['nomodinvoice'] == 1) { $_SESSION['ds_canmodinvoice'] = 0; }
$_SESSION['ds_canconfirm'] = 1; if ($query_result[0]['noconfirm'] == 1) { $_SESSION['ds_canconfirm'] = 0; }
$_SESSION['ds_canchangeprice'] = 1; if ($query_result[0]['noprice'] == 1) { $_SESSION['ds_canchangeprice'] = 0; }
$_SESSION['ds_canchangestock'] = 1; if ($query_result[0]['nostock'] == 1) { $_SESSION['ds_canchangestock'] = 0; }; if ($query_result[0]['nostock'] == 2) { $_SESSION['ds_canchangestock'] = 2; }
#$_SESSION['ds_printerid'] = $query_result[0]['printerid'];
$_SESSION['ds_enterttcq'] = $query_result[0]['enterttcq']+0;
$_SESSION['ds_defpaymenttypeid'] = $query_result[0]['defpaymenttypeid']+0;
$_SESSION['ds_hidetop'] = $query_result[0]['hidetop']+0;
$_SESSION['ds_persistentdates'] = $query_result[0]['persistentdates']+0;
$_SESSION['ds_balanceonsearch'] = $query_result[0]['balanceonsearch']+0;
$_SESSION['ds_matching_extended_info'] = $query_result[0]['matching_extended_info']+0;
$_SESSION['ds_myemployeeid'] = $query_result[0]['myemployeeid']+0;
$_SESSION['ds_ishrsuperuser'] = $query_result[0]['ishrsuperuser']+0;
$_SESSION['ds_manage_qr'] = $query_result[0]['manage_qr_locations']+0;
#$_SESSION['ds_ishradmin'] = $query_result[0]['ishradmin']+0;
$_SESSION['ds_acc_canmodinvoice'] = $query_result[0]['acc_canmodinvoice']+0;
$_SESSION['ds_acc_canmodpayment'] = $query_result[0]['acc_canmodpayment']+0;
$_SESSION['ds_restrictbyplanning'] = $query_result[0]['restrictbyplanning']+0;
$_SESSION['ds_userrepresentsclientid'] = $query_result[0]['userrepresentsclientid']+0;
$_SESSION['ds_nolocalbol'] = $query_result[0]['nolocalbol']+0;
$_SESSION['ds_allowsalesreportsvalues'] = 1; if ($query_result[0]['nosalesreportsvalues'] == 1) { $_SESSION['ds_allowsalesreportsvalues'] = 0; };
$_SESSION['ds_num_resources'] = $query_result[0]['num_resources'];
$_SESSION['ds_accountinglines'] = $query_result[0]['accountinglines'];
$_SESSION['ds_hovercolor'] = '#' . $query_result[0]['hovercolor'];
$_SESSION['ds_usehovercolor'] = $query_result[0]['usehovercolor']+0;
$_SESSION['ds_accounting_accountbyselect'] = $query_result[0]['accounting_accountbyselect']+0;
$_SESSION['ds_style_image_id'] = $query_result[0]['style_image_id'];
$_SESSION['ds_showinvoice_modifiy_options'] = $query_result[0]['showinvoice_modify_options']+0;
$_SESSION['ds_invoicedirecttopayment'] = $query_result[0]['invoicedirecttopayment']+0;
#$_SESSION['ds_autoconfirminvoices'] = $query_result[0]['autoconfirminvoices']+0;
$_SESSION['ds_definvoiceemployeeid'] = $query_result[0]['definvoiceemployeeid']+0;
$_SESSION['ds_show_hideprices_after_confirm'] = (int) $query_result[0]['show_hideprices_after_confirm'];

$_SESSION['ds_allowedclientlist'] = '';
if ($_SESSION['ds_restrictbyplanning'])
{
  if ($_SESSION['ds_myemployeeid'] == 0) { $_SESSION['ds_allowedclientlist'] = '(-1)'; }
  else
  {
    $allowedclientlistA = array(); $resultcount = -1;
    $query = 'select planning_client.clientid from planning,planning_employee,planning_client where planning_employee.planningid=planning.planningid and planning_client.planningid=planning.planningid
    and planning.deleted=0 and planning_employee.employeeid=? and planningstart<=? and planningstop>=?';
    $query_prm = array($_SESSION['ds_myemployeeid'],$_SESSION['ds_curdate'],$_SESSION['ds_curdate']);
    require('inc/doquery.php');
    for ($i=0;$i<$num_results;$i++)
    {
      $resultcount++;
      $allowedclientlistA[$resultcount] = $query_result[$i]['clientid'];
    }
    $allowedclientlistA = array_filter(array_unique($allowedclientlistA));
    sort($allowedclientlistA);
    $_SESSION['ds_allowedclientlist'] = '(';
    foreach ($allowedclientlistA as $kladd)
    {
      $_SESSION['ds_allowedclientlist'] .= $kladd . ',';
    }
    $_SESSION['ds_allowedclientlist'] = rtrim($_SESSION['ds_allowedclientlist'],',') . ')';
    if ($_SESSION['ds_allowedclientlist'] == '()') { $_SESSION['ds_allowedclientlist'] = '(-1)'; }
    unset($resultcount,$allowedclientlistA,$kladd);
  }
}

### HR ACCESS ###
$_SESSION['ds_hraccess'] = 0;
if($_SESSION['ds_userrepresentsclientid'] == 0 && ($_SESSION['ds_ishrsuperuser'] || $_SESSION['ds_myemployeeid'] > 0))
{
  $_SESSION['ds_hraccess'] = 1;
  $query = 'select teamid,ismanager,unionrep from employee where employeeid=?';
  $query_prm = array($_SESSION['ds_myemployeeid']);
  require('inc/doquery.php');
  if ($num_results)
  {
    $_SESSION['ds_teamid'] = $query_result[0]['teamid'];
    $_SESSION['ds_ismanager'] = $query_result[0]['ismanager']; # ismanager represents teamid
    $_SESSION['ds_unionrep'] = $query_result[0]['unionrep'];
  }
  else
  {
    $_SESSION['ds_teamid'] = 0;
    $_SESSION['ds_ismanager'] = 0;
    $_SESSION['ds_unionrep'] = 0;
  }
}

$query = 'select * from globalvariables where primaryunique=1';
$query_prm = array();
require ('inc/doquery.php');
$_SESSION['ds_customname'] = $query_result[0]['customname'];
if ($_SESSION['ds_customname'] == 'Wing Chong') { $_SESSION['ds_prevent_duplicate'] = 1; } # TODO option
else { $_SESSION['ds_prevent_duplicate'] = 0; }
$_SESSION['ds_prevent_duplicate_time'] = 0;
if ($_SESSION['ds_customname'] == 'Wing Chong') { $_SESSION['ds_invoicereport_menus'] = 0; } # TODO global option (its a user option)
$_SESSION['ds_confirm_remove_proforma'] = 1; # TODO option
if ($_SESSION['ds_customname'] == 'Fenua AC Cleaner')
{
  $_SESSION['ds_confirm_remove_proforma'] = 0;
}
$_SESSION['ds_store_quotes'] = 0; # TODO option
if ($_SESSION['ds_customname'] == 'Fenua AC Cleaner'
   || $_SESSION['ds_customname'] == 'TEM')
{
  $_SESSION['ds_store_quotes'] = 1;
}
$_SESSION['ds_invoice_display_by_family'] = 0;
if ($_SESSION['ds_customname'] == 'Tahiti Crew') # TODO option
{
  $_SESSION['ds_invoice_display_by_family'] = 1;
}
if ($_SESSION['ds_customname'] == 'TAHITI MARQUAGES') # TODO options
{
  $_SESSION['ds_select_itemcomment'] = 1; # TODO needs to restrict by product (example: does the color exist for the product?)
  $_SESSION['ds_beta_access'] = 1;
  $_SESSION['ds_show_unittotalvat'] = 1;
}
else
{
  $_SESSION['ds_beta_access'] = 0;
  $_SESSION['ds_select_itemcomment'] = 0;
  $_SESSION['ds_show_unittotalvat'] = 0;
}
if ($_SESSION['ds_customname'] == 'Fenua AC Cleaner'
|| $_SESSION['ds_customname'] == 'TEM') # TODO option
{
  $_SESSION['ds_discount_line'] = 1;
}
else { $_SESSION['ds_discount_line'] = 0; }
if ($_SESSION['ds_customname'] == 'TEM') { $_SESSION['ds_beta_access'] = 1; }
$_SESSION['ds_selectitem_length'] = 30;
if ($_SESSION['ds_customname'] == 'Wing Chong') { $_SESSION['ds_selectitem_length'] = 80; }
$_SESSION['invoicedirecttopayment_overpay'] = 0; # TODO option
if ($_SESSION['ds_customname'] == 'Terevau') { $_SESSION['invoicedirecttopayment_overpay'] = 1; }
if ($_SESSION['ds_customname'] == '') { $_SESSION['ds_customname'] = $companyinfo_companyname; } # TODO verify, is this a good idea?
# TODO !!! option for ds_use_warehouse
if ($_SESSION['ds_customname'] == 'Wing Chong') { $_SESSION['ds_use_warehouse'] = 1; }
else { $_SESSION['ds_use_warehouse'] = 0; }
# TODO !!! option for ds_restrict_sales_reports
if ($_SESSION['ds_customname'] == 'Wing Chong') { $_SESSION['ds_restrict_sales_reports'] = 1; }
else { $_SESSION['ds_restrict_sales_reports'] = 0; }
if ($_SESSION['ds_customname'] == 'Pacific Batiment') # TODO option
{
  $_SESSION['ds_payroll_startday'] = 26;
}
elseif ($_SESSION['ds_customname'] == 'Team ELEC')
{
  $_SESSION['ds_payroll_startday'] = 25;
}
else { $_SESSION['ds_payroll_startday'] = 0; }
$_SESSION['ds_term_invoiceclient2'] = '';
$_SESSION['ds_term_invoiceclient3'] = '';
if ($_SESSION['ds_customname'] == 'Terevau') # TODO option
{
  /* client changed their mind, fun stuff
  $_SESSION['ds_term_invoiceclient2'] = 'Expéditeur';
  $_SESSION['ds_term_invoiceclient3'] = 'Destinataire';
  */
}
$_SESSION['can_rebate_invoice'] = 1;
if ($_SESSION['ds_customname'] == 'Terevau' && $_SESSION['ds_systemaccess'] != 1) # TODO option
{
  $_SESSION['can_rebate_invoice'] = 0;
}
if ($_SESSION['ds_reportsaccess']) { $_SESSION['ds_restrict_sales_reports'] = 0; }
$_SESSION['ds_reconciliation_type'] = (int) $query_result[0]['reconciliation_type'];
$_SESSION['ds_globalise_vat'] = (int) $query_result[0]['globalise_vat'];
$_SESSION['ds_stockperuser'] = (int) $query_result[0]['stockperuser'];
$_SESSION['ds_rebate_listpricing'] = $query_result[0]['rebate_listpricing'];
$_SESSION['ds_use_interventions'] = $query_result[0]['use_interventions'];
$_SESSION['ds_use_loyalty_points'] = $query_result[0]['use_loyalty_points'];
$_SESSION['ds_loyalty_points_percent'] = 10; # TODO option
$_SESSION['ds_autocompleteoption'] = $query_result[0]['autocompleteoption'];
$_SESSION['ds_directtoacc'] = $query_result[0]['directtoacc'];
$_SESSION['ds_continuousstock'] = $query_result[0]['continuousstock'];
$_SESSION['ds_exportfields'] = $query_result[0]['exportfields'];
$_SESSION['ds_invoicedeductions'] = $query_result[0]['invoicedeductions'];
$_SESSION['ds_paybydateselect'] = $query_result[0]['paybydateselect'];
$_SESSION['ds_invoicetemplate'] = $query_result[0]['invoicetemplate'];
$_SESSION['ds_proformadefaultcomment'] = $query_result[0]['proformadefaultcomment'];
$_SESSION['ds_useserialnumbers'] = $query_result[0]['useserialnumbers'];
$_SESSION['ds_badpayeralert'] = $query_result[0]['badpayeralert'];
$_SESSION['ds_maxresults'] = $query_result[0]['maxresults'];
$_SESSION['ds_noretrodates'] = $query_result[0]['noretrodates'];
$_SESSION['ds_packinglisttop'] = $query_result[0]['packinglisttop'];
$_SESSION['ds_packinglistbottom'] = $query_result[0]['packinglistbottom'];
$_SESSION['ds_returnproductsaregeneric'] = $query_result[0]['returnproductsaregeneric'];
$_SESSION['ds_showtimeprinted'] = $query_result[0]['showtimeprinted'];
$_SESSION['ds_usenotice'] = $query_result[0]['usenotice'];
$_SESSION['ds_usedelivery'] = $query_result[0]['usedelivery'];
$_SESSION['ds_uselocalbol'] = $query_result[0]['uselocalbol'];
$_SESSION['ds_useitemadd'] = $query_result[0]['useitemadd'];
$_SESSION['ds_startyear'] = $query_result[0]['startyear'];
### Wing Chong custom   We would like to limit the access to Afficher Factures to current year +previous year only for all users except Direction
if ($_SESSION['ds_customname'] == 'Wing Chong' && !$_SESSION['ds_systemaccess'] && !$_SESSION['ds_accountingaccess'])
{
  if ((substr($_SESSION['ds_curdate'],0,4)-1) >= $_SESSION['ds_startyear']) { $_SESSION['ds_startyear'] = substr($_SESSION['ds_curdate'],0,4)-1; }
}
###
$_SESSION['ds_endyear'] = $query_result[0]['endyear'];
$_SESSION['ds_invoicelines'] = $query_result[0]['invoicelines'];
$_SESSION['ds_usedlv'] = $query_result[0]['usedlv'];
$_SESSION['ds_useemplacement'] = $query_result[0]['useemplacement'];
$_SESSION['ds_useunits'] = $query_result[0]['useunits'];
$_SESSION['ds_currencyname'] = $query_result[0]['currencyname'];
$_SESSION['ds_companyinfo'] = $query_result[0]['companyinfo'];
$_SESSION['ds_infofact'] = $query_result[0]['infofact'];
$_SESSION['ds_quote_info'] = $query_result[0]['quote_info'];
$_SESSION['ds_accounttop'] = $query_result[0]['accounttop'];
$_SESSION['ds_accountbottom'] = $query_result[0]['accountbottom'];
# what are these two lines? TODO check
$_SESSION['ds_accounttop'] = str_replace('$CURDATE$', datefix($_SESSION['ds_curdate']), $_SESSION['ds_accounttop']);
$_SESSION['ds_accountbottom'] = str_replace('$CURDATE$', datefix($_SESSION['ds_curdate']), $_SESSION['ds_accountbottom']);
$_SESSION['ds_useproductcode'] = $query_result[0]['useproductcode'];
$_SESSION['ds_autoproforma'] = $query_result[0]['autoproforma'];
$_SESSION['ds_showlinevat'] = $query_result[0]['showlinevat'];
$_SESSION['ds_defshowcomments'] = $query_result[0]['defshowcomments'];
$_SESSION['ds_confirmchangesdate'] = $query_result[0]['confirmchangesdate'];
$_SESSION['ds_usesofix'] = $query_result[0]['usesofix'];
if ($query_result[0]['defaultclientid'] > 0) { $_SESSION['ds_defaultclientid'] = $query_result[0]['defaultclientid']; }
$_SESSION['ds_dontshowstock'] = $query_result[0]['dontshowstock'];
$_SESSION['ds_unconfirmedcountsinstock'] = $query_result[0]['unconfirmedcountsinstock'];
$_SESSION['ds_useinvoicetag'] = $query_result[0]['useinvoicetag'];
$_SESSION['ds_displaydateandtime'] = $query_result[0]['displaydateandtime'];
if ($query_result[0]['hideaccountingdate'] == 1) { $_SESSION['ds_caninvoicedate'] = 0; } # set global caninvoicedate for all users
$_SESSION['ds_hidedeliverydate'] = $query_result[0]['hidedeliverydate']+0;
$_SESSION['ds_checktimes'] = $query_result[0]['checktimes']+0;
$_SESSION['ds_accountingalert'] = $query_result[0]['accountingalert']+0;
$_SESSION['ds_custominvoiceisdefault'] = $query_result[0]['custominvoiceisdefault'];
$_SESSION['ds_hidedefaultinvoice'] = $query_result[0]['hidedefaultinvoice'];
$_SESSION['ds_useretailprice'] = $query_result[0]['useretailprice'];
$_SESSION['ds_musthavecheckinput'] = $query_result[0]['musthavecheckinput'];
if ($_SESSION['ds_invoicedirecttopayment'] == 0)
{
  $_SESSION['ds_invoicedirecttopayment'] = $query_result[0]['invoicedirecttopayment']+0;
}
$_SESSION['ds_allowinvoiceshare'] = $query_result[0]['allowinvoiceshare'];
$_SESSION['ds_salestrace'] = $query_result[0]['salestrace'];
$_SESSION['ds_printcheck'] = $query_result[0]['printcheck']+0;
$_SESSION['ds_showinvoice_position_logo_default'] =  $query_result[0]['showinvoice_position_logo_default']+0;
$_SESSION['ds_showinvoice_position_client_information_default'] = $query_result[0]['showinvoice_position_client_information_default']+0;
$_SESSION['ds_showinvoice_position_title_invoice_default'] =  $query_result[0]['showinvoice_position_title_invoice_default']+0;
$_SESSION['ds_showinvoice_dateformat'] = $query_result[0]['showinvoice_dateformat']+0;
$_SESSION['ds_time_management'] = (int) $query_result[0]['time_management'];
if ($_SESSION['ds_use_invoiceitemgroup'] == 0) { $_SESSION['ds_use_invoiceitemgroup'] = (int) $query_result[0]['use_invoiceitemgroup']; }
$_SESSION['ds_use_invoice_sig'] = (int) $query_result[0]['use_invoice_sig'];

$query = 'select * from globalvariables_hr where primaryunique=1'; # TODO remove table?
$query_prm = array();
require ('inc/doquery.php');
$_SESSION['ds_employeenamedisplay'] = $query_result[0]['employeenamedisplay'];

$_SESSION['ds_use_salesprice_mod'] = 0;
require('preload/invoice_priceoption1.php'); if (isset($invoice_priceoption1A)) { $_SESSION['ds_use_salesprice_mod'] = 1; }

# globalterms
$query = 'select * from globalterms where primaryunique=1';
$query_prm = array();
require ('inc/doquery.php');
$_SESSION['ds_term_accounting_tag'] = $query_result[0]['term_accounting_tag'];
$_SESSION['ds_term_invoice_priceoption1'] = $query_result[0]['term_invoice_priceoption1'];
$_SESSION['ds_term_invoice_priceoption2'] = $query_result[0]['term_invoice_priceoption2'];
$_SESSION['ds_term_invoice_priceoption3'] = $query_result[0]['term_invoice_priceoption3'];
$_SESSION['ds_term_invoice'] = $query_result[0]['term_invoice'];
$_SESSION['ds_term_accountingdate'] = $query_result[0]['term_accountingdate'];
if ($_SESSION['ds_term_accountingdate'] == "") { $_SESSION['ds_term_accountingdate'] = 'Date'; }
$_SESSION['ds_term_deliverydate'] = $query_result[0]['term_deliverydate'];
if ($_SESSION['ds_term_deliverydate'] == "") { $_SESSION['ds_term_deliverydate'] = 'Livraison'; }
$_SESSION['ds_term_extraname'] = $query_result[0]['term_extraname'];
if ($_SESSION['ds_term_extraname'] == "") { $_SESSION['ds_term_extraname'] = 'Extension du Nom'; }
$_SESSION['ds_term_reference'] = $query_result[0]['term_reference'];
if ($_SESSION['ds_term_reference'] == "") { $_SESSION['ds_term_reference'] = 'Référence'; }
$_SESSION['ds_term_invoicetag'] = $query_result[0]['term_invoicetag'];
#if ($_SESSION['ds_term_invoicetag'] == "") { $_SESSION['ds_term_invoicetag'] = 'Tag'; }
$_SESSION['ds_term_servedby'] = $query_result[0]['term_servedby'];
if ($_SESSION['ds_term_servedby'] == "") { $_SESSION['ds_term_servedby'] = 'Servi par'; }
$_SESSION['ds_term_clientemployee1'] = $query_result[0]['term_clientemployee1'];
$_SESSION['ds_term_clientemployee2'] = $query_result[0]['term_clientemployee2'];
$_SESSION['ds_term_invoicenotice'] = $query_result[0]['term_invoicenotice'];
$_SESSION['ds_term_prixalternatif'] = $query_result[0]['term_prixalternatif'];
$_SESSION['ds_term_localvessel'] = $query_result[0]['term_localvessel'];
$_SESSION['ds_term_field1'] = $query_result[0]['term_field1'];
$_SESSION['ds_term_field2'] = $query_result[0]['term_field2'];
$_SESSION['ds_term_paymfield1'] = $query_result[0]['term_paymfield1'];
$_SESSION['ds_term_paymfield2'] = $query_result[0]['term_paymfield2'];
$_SESSION['ds_term_clientactionfield1'] = $query_result[0]['term_clientactionfield1'];
$_SESSION['ds_term_productsubunit'] = $query_result[0]['term_productsubunit'];
$_SESSION['ds_term_manager'] = $query_result[0]['term_manager'];
$_SESSION['ds_term_interimmanager'] = $query_result[0]['term_interimmanager'];
$_SESSION['ds_term_accounting_comment'] = $query_result[0]['term_accounting_comment'];
$_SESSION['ds_term_accounting_reference'] = $query_result[0]['term_accounting_reference'];
$_SESSION['ds_term_employeedepartment'] = $query_result[0]['term_employeedepartment'];
$_SESSION['ds_term_employeesection'] = $query_result[0]['term_employeesection'];
$_SESSION['ds_term_custominvoicedate'] = $query_result[0]['term_custominvoicedate'];
$_SESSION['ds_term_invoicetag2'] = $query_result[0]['term_invoicetag2'];
$_SESSION['ds_term_clientcategory'] = $query_result[0]['term_clientcategory'];
$_SESSION['ds_term_clientcategory2'] = $query_result[0]['term_clientcategory2'];
$_SESSION['ds_term_clientcategory3'] = $query_result[0]['term_clientcategory3'];
$_SESSION['ds_term_interventionfield1'] = $query_result[0]['term_interventionfield1'];
$_SESSION['ds_term_interventionfield2'] = $query_result[0]['term_interventionfield2'];
$_SESSION['ds_term_interventionfield3'] = $query_result[0]['term_interventionfield3'];
$_SESSION['ds_term_interventionfield4'] = $query_result[0]['term_interventionfield4'];
$_SESSION['ds_term_intervention_tag1'] = $query_result[0]['term_intervention_tag1'];
$_SESSION['ds_term_intervention_tag2'] = $query_result[0]['term_intervention_tag2'];
$_SESSION['ds_term_intervention_value1'] = $query_result[0]['term_intervention_value1'];
$_SESSION['ds_term_intervention_value2'] = $query_result[0]['term_intervention_value2'];
$_SESSION['ds_term_intervention_value3'] = $query_result[0]['term_intervention_value3'];
$_SESSION['ds_term_intervention_value4'] = $query_result[0]['term_intervention_value4'];
$_SESSION['ds_term_discontinued'] = $query_result[0]['term_discontinued'];
$_SESSION['ds_term_clientfield1'] = $query_result[0]['term_clientfield1'];
$_SESSION['ds_term_clientfield2'] = $query_result[0]['term_clientfield2'];
$_SESSION['ds_term_clientfield3'] = $query_result[0]['term_clientfield3'];
$_SESSION['ds_term_clientfield4'] = $query_result[0]['term_clientfield4'];
$_SESSION['ds_term_clientfield5'] = $query_result[0]['term_clientfield5'];
$_SESSION['ds_term_clientfield6'] = $query_result[0]['term_clientfield6'];
$_SESSION['ds_term_client_customdate1'] = $query_result[0]['term_client_customdate1'];
$_SESSION['ds_term_client_customdate2'] = $query_result[0]['term_client_customdate2'];
$_SESSION['ds_term_client_customdate3'] = $query_result[0]['term_client_customdate3'];
$_SESSION['ds_term_productactionfield1'] = $query_result[0]['term_productactionfield1'];
$_SESSION['ds_term_productactiontag'] = $query_result[0]['term_productactiontag'];
$_SESSION['ds_term_client_telephone'] = $query_result[0]['term_client_telephone'];
$_SESSION['ds_term_client_cellphone'] = $query_result[0]['term_client_cellphone'];
$_SESSION['ds_term_client_telephone3'] = $query_result[0]['term_client_telephone3'];
$_SESSION['ds_term_client_telephone4'] = $query_result[0]['term_client_telephone4'];
$_SESSION['ds_term_client_email'] = $query_result[0]['term_client_email'];
$_SESSION['ds_term_client_email2'] = $query_result[0]['term_client_email2'];
$_SESSION['ds_term_client_email3'] = $query_result[0]['term_client_email3'];
$_SESSION['ds_term_client_email4'] = $query_result[0]['term_client_email4'];

?>
