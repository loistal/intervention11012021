<?php

# TODO completely remove oldfunc
if($_POST['custommenu'] != 'addfr'
   && $_POST['custommenu'] != 'mod2fr'
   && $_POST['custommenu'] != 'modfr'
   && $_POST['custommenu'] != 'showfr'
  )
{
  require('custom/oldfunc.php');
}

#ini_set('display_errors', 'On');
#error_reporting(E_ALL);

    ################################################
 if (!function_exists('d_builddate'))
{   
      # build a valid date, format yy-mm-dd
  function d_builddate($day,$month,$year)
  {
    $day = $day + 0; $month = $month + 0;
    switch ($month)
    {
      case 2:
        if ($year%4 == 0) { $maxday = 29; }
        else { $maxday = 28; }
      break;
      case 4:
      case 6:
      case 9:
      case 11:
        $maxday = 30;
      break;
      default:
        $maxday = 31;
      break;
    }
    if ($day > $maxday) { $day = $maxday; }
    if ($day < 10) { $day = '0' . $day; }
    if ($month < 10) { $month = '0' . $month; }
    $date = $year . '-' .  $month . '-' . $day;
    return $date;
  }
}
    ####################################################

# Build web page
require ('inc/standard.php');
require ('inc/top.php');
require ('inc/logo.php');
require ('inc/menu.php');
 if (!function_exists('correctdate'))
{   
  # should be removed
  function correctdate($date)
  {
    return $date;
  }
}

# table
?>
</div><div id="wrapper">
<title>TEM Vaimato</title>
<div id="leftmenu">
<div id="selectactionbar">
  <div class="selectaction">
    <div class="selectactionlist">
      <?php
      echo '<a href="custom.php?vaimatomenu=invoicereport">Rapport&nbsp;factures (supprimé)</a><br>';
      if ($_SESSION['ds_userid'] == 1 || $_SESSION['ds_userid'] == 14 || $_SESSION['ds_userid'] == 15 || $_SESSION['ds_userid'] == 5)
      { ?>
            <a href="custom.php?vaimatomenu=checkBdL">Vérifier BdL</a><br><?php 
      } ?>
      <a href="custom.php?vaimatomenu=custrel">Nouveau RelVMT</a><br>
    </div>
  </div>
  <div class="selectaction">
    <div class="selectactiontitle">Planning livraison</div>  
    <div class="selectactionlist">
      <a href="custom.php?vaimatomenu=addfr">Ajouter</a><br>
      <a href="custom.php?vaimatomenu=mod2fr">Modifier</a><br>
      <a href="custom.php?vaimatomenu=modfr">Supprimer</a><br>
      <a href="custom.php?vaimatomenu=showfr">Feuille de route</a><br>
      <a href="custom.php?vaimatomenu=showfrdate">Date unique</a><br>
      <a href="custom.php?vaimatomenu=comptrendu">Compte Rendu</a><br>
    </div>
  </div>
  <div class="selectaction">
    <div class="selectactiontitle">Location</div>  
    <div class="selectactionlist">
      <a href="custom.php?vaimatomenu=newloc">Nouvelle</a><br>
      <a href="custom.php?vaimatomenu=modloc">Modifier</a><br>
      <a href="custom.php?vaimatomenu=findloccli">Chercher client</a><br>
      <a href="custom.php?vaimatomenu=listloc">Rapport</a><br>
      <a href="custom.php?vaimatomenu=makeinvoices">Créer factures</a><br>
      <a href="custom.php?vaimatomenu=showinvoices">Afficher factures</a><br>
      <a href="custom.php?vaimatomenu=tobank">Prélèvements</a><br>
      <a href="custom.php?vaimatomenu=frombank">Non paiements</a><br>
      <a href="custom.php?vaimatomenu=locpaybymonth">Encaissées/mois</a><br>
    </div>
  </div>
  <div class="selectaction">
    <div class="selectactiontitle">Fontaines</div>  
    <div class="selectactionlist">
      <a href="custom.php?vaimatomenu=newfon">Nouvelle</a><br>
      <a href="custom.php?vaimatomenu=modfon">Modifier</a><br>
      <a href="custom.php?vaimatomenu=echfon">Echange</a><br>
      <a href="custom.php?vaimatomenu=listfon">Liste</a><br>
      <?php
      /*
      <a href="custom.php?vaimatomenu=listfon2">Louées NG</a><br>
      <a href="custom.php?vaimatomenu=listfon3">Louées G</a><br>
      */?>
      <a href="custom.php?vaimatomenu=fonrep">Rapport</a><br>      
    </div>
  </div>
  <div class="selectaction">
    <div class="selectactionlist">
      <a href="custom.php?vaimatomenu=fonhis">Historique</a><br>
    </div>
  </div>
  <div class="selectaction">
    <div class="selectactiontitle">Installations</div>    
    <div class="selectactionlist">
      <a href="custom.php?vaimatomenu=inst">Installations</a><br>
      <a href="custom.php?vaimatomenu=instreport">Rapport</a>
    </div>
  </div>
  <div class="selectaction">
    <div class="selectactiontitle">Catégorie fontaine</div>    
    <div class="selectactionlist">
      <a href="custom.php?vaimatomenu=newfoncat">Nouvelle</a><br>
      <a href="custom.php?vaimatomenu=modfoncat">Modifier</a><br>
      <a href="custom.php?vaimatomenu=listfoncat">Liste</a>
    </div>
  </div>
  <div class="selectaction">
    <div class="selectactiontitle">Etat fontaine</div>    
    <div class="selectactionlist">
      <a href="custom.php?vaimatomenu=newfondesc">Nouvelle</a><br>
      <a href="custom.php?vaimatomenu=modfondesc">Modifier</a><br>
      <a href="custom.php?vaimatomenu=listfondesc">Liste</a>
    </div>
  </div>
  <div class="selectaction">
    <div class="selectactiontitle">Marque fontaine</div>    
    <div class="selectactionlist">
      <a href="custom.php?vaimatomenu=newbrand">Nouvelle</a><br>
      <a href="custom.php?vaimatomenu=modbrand">Modifier</a><br>
      <a href="custom.php?vaimatomenu=listbrand">Liste</a><br>
    </div>
  </div>
  <div class="selectaction">
    <div class="selectactionlist">
      <b><a href="products.php?productsmenu=clientstock">Consignes</a></b><br>
      <br>
      <a href="custom.php?vaimatomenu=tosage3">Export SAGE</a><br>
      <a href="custom.php?vaimatomenu=redosage3">Re-faire export</a>
    </div>
  </div>
  <div class="selectaction">
    <div class="selectactionlist">
      <?php
      /*
      <b><a href="custom.php?vaimatomenu=releves">Comptes/Relevés</a></b><br>
      <br>

      <a href="custom.php?vaimatomenu=sageexport">Export SAGE</a><br>
      <a href="custom.php?vaimatomenu=sageexport2">SAGE: à exporter</a><br>
      <br>
      */
      ?>
      <a href="custom.php?vaimatomenu=doubles">Doublés</a><br>
      <a href="custom.php?vaimatomenu=locclientpay">Loc client/année</a><br>
      <a href="custom.php?vaimatomenu=rfa">RFA</a><br>
      <a href="custom.php?vaimatomenu=cdj">Caisse du jour</a><br>
      <?php
      #echo <a href="custom.php?vaimatomenu=integrate">Integrate 2010</a><br>';
      ?>
    </div>
  </div>
</div>
<?php
require ('inc/searchbox.php');
require ('inc/copyright.php');
?>
</div><div id="mainprogram">
<?php

# Which step are we on? If not on a step, go to a menu instead.
$_SESSION['ds_step'] = $_POST['step'];
if (!isset($_SESSION['ds_step'])) { $_SESSION['ds_vaimatomenu'] = $_GET['vaimatomenu']; $_SESSION['ds_step'] = 0; }

# Go to the menuitem
switch($_SESSION['ds_vaimatomenu'])
{  
  case 'invoicereport':
    require('preload/clientcategory.php');
    require('preload/clientcategory2.php');

    echo '<h2>Rapport des factures:</h2><form method="post" action="reportwindow.php" target="_blank"><table>';
    echo '<tr><td>Date:</td><td><select name="datefield"><option value=0>' . $_SESSION['ds_term_accountingdate'] . '</option>';
    if ($_SESSION['ds_hidedeliverydate'] == 0) { echo '<option value=1>' . $_SESSION['ds_term_deliverydate'] . '</option>'; }
    echo '<option value=2>Saisie</option><option value=3>Payable</option></select></td></tr>';
    echo '<tr><td>De:</td><td>';
    $datename = 'startdate';
    require('inc/datepicker.php');
    echo '</td></tr>';
    echo '<tr><td>A:</td><td>';
    $datename = 'stopdate';
    require('inc/datepicker.php');
    echo '</td></tr>';
    echo '<tr><td align=right><input type=checkbox name="bynumber" value=1></td><td>Par numéro: <input type="text" STYLE="text-align:right" name="startid" size=10> à <input type="text" STYLE="text-align:right" name="stopid" size=10> (et ne pas par date)</td></tr>';

    ?>
    <tr><td>Client:</td><td><input autofocus type="text" STYLE="text-align:right" name="client" size=20></td></tr>

    <?php
    $dp_itemname = 'employee'; $dp_iscashier = 1; $dp_description = 'Employé ' . $_SESSION['ds_term_clientemployee1']; $dp_allowall = 1; $dp_selectedid = -1;
    require ('inc/selectitem.php');
    ?>

    <?php
    $dp_itemname = 'employee'; $dp_addtoid = '2'; $dp_iscashier = 1; $dp_description = 'Employé ' . $_SESSION['ds_term_clientemployee2']; $dp_allowall = 1; $dp_selectedid = -1;
    require ('inc/selectitem.php');
    ?>

    <tr><td>Catégorie client:</td>
    <td><?php
    if (isset($clientcategoryA))
    {
      echo '<select name="clientcategoryid">';
      echo '<option value=-1></option>';
      foreach ($clientcategoryA as $clientcategoryidS => $clientcategoryname)
      {
        echo '<option value="' . $clientcategoryidS . '">' . $clientcategoryname . '</option>';
      }
      echo '</select>';
    }
    ?></td></tr>
    <tr><td>Catégorie client 2:</td>
    <td><?php
    if (isset($clientcategory2A))
    {
      echo '<select name="clientcategory2id">';
      echo '<option value=-1></option>';
      foreach ($clientcategory2A as $clientcategory2idS => $clientcategory2name)
      {
        echo '<option value="' . $clientcategory2idS . '">' . $clientcategory2name . '</option>';
      }
      echo '</select>';
    }
    ?></td></tr>

    <?php
    $dp_itemname = 'clientterm'; $dp_description = 'Paiement'; $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1; 
    require('inc/selectitem.php');
    ?>

    <tr><td>Facturier:</td>
    <td><select name="userid"><?php

    $query = 'select userid,name from usertable where userid<>1 and deleted=0 order by name';
    $query_prm = array();
      require('inc/doquery.php');
    echo '<option value="-1"> </option>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      echo '<option value="' . $row2['userid'] . '">' . $row2['name'] . '</option>';
    }
    ?></select></td></tr>

    <?php
    $dp_itemname = 'employee'; $dp_addtoid = 'f'; $dp_issales = 1; $dp_description = 'Employé (facture)'; $dp_allowall = 1; $dp_selectedid = -1;
    require ('inc/selectitem.php');
    ?>

    <?php
    echo '<tr><td>';
    if ($_SESSION['ds_term_reference'] != "") { echo $_SESSION['ds_term_reference']; }
    else { echo 'Référence'; }
    echo ':</td><td><input type="text" STYLE="text-align:right" name="reference" size=20> <input type=checkbox name="excluderef" value=1> Exclure</td></tr>';
    echo '<tr><td>';
    if ($_SESSION['ds_term_extraname'] != "") { echo $_SESSION['ds_term_extraname']; }
    else { echo 'Extension du nom'; }
    echo ':</td><td><input type="text" STYLE="text-align:right" name="extraname" size=20></td></tr>';
    if ($_SESSION['ds_term_field1'] != "")
    {
      echo '<tr><td>' . $_SESSION['ds_term_field1'] . ':</td><td><input type="text" STYLE="text-align:right" name="field1" size=20></td></tr>';
    }
    if ($_SESSION['ds_term_field2'] != "")
    {
      echo '<tr><td>' . $_SESSION['ds_term_field2'] . ':</td><td><input type="text" STYLE="text-align:right" name="field2" size=20></td></tr>';
    }
    if ($_SESSION['ds_useserialnumbers'])
    {
      echo '<tr><td>No Serie:</td><td><input type="text" STYLE="text-align:right" name="serial" size=20></td></tr>';
    }
    ?>
    <tr><td>Status:</td>
    <td><select name="mychoice"><option value=1></option><option value=3>Non confirmées</option><option value=2>Confirmées</option><option value=8>Confirmées et non lettrées</option><option value=9>Lettrées</option><option value=4>Annulées</option></select></td></tr>
    <tr><td>Type:</td>
    <?php
    echo '<td><select name="mychoice2"><option value=1>' . d_trad('selectall') . '</option><option value=2>Factures</option><option value=5>Avoirs</option><option value=6>Proforma</option>
    <option value=7>' . $_SESSION['ds_term_invoicenotice'] . '</option><option value=8>Avoir ' . $_SESSION['ds_term_invoicenotice'] . '</option></select></td></tr>';

    if ($_SESSION['ds_useinvoicetag'])
    {
      require('preload/invoicetag.php');
      if (isset($invoicetagA))
      {
        echo '<tr><td>' . $_SESSION['ds_term_invoicetag'] . ':</td><td><select name="invoicetagid"><option value="0"></option>';
        foreach ($invoicetagA as $invoicetagid => $invoicetagname)
        {
          if ($invoicetag_deletedA[$invoicetagid] != 1) { echo '<option value="' . $invoicetagid . '">' . d_output($invoicetagname) . '</option>'; }
        }
        echo ' <input type=checkbox name="excludetag" value=1> Exclure</td></tr>';
      }
    }
    ?>
    <tr><td align=right><input type=checkbox name="showvat" value=1></td><td>Afficher TVA</td></tr>
    <tr><td>Ranger par:</td><td><select name="mychoice3"><option value=1>Numéro facture</option><option value=2>Numéro client</option>
    <option value=3><?php echo $_SESSION['ds_term_reference']; ?></option>
    <?php
    if ($_SESSION['ds_term_field1'] != "")
    {
      echo '<option value=4>' . $_SESSION['ds_term_field1'] . '</option>';
    }
    if ($_SESSION['ds_term_field2'] != "")
    {
      echo '<option value=5>' . $_SESSION['ds_term_field2'] . '</option>';
    }
    ?>
    </select></td></tr>
    <tr><td colspan="2" align="center"><input type="submit" value="Valider"></td></tr>

    <?php
    if ($_SESSION['ds_accountingaccess'] == 1)
    {
    ?>
    <tr><td colspan="2" align="center">&nbsp;</td></tr>
    <?php
    #<tr><td align=right><input type=checkbox name="csv" value=1></td><td>Format CSV &nbsp; <input type=checkbox name="csvfile" value=1> Enregistrer comme Fichier</td></tr>



    #<tr><td colspan="2" align="center"><input type="submit" value="Valider"></td></tr>
    }
    ?>


    </table><input type=hidden name="report" value="invoicereport"></form><?php
  break;

  case 'cdj':
  ?><h2>Caisse du jour:</h2>
  <form method="post" action="customreportwindow.php" target=_blank><table>
  <tr><td>Date:</td><td><?php
  $datename = 'cdjdate';
  require('inc/datepicker.php');
  ?></td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="report" value="cdj"><input type="submit" value="Valider"></td></tr></table></form>
  <?php
  break;
  
  case 'redosage3':
  switch($_SESSION['ds_step'])
  {
    case 0:
    ?><h2>Re-exporter journée</h2>
   
    <form method="post" action="custom.php"><table>
    <?php
    echo '<tr><td>Date:</td><td>';
    $datename = 'startdate';
    require('inc/datepicker.php');
    echo '</td></tr>';

    ?>
    <tr><td colspan=2>
    <input type=hidden name="step" value="1"><input type=hidden name="custommenu" value="redosage3">
    <input type="submit" value="Re-exporter journée"></td></tr>
    </table></form>
    <?php
    break;

    case 1:
    $datename = 'startdate'; require('inc/datepickerresult.php');
  
    $query = 'update invoicehistory set exported=0 where accountingdate=?';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $query = 'update payment set exported=0 where paymentdate=?';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $query = 'update adjustmentgroup set exported=0 where adjustmentdate=?';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    
    echo datefix2($startdate) . ' peut etre re-exporté';
    
    break;
  }
  break;

  case 'tosage3':
  require('vaimatoexportsage.php');
  break;

  case 'checkBdL':
  switch($_SESSION['ds_step'])
  {
    case 0:
    ?><h2>Vérifier BdLs (max 100)</h2>
    <form method="post" action="custom.php"><table>
    <tr><td>De:</td><td><input autofocus type=text STYLE="text-align:right" name="start"></td></tr>
    <tr><td>A:</td><td><input type=text STYLE="text-align:right" name="stop"></td></tr>
    <tr><td>Année:</td><td><input type=text STYLE="text-align:right" name="year"> (optionel)</td></tr>
    <?php
    $dp_itemname = 'invoicetag'; $dp_description = 'Locations/Don'; $dp_allowall = 1; $dp_noblank = 1;
    require('inc/selectitem.php');
    ?>
    <tr><td colspan=2>
    <input type=hidden name="step" value="1"><input type=hidden name="custommenu" value="checkBdL">
    <input type="submit" value="Valider"></td></tr>
    </table></form>
    <?php
    break;
    
    case 1;
    $start = (int) $_POST['start'];
    $stop = (int) $_POST['stop'];
    $year = (int) $_POST['year'];
    $invoicetagid = (int) $_POST['invoicetagid'];
    echo '<h2>Vérifier BdLs ' . $start . ' à ' . $stop . '</h2>';
    $query = 'select invoiceid,reference from invoicehistory where cancelledid=0 and reference>=? and reference<=?';
    $query_prm = array($start,$stop);
    if ($year > 0) { $query .= ' and year(accountingdate)=?'; array_push($query_prm,$year); }
    if ($invoicetagid > 0) { $query .= ' and invoicetagid=?'; array_push($query_prm,$invoicetagid); }
    $query .= ' order by reference limit 120';
    require('inc/doquery.php');
    echo '<table class=report><thead><th>BdL</th><th>Facture</th><th></th></thead>';
    for ($i=0;$i<$num_results;$i++)
    {
      echo '<tr><td align=right>' . myfix($query_result[$i]['reference']) . '</td><td align=right>' . myfix($query_result[$i]['invoiceid']) . '</td>';
      if ($i > 0 && $query_result[$i]['reference'] != ($lastref + 1)) { echo '<td><font color=red>!!!</font></td>'; }
      else { echo '<td></td>'; }
      echo '</tr>';
      $lastref = $query_result[$i]['reference'];
    }
    echo '</table>';
    break;
  }
  break;
  
  

  case 'showfrdate':
    switch($_SESSION['ds_step'])
    {
      case '0':
      ?><h2>Liste des client sur date unique</h2>
      <form method="post" action="custom.php"><table>
      <?php
      echo '<tr><td>Date:</td><td>';
      $datename = 'frdate';
      require('inc/datepicker.php');
      echo '</td></tr>';
      ?><tr><td colspan="2" align="left"><input type=hidden name="step" value="1"><input type="submit" value="Valider"></td></tr></table></form><?php
      break;

      case '1':
      $datename = 'frdate';
      require('inc/datepickerresult.php');
      echo '<h2>Liste des client '.datefix2($frdate).'</h2>';
      require('preload/employee.php');
      $query = 'select employeeid,vmt_delivery.clientid,clientname,quantity,reference from vmt_delivery,client where vmt_delivery.clientid=client.clientid and periodic=0 and deliverydate=? order by employeeid,clientname';
      $query_prm = array($frdate);
      require ('inc/doquery.php');#echo $num_results;
      echo '<table border=1 cellspacing=1 cellpadding=1><tr><td><b>Livreur</td><td><b>Client</td><td><b>Quantité</td><td></td></tr>';
      for ($i=0;$i<$num_results;$i++)
      {
        $employeeid = $query_result[$i]['employeeid'];
        echo '<tr><td>'.$employeeA[$employeeid].'</td><td>'.$query_result[$i]['clientname'].' ('.$query_result[$i]['clientid'].')</td><td align=right>'.$query_result[$i]['quantity'].'</td>';
        echo '<td>'.$query_result[$i]['reference'].'</td></tr>';
      }
      echo '</table>';
      break;
    }
  break;
  
  
  case 'rfa':
  echo '<h2>RFA</h2>';
  echo '<p>Les valeurs sont HT.</p>';
  #echo '<p class="alert">quantités pour remise est en CARTON (pack compte 1/2)</p>';
  echo '<form method="post" action="customreportwindow.php" target=_blank><table><tr><td>Du:</td><td>';
  $datename = 'startdate';
  require('inc/datepicker.php');
  echo '</td></tr><tr><td>Au:</td><td>';
  $datename = 'stopdate';
  require('inc/datepicker.php');
  echo '</td></tr>';  
  echo '<tr><td>';
  require('inc/selectclient.php');
  ?><tr><td>Île:</td>
  <td><select name="islandid"><?php
  $query = 'select islandid,islandname from island order by islandname';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<option value="-1">&lt;Tous&gt;</option>';
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<option value="' . $row['islandid'] . '">' . $row['islandname'] . '</option>';
  }
  ?></select></td></tr><?php
  $dp_itemname = 'clientsector'; $dp_description = 'Secteur client'; $dp_allowall = 1; $dp_noblank = 1;
  require('inc/selectitem.php');
  echo '<tr><td>Sous-famille de produit:</td><td><select name="productfamilyid"><option value="7">1.5 l (RFA)</option></select></td></tr>';
  echo '<tr><td>20 - 49:</td><td><input type=number STYLE="text-align:right" name="range1" value="5" size=5>%</td></tr>';
  echo '<tr><td>50 - 99:</td><td><input type=number STYLE="text-align:right" name="range2" value="6" size=5>%</td></tr>';
  echo '<tr><td>100+:</td><td><input type=number STYLE="text-align:right" name="range3" value="10" size=5>%</td></tr>';
  echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="rfa"><input type="submit" value="Valider"></td></tr></table></form>';
  break;

  case 'locpaybymonth':
  ?><h2>Rapport locations encaissées</h2>
  <?php
  #<p class="alert">Ce rapport demande que le champs "paiement pour facture no" est rempli</p>
  ?>
  <form method="post" action="customreportwindow.php" target=_blank><table><tr><td>Mois:</td><td><select name="month"><?php
  for ($i=1; $i <= 12; $i++)
  {
    if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="year"><?php
  for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
  {
    if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="report" value="locpaybymonth"><input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

    case 'locclientpay':
    ?><h2>Liste des clients locations >1mois:</h2>
    <form method="post" action="customreportwindow.php" target=_blank><table>
    <tr><td>Catégorie:</td>
    <td><select name="clientcategoryid"><?php
    $query = 'select clientcategoryid,clientcategoryname from clientcategory order by clientcategoryname';
    $query_prm = array();
    require('inc/doquery.php');
    echo '<option value="0"></option>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row = $query_result[$i];
      echo '<option value="' . $row['clientcategoryid'] . '">' . $row['clientcategoryname'] . '</option>';
    }
    ?></select></td></tr>
    <tr><td>Catégorie 2:</td>
    <td><select name="clientcategory2id"><?php
    $query = 'select clientcategory2id,clientcategory2name from clientcategory2 order by clientcategory2name';
    $query_prm = array();
    require('inc/doquery.php');
    echo '<option value="0"></option>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row = $query_result[$i];
      echo '<option value="' . $row['clientcategory2id'] . '">' . $row['clientcategory2name'] . '</option>';
    }
    ?></select></td></tr>
    <tr><td>Île:</td>
    <td><select name="islandid"><?php
    $query = 'select islandid,islandname from island order by islandname';
    $query_prm = array();
    require('inc/doquery.php');
    echo '<option value="0"></option>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row = $query_result[$i];
      echo '<option value="' . $row['islandid'] . '">' . $row['islandname'] . '</option>';
    }
    ?></select></td></tr>
    <tr><td>Employee <?php echo $_SESSION['ds_term_clientemployee1']; ?>:</td>
    <td><select name="employeeid"><option value="-1"> </option><?php
    $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee where iscashier=1 order by employeename';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row['employeeid'] == $row2['employeeid']) { echo '<option value="' . $row2['employeeid'] . '" SELECTED>' . $row2['employeename'] . '</option>'; }
      else { echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeename'] . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>Employee <?php echo $_SESSION['ds_term_clientemployee2']; ?>:</td>
    <td><select name="employeeid2"><option value="-1"> </option><?php
    $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee where iscashier=1 order by employeename';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row['employeeid'] == $row2['employeeid']) { echo '<option value="' . $row2['employeeid'] . '" SELECTED>' . $row2['employeename'] . '</option>'; }
      else { echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeename'] . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>Interdits:</td><td><select name="interdit"><option value="2">Non interdits, comptes non fermés</option><option value="0"></option><option value="1">Interdits</option><option value="3">Comptes fermés</option></select></td></tr>
    <tr><td>Ranger par:</td><td><select name="orderby"><option value="0">Nom</option><option value="1">Numéro</option><option value="2">Île, Nom</option><option value="3">Employee</option></select></td></tr>
    <?php
    #<tr><td>Débit/crédit:</td><td><select name="debcred"><option value="0"></option><option value="1">Débit</option><option value="2">Crédit</option></td></tr>
    ?>
	  <?php
    #<tr><td colspan=2><input type="checkbox" name="ss" value=1> Format tableur</td></tr>
    ?>
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td>Numéros locations<td><input type=number name="from"> à <input type=number name="to"> (exemple: 1 à 1000)
    <?php
    #<tr><td colspan=2><input type="checkbox" name="showbalance" value=1> Afficher solde (<span class="alert">Opération lourde</span>) &nbsp; <input type="checkbox" name="onlydebitbalance" value=1> Solde > 0</td></tr>
    ?>
    <tr><td colspan="2" align="center"><input type=hidden name="report" value="locclientpay"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;


  case 'integrate';
    switch($_SESSION['ds_step'])
    {
      case '0':
    exit;
      ?><h2>Integrate solde 2010 (ne pas utiliser!)</h2>
      <form enctype="multipart/form-data" method="post" action="custom.php"><table>
      <tr><td>Fichier: </td><td><input type="hidden" name="MAX_FILE_SIZE" value="100000"><input type="file" name="userfile" size=80></td></tr>
      <tr><td colspan="2" align="left"><input type=hidden name="step" value="1"><input type=hidden name="step" value="1"><input type="submit" value="Integrate"></td></tr></table></form><?php
      break;

      case '1':
    exit;
      $file = file_get_contents($_FILES['userfile']['tmp_name']);
      if (!$file) { echo "Cannot read the file<br>"; exit; }
      $sep = chr(13) . chr(10);
      $fileline = explode($sep, $file);
      $tcred=0; $tdeb = 0;
      #var_dump($fileline); exit;
      foreach ($fileline as $line)
      {
        $valueA = explode(';', $line);
        $clientid = $valueA[3];
        $comment = $valueA[4];
        $debit = $valueA[6];
        $credit = $valueA[7];
        if ($debit)
        {
          $ptid = 0;
          #if ($debit < 0) { $debit = d_abs($debit); $ourtext = 'CREDIT '; $ptid = 0; }
          echo 'DEBIT ' . $clientid . ' ' . $comment . ' ' . $debit . '<br>';
          $tdeb = $tdeb + $debit;
          $query = 'insert into payment (forinvoiceid,clientid,paymentdate,value,paymenttypeid,userid,bankid,depositbankid,paymentcomment,matchingid,reimbursement,employeeid) values (?,?,?,?,?,?,?,?,?,?,?,?)';
          $query_prm = array(0,$clientid,'2010-12-31',$debit,1,$_SESSION['ds_userid'],0,0,$comment,0,$ptid,0);
          require ('inc/doquery.php');
        }
        if ($credit)
        {
          $ptid = 1;
          echo 'CREDIT ' . $clientid . ' ' . $comment . ' ' . $credit . '<br>';
          $tcred = $tcred + $credit;
          $query = 'insert into payment (forinvoiceid,clientid,paymentdate,value,paymenttypeid,userid,bankid,depositbankid,paymentcomment,matchingid,reimbursement,employeeid) values (?,?,?,?,?,?,?,?,?,?,?,?)';
          $query_prm = array(0,$clientid,'2010-12-31',$credit,1,$_SESSION['ds_userid'],0,0,$comment,0,$ptid,0);
          require ('inc/doquery.php');
        }
      }
      echo 'DEBIT ' . myfix ($tdeb) . '<br>';
      echo 'CREDIT ' . myfix ($tcred) . '<br>';
      echo myfix($tdeb-$tcred);
      break;
    }
  break;

  case 'doubles':
    echo '<h2>Fontaines doublés</h2>';
    
    $query = 'select fountainname from vmt_fountain order by fountainname';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    $lastfountainname = '';
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      if ($row['fountainname'] == $lastfountainname && $i != 1) { echo 'Fontaine "' . $lastfountainname . '"<br>'; }
      $lastfountainname = $row['fountainname'];
    }
    
    echo '<br><h2>Contrats doublés</h2>';
    
    $query = 'select reference from vmt_rental order by reference';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    $lastfountainname = '';
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      if ($row['reference'] == $lastfountainname && $i != 1) { echo 'Contrat "' . $lastfountainname . '"<br>'; }
      $lastfountainname = $row['reference'];
    }
  break;

  case 'custrel':
  
      ?>
    <h2>Releve format Vaimato:</h2>
    <form method="post" action="customprintwindow.php" target="_blank"><table><?php
    $month = mb_substr($_SESSION['ds_curdate'],5,2);
    $year = mb_substr($_SESSION['ds_curdate'],0,4);
    echo '<tr><td>Numéro client:</td><td><input autofocus type="text" STYLE="text-align:right" name="clientid" size=10></td></tr>';
    ?><tr><td>Mois:</td><td><select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>Page:</td><td><input type="text" STYLE="text-align:right" name="pagenumber" size=5 value="1"></td></tr>
    <tr><td>Lignes / Page:</td><td><input type="text" STYLE="text-align:right" name="linesperpage" size=5 value="16"></td></tr>
    <tr><td>Exclure locations:</td><td><input type="checkbox" name="noloc" value=1 checked></td></tr>
    <tr><td>Exclure factures lettrées:</td><td><input type="checkbox" name="nomatched" value=1 checked></td></tr>
    <tr><td>Exclure palette consignée (75):</td><td><input type="checkbox" name="exclude75" value=1 checked></td></tr>
    <tr><td>Exclure lignes de zéro francs:</td><td><input type="checkbox" name="nozero" value=1></td></tr>
    <tr><td>Taille ligne:</td><td><select name="itemfontsize"><option value=75>75</option><option value=70>70</option><option value=60>60</option><option value=50>50</option><option value=45>45</option><option value=40>40</option><option value=25>25</option><option value=125>125</option><option value=100>100</option><option value=90>90</option><option value=80>80</option></select></td></tr>
    <?php
    echo '<tr><td>Décaler:</td><td><select name="offset"><option value=0></option>';
    $testvar = $_SESSION['ds_vaimato_decaler'];
    for ($i=-10;$i>=-100;$i-=10)
    {
      echo '<option value="'.$i.'"';
      if ($i == $testvar) { echo ' selected'; }
      echo '>'.$i.'</option>';
    }
    echo '</select></td></tr>'; #echo $testvar;
    #echo '<tr><td>Décaler:</td><td><input type="text" STYLE="text-align:right" name="offset" size=5 value="' . ($_SESSION['ds_vaimato_decaler']+0) . '"></td></tr>';
    ?>
    <tr><td colspan="2" align="center">
    <input type=hidden name="report" value="custrel">
    <input type="submit" value="Valider"></td></tr></table></form><?php
  
  break;
