<?php

$PA['item'] = '';
$PA['action'] = '';
$PA['deleted'] = 'uint';
require('inc/readpost.php');

$item = d_safebasename($item);
$successtext = 'successmod' . $item . ':';

if ($action == 'save')
{
  $ok = 1;
  $updateplanning = 0;  
  switch ($item)
  {
    case 'interventionitemtag1':
      $query = 'update interventionitemtag1 set interventionitemtag1name=?, deleted=? '
               . 'where interventionitemtag1id=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'interventionitemtag2':
      $query = 'update interventionitemtag2 set interventionitemtag2name=?, deleted=? '
               . 'where interventionitemtag2id=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'advance':
      $query = 'update advance set advancename=?,deleted=?,advance_percentage=? where advanceid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['advance_percentage'], $_POST['id']);
    break;
    case 'adjustmentgroup_tag':
      $query = 'update adjustmentgroup_tag set adjustmentgroup_tagname=?,deleted=? where adjustmentgroup_tagid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'color':
      $query = 'update color set colorname=?,deleted=?,colorcode=? where colorid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['colorcode'], $_POST['id']);
    break;
    case 'palette':
      $query = 'update palette set palettename=?,deleted=? where paletteid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'invoice_priceoption1':
      $query = 'update invoice_priceoption1 set invoice_priceoption1name=?,deleted=?,salesprice_mod=?,`rank`=?
      where invoice_priceoption1id=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['salesprice_mod'], $_POST['rank'], $_POST['id']);
    break;
    case 'invoice_priceoption2':
      $query = 'update invoice_priceoption2 set invoice_priceoption2name=?,deleted=?,salesprice_mod=?,`rank`=?
      where invoice_priceoption2id=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['salesprice_mod'], $_POST['rank'], $_POST['id']);
    break;
    case 'invoice_priceoption3':
      $query = 'update invoice_priceoption3 set invoice_priceoption3name=?,deleted=?,salesprice_mod=?,`rank`=?
      where invoice_priceoption3id=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['salesprice_mod'], $_POST['rank'], $_POST['id']);
    break;
    case 'select_itemcomment':
      $query = 'update select_itemcomment set select_itemcommentname=?,deleted=? where select_itemcommentid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'journal':
      $query = 'update journal set journalname=?,deleted=? where journalid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'net_modif_account':
      $query = 'update net_modif_account set net_modif_accountname=?,accountingnumberid=?,deleted=? where net_modif_accountid=?';
      $query_prm = array($_POST['name'], $_POST['accountingnumberid'], $deleted, $_POST['id']);
    break;
    case 'reason_payment_modify':
      $query = 'update reason_payment_modify set reason_payment_modifyname=?,deleted=? where reason_payment_modifyid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'productactiontag':
      $query = 'update productactiontag set productactiontagname=?,deleted=? where productactiontagid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'productactioncat':
      $query = 'update productactioncat set productactioncatname=?,deleted=? where productactioncatid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'competitor':
      $query = 'update competitor set competitorname=?,deleted=? where competitorid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'team':
      $query = 'update team set teamname=?,deleted=? where teamid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'bank':
      $query = 'update bank set bankname=?,fullbankname=?,deleted=? where bankid=?';
      $query_prm = array($_POST['name'], $_POST['fullbankname'], $deleted, $_POST['id']);
    break;
    case 'bankaccount':
      $query = 'update bankaccount set bankaccountname=?,bankid=?,accountingnumberid=?,deleted=? where bankaccountid=?';
      $query_prm = array($_POST['name'], $_POST['bankid'], $_POST['accountingnumberid'], $deleted, $_POST['id']);
    break;
    case 'clientcategory':
      $query = 'update clientcategory set clientcategoryname=?,clientcategorygroupid=?,deleted=? where clientcategoryid=?';
      $query_prm = array($_POST['name'] ,$_POST['clientcategorygroupid'],$deleted, $_POST['id']);
    break;
    case 'clientcategory2':
      $query = 'update clientcategory2 set clientcategory2name=?,clientcategorygroup2id=?,deleted=? where clientcategory2id=?';
      $query_prm = array($_POST['name'],$_POST['clientcategorygroup2id'], $deleted, $_POST['id']);
    break;
    case 'clientcategory3':
      $query = 'update clientcategory3 set clientcategory3name=?,clientcategorygroup3id=?,deleted=? where clientcategory3id=?';
      $query_prm = array($_POST['name'],$_POST['clientcategorygroup3id'], $deleted, $_POST['id']);
    break;
		case 'clientcategorygroup':
      $query = 'update clientcategorygroup set clientcategorygroupname=?,`rank`=?, deleted=? where clientcategorygroupid=?';
      $query_prm = array($_POST['name'] ,$_POST['rank'],$deleted, $_POST['id']);
    break;
    case 'clientcategory2':
      $query = 'update clientcategory2 setgroupclientcategorygroup2name=?,`rank`=?,deleted=? where clientcategorygroup2id=?';
      $query_prm = array($_POST['name'], $_POST['rank'],$deleted, $_POST['id']);
    break;
    case 'clientcategory3':
      $query = 'update clientcategory3 setgroupclientcategorygroup3name=?,`rank`=?,deleted=? where clientcategorygroup3id=?';
      $query_prm = array($_POST['name'], $_POST['rank'],$deleted, $_POST['id']);
    break;
    case 'clientactioncat':
      $query = 'update clientactioncat set clientactioncatname=?,deleted=? where clientactioncatid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'clientsector':
      $query = 'update clientsector set clientsectorname=?,clientsectorrank=?,deleted=? where clientsectorid=?';
      $query_prm = array($_POST['name'], $_POST['rank'], $deleted, $_POST['id']);
    break;
    case 'clientschedulecat':
      $query = 'update clientschedulecat set clientschedulecatname=?,deleted=? where clientschedulecatid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'commissionrate':
      $query = 'update commissionrate set commissionrate=?,deleted=? where commissionrateid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'companytransport':
      $query = 'update companytransport set companytransportname=?,deleted=? where companytransportid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;     
    case 'localvessel':
      $query = 'update localvessel set localvesselname=?,deleted=? where localvesselid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'modifiedstockreason':
      $query = 'update modifiedstockreason set modifiedstockreasonname=?,deleted=? where modifiedstockreasonid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'returnreason':
      $query = 'update returnreason set returnreasonname=?,returntostock=?,deleted=? where returnreasonid=?';
      $query_prm = array($_POST['name'], ($_POST['returntostock']+0), $deleted, $_POST['id']);
    break;
    case 'warehousereason':
      $query = 'update warehousereason set warehousereasonname=?,deleted=? where warehousereasonid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'country':
      $query = 'update country set countryname=?,sofixcode=?,fenixcode=?,deleted=?,`rank`=? where countryid=?';
      $query_prm = array($_POST['name'], mb_substr($_POST['sofixcode'],0,3), mb_substr($_POST['fenixcode'],0,3), $deleted, $_POST['rank'], $_POST['id']);
    break;
    case 'unittype':
      $query = 'update unittype set unittypename=?,displaymultiplier=?,deleted=? where unittypeid=?';
      $query_prm = array($_POST['name'], $_POST['displaymultiplier'], $deleted, $_POST['id']);
    break;
    case 'invoicetag':
      require('inc/findclient.php');
      $query = 'update invoicetag set invoicetagname=?,invoicetag_clientid=?,deleted=? where invoicetagid=?';
      $query_prm = array($_POST['name'], $clientid, $deleted, $_POST['id']);
      $successtext = 'modifiedparam:';      
    break;
    case 'invoicetag2':
      $query = 'update invoicetag2 set invoicetag2name=?,daysaddedtocustomdate=?,deleted=? where invoicetag2id=?';
      $query_prm = array($_POST['name'], $_POST['daysaddedtocustomdate'], $deleted, $_POST['id']);
      $successtext = 'modifiedparam:';
    break;
    case 'paymentcategory':
      $query = 'update paymentcategory set paymentcategoryname=?,deleted=? where paymentcategoryid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'vessel':
      $query = 'update vessel set vesselname=?,deleted=? where vesselid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'currency':
      $query = 'update currency set currencyname=?,currencyacronym=?,deleted=? where currencyid=?';
      $query_prm = array($_POST['name'], mb_substr($_POST['currencyacronym'],0,3), $deleted, $_POST['id']);
    break;
    case 'resource':
      $query = 'update resource set resourcename=?,deleted=? where resourceid=?';
      $query_prm = array($_POST['name'], $deleted, $_POST['id']);
    break;
    case 'qr_location':
      require('inc/findclient.php');
      $query = 'update qr_location set qr_locationname=?,clientid=?,deleted=? where qr_locationid=?';
      $query_prm = array($_POST['name'], $clientid+0, $deleted, $_POST['id']);
    break;
    default:
      $ok = 0;
    break;
  }
  if ($ok)
  {
    require ('inc/doquery.php');
    if ($num_results)
    {
      if($item == 'invoicetag' || $item == 'invoicetag2')
      {
        echo '<p>' . d_trad($successtext,array($_SESSION['ds_term_' . $item],d_output($_POST['name']))) . '</p><br>';              
      }
      else        
      { 
        echo '<p>' . d_trad($successtext,d_output($_POST['name'])) . '</p>'; 
      }
    }
  }
}
$itemname = d_trad($item);
$title = d_trad('modifyparam:',$itemname);
$nametitle = d_trad('name:');
$tablename = $item;
switch ($item)
{
  case 'invoice_priceoption1':
  case 'invoice_priceoption2':
  case 'invoice_priceoption3':    
    $itemname = $_SESSION['ds_term_'.$item];
    $nametitle = d_trad('param:',$itemname);
    $title = d_trad('modifyparam:',$itemname);      
    break;
  case 'bank':
    $nametitle = d_trad('initials2:');
    break;
  case 'bankaccount':
    $nametitle = d_trad('account:');
    break;    
  case 'invoicetag':
    $itemname = $_SESSION['ds_term_'.$item];
    $nametitle = d_trad('param:',$itemname);
    $title = d_trad('modifyparam:',$itemname);      
    break;
  case 'invoicetag2':
    $itemname = $_SESSION['ds_term_'.$item];
    $nametitle = d_trad('param:',$itemname);
    $title = d_trad('modifyparam:',$itemname);      
    break;
  case 'paymentcategory':
  case 'vessel':  
  case 'localvessel':  
  case 'currency':  
  case 'companytransport':  
  case 'commissionrate':  
  case 'modifiedstockreason':  
  case 'resource':  
  case 'returnreason':  
  case 'warehousereason':
  case 'qr_location':
    $nameitem = d_trad($item);
    $nametitle = d_trad('param:',$nameitem);
    break;      
  default:
    $nametitle = d_trad('name:');
}
if (isset($title)) { echo '<h2>' . $title . '</h2>'; }


