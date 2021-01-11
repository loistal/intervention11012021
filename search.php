<?php

# This is basically custom for Wing Chong, move it to their custom menus

if (!isset($_SESSION['ds_userid']) || $_SESSION['ds_userid'] < 1) { exit; }

# Build web page
require ('inc/standard.php');
require ('inc/top.php');
echo '<title>'.d_output($_SESSION['ds_customname']).' Recherche</title>';

$PA['searchtype'] = 'uint';
$PA['name'] = '';
require('inc/readpost.php');

$ourtext = 'Rechercher';
if ($_POST['searchtype'] == 1) { $ourtext = $ourtext . ' produit "' . d_output($name) . '"'; }
if ($_POST['searchtype'] == 2)
{
  $ourtext = $ourtext . ' ';
  $ourtext .= 'tiers';
  $ourtext .= ' "' . d_output($name) . '"';
}
echo '<br><div class="myblock" style="width:auto;margin:2px;"><div class="selectaction"><h6>' . $ourtext . '</h6></div><br>';


switch($searchtype)
{

  ### Client search ###
  case '2':
  if ($name != '')
  {
    $query = 'select clientid,clientname,contact,telephone,cellphone,fax,email,townid,blocked,deleted,clientcomment,clienttermid,clientcategoryid from client where lower(clientname) like ?';
    if ($_SESSION['ds_allowedclientlist'] != '') { $query = $query . ' and clientid in ' . $_SESSION['ds_allowedclientlist']; }
    if ($_SESSION['ds_purchaseaccess'] != 1 && $_SESSION['ds_accountingaccess'] != 1) { $query .= ' and (isclient=1 or isemployee=1)'; }
    $query = $query . ' order by clientname';
    if ($_SESSION['ds_maxresults'] > 0) { $query .= ' LIMIT '.$_SESSION['ds_maxresults']; }
    $query_prm = array('%' .  mb_strtolower(d_encode($name)) . '%');
    require ('inc/doquery.php');
    $num_results_main = $num_results;
    $query_result_main = $query_result;
    if ($num_results)
    {
      require('preload/town.php');
      require('preload/island.php');
      require('preload/clientterm.php');
      require('preload/clientcategory.php');
      echo '<table class="report"><tr><td class="breakme"><b>Numéro client</b></td><td><b>Client</b></td>';
      echo '<td><b>Catégorie</b></td>'; 
      if ($_SESSION['ds_balanceonsearch'] == 1) { echo '<td><b>Solde</b></td>'; }
      echo '<td><b>Contact</b></td><td><b>Téléphone</b></td><td><b>Vini</b></td><td><b>Fax</b></td><td><b>Email</b></td><td><b>Ville</b></td><td><b>Île</b></td>';
      echo '<td><b>Paiement</b></td><td><b>Status</b></td>';
      echo '<td><b>Commentaire</b></td></tr>';
    }
    for ($i=0; $i < $num_results_main; $i++)
    {
      $row = $query_result_main[$i];
      $townname = $townA[$row['townid']];
      $islandname = $islandA[$town_islandidA[$row['townid']]];
      $showclientname = d_output(d_decode($row['clientname']));
      $status = '&nbsp;';
      if ($row['blocked'] == 1)
      {
        $status = "<font color=red>Interdit</font>";
        $showclientname = '<font color=red>'.$showclientname.'</font>';
      }
      elseif ($row['blocked'] == 2)
      {
        $status = "<font color=orange>Suspendu</font>";
        $showclientname = '<font color=orange>'.$showclientname.'</font>';
      }
      if ($row['deleted'] == 1) { $status = "<font color=red>Fermé</font>"; }
      echo '<tr><td align=right>' . $row['clientid'] . '</td>';
      echo '<td class="breakme"><a href="reportwindow.php?report=showclient&usedefaultstyle=1&client=' . $row['clientid']
      . '" target=_blank>' . $showclientname . '</a><td>';
      if (isset($clientcategoryA[$row['clientcategoryid']])) { echo d_output($clientcategoryA[$row['clientcategoryid']]); }
      if ($_SESSION['ds_balanceonsearch'] == 1)
      {
        $dp_clientid = $row['clientid'];
        require('inc/clientbalance.php');
        $balance = $dr_balance;
        echo '<td align=right>';
        if ($balance < 0) { echo '(Crédit) '; $balance = d_abs($balance); }
        echo myfix($balance) . '</td>';
      }
      echo '<td class="breakme">' . d_output($row['contact']) . '</td><td class="breakme">' . d_output($row['telephone']) . '</td><td class="breakme">&nbsp;' . d_output($row['cellphone']) . '</td><td class="breakme">&nbsp;' . d_output($row['fax']) . '</td><td class="breakme">' . d_output($row['email']) . '&nbsp;</td><td>' . d_output($townname) . '&nbsp;</td><td>' . d_output($islandname) . '&nbsp;</td>';
      echo '<td>' . $clienttermA[$row['clienttermid']] . '</td><td>' . $status . '&nbsp;</td>';
      echo '<td class="breakme">' . d_output($row['clientcomment']) . '&nbsp;</td></tr>';
    }
    if ($num_results) { echo '</table></form><br>'; }
  }
  break;



  ### Product search ###
  case '1':
  if ($name != '')
  {
    $explainpgl = 0;
    require('preload/taxcode.php');
    require('preload/producttype.php');
    $orderby = 'productname';
    if ($_SESSION['ds_useproductcode'] == 1) { $orderby = 'suppliercode'; }
    $query = 'select temperatureid,promotext,producttypeid,retailprice,weight,volume,eancode,taxcodeid,productid,suppliercode
    ,productname,brand,numberperunit,netweightlabel,countstock,
    unittypename,displaymultiplier,sih,productcomment,salesprice,detailsalesprice,currentstock
    from product,unittype
    where product.unittypeid=unittype.unittypeid and discontinued=0 and (lower(productname) like ? or lower(suppliercode) like ? or productid=?)';
    if ($_SESSION['ds_userrepresentsclientid']) { $query .= ' and product.supplierid=?'; }
    $query .= ' order by ' . $orderby;
    if ($_SESSION['ds_maxresults'] > 0) { $query .= ' LIMIT '.$_SESSION['ds_maxresults']; }
    $query_prm = array('%' .  mb_strtolower(d_encode($name)) . '%'
    ,'%' .  mb_strtolower(d_encode($name)) . '%'
    ,(int)$name);
    if ($_SESSION['ds_userrepresentsclientid']) { array_push($query_prm,$_SESSION['ds_userrepresentsclientid']); }
    require ('inc/doquery.php');
    $main_result = $query_result; $num_results_main = $num_results;
    $showunitprice = 0;
    $showstock = 0;
    $showdetailsalesprice = 0;
    $showretailprice = 0;
    for ($i=0; $i < $num_results_main; $i++)
    {
      ### 2020 09 11 calc stock
      /*
      if ($main_result[$i]['countstock'])
      {
        $productid = $main_result[$i]['productid'];
        $currentyear = substr($_SESSION['ds_curdate'],0,4);
        $numberperunit = $main_result[$i]['numberperunit'];
        require('inc/calcstock.php');
      }
      */
      ###
      if ($main_result[$i]['numberperunit'] > 1) { $showunitprice = 1; }
      if ($main_result[$i]['currentstock'] > 0) { $showstock = 1; }
      if ($main_result[$i]['salesprice'] != $main_result[$i]['detailsalesprice']) { $showdetailsalesprice = 1; }
      if ($main_result[$i]['retailprice'] > 0) { $showretailprice = 1; }
    }
    echo '<table class="report"><tr><td colspan=3><b>Produit</b></td><td><b>Marque</b></td><td><b>Unité&nbsp;de&nbsp;vente</b></td>';
    if ($showstock) { echo '<td><b>Stock</b></td>'; }
    echo '<td><b>Prix&nbsp;HT</b></td>';
    if ($showunitprice) { echo '<td><b>Prix Uni HT</b>'; }
    if ($showdetailsalesprice) { echo '<td><b>'.$_SESSION['ds_term_prixalternatif'].'&nbsp;HT</b></td>'; }
    if ($showretailprice) { echo '<td><b>Prix&nbsp;Rég.</b></td>'; }
    echo '<td><b>TVA</b></td><td><b>Prix&nbsp;TTC</b></td><td><b>Promo</b></td><td><b>Commentaire</b></td>';
    echo '<td><b>Arrivage</b></td></td>
    <td><b>Poids</b></td><td><b>Volume</b></td><td><b>Type</b></td>';
    if ($_SESSION['ds_usedlv']) { echo '<td><b>DLV en cours'; }
    if ($_SESSION['ds_purchaseaccess'] == 1) { echo '<td><b>SIH</b></td>'; }
    if ($_SESSION['ds_useproductcode'] != 1) { echo '<td><b>Code Fourn.</b></td>'; }
    echo '<td><b>EAN</b>';
    for ($i=0; $i < $num_results_main; $i++)
    {
      $row = $main_result[$i];
      $pid = $row['productid'];
      if (1 == 1) # TODO system parameter, show arrivage?
      {
        $query = 'select shipment.arrivaldate from shipment,purchase
        where purchase.shipmentid=shipment.shipmentid and purchase.productid="' . $pid . '" and shipmentstatus<>"Fini" order by arrivaldate asc limit 1';
        $query_prm = array();
        require('inc/doquery.php');
        if ($num_results) { $arrivaldate = datefix2($query_result[0]['arrivaldate']); }
        else { $arrivaldate = '&nbsp;'; }
      }
      if ($row['temperatureid'] == 1) { echo '<tr style="background-color: ' . $_SESSION['ds_menucolor'] . ';">'; }
      elseif ($row['temperatureid'] == 2) { echo '<tr style="background-color: #ec80b6;">'; } # rose clair
      else { echo '<tr>'; }
      $showpid = $pid; if ($_SESSION['ds_useproductcode'] == 1) { $showpid = d_output($row['suppliercode']); }
      $prodname = d_decode($row['productname']);
      $cond = '';
      if ($row['numberperunit'] > 1) { $cond .= $row['numberperunit'] . ' x '; }
      $cond .= $row['netweightlabel'];
      echo '<td align=right>' . $showpid . '</td><td class="breakme"><a href="reportwindow.php?report=productimages&productid=' . $pid . '" target=_blank>' . d_output($prodname) . '</a></td>
      <td>' . d_output($cond) . '</td><td>' . d_output($row['brand']) . '</td><td>' . d_output($row['unittypename']) . '</td>';
      if ($row['displaymultiplier'] != 1)
      {
        $ourprecision = mb_strlen($row['displaymultiplier'])-1;
        if ($showstock) { echo '<td align=right>' . myfix($row['currentstock']/$row['displaymultiplier'],$ourprecision) . '</td>'; }
        echo '<td align=right>' . myfix($row['salesprice']*$row['displaymultiplier']) . '</td>';
        if ($showunitprice) { echo '<td align=right>' . myfix(($row['salesprice']*$row['displaymultiplier'])/$row['numberperunit']) . '</td>'; }
        if ($showdetailsalesprice) { echo '<td align=right>' . myfix($row['detailsalesprice']*$row['displaymultiplier']) . '</td>'; }
        if ($showretailprice) { echo '<td align=right>' . myfix($row['retailprice']*$row['displaymultiplier']) . '</td>'; }
        echo '<td align=right>' . $taxcodeA[$row['taxcodeid']] . '%</td>
        <td align=right>' . myfix($row['salesprice']*$row['displaymultiplier'] * (1+($taxcodeA[$row['taxcodeid']]/100))) . '</td>';
      }
      else
      {
        if ($showstock) { echo '<td align=right>' . myfix($row['currentstock']) . '</td>'; }
        echo '<td align=right>' . myfix($row['salesprice']) . '</td>';
        if ($showunitprice) { echo '<td align=right>' . myfix($row['salesprice']/$row['numberperunit']) . '</td>'; }
        if ($showdetailsalesprice) { echo '<td align=right>' . myfix($row['detailsalesprice']) . '</td>'; }
        if ($showretailprice) { echo '<td align=right>' . myfix($row['retailprice']) . '</td>'; }
        echo '<td align=right>' . $taxcodeA[$row['taxcodeid']] . '%</td>
        <td align=right>' . myfix($row['salesprice'] * (1+($taxcodeA[$row['taxcodeid']]/100))) . '</td>';
      }
      echo '<td class="breakme">' . d_output($row['promotext']) . '</td><td class="breakme">' . d_output($row['productcomment']) . '</td>';
      if (1 == 1) { echo '<td align=right>' . $arrivaldate . '</td>'; }  # TODO system parameter, show arrivage?
      $weight = $row['weight'];
      if ($weight >= 100) { $weight = ($weight / 1000) . '&nbsp;kg'; }
      else { $weight = $weight . '&nbsp;g'; }
      echo '<td align=right>' . $weight . '</td><td align=right>' . $row['volume'] . '</td><td align=right>' . $producttypeA[$row['producttypeid']] . '</td>';
      if ($producttypeA[$row['producttypeid']] == 'PGL') { $explainpgl = 1; }
      if ($_SESSION['ds_usedlv'])
      {
        /*
        $query = 'select purchasebatchid,arrivaldate,useby from purchasebatch where purchasebatch.deleted=0 and useby!="0000-00-00" and productid=?';
        $query = $query . ' order by arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc limit 1';
        */
        $query = 'select useby from purchasebatch
        where purchasebatchid=(select currentpurchasebatchid from product where productid=?)';
        $query_prm = array($pid);
        require('inc/doquery.php');
        echo '<td>',datefix($query_result[0]['useby'],'short');
      }
      if ($_SESSION['ds_purchaseaccess'] == 1) { echo '<td align=right>' . d_output($row['sih']) . '</td>'; }
      if ($_SESSION['ds_useproductcode'] != 1) { echo '<td align=right>' . d_output($row['suppliercode']) . '</td>'; }
      echo '<td align=right>' . d_output($row['eancode']) . '</td></tr>';
    }
    echo '</table></form>';
    if ($explainpgl)
    {
      echo '<p>PGL libre sur les îsles de: ';
      $query = 'select islandname from island where outerisland=0 order by islandid';
      $query_prm = array();
      require('inc/doquery.php');
      for ($i=0;$i<$num_results;$i++)
      {
        if ($i != 0) { echo ', '; }
        echo $query_result[$i]['islandname'];
      }
      echo '</p>';
    }
    else { echo '<br>'; }
    if ($_SESSION['ds_customname'] == 'Wing Chong') # TODO option
    {
      echo '<p>VP = Sous Vide
      6VP = 6 Pieces Sous Vide &nbsp;
      BP = Vrac &nbsp;
      LP = Disposé en Couches &nbsp;
      IQF = Surgelé Individuellement &nbsp;
      "couleur bleu" = Réfrigéré +3/+8°C &nbsp;
      "couleur rose" = Surgelé -18/-20°C
      <br>
      <span class="alert"><b>Pour des raisons sanitaires, aucun produit surgelé ou réfrigéré commandé ne sera repris</b></span>
      <br>
      <span style="color:green;font-weight:bold;">Les dates d\'arrivages prévisionnelles sont données à titre indicatif exclusivement, sans garantie, et peuvent subir des changements, à tout moment, sans préavis.</span>
      </p>';
    }
  }
  break;

  
  default:

  break;
}

echo '<form method="post" action="search.php">';
echo '<fieldset><legend>Recherche</legend>';
echo '<label>&nbsp;</label><input autofocus type="search" class="inputtext" STYLE="text-align:right" name="name" placeholder="example: abc" value="' . d_input($name) . '" size=30>';
echo '<br>';
echo '<label><input type=radio name=searchtype value=1';
if ($_POST['searchtype'] == 1 || !isset($_POST['searchtype'])) { echo ' checked'; }
echo '></label>Produits';
echo '<br><label>';
echo '<input type=radio name=searchtype value=2';
if ($_POST['searchtype'] == 2) { echo ' checked'; }
echo '></label>';
echo 'Client';
echo '<br><button type="submit">Valider</button>';
echo '</fieldset></form></div>';
echo '</td></tr></table>';
echo '</BODY></HTML>';
?>