/*
  case 'releves':
  switch($_SESSION['ds_step'])
  {
      case 0:
    ?><h2>Afficher comptes client:</h2>
    <form method="post" action="reportwindow.php" target="_blank"><table>
    <table><tr><td>Un seul client:</td><td><input type="text" STYLE="text-align:right" name="clientid" size=5> : <input type="text" id="clientlist" STYLE="text-align:right" name="clientname" size=20></td></tr>
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td colspan=2><b>Plusieurs clients:</b></td></tr>
    <tr><td>Île:</td>
    <td><select name="islandid"><option value=0> </option><?php
    
    $query = 'select islandid,islandname from island order by islandname';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      echo '<option value="' . $row['islandid'] . '">' . $row['islandname'] . '</option>';
    }
    ?></select></td></tr>
    <tr><td>Secteur:</td>
    <td><select name="clientsectorid"><option value=0> </option><?php
    
    $query = 'select clientsectorid,clientsectorname from clientsector order by clientsectorname';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = mysql_fetch_array($result);
      echo '<option value="' . $row2['clientsectorid'] . '">' . $row2['clientsectorname'] . '</option>';
    }
    ?></select></td></tr>
    <tr><td>Employé(e):</td>
    <td><select name="employeeid"><option value="0"> </option><?php
    
    $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee where iscashier=1 order by employeename';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = mysql_fetch_array($result);
      if ($row2['employeeid'] == $employeeid) { echo '<option value="' . $row2['employeeid'] . '" SELECTED>' . $row2['employeename'] . '</option>'; }
      else { echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeename'] . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>Catégorie:</td>
    <td><select name="clientcategoryid"><option value="0"> </option><?php
    
    $query = 'select clientcategoryid,clientcategoryname from clientcategory order by clientcategoryname';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = mysql_fetch_array($result);
      if ($row2['clientcategoryid'] == $clientcategoryid) { echo '<option value="' . $row2['clientcategoryid'] . '" SELECTED>' . $row2['clientcategoryname'] . '</option>'; }
      else { echo '<option value="' . $row2['clientcategoryid'] . '">' . $row2['clientcategoryname'] . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>Ranger par:</td><td><select name="myorderby"><option value=1>Numéro</option><option value=2>Nom</option></select></td></tr>
    <tr><td colspan=2><input type="checkbox" name="byclientid" value="1"> Clients numéros <input type="text" STYLE="text-align:right" name="startid" size=5> à <input type="text" STYLE="text-align:right" name="stopid" size=5></td></tr>
    <tr><td colspan=2><input type="checkbox" name="onlyrental" value="1"> Clients location</td></tr>
    <?php
    echo '<tr><td>&nbsp;</td></tr>';
    echo '<tr><td colspan=2><b>N\'afficher que les clients:</b><br><input type="checkbox" name="creditlimit" value="1"> en depassement de crédit<br><input type="checkbox" name="onlydebitors" value="1"> débiteurs</td></tr>';
    ?>
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td colspan=2><input type=checkbox name="showoperations" value="1"> <b>Relevés:<b></td></tr>
    <?php
    $day = mb_substr($_SESSION['ds_curdate'],8,2);
    $month = mb_substr($_SESSION['ds_curdate'],5,2);
    $year = mb_substr($_SESSION['ds_curdate'],0,4);
    ?><tr><td>Début:</td><td><select name="day"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>Fin:</td><td><select name="stopday"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="stopmonth"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="stopyear"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td colspan=2><input type="checkbox" name="relevenomatched" value="1"> Ne pas afficher les lettrés</td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="report" value="releves"><input type=hidden name="usedefaultstyle" value="1"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;
  }
  break;*/


  case 'findloccli':
  switch($_SESSION['ds_step'])
  {
    # 
    case 0:
    ?><h2>Chercher client par numéro contrat:</h2>
    <form method="post" action="custom.php"><table>
    <tr><td>No contrat:</td><td><input type="text" autofocus STYLE="text-align:right" name="reference" size=50></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;
    
    case 1:
    
    $query = 'select vmt_rental.clientid,clientname from vmt_rental,client where vmt_rental.clientid=client.clientid and vmt_rental.reference="' . $_POST['reference'] . '"';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $row = mysql_fetch_array($result);
    $clientid = $row['clientid']+0;
    if ($clientid > 0) { echo '<p>Référence <b>' . $_POST['reference'] . '</b> = client <b>' . $row['clientid'] . ': ' . d_decode($row['clientname']) . '</b></p>'; }
    else { echo '<p>Contrat <b>' . $_POST['reference'] . '</b> inexistant.</p>';}
    break;
  
  }
  break;
  
  case 'tobank':
  
  set_time_limit(3600);
  
  $PA['year'] = 'uint';
  $PA['month'] = 'uint';
  require('inc/readpost.php');
  
  if ($year == 0)
  {
    ?><h2>Créer ficher Prélèvement pour Banque de Polynésie:</h2><?php
    echo '<form method="post" action="custom.php"><table>';
    $month = substr($_SESSION['ds_curdate'],5,2);
    $year = substr($_SESSION['ds_curdate'],0,4);
    if ($month > 12) { $month = 1; $year = $year + 1; }
    ?><tr><td>Mois:<td><select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select>
    <tr><td>Clients à exclure:<td><input type=text name=nottheseclients size=20> (numéros clients separés d'un simpe éspace)
    <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type="submit" value="Valider">
    </table></form><?php
    echo '<p>Créer factures pour le mois avant de créer ce fichier.</p>';
  }
  else
  {
    $monthd = $month; if ($monthd < 10) { $monthd = '0' . $monthd; }
    $yeard = $year - 2000;
    if (!$month || !$year) { echo 'erreur date'; exit; }
    $ourdate = d_builddate(1,$month,$year);

    $filename = 'customfiles/PRL_Vaimato_' . $year . '_' . $monthd . '.txt';
    $file = fopen($filename, "w");
    if (!$file) { echo "Cannot create the file!<br>"; exit; }
    $sep = chr(13) . chr(10);
    $total = 0;
    
    #header
    $counter = 1;
    $writebuffer = '01';
      $numspaces = 6 - strlen($counter);
      for ($x=1; $x<= $numspaces; $x++) { $writebuffer = $writebuffer . '0'; }
    $writebuffer = $writebuffer . $counter;
    $writebuffer = $writebuffer . '80';
    $writebuffer = $writebuffer . '01' . $monthd . $yeard;
    $writebuffer = $writebuffer . '12149'; #code banque du remettant (5)
    $writebuffer = $writebuffer . '                     ';
    $writebuffer = $writebuffer . 'Banque de Polynesie     ';
    $writebuffer = $writebuffer . '      ';
    $writebuffer = $writebuffer . '12149'; # banque du rib remettant (5)
    $writebuffer = $writebuffer . '06746'; # agence du rib remettant (5)
    $writebuffer = $writebuffer . '10358001010'; # compte du rib remettant (11)
    for ($x=1; $x<= 147; $x++) { $writebuffer = $writebuffer . ' '; }
    $writebuffer = $writebuffer . $sep;
    fwrite($file, $writebuffer);
    
    #lines
    $query = 'select client.clientid,clientname,value,paymentcomment,titu,domi,codebanque,guichet,clerib,account
    from client,payment where payment.clientid=client.clientid and paymentcomment like "%Prélèvement anticipé facture%"
    and value>0 and titu<>"" and month(paymentdate)=? and year(paymentdate)=?';
	  if ($_POST['nottheseclients'] != "")
    {
      $nottheseclients = str_replace(" ", ",", $_POST['nottheseclients']);
      $query .= ' and client.clientid not in (' . $nottheseclients . ')';
    }
#$query .= ' and client.clientid=1707';
	  $query .= ' order by codebanque,client.clientid';
    $query_prm = array($month,$year);
    require('inc/doquery.php');
    $main_result = $query_result; $num_results_main = $num_results;
    for ($i=0; $i < $num_results_main; $i++)
    {
      $row = $main_result[$i]; $clientid = $row['clientid'];
      $counter++;
      $writebuffer = '04';
        $numspaces = 6 - strlen($counter);
        for ($x=1; $x<= $numspaces; $x++) { $writebuffer = $writebuffer . '0'; }
      $writebuffer = $writebuffer . $counter;
      $writebuffer = $writebuffer . '80';
      $writebuffer = $writebuffer . '01' . $monthd . $yeard;
      $writebuffer = $writebuffer . '12149'; #code banque du remettant (5)
      $writebuffer = $writebuffer . '12149'; #code banque du bénéficiaire (5)
      $writebuffer = $writebuffer . '06746'; #agence du bénéficiaire (5)
      $writebuffer = $writebuffer . '10358001010'; #compte du bénéficiaire (11)
      $writebuffer = $writebuffer . 'Vaimato                 '; #nom du bénéficiaire (24)
      $writebuffer = $writebuffer . '420849'; #Numéro émetteur de prélèvement (6)
        $codebanque = substr($row['codebanque'], 0, 5);
      $writebuffer = $writebuffer . $codebanque; #Code banque du donneur d'ordre (5)
        $numspaces = 5 - strlen($codebanque);
        for ($x=1; $x<= $numspaces; $x++) { $writebuffer = $writebuffer . ' '; }
      $writebuffer = $writebuffer . $codebanque; #Code banque du donneur d'ordre (5)
        $numspaces = 5 - strlen($codebanque);
        for ($x=1; $x<= $numspaces; $x++) { $writebuffer = $writebuffer . ' '; }
        $guichet = substr($row['guichet'], 0, 5);
      $writebuffer = $writebuffer . $guichet; #Code agence du donneur d'ordre (5)
        $numspaces = 5 - strlen($guichet);
        for ($x=1; $x<= $numspaces; $x++) { $writebuffer = $writebuffer . ' '; }
        $account = substr($row['account'], 0, 11);
      $writebuffer = $writebuffer . $account; # Numéro compte du donneur d'ordre (11)
        $numspaces = 11 - strlen($account);
        for ($x=1; $x<= $numspaces; $x++) { $writebuffer = $writebuffer . ' '; }
        $clientname = substr($row['titu'], 0, 24); #echo 'setting clientname='.$clientname.'<br>';
      $writebuffer = $writebuffer . $clientname; # Nom du donneur d'ordre (24)
        $numspaces = 24 - strlen(utf8_decode($clientname));
        for ($x=1; $x<= $numspaces; $x++) { $writebuffer = $writebuffer . ' '; }
        $reference = substr($row['clientid'], 0, 6);
        $numspaces = 6 - strlen(utf8_decode($reference));
        for ($x=1; $x<= $numspaces; $x++) { $writebuffer = $writebuffer . ' '; }
      $writebuffer = $writebuffer . $reference; # Référence (6)
        $domi = substr($row['domi'], 0, 24);
      $writebuffer = $writebuffer . $domi; # Libellé domiciliation bancaire (24)
        $numspaces = 24 - strlen($domi);
        for ($x=1; $x<= $numspaces; $x++) { $writebuffer = $writebuffer . ' '; }
      for ($x=1; $x<= 32; $x++) { $writebuffer = $writebuffer . ' '; } # Libellé 1 de l'opération (32)
      for ($x=1; $x<= 32; $x++) { $writebuffer = $writebuffer . ' '; } # Libellé 2 de l'opération (32)
      $writebuffer = $writebuffer . '            '; # Zone non utilisée
      $amount = $row['value']+0;

      ### add prélèvement refoulé to amount
      /* not what they wanted
      $findinvoiceid = substr($row['paymentcomment'], 29, 6); echo $row['paymentcomment'];
      $query = 'select reference from invoicehistory where invoiceid=?';
      $query_prm = array($findinvoiceid);
      require('inc/doquery.php');
      $reference = $query_result[0]['reference'];
      $ourpos = strpos($reference, ',') - 8;
      $reference = substr($reference, 8, $ourpos);
      echo '<br>found reference=' . $reference;
      $oursearchstring = 'Prélèvement refoulé ' . $reference;
      $oursearchstring2 = 'Virement échoué Réf ' . $reference;
        $lastmonth = $_POST['month'] - 1;
        $lastmonthyear = $_POST['year'];
        if ($lastmonth < 1) { $lastmonth = 12; $lastmonthyear = $lastmonthyear - 1; }
      $queryX = 'select sum(value) as addamount from payment where (paymentcomment="' . $oursearchstring . '" or paymentcomment="' . $oursearchstring2 . '") and matchingid=0 and paymentdate>="2011-02-01" and month(paymentdate)="' .$lastmonth . '" and year(paymentdate)="' .$lastmonthyear . '"'; # only last month
      $resultX = mysql_query($queryX, $db_conn); querycheck($resultX);
      $rowX = mysql_fetch_array($resultX);
      */
      
      $query = 'select sum(value) as addamount from payment where paymentcomment like "%Prélèvement%" and clientid=? and matchingid=0 and reimbursement=0 and matchingid=0';
      $query_prm = array($clientid);
      require('inc/doquery.php');
      if ($num_results) { $amount += $query_result[0]['addamount']; }
      ###

      $total = $total + $amount;
      $numspaces = 12 - strlen($amount);
      for ($x=1; $x<= $numspaces; $x++) { $writebuffer = $writebuffer . '0'; }
      $writebuffer = $writebuffer . $amount; # Montant sans décimales (12)
      $writebuffer = $writebuffer . $sep;
      fwrite($file, utf8_decode($writebuffer)); # can try mb_convert_encoding() if necessary
    }
 

    #footer
    $counter++;
    $writebuffer = '09';
      $numspaces = 6 - strlen($counter);
      for ($x=1; $x<= $numspaces; $x++) { $writebuffer = $writebuffer . '0'; }
    $writebuffer = $writebuffer . $counter;
    $writebuffer = $writebuffer . '80';
    $writebuffer = $writebuffer . '01' . $monthd . $yeard;
    $writebuffer = $writebuffer . '12149'; #code banque du remettant (5)
    for ($x=1; $x<= 51; $x++) { $writebuffer = $writebuffer . ' '; }
    $writebuffer = $writebuffer . '00008'; #rib banque (5)   assuming this is clé rib
    for ($x=1; $x<= 147; $x++) { $writebuffer = $writebuffer . ' '; }
      $numspaces = 16 - strlen($total);
      for ($x=1; $x<= $numspaces; $x++) { $writebuffer = $writebuffer . '0'; }
    $writebuffer = $writebuffer . $total;
    $writebuffer = $writebuffer . $sep;
    fwrite($file, $writebuffer);
    
    fclose($file);
    
    echo '<p>Fichier <a href="customfiles/' . basename($filename) . '"><font color=blue><i>' . basename($filename) . '</i></font></a> créé.</p>';
    echo '<p>- Cliquer sur le bouton droit de la souris</p><p>- Enregistrer le lien / la cible</p>';

  }
  break;

  
  case 'frombank':
  switch($_SESSION['ds_step'])
  {
    case '0':
    ?><h2>Réception ficher Non Paiments de Banque de Polynésie:</h2>
    <form enctype="multipart/form-data" method="post" action="custom.php"><table>
    <tr><td>Fichier: </td><td><input type="hidden" name="MAX_FILE_SIZE" value="1000000"><input type="file" name="frombankfile" size=80></td></tr>
    <tr><td colspan="2" align="left"><input type=hidden name="step" value="1"><input type=hidden name="vaimatomenu" value="<?php echo $vaimatomenu; ?>"><input type="submit" value="Continuer"></td></tr></table></form>
    <p class=alert>Le fichier doit avoir l'extension <b>.txt</b></p>
    <?php
    break;

    # Display imported data
    case '1':

    $ok = 1;
    #if (copy($HTTP_POST_FILES['frombankfile']['tmp_name'], '.')) { echo 'file copied'; }
    #echo $HTTP_POST_FILES['frombankfile']['tmp_name'];
    #echo 'DEBUG file info: ' . $_FILES['frombankfile']['tmp_name'] . ' ' . $_FILES['frombankfile']['name'] . '<br><br>';
    $file = file_get_contents($_FILES['frombankfile']['tmp_name']);
    if (!$file) { echo "<p>Problème de réception de fichier. Veuillez réessayer ultérieurement.</p>"; $ok = 0; }

    if ($ok)
    {
      $separator = "\n";
      $lines = explode($separator, $file);
      $i=0; $total = 0;
      foreach ($lines as $ourline)
      {
        $i++;
        $clientname = trim(substr($ourline,98,24)); #old=30
        $reference = trim(substr($ourline,122,6)); #echo 'ref='.$reference.'<br>';#old=18 
        $reference = ltrim($reference, "0"); #echo 'reference is: '.$reference;
        $ourerrorcode = trim(substr($ourline,226,2))+0; #old=158
        if ($ourerrorcode == 5) { $ourerrorcode = "SANS AUTORISATION DE DECOUVERT"; }
        if ($ourerrorcode == 11) { $ourerrorcode = "ANNULATION BANCAIRE"; }
        if ($ourerrorcode == 12) { $ourerrorcode = "COORDONNEES BANCAIRES INEXPLOITABLES"; }
        if ($ourerrorcode == 14) { $ourerrorcode = "COMPTE SOLDE"; }
        if ($ourerrorcode == 16) { $ourerrorcode = "DESTINATAIRE NON RECONNU"; }
        if ($ourerrorcode == 20) { $ourerrorcode = "PROVISION INSUFFISANTE"; }
        if ($ourerrorcode == 31) { $ourerrorcode = "PAS D’ORDRE DE PAYER"; }
        if ($ourerrorcode == 32) { $ourerrorcode = "DECISION JUDICIAIRE"; }
        if ($ourerrorcode == 34) { $ourerrorcode = "OPPOSITION SUR COMPTE"; }
        if ($ourerrorcode == 35) { $ourerrorcode = "TITULAIRE DECEDE"; }
        if ($ourerrorcode == 99) { $ourerrorcode = "OPERATION NON ADMISE"; }
        #echo 'code='.$ourerrorcode.'<br>';
        if ($i>1 && $clientname != "")
        {
          if ($reference > 0) { $clientid = $reference+0; }
          else
          {
            $searchcn = d_encode($clientname);
            $query = 'select clientid,clientname from client where deleted=0 and (clientname like ? or titu like ?)';
            $query_prm = array('%' . $searchcn . '%','%' . $searchcn . '%');
            require ('inc/doquery.php');
            $clientid = $query_result[0]['clientid']+0;
          }
          echo '<br><p>Recherce "' . d_output($clientname) . '"';
          echo '<p>Client ';
          if ($clientid > 0) { echo $clientid . ': '; }
          echo d_decode($query_result[0]['clientname']);
          $amount = mb_substr($ourline,228,14)+0; #old=107
          $total = $total + $amount;
          echo ' &nbsp; ' . $amount . ' XPF';
          if ($clientid == 0) { echo ' <span class=alert>PAS TROUVE</span>'; }
          else
          {
            $query = 'insert into payment (forinvoiceid,clientid,paymentdate,paymenttime,value,paymenttypeid,userid,chequeno,bankid,depositbankid,payer,paymentcomment,matchingid,reimbursement,employeeid,paymentcategoryid) values (?,?,?,CURTIME(),?,?,?,?,?,?,?,?,0,?,?,2)';
            $paymentcomment = 'Prélèvement refoulé ' .  $reference . ' ' . $ourerrorcode;
            $query_prm = array(0,$clientid,$_SESSION['ds_curdate'],$amount,3,$_SESSION['ds_userid'],"",0,3,$clientname,$paymentcomment,1,0); # 2015 09 10 bankid changed from 3 to 0
            require ('inc/doquery.php');
            #echo $query . '<br>' . $clientid . ' ' . $amount . '<br><br>';
          }
          echo '</p>';
        }
        elseif ($i == 1)
        {
          $ourdate = '20' . mb_substr($ourline,14,2) . '-' . mb_substr($ourline,12,2) . '-' . mb_substr($ourline,10,2); #old=22,20,18
          echo '<h2>Non paiements ' . datefix2($ourdate) . '</h2>';
        }
      }
      echo '<br><p>Total: ' . $total . ' XPF</p>';
    }

    break;

  }
  break;
  
  case 'sageexport2':
  switch($_SESSION['ds_step'])
  {

    # Confirm
    case 0:
    ?><h2>Créer fichier à importer dans SAGE</h2>
    <form method="post" action="custom.php">
    <?php
    $month = mb_substr($_SESSION['ds_curdate'],5,2);
    $year = mb_substr($_SESSION['ds_curdate'],0,4);
    $month = $month; if ($month > 12) { $month = 1; $year = $year + 1; }
    ?>Mois: <select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><br><br>
    <input type=hidden name="step" value="1">
    <input type="submit" value="Valider">
    </form><?php
    break;
    
    case 1:
    
    $exportyear = $_POST['year']+0;
    $exportmonth = $_POST['month']+0;
    $query = 'select count(clientid) as mycount from client,town,island where client.townid=town.townid and town.islandid=island.islandid and (exported=0 or exported IS NULL)';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    echo 'Clients: '.$row['mycount'];
    $query = 'select count(invoicehistory.clientid) as mycount from invoicehistory,client where invoicehistory.clientid=client.clientid and cancelledid=0 and (invoicehistory.exported=0 or invoicehistory.exported IS NULL) and reference NOT LIKE "Contrat %" and isreturn=0 and confirmed=1 and year(accountingdate)=' . $exportyear . ' and month(accountingdate)=' . $exportmonth . '';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    echo '<br>Factures: '.$row['mycount'];
    $query = 'select count(purchasebatchid) as mycount from purchasebatch,product,supplier where purchasebatch.productid=product.productid and product.supplierid=supplier.supplierid and (purchasebatch.exported=0 or purchasebatch.exported IS NULL) and totalcost>0 and to_days(curdate())-to_days(arrivaldate) > 1 and year(arrivaldate)=' . $exportyear . ' and month(arrivaldate)=' . $exportmonth;
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    echo '<br>Achats: '.$row['mycount'];
    $query = 'select count(paymentid) as mycount from payment where (payment.exported=0 or payment.exported IS NULL) and year(paymentdate)=' . $exportyear . ' and month(paymentdate)=' . $exportmonth . '';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    echo '<br>Paiements: '.$row['mycount'];
    $query = 'select count(invoicehistory.clientid) as mycount from invoicehistory,client where invoicehistory.clientid=client.clientid and cancelledid=0 and (invoicehistory.exported=0 or invoicehistory.exported IS NULL) and reference NOT LIKE "Contrat %" and isreturn=1 and confirmed=1 and year(accountingdate)=' . $exportyear . ' and month(accountingdate)=' . $exportmonth . '';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    echo '<br>Avoirs: '.$row['mycount'];
    break;
  }
  break;

  ### Create SAGE import file ###
  case 'sageexport':
  switch($_SESSION['ds_step'])
  {

    # Confirm
    case 0:
    ?><h2>Créer fichier à importer dans SAGE</h2>
    <form method="post" action="custom.php">
    <?php
    #Exporter toutes écritures comptables lettrées et non-lettrées de <b>l'année passée</b> <input type="checkbox" name="allinvoices" value="1"><br><br>
    ?>
    <?php
    $month = mb_substr($_SESSION['ds_curdate'],5,2);
    $year = mb_substr($_SESSION['ds_curdate'],0,4);
    $month = $month; if ($month > 12) { $month = 1; $year = $year + 1; }
    ?>Mois: <select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><br><br>
    <input type=hidden name="step" value="1">
    <?php
    /*
    <input type="checkbox" name="clients" value="1">Clients (non restrain par date)<br>
    <input type="checkbox" name="invoices" value="1">Factures<br>
    <input type="checkbox" name="purchases" value="1">Achats<br>
    <input type="checkbox" name="payments" value="1">Paiements<br>
    <input type="checkbox" name="returns" value="1">Avoirs<br>
    */
    ?>
    <input type="radio" name="typechoice" value="1">Clients (non restrain par date)<br>
    <input type="radio" name="typechoice" value="2">Factures<br>
    <input type="radio" name="typechoice" value="3">Achats<br>
    <input type="radio" name="typechoice" value="4">Paiements hors location<br>
    <input type="radio" name="typechoice" value="5">Avoirs<br>
    <input type="radio" name="typechoice" value="6">Paiements location<br>
    <br><br>
    <font color=red>Êtes-vous certain?</font> <input type="submit" value="Valider">
    </form><?php
    break;

    # Make file
    case 1:
    

    /*
    $exportyear = "99999";
    $query = 'select DATE_FORMAT(curdate(),"%Y") as year';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $row = mysql_fetch_array($result);
    $exportyear = $row['year'];
    if ($_POST['allinvoices'] == 1)
    {
      $exportyear--;
    }
    */
    $exportyear = $_POST['year']+0;
    $exportmonth = $_POST['month']+0;

    /* OLD from Wing Chong
    # Account Number definitions
    $anclient = "412000";
    $ansalesnet = "707250";
    $anvat2 = "445711";
    $anvat3 = "445715";
    $anvat4 = "445712";
    $anfreight = "707301";
    $aninsurance = "707310";
    $anlocalpurchase = "60100";
    $anlocalvat = "445660";
    $anlocalsupplier = "401000";
    $animport = "602000";
    $animportvat = "445660";
    $anportfees = "608000";
    $animportsupplier = "402000";
    $anporttaxes = "444000";
    $anOD = "654000";
    $anBC = "514000";
    $anBT = "512100";
    $anSOC = "515000";
    $anBP = "512000";
    $anCS = "530000";
    $ansalarydeduct = "421000";
    */
    
    # Account Number definitions
    $anclient = "411000";
    $ansalesnet = "701000"; # was 707250
    $anvat2 = "445711"; #5
    $anvat3 = "445713"; #10
    $anvat4 = "445712"; #16
    $anfreight = "707301";
    $aninsurance = "707310";
    $anlocalpurchase = "60100";
    $anlocalvat = "445660";
    $anlocalsupplier = "401000";
    $animport = "602000";
    $animportvat = "445660";
    $anportfees = "608000";
    $animportsupplier = "402000";
    $anporttaxes = "444000";
    $anOD = "654000";
    $anBC = "514000";
    $anBT = "512140";
    $anSOC = "515000";
    $anBP = "512240";
    $anCS = "531010"; # caisse papeari
    $ansalarydeduct = "421000";
    # from Odette 2012 05 21
    #Débit compte 582.000 Dépôt banques
    $locdepotbanque = '582000';
    #Crédit compte 708100 Locations
    $loccompte = '708100';
    #Crédit compte 445713 TVA collectée 10%
    $loctva = '445713';

    function mytrim($str)
    {
      $str=ereg_replace (' +', ' ', trim($str));
      $str=ereg_replace("[\n\r\t]","",$str);
      return $str;
    }

    function makeMPCT($id,$name,$type,$annumber,$contact,$address1,$address2,$postcode,$town,$country,$tahitinumber,$telephone,$fax,$email,$website)
    {
      $id = mytrim($id);
      $name = mytrim($name); if ($name == "") { $name = "DOESNOTEXIST"; }
      $type = mytrim($type);
      $annumber = mytrim($annumber);
      $contact = mytrim($contact);
      $address1 = mytrim($address1);
      $address2 = mytrim($address2);
      $postcode = mytrim($postcode);
      $town = mytrim($town);
      $country = mytrim($country);
      $tahitinumber = mytrim($tahitinumber);
      $telephone = mytrim($telephone);
      $fax = mytrim($fax);
      $email = mytrim($email);
      $website = mytrim($website);
      $sep = chr(13) . chr(10);
      $writebuffer = '#MPCT' . $sep . mb_substr($id,0,17) . $sep . mb_substr($name,0,35) . $sep . $type . $sep . $annumber . $sep . $sep . mb_substr($name,0,17) . $sep . mb_substr($contact,0,35) . $sep;
      $writebuffer = $writebuffer . mb_substr($address1,0,35) . $sep . mb_substr($address2,0,35) . $sep . mb_substr($postcode,0,9) . $sep . mb_substr($town,0,35) . $sep . $sep . mb_substr($country,0,35) . $sep . $sep . '0' . $sep;
      $writebuffer = $writebuffer . $sep . mb_substr($tahitinumber,0,25) . $sep . $sep . $sep . $sep . $sep . $sep . $sep . $sep . $sep . $sep . $sep . $sep . $sep . '0' . $sep . '0' . $sep . $id . $sep;
      $writebuffer = $writebuffer . '1' . $sep . '1' . $sep . '0,0000' . $sep . '0,0000' . $sep . '0,0000' . $sep . '0,0000' . $sep . '1' . $sep . '1' . $sep . '1' . $sep . '0' . $sep . '0' . $sep . $sep . $sep . $sep . '1' . $sep . '1' . $sep;
      $writebuffer = $writebuffer . '1' . $sep . '1' . $sep . '0' . $sep . '0' . $sep . '0' . $sep . '010100' . $sep . '0' . $sep . '1' . $sep . $sep;
      $writebuffer = $writebuffer . mb_substr($telephone,0,17) . $sep . mb_substr($fax,0,17) . $sep . mb_substr($email,0,17) . $sep . mb_substr($website,0,17) . $sep . $sep . '0' . $sep;
      $writebuffer = $writebuffer . $sep . $sep . $sep . $sep . $sep . '0' . $sep . $sep . '0' . $sep . $sep . $sep . $sep . $sep . $sep;
      $writebuffer = $writebuffer . '0' . $sep . $annumber . $sep;
      return $writebuffer;
    }

    function makeMECG($journalcode,$date,$reference,$matchingid,$generalaccount,$id,$paymenttype,$debcred,$amount,$title)
    {
      $journalcode = mytrim($journalcode);
      $date = mytrim($date);
      $reference = mytrim($reference);
      $matchingid = mytrim($matchingid);
      $generalaccount = mytrim($generalaccount);
      $id = mytrim($id);
      $paymenttype = mytrim($paymenttype);
      $debcred = mytrim($debcred);
      $amount = mytrim($amount);
      $title = mytrim($title);
      $sep = chr(13) . chr(10);
      if ($debcred == "D") { $debcred = '0'; }
      else { $debcred = '1'; }
      $writebuffer = '#MECG' . $sep . $journalcode . $sep . date("dmy",strtotime($date)) . $sep . $sep . mb_substr($reference,0,13) . $sep . $matchingid . $sep . $sep;
      $writebuffer = $writebuffer . $generalaccount . $sep . $sep . mb_substr($id,0,16) . $sep . $sep . mb_substr($title,0,34) . $sep . $paymenttype . $sep . $sep;
      $writebuffer = $writebuffer . $sep . $sep . '0' . $sep . $debcred . $sep . mb_substr($amount,0,14) . $sep . $sep . $sep . $sep;
      $writebuffer = $writebuffer . '0'. $sep. '0'. $sep. '0'. $sep. $sep. $sep;
      return $writebuffer;
    }

    $filename = 'tosage/tosage' . date("ymdHis") . '.txt';
    $file = fopen($filename, "w");
    if (!$file) { echo "Cannot create the file!<br>"; exit; }
    $sep = chr(13) . chr(10);
    $writebuffer = '#FLG 000' . $sep . '#VER 10' . $sep . '#DEV XPF' . $sep;
    fwrite($file, utf8_decode($writebuffer));

    if ($_POST['typechoice'] == 1)
    {
    # clients
    $query = 'select clientid,clientname,contact,postaladdress,postalcode,townname,islandname,tahitinumber,telephone,fax,email from client,town,island where client.townid=town.townid and town.islandid=island.islandid and (exported=0 or exported IS NULL)';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      $id = 'C' . $row['clientid'];
      $writebuffer = makeMPCT($id,d_decode($row['clientname']),'0',$anclient,$row['contact'],$row['postaladdress'],$row['islandname'],$row['postalcode'],$row['townname'],"Polynésie Française",$row['tahitinumber'],$row['telephone'],$row['fax'],$row['email'],"");
      fwrite($file, utf8_decode($writebuffer));
      $query = 'update client set exported=1,exportdate=curdate() where clientid="' . $row['clientid'] . '"';
      $result2 = mysql_query($query, $db_conn); querycheck($result2);
    }
    }

    /*
    # suppliers
    $query = 'select supplierid,suppliername,contact,address,postalcode,town,state,telephone,fax,email,website,supplier.countryid as countryid,countryname from supplier,country where supplier.countryid=country.countryid and (exported=0 or exported IS NULL)';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      $id = 'F' . $row['supplierid'];
      if ($row['countryid'] == 156) { $annumber = $anlocalsupplier; }
      else { $annumber = $animportsupplier; }
      $writebuffer = makeMPCT($id,$row['suppliername'],'1',$annumber,$row['contact'],$row['address'],$row['state'],$row['postalcode'],$row['town'],$row['countryname'],"",$row['telephone'],$row['fax'],$row['email'],$row['website']);
      fwrite($file, utf8_decode($writebuffer));
      $query = 'update supplier set exported=1,exportdate=curdate() where supplierid="' . $row['supplierid'] . '"';
      $result2 = mysql_query($query, $db_conn); querycheck($result2);
    }
    */

    if ($_POST['typechoice'] == 2)
    {
    # invoices
    $query = 'select matchingid,reference,invoiceid,invoicehistory.clientid as clientid,clientname,accountingdate,invoiceprice as totalprice,invoicevat,freightcost,insurancecost from invoicehistory,client where invoicehistory.clientid=client.clientid and cancelledid=0 and (invoicehistory.exported=0 or invoicehistory.exported IS NULL) and reference NOT LIKE "Contrat %" and isreturn=0 and confirmed=1 and year(accountingdate)=' . $exportyear . ' and month(accountingdate)=' . $exportmonth . ' order by accountingdate';
    #if ($_POST['allinvoices'] == 1) { $query = 'select matchingid,reference,invoiceid,invoicehistory.clientid as clientid,clientname,accountingdate,totalprice,vat2,vat3,vat4,freightcost,insurancecost from invoicehistory,client where invoicehistory.clientid=client.clientid and cancelledid=0 and (invoicehistory.exported=0 or invoicehistory.exported IS NULL) and reference NOT LIKE "Contrat %" and isreturn=0 and confirmed=1 and DATE_FORMAT(accountingdate,"%Y")=' . $exportyear . ' order by accountingdate'; }
  $query = $query . ' LIMIT 1000';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result); #echo $num_results . '<br>';
  if ($num_results == 1000) { echo '<p class="alert">LIMIT: 1000 factures exportées.</p>'; }
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
        $vat2 = 0; $vat3 = 0; $vat4 = 0;
        $query2 = 'select taxcodeid,linevat from invoiceitemhistory,product where invoiceitemhistory.productid=product.productid and invoiceid="' . $row['invoiceid'] . '"';
        $result2 = mysql_query($query2, $db_conn); querycheck($result2);
        $num_results2 = mysql_num_rows($result2);
        for ($y=0; $y < $num_results2; $y++)
        {
          $row2 = mysql_fetch_array($result2);
          if ($row2['taxcodeid'] == 2) { $vat2 = $vat2 + $row2['linevat']; }
          if ($row2['taxcodeid'] == 3) { $vat3 = $vat3 + $row2['linevat']; }
          if ($row2['taxcodeid'] == 4) { $vat4 = $vat4 + $row2['linevat']; }
        }
      $matchingid = $row['matchingid'];
      $reference = 'FA' . $row['invoiceid'];
      $title = $row['invoiceid'] . ' ' . d_decode($row['clientname']);
      $journalcode = "700"; #VT
      $id = 'C' . $row['clientid'];
      $nettotal = round($row['totalprice']) - round($row['invoicevat']) - round($row['freightcost']) - round($row['insurancecost']);
      $writebuffer = makeMECG($journalcode,$row['accountingdate'],$reference,$matchingid,$anclient,$id,"0","D",round($row['totalprice']),$title); fwrite($file, utf8_decode($writebuffer));
      if ($nettotal > 0) { $writebuffer = makeMECG($journalcode,$row['accountingdate'],$reference,"0",$ansalesnet,"","0","C",$nettotal,$title); fwrite($file, utf8_decode($writebuffer)); }
      if (round($vat2) > 0) { $writebuffer = makeMECG("700",$row['accountingdate'],$reference,"0",$anvat2,"","0","C",round($vat2),$title); fwrite($file, utf8_decode($writebuffer)); }
      if (round($vat3) > 0) { $writebuffer = makeMECG("700",$row['accountingdate'],$reference,"0",$anvat3,"","0","C",round($vat3),$title); fwrite($file, utf8_decode($writebuffer)); }
      if (round($vat4) > 0) { $writebuffer = makeMECG("700",$row['accountingdate'],$reference,"0",$anvat4,"","0","C",round($vat4),$title); fwrite($file, utf8_decode($writebuffer)); }
      if (round($row['freightcost']) > 0) { $writebuffer = makeMECG("700",$row['accountingdate'],$reference,"0",$anfreight,"","0","C",round($row['freightcost']),$title); fwrite($file, utf8_decode($writebuffer)); }
      if (round($row['insurancecost']) > 0) { $writebuffer = makeMECG("700",$row['accountingdate'],$reference,"0",$aninsurance,"","0","C",round($row['insurancecost']),$title); fwrite($file, utf8_decode($writebuffer)); }
      $query = 'update invoicehistory set exported=1,exportdate=curdate() where invoiceid="' . $row['invoiceid'] . '"';
      $result2 = mysql_query($query, $db_conn); querycheck($result2);
    }
    }

    if ($_POST['typechoice'] == 3)
    {
    # purchase batches
    $query = 'select purchasebatchid,supplier.supplierid as supplierid,suppliername,arrivaldate,totalcost,vat from purchasebatch,product,supplier where purchasebatch.productid=product.productid and product.supplierid=supplier.supplierid and (purchasebatch.exported=0 or purchasebatch.exported IS NULL) and totalcost>0 and to_days(curdate())-to_days(arrivaldate) > 1 and year(arrivaldate)=' . $exportyear . ' and month(arrivaldate)=' . $exportmonth;
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      $reference = 'AL' . $row['purchasebatchid'];
      $id = 'F' . $row['supplierid'];
      $title = $id . ' ' . $row['suppliername'];
      $nettotal = $row['totalcost'] - $row['vat'];
      $writebuffer = makeMECG("AL",$row['arrivaldate'],$reference,"",$anlocalpurchase,"","0","D",$nettotal,$title); fwrite($file, utf8_decode($writebuffer));
      if ($row['vat'] > 0) { $writebuffer = makeMECG("AL",$row['arrivaldate'],$reference,"",$anlocalvat,"","0","D",$row['vat'],$title); fwrite($file, utf8_decode($writebuffer)); }
      $writebuffer = makeMECG("AL",$row['arrivaldate'],$reference,"",$anlocalsupplier,$id,"0","C",$row['totalcost'],$title); fwrite($file, utf8_decode($writebuffer));
      $query = 'update purchasebatch set exported=1,exportdate=curdate() where purchasebatchid="' . $row['purchasebatchid'] . '"';
      $result2 = mysql_query($query, $db_conn); querycheck($result2);
    }
    }

    if ($_POST['typechoice'] == 4)
    {
    # import payments
    $query = 'select reimbursement,matchingid,paymentcomment,paymentid,clientid,value,paymentdate,depositbankid,paymenttypeid,forinvoiceid from payment where (payment.exported=0 or payment.exported IS NULL) and year(paymentdate)=' . $exportyear . ' and month(paymentdate)=' . $exportmonth;
    $query = $query . ' and paymentcategoryid<>2'; # do not export Locations  (added 2012 05 20)
    $query = $query . ' order by paymentid';
    #if ($_POST['allinvoices'] == 1) { $query = 'select matchingid,paymentcomment,paymentid,clientid,value,paymentdate,depositbankid,paymenttypeid from payment where (payment.exported=0 or payment.exported IS NULL) and DATE_FORMAT(paymentdate,"%Y")>=' . $exportyear . ' order by paymentid'; }
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      $matchingid = $row['matchingid'];
      $reference = 'PMT' . $row['paymentid'];
      $id = 'C' . $row['clientid'];
      $title = $row['paymentcomment']; if ($row['forinvoiceid'] > 0) { $title = 'Fact ' . $row['forinvoiceid'] . ' ' . $title; }
      $journalcode = "533"; # CS
      $annumber = $anCS; $paymenttype = 2;
      if ($row['depositbankid'] == 4) { $journalcode = "BC"; $annumber = $anBC; }
      if ($row['depositbankid'] == 1) { $journalcode = "BT"; $annumber = $anBT; }
      if ($row['depositbankid'] == 2) { $journalcode = "SOC"; $annumber = $anSOC; }
      if ($row['depositbankid'] == 3) { $journalcode = "BP"; $annumber = $anBP; }
      
      if ($row['paymenttypeid'] == 1) { $journalcode = "533"; $annumber = $anCS; $paymenttype = 1; }
      if ($row['paymenttypeid'] == 2) { $paymenttype = 0; }
      
      if ($row['reimbursement'] == 0)
      {
        $writebuffer = makeMECG($journalcode,$row['paymentdate'],$reference,$matchingid,$annumber,"",$paymenttype,"D",$row['value'],$title); fwrite($file, utf8_decode($writebuffer));
        $writebuffer = makeMECG($journalcode,$row['paymentdate'],$reference,$matchingid,$anclient,$id,$paymenttype,"C",$row['value'],$title); fwrite($file, utf8_decode($writebuffer));
      }
      if ($row['reimbursement'] == 1)
      {
        $writebuffer = makeMECG($journalcode,$row['paymentdate'],$reference,$matchingid,$anclient,$id,$paymenttype,"D",$row['value'],$title); fwrite($file, utf8_decode($writebuffer));
        $writebuffer = makeMECG($journalcode,$row['paymentdate'],$reference,$matchingid,$annumber,"",$paymenttype,"C",$row['value'],$title); fwrite($file, utf8_decode($writebuffer));
      }

      $query = 'update payment set exported=1,exportdate=curdate() where paymentid="' . $row['paymentid'] . '"';
      $result2 = mysql_query($query, $db_conn); querycheck($result2);
    }
    }

    if ($_POST['typechoice'] == 5)
    {
    # returns
    $query = 'select matchingid,reference,invoiceid,invoice.clientid as clientid,clientname,accountingdate,invoiceprice as totalprice,invoicevat,freightcost,insurancecost from invoice,client where invoice.clientid=client.clientid and cancelledid=0 and (invoice.exported=0 or invoice.exported IS NULL) and isreturn=1 and confirmed=1 and year(accountingdate)=' . $exportyear . ' and month(accountingdate)=' . $exportmonth . ' order by accountingdate';
    #if ($_POST['allinvoices'] == 1) { $query = 'select matchingid,reference,invoiceid,displayinvoiceid,invoice.clientid as clientid,clientname,accountingdate,totalprice,vat2,vat3,vat4,freightcost,insurancecost from invoice,client where invoice.clientid=client.clientid and cancelledid=0 and (invoice.exported=0 or invoice.exported IS NULL) and isreturn=1 and confirmed=1 and DATE_FORMAT(accountingdate,"%Y")=' . $exportyear . ' order by accountingdate'; }
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
        $vat2 = 0; $vat3 = 0; $vat4 = 0;
        $query2 = 'select taxcodeid,linevat from invoiceitem where invoiceid="' . $row['invoiceid'] . '"';
        $result2 = mysql_query($query2, $db_conn); querycheck($result2);
        $num_results2 = mysql_num_rows($result2);
        for ($y=0; $y < $num_results2; $y++)
        {
          $row2 = mysql_fetch_array($result2);
          if ($row2['taxcodeid'] == 2) { $vat2 = $vat2 + $row2['linevat']; }
          if ($row2['taxcodeid'] == 3) { $vat3 = $vat3 + $row2['linevat']; }
          if ($row2['taxcodeid'] == 4) { $vat4 = $vat4 + $row2['linevat']; }
        }
      $matchingid = $row['matchingid'];
      $reference = 'FA' . $row['invoiceid'];
      $title = $row['invoiceid'] . ' ' . d_decode($row['clientname']);
      $journalcode = "700";
      $id = 'C' . $row['clientid'];
      $nettotal = round($row['totalprice']) - round($row['invoicevat']) - round($row['freightcost']) - round($row['insurancecost']);
      $writebuffer = makeMECG($journalcode,$row['accountingdate'],$reference,$matchingid,$anclient,$id,"0","C",round($row['totalprice']),$title); fwrite($file, utf8_decode($writebuffer));
      if ($nettotal > 0) { $writebuffer = makeMECG($journalcode,$row['accountingdate'],$reference,"0",$ansalesnet,"","0","D",$nettotal,$title); fwrite($file, utf8_decode($writebuffer)); }
      if (round($vat2) > 0) { $writebuffer = makeMECG("700",$row['accountingdate'],$reference,"0",$anvat2,"","0","D",round($vat2),$title); fwrite($file, utf8_decode($writebuffer)); }
      if (round($vat3) > 0) { $writebuffer = makeMECG("700",$row['accountingdate'],$reference,"0",$anvat3,"","0","D",round($vat3),$title); fwrite($file, utf8_decode($writebuffer)); }
      if (round($vat4) > 0) { $writebuffer = makeMECG("700",$row['accountingdate'],$reference,"0",$anvat4,"","0","D",round($vat4),$title); fwrite($file, utf8_decode($writebuffer)); }
      if (round($row['freightcost']) > 0) { $writebuffer = makeMECG("700",$row['accountingdate'],$reference,"0",$anfreight,"","0","D",round($row['freightcost']),$title); fwrite($file, utf8_decode($writebuffer)); }
      if (round($row['insurancecost']) > 0) { $writebuffer = makeMECG("700",$row['accountingdate'],$reference,"0",$aninsurance,"","0","D",round($row['insurancecost']),$title); fwrite($file, utf8_decode($writebuffer)); }
      $query = 'update invoice set exported=1,exportdate=curdate() where invoice="' . $row['invoice'] . '"';
      $result2 = mysql_query($query, $db_conn); querycheck($result2);
    }
    }
    
    if ($_POST['typechoice'] == 6)
    {
    # import payments location
    $query = 'select sum(value) as value,depositbankid from payment where (payment.exported=0 or payment.exported IS NULL) and year(paymentdate)=' . $exportyear . ' and month(paymentdate)=' . $exportmonth;
    $query = $query . ' and paymentcategoryid=2 and reimbursement=0'; # only export Locations  (added 2012 05 21)
    $query = $query . ' group by depositbankid order by paymentid';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      $dpbid = $row['depositbankid'];
      $value_loc[$dpbid] = $row['value'];
      #echo '<br>setting bankid '.$dpbid.'= '.$value_loc[$dpbid];
    }
    # substract reimb here
    $query = 'select sum(value) as value,depositbankid from payment where (payment.exported=0 or payment.exported IS NULL) and year(paymentdate)=' . $exportyear . ' and month(paymentdate)=' . $exportmonth;
    $query = $query . ' and paymentcategoryid=2 and reimbursement=1'; # only export Locations  (added 2012 05 21)
    $query = $query . ' group by depositbankid order by paymentid';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      $dpbid = $row['depositbankid'];
      $value_loc[$dpbid] = $value_loc[$dpbid] - $row['value'];
      #echo '<br>substracting bankid '.$dpbid.'= '.$row['value'];
    }
    for ($i=0; $i <= 4; $i++) # 4 hardcoded banks
    {
      $row = mysql_fetch_array($result);
      $matchingid = 1;
      $reference = 'PMTL' . $exportyear . $exportmonth;
      $id = 'L' . $exportyear . $exportmonth;
      $title = 'Paiem Loc ' . $exportyear . ' ' . $exportmonth;
      $journalcode = "533"; # CS
      $annumber = $anCS; $paymenttype = 2;
      if ($row['depositbankid'] == 4) { $journalcode = "BC"; $annumber = $anBC; }
      if ($row['depositbankid'] == 1) { $journalcode = "BT"; $annumber = $anBT; }
      if ($row['depositbankid'] == 2) { $journalcode = "SOC"; $annumber = $anSOC; }
      if ($row['depositbankid'] == 3) { $journalcode = "BP"; $annumber = $anBP; }
      
      if ($row['paymenttypeid'] == 1) { $journalcode = "533"; $annumber = $anCS; $paymenttype = 1; }
      if ($row['paymenttypeid'] == 2) { $paymenttype = 0; }
      
      if ($value_loc[$i] != 0)
      {
        $ourdate = d_builddate(1,$exportmonth,$exportyear);
        $ournet = round($value_loc[$i]*10/11);
        $ourvat = $value_loc[$i] - $ournet;
        $writebuffer = makeMECG($journalcode,$ourdate,$reference,$matchingid,$locdepotbanque,"",$paymenttype,"D",$value_loc[$i],$title); fwrite($file, utf8_decode($writebuffer));
        $writebuffer = makeMECG($journalcode,$ourdate,$reference,$matchingid,$loccompte,"",$paymenttype,"C",$ournet,$title); fwrite($file, utf8_decode($writebuffer));
        $writebuffer = makeMECG($journalcode,$ourdate,$reference,$matchingid,$loctva,"",$paymenttype,"C",$ourvat,$title); fwrite($file, utf8_decode($writebuffer));
      }
      $query = 'update payment set exported=1,exportdate=curdate() where paymentid="' . $row['paymentid'] . '"';
      $result2 = mysql_query($query, $db_conn); querycheck($result2);
    }
    }

    $writebuffer = '#FIN' . $sep;
    fwrite($file, utf8_decode($writebuffer));

    echo '<p>Fichier <a href="tosage/' . basename($filename) . '"><font color=blue><i>' . basename($filename) . '</i></font></a> créé.</p>';
    ?><p>- Cliquer sur le bouton droit de la souris</p>
    <p>- Enregistrer la cible</p><?php
    break;

  }
  break;

  ### Print invoices ###
  case 'showinvoices':
  switch($_SESSION['ds_step'])
  {
    # 
    case 0:
    ?><h2>Afficher factures location:</h2><?php
    $ourfile = "custom.php";
    echo '<form method="post" action="' . $ourfile . '"><table>';
    $month = mb_substr($_SESSION['ds_curdate'],5,2);
    $year = mb_substr($_SESSION['ds_curdate'],0,4);
    $month = $month; if ($month > 12) { $month = 1; $year = $year + 1; }
    ?><tr><td>Debut:</td><td><select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td><input type=checkbox name="nozero" value=1></td><td>Exclure le montant 0F</td></tr>
    <tr><td><input type=checkbox name="noprelev" value=1></td><td>Exclure prélèvement</td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    case 1:
    
    $total = 0;
    $query = 'select reference,proforma,isreturn,invoiceid,deliverydate,clientname,invoice.clientid as clientid,invoiceprice,initials from invoice,client,usertable where invoice.userid=usertable.userid and invoice.clientid=client.clientid and cancelledid=0 and reference like "%Contrat%" and month(accountingdate)="' . $_POST['month'] . '" and year(accountingdate)="' . $_POST['year'] . '"';
    if ($_POST['nozero'] == 1) { $query = $query . ' and invoiceprice > 0'; }
    $query = $query . ' UNION ';
    $query = $query . 'select reference,proforma,isreturn,invoiceid,deliverydate,clientname,invoicehistory.clientid as clientid,invoiceprice,initials from invoicehistory,client,usertable where invoicehistory.userid=usertable.userid and invoicehistory.clientid=client.clientid and cancelledid=0 and reference like "%Contrat%" and month(accountingdate)="' . $_POST['month'] . '" and year(accountingdate)="' . $_POST['year'] . '"';
    if ($_POST['nozero'] == 1) { $query = $query . ' and invoiceprice > 0'; }
    $query = $query . ' order by invoiceid';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    echo '<table border=1 cellspacing=2 cellpadding=2><tr><td><b>Facture</td><td><b>Facturier</td><td><b>Client</td><td><b>Prix total</td><td><b>Date de livraison</td><td><b>Afficher</td></tr>';
    for ($i=1; $i <= $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      $returntext = ""; if ($row['isreturn'] == 1) { $returntext = '(Avoir) '; }
      if ($row['proforma'] == 1) { $returntext = '(Proforma) '; }
      $ok = 1;
      if ($_POST['noprelev'] == 1)
      {
        $ok = 0;
        $reference = $row['reference'];
        $startpos = 8; # 'Contrat '
        $reference = mb_substr($reference, $startpos);
        $endpos = mb_strlen($reference);
        if(mb_strpos($reference, ',')) { $endpos = mb_strpos($reference, ','); }
        $reference = mb_substr($reference, 0, $endpos);
      #echo 'look for: '.$reference;
        $query2 = 'select months,noprelev from vmt_rental where reference="' . $reference . '" LIMIT 1';
        $result2 = mysql_query($query2, $db_conn); querycheck($result2);
        $row2 = mysql_fetch_array($result2);
      #echo $row2['months'].' '.$row2['noprelev'].'<br>';
        if ($row2['months'] != 1 || $row2['noprelev'] == 1) { $ok = 1; }
      }
      if ($ok)
      {
        echo '<tr><td align=right>' . $returntext . myfix($row['invoiceid']) . '</td><td>' . $row['initials'] . '</td><td>' . $row['clientid'] . ': ' . d_decode($row['clientname']) . '</td><td align=right>' . myfix($row['invoiceprice']) . '</td><td align=right>' . datefix($row['deliverydate']) . '</td><td><a href="customprintwindow.php?report=1&invoiceid=' . $row['invoiceid'] . '" target="_NEW">Facture ' .  $row['invoiceid'] . '</a></td></tr>';
        $total = $total + $row['invoiceprice'];
      }
    }
    echo '<tr><td><b>Total</td><td colspan=2>&nbsp;</td><td align=right><b>' . myfix($total) . '</td><td colspan=2>&nbsp;</td></tr>';
    echo '</table>';
    break;

  }
  break;


  ### Print invoices ###
  case 'OLDshowinvoices': # does not work because absolute positioning overwrites
  switch($_SESSION['ds_step'])
  {
    # 
    case 0:
exit;
    ?><h2>Afficher factures:</h2><?php
    $ourfile = "custom/vaimatoprintwindow.php";
    echo '<form method="post" action="' . $ourfile . '" target="_NEW"><table>';
    $month = mb_substr($_SESSION['ds_curdate'],5,2);
    $year = mb_substr($_SESSION['ds_curdate'],0,4);
    $month = $month + 1; if ($month > 12) { $month = 1; $year = $year + 1; }
    ?><tr><td>Debut:</td><td><select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="report" value="1"><input type=hidden name="showrentals" value="1"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

  }
  break;
  
  case 'instreport':
  switch($_SESSION['ds_step'])
  {
    # Read
    case '0':
    
    ?><h2>Rapport installations:</h2>
    <form method="post" action="customreportwindow.php" target="_blank">
    <table>
    <?php
    echo '<tr><td>De:</td><td>';
    $datename = 'instdate';
    require('inc/datepicker.php');
    echo '</td></tr>';
    echo '<tr><td>à:</td><td>';
    $datename = 'instdate2';
    require('inc/datepicker.php');
    echo '</td></tr>';
    echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="instreport"><input type="submit" value="Valider"></td></tr></table></form>';
    break;

  }
  break;


  case 'fonrep':
  switch($_SESSION['ds_step'])
  {
    # Read
    case '0':
    ?><h2>Rapport fontaines:</h2>
    <form method="post" action="customprintwindow.php" target="_blank">
    <table>
    <?php
    echo '<tr><td>Marque:</td><td><select name="brandid">';
    echo '<option value="-1"> </option>';
    $query = 'select brandid,brandname from vmt_brand order by brandname';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results == 0) { $error = 1; }
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row2['brandid'] == $row['brandid']) { echo '<option value="' . $row2['brandid'] . '" SELECTED>' . $row2['brandname'] . '</option>'; }
      else { echo '<option value="' . $row2['brandid'] . '">' . $row2['brandname'] . '</option>'; }
    }
    echo '</select></td></tr>';
    ?><tr><td>Catégorie:</td>
    <td><select name="fountaincatid"><?php
    echo '<option value="-1"> </option>';
    $query = 'select fountaincatid,fountaincatname from vmt_fountaincat order by fountaincatname';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results == 0) { $error = 1; }
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row['fountaincatid'] == $row2['fountaincatid']) { echo '<option value="' . $row2['fountaincatid'] . '" SELECTED>' . $row2['fountaincatname'] . '</option>'; }
      else { echo '<option value="' . $row2['fountaincatid'] . '">' . $row2['fountaincatname'] . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>Etat:</td>
    <td><select name="fountaindescid"><?php
    echo '<option value="-1"> </option>';
    $query = 'select fountaindescid,fountaindescname from vmt_fountaindesc order by fountaindescname';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results == 0) { $error = 1; }
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row['fountaindescid'] == $row2['fountaindescid']) { echo '<option value="' . $row2['fountaindescid'] . '" SELECTED>' . $row2['fountaindescname'] . '</option>'; }
      else { echo '<option value="' . $row2['fountaindescid'] . '">' . $row2['fountaindescname'] . '</option>'; }
    }
    ?></select></td></tr><?php
    $day = mb_substr($_SESSION['ds_curdate'],8,2);
    $month = mb_substr($_SESSION['ds_curdate'],5,2);
    $year = mb_substr($_SESSION['ds_curdate'],0,4);
    ?>
    
    <tr><td><input type=checkbox name="usedate2" value=1> Date service:</td><td><select name="day2"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="month2"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year2"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>&nbsp;</td><td><select name="stopday2"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="stopmonth2"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="stopyear2"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    
    <tr><td><input type=checkbox name="usedate1" value=1> Date changement état:</td><td><select name="day"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>&nbsp;</td><td><select name="stopday"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="stopmonth"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="stopyear"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    
    <tr><td><input type=checkbox name="usedate3" value=1> Date dèrnier entretien:</td><td><select name="day3"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="month3"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year3"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>&nbsp;</td><td><select name="stopday3"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="stopmonth3"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="stopyear3"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    
    <tr><td><input type=checkbox name="usedate4" value=1> Date prochain entretien:</td><td><select name="day4"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="month4"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year4"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>&nbsp;</td><td><select name="stopday4"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="stopmonth4"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="stopyear4"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <?php
    echo '<tr><td>Location:</td><td><select name="rentalstatus"><option value="-1"> </option><option value="1">Disponibles</option><option value="2">Louées</option></select></td></tr>';
    echo '<tr><td>Gratuit:</td><td><select name="gratuit"><option value="-1"> </option><option value="1">Gratuit</option><option value="0">Payant</option></select></td></tr>';
    ?><tr><td>Secteur client:</td>
    <td><select name="clientsectorid"><option value=0> </option><?php
    $query = 'select clientsectorid,clientsectorname from clientsector order by clientsectorname';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      echo '<option value="' . $row2['clientsectorid'] . '">' . $row2['clientsectorname'] . '</option>';
    }
    ?></select></td></tr><?php
    echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="fonrep"><input type="submit" value="Valider"></td></tr></table></form>';
    break;

  }
  break;

  ### new fountain ###
  case 'newfon':
  switch($_SESSION['ds_step'])
  {
    # Read
    case '0':
    $error = 0;
    
    ?><h2>Nouvelle fontaine:</h2>
    <form method="post" action="custom.php"><table>
    <tr><td>Numéro fontaine/préfix:</td><td><input autofocus type="text" STYLE="text-align:right" name="fountainname" size=20>
    &nbsp; Numéros <input type="text" STYLE="text-align:right" name="startfon" size=5> à <input type="text" STYLE="text-align:right" name="stopfon" size=5></td>
    </td></tr>
    <tr><td>Marque:</td>
    <td><select name="brandid"><?php
    $query = 'select brandid,brandname from vmt_brand order by brandname';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results == 0) { $error = 1; }
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      echo '<option value="' . $row2['brandid'] . '">' . $row2['brandname'] . '</option>';
    }
    ?></select></td></tr>
    <tr><td>Catégorie:</td>
    <td><select name="fountaincatid"><?php
    
    $query = 'select fountaincatid,fountaincatname from vmt_fountaincat order by fountaincatname';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results == 0) { $error = 1; }
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      echo '<option value="' . $row2['fountaincatid'] . '">' . $row2['fountaincatname'] . '</option>';
    }
    ?></select></td></tr>
    <tr><td>Etat:</td>
    <td><select name="fountaindescid"><?php
    
    $query = 'select fountaindescid,fountaindescname from vmt_fountaindesc order by fountaindescname';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results == 0) { $error = 1; }
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      echo '<option value="' . $row2['fountaindescid'] . '"';
      if ($row2['fountaindescname'] == "STOCK") { echo ' selected'; }
      echo '>' . $row2['fountaindescname'] . '</option>';
    }
    ?></select></td></tr>
    <tr><td>Date service:</td><td colspan=3><?php
    $day = mb_substr($_SESSION['ds_curdate'],8,2);
    $month = mb_substr($_SESSION['ds_curdate'],5,2);
    $year = mb_substr($_SESSION['ds_curdate'],0,4);
    ?><select name="day"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>Date changement état:</td><td colspan=3><?php
    $day = mb_substr($_SESSION['ds_curdate'],8,2);
    $month = mb_substr($_SESSION['ds_curdate'],5,2);
    $year = mb_substr($_SESSION['ds_curdate'],0,4);
    ?><select name="day2"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="month2"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year2"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>Remarques:</td><td><input type="text" name="fntcomments" size=80></td></tr>
    <?php
    if ($error) { echo '<font color="' . $_SESSION['ds_alertcolor'] . '">Il faut definir un fournisseur, une catégorie et une etat.</font>'; exit; }
    ?>
    <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    # Save
    case '1':
    
    $ok = 1;
    $servicedate = d_builddate($_POST['day'],$_POST['month'],$_POST['year']);
    $servicedate = correctdate($servicedate);
    $changedate = d_builddate($_POST['day2'],$_POST['month2'],$_POST['year2']);
    $changedate = correctdate($changedate);
    $startfon = $_POST['startfon']; $stopfon = $_POST['stopfon']; $append = 1;
    $num_fountains = $stopfon - $startfon + 0; if ($num_fountains > 100) { echo 'erreur +100 fontaines'; $ok = 0; }
    if ($_POST['fountainname'] != "" && $num_fountains < 1) { $num_fountains = 1; $startfon = 1; $stopfon = 1; $append = 0; }
    if ($ok)
    {
    for ($i=$startfon; $i <= $stopfon; $i++)
    {
      # add many fountains
      $fountainname = $_POST['fountainname'];
      if ($append) { $fountainname = $fountainname . $i; }
      else
      {
        #$fountainname = $fountainname . $startfon;
      }
      $query = 'select rentalid from vmt_fountain where fountainname="' . $fountainname . '"';
      $query_prm = array();
      require('inc/doquery.php');
      if ($num_results > 0)
      {
        echo '<p>Fontaine ' . $fountainname . ' existe déja.</p>';
      }
      else
      {
        $query = 'insert into vmt_fountain (fountainname,brandid,fountaininfo,rentalid,notused,fountaincatid,fountaindescid,servicedate,changedate,fntcomments) values ("' . $fountainname . '","' . $_POST['brandid'] . '","' . $_POST['fountaininfo'] . '",0,0,"' . $_POST['fountaincatid'] . '","' . $_POST['fountaindescid'] . '","' . $servicedate . '","' . $changedate . '","' . $_POST['fntcomments'] . '")';
        $query_prm = array();
        require('inc/doquery.php');
        if ($num_result)
        {
          echo '<p>Fontaine ' . $fountainname . ' ajouté.</p>';
        }
      }
    }
    }
    break;

  }
  break;

  ### new brand ###
  case 'newbrand':
  switch($_SESSION['ds_step'])
  {
    # Read
    case '0':
    $error = 0;
    
    ?><h2>Nouvelle marque:</h2>
    <form method="post" action="custom.php"><table>
    <tr><td>Marque:</td><td><input type="text" STYLE="text-align:right" name="brandname" size=20></td></tr>
    <tr><td>Fournisseur:</td>
    <td><select name="supplierid"><?php
    $query = 'select supplierid,suppliername from supplier order by suppliername';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    if ($num_results == 0) { $error = 1; }
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = mysql_fetch_array($result);
      echo '<option value="' . $row2['supplierid'] . '">' . $row2['supplierid'] . ': ' . $row2['suppliername'] . '</option>';
    }
    ?></select></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    # Save
    case '1':
    
    $query = 'insert into vmt_brand (brandname,supplierid) values ("' . $_POST['brandname'] . '","' . $_POST['supplierid'] . '")';
    $result = mysql_query($query, $db_conn); querycheck($result);
    if ($result)
    {
      echo '<p>Marque ' . $_POST['brandname'] . ' ajouté.</p>';
    }
    break;

  }
  break;

  ### new fountain desc ###
  case 'newfondesc':
  switch($_SESSION['ds_step'])
  {
    # Read
    case '0':
    $error = 0;
    
    ?><h2>Nouvelle etat fontaine:</h2>
    <form method="post" action="custom.php"><table>
    <tr><td>Etat:</td><td><input type="text" STYLE="text-align:right" name="fountaindescname" size=50></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    # Save
    case '1':
    
    $query = 'insert into vmt_fountaindesc (fountaindescname) values ("' . $_POST['fountaindescname'] . '")';
    $result = mysql_query($query, $db_conn); querycheck($result);
    if ($result)
    {
      echo '<p>Etat fontaine ' . $_POST['fountaindescname'] . ' ajouté.</p>';
    }
    break;

  }
  break;

  ### new fountain cat ###
  case 'newfoncat':
  switch($_SESSION['ds_step'])
  {
    # Read
    case '0':
    $error = 0;
    
    ?><h2>Nouvelle catégorie fontaine:</h2>
    <form method="post" action="custom.php"><table>
    <tr><td>Type/coleur:</td><td><input type="text" STYLE="text-align:right" name="fountaincatname" size=50></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    # Save
    case '1':
    
    $query = 'insert into vmt_fountaincat (fountaincatname) values ("' . $_POST['fountaincatname'] . '")';
    $result = mysql_query($query, $db_conn); querycheck($result);
    if ($result)
    {
      echo '<p>Catégorie fontaine ' . $_POST['fountaincatname'] . ' ajouté.</p>';
    }
    break;

  }
  break;

  case 'listfon':
  $query = 'select fountainname,suppliername,fountaincatname,brandname
  from vmt_fountain,vmt_brand,supplier,vmt_fountaincat
  where vmt_fountain.fountaincatid=vmt_fountaincat.fountaincatid and vmt_fountain.brandid=vmt_brand.brandid
  and vmt_brand.supplierid=supplier.supplierid and rentalid=0 and notused=0 order by suppliername,fountainname';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<h2>Fontaines disponibles</h2><br>';
  echo '<table border=1 cellpadding=2 cellspacing=2><tr><td><b>Numéro</b></td><td><b>Catégorie</b></td><td><b>Fournisseur</b></td></tr>';
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<tr><td>' . $row['fountainname'] . '</td><td>' . $row['fountaincatname'] . '</td><td>' . $row['suppliername'] . '</td></tr>';
  }
  echo '</table>';
  echo '<br><br>';
  $query = 'select fountainname,suppliername,fountaincatname,client.clientid,clientname,reference,brandname,client.telephone,client.cellphone from vmt_fountain,vmt_brand,supplier,vmt_fountaincat,vmt_rental,client where vmt_fountain.rentalid=vmt_rental.rentalid and vmt_rental.clientid=client.clientid and vmt_fountain.fountaincatid=vmt_fountaincat.fountaincatid and vmt_fountain.brandid=vmt_brand.brandid and vmt_brand.supplierid=supplier.supplierid and vmt_fountain.rentalid>0 and notused=0 order by suppliername,fountainname';
  $query_prm = array();
    require('inc/doquery.php');
  echo '<h2>Fontaines louées</h2><br>';
  echo '<table border=1 cellpadding=2 cellspacing=2><tr><td><b>Numéro</b></td><td><b>Catégorie</b></td><td><b>Fournisseur</b></td><td><b>Contrat</b></td><td><b>Client</b></td><td><b>Tél.</b></td></tr>';
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<tr><td>' . $row['fountainname'] . '</td><td>' . $row['fountaincatname'] . '</td><td>' . $row['suppliername'] . '</td><td>' . $row['reference'] . '</td><td>' . $row['clientid'] . ': ' . d_output(d_decode($row['clientname'])) . '</td><td>' . $row['telephone'] . ' ' . $row['cellphone'] . '</td></tr>';
  }
  echo '</table>';
  echo '<br><br>';
  $query = 'select fountainname,suppliername,fountaincatname,brandname from vmt_fountain,vmt_brand,supplier,vmt_fountaincat where vmt_fountain.fountaincatid=vmt_fountaincat.fountaincatid and vmt_fountain.brandid=vmt_brand.brandid and vmt_brand.supplierid=supplier.supplierid and notused=1 order by suppliername,fountainname';
  $query_prm = array();
    require('inc/doquery.php');
  echo '<h2>Fontaines supprimées</h2><br>';
  echo '<table border=1 cellpadding=2 cellspacing=2><tr><td><b>Numéro</b></td><td><b>Catégorie</b></td><td><b>Fournisseur</b></td></tr>';
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<tr><td>' . $row['fountainname'] . '</td><td>' . $row['fountaincatname'] . '</td><td>' . $row['suppliername'] . '</td></tr>';
  }
  echo '</table>';
  break;
  
  ### List fon rented not free ###
  case 'listfon2':
  switch($_SESSION['ds_step'])
  {

    # 
    case '0':
    
    $query = 'select fountainname,suppliername,fountaincatname,client.clientid,clientname,reference,brandname,client.telephone,client.cellphone from vmt_fountain,vmt_brand,supplier,vmt_fountaincat,vmt_rental,client where vmt_fountain.rentalid=vmt_rental.rentalid and vmt_rental.clientid=client.clientid and vmt_fountain.fountaincatid=vmt_fountaincat.fountaincatid and vmt_fountain.brandid=vmt_brand.brandid and vmt_brand.supplierid=supplier.supplierid and vmt_fountain.rentalid>0 and notused=0 and rentalprice>0 order by suppliername,fountainname';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    echo '<h2>' . $num_results . ' fontaines louées, non gratuites</h2><br>';
    echo '<table border=1 cellpadding=2 cellspacing=2><tr><td><b>Numéro</b></td><td><b>Catégorie</b></td><td><b>Fournisseur</b></td><td><b>Contrat</b></td><td><b>Client</b></td><td><b>Tél.</b></td></tr>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      echo '<tr><td>' . $row['fountainname'] . '</td><td>' . $row['fountaincatname'] . '</td><td>' . $row['suppliername'] . '</td><td>' . $row['reference'] . '</td><td>' . $row['clientid'] . ': ' . d_output(d_decode($row['clientname'])) . '</td><td>' . $row['telephone'] . ' ' . $row['cellphone'] . '</td></tr>';
    }
    echo '</table>';
    break;

  }
  break;
  
  ### List fon rented free ###
  case 'listfon3':
  switch($_SESSION['ds_step'])
  {

    # 
    case '0':
    
    $query = 'select fountainname,suppliername,fountaincatname,client.clientid,clientname,reference,brandname,client.telephone,client.cellphone from vmt_fountain,vmt_brand,supplier,vmt_fountaincat,vmt_rental,client where vmt_fountain.rentalid=vmt_rental.rentalid and vmt_rental.clientid=client.clientid and vmt_fountain.fountaincatid=vmt_fountaincat.fountaincatid and vmt_fountain.brandid=vmt_brand.brandid and vmt_brand.supplierid=supplier.supplierid and vmt_fountain.rentalid>0 and notused=0 and rentalprice=0 order by suppliername,fountainname';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    echo '<h2>' . $num_results . ' fontaines louées, gratuites</h2><br>';
    echo '<table border=1 cellpadding=2 cellspacing=2><tr><td><b>Numéro</b></td><td><b>Catégorie</b></td><td><b>Fournisseur</b></td><td><b>Contrat</b></td><td><b>Client</b></td><td><b>Tél.</b></td></tr>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      echo '<tr><td>' . $row['fountainname'] . '</td><td>' . $row['fountaincatname'] . '</td><td>' . $row['suppliername'] . '</td><td>' . $row['reference'] . '</td><td>' . $row['clientid'] . ': ' . d_output(d_decode($row['clientname'])) . '</td><td>' . $row['telephone'] . ' ' . $row['cellphone'] . '</td></tr>';
    }
    echo '</table>';
    break;

  }
  break;


  ### List fon cat ###
  case 'listfoncat':
  switch($_SESSION['ds_step'])
  {

    # 
    case '0':
    
    $query = 'select fountaincatname from vmt_fountaincat order by fountaincatname';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    echo '<h2>Catégories fontaines</h2><br>';
    echo '<table border=1 cellpadding=2 cellspacing=2><tr><td><b>Catégorie</b></td></tr>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      echo '<tr><td>' . $row['fountaincatname'] . '</td></tr>';
    }
    echo '</table>';
    break;

  }
  break;


  ### List fon desc ###
  case 'listfondesc':
  switch($_SESSION['ds_step'])
  {

    # 
    case '0':
    
    $query = 'select fountaindescname from vmt_fountaindesc order by fountaindescname';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    echo '<h2>Etats fontaines</h2><br>';
    echo '<table border=1 cellpadding=2 cellspacing=2><tr><td><b>Etat</b></td></tr>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      echo '<tr><td>' . $row['fountaindescname'] . '</td></tr>';
    }
    echo '</table>';
    break;

  }
  break;

  ### List brand ###
  case 'listbrand':
  switch($_SESSION['ds_step'])
  {

    # 
    case '0':
    
    $query = 'select brandname,suppliername from vmt_brand,supplier where vmt_brand.supplierid=supplier.supplierid order by brandname';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    echo '<h2>Marques</h2><br>';
    echo '<table border=1 cellpadding=2 cellspacing=2><tr><td><b>Etat</b></td><td><b>Fournisseur</b></td></tr>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      echo '<tr><td>' . $row['brandname'] . '</td><td>' . $row['suppliername'] . '</td></tr>';
    }
    echo '</table>';
    break;

  }
  break;


  case 'echfon':
  
  $oldfountainname = $_POST['oldfountainname'];
  $newfountainname = $_POST['newfountainname'];
  
  $query = 'select fountaindescid from vmt_fountaindesc where fountaindescname="CLIENT"';
  $query_prm = array();
  require('inc/doquery.php');
  $etatclient = $query_result[0]['fountaindescid'];

  $oldfok = 0;
  $query = 'select vmt_fountain.fountaindescid,vmt_fountain.rentalid,fountaindescname,client.clientid,clientname,reference,fountaincatname from vmt_fountain,vmt_rental,client,vmt_fountaindesc,vmt_fountaincat where vmt_fountain.fountaincatid=vmt_fountaincat.fountaincatid and vmt_fountain.rentalid=vmt_rental.rentalid and vmt_rental.clientid=client.clientid and vmt_fountain.fountaindescid=vmt_fountaindesc.fountaindescid and notused=0 and vmt_fountain.fountaindescid="' . $etatclient . '"';
  $query = $query . ' and fountainname="' . $oldfountainname . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row = $query_result[0];
  $clientid = $row['clientid'];
  $client = $row['clientid'] . ': ' . $row['clientname'];
  $reference = $row['reference'];
  $rentalid = $row['rentalid'];
  $fountaindescid = $row['fountaindescid'];
  $fountaindescname = $row['fountaindescname'];
  $fcatname = $row['fountaincatname'];
  if ($num_results > 0 && $row['rentalid'] > 0) { $oldfok = 1; }

  $newfok = 0;
  $query = 'select rentalid,fountaindescname,fountaincatname from vmt_fountain,vmt_fountaindesc,vmt_fountaincat where vmt_fountain.fountaincatid=vmt_fountaincat.fountaincatid and vmt_fountain.fountaindescid=vmt_fountaindesc.fountaindescid and vmt_fountain.fountaindescid<>"' . $etatclient . '" and fountainname="' . $newfountainname . '"';
  $query_prm = array();
    require('inc/doquery.php');
    $row = $query_result[0];
  $fountaindescnameold = $row['fountaindescname'];
  $fcatnameold = $row['fountaincatname'];
  if ($num_results > 0 && $row['rentalid'] == 0) { $newfok = 1; }
 