if ($action == 'mod')
{  
  $id = $_POST['id']+0;
  $query = 'select * from ' . $tablename . ' where ' . $tablename . 'id=?';
  $query_prm = array($id);
  require ('inc/doquery.php');
  $item_query = $query_result;
  if ($num_results > 0)
  {
    $name = $item_query[0][$tablename . 'name'];
    if ($item == 'commissionrate') { $name = $item_query[0][$tablename]; }
    echo '<form method="post" action="admin.php"><table>';
    echo '<tr><td>' . $nametitle . '</td><td><input autofocus type="text" name="name" value="' . d_input($name) . '" size=50></td></tr>';
  }
  else 
  { 
    $itemname = d_trad($item);
    $action = d_trad('nodata',$itemname); 
  }
  if ($item == 'invoice_priceoption1' || $item == 'invoice_priceoption2' || $item == 'invoice_priceoption3')
  {
    echo '<tr><td>Modif Prix<td><input type="text" name="salesprice_mod" size=50 value="' . d_input($item_query[0]['salesprice_mod']) . '">';
    echo '<tr><td>' . d_trad('rank:') . '<td><input type="text" name="rank" size=50 value="' . d_input($item_query[0]['rank']) . '">';
  }
  if ($item == 'advance')
  {
    echo '<tr><td>Pourcentage:<td><input type="number" name="advance_percentage"
    value="' . d_input($item_query[0]['advance_percentage']) . '" size=8>';
  }
  if ($item == 'color')
  {
    echo '<tr><td>Code:<td><input type="text" name="colorcode"
    value="' . d_input($item_query[0]['colorcode']) . '" size=8>';
  }
  if ($item == 'bank')
  {
    echo '<tr><td>' . d_trad('name:') . '</td><td><input type="text" name="fullbankname" value="' . d_input($item_query[0]['fullbankname']) . '" size=50></td></tr>';
  }
  if ($item == 'currency')
  {
    echo '<tr><td>' . d_trad('acronym:') . '</td><td><input type="text" name="currencyacronym" value="' . d_input($item_query[0]['currencyacronym']) . '" size=5></td></tr>';
  }
  if ($item == 'bankaccount')
  {
    $dp_itemname = 'bank'; $dp_description = d_trad('bank'); $dp_allowall = 0; $dp_blank = 1; $dp_selectedid = $item_query[0]['bankid'];
    require('inc/selectitem.php');
    $dp_itemname = 'accountingnumber'; $dp_description = 'Compte (Compta)'; $dp_allowall = 0; $dp_blank = 1; $dp_selectedid = $item_query[0]['accountingnumberid'];
    require('inc/selectitem.php');
  }
  if ($item == 'clientsector')
  {
    echo '<tr><td>' . d_trad('rank:') . '</td><td><input type="text" name="rank" value="' . d_input($item_query[0]['clientsectorrank']) . '" size=10></td></tr>';
  }
	if ($item == 'clientcategory')
  {
    $dp_itemname = 'clientcategorygroup'; $dp_description = d_trad('clientcategorygroup'); $dp_allowall = 0; $dp_blank = 1; $dp_selectedid = $item_query[0]['clientcategorygroupid'];
    require('inc/selectitem.php');
  }	
	if ($item == 'clientcategory2')
  {
    $dp_itemname = 'clientcategorygroup2'; $dp_description = d_trad('clientcategorygroup2'); $dp_allowall = 0; $dp_blank = 1; $dp_selectedid = $item_query[0]['clientcategorygroup3id'];
    require('inc/selectitem.php');
  }
  if ($item == 'clientcategory3')
  {
    $dp_itemname = 'clientcategorygroup3'; $dp_description = d_trad('clientcategorygroup3'); $dp_allowall = 0; $dp_blank = 1; $dp_selectedid = $item_query[0]['clientcategorygroup3id'];
    require('inc/selectitem.php');
  }
  if ($item == 'clientcategorygroup' || $item == 'clientcategorygroup2' || $item == 'clientcategorygroup3')
  {
    echo '<tr><td>' . d_trad('rank:') . '</td><td><input type="text" name="rank" value="' . d_input($item_query[0]['rank']) . '" size=10></td></tr>';
  }	
  if ($item == 'country')
  {
    echo '<tr><td>' . d_trad('sofixcode:') . '</td><td><input type="text" name="sofixcode" value="' . d_input($item_query[0]['sofixcode']) . '" size=10></td></tr>';
    echo '<tr><td>Code Fenix:</td><td><input type="text" name="fenixcode" value="' . d_input($item_query[0]['fenixcode']) . '" size=10></td></tr>';
    echo '<tr><td>' . d_trad('rank:') . '</td><td><input type="text" STYLE="text-align:right" name="rank" value="' . d_input($item_query[0]['rank']) . '" size=5></td></tr>';
  }
  if ($item == 'returnreason')
  {
    echo '<tr><td>Remettre en stock</td><td><input type="checkbox" name="returntostock" value=1';
    if ($item_query[0]['returntostock'] == 1) { echo ' checked'; }
    echo '></td></tr>';
  }
  if ($item == 'invoicetag')
  {
    echo '<tr><td>Limité au client :<td>'; $dp_nodescription = 1; $clientid = $item_query[0]['invoicetag_clientid'];
    require('inc/selectclient.php');
  }
  if ($item == 'invoicetag2')
  {
    echo '<tr><td>Jours ajouté au date customisé:</td><td><input type="text" name="daysaddedtocustomdate" value="' . d_input($item_query[0]['daysaddedtocustomdate']) . '" size=6></td></tr>';
  }
  if ($item == 'net_modif_account')
  {
    $dp_itemname = 'accountingnumber'; $dp_description = 'Compte'; $dp_allowall = 0; $dp_blank = 1; $dp_selectedid = $item_query[0]['accountingnumberid'];
    require('inc/selectitem.php');
  }
  if ($item == 'unittype')
  {
    echo '<tr><td>Multiplicateur:</td><td><input type="number" min=1 name="displaymultiplier" value="' . d_input($item_query[0]['displaymultiplier']) . '">';
  }
  if ($item == 'qr_location')
  {
    $clientid = $item_query[0]['clientid'];
    echo '<tr><td>';require('inc/selectclient.php');
  }
  echo '<tr><td>' . d_trad('deleted') . ':</td><td><input type="checkbox" name="deleted" value="1"'; if ($item_query[0]['deleted'] == 1) echo ' CHECKED'; echo '></td></tr>';
  echo '<tr><td colspan="2" align="center"><input type=hidden name="action" value="save"><input type=hidden name="item" value="' . $item . '"><input type=hidden name="adminmenu" value="' . $adminmenu . '"><input type=hidden name="id" value="' . $id . '"><input type="submit" value="' . d_trad('save') . '"></td></tr>';
  echo '</table></form>';
}

