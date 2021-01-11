<?php

$PA['item'] = '';
$PA['action'] = '';
require('inc/readpost.php');
$item = d_safebasename($item);

if ($action == 'save')
{
  $ok = 1;
  switch ($item)
  {
    case 'advance':
      $query = 'insert into advance (advancename,advance_percentage) values(?,?)';
      $query_prm = array($_POST['name'],$_POST['advance_percentage']);
      $successtext = 'successaddadvance:';
    break;
    case 'adjustmentgroup_tag':
      $query = 'insert into adjustmentgroup_tag (adjustmentgroup_tagname) values(?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddadjustmentgroup_tag:';
    break;
    case 'color':
      $query = 'insert into color (colorname,colorcode) values(?,?)';
      $query_prm = array($_POST['name'],$_POST['colorcode']);
      $successtext = 'successaddcolor:';
    break;
    case 'palette':
      $query = 'insert into palette (palettename) values(?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddpalette:';
    break;
    case 'invoice_priceoption1':
      $query = 'insert into invoice_priceoption1 (invoice_priceoption1name,salesprice_mod,`rank`) values(?,?,?)';
      $query_prm = array($_POST['name'],$_POST['salesprice_mod'],$_POST['rank']);
      $successtext = 'successaddparam:';
    break;
    case 'invoice_priceoption2':
      $query = 'insert into invoice_priceoption2 (invoice_priceoption2name,salesprice_mod,`rank`) values(?,?,?)';
      $query_prm = array($_POST['name'],$_POST['salesprice_mod'],$_POST['rank']);
      $successtext = 'successaddinvoice_priceoption2:';
    break;
    case 'invoice_priceoption3':
      $query = 'insert into invoice_priceoption3 (invoice_priceoption3name,salesprice_mod,`rank`) values(?,?,?)';
      $query_prm = array($_POST['name'],$_POST['salesprice_mod'],$_POST['rank']);
      $successtext = 'successaddinvoice_priceoption3:';
    break;
    case 'select_itemcomment':
      $query = 'insert into select_itemcomment (select_itemcommentname) values(?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddselect_itemcomment:';
    break;
    case 'journal':
      $query = 'insert into journal (journalname) values(?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddjournal:';
    break;
    case 'net_modif_account':
      $query = 'insert into net_modif_account (net_modif_accountname,accountingnumberid) values(?,?)';
      $query_prm = array($_POST['name'],$_POST['accountingnumberid']);
      $successtext = 'successaddnet_modif_account:';
    break;
    case 'reason_payment_modify':
      $query = 'insert into reason_payment_modify (reason_payment_modifyname) values(?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddreason_payment_modify:';
    break;
    case 'productactiontag':
      $query = 'insert into productactiontag (productactiontagname) values(?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddproductactiontag:';
    break;
    case 'competitor':
      $query = 'insert into competitor (competitorname) values(?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddcompetitor:';
    break;
    case 'productactioncat':
      $query = 'insert into productactioncat (productactioncatname) values(?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddproductactioncat:';
    break;
    case 'team':
      $query = 'insert into team (teamname) values(?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddteam:';
    break;
    case 'bank':
      $query = 'insert into bank (bankname,fullbankname) values(?,?)';
      $query_prm = array($_POST['name'], $_POST['fullbankname']);
      $successtext = 'successaddbank:';
    break;
    case 'bankaccount':
      $query = 'insert into bankaccount (bankaccountname,bankid,accountingnumberid) values(?,?,?)';
      $query_prm = array($_POST['name'], $_POST['bankid'], $_POST['accountingnumberid']);
      $successtext = 'successaddbankaccount:';
    break;
    case 'clientcategory':
      $query = 'insert into clientcategory (clientcategoryname,clientcategorygroupid) values (?,?)';
      $query_prm = array($_POST['name'],$_POST['clientcategorygroupid']+0);
      $successtext = 'successaddclientcategory:';
    break;
    case 'clientcategory2':
      $query = 'insert into clientcategory2 (clientcategory2name,clientcategorygroup2id) values (?,?)';
      $query_prm = array($_POST['name'],$_POST['clientcategorygroup2id']+0);
      $successtext = 'successaddclientcategory2:';
    break;
    case 'clientcategory3':
      $query = 'insert into clientcategory3 (clientcategory3name,clientcategorygroup3id) values (?,?)';
      $query_prm = array($_POST['name'],$_POST['clientcategorygroup3id']+0);
      $successtext = 'successaddclientcategory3:';
    break;
		case 'clientcategorygroup':
      $query = 'insert into clientcategorygroup (clientcategorygroupname,`rank`) values (?,?)';
      $query_prm = array($_POST['name'],$_POST['rank']);
      $successtext = 'successaddclientcategorygroup:';
    break;
    case 'clientcategorygroup2':
      $query = 'insert into clientcategorygroup2 (clientcategorygroup2name,`rank`) values (?,?)';
      $query_prm = array($_POST['name'],$_POST['rank']);
      $successtext = 'successaddclientcategorygroup2:';
    break;
    case 'clientcategorygroup3':
      $query = 'insert into clientcategorygroup3 (clientcategorygroup3name,`rank`) values (?,?)';
      $query_prm = array($_POST['name'],$_POST['rank']);
      $successtext = 'successaddclientcategorygroup3:';
    break;
    case 'clientactioncat':
      $query = 'insert into clientactioncat (clientactioncatname) values (?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddclientactioncat:';
    break;
    case 'clientsector':
      $query = 'insert into clientsector (clientsectorname,clientsectorrank) values (?,?)';
      $query_prm = array($_POST['name'],$_POST['rank']);
      $successtext = 'successaddclientsector:';
    break;
    case 'clientschedulecat':
      $query = 'insert into clientschedulecat (clientschedulecatname) values (?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddclientschedulecat:';
    break;
    case 'commissionrate':
      $query = 'insert into commissionrate (commissionrate) values (?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddcommissionrate:';
    break;
    case 'companytransport':
      $query = 'insert into companytransport (companytransportname) values (?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddcompanytransport:';
    break;
    case 'interventionitemtag1':
      $query = 'insert into interventionitemtag1 (interventionitemtag1name) values (?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddinterventionitemtag1:';
    break;
    case 'interventionitemtag2':
      $query = 'insert into interventionitemtag2 (interventionitemtag2name) values (?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddinterventionitemtag2:';
    break;
    case 'localvessel':
      $query = 'insert into localvessel (localvesselname) values (?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddlocalvessel:';
    break;
    case 'modifiedstockreason':
      $query = 'insert into modifiedstockreason (modifiedstockreasonname) values (?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddmodifiedstockreason:';
    break;
    case 'returnreason':
      $query = 'insert into returnreason (returnreasonname) values (?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddreturnreason:';
    break;
    case 'resource':
      $query = 'insert into resource (resourcename) values (?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddresource:';
    break;    
    case 'country':
      $query = 'insert into country (countryname,sofixcode,fenixcode,`rank`) values (?,?,?,?)';
      $query_prm = array($_POST['name'], mb_substr($_POST['sofixcode'],0,3), mb_substr($_POST['fenixcode'],0,3), ($_POST['rank']+0));
      $successtext = 'successaddcountry:';
    break;
    case 'unittype':
      $query = 'insert into unittype (unittypename,displaymultiplier) values (?,?)';
      $query_prm = array($_POST['name'],$_POST['displaymultiplier']);
      $successtext = 'successaddunittype:';
    break;
    case 'invoicetag':
      require('inc/findclient.php');
      $query = 'insert into invoicetag (invoicetagname,invoicetag_clientid) values (?,?)';
      $query_prm = array($_POST['name'],$clientid);
      $successtext = 'successaddparam:';
    break;
    case 'invoicetag2':
      $query = 'insert into invoicetag2 (invoicetag2name,daysaddedtocustomdate) values (?,?)';
      $query_prm = array($_POST['name'], $_POST['daysaddedtocustomdate']);
      $successtext = 'successaddparam:';
    break;
    case 'paymentcategory':
      $query = 'insert into paymentcategory (paymentcategoryname) values (?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddpaymentcategory:';
    break;
    case 'vessel':
      $query = 'insert into vessel (vesselname) values (?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddvessel:';
    break;
    case 'currency':
      $query = 'insert into currency (currencyname,currencyacronym) values (?,?)';
      $query_prm = array($_POST['name'], mb_substr($_POST['currencyacronym'],0,3));
      $successtext = 'successaddcurrency:';
    break;
    case 'warehousereason':
      $query = 'insert into warehousereason (warehousereasonname) values (?)';
      $query_prm = array($_POST['name']);
      $successtext = 'successaddwarehousereason:';
    break;
    case 'qr_location':
      require('inc/findclient.php');
      $query = 'insert into qr_location (qr_locationname,clientid) values (?,?)';
      $query_prm = array($_POST['name'],$clientid+0);
      $successtext = 'successaddqr_location:';
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
      elseif($item == 'invoice_priceoption1' || $item == 'invoice_priceoption2' || $item == 'invoicetag3')
      {
        echo '<p>' . d_trad($successtext,array($_SESSION['ds_term_' . $item],d_output($_POST['name']))) . '</p><br>';              
      }
      else     
      {
        echo '<p>' . d_trad($successtext,d_output($_POST['name'])) . '</p><br>'; 
      }
    }
  }
}