#echo '<br>debug $oldfok = ' .$oldfok;
#echo '<br>debug $newfok = ' .$newfok;
#echo '<br>debug $_POST[doit] = ' .$_POST['doit'];

  if ($_POST['doit'] == 1)
  {
    $actiondate = d_builddate($_POST['day'],$_POST['month'],$_POST['year']);
    $mtd2day = mb_substr($actiondate,8,2);
    $mtd2month = mb_substr($actiondate,5,2);
    $mtd2year = mb_substr($actiondate,0,4)+2;
    $maintdate2 = d_builddate($mtd2day,$mtd2month,$mtd2year);
    $query = 'update vmt_fountain set maintdate2="' . $maintdate2 . '",changedate="' . $actiondate . '",rentalid="' . $rentalid . '",fountaindescid="' . $fountaindescid . '" where fountainname="' . $newfountainname . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $query = 'select fountaindescid from vmt_fountaindesc where fountaindescname="STOCK"';
    $query_prm = array();
    require('inc/doquery.php');
    $row = $query_result[0];
    $instock = $row['fountaindescid'];
    $query = 'update vmt_fountain set changedate="' . $actiondate . '",rentalid="0",fountaindescid="' . $instock . '" where fountainname="' . $oldfountainname . '"';
    $query_prm = array();
    require('inc/doquery.php');
    echo '<p>Echange effectué.</p>';
    
    ### vmt_fonhis
    $query = 'select clientid from vmt_rental where rentalid="' . $rentalid . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $row = $query_result[0];
    $clientid = $row['clientid'];
    $query = 'select fountainid from vmt_fountain where fountainname="' . $newfountainname . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $row = $query_result[0];
    $fountainid = $row['fountainid'];
    $query = 'insert into vmt_fonhis (fountainid,fonhisdate,fountaindescid,clientid,rentalid,userid) values ("' . $fountainid . '","' . $actiondate . '","' . $fountaindescid . '","' . $clientid . '","' . $rentalid . '","' . $_SESSION['ds_userid'] . '")';
    $query_prm = array();
    require('inc/doquery.php');
    
    $query = 'select fountainid from vmt_fountain where fountainname="' . $oldfountainname . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $row = $query_result[0];
    $fountainid = $row['fountainid'];
    $query = 'insert into vmt_fonhis (fountainid,fonhisdate,fountaindescid,clientid,rentalid,userid,employeeid) values ("' . $fountainid . '","' . $actiondate . '","' . $instock . '","0","0","' . $_SESSION['ds_userid'] . '","'.$_POST['employeeid'].'")';
    $query_prm = array();
    require('inc/doquery.php');
    ###

    $query = 'select clientactioncatid from clientactioncat where clientactioncatname="ECHANGE FONTAINE"';
    $query_prm = array();
    require('inc/doquery.php');
    $row = $query_result[0];
    $clientactioncatid = $row['clientactioncatid'];

    $ourtext = $oldfountainname . ' en ' . $newfountainname;
  
    $query = 'insert into clientaction (clientactionfield1,clientid,actiondate,employeeid,clientactioncatid,actionname,userid) values ("' . $_POST['clientactionfield1'] . '","' . $clientid . '","' . $actiondate . '","' . $_POST['employeeid'] . '","' . $clientactioncatid . '","' . $ourtext . '","' . $_SESSION['ds_userid'] . '")';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_result)
    {
      echo '<p>Action client ECHANGE FONTAINE ajouté.</p>';
    }

  }

  if ($oldfok && $newfok && $_POST['doit'] != 1)
  {

    echo '<h2>Echange fontaine</h2><table border=1 cellspacing=2 cellpadding=2><form method="post" action="custom.php">';
    echo '<tr><td><b>Fontaine</td><td><b>Catégorie</td><td><b>Etat</td><td><b>Client</td><td><b>Contrat</td></tr>';
    echo '<tr><td><b>' . $oldfountainname . '</td><td>' . $fcatname . '</td><td>' . $fountaindescname . '</td><td>' . $client . '</td><td>' . $reference . '</td></tr>';
    echo '<tr><td><b>' . $newfountainname . '</td><td>' . $fcatnameold . '</td><td>' . $fountaindescnameold . '</td></td>&nbsp;<td></td>&nbsp;<td></tr>';
    ?>
    <tr><td>Date:</td><td colspan=4><?php
    $day = mb_substr($_SESSION['ds_curdate'],8,2);
    $month = mb_substr($_SESSION['ds_curdate'],5,2);
    $year = mb_substr($_SESSION['ds_curdate'],0,4);
    ?><select name="day"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <?php # 2014 09 02
    if (isset($_SESSION['ds_term_clientactionfield1']) && $_SESSION['ds_term_clientactionfield1'] != '')
    {
    echo '<tr><td>' . d_output($_SESSION['ds_term_clientactionfield1']) . ':</td><td><input type="text" STYLE="text-align:left" name="clientactionfield1" value="' . $_POST['clientactionfield1'] . '" size=80></td></tr>';
    }
    ?>
    <tr><td>Employé(e):</td>
    <td colspan=4><select name="employeeid"><option value="0"></option><?php
    
    $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee order by employeename';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeename'] . '</option>';
    }
    ?></select></td></tr>
    <?php
    echo '<tr><td colspan="5" align="center"><input type=hidden name="oldfountainname" value="' . $oldfountainname . '"><input type=hidden name="newfountainname" value="' . $newfountainname . '"><input type=hidden name="doit" value="1"><input type=hidden name="step" value="0"><input type="submit" value="Valider"></td></tr>';
    echo '</form></table>';
  }
  elseif ($_POST['doit'] != 1)
  {
    echo '<h2>Echange fontaine:</h2><form method="post" action="custom.php"><table>';
    echo '<tr><td>La fontaine:</td><td><input autofocus type="text" STYLE="text-align:right" name="oldfountainname" value="' . $oldfountainname . '" size=25>';
    if (!$oldfok) { echo ' <span class="alert">Veuiller spécifier une fontaine louée (état CLIENT).</span>'; }
    echo '</td></tr>';
    echo '<tr><td>est remplacé par:</td><td><input type="text" STYLE="text-align:right" name="newfountainname" value="' . $newfountainname . '" size=25>';
    if (!$newfok) { echo ' <span class="alert">Veuiller spécifier une fontaine disponible (non état CLIENT).</span>'; }
    echo '</td></tr>';
    echo '<tr><td colspan="2" align="center"><input type=hidden name="step" value="0"><input type="submit" value="Valider"></td></tr>';
    echo '</table></form>';
  }
  break;

  
  case 'fonhis':
  switch($_SESSION['ds_step'])
  {

    case 0:
    ?><h2>Historique fontaine:</h2>
    <form method="post" action="customreportwindow.php" target="_blank">
    <table>
    <tr><td>Nom:</td><td><input autofocus type="text" STYLE="text-align:right" name="fountainname" size=25></td></tr>
    <tr><td colspan="2" align="center">
    <input type=hidden name="report" value="fonhis">
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;
    
  }
  break;
  
  

  ### Edit fontain ###
  case 'modfon':
  switch($_SESSION['ds_step'])
  {

    case 0:
    ?><h2>Modifier fontaine:</h2>
    <form method="post" action="custom.php">
    <table>
    <tr><td>Nom:</td><td><input autofocus type="text" STYLE="text-align:right" name="fountainname" size=25></td></tr>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="1">
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    case 1:
    $fountainid = $_POST['fountainid'];
    if ($_POST['fountainname'] != "")
    {
      $query = 'select maintdate2,maintdate,fntcomments,changedate,servicedate,fountainid,fountainname,brandid
      ,fountaininfo,rentalid,notused,fountaincatid,fountaindescid from vmt_fountain
      where fountainname=?';
      $query_prm = array($_POST['fountainname']);
      require('inc/doquery.php');
      if ($num_results == 0)
      {
        echo "Fontaine " . $_POST['fountainname'] . " n'existe pas.";
        exit;
      }
    }
    else
    {
      $query = 'select maintdate2,maintdate,fntcomments,changedate,servicedate,fountainid,fountainname,brandid
      ,fountaininfo,rentalid,notused,fountaincatid,fountaindescid from vmt_fountain
      where fountainid="' . $fountainid . '"';
      $query_prm = array();
      require('inc/doquery.php');
      if ($num_results == 0)
      {
        echo "Fontaine n'existe pas.";
        exit;
      }
    }
    $row = $query_result[0];
    $fountainid = $row['fountainid'];
    $rentalid = $row['rentalid']+0; $reference = "";
    ?><h2>Modifier fontaine:</h2>
    <form method="post" action="custom.php">
    <table>
    <tr><td>Numéro:</td>
    <?php
    echo '<td><input type="text" id="myfocus" name="name" value="' . $row['fountainname'] . '" size=20></td></tr>';
    echo '<tr><td>Marque:</td><td><select name="brandid">';
    $query = 'select brandid,brandname from vmt_brand order by brandname';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results == 0) { $error = 1; }
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row2['brandid'] == $row['brandid']) { echo '<option value="' . $row2['brandid'] . '" SELECTED>' . $row2['brandname'] . '</option>'; }
      else { echo '<option value="' . $row2['brandid'] . '">' . $row2['brandname'] . '</option>'; }
    }
    echo '</select></td></tr>';
    if ($rentalid > 0)
    {
      $query = 'select reference from vmt_rental where rentalid="' . $rentalid . '"';
      $query_prm = array();
      require('inc/doquery.php');
      $reference = $query_result[0]['reference'];
    }
    echo '<tr><td>Contrat:</td><td><input type="text" name="reference" value="' . $reference . '" size=20>';
    if ($reference != "")
    {
      $query = 'select vmt_rental.clientid,clientname from vmt_rental,client where vmt_rental.clientid=client.clientid and vmt_rental.reference="' . $reference . '"';
      $query_prm = array();
      require('inc/doquery.php');
      if ($query_result[0]['clientid'] > 0) { echo ' Client ' . $query_result[0]['clientid'] . ': ' . $query_result[0]['clientname']; }
    }
    ?><tr><td>Catégorie:</td>
    <td><select name="fountaincatid"><?php
    
    $query = 'select fountaincatid,fountaincatname from vmt_fountaincat order by fountaincatname';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results == 0) { $error = 1; }
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row['fountaincatid'] == $row2['fountaincatid']) { echo '<option value="' . $row2['fountaincatid'] . '" SELECTED>' . $row2['fountaincatname'] . '</option>'; }
      else { echo '<option value="' . $row2['fountaincatid'] . '">' . $row2['fountaincatname'] . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>Etat:</td>
    <td><select name="fountaindescid"><?php
    
    $query = 'select fountaindescid,fountaindescname from vmt_fountaindesc order by fountaindescname';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results == 0) { $error = 1; }
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row['fountaindescid'] == $row2['fountaindescid']) { echo '<option value="' . $row2['fountaindescid'] . '" SELECTED>' . $row2['fountaindescname'] . '</option>'; }
      else { echo '<option value="' . $row2['fountaindescid'] . '">' . $row2['fountaindescname'] . '</option>'; }
    }
    ?></select></td></tr>

    <tr><td>Date service:</td><td colspan=3><?php
    $day = mb_substr($row['servicedate'],8,2);
    $month = mb_substr($row['servicedate'],5,2);
    $year = mb_substr($row['servicedate'],0,4);
    ?><select name="day"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    
    <tr><td>Date changement état:</td><td colspan=3><?php
    $day = mb_substr($row['changedate'],8,2);
    $month = mb_substr($row['changedate'],5,2);
    $year = mb_substr($row['changedate'],0,4);
    ?><select name="day2"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="month2"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year2"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    
    <tr><td>Date dèrnier entretien:</td><td colspan=3><?php
    $day = mb_substr($row['maintdate'],8,2);
    $month = mb_substr($row['maintdate'],5,2);
    $year = mb_substr($row['maintdate'],0,4);
    ?><select name="mday"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="mmonth"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="myear"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    
    <tr><td>Date prochain entretien:</td><td colspan=3><?php
    $day = mb_substr($row['maintdate2'],8,2);
    $month = mb_substr($row['maintdate2'],5,2);
    $year = mb_substr($row['maintdate2'],0,4);
    ?><select name="mday2"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="mmonth2"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="myear2"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    
    <?php
    echo '<tr><td>Remarques:</td><td><input type="text" name="fntcomments" value="' . $row['fntcomments'] . '" size=80></td></tr>';

    
    if ($row['rentalid'] == 0)
    {
      if ($row['notused'] == 0) { echo '<tr><td>Supprimée:</td><td><input type="checkbox" name="notused" value="1"></td></tr>'; }
      else { echo '<tr><td>Supprimée:</td><td><input type="checkbox" name="notused" value="1" CHECKED></td></tr>'; }
    }