if ($action != 'mod')
{
  $ok = 1;
  switch ($item)
  {
    case 'interventionitemtag1':
      require('preload/interventionitemtag1.php');
      $nameA = $interventionitemtag1A;
      $deletedA = $interventionitemtag1_deletedA;
    break;
    case 'interventionitemtag2':
      require('preload/interventionitemtag2.php');
      $nameA = $interventionitemtag2A;
      $deletedA = $interventionitemtag2_deletedA;
    break;
    case 'advance':
      require('preload/advance.php');
      $nameA = $advanceA;
      $deletedA = $advance_deletedA;
    break;
    case 'adjustmentgroup_tag':
      require('preload/adjustmentgroup_tag.php');
      $nameA = $adjustmentgroup_tagA;
      $deletedA = $adjustmentgroup_tag_deletedA;
    break;
    case 'color':
      require('preload/color.php');
      $nameA = $colorA;
      $deletedA = $color_deletedA;
    break;
    case 'palette':
      require('preload/palette.php');
      $nameA = $paletteA;
      $deletedA = $palette_deletedA;
    break;
    case 'invoice_priceoption1':
      require('preload/invoice_priceoption1.php');
      $nameA = $invoice_priceoption1A;
      $deletedA = $invoice_priceoption1_deletedA;
    break;
    case 'invoice_priceoption2':
      require('preload/invoice_priceoption2.php');
      $nameA = $invoice_priceoption2A;
      $deletedA = $invoice_priceoption2_deletedA;
    break;
    case 'invoice_priceoption3':
      require('preload/invoice_priceoption3.php');
      $nameA = $invoice_priceoption3A;
      $deletedA = $invoice_priceoption3_deletedA;
    break;
    case 'select_itemcomment':
      require('preload/select_itemcomment.php');
      $nameA = $select_itemcommentA;
      $deletedA = $select_itemcomment_deletedA;
    break;
    case 'journal':
      require('preload/journal.php');
      $nameA = $journalA;
      $deletedA = $journal_deletedA;
    break;
    case 'net_modif_account':
      require('preload/net_modif_account.php');
      require('preload/accountingnumber.php');
      $nameA = $net_modif_accountA;
      $deletedA = $net_modif_account_deletedA;
    break;
    case 'reason_payment_modify':
      require('preload/reason_payment_modify.php');
      $nameA = $reason_payment_modifyA;
      $deletedA = $reason_payment_modify_deletedA;
    break;
    case 'productactiontag':
      require('preload/productactiontag.php');
      $nameA = $productactiontagA;
      $deletedA = $productactiontag_deletedA;
    break;
    case 'productactioncat':
      require('preload/productactioncat.php');
      $nameA = $productactioncatA;
      $deletedA = $productactioncat_deletedA;
    break;
    case 'competitor':
      require('preload/competitor.php');
      $nameA = $competitorA;
      $deletedA = $competitor_deletedA;
    break;
    case 'team':
      require('preload/team.php');
      $nameA = $teamA;
      $deletedA = $team_deletedA;
    break;
    case 'bank':
      if (!isset($bankA)) { require('preload/bank.php');}
      $nameA = $bankA;
      $deletedA = $bank_deletedA;
    break;
    case 'bankaccount':
      if (!isset($bankaccountA)) {require('preload/bankaccount.php');}
      $nameA = $bankaccountA;
      $deletedA = $bankaccount_deletedA;
      require('preload/bank.php');
    break;
    case 'clientcategory':
      if (!isset($clientcategoryA)) {require('preload/clientcategory.php');}
      $nameA = $clientcategoryA;
      $deletedA = $client_deletedA;
    break;
    case 'clientcategory2':
      if (!isset($clientcategory2A)) {require('preload/clientcategory2.php');}
      $nameA = $clientcategory2A;
      $deletedA = $clientcategory2_deletedA;
    break;
    case 'clientcategory3':
      if (!isset($clientcategory3A)) {require('preload/clientcategory3.php');}
      $nameA = $clientcategory3A;
      $deletedA = $clientcategory3_deletedA;
    break;
		case 'clientcategorygroup':
      if (!isset($clientcategorygroupA)) {require('preload/clientcategorygroup.php');}
      $nameA = $clientcategorygroupA;
      $deletedA = $clientcategorygroupA_deletedA;
    break;
    case 'clientcategorygroup2':
      if (!isset($clientcategorygroup2A)) {require('preload/clientcategorygroup2.php');}
      $nameA = $clientcategorygroup2A;
      $deletedA = $clientcategorygroup2_deletedA;
    break;
    case 'clientcategorygroup3':
      if (!isset($clientcategorygroup2A)) {require('preload/clientcategorygroup3.php');}
      $nameA = $clientcategorygroup3A;
      $deletedA = $clientcategorygroup3_deletedA;
    break;
    case 'clientactioncat':
      if (!isset($clientactioncatA)) {require('preload/clientactioncat.php');}
      $nameA = $clientactioncatA;
      $deletedA = $clientactioncat_deletedA;
    break;
    case 'clientsector':
      if (!isset($clientsectorA)) {require('preload/clientsector.php');}
      $nameA = $clientsectorA;
      $deletedA = $clientsector_deletedA;
    break;
    case 'clientschedulecat':
      if (!isset($clientschedulecatA)) {require('preload/clientschedulecat.php');}
      $nameA = $clientschedulecatA;
      $deletedA = $clientschedulecat_deletedA;
    break;
    case 'commissionrate':
      if (!isset($commissionrateA)) {require('preload/commissionrate.php');}
      $nameA = $commissionrateA;
      $deletedA = $commissionrate_deletedA;
    break;
    case 'companytransport':
      if (!isset($companytransportA)) {require('preload/companytransport.php');}
      $nameA = $companytransportA;
      $deletedA = $companytransport_deletedA;
    break;
    case 'localvessel':
      if (!isset($localvesselA)) {require('preload/localvessel.php');}
      $nameA = $localvesselA;
      $deletedA = $localvessel_deletedA;
    break;
    case 'modifiedstockreason':
      if (!isset($modifiedstockreasonA)) {require('preload/modifiedstockreason.php');}
      $nameA = $modifiedstockreasonA;
      $deletedA = $modifiedstockreason_deletedA;
    break;
    case 'returnreason':
      if (!isset($returnreasonA)) {require('preload/returnreason.php');}
      $nameA = $returnreasonA;
      $deletedA = $returnreason_deletedA;
    break;
    
    case 'warehousereason':
      if (!isset($warehousereasonA)) {require('preload/warehousereason.php');}
      $nameA = $warehousereasonA;
      $deletedA = $warehousereason_deletedA;
    break;
    
    case 'resource':
      if (!isset($resourceA)) {require('preload/resource.php');}
      $nameA = $resourceA;
      $deletedA = $resource_deletedA;
    break;
    case 'qr_location':
      if (!isset($qr_locationA)) {require('preload/qr_location.php');}
      $nameA = $qr_locationA;
      $deletedA = $qr_location_deletedA;
    break;
    case 'country':
      if (!isset($countryA)) {require('preload/country.php');}
      $nameA = $countryA;
      $deletedA = $country_deletedA;
    break;
    case 'unittype':
      if (!isset($unittypeA)) {require('preload/unittype.php');}
      $nameA = $unittypeA;
      $deletedA = $unittype_deletedA;
    break;
    case 'invoicetag':
      if (!isset($invoicetagA)) {require('preload/invoicetag.php');}
      $nameA = $invoicetagA;
      $deletedA = $invoicetag_deletedA;
    break;
    case 'invoicetag2':
      if (!isset($invoicetag2A)) {require('preload/invoicetag2.php');}
      $nameA = $invoicetag2A;
      $deletedA = $invoicetag2_deletedA;
    break;
    case 'paymentcategory':
      if (!isset($paymentcategoryA)) {require('preload/paymentcategory.php');}
      $nameA = $paymentcategoryA;
      $deletedA = $paymentcategory_deletedA;
    break;
    case 'vessel':
      if (!isset($vesselA)) {require('preload/vessel.php');}
      $nameA = $vesselA;
      $deletedA = $vessel_deletedA;
    break;
    case 'currency':
      if (!isset($currencyA)) {require('preload/currency.php');}
      $nameA = $currencyA;
      $deletedA = $currency_deletedA;
    break;
    default:
      $ok = 0;
    break;
  }
  if ($ok && isset($nameA))
  {
    echo '<form method="post" action="admin.php"><table>';
    echo '<tr><td>' . $nametitle . '</td><td><select name="id">';
    foreach ($nameA as $id => $name)
    {
      echo '<option value="' . $id . '">';
      if ($item == 'bankaccount')
      {
        $bankid = $bankaccount_bankidA[$id];
        echo $bankA[$bankid] . ': ';
      }
      if ($deletedA[$id]) 
      { 
        echo d_trad('deletedsquarebrackets',d_output($name)); 
      }
      else
      {
        echo d_output($name);
      }
      echo '</option>';
    }
    echo '</select></td></tr>';
    echo '<tr><td colspan="2" align="center"><input type=hidden name="action" value="mod"><input type=hidden name="item" value="' . $item . '"><input type=hidden name="adminmenu" value="' . $adminmenu . '"><input type="submit" value="' . d_trad('continue') . '"></td></tr>';
    echo '</table></form>';
  }
}

?>