$itemname = d_trad($item);
$title = d_trad('addparam:',$itemname);
$nametitle = '';
switch ($item)
{
  case 'bank':
    $nametitle = d_trad('initials2:');
    break;
  case 'bankaccount':
    $nametitle = d_trad('account:');
    break;    
  case 'invoicetag':
    $itemname = $_SESSION['ds_term_'.$item];
    $nametitle = d_trad('param:',$itemname);
    $title = d_trad('addparam:',$itemname);
    break;
  case 'invoicetag2':
    $itemname = $_SESSION['ds_term_'.$item];
    $nametitle = d_trad('param:',$itemname);
    $title = d_trad('addparam:',$itemname);
    break;
  case 'invoice_priceoption1':
    $title = d_trad('addparam:',$_SESSION['ds_term_invoice_priceoption1']);
    $nametitle = d_trad('name:');
    break;
  case 'invoice_priceoption2':
    $title = d_trad('addparam:',$_SESSION['ds_term_invoice_priceoption2']);
    $nametitle = d_trad('name:');
    break;
  case 'invoice_priceoption3':
    $title = d_trad('addparam:',$_SESSION['ds_term_invoice_priceoption3']);
    $nametitle = d_trad('name:');
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
echo '<form method="post" action="admin.php"><table>';
echo '<tr><td>' . $nametitle . '</td><td><input autofocus type="text" name="name" size=50></td></tr>';
switch ($item)
{
  case 'advance':
    echo '<tr><td>Pourcentage:<td><input type="number" name="advance_percentage" size=80>';
    break;
  case 'color':
    echo '<tr><td>Code:<td><input type="text" name="colorcode" size=10>';
    break;
  case 'invoice_priceoption1':
  case 'invoice_priceoption2':
  case 'invoice_priceoption3':
    echo '<tr><td>Modif Prix<td><input type="text" name="salesprice_mod" size=50>';
    echo '<tr><td>' . d_trad('rank:') . '<td><input type="text" name="rank" size=50 value=100>';
    break;
  case 'bank':
    echo '<tr><td>' . d_trad('name:') . '</td><td><input type="text" name="fullbankname" size=50></td></tr>';
    break;
  case 'currency':
    echo '<tr><td>' . d_trad('acronym:') . '</td><td><input type="text" name="currencyacronym" size=5></td></tr>';
    break;
  case 'bankaccount':
    $dp_itemname = 'bank'; $dp_description = d_trad('bank'); $dp_allowall = 0; $dp_noblank = 1;
    require('inc/selectitem.php');
    $dp_itemname = 'accountingnumber'; $dp_description = 'Compte (Compta)'; $dp_allowall = 0; $dp_blank = 1;
    if (isset($item_query[0]['accountingnumberid'])) { $dp_selectedid = $item_query[0]['accountingnumberid']; }
    require('inc/selectitem.php');
    break;
	case 'clientcategory':
    $dp_itemname = 'clientcategorygroup'; $dp_description = d_trad('clientcategorygroup'); $dp_allowall = 0; $dp_blank = 1; $dp_selectedid = $item_query[0]['clientcategorygroupid'];
    require('inc/selectitem.php');
		break;
	case 'clientcategory2':
    $dp_itemname = 'clientcategorygroup2'; $dp_description = d_trad('clientcategorygroup2'); $dp_allowall = 0; $dp_blank = 1; $dp_selectedid = $item_query[0]['clientcategorygroup2id'];
    require('inc/selectitem.php');
		break;
  case 'clientcategory3':
    $dp_itemname = 'clientcategorygroup3'; $dp_description = d_trad('clientcategorygroup3'); $dp_allowall = 0; $dp_blank = 1; $dp_selectedid = $item_query[0]['clientcategorygroup3id'];
    require('inc/selectitem.php');
		break;
  case 'clientsector':
	case 'clientcategorygroup':
	case 'clientcategorygroup2':
  case 'clientcategorygroup3':
    echo '<tr><td>' . d_trad('rank:') . '</td><td><input type="text" name="rank" size=50></td></tr>';
    break;
  case 'country':
    echo '<tr><td>Code Fenix:</td><td><input type="text" name="fenixcode" size=50></td></tr>';
    echo '<tr><td>' . d_trad('sofixcode:') . '</td><td><input type="text" name="sofixcode" size=50></td></tr>';
    echo '<tr><td>' . d_trad('rank:') . '</td><td><input type="text" STYLE="text-align:right" name="rank" size=5></td></tr>';
    break;
  case 'invoicetag':
    echo '<tr><td>Limité au client :<td>'; $dp_nodescription = 1; require('inc/selectclient.php');
    break;
  case 'invoicetag2':
    echo '<tr><td>Jours ajouté au date customisé:</td><td><input type="text" name="daysaddedtocustomdate" size=6></td></tr>';
    break;
  case 'net_modif_account':
    $dp_itemname = 'accountingnumber'; $dp_description = 'Compte:'; $dp_allowall = 0; $dp_blank = 1;
    require('inc/selectitem.php');
    break;
  case 'unittype':
    echo '<tr><td>Multiplicateur:</td><td><input type="number" min=1 name="displaymultiplier" value=1>';
    break;
  case 'qr_location':
    echo '<tr><td>';require('inc/selectclient.php');
    break;
}
echo '<tr><td colspan="2" align="center"><input type=hidden name="action" value="save"><input type=hidden name="item" value="' . $item . '"><input type=hidden name="adminmenu" value="' . $adminmenu . '"><input type="submit" value="' . d_trad('save') . '"></td></tr>';
echo '</table></form>';

?>