#    echo '<tr><td valign=top>Informations:</td><td><textarea type="textarea" name="fountaininfo" cols=80 rows=20>' . $row['fountaininfo'] . '</textarea></td></tr>';
    ?>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="2">
    <?php echo '<input type=hidden name="fountainid" value="' . $fountainid . '">'; ?>
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    # Save data
    case 2:
    
    $reference = $_POST['reference'];
    if ($reference != "")
    {
      $query = 'select rentalid from vmt_rental where reference="' . $reference . '"';
      $query_prm = array();
      require('inc/doquery.php');
      $rentalid = $query_result[0]['rentalid']+0;
      if ($rentalid == 0) { echo '<p class=alert>Contrat ' . $reference . ' inexistant.</p>'; }
    }
    else { $rentalid = 0; }
    $fountainid = $_POST['fountainid'];
    $fountainname = $_POST['name'];

    $query = 'select fountainid from vmt_fountain where fountainname="' . $fountainname . '" and fountainid<>"' . $fountainid . '"';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results > 0)
    {
      echo '<p>Fontaine ' . $fountainname . ' existe déja.</p>';
      exit;
    }

    $notused = $_POST['notused']; if ($notused == "") $notused = 0;
    $brandid = $_POST['brandid'];
    $fountaininfo = $_POST['fountaininfo'];
    $fountaincatid = $_POST['fountaincatid'];
    $fountaindescid = $_POST['fountaindescid'];
    $maintdate = d_builddate($_POST['mday'],$_POST['mmonth'],$_POST['myear']);
    $maintdate2 = d_builddate($_POST['mday2'],$_POST['mmonth2'],$_POST['myear2']);
    $servicedate = d_builddate($_POST['day'],$_POST['month'],$_POST['year']);
    #$servicedate = correctdate($servicedate);
    $changedate = d_builddate($_POST['day2'],$_POST['month2'],$_POST['year2']);
    #$changedate = correctdate($changedate);
    $fntcomments = $_POST['fntcomments'];
    $query = 'update vmt_fountain set maintdate2="' . $maintdate2 . '",maintdate="' . $maintdate . '",rentalid="' . $rentalid . '",fntcomments="' . $fntcomments . '",changedate="' . $changedate . '",servicedate="' . $servicedate . '",fountainid="' . $fountainid . '",fountainname="' . $fountainname . '",notused="' . $notused . '",brandid="' . $brandid . '",fountaininfo="' . $fountaininfo . '",fountaincatid="' . $fountaincatid . '",fountaindescid="' . $fountaindescid . '" where fountainid="' . $fountainid . '"';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_result)
    {
      echo '<p>Fontaine ' . $fountainname . ' modifié.</p>';
      $query = 'select clientid from vmt_rental where rentalid="' . $rentalid . '"';
      $query_prm = array();
      require('inc/doquery.php');
      $clientid = $query_result[0]['clientid']+0;
      $query = 'insert into vmt_fonhis (fountainid,fonhisdate,fountaindescid,clientid,rentalid,userid) values ("' . $fountainid . '","' . $changedate . '","' . $fountaindescid . '","' . $clientid . '","' . $rentalid . '","' . $_SESSION['ds_userid'] . '")';
      $query_prm = array();
      require('inc/doquery.php');
    }
    break;

  }
  break;
  
  # add edit installation
  case 'inst':
  switch($_SESSION['ds_step'])
  {

    case 0:
    ?><h2>Installation:</h2>
    <form method="post" action="custom.php">
    <table>
    <tr><td>Numéro:</td><td><input autofocus type="text" STYLE="text-align:right" name="instid" size=25> (vide pour ajouter)</td></tr>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="1">
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    # Read/enter/save data
    case 1:
    
    $instid = $_POST['instid']+0;
    if ($_POST['saveme'])
    {
      # check vars and save
      $ok = 1;
      $fountainname = $_POST['fountainname'];
        $query = 'select fountainid from vmt_fountain where fountainname="'.$fountainname.'"';
        $result = mysql_query($query, $db_conn); querycheck($result);
        $row = mysql_fetch_array($result);
        $fountainid = $row['fountainid']+0;
        if ($fountainid == 0) { $ok = 0; echo 'fountaine inexistant<br>'; }
      $clientid = $_POST['clientid']+0;
        $query = 'select clientid from client where clientid="'.$clientid.'"';
        $result = mysql_query($query, $db_conn); querycheck($result);
        $row = mysql_fetch_array($result);
        $clientid = $row['clientid'];
        if ($clientid == 0) { $ok = 0; echo 'client inexistant<br>'; }
      $reference = $_POST['reference'];
        $query = 'select reference from vmt_rental where reference="'.$reference.'"';
        $result = mysql_query($query, $db_conn); querycheck($result);
        $row = mysql_fetch_array($result);
        $reference = $row['reference'];
        if ($reference == "") { $ok = 0; echo 'reference inexistant<br>'; }
      $employeeid = $_POST['employeeid']+0;
      $datename = 'instdate';
      require('inc/datepickerresult.php');
      if ($ok)
      {
        if ($instid > 0)
        {
          # modify
          $query = 'update vmt_inst set fountainid="'.$fountainid.'",clientid="'.$clientid.'",rental_reference="'.$reference.'",instdate="'.$instdate.'",employeeid="'.$employeeid.'" where instid="'.$instid.'"';
          $result = mysql_query($query, $db_conn); querycheck($result);
          echo 'Installation '.$instid.' modifié.<br>';
        }
        else
        {
          # save
          $query = 'insert into vmt_inst (fountainid,clientid,rental_reference,instdate,employeeid) values ("' . $fountainid . '","' . $clientid . '","' . $reference . '","' . $instdate . '","' . $employeeid . '")';
          $result = mysql_query($query, $db_conn); querycheck($result);
          echo 'Installation '.mysql_insert_id().' ajouté.<br>';
        }
      }
    }
    if ($instid > 0)
    {
      $query = 'select * from vmt_inst where instid='.$instid;
      $result = mysql_query($query, $db_conn); querycheck($result);
      $num_results = mysql_num_rows($result);
      if ($num_results)
      {
        $row = mysql_fetch_array($result);
        $fountainid = $row['fountainid'];
        $clientid = $row['clientid'];
        $reference = $row['rental_reference'];
        $instdate = $row['instdate'];
        $employeeid = $row['employeeid'];
        
        $query2 = 'select fountainname from vmt_fountain where fountainid="'.$fountainid.'"';
        $result2 = mysql_query($query2, $db_conn); querycheck($result2);
        $row2 = mysql_fetch_array($result2);
        $fountainname = $row2['fountainname'];
      }
      else { $instid = 0; }
    }
    if ($instid > 0) { echo '<h2>Modifier installation '.$instid.'</h2>'; }
    else { echo '<h2>Ajouter installation</h2>'; }
    echo '<form method="post" action="custom.php"><table>';
    echo '<tr><td>Fontaine:</td><td><input autofocus type="text" STYLE="text-align:right" name="fountainname" value="'.$fountainname.'" size=25></td></tr>';
    echo '<tr><td>Numéro client:</td><td><input type="text" STYLE="text-align:right" name="clientid" value="'.$clientid.'" size=25></td></tr>';
    echo '<tr><td>Contrat:</td><td><input type="text" STYLE="text-align:right" name="reference" value="'.$reference.'" size=25></td></tr>';
    echo '<tr><td>Commercial:</td><td><select name="employeeid">';
    $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee order by employeename';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = mysql_fetch_array($result);
      echo '<option value="' . $row2['employeeid'] . '"';
      if ($row2['employeeid'] == $employeeid) { echo ' selected'; }
      echo '>' . $row2['employeename'] . '</option>';
    }
    echo '</select></td></tr>';
    echo '<tr><td>Date:</td><td>';
    $datename = 'instdate';
    require('inc/datepicker.php');
    echo '</td></tr>';
    echo '<tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="saveme" value="1">';
    echo '<input type=hidden name="instid" value="'.$instid.'">';
    echo '<input type="submit" value="Valider"></td></tr>';
    echo '</table></form>';
    break;
    
  }
  break;

  ### Edit fontain ###
  case 'modfoncat':
  switch($_SESSION['ds_step'])
  {

    case 0:
    ?><h2>Modifier catégorie fontaine:</h2>
    <form method="post" action="custom.php">
    <table>
    <tr><td>Nom:</td>
    <td><select name="fountaincatid"><?php
    
    $query = 'select fountaincatid,fountaincatname from vmt_fountaincat order by fountaincatname';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = mysql_fetch_array($result);
      echo '<option value="' . $row2['fountaincatid'] . '">' . $row2['fountaincatname'] . '</option>';
    }
    ?></select></td></tr>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="1">
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    # Read/enter data
    case 1:
    $fountaincatid = $_POST['fountaincatid'];
    $query = 'select fountaincatname from vmt_fountaincat where fountaincatid="' . $fountaincatid . '"';
    
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    if (mysql_num_rows($result) == 0)
    {
      echo "Catégorie n'existe pas.";
      break;
    }
    $row = mysql_fetch_array($result);
    ?><h2>Modifier catégorie fontaine:</h2>
    <form method="post" action="custom.php">
    <table>
    <tr><td>Nom:</td>
    <?php
    echo '<td><input type="text" name="name" value="' . $row['fountaincatname'] . '" size=20></td></tr>';
    ?>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="2">
    <?php echo '<input type=hidden name="fountaincatid" value="' . $fountaincatid . '">'; ?>
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    # Save data
    case 2:
    $fountaincatid = $_POST['fountaincatid'];
    $fountaincatname = $_POST['name'];
    $query = 'update vmt_fountaincat set fountaincatname="' . $fountaincatname . '" where fountaincatid="' . $fountaincatid . '"';
    
    $result = mysql_query($query, $db_conn); querycheck($result);
    if ($result) echo '<p>Catégorie fontaine ' . $fountaincatname . ' modifié.</p>';
    break;

  }
  break;

  ### Edit fontain ###
  case 'modfondesc':
  switch($_SESSION['ds_step'])
  {

    case 0:
    ?><h2>Modifier etat fontaine:</h2>
    <form method="post" action="custom.php">
    <table>
    <tr><td>Nom:</td>
    <td><select name="fountaindescid"><?php
    
    $query = 'select fountaindescid,fountaindescname from vmt_fountaindesc order by fountaindescname';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = mysql_fetch_array($result);
      echo '<option value="' . $row2['fountaindescid'] . '">' . $row2['fountaindescname'] . '</option>';
    }
    ?></select></td></tr>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="1">
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    # Read/enter data
    case 1:
    $fountaindescid = $_POST['fountaindescid'];
    $query = 'select fountaindescname from vmt_fountaindesc where fountaindescid="' . $fountaindescid . '"';
    
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    if (mysql_num_rows($result) == 0)
    {
      echo "Etat n'existe pas.";
      break;
    }
    $row = mysql_fetch_array($result);
    ?><h2>Modifier etat fontaine:</h2>
    <form method="post" action="custom.php">
    <table>
    <tr><td>Nom:</td>
    <?php
    echo '<td><input type="text" name="name" value="' . $row['fountaindescname'] . '" size=20></td></tr>';
    ?>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="2">
    <?php echo '<input type=hidden name="fountaindescid" value="' . $fountaindescid . '">'; ?>
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    # Save data
    case 2:
    $fountaindescid = $_POST['fountaindescid'];
    $fountaindescname = $_POST['name'];
    $query = 'update vmt_fountaindesc set fountaindescname="' . $fountaindescname . '" where fountaindescid="' . $fountaindescid . '"';
    
    $result = mysql_query($query, $db_conn); querycheck($result);
    if ($result) echo '<p>Etat fontaine ' . $fountaindescname . ' modifié.</p>';
    break;

  }
  break;


  ### Edit brand ###
  case 'modbrand':
  switch($_SESSION['ds_step'])
  {

    case 0:
    ?><h2>Modifier marque fontaine:</h2>
    <form method="post" action="custom.php">
    <table>
    <tr><td>Nom:</td>
    <td><select name="brandid"><?php
    
    $query = 'select brandid,brandname from vmt_brand order by brandname';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = mysql_fetch_array($result);
      echo '<option value="' . $row2['brandid'] . '">' . $row2['brandname'] . '</option>';
    }
    ?></select></td></tr>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="1">
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    # Read/enter data
    case 1:
    $brandid = $_POST['brandid'];
    $query = 'select brandname,supplierid from vmt_brand where brandid="' . $brandid . '"';
    
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    if (mysql_num_rows($result) == 0)
    {
      echo "Marque n'existe pas.";
      break;
    }
    $row = mysql_fetch_array($result);
    ?><h2>Modifier marque fontaine:</h2>
    <form method="post" action="custom.php">
    <table>
    <tr><td>Nom:</td>
    <?php
    echo '<td><input type="text" name="name" value="' . $row['brandname'] . '" size=20></td></tr>';
    ?>
    <tr><td>Fournisseur:</td>
    <td><select name="supplierid"><?php
    $query = 'select supplierid,suppliername from supplier order by suppliername';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    if ($num_results == 0) { $error = 1; }
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = mysql_fetch_array($result);
      if ($row['supplierid'] == $row2['supplierid']) { echo '<option value="' . $row2['supplierid'] . '" SELECTED>' . $row2['supplierid'] . ': ' . $row2['suppliername'] . '</option>'; }
      else { echo '<option value="' . $row2['supplierid'] . '">' . $row2['supplierid'] . ': ' . $row2['suppliername'] . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="2">
    <?php echo '<input type=hidden name="brandid" value="' . $brandid . '">'; ?>
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    # Save data
    case 2:
    $brandid = $_POST['brandid'];
    $brandname = $_POST['name'];
    $query = 'update vmt_brand set brandname="' . $brandname . '",supplierid="' . $_POST['supplierid'] . '" where brandid="' . $brandid . '"';
    
    $result = mysql_query($query, $db_conn); querycheck($result);
    if ($result) echo '<p>Marque fontaine ' . $brandname . ' modifié.</p>';
    break;

  }
  break;


  ### new rental ###
  case 'newloc':
  switch($_SESSION['ds_step'])
  {
    # Read
    case '0':
    $error = 0;
    $frigo = (int) $_POST['frigo'];
   
    if ($_POST['save'] == 1)
    {
      $ok = 1;
      $client = $_POST['client'];
      require('inc/findclient.php');
      if ($num_clients > 1) { $ok = 0; }
      $contractdate = d_builddate($_POST['contractday'],$_POST['contractmonth'],$_POST['contractyear']);
      $rentaldate = d_builddate(1,$_POST['month'],$_POST['year']);

      $query = 'select rentalid,clientid from vmt_rental where reference=?';
      $query_prm = array($_POST['reference']);
      require('inc/doquery.php');
      if ($num_results > 0)
      {
        echo '<p class="alert">Numéro contract existant (client numéro ' . $query_result[0]['clientid'] . ').</p>'; $ok = 0;
      }
      
      if ($_POST['addinstall'])
      {
        $fountainname = $_POST['fountainname'];
        $query = 'select fountainid from vmt_fountain where fountainname=?';
        $query_prm = array($fountainname);
        require('inc/doquery.php');
        $fountainid = $query_result[0]['fountainid']+0;
        if ($fountainid == 0) { $ok = 0; echo '<p>fontaine inexistant</p>'; }
      }
      
      if ($ok)
      {
        $noprelev = $_POST['noprelev']+0;
        $query = 'insert into vmt_rental (noprelev,reference,clientid,months,rentalprice,rentaldate,contractdate,deleted,frigo) values ("' . $noprelev . '","' . $_POST['reference'] . '","' . $clientid . '","' . $_POST['months'] . '","' . ($_POST['rentalprice']+0) . '","' . $rentaldate . '","' . $contractdate . '",0,?)';
        $query_prm = array($frigo);
        require('inc/doquery.php');
        $rentalid = $query_insert_id;
        if ($result)
        {
          echo '<p>Location ' . $_POST['reference'] . ' ajouté.</p>';
        }
        if ($_POST['addinstall'])
        {
          $employeeid = $_POST['employeeid']+0;
          $datename = 'instdate';
          require('inc/datepickerresult.php');
          # save
          $query = 'insert into vmt_inst (fountainid,clientid,rental_reference,instdate,employeeid) values ("' . $fountainid . '","' . $clientid . '","' . $_POST['reference'] . '","' . $instdate . '","' . $employeeid . '")';
          $query_prm = array();
          require('inc/doquery.php');
          echo 'Installation ajouté.<br>';
          if ($rentalid > 0)
          {
            $query = 'update vmt_fountain set rentalid="' . $rentalid . '" where fountainid="' . $fountainid . '" LIMIT 1';
            $query_prm = array();
            require('inc/doquery.php');
          }
        }
      }
    }
    
    
?>
    <h2>Nouvelle location:</h2>
    <form method="post" action="custom.php"><table><?php
    echo '<tr><td>';
    require("inc/selectclient.php");
    echo '</td></tr>';
    echo '<tr><td>N<span class=sup>o</span> Contrat:</td><td><input type="text" id="myfocus" STYLE="text-align:right" name="reference" value="' . $_POST['reference'] . '" size=20></td></tr>';

    echo '<tr><td>Date contrat:</td><td>';
    if (isset($_POST['contractday']))
    {
      $day = $_POST['contractday']+0;
      $month = $_POST['contractmonth']+0;
      $year = $_POST['contractyear']+0;
    }
    else
    {
      $day = mb_substr($_SESSION['ds_curdate'],8,2);
      $month = mb_substr($_SESSION['ds_curdate'],5,2);
      $year = mb_substr($_SESSION['ds_curdate'],0,4);
    }
    ?><select name="contractday"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="contractmonth"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="contractyear"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    echo '</select></td></tr>';

    $rentalprice = $_POST['rentalprice']+0; if ($rentalprice == 0) { $rentalprice = ''; }
    echo '<tr><td>Prix mensuel TTC:</td><td><input type="text" STYLE="text-align:right" name="rentalprice" value="' . $rentalprice . '" size=20></td></tr>';

    if (isset($_POST['month']))
    {
      $month = $_POST['month']+0;
      $year = $_POST['year']+0;
    }
    else
    {
      $month = mb_substr($_SESSION['ds_curdate'],5,2);
      $year = mb_substr($_SESSION['ds_curdate'],0,4);
    }

    ?><tr><td>Debut:</td><td><select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>Periodicité:</td><td><select name="months"><?php
    echo '<option value=1'; if ($_POST['months'] == 1) { echo ' selected'; }
    echo '>1</option><option value=3'; if ($_POST['months'] == 3) { echo ' selected'; }
    echo '>3</option><option value=6'; if ($_POST['months'] == 6) { echo ' selected'; }
    echo '>6</option><option value=12'; if ($_POST['months'] == 12) { echo ' selected'; }
    echo '>12</option>';
    echo '</select> mois &nbsp; Pas de prélèvement: <input type=checkbox name="noprelev" value="1"';
    if ($_POST['noprelev'] == 1) { echo ' checked'; }
    echo '></td></tr>';
    echo '<tr><td colspan=2>&nbsp;</td></tr>';
    echo '<tr><td colspan=2>Ajouter installation: <input type=checkbox name="addinstall" value="1"';
    if ($_POST['addinstall'] == 1 || !isset($_POST['save'])) { echo ' checked'; }
    echo '></td></tr>';
    echo '<tr><td>N<span class=sup>o</span> Fontaine:</td><td><input type="text" id="myfocus" STYLE="text-align:right" name="fountainname" value="' . $_POST['fountainname'] . '" size=20></td></tr>';
    ?>
    <tr><td>Commercial:</td>
    <td><select name="employeeid"><?php
    $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee order by employeename';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row2['employeeid'] == $_POST['employeeid']) { echo '<option value="' . $row2['employeeid'] . '" selected>' . $row2['employeeid'] . ': ' . $row2['employeename'] . '</option>'; }
      else { echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeeid'] . ': ' . $row2['employeename'] . '</option>'; }
    }
    ?></select></td></tr><?php
    echo '<tr><td>Date installation:</td><td>';
    $datename = 'instdate';
    require('inc/datepicker.php');
    echo '</td></tr>';
    echo '<tr><td colspan=2>Frigo: <input type=checkbox name="frigo" value="1"';
    if ($_POST['frigo'] == 1) { echo ' checked'; }
    echo '></td></tr>';
    ?>
	  <tr><td colspan="2" align="center"><input type=hidden name="step" value="0"><input type=hidden name="save" value="1"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

  }
  break;


  ### List location ###
  case 'listloc':

  $error = 0;
  $day = mb_substr($_SESSION['ds_curdate'],8,2);
  $month = mb_substr($_SESSION['ds_curdate'],5,2);
  $year = mb_substr($_SESSION['ds_curdate'],0,4);
  ?>
  <h2>Rapport location:</h2>
  <form method="post" action="customreportwindow.php" target=_blank><table>
  <tr><td><input type=radio name=filter value=1 checked></td><td>Tous &nbsp; <input type=checkbox name="byclientname" value="1"> Ranger par client</td></tr>
  <tr><td><input type=radio name=filter value=2></td><td>"Dans le mois" 5 ans</td></tr>
  <tr><td><input type=radio name=filter value=3></td><td>Resiliés
  
  <select name="startday"><?php
  for ($i=1; $i <= 31; $i++)
  { 
    if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="startmonth"><?php
  for ($i=1; $i <= 12; $i++)
  {
    if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="startyear"><?php
  for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
  {
    if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select> au
  <select name="stopday"><?php
  for ($i=1; $i <= 31; $i++)
  { 
    if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="stopmonth"><?php
  for ($i=1; $i <= 12; $i++)
  {
    if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="stopyear"><?php
  for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
  {
    if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select>
  
  
  </td></tr>
  <tr><td><input type=radio name=filter value=4></td><td>Factures le mois:
  <select name="month"><?php
  for ($i=1; $i <= 12; $i++)
  {
    if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="year"><?php
  for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
  {
    if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select>
  </td></tr>
  <tr><td></td>
  <td>Employee <?php echo $_SESSION['ds_term_clientemployee2']; ?>: <select name="employeeid"><option value="0"> </option><?php
  $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee where iscashier=1 order by employeename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row2 = $query_result[$i];
    echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeename'] . '</option>';
  }
  ?></select></td></tr>
  
  <tr><td><input type=radio name=filter value=6></td><td>Total nouveaux contrats:
  <?php
  $datename = 'starttotal';
  require('inc/datepicker.php');
  echo ' au ';
  $datename = 'stoptotal';
  require('inc/datepicker.php');
  ?></td></tr>
  
  <tr><td>&nbsp;</td></tr>
  <?php
  require('preload/clientcategory.php');
  if (isset($clientcategoryA))
  {
    echo '<tr><td>&nbsp;</td><td>Catégorie client: <select name="clientcategoryid"><option value="0"></option>';
    foreach ($clientcategoryA as $clientcategoryidS => $clientcategoryname)
    {
      echo '<option value="' . $clientcategoryidS . '">' . $clientcategoryname . '</option>';
    }
    echo '</td></tr>';
  }
  ?>
  <tr><td><input type=checkbox name="onlyprelev" value="1"></td><td>Que prélevement</td></tr>
  <?php
  #<tr><td><input type=checkbox name="onlynonmatched" value="1"></td><td>Non-lettrés</td></tr>
  # locations do not have a match field
  ?>
  <tr><td colspan=2>&nbsp;</td></tr>
  <tr><td><input type=radio name=filter value=5></td><td>Prélèvements refoulés (et non lettrés)
      <select name="startday2"><?php
  for ($i=1; $i <= 31; $i++)
  { 
    if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="startmonth2"><?php
  for ($i=1; $i <= 12; $i++)
  {
    if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="startyear2"><?php
  for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
  {
    if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select> au
  <select name="stopday2"><?php
  for ($i=1; $i <= 31; $i++)
  { 
    if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="stopmonth2"><?php
  for ($i=1; $i <= 12; $i++)
  {
    if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="stopyear2"><?php
  for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
  {
    if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select>
  </td></tr>
  <tr><td colspan=2><select name="frigo"><option value=0>Frigo exclu</option><option value=-1>Tous</option><option value=1>Uniquement frigo</option></select>
  <tr><td colspan="2" align="center"><input type=hidden name="report" value="listloc"><input type="submit" value="Valider"></td></tr>
  </table></form><?php

  break;


  ### feuille route ###
  case 'addfr':
  switch($_SESSION['ds_step'])
  {
    # Read
    case '0':
    $error = 0;
    

    ?>
    <h2>Nouveau planning livraison:</h2>
    <form method="post" action="custom.php"><table>
    <tr><td>Commentaire:</td><td><input type="text" id="myfocus" STYLE="text-align:right" name="reference" size=50></td></tr>
    <tr><td>Client:</td><td><input type="text" id="myfocus" STYLE="text-align:right" name="clientid" size=5> : <input type="text" id="clientlist" STYLE="text-align:right" name="clientname" size=20></td></tr>
    <tr><td>Type:</td>
    <td><select name="frtype">
    <option value="0">Bonbonnes</option><option value="1">Pack</option><option value="2">Autre</option>
    </select></td></tr>
    <tr><td>Quantité:</td><td><input type="text" STYLE="text-align:right" name="quantity" size=10></td></tr>
    <tr><td>Quantité 1.5l:</td><td><input type="text" STYLE="text-align:right" name="quantity4" size=10></td></tr>
    <tr><td>Quantité 1.0l:</td><td><input type="text" STYLE="text-align:right" name="quantity2" size=10></td></tr>
    <tr><td>Quantité 0.5l:</td><td><input type="text" STYLE="text-align:right" name="quantity3" size=10></td></tr>
    <tr><td>Periodicité:</td><td><input type=radio name="periodic" value="1" CHECKED>
    <select name="weekday"><option value=1>Lundi</option><option value=2>Mardi</option><option value=3>Mercredi</option><option value=4>Jeudi</option><option value=5>Vendredi</option></select>
    <select name="daytype"><option value=1>Tous</option><option value=2>Semaine Pair</option><option value=3>Semaine Impair</option><option value=4>Premier du mois</option><option value=6>Mensuel (1)</option><option value=7>Mensuel (2)</option><option value=8>Mensuel (3)</option><option value=9>Mensuel (4)</option></select>
    </td></tr>
    <tr><td>&nbsp;</td><td><input type=radio name="periodic" value="0"> <?php
    $day = mb_substr($_SESSION['ds_curdate'],8,2);
    $month = mb_substr($_SESSION['ds_curdate'],5,2);
    $year = mb_substr($_SESSION['ds_curdate'],0,4);
    ?><select name="day"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr><?php
    echo '<tr><td colspan=2>&nbsp;</td></tr><tr><td>Ne pas livrer jusq\'au:</td><td>';
    $day = 1;
    $month = 1;
    $year = mb_substr($_SESSION['ds_startyear'],0,4);
    ?><select name="vday"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="vmonth"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="vyear"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="eaid" value="-1"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    # Save
    case '1': # TODO make sure client exists
    $clientid = $_POST['clientid'];
    $clientname = $_POST['clientname'];
    if ($clientid == "" && $clientname != "")
    {
      $query = 'select clientid from client where clientname="' . $clientname . '"';
      $query_prm = array();
      require('inc/doquery.php');
      if ($num_results == 0) { echo '<font color="' . $_SESSION['ds_alertcolor'] . '">Client inexistant.</font>'; exit; }
      $clientid = $query_result[0]['clientid'];
    }
    echo '<h2>Nouveau planning livraison:</h2><form method="post" action="custom.php"><table>';
    $query = 'select extraaddressid,address,postaladdress from extraaddress where clientid="' . $_POST['clientid'] . '" and deleted<>1';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results > 0 && $_POST['eaid'] == -1)
    {
      echo '<tr><td>Adresse alternative?</td><td><select name="extraaddressid"><option value="0">Non</option>';
      for ($iy=0; $iy < $num_results; $iy++)
      {
        $row49 = $query_result[$iy];
        if ($row49['extraaddressid'] == $extraaddressid) { echo '<option value="' . $row49['extraaddressid'] . '" SELECTED>' . $row49['address'] . ' ' . $row49['postaladdress'] . '</option>'; }
        else { echo '<option value="' . $row49['extraaddressid'] . '">' . $row49['address'] . ' ' . $row49['postaladdress'] . '</option>'; }
      }
      echo '</select></td></tr>';
      echo '<tr><td colspan="2" align="center"><input type=hidden name="step" value="1">';
      echo '<input type=hidden name="reference" value="' . $_POST['reference'] . '">';
      echo '<input type=hidden name="clientid" value="' . $clientid . '">';
      echo '<input type=hidden name="frtype" value="' . $_POST['frtype'] . '">';
      echo '<input type=hidden name="quantity" value="' . $_POST['quantity'] . '">';
      echo '<input type=hidden name="weekday" value="' . $_POST['weekday'] . '">';
      echo '<input type=hidden name="daytype" value="' . $_POST['daytype'] . '">';
      echo '<input type=hidden name="periodic" value="' . $_POST['periodic'] . '">';
      echo '<input type=hidden name="day" value="' . $_POST['day'] . '">';
      echo '<input type=hidden name="month" value="' . $_POST['month'] . '">';
      echo '<input type=hidden name="year" value="' . $_POST['year'] . '">';
      echo '<input type=hidden name="vday" value="' . $_POST['vday'] . '">';
      echo '<input type=hidden name="vmonth" value="' . $_POST['vmonth'] . '">';
      echo '<input type=hidden name="vyear" value="' . $_POST['vyear'] . '">';
      echo '<input type="submit" value="Valider"></td></tr></table></form>';
    }
    else
    {
      $eaid = $_POST['extraaddressid']+0; if ($eaid < 0) { $eaid = 0; }
      $deliverydate = d_builddate($_POST['day'],$_POST['month'],$_POST['year']);
      $vacationdate = d_builddate($_POST['vday'],$_POST['vmonth'],$_POST['vyear']);
      $query = 'insert into vmt_delivery (reference,clientid,frtype,quantity,quantity2,quantity3,quantity4,day,daytype
      ,periodic,deliverydate,extraaddressid,vacationdate) values 
      (?,?,?,?,?,?,?,?,?,?,?,?,?)';
      $query_prm = array($_POST['reference'],$clientid,$_POST['frtype'],($_POST['quantity']+0),($_POST['quantity2']+0),
      ($_POST['quantity3']+0),($_POST['quantity4']+0),$_POST['weekday'],$_POST['daytype'],$_POST['periodic'],$deliverydate,
      $eaid,$vacationdate);
      require('inc/doquery.php');
      if ($query_insert_id > 0)
      {
        echo '<p>Livraison ajouté.</p>';
      }
    }
    break;

  }
  break;


  ### del route ###
  case 'modfr':
  switch($_SESSION['ds_step'])
  {
    # Read
    case '0':
    $error = 0;
    ?>
    <h2>Supprimer planning livraison:</h2>
    <form method="post" action="custom.php"><table>
    <tr><td>Client:</td><td><input type="text" id="myfocus" STYLE="text-align:right" name="clientid" size=5> : <input type="text" id="clientlist" STYLE="text-align:right" name="clientname" size=20></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    case '1':
    $clientid = $_POST['clientid'];
    $clientname = $_POST['clientname'];
    if ($clientid == "" && $clientname != "")
    {
      $query = 'select clientid from client where clientname="' . $clientname . '"';
      $query_prm = array();
      require('inc/doquery.php');
      if ($num_results == 0) { echo '<font color="' . $_SESSION['ds_alertcolor'] . '">Client inexistant.</font>'; exit; }
      $clientid = $query_result[0]['clientid'];
    }
    $query = 'select rentalid,reference,vmt_delivery.clientid,clientname,frtype,quantity,day,daytype,periodic,deliverydate from vmt_delivery,client where vmt_delivery.clientid=client.clientid';
    $query = $query . ' and vmt_delivery.clientid="' . $clientid . '" order by rentalid';
    $query_prm = array();
    require('inc/doquery.php');
    echo '<h2>Supprimer Planning Livraison</h2><form method="post" action="custom.php">';
    echo '<table class=report><tr><td>&nbsp;</td><td><b>Client</b></td><td><b>Type</b></td><td><b>Quantité</b></td><td><b>Periodicité</b></td><td><b>Info</b></td></tr>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row = $query_result[$i];
      $type = '';
      if ($row['type'] == 0) { $type = 'Bonbonnes'; }
      if ($row['type'] == 1) { $type = 'Pack'; }
      $when = '';
      if ($row['periodic'] == 0) { $when = datefix2($row['deliverydate']); }
      if ($row['periodic'] == 1)
      {
        if ($row['day'] == 1) { $when = 'Lundi'; }
        if ($row['day'] == 2) { $when = 'Mardi'; }
        if ($row['day'] == 3) { $when = 'Mercredi'; }
        if ($row['day'] == 4) { $when = 'Jeudi'; }
        if ($row['day'] == 5) { $when = 'Vendredi'; }
        if ($row['daytype'] == 1) { $when = $when . ' Tous'; }
        if ($row['daytype'] == 2) { $when = $when . ' Semaine Pair'; }
        if ($row['daytype'] == 3) { $when = $when . ' Semaine Impair'; }
        if ($row['daytype'] == 4) { $when = $when . ' Premier du mois'; }
      }
      echo '<tr><td><input type=radio name=rentalid value="' . $row['rentalid'] . '">' . $row['rentalid'] . '</td><td>' . $row['clientid'] . ': ' . $row['clientname'] . '</td><td>' . $type . '</td><td align=right>' . $row['quantity'] . '</td><td>' . $when . '</td><td>' . $row['reference'] . '</td></tr>';
    }
    echo '<tr><td colspan="2" align="center"><input type=hidden name="step" value="2"><input type="submit" value="Valider"></td></tr>';
    echo '</table></form>';
    break;

    case '2':
    $rentalid = $_POST['rentalid'];
    if ($rentalid < 1) { echo '<font color="' . $_SESSION['ds_alertcolor'] . '">Veuiller selectionner un planning à supprimer.</font>'; }
    else {
      $query = 'delete from vmt_delivery where rentalid="' . $rentalid . '"';
      $query_prm = array();
      require('inc/doquery.php');
      echo 'Planning livraison supprimé.';
    }
    break;

  }
  break;

  ### mod route ###
  case 'mod2fr':
  switch($_SESSION['ds_step'])
  {
    case '0':
    ?>
    <h2>Modifier planning livraison:</h2>
    <form method="post" action="custom.php"><table>
    <tr><td>Client:</td><td><input autofocus type="text" id="myfocus" STYLE="text-align:right" name="clientid" size=5> : <input type="text" id="clientlist" STYLE="text-align:right" name="clientname" size=20></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    case '1': # TODO make sure client exists
    $clientid = $_POST['clientid'];
    $clientname = $_POST['clientname'];
    if ($clientid == "" && $clientname != "")
    {
      $query = 'select clientid from client where clientname="' . $clientname . '"';
      $query_prm = array();
      require('inc/doquery.php');
      if ($num_results == 0) { echo '<font color="' . $_SESSION['ds_alertcolor'] . '">Client inexistant.</font>'; exit; }
      $clientid = $query_result[0]['clientid'];
    }
    $query = 'select rentalid,reference,vmt_delivery.clientid,clientname,frtype,quantity,day,daytype,periodic,deliverydate from vmt_delivery,client where vmt_delivery.clientid=client.clientid';
    $query = $query . ' and vmt_delivery.clientid="' . $clientid . '" order by periodic desc,rentalid';
    $query_prm = array();
    require('inc/doquery.php');
    echo '<h2>Modifier Planning Livraison</h2><form method="post" action="custom.php">';
    echo '<table class=report><tr><td>&nbsp;</td><td><b>Client</b></td><td><b>Type</b></td><td><b>Quantité</b></td><td><b>Periodicité</b></td><td><b>Info</b></td></tr>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row = $query_result[$i];
      $type = '';
      if ($row['type'] == 0) { $type = 'Bonbonnes'; }
      if ($row['type'] == 1) { $type = 'Pack'; }
      $when = '';
      if ($row['periodic'] == 0) { $when = datefix2($row['deliverydate']); }
      if ($row['periodic'] == 1)
      {
        if ($row['day'] == 1) { $when = 'Lundi'; }
        if ($row['day'] == 2) { $when = 'Mardi'; }
        if ($row['day'] == 3) { $when = 'Mercredi'; }
        if ($row['day'] == 4) { $when = 'Jeudi'; }
        if ($row['day'] == 5) { $when = 'Vendredi'; }
        if ($row['daytype'] == 1) { $when = $when . ' Tous'; }
        if ($row['daytype'] == 2) { $when = $when . ' Semaine Pair'; }
        if ($row['daytype'] == 3) { $when = $when . ' Semaine Impair'; }
        if ($row['daytype'] == 4) { $when = $when . ' Premier du mois'; }
        if ($row['daytype'] == 6) { $when = $when . ' Mensuel (1)'; }
        if ($row['daytype'] == 7) { $when = $when . ' Mensuel (2)'; }
        if ($row['daytype'] == 8) { $when = $when . ' Mensuel (3)'; }
        if ($row['daytype'] == 9) { $when = $when . ' Mensuel (4)'; }
      }
      $ok = 1;
      if ($row['periodic'] == 0 && $row['deliverydate'] <= $_SESSION['ds_curdate']) { $ok = 0; }
      if ($ok)
      {
        echo '<tr><td><input type=radio name=rentalid value="' . $row['rentalid'] . '"';
        if ($i==0) { echo ' checked'; }
        echo '></td><td>' . $row['clientid'] . ': ' . d_decode($row['clientname']) . '</td><td>' . $type . '</td><td align=right>' . $row['quantity'] . '</td><td>' . $when . '</td><td>' . $row['reference'] . '</td></tr>';
      }
    }
    echo '<tr><td colspan="2" align="center"><input type=hidden name="step" value="2"><input type="submit" value="Valider"></td></tr>';
    echo '</table></form>';
    break;

    case '2':
    $rentalid = $_POST['rentalid'];
    if ($rentalid < 1) { echo '<font color="' . $_SESSION['ds_alertcolor'] . '">Veuiller selectionner un planning à modifier.</font>'; }
    else
    {
      # mod
      $query = 'select * from vmt_delivery where rentalid="' . $rentalid . '"';
      $query_prm = array();
      require('inc/doquery.php');
      $row = $query_result[0];
      ?>
      <h2>Modifier planning livraison:</h2>
      <form method="post" action="custom.php"><table>
      <?php
      echo '<tr><td>Commentaire:</td><td><input type="text" id="myfocus" STYLE="text-align:right" name="reference" value="' . $row['reference'] . '" size=50></td></tr>';
      echo '<tr><td>Client:</td><td><input type="text" id="myfocus" STYLE="text-align:right" name="clientid" value="' . $row['clientid'] . '" size=5> : <input type="text" id="clientlist" STYLE="text-align:right" name="clientname" size=20></td></tr>';
      $query = 'select extraaddressid,address,postaladdress from extraaddress where clientid="' . $row['clientid'] . '" and deleted<>1';
      $query_prm = array();
      require('inc/doquery.php');
      if ($num_results > 0)
      {
        echo '<tr><td>Adresse alternative:</td><td><select name="extraaddressid"><option value="0">Non</option>';
        for ($iy=0; $iy < $num_results; $iy++)
        {
          $row49 = $query_result[$iy];
          if ($row49['extraaddressid'] == $row['extraaddressid']) { echo '<option value="' . $row49['extraaddressid'] . '" SELECTED>' . $row49['address'] . ' ' . $row49['postaladdress'] . '</option>'; }
          else { echo '<option value="' . $row49['extraaddressid'] . '">' . $row49['address'] . ' ' . $row49['postaladdress'] . '</option>'; }
        }
        echo '</select></td></tr>';
      }
      echo '<tr><td>Type:</td>';
      echo '<td><select name="frtype">';
      echo '<option value="0"'; if ($row['frtype'] == 0) { echo ' selected'; }
      echo '>Bonbonnes</option><option value="1"'; if ($row['frtype'] == 1) { echo ' selected'; }
      echo '>Pack</option><option value="2"'; if ($row['frtype'] == 2) { echo ' selected'; }
      echo '>Autre</option>';
      echo '</select></td></tr>';
      echo '<tr><td>Quantité:</td><td><input type="text" STYLE="text-align:right" name="quantity" value="' . $row['quantity'] . '" size=10></td></tr>';
      echo '<tr><td>Quantité 1.5l:</td><td><input type="text" STYLE="text-align:right" name="quantity4" value="' . $row['quantity4'] . '" size=10></td></tr>';
      echo '<tr><td>Quantité 1.0l:</td><td><input type="text" STYLE="text-align:right" name="quantity2" value="' . $row['quantity2'] . '" size=10></td></tr>';
      echo '<tr><td>Quantité 0.5l:</td><td><input type="text" STYLE="text-align:right" name="quantity3" value="' . $row['quantity3'] . '" size=10></td></tr>';
      echo '<tr><td>Periodicité:</td><td><input type=radio name="periodic" value="1"'; if ($row['periodic'] == 1) { echo ' checked'; }
      echo '>';
      echo '<select name="weekday"><option value=1'; if ($row['day'] == 1) { echo ' selected'; }
      echo '>Lundi</option><option value=2'; if ($row['day'] == 2) { echo ' selected'; }
      echo '>Mardi</option><option value=3'; if ($row['day'] == 3) { echo ' selected'; }
      echo '>Mercredi</option><option value=4'; if ($row['day'] == 4) { echo ' selected'; }
      echo '>Jeudi</option><option value=5'; if ($row['day'] == 5) { echo ' selected'; }
      echo '>Vendredi</option></select>';
      echo '<select name="daytype"><option value=1'; if ($row['daytype'] == 1) { echo ' selected'; }
      echo '>Tous</option><option value=2'; if ($row['daytype'] == 2) { echo ' selected'; }
      echo '>Semaine Pair</option><option value=3'; if ($row['daytype'] == 3) { echo ' selected'; }
      echo '>Semaine Impair</option><option value=4'; if ($row['daytype'] == 4) { echo ' selected'; }
      echo '>Premier du mois</option><option value=6'; if ($row['daytype'] == 6) { echo ' selected'; }
      echo '>Mensuel (1)</option><option value=7'; if ($row['daytype'] == 7) { echo ' selected'; }
      echo '>Mensuel (2)</option><option value=8'; if ($row['daytype'] == 8) { echo ' selected'; }
      echo '>Mensuel (3)</option><option value=9'; if ($row['daytype'] == 9) { echo ' selected'; }
      echo '>Mensuel (4)</option></select>';
      echo '</td></tr>';
      echo '<tr><td>&nbsp;</td><td><input type=radio name="periodic" value="0"'; if ($row['periodic'] == 0) { echo ' checked'; }
      echo '>';
      $day = mb_substr($row['deliverydate'],8,2);
      $month = mb_substr($row['deliverydate'],5,2);
      $year = mb_substr($row['deliverydate'],0,4);
      ?><select name="day"><?php
      for ($i=1; $i <= 31; $i++)
      { 
        if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
        else { echo '<option value="' . $i . '">' . $i . '</option>'; }
      }
      ?></select><select name="month"><?php
      for ($i=1; $i <= 12; $i++)
      {
        if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
        else { echo '<option value="' . $i . '">' . $i . '</option>'; }
      }
      ?></select><select name="year"><?php
      for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
      {
        if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
        else { echo '<option value="' . $i . '">' . $i . '</option>'; }
      }
      ?></select></td></tr><?php
      echo '<tr><td colspan=2>&nbsp;</td></tr><tr><td>Ne pas livrer jusq\'au:</td><td>';
      $day = mb_substr($row['vacationdate'],8,2);
      $month = mb_substr($row['vacationdate'],5,2);
      $year = mb_substr($row['vacationdate'],0,4);
      ?><select name="vday"><?php
      for ($i=1; $i <= 31; $i++)
      { 
        if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
        else { echo '<option value="' . $i . '">' . $i . '</option>'; }
      }
      ?></select><select name="vmonth"><?php
      for ($i=1; $i <= 12; $i++)
      {
        if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
        else { echo '<option value="' . $i . '">' . $i . '</option>'; }
      }
      ?></select><select name="vyear"><?php
      for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
      {
        if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
        else { echo '<option value="' . $i . '">' . $i . '</option>'; }
      }
      ?></select></td></tr><?php
      echo '<tr><td colspan="2" align="center"><input type=hidden name="step" value="3"><input type=hidden name="rentalid" value="' . $rentalid . '"><input type="submit" value="Valider"></td></tr>';
      echo '</table></form>';

    }
    break;

    case '3';
    $clientid = $_POST['clientid'];
    $clientname = $_POST['clientname'];
    if ($clientid == "" && $clientname != "")
    {
      $query = 'select clientid from client where clientname="' . $clientname . '"';
      $query_prm = array();
      require('inc/doquery.php');
      if ($num_results == 0) { echo '<font color="' . $_SESSION['ds_alertcolor'] . '">Client inexistant.</font>'; exit; }
      $clientid = $query_result[0]['clientid'];
    }
    $deliverydate = d_builddate($_POST['day'],$_POST['month'],$_POST['year']);
    $deliverydate = correctdate($deliverydate);
    $vacationdate = correctdate(d_builddate($_POST['vday'],$_POST['vmonth'],$_POST['vyear']));
    $eaid = $_POST['extraaddressid']+0;
    $query = 'update vmt_delivery set vacationdate="' . $vacationdate . '",extraaddressid="' . $eaid . '",reference="' . $_POST['reference'] . '",clientid="' . $clientid . '",frtype="' . $_POST['frtype'] . '",quantity="' . $_POST['quantity'] . '",quantity2="' . $_POST['quantity2'] . '",quantity3="' . $_POST['quantity3'] . '",quantity4="' . $_POST['quantity4'] . '",day="' . $_POST['weekday'] . '",daytype="' . $_POST['daytype'] . '",periodic="' . $_POST['periodic'] . '",deliverydate="' . $deliverydate . '" where rentalid="' . $_POST['rentalid'] . '"';
    $query_prm = array();
    require('inc/doquery.php');
    echo '<p>Livraison modifie.</p>';
    break;

  }
  break;



  ### mod rental ###
  case 'modloc':
  switch($_SESSION['ds_step'])
  {

    case '0':
    $client = $_POST['client']; $emptyclientid = $client+0;
    require('inc/findclient.php');
	
    $num_results = 0;
    if ($emptyclientid > 0)
    {
      $query = 'select rentalid from vmt_rental where vmt_rental.clientid="' . $emptyclientid . '"';
      $query_prm = array();
      require('inc/doquery.php');
    }

    if ($clientid > 0 || $num_results > 0)
    {
      if ($clientid > 0)
      {
        $query = 'select rentalid,rentaldate,contractdate,rentalprice,reference,vmt_rental.clientid,clientname,months,vmt_rental.deleted from vmt_rental,client';
        $query = $query . ' where vmt_rental.clientid=client.clientid and vmt_rental.clientid="' . $clientid . '"';
        $query = $query . ' order by vmt_rental.deleted,reference';
        $query_prm = array();
        require('inc/doquery.php');
      }
      echo '<h2>Modifier location</h2><br><form method="post" action="custom.php">';
      echo '<table class=report><tr><td><b>Reference</b></td><td><b>Client</b></td><td><b>Prix Mensuel</b></td><td><b>Date contrat</b></td><td><b>Date location</b></td><td><b>Periodicité</b></td></tr>';
      for ($i=0; $i < $num_results; $i++)
      {
        $row = $query_result[$i];
        echo '<tr><td><input type=radio name=rentalid value="' . $row['rentalid'] . '"';
        if ($i==0) { echo ' checked'; }
        echo '>' . $row['reference'];
        if ($row['deleted'] == 1) { echo ' <span class="alert">(Supprimé)</span>'; }
        echo '</td><td>' . $row['clientid'] . ': ' . d_decode($row['clientname']) . '</td><td align=right>' . $row['rentalprice'] . '</td><td>' . datefix2($row['contractdate']) . '</td><td>' . datefix2($row['rentaldate']) . '</td><td>' . $row['months'] . ' mois</td></tr>';
      }
      echo '<tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type="submit" value="Valider"></td></tr>';
      echo '</table></form>';
    }
    else
    {
?>
    <h2>Modifier location:</h2>
    <form method="post" action="custom.php"><table>
    <tr><td>Client:</td><td><input autofocus type="text" STYLE="text-align:right" name="client" size=20></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="step" value="0"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
    }
    break;

    case '1':
    $rentalid = $_POST['rentalid'];
    if ($rentalid < 1) { echo '<font color="' . $_SESSION['ds_alertcolor'] . '">Veuiller selectionner une location à modifier.</font>'; exit; }
    $query = 'select frigo,rentalcomment,gratuit,resilmotifid,noprelev,deleteddate,deleted,rentalid,rentaldate,contractdate,rentalprice,reference,vmt_rental.clientid,months from vmt_rental where rentalid="' . $rentalid . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $row = $query_result[0];
    $resilmotifid = $row['resilmotifid'];
    $gratuit = $row['gratuit'];
    $frigo = $row['frigo'];
    $query = 'select fountainname from vmt_fountain where rentalid="' . $rentalid . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $fountaintext = "";
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      $fountaintext = $fountaintext . ' ' . $row2['fountainname'];
    }
    echo '<h2>Modifier location:</h2>';
    echo '<form method="post" action="custom.php"><table>';
    echo '<tr><td>No Contrat:</td><td><input type="text" id="myfocus" STYLE="text-align:right" name="reference" value="' . $row['reference'] . '" size=20></td></tr>';
    echo '<tr><td>Date contrat:</td><td>';
    $day = mb_substr($row['contractdate'],8,2);
    $month = mb_substr($row['contractdate'],5,2);
    $year = mb_substr($row['contractdate'],0,4);
    ?><select name="contractday"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="contractmonth"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="contractyear"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    echo '</select></td></tr>';
#    echo '<tr><td>Date Contrat:</td><td><input type="text" STYLE="text-align:right" name="contractdate" value="' . $row['contractdate'] . '" size=20> Att: format (2010-05-25)</td></tr>';
#    echo '<tr><td>No Fontaine:</td><td><input type="text" STYLE="text-align:right" name="fountainname" value="' . $row2['fountainname'] . '" size=20></td></tr>';
      $query = 'select clientname from client where clientid=?';
      $query_prm = array($row['clientid']);
      require('inc/doquery.php');
      $clientname = d_decode($query_result[0]['clientname']);
    echo '<tr><td>Client:</td><td><input type="text" STYLE="text-align:right" name="clientid" value="' . $row['clientid'] . '" size=5> '.d_output($clientname).'('.$row['clientid'].')</td></tr>';
    echo '<tr><td>Prix mensuel:</td><td><input type="text" STYLE="text-align:right" name="rentalprice" value="' . $row['rentalprice'] . '" size=20> &nbsp; ';
    echo '<input type=checkbox name=gratuit value=1';
    if ($gratuit) { echo ' checked'; }
    echo '> Gratuit</td></tr>';
    $month = mb_substr($row['rentaldate'],5,2);
    $year = mb_substr($row['rentaldate'],0,4);
    ?><tr><td>Debut:</td><td><select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    echo '</select></td></tr><tr><td>Periodicité:</td><td><select name="months">';
    echo '<option value=1';
    if ($row['months'] == 1) { echo ' selected'; }
    echo '>1</option><option value=3';
    if ($row['months'] == 3) { echo ' selected'; }
    echo '>3</option><option value=6';
    if ($row['months'] == 6) { echo ' selected'; }
    echo '>6</option><option value=12';
    if ($row['months'] == 12) { echo ' selected'; }
    echo '>12</option>';
    echo '</select> mois &nbsp; Pas de prélèvement: <input type=checkbox name="noprelev" value="1"';
    if ($row['noprelev'] == 1) { echo ' checked'; }
    echo '></td></tr>';

    echo '<tr><td>Fontaines:</td><td>' . $fountaintext . '</td></tr>';
    echo '<tr><td colspan=2>&nbsp;</td></tr>';
        echo '<tr><td>Resilié</td><td><input type=checkbox name="delete" value="1"';
    if ($row['deleted'] == 1) { echo ' checked'; }
    echo '>';
      echo ' Fontaines récuperés par: <select name="employeeid"><option value="0">&nbsp;</option>';

      $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee where iscashier=1 order by employeename'; # restrict to livreurs (client link)
      $query_prm = array();
      require('inc/doquery.php');
      for ($i=0; $i < $num_results; $i++)
      {
        $row2 = $query_result[$i];
        echo '<option value="' . $row2['employeeid'] . '"';
        if ($row2['employeeid'] == $row['employeeid']) { echo ' selected'; }
        echo '>' . $row2['employeename'] . '</option>';
      }
      echo '</select>';
      echo '<br>  Motif résiliation: <select name="resilmotifid">';

      $query = 'select resilmotifid,resilmotifname from vmt_resilmotif order by resilmotifid';
      $query_prm = array();
      require('inc/doquery.php');
      for ($i=0; $i < $num_results; $i++)
      {
        $row2 = $query_result[$i];
        echo '<option value="' . $row2['resilmotifid'] . '"';
        if ($row2['resilmotifid'] == $resilmotifid) { echo ' selected'; }
        echo '>' . $row2['resilmotifname'] . '</option>';
      }
      echo '</select>';
    echo '</td></tr>';
        echo '<tr><td>Date resiliation:</td><td>';
    $day = mb_substr($row['deleteddate'],8,2);
    $month = mb_substr($row['deleteddate'],5,2);
    $year = mb_substr($row['deleteddate'],0,4);
    ?><select name="deletedday"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="deletedmonth"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="deletedyear"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    echo '</select></td></tr>';
    echo '<tr><td>Observations:</td><td><input type="text" name="rentalcomment" value="' . $row['rentalcomment'] . '" size=80></td></tr>';
    echo '<tr><td colspan=2>Frigo: <input type=checkbox name="frigo" value="1"';
    if ($frigo == 1) { echo ' checked'; }
    echo '></td></tr>';
    echo '<tr><td colspan="2" align="center"><input type=hidden name="step" value="2"><input type=hidden name="rentalid" value="' . $rentalid . '"><input type="submit" value="Valider"></td></tr>';
    echo '</table></form>';
    
      $clientid = $row['clientid'];
      require('vaimatoclientfile.php');
    
    break;

    case '2':
    $rentalid = $_POST['rentalid'];
    if ($rentalid < 1) { echo '<font color="' . $_SESSION['ds_alertcolor'] . '">Veuiller selectionner une location à modifier.</font>'; exit; }

#    $query = 'select fountainid,rentalid from vmt_fountain where fountainname="' . $_POST['fountainname'] . '"';
#    $result = mysql_query($query, $db_conn); querycheck($result);
#    $num_results = mysql_num_rows($result);
#    if ($num_results > 0)
#    {
#      $row = mysql_fetch_array($result);
#      if ($row['rentalid'] > 0) { echo '<p><font color="' . $_SESSION['ds_alertcolor'] . '">Fontaine ' . $_POST['fountainname'] . '  déja chez un client.</font></p>'; exit; }
#    }

#    $query = 'update vmt_fountain set rentalid=0 where rentalid="' . $rentalid . '"';
#    $result = mysql_query($query, $db_conn); querycheck($result);
#    $query = 'update vmt_fountain set rentalid="' . $rentalid . '" where fountainname="' . $_POST['fountainname'] . '"';
#    $result = mysql_query($query, $db_conn); querycheck($result);

    $query = 'select rentalid from vmt_rental where deleted=0 and reference="' . $_POST['reference'] . '" and rentalid<>"' . $rentalid . '"';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results)
    {
      echo '<p class="alert">Numéro contract existant.</p>'; exit;
    }

    $newdeletion = 0;
    $query = 'select clientid,deleted from vmt_rental where rentalid="' . $rentalid . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $row = $query_result[0];
    $clientid = $row['clientid'];
    if ($row['deleted'] == 0 && $_POST['delete'] == 1) { $newdeletion = 1; }
    
    if ($newdeletion)
    {
      $query = 'select fountaindescid from vmt_fountaindesc where fountaindescname="STOCK"';
      $query_prm = array();
      require('inc/doquery.php');
      $row = $query_result[0];
      $fountaindescid = $row['fountaindescid'];
      $actiondate = correctdate(d_builddate($_POST['deletedday'],$_POST['deletedmonth'],$_POST['deletedyear']));
      
      $query = 'select fountainid,fountainname from vmt_fountain where rentalid="' . $rentalid . '"';
      $query_prm = array();
      require('inc/doquery.php');
      $fountaintext = "Fontaines:";
      $main_result = $query_result; $num_results_main = $num_results;
      for ($i=0; $i < $num_results_main; $i++)
      {
        $row2 = $main_result[$i];
        $fountaintext = $fountaintext . ' ' . $row2['fountainname'];
        $fountainid = $row2['fountainid'];
        $query = 'insert into vmt_fonhis (fountainid,fonhisdate,fountaindescid,clientid,rentalid,userid,employeeid) values ("' . $fountainid . '","' . $actiondate . '","' . $fountaindescid . '","0","0","' . $_SESSION['ds_userid'] . '","' . $_POST['employeeid'] . '")';
        $query_prm = array();
        require('inc/doquery.php');
      }
      $query = 'update vmt_fountain set fountaindescid="' . $fountaindescid . '",rentalid=0,changedate="' . $actiondate . '" where rentalid="' . $rentalid . '"';
      $query_prm = array();
      require('inc/doquery.php');
      echo '<p>Fontaine(s) mis en STOCK.</p>';
      
      $query = 'select clientactioncatid from clientactioncat where clientactioncatname="RESILIATION"';
      $query_prm = array();
      require('inc/doquery.php');
      $row = $query_result[0];
      $clientactioncatid = $row['clientactioncatid'];
      
      $query = 'insert into clientaction (clientid,actiondate,employeeid,clientactioncatid,actionname,userid) values ("' . $clientid . '","' . $actiondate . '","' . $_POST['employeeid'] . '","' . $clientactioncatid . '","' . $fountaintext . '","' . $_SESSION['ds_userid'] . '")';
      $query_prm = array();
      require('inc/doquery.php'); 
    }

    $contractdate = correctdate(d_builddate($_POST['contractday'],$_POST['contractmonth'],$_POST['contractyear']));
    $deleteddate = correctdate(d_builddate($_POST['deletedday'],$_POST['deletedmonth'],$_POST['deletedyear']));
    $rentaldate = correctdate(d_builddate(1,$_POST['month'],$_POST['year']));
$deleted = 0; if ($_POST['delete'] == 1) { $deleted = 1; }
$noprelev = 0; if ($_POST['noprelev'] == 1) { $noprelev = 1; }
$resilmotifid = $_POST['resilmotifid']+0;
$gratuit = $_POST['gratuit'];
$frigo = $_POST['frigo']+0;
    $query = 'update vmt_rental set frigo="' . $frigo . '",rentalcomment="' . $rentalcomment . '",gratuit="' . $gratuit . '",resilmotifid="' . $resilmotifid . '",noprelev="' . $noprelev . '",deleteddate="' . $deleteddate . '",deleted="' . $deleted . '",reference="' . $_POST['reference'] . '",contractdate="' . $contractdate . '",clientid="' . $_POST['clientid'] . '",rentalprice="' . $_POST['rentalprice'] . '",rentaldate="' . $rentaldate . '",months="' . $_POST['months'] . '" where rentalid="' . $rentalid . '"';
#echo $query;
#    if ($_POST['delete'] == 1) { $query = 'delete from vmt_rental where rentalid="' . $rentalid . '"'; }
    $query_prm = array();
    require('inc/doquery.php');
    echo '<p>Location ' . $_POST['reference'];
    if ($_POST['delete'] == 1) { echo ' supprimé.</p>'; }
    else { echo ' modifié.</p>'; }
    break;

  }
  break;


  case 'showfr':
  ?><h2>Feuille de route:</h2>
  <form method="post" action="customreportwindow.php" target="_blank"><table><?php
  $day = mb_substr($_SESSION['ds_curdate'],8,2);
  $month = mb_substr($_SESSION['ds_curdate'],5,2);
  $year = mb_substr($_SESSION['ds_curdate'],0,4);
  ?><tr><td>Jour:</td><td><select name="day"><?php
  for ($i=1; $i <= 31; $i++)
  { 
    if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="month"><?php
  for ($i=1; $i <= 12; $i++)
  {
    if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="year"><?php
  for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
  {
    if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select></td></tr>
  <tr><td>Livreur:</td>
  <td><select name="employeeid"><?php
  $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee where iscashier=1 order by employeename'; # restrict to livreurs (client link)
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row2 = $query_result[$i];
    echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeeid'] . ': ' . $row2['employeename'] . '</option>';
  }
  ?></select></td></tr>
  <tr><td>Type:</td>
  <td><select name="frtype">
  <option value="0">Bonbonnes</option><option value="1">Pack</option><option value="2">Autre</option>
  </select></td></tr>
  <tr><td><input type=checkbox name=compterendu value=1><td>Compte Rendu
  <tr><td><input type=checkbox name="switch_oddeven" value=1><td>Inverser Pair / Impair
  <tr><td>Lignes / page:</td><td><input type="text" STYLE="text-align:right" name="linesperpage" value=14 size=5></td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="step" value="1">
  <input type=hidden name="report" value="showfr">
  <input type="submit" value="Valider"></td></tr></table></form>
  
  <br><br>
  
  <h2>ANCIEN Feuille de route:</h2>
  <form method="post" action="customreportwindow.php" target="_blank"><table><?php
  $day = mb_substr($_SESSION['ds_curdate'],8,2);
  $month = mb_substr($_SESSION['ds_curdate'],5,2);
  $year = mb_substr($_SESSION['ds_curdate'],0,4);
  ?><tr><td>Jour:</td><td><select name="day"><?php
  for ($i=1; $i <= 31; $i++)
  { 
    if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="month"><?php
  for ($i=1; $i <= 12; $i++)
  {
    if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="year"><?php
  for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
  {
    if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select></td></tr>
  <tr><td>Livreur:</td>
  <td><select name="employeeid"><?php
  $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee where iscashier=1 order by employeename'; # restrict to livreurs (client link)
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row2 = $query_result[$i];
    echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeeid'] . ': ' . $row2['employeename'] . '</option>';
  }
  ?></select></td></tr>
  <tr><td>Type:</td>
  <td><select name="frtype">
  <option value="0">Bonbonnes</option><option value="1">Pack</option><option value="2">Autre</option>
  </select></td></tr>
  <tr><td><input type=checkbox name=compterendu value=1></td><td>Compte Rendu</td></tr>
  <tr><td>Lignes / page:</td><td><input type="text" STYLE="text-align:right" name="linesperpage" value=14 size=5></td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="step" value="1">
  <input type=hidden name="report" value="showfrOLD">
  <input type="submit" value="Valider"></td></tr></table></form><?php
  break;

  case 'comptrendu':
  switch($_SESSION['ds_step'])
  {
    case 0:
    ?><h2>Compte Rendu:</h2>
    <form method="post" action="custom.php"><table><?php
    $day = mb_substr($_SESSION['ds_curdate'],8,2);
    $month = mb_substr($_SESSION['ds_curdate'],5,2);
    $year = mb_substr($_SESSION['ds_curdate'],0,4);
    ?><tr><td>Jour:</td><td><select name="day"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>Livreur:</td>
    <td><select name="employeeid"><?php
    
    $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee where iscashier=1 order by employeename'; # restrict to livreurs (client link)
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = mysql_fetch_array($result);
      echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeeid'] . ': ' . $row2['employeename'] . '</option>';
    }
    ?></select></td></tr>
    <tr><td>Type:</td>
    <td><select name="frtype">
    <option value="0">Bonbonnes</option>
<?php
#<option value="1">Pack</option><option value="2">Autre</option>
?>
    </select></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="step" value="1">
    <input type="submit" value="Valider"></td></tr></table></form><?php
    break;

  case '1':
  
  $day = $_POST['day']; $month = $_POST['month']; $year = $_POST['year']; $employeeid = $_POST['employeeid']; $frtype = $_POST['frtype'];
  $compterendu = 1;
  $query = 'select concat(employeename," ",employeefirstname) as employeename from employee where employeeid="' . $employeeid . '"';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $row = mysql_fetch_array($result);
  $employeename = $row['employeename'];
  $ourdate = d_builddate($day,$month,$year);
  $ourdate = correctdate($ourdate);
  $day = mb_substr($ourdate,8,2);
  # find day of week
  $ourmktime = mktime(0,0,0,$month,$day,$year);
  $ourdate = d_builddate($day,$month,$year); $ourdate = correctdate($ourdate);
  $ourday = date("w",$ourmktime);
  $weeknumber = date("W",$ourmktime);
  $odd = 0; $even = 0;
  if ($weeknumber % 2) { $odd = 1; }
  else { $even = 1; }
# counts
$totalclients = 0; $totalamount = 0;
$query = 'select count(vmt_delivery.clientid) as amountclient,sum(quantity) as quantitytotal from vmt_delivery,client where vmt_delivery.clientid=client.clientid and periodic=1 and client.employeeid="' . $employeeid . '" and vmt_delivery.day="' . $ourday . '" and frtype="' . $frtype . '"';
$result = mysql_query($query, $db_conn); querycheck($result);
$row = mysql_fetch_array($result);
$totalclients = $totalclients + $row['amountclient'];
$totalamount = $totalamount + $row['quantitytotal'];
$query = 'select count(vmt_delivery.clientid) as amountclient,sum(quantity) as quantitytotal from vmt_delivery,client where vmt_delivery.clientid=client.clientid and periodic=0 and client.employeeid="' . $employeeid . '" and vmt_delivery.deliverydate="' . $ourdate . '" and frtype="' . $frtype . '"';
$result = mysql_query($query, $db_conn); querycheck($result);
$row = mysql_fetch_array($result);
$totalclients = $totalclients + $row['amountclient'];
$totalamount = $totalamount + $row['quantitytotal'];
#
  $query = 'select surcharge,usedetail,reference,client.clientid,clientname,frtype,telephone,cellphone,quantity,quarter,address,contact,daytype from vmt_delivery,client where vmt_delivery.clientid=client.clientid';
  $query = $query . ' and periodic=1 and client.employeeid="' . $employeeid . '" and vmt_delivery.day="' . $ourday . '" and frtype="' . $frtype . '"';
  $query = $query . ' order by clientname';
  $resultX = mysql_query($query, $db_conn); querycheck($resultX);
  $num_resultsX = mysql_num_rows($resultX);
  if ($frtype == 0) { $frtypename = 'Bonbonnes'; }
  if ($frtype == 1) { $frtypename = 'Pack'; }
  if ($frtype == 2) { $frtypename = ''; }
  $ourtitle = 'FEUILLE DE ROUTE ' . datefix2($ourdate) . ' ' . $frtypename . ' ' . $employeename;
  if ($compterendu == 1) { $ourtitle = 'COMPTE RENDU ' . datefix2($ourdate) . ' ' . $frtypename . ' ' . $employeename; }
  echo '<h2>' . $ourtitle . '</h2>';
  echo '<p>Nombre de clients: ' . $totalclients . ' &nbsp; &nbsp; &nbsp; Nombre ' . $frtypename . ': ' . $totalamount . '</p>';
  echo '<form method="post" action="custom.php"><table border=1 cellpadding=5 cellspacing=5>';
  if ($compterendu == 1)
  {
    echo '<tr><td>&nbsp;</td><td colspan=3 align=center><b><font size=+1>Factures</font></b></td><td align=center><b><font size=+1>Encaissement</font></b></td></tr>';
    echo '<tr><td><b>Client</b></td><td><b>No Fact</b></td><td><b>Consigne</b></td><td><b>Quantité&nbsp;' . $frtypename . '</b></td><td><b>Montant</b></td></tr>';
  }
  else { echo '<tr><td><b>Client</b></td><td><b>Adresse</b></td><td><b>Contact</b></td><td><b>Telephone</b></td><td><b>Vini</b></td><td><b>Quantité</b></td><td><b>Tarif</b></td><td><b>Info</b></td></tr>'; }
  for ($i=0; $i < $num_resultsX; $i++)
  {
    $row = mysql_fetch_array($resultX);
    $ok = 1;
    if ($row['daytype'] == 2 && $odd == 1) { $ok = 0; }
    if ($row['daytype'] == 3 && $even == 1) { $ok = 0; }
    if ($row['daytype'] == 4 && $day > 7) { $ok = 0; }
    if ($ok)
    {
      echo '<tr><td><input type=hidden name="clientid' . $i . '" value="' . $row['clientid'] . '">' . $row['clientid'] . ': ' . $row['clientname'] . '</td><td><input type="text" STYLE="text-align:right" name="reference' . $i . '" size=10></td><td align=center><input type="checkbox" name="consigne' . $i . '" value="1"></td><td><input type="text" STYLE="text-align:right" name="quantity' . $i . '" size=10></td><td><input type="text" STYLE="text-align:right" name="paym' . $i . '" size=10></td></tr>';
    }
  }
  $query = 'select surcharge,usedetail,reference,client.clientid,clientname,frtype,telephone,cellphone,quantity,quarter,address,contact,daytype from vmt_delivery,client where vmt_delivery.clientid=client.clientid';
  $query = $query . ' and periodic=0 and client.employeeid="' . $employeeid . '" and vmt_delivery.deliverydate="' . $ourdate . '" and frtype="' . $frtype . '"';
  $query = $query . ' order by clientname';
  $resultX = mysql_query($query, $db_conn); querycheck($resultX);
  $num_resultsY = mysql_num_rows($resultX);
$num_resultsXY = $num_resultsX + $num_resultsY;
  for ($i=$num_resultsX; $i < $num_resultsXY; $i++)
  {
    $row = mysql_fetch_array($resultX);
    echo '<tr><td><input type=hidden name="clientid' . $i . '" value="' . $row['clientid'] . '">' . $row['clientid'] . ': ' . $row['clientname'] . '</td><td><input type="text" STYLE="text-align:right" name="reference' . $i . '" size=10></td><td align=center><input type="checkbox" name="consigne' . $i . '" value="1"></td><td><input type="text" STYLE="text-align:right" name="quantity' . $i . '" size=10></td><td><input type="text" STYLE="text-align:right" name="paym' . $i . '" size=10></td></tr>';
  }
  echo '<tr><td colspan="10" align="center"><input type=hidden name="step" value="2"><input type=hidden name="ourcount" value="' . $num_resultsXY . '"><input type="submit" value="Valider"></td></tr>';
  echo '</table></form>';
  break;

  case '2':
  
    # read prices
    $query = 'select salesprice,detailsalesprice from product where productid=10'; # hardcode
    $result = mysql_query($query, $db_conn); querycheck($result);
    $row = mysql_fetch_array($result);
    $sp = myround($row['salesprice']);
    $dsp = myround($row['detailsalesprice']);
  $num_results = $_POST['ourcount'];
  for ($i=0; $i < $num_results; $i++)
  {
    $kladd = 'clientid' . $i; $clientid = $_POST[$kladd];
    $kladd = 'reference' . $i; $reference = $_POST[$kladd];
    $kladd = 'consigne' . $i; $consigne = $_POST[$kladd];
    $kladd = 'quantity' . $i; $quantity = $_POST[$kladd];
    $kladd = 'paym' . $i; $paym = $_POST[$kladd];

    if ($consigne > 0 || $quantity > 0)
    {
        $query = 'select usedetail,surcharge,daystopay from client where clientid="' . $clientid . '"';
        $result = mysql_query($query, $db_conn); querycheck($result);
        $num_results = mysql_num_rows($result);
        $row = mysql_fetch_array($result);
        $daystopay = $row['daystopay'];
        $usedetail = $row['usedetail']; $surcharge = $row['surcharge'];
        ### PRICE DETERMINATION ### COPY FROM sales.php
        $price = $sp;
        if ($usedetail) { $price = $dsp; }

        # surcharge
        if ($surcharge > 0) { $price = $price + myround($price * $surcharge / 100); }

        # check if there is a special category price
        $query = 'select categoryprice as salesprice from categorypricing where productid="' . $productidA['productid' . $i] . '" and clientcategoryid="' . $clientcategoryid . '"';
        $result = mysql_query($query, $db_conn); querycheck($result);
        $num_results = mysql_num_rows($result);
        if ($num_results > 0) { $row2 = mysql_fetch_array($result); $price = $row2['salesprice']; }

        # check if there is a special client price
        $query = 'select salesprice from clientpricing where productid="' . $productidA['productid' . $i] . '" and clientid="' . $clientid . '"';
        $result = mysql_query($query, $db_conn); querycheck($result);
        $num_results = mysql_num_rows($result);
        if ($num_results > 0) { $row2 = mysql_fetch_array($result); $price = $row2['salesprice']; }

      $invoiceprice;
# insert invoice calc all stuff, finish
#      $query = 'insert into invoice (invoicecomment,extraaddressid,paybydate,accountingdate,deliverydate,invoicedate,invoicetime,employeeid,clientid,userid,invoiceprice,invoicevat,) values ("Compte Rendu",0,DATE_ADD(curdate(), INTERVAL ' . $daystopay . ' DAY),curdate(),curdate(),curdate(),curtime(),1,"' . $clientid . '","' . $_SESSION['ds_userid'] . '","' . $invoiceprice . '",)';        ,=' . $vat . ',localvesselid=' . $setvesselid . ',reference="' . $invoicereference . '",proforma="' . $proforma . '",isreturn="' . $isreturn . '",returntostock="' . $returntostock . '" where invoiceid="' . $invoiceid . '"';
echo $query . '<br>';
#      $result = mysql_query($query, $db_conn); querycheck($result);
      $query = 'insert into invoiceitem (invoiceid,productid,quantity,givenrebate,basecartonprice,lineprice,linevat,itemcomment) values ("' . $invoiceid . '","' . $productidA['productid' . $i] . '","' . $quantityA['quantity' . $i] . '","' . $discountA['discount' . $i] . '","' . $basecartonprice[$i] . '","' . $lineprice[$i] . '","' . $linevat[$i] . '","' . $itemcomment['itemcomment' . $i] . '")';
echo $query . '<br>';
#      $result = mysql_query($query, $db_conn); querycheck($result);
    }
  }
  break;

  }
  break;


  ### create rental invoices ###
  case 'makeinvoices':
  switch($_SESSION['ds_step'])
  {
    # Read
    case '0':
    
    echo '<h2>Créer factures location:</h2><form method="post" action="custom.php"><table>';
    $month = mb_substr($_SESSION['ds_curdate'],5,2);
    $year = mb_substr($_SESSION['ds_curdate'],0,4);
    $month = $month; if ($month > 12) { $month = 1; $year = $year + 1; }
    ?><tr><td>Mois:</td><td><select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
    echo '<p class=alert>Continuer à faire cette operation jusq\'au toutes factures sont crées.</p>';
    break;

    case '1':
    
    $productid = 11; # hardcode
    $query = 'select taxcode from taxcode,product where product.taxcodeid=taxcode.taxcodeid and productid="' . $productid . '"';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $row = mysql_fetch_array($result);
    $taxcode = $row['taxcode']; $original_taxcode = $taxcode;
    $ourmonth = $_POST['month']; $ouryear = $_POST['year'];
    $ourdate = d_builddate(1,$ourmonth,$ouryear);
    # months = 1
    $query2 = 'select vatexempt,noprelev,rentalid,reference,vmt_rental.clientid,employeeid2,rentalprice,months from vmt_rental,client where vmt_rental.clientid=client.clientid and vmt_rental.deleted=0 and rentaldate<="' . $ourdate . '" and months=1 and (lastcreatedate<"' . $ourdate . '" OR lastcreatedate is NULL)';
    $query2 = $query2 . ' LIMIT 1000';
    $result2 = mysql_query($query2, $db_conn); querycheck($result2);
    $num_results2 = mysql_num_rows($result2);
require('preload/employee.php');
    for ($i=0; $i < $num_results2; $i++)
    {
      $row = mysql_fetch_array($result2);
      $taxcode = $original_taxcode; if ($row['vatexempt'] == 1) { $taxcode = 0; }
      $employeeid = $row['employeeid2'];
$kladd = $row['employeeid2'];
#echo '<br>DEBUG clientid / employeeid /employee= '.$row['clientid'] . ' / ' . $kladd . ' / ' . $employeeA[$kladd];
      $noprelev = $row['noprelev']+0;
      $rentalid = $row['rentalid'];
      $months = $row['months'];
      $basecartonprice = $row['rentalprice'];
      $tag = 'Contrat ' . $row['reference'] . ', ' . $ourmonth . '/' . $ouryear;
    $tag2 = '';
    $query3 = 'select fountainname from vmt_fountain where rentalid="' . $rentalid . '"';
    $result3 = mysql_query($query3, $db_conn); querycheck($result3);
    $num_results3 = mysql_num_rows($result3);
    for ($x=0; $x < $num_results3; $x++)
    {
      $row3 = mysql_fetch_array($result3);
      $tag2 = $tag2 . $row3['fountainname'] . '<br>';
    }
    if ($num_results3 > 0) { $tag2 = $tag2 . $num_results3 . ' fontaine'; }
    if ($num_results3 > 1) { $tag2 = $tag2 . 's'; }
      $clientid = $row['clientid'];
      $invoiceprice = $basecartonprice * $months;
      $lineprice = round($invoiceprice / (1+($taxcode/100))); $basecartonprice = $lineprice / $months;
      $invoicevat = $invoiceprice - $lineprice;
      $query = 'SET time_zone = "' . $dauphin_timezone . '"';
      $result = mysql_query($query, $db_conn);
      $matchingid = 0;
      if ($noprelev == 0)
      {
        # NEW auto-lettrer
        $query = 'insert into matching (userid,date) values ("' . $_SESSION['ds_userid'] . '",CURDATE())';
        $result = mysql_query($query, $db_conn); querycheck($result);
        $matchingid = mysql_insert_id();
      }
      $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoice"';
      $query_prm = array();
      require('inc/doquery.php');
      $invoiceid = $query_insert_id;
      $query = 'insert into invoice (invoiceid,invoicetagid,matchingid,cancelledid,invoicegroupid,confirmed,hascold,invoicecomment,extraaddressid,extraname,paybydate,accountingdate,deliverydate,invoicedate,invoicetime,employeeid,clientid,freightcost,insurancecost,userid,invoiceprice,invoicevat,localvesselid,reference,proforma,isreturn,returntostock,isnotice) values ("' . $invoiceid . '",2,"' . $matchingid . '",0,0,1,0,"",0,"","' . $ourdate . '","' . $ourdate . '","' . $ourdate . '",curdate(),curtime(),"' . $employeeid . '","' . $clientid . '",0,0,"' . $_SESSION['ds_userid'] . '","' . $invoiceprice . '","' . $invoicevat . '",0,"' . $tag . '",0,0,0,0)';
      $result = mysql_query($query, $db_conn); querycheck($result);
      #$invoiceid = mysql_insert_id();
      echo '<p>Facture ' . $invoiceid . ' crée.</p>';
      $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoiceitem"';
      $query_prm = array();
      require('inc/doquery.php');
      $invoiceitemid = $query_insert_id;
      $query = 'insert into invoiceitem (invoiceitemid,invoiceid,productid,quantity,givenrebate,basecartonprice,lineprice,linevat,itemcomment) values ("' . $invoiceitemid . '","' . $invoiceid . '","' . $productid . '","' . $months . '","0","' . $basecartonprice . '","' . $lineprice . '","' . $invoicevat . '","' . $tag2 . '")';
      $result = mysql_query($query, $db_conn); querycheck($result);
      $query = 'update vmt_rental set lastcreatedate="' . $ourdate . '" where rentalid="' . $rentalid . '"';
      $result = mysql_query($query, $db_conn); querycheck($result);
      if ($noprelev == 0)
      {
        $query = 'insert into payment (forinvoiceid,clientid,paymentdate,paymenttime,value,paymenttypeid,userid,bankid,depositbankid,paymentcomment,matchingid,reimbursement,employeeid,paymentcategoryid) values (?,?,?,CURTIME(),?,?,?,?,?,?,?,?,?,2)';
        #$paymentcomment = 'Prélèvement anticipé facture ' . $invoiceid;
        $paymentcomment = 'Prélèvement anticipé facture ' . $row['reference'];
        $query_prm = array($invoiceid,$clientid,$ourdate,$invoiceprice,5,$_SESSION['ds_userid'],1,1,$paymentcomment,$matchingid,0,0); $query_set_timezone = 1;
        require ('inc/doquery.php'); $query_set_timezone = 0;
      }
    }
    # months = 3
    $ok1 = $_POST['month'];
    $ok2 = $_POST['month'] + 3; if ($ok2 > 12) { $ok2 = $ok2 - 12; }
    $ok3 = $_POST['month'] + 6; if ($ok3 > 12) { $ok3 = $ok3 - 12; }
    $ok4 = $_POST['month'] + 9; if ($ok4 > 12) { $ok4 = $ok4 - 12; }
    $query2 = 'select vatexempt,rentalid,reference,vmt_rental.clientid,employeeid2,rentalprice,months from vmt_rental,client where vmt_rental.clientid=client.clientid and vmt_rental.deleted=0 and rentaldate<="' . $ourdate . '" and months=3 and (month(rentaldate)="' . $ok1 . '" or month(rentaldate)="' . $ok2 . '" or month(rentaldate)="' . $ok3 . '" or month(rentaldate)="' . $ok4 . '") and (lastcreatedate<"' . $ourdate . '" OR lastcreatedate is NULL)';
    $query2 = $query2 . ' LIMIT 1000';
    $result2 = mysql_query($query2, $db_conn); querycheck($result2);
    $num_results2 = mysql_num_rows($result2);
    for ($i=0; $i < $num_results2; $i++)
    {
      $row = mysql_fetch_array($result2);
      $taxcode = $original_taxcode; if ($row['vatexempt'] == 1) { $taxcode = 0; }
      $employeeid = $row['employeeid2'];
      $rentalid = $row['rentalid'];
      $months = $row['months'];
      $basecartonprice = $row['rentalprice'];
      $showyear = $ouryear; if ($ourmonth+2 > 12) { $showyear++; }
      $showmonth = $ourmonth+2; if ($showmonth > 12) { $showmonth = $showmonth - 12; }
      $tag = 'Contrat ' . $row['reference'] . ', ' . $ourmonth . '/' . $ouryear . ' au ' . $showmonth . '/' . $showyear;
    $tag2 = '';
    $query3 = 'select fountainname from vmt_fountain where rentalid="' . $rentalid . '"';
    $result3 = mysql_query($query3, $db_conn); querycheck($result3);
    $num_results3 = mysql_num_rows($result3);
    for ($x=0; $x < $num_results3; $x++)
    {
      $row3 = mysql_fetch_array($result3);
      $tag2 = $tag2 . $row3['fountainname'] . '<br>';
    }
    if ($num_results3 > 0) { $tag2 = $tag2 . $num_results3 . ' fontaine'; }
    if ($num_results3 > 1) { $tag2 = $tag2 . 's'; }
      $clientid = $row['clientid'];
      $invoiceprice = $basecartonprice * $months;
      $lineprice = round($invoiceprice / (1+($taxcode/100))); $basecartonprice = $lineprice / $months;
      $invoicevat = $invoiceprice - $lineprice;
      $query = 'SET time_zone = "' . $dauphin_timezone . '"';
      $result = mysql_query($query, $db_conn);
      $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoice"';
      $query_prm = array();
      require('inc/doquery.php');
      $invoiceid = $query_insert_id;
      $query = 'insert into invoice (invoiceid,invoicetagid,matchingid,cancelledid,invoicegroupid,confirmed,hascold,invoicecomment,extraaddressid,extraname,paybydate,accountingdate,deliverydate,invoicedate,invoicetime,employeeid,clientid,freightcost,insurancecost,userid,invoiceprice,invoicevat,localvesselid,reference,proforma,isreturn,returntostock,isnotice) values ("' . $invoiceid . '",2,0,0,0,1,0,"",0,"","' . $ourdate . '","' . $ourdate . '","' . $ourdate . '",curdate(),curtime(),"' . $employeeid . '","' . $clientid . '",0,0,"' . $_SESSION['ds_userid'] . '","' . $invoiceprice . '","' . $invoicevat . '",0,"' . $tag . '",0,0,0,0)';
      $result = mysql_query($query, $db_conn); querycheck($result);
      #$invoiceid = mysql_insert_id();
      echo '<p>Facture ' . $invoiceid . ' crée.</p>';
      $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoiceitem"';
      $query_prm = array();
      require('inc/doquery.php');
      $invoiceitemid = $query_insert_id;
      $query = 'insert into invoiceitem (invoiceitemid,invoiceid,productid,quantity,givenrebate,basecartonprice,lineprice,linevat,itemcomment) values ("' . $invoiceitemid . '","' . $invoiceid . '","' . $productid . '","' . $months . '","0","' . $basecartonprice . '","' . $lineprice . '","' . $invoicevat . '","' . $tag2 . '")';
      $result = mysql_query($query, $db_conn); querycheck($result);
      $query = 'update vmt_rental set lastcreatedate="' . $ourdate . '" where rentalid="' . $rentalid . '"';
      $result = mysql_query($query, $db_conn); querycheck($result);
      #$query = 'insert into payment (clientid,paymentdate,paymenttime,value,paymenttypeid,userid,bankid,depositbankid,paymentcomment,matchingid,reimbursement,employeeid) values (?,?,CURTIME(),?,?,?,?,?,?,0,?,?)';
      #$paymentcomment = 'Prélèvement anticipé facture ' . $invoiceid;
      #$query_prm = array($clientid,$ourdate,$invoiceprice,3,$_SESSION['ds_userid'],1,1,$paymentcomment,0,0); $query_set_timezone = 1;
      #require ('inc/doquery.php'); $query_set_timezone = 0;
    }
    # months = 6
    $ok1 = $_POST['month'];
    $ok2 = $_POST['month'] + 6; if ($ok2 > 12) { $ok2 = $ok2 - 12; }
    $query2 = 'select vatexempt,rentalid,reference,vmt_rental.clientid,employeeid2,rentalprice,months from vmt_rental,client where vmt_rental.clientid=client.clientid and vmt_rental.deleted=0 and rentaldate<="' . $ourdate . '" and months=6 and (month(rentaldate)="' . $ok1 . '" or month(rentaldate)="' . $ok2 . '") and (lastcreatedate<"' . $ourdate . '" OR lastcreatedate is NULL)';
    $query2 = $query2 . ' LIMIT 1000';
    $result2 = mysql_query($query2, $db_conn); querycheck($result2);
    $num_results2 = mysql_num_rows($result2);
    for ($i=0; $i < $num_results2; $i++)
    {
      $row = mysql_fetch_array($result2);
      $taxcode = $original_taxcode; if ($row['vatexempt'] == 1) { $taxcode = 0; }
      $employeeid = $row['employeeid2'];
      $rentalid = $row['rentalid'];
      $months = $row['months'];
      $basecartonprice = $row['rentalprice'];
      $showyear = $ouryear; if ($ourmonth+5 > 12) { $showyear++; }
      $showmonth = $ourmonth+5; if ($showmonth > 12) { $showmonth = $showmonth - 12; }
      $tag = 'Contrat ' . $row['reference'] . ', ' . $ourmonth . '/' . $ouryear . ' au ' . $showmonth . '/' . $showyear;
    $tag2 = '';
    $query3 = 'select fountainname from vmt_fountain where rentalid="' . $rentalid . '"';
    $result3 = mysql_query($query3, $db_conn); querycheck($result3);
    $num_results3 = mysql_num_rows($result3);
    for ($x=0; $x < $num_results3; $x++)
    {
      $row3 = mysql_fetch_array($result3);
      $tag2 = $tag2 . $row3['fountainname'] . '<br>';
    }
    if ($num_results3 > 0) { $tag2 = $tag2 . $num_results3 . ' fontaine'; }
    if ($num_results3 > 1) { $tag2 = $tag2 . 's'; }
      $clientid = $row['clientid'];
      $invoiceprice = $basecartonprice * $months;
      $lineprice = round($invoiceprice / (1+($taxcode/100))); $basecartonprice = $lineprice / $months;
      $invoicevat = $invoiceprice - $lineprice;
      $query = 'SET time_zone = "' . $dauphin_timezone . '"';
      $result = mysql_query($query, $db_conn);
      $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoice"';
      $query_prm = array();
      require('inc/doquery.php');
      $invoiceid = $query_insert_id;
      $query = 'insert into invoice (invoiceid,invoicetagid,matchingid,cancelledid,invoicegroupid,confirmed,hascold,invoicecomment,extraaddressid,extraname,paybydate,accountingdate,deliverydate,invoicedate,invoicetime,employeeid,clientid,freightcost,insurancecost,userid,invoiceprice,invoicevat,localvesselid,reference,proforma,isreturn,returntostock,isnotice) values ("' . $invoiceid . '",2,0,0,0,1,0,"",0,"","' . $ourdate . '","' . $ourdate . '","' . $ourdate . '",curdate(),curtime(),"' . $employeeid . '","' . $clientid . '",0,0,"' . $_SESSION['ds_userid'] . '","' . $invoiceprice . '","' . $invoicevat . '",0,"' . $tag . '",0,0,0,0)';
      $result = mysql_query($query, $db_conn); querycheck($result);
      #$invoiceid = mysql_insert_id();
      echo '<p>Facture ' . $invoiceid . ' crée.</p>';
      $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoiceitem"';
      $query_prm = array();
      require('inc/doquery.php');
      $invoiceitemid = $query_insert_id;
      $query = 'insert into invoiceitem (invoiceitemid,invoiceid,productid,quantity,givenrebate,basecartonprice,lineprice,linevat,itemcomment) values ("' . $invoiceitemid . '","' . $invoiceid . '","' . $productid . '","' . $months . '","0","' . $basecartonprice . '","' . $lineprice . '","' . $invoicevat . '","' . $tag2 . '")';
      $result = mysql_query($query, $db_conn); querycheck($result);
      $query = 'update vmt_rental set lastcreatedate="' . $ourdate . '" where rentalid="' . $rentalid . '"';
      $result = mysql_query($query, $db_conn); querycheck($result);
      #$query = 'insert into payment (clientid,paymentdate,paymenttime,value,paymenttypeid,userid,bankid,depositbankid,paymentcomment,matchingid,reimbursement,employeeid) values (?,?,CURTIME(),?,?,?,?,?,?,0,?,?)';
      #$paymentcomment = 'Prélèvement anticipé facture ' . $invoiceid;
      #$query_prm = array($clientid,$ourdate,$invoiceprice,3,$_SESSION['ds_userid'],1,1,$paymentcomment,0,0); $query_set_timezone = 1;
      #require ('inc/doquery.php'); $query_set_timezone = 0;
    }
    # months = 12
    $ok1 = $_POST['month'];
    $query2 = 'select vatexempt,rentalid,reference,vmt_rental.clientid,employeeid2,rentalprice,months from vmt_rental,client where vmt_rental.clientid=client.clientid and vmt_rental.deleted=0 and rentaldate<="' . $ourdate . '" and months=12 and month(rentaldate)="' . $ok1 . '" and (lastcreatedate<"' . $ourdate . '" OR lastcreatedate is NULL)';
    $query2 = $query2 . ' LIMIT 1000';
    $result2 = mysql_query($query2, $db_conn); querycheck($result2);
    $num_results2 = mysql_num_rows($result2);
    for ($i=0; $i < $num_results2; $i++)
    {
      $row = mysql_fetch_array($result2);
      $taxcode = $original_taxcode; if ($row['vatexempt'] == 1) { $taxcode = 0; }
      $employeeid = $row['employeeid2'];
      $rentalid = $row['rentalid'];
      $months = $row['months'];
      $basecartonprice = $row['rentalprice'];
      $showyear = $ouryear; if ($ourmonth+11 > 12) { $showyear++; }
      $showmonth = $ourmonth+11; if ($showmonth > 12) { $showmonth = $showmonth - 12; }
      $tag = 'Contrat ' . $row['reference'] . ', ' . $ourmonth . '/' . $ouryear . ' au ' . $showmonth . '/' . $showyear;
    $tag2 = '';
    $query3 = 'select fountainname from vmt_fountain where rentalid="' . $rentalid . '"';
    $result3 = mysql_query($query3, $db_conn); querycheck($result3);
    $num_results3 = mysql_num_rows($result3);
    for ($x=0; $x < $num_results3; $x++)
    {
      $row3 = mysql_fetch_array($result3);
      $tag2 = $tag2 . $row3['fountainname'] . '<br>';
    }
    if ($num_results3 > 0) { $tag2 = $tag2 . $num_results3 . ' fontaine'; }
    if ($num_results3 > 1) { $tag2 = $tag2 . 's'; }
      $clientid = $row['clientid'];
      $invoiceprice = $basecartonprice * $months;
      $lineprice = round($invoiceprice / (1+($taxcode/100))); $basecartonprice = $lineprice / $months;
      $invoicevat = $invoiceprice - $lineprice;
      $query = 'SET time_zone = "' . $dauphin_timezone . '"';
      $result = mysql_query($query, $db_conn);
      $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoice"';
      $query_prm = array();
      require('inc/doquery.php');
      $invoiceid = $query_insert_id;
      $query = 'insert into invoice (invoiceid,invoicetagid,matchingid,cancelledid,invoicegroupid,confirmed,hascold,invoicecomment,extraaddressid,extraname,paybydate,accountingdate,deliverydate,invoicedate,invoicetime,employeeid,clientid,freightcost,insurancecost,userid,invoiceprice,invoicevat,localvesselid,reference,proforma,isreturn,returntostock,isnotice) values ("' . $invoiceid . '",2,0,0,0,1,0,"",0,"","' . $ourdate . '","' . $ourdate . '","' . $ourdate . '",curdate(),curtime(),"' . $employeeid . '","' . $clientid . '",0,0,"' . $_SESSION['ds_userid'] . '","' . $invoiceprice . '","' . $invoicevat . '",0,"' . $tag . '",0,0,0,0)';
      $result = mysql_query($query, $db_conn); querycheck($result);
      #$invoiceid = mysql_insert_id();
      echo '<p>Facture ' . $invoiceid . ' crée.</p>';
      $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoiceitem"';
      $query_prm = array();
      require('inc/doquery.php');
      $invoiceitemid = $query_insert_id;
      $query = 'insert into invoiceitem (invoiceitemid,invoiceid,productid,quantity,givenrebate,basecartonprice,lineprice,linevat,itemcomment) values ("' . $invoiceitemid . '","' . $invoiceid . '","' . $productid . '","' . $months . '","0","' . $basecartonprice . '","' . $lineprice . '","' . $invoicevat . '","' . $tag2 . '")';
      $result = mysql_query($query, $db_conn); querycheck($result);
      $query = 'update vmt_rental set lastcreatedate="' . $ourdate . '" where rentalid="' . $rentalid . '"';
      $result = mysql_query($query, $db_conn); querycheck($result);
      #$query = 'insert into payment (clientid,paymentdate,paymenttime,value,paymenttypeid,userid,bankid,depositbankid,paymentcomment,matchingid,reimbursement,employeeid) values (?,?,CURTIME(),?,?,?,?,?,?,0,?,?)';
      #$paymentcomment = 'Prélèvement anticipé facture ' . $invoiceid;
      #$query_prm = array($clientid,$ourdate,$invoiceprice,3,$_SESSION['ds_userid'],1,1,$paymentcomment,0,0); $query_set_timezone = 1;
      #require ('inc/doquery.php'); $query_set_timezone = 0;
    }
    require('inc/move_to_history.php');
    echo '<p>Terminé.</p>';
    break;

  }
  break;


  ### consignes ###
  case 'consignes':
  switch($_SESSION['ds_step'])
  {
    # Read
    case '0':
    $error = 0;
    
?>
    <h2>Consignes / client:</h2>
    <form method="post" action="custom.php"><table>
    <tr><td>Client:</td><td><input type="text" id="myfocus" STYLE="text-align:right" name="clientid" size=5> : <input type="text" id="clientlist" STYLE="text-align:right" name="clientname" size=20></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    case '1':
    
    $clientid = $_POST['clientid'];
    $clientname = $_POST['clientname'];
    if ($clientid == "" && $clientname != "")
    {
      $query = 'select clientid from client where clientname="' . $clientname . '"';
      $result = mysql_query($query, $db_conn); querycheck($result);
      $num_results = mysql_num_rows($result);
      if ($num_results == 0) { echo '<font color="' . $_SESSION['ds_alertcolor'] . '">Client inexistant.</font>'; exit; }
      $row = mysql_fetch_array($result);
      $clientid = $row['clientid'];
    }
echo $clientid;
    break;

  break;
  }

  default:

  break;
  }




?>

</td></tr></table>



<?php

  require ('inc/bottom.php');



?>