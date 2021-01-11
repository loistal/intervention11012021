<?php

$item = $_GET['item'];
if (isset($_POST['item'])) { $item = $_POST['item']; }
$item = d_safebasename($item);
$title = d_trad('list' . $item . ':');

$ok = 1;
switch ($item)
{
  case 'interventionitemtag1':
    if (!isset($interventionitemtag1A)) { require('preload/interventionitemtag1.php');}
    $nameA = $interventionitemtag1A;
    $deletedA = $interventionitemtag1_deletedA;
    $nameitem = d_trad($item);
    $nametitle = d_trad('param:',$nameitem);
  break;
  case 'interventionitemtag2':
    if (!isset($interventionitemtag2A)) { require('preload/interventionitemtag2.php');}
    $nameA = $interventionitemtag2A;
    $deletedA = $interventionitemtag2_deletedA;
    $nameitem = d_trad($item);
    $nametitle = d_trad('param:',$nameitem);
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
    $title = $_SESSION['ds_term_invoice_priceoption1'];
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
  case 'competitor':
    require('preload/competitor.php');
    $nameA = $competitorA;
    $deletedA = $competitor_deletedA;
  break;
  case 'productactioncat':
    require('preload/productactioncat.php');
    $nameA = $productactioncatA;
    $deletedA = $productactioncat_deletedA;
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
    $nametitle = d_trad('initials2:');
    $nameitem = d_trad('initials2');    
  break;
  case 'bankaccount':
    if (!isset($bankaccountA)) { require('preload/bankaccount.php');}
    $nameA = $bankaccountA;
    $deletedA = $bankaccount_deletedA;
    $nametitle = d_trad('account:');  
    $nameitem = d_trad('account'); 
    require('preload/bank.php');
    require('preload/accountingnumber.php');
  break;
  case 'clientcategory':
     if (!isset($clientcategoryA)) { require('preload/clientcategory.php');}
    $nameA = $clientcategoryA;
    $deletedA = $clientcategory_deletedA;
		$clientcategorygroupidA = $clientcategory_groupidA;
  break;
  case 'clientcategory2':
    if (!isset($clientcategory2A)) { require('preload/clientcategory2.php');}
    $nameA = $clientcategory2A;
    $deletedA = $clientcategory2_deletedA;
		$clientcategorygroup2idA = $clientcategory_group2idA;		
  break;
  case 'clientcategory3':
    if (!isset($clientcategory3A)) { require('preload/clientcategory3.php');}
    $nameA = $clientcategory3A;
    $deletedA = $clientcategory3_deletedA;
		$clientcategorygroup3idA = $clientcategory_group3idA;		
  break;
	case 'clientcategorygroup':
     if (!isset($clientcategorygroupA)) { require('preload/clientcategorygroup.php');}
    $nameA = $clientcategorygroupA;
    $deletedA = $clientcategorygroup_deletedA;
		$rankA = $clientcategorygroup_rankA;
  break;
  case 'clientcategorygroup2':
    if (!isset($clientcategorygroup2A)) { require('preload/clientcategorygroup2.php');}
    $nameA = $clientcategorygroup2A;
    $deletedA = $clientcategorygroup2_deletedA;
		$rankA = $clientcategorygroup2_rankA;		
  break;
  case 'clientcategorygroup3':
    if (!isset($clientcategorygroup3A)) { require('preload/clientcategorygroup3.php');}
    $nameA = $clientcategorygroup3A;
    $deletedA = $clientcategorygroup3_deletedA;
		$rankA = $clientcategorygroup3_rankA;		
  break;
  case 'clientactioncat':
     if (!isset($clientactioncatA)) { require('preload/clientactioncat.php');}
    $nameA = $clientactioncatA;
    $deletedA = $clientactioncat_deletedA;
  break;
  case 'clientsector':
     if (!isset($clientsectorA)) { require('preload/clientsector.php');}
    $nameA = $clientsectorA;
    $deletedA = $clientsector_deletedA;
  break;
  case 'clientschedulecat':
    if (!isset($clientschedulecatA)) { require('preload/clientschedulecat.php');}
    $nameA = $clientschedulecatA;
    $deletedA = $clientschedulecat_deletedA;    
  break;
  case 'commissionrate':
    if (!isset($commissionrateA)) { require('preload/commissionrate.php');}
    $nameA = $commissionrateA;
    $deletedA = $commissionrate_deletedA;
    $nameitem = d_trad($item);
    $nametitle = d_trad('param:',$nameitem);
  break;
  case 'companytransport':
    if (!isset($companytransportA)) { require('preload/companytransport.php');}
    $nameA = $companytransportA;
    $deletedA = $companytransport_deletedA;
    $nameitem = d_trad($item);
    $nametitle = d_trad('param:',$nameitem);
  break;
  case 'employeecategory':
    if (!isset($employeecategoryA)) { require('preload/employeecategory.php');}
    $nameA = $employeecategoryA;
    $deletedA = $employeecategory_deletedA;
    //$numdailycheckingA = $employeecategory_numdailycheckingA;
  break;
  case 'employeedepartment':
    if (!isset($employeedepartmentA)) { require('preload/employeedepartment.php');}
    $nameA = $employeedepartmentA;
    $deletedA = $employeedepartment_deletedA;
    $itemname = $_SESSION['ds_term_'.$item];
    $nametitle = d_trad('param:',$itemname);
    $title = d_trad('listparam:',$itemname);      
  break;  
  case 'employeesection':
    if (!isset($employeesectionA)) { require('preload/employeesection.php');}
    if (!isset($employeedepartmentA)) { require('preload/employeedepartment.php');}
    $nameA = $employeesectionA;
    $deletedA = $employeesection_deletedA;
    $itemname = $_SESSION['ds_term_'.$item];
    $nametitle = d_trad('param:',$itemname);
    $title = d_trad('listparam:',$itemname);    
  break;  
  case 'localvessel':
    if (!isset($localvesselA)) { require('preload/localvessel.php');}
    $nameA = $localvesselA;
    $deletedA = $localvessel_deletedA;
    $nameitem = d_trad($item);
    $nametitle = d_trad('param:',$nameitem);
  break;
  case 'modifiedstockreason':
    if (!isset($modifiedstockreasonA)) { require('preload/modifiedstockreason.php');}
    $nameA = $modifiedstockreasonA;
    $deletedA = $modifiedstockreason_deletedA;
    $nameitem = d_trad($item);
    $nametitle = d_trad('param:',$nameitem);
  break;
  case 'returnreason':
    if (!isset($returnreasonA)) { require('preload/returnreason.php');}
    $nameA = $returnreasonA;
    $deletedA = $returnreason_deletedA;
    $nameitem = d_trad($item);
    $nametitle = d_trad('param:',$nameitem);
  break;
  
  case 'warehousereason':
    if (!isset($warehousereasonA)) { require('preload/warehousereason.php');}
    $nameA = $warehousereasonA;
    $deletedA = $warehousereason_deletedA;
    $nameitem = d_trad($item);
    $nametitle = d_trad('param:',$nameitem);
  break;
  case 'country':
    if (!isset($countryA)) { require('preload/country.php');}
    $nameA = $countryA;
    $deletedA = $country_deletedA;
    $rankA = $country_rankA;
  break;
  case 'unittype':
    if (!isset($unittypeA)) { require('preload/unittype.php');}
    $nameA = $unittypeA;
    $deletedA = $unittype_deletedA;
  break;
  case 'invoicetag':
    require('preload/invoicetag.php');
    $nameA = $invoicetagA;
    $deletedA = $invoicetag_deletedA;
    $itemname = $_SESSION['ds_term_'.$item];
    $nametitle = d_trad('param:',$itemname);
    $title = d_trad('listparam:',$itemname);   
  break;
  case 'invoicetag2':
    if (!isset($invoicetag2A)) { require('preload/invoicetag2.php');}
    $nameA = $invoicetag2A;
    $deletedA = $invoicetag2_deletedA;
    $itemname = $_SESSION['ds_term_'.$item];
    $nametitle = d_trad('param:',$itemname);
    $title = d_trad('listparam:',$itemname);
  break;
  case 'paymentcategory':
    if (!isset($paymentcategoryA)) { require('preload/paymentcategory.php');}
    $nameA = $paymentcategoryA;
    $deletedA = $paymentcategory_deletedA;
  break;
  case 'vessel':
    if (!isset($vesselA)) { require('preload/vessel.php');}
    $nameA = $vesselA;
    $deletedA = $vessel_deletedA;
    $nameitem = d_trad($item);
    $nametitle = d_trad('param:',$nameitem);
  break;
  case 'currency':
    if (!isset($currencyA)) { require('preload/currency.php');}
    $nameA = $currencyA;
    $deletedA = $currency_deletedA;
    $nameitem = d_trad($item);
    $nametitle = d_trad('param:',$nameitem);
  break;
  case 'resource':
    if (!isset($resourceA)) { require('preload/resource.php');}
    $nameA = $resourceA;
    $deletedA = $resource_deletedA;
    $nameitem = d_trad($item);
    $nametitle = d_trad('param:',$nameitem);
  break;
  case 'qr_location':
    if (!isset($qr_locationA)) { require('preload/qr_location.php');}
    require('preload/client.php');
    $nameA = $qr_locationA;
    $deletedA = $qr_location_deletedA;
    $nameitem = d_trad($item);
    $nametitle = d_trad('param:',$nameitem);
  break;
  default:
    $ok = 0;
    $nametitle = d_trad('name:');    
  break;
}
echo '<h2>' . $title . '</h2>';
if(!isset($nameitem) || $nameitem == '')
{
  $nameitem = d_trad('name');
}
if(!isset($nametitle) || $nametitle == '')
{
  $nametitle = d_trad('name:');
}
if ($ok && isset($nameA))
{
  echo '<table class="report">';
  echo '<thead><th>' . $nameitem . '</th>';
  switch($item)
  {
    case 'advance':     
      echo '<th>Pourcentage</th>';
      break;
    case 'color':     
      echo '<th>Code</th>';
      break;
    case 'invoice_priceoption1':
    case 'invoice_priceoption2':
    case 'invoice_priceoption3':    
      echo '<th>Modif Prix<th>',d_trad('rank');
      break;
    case 'bank':
      echo '<th>' . d_trad('name') . '</th>'; 
      break;
    case 'currency':
      echo '<th>' . d_trad('acronym') . '</th>'; 
      break;
    case 'clientsector':
      echo '<th>' . d_trad('rank') . '</th>';
      break;
    case 'country':
      echo '<th>' . d_trad('sofixcode') . '</th>';
      break;
    case 'bankaccount':     
      echo '<th>Compte</th>';
      break;
    case 'employeesection':     
      echo '<th>' . d_output($_SESSION['ds_term_employeedepartment']) . '</th>';
      break;
    case 'invoicetag':     
      echo '<th>Limité au client</th>';
      break;
    case 'invoicetag2':     
      echo '<th>Jours ajouté au date customisé</th>';
      break;
    case 'net_modif_account':     
      echo '<th>Compte</th>';
      break;
    case 'unittype':
      echo '<th>Multiplicateur';
      break;
    case 'qr_location':
      echo '<th>Client';
      break;
  }
  if (isset($rankA)) { echo '<th>' . d_trad('rank') . '</th>'; }
  echo '<th>' . d_trad('deleted') . '</th></thead>';
  foreach ($nameA as $id => $name)
  {
    if ($item == 'bankaccount') { $name = $bankA[$bankaccount_bankidA[$id]] . ': ' . $name; }
    echo d_tr() .'<td>' . d_output($name) . '</td>';
    
    switch($item)
    {
      case 'advance':
        echo '<td align=right>' . d_output($advance_percentageA[$id],'int') . '</td>';
        break;
      case 'color':
        echo '<td>' . d_output($color_codeA[$id]) . '</td>';
        break;
      case 'invoice_priceoption1':
        echo d_td($invoice_priceoption1_salesprice_modA[$id],'currency');
        echo d_td($invoice_priceoption1_rankA[$id],'int');
        break;
      case 'invoice_priceoption2':
        echo d_td($invoice_priceoption2_salesprice_modA[$id],'currency');
        echo d_td($invoice_priceoption2_rankA[$id],'int');
        break;
      case 'invoice_priceoption3':
        echo d_td($invoice_priceoption3_salesprice_modA[$id],'currency');
        echo d_td($invoice_priceoption3_rankA[$id],'int');
        break;
      case 'bank':
        echo '<td>' . d_output($bank_fullbanknameA[$id]) . '</td>';
        break;
      case 'currency':
        echo '<td>' . d_output($currency_acronymA[$id]) . '</td>';
        break;
      case 'clientsector':
        echo '<td>' . d_output($clientsector_rankA[$id]) . '</td>';
        break;
      case 'country':
        echo '<td>' . d_output($country_sofixcodeA[$id]) . '</td>';
        break;
      case 'bankaccount':     
        echo '<td align=right>' . d_output($accountingnumberA[$bankaccount_accountingnumberidA[$id]]) . '</td>';
        break;
      case 'employeesection':
        echo '<td>' . d_output($employeedepartmentA[$employeesection_employeedepartmentidA[$id]]) . '</td>';
        break;
      case 'invoicetag':
        $clientname = '';
        if ($invoicetag_clientidA[$id] > 0)
        {
          $query = 'select clientname from client where clientid=?';
          $query_prm = array($invoicetag_clientidA[$id]);
          require('inc/doquery.php');
          if ($num_results)
          {
            $clientname = d_decode($query_result[0]['clientname']);
          }
        }
        echo d_td($clientname);
        break;
      case 'invoicetag2':
        echo '<td align=right>' . d_output($invoicetag2_daysaddedtocustomdateA[$id]) . '</td>';
        break;
      case 'net_modif_account':
        echo '<td align=right>' . d_output($accountingnumberA[$net_modif_account_anidA[$id]]) . '</td>';
        break;
      case 'unittype':
        echo '<td align=right>' . d_output($unittype_dmpA[$id]);
        break;
      case 'qr_location':
        echo '<td align=right>' . d_output($clientA[$qr_location_clientidA[$id]]);
        break;
    }
    if (isset($rankA)) { echo '<td align=right>' . $rankA[$id] . '</td>'; }
    echo '<td align=center>';
    if (isset($deletedA[$id]) && $deletedA[$id]) { echo '&radic;'; }
    echo '</td></tr>';
  }
  echo '</table>';
}

?>