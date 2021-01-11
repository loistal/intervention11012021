<?php

# Build web page
require ('inc/standard.php');
require ('inc/top.php');
require ('inc/logo.php');
require ('inc/menu.php');

# table
?>
</div><div id="wrapper">
<title>AFEQ</title>
<div id="leftmenu">
  <div id="selectactionbar">
    <div class="selectaction">
      <?php
      #if ($_SESSION['ds_systemaccess'])
      #{
      echo '[<a class="leftmenu" href="custom.php?custommenu=prodsales">Produits&nbsp;vendus - Ancien</a>]<br>';
      #}
      ?>
      <br>
    </div>
  </div>

<?php
require ('inc/searchbox.php');
require ('inc/copyright.php');
?>
</div><div id="mainprogram">
<?php

$custommenu = "";
if (isset($_GET['custommenu'])) { $custommenu = $_GET['custommenu']; }
if (isset($_POST['custommenu'])) { $custommenu = $_POST['custommenu']; }
$custommenu = d_safebasename($custommenu);

$currentstep = 0;
if (isset($_POST['step'])) { $currentstep = $_POST['step']+0; }

# Go to the menuitem
switch($custommenu)
{
  
  case 'prodsales':

    require('preload/returnreason.php');
    ?>
    <h2>Produits vendus:</h2>
    <form method="post" action="reportwindow.php" target="_blank"><table>
    <?php

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
    echo '<tr><td>Produit:</td><td><input type="text" STYLE="text-align:right" name="product" size=20></td></tr>';
    ?>
    <tr><td>Client:</td><td><input type="text" STYLE="text-align:right" name="client" size=20></td></tr>
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
    <tr><td>Employé(e):</td>
    <td><select name="employeeid"><option value="0"> </option><?php
    $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee where issales=1 and deleted=0 order by employeename';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row2['employeeid'] == $employeeid) { echo '<option value="' . $row2['employeeid'] . '" SELECTED>' . $row2['employeename'] . '</option>'; }
      else { echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeename'] . '</option>'; }
    }
    ?></select> <input type="checkbox" name="showpayments" value="1"> Afficher paiements</td></tr>


    <tr><td>Status:</td>
    <td><select name="mychoice"><option value=1><?php echo d_trad('selectall'); ?></option><?php echo d_trad('selectall'); ?> sauf annulées</option><option value=3>Non confirmées</option><option value=2>Confirmées</option><option value=8>Confirmées et non lettrées</option><option value=9>Lettrées</option><option value=4>Annulées</option></select></td></tr>

    <tr><td>Type:</td>
    <td><select name="mychoice2"><option value=1><?php echo d_trad('selectall'); ?></option><option value=2>Factures</option><option value=5>Avoirs</option></select></td></tr>

    <?php
    if (isset($returnreasonA))
    {
      $dp_itemname = 'returnreason'; $dp_description = ' &nbsp; Reason d\'avoir'; $dp_allowall = 1; $dp_selectedid = -1;
      require('inc/selectitem.php');
    }
    ?>

    <tr><td> &nbsp; Proforma:</td>
    <td><select name="mychoice3"><option value=1><?php echo d_trad('selectall'); ?></option><option value=2>Proformas</option><option value=3>Non proforma</option></select></td></tr>

    <?php

    echo '<tr><td> &nbsp; ' . $_SESSION['ds_term_invoicenotice'] . ':</td>
    <td><select name="mychoice4"><option value=1>' . d_trad('selectall') . '</option><option value=2>' . $_SESSION['ds_term_invoicenotice'] . '</option><option value=3>Non ' . $_SESSION['ds_term_invoicenotice'] . '</option></select></td></tr>';

    /*
    <option value=6>Proforma</option><option value=7>
    <?php echo $_SESSION['ds_term_invoicenotice']; ?>
    </option>
    */

    if ($_SESSION['ds_useinvoicetag'])
    {
      $dp_itemname = 'invoicetag'; $dp_description = ' &nbsp; ' . $_SESSION['ds_term_invoicetag']; $dp_allowall = 1; $dp_selectedid = -1;
      require ('inc/selectitem.php');
    }

    echo '<tr><td>Classe produit:</td><td><select name="productdepartmentid"><option value=0>' . d_trad('selectall') . '</option>';
    $query = 'select productdepartmentname,productdepartmentid from productdepartment order by productdepartmentname';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      echo '<option value="' . $query_result[$i]['productdepartmentid'] . '">' . $query_result[$i]['productdepartmentname'] . '</option>';
    }
    echo '</select></td></tr>';

    echo '<tr><td>Famille produit:</td><td><select name="productfamilygroupid"><option value=0>' . d_trad('selectall') . '</option>';
    $query = 'select productfamilygroupname,productfamilygroupid,productdepartmentname from productfamilygroup,productdepartment where productfamilygroup.productdepartmentid=productdepartment.productdepartmentid order by productdepartmentname,productfamilygroupname';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      echo '<option value="' . $query_result[$i]['productfamilygroupid'] . '">' . $query_result[$i]['productdepartmentname'] . '/' . $query_result[$i]['productfamilygroupname'] . '</option>';
    }
    echo '</select></td></tr>';

    echo '<tr><td>Sous-famille produit:</td><td><select name="productfamilyid"><option value=0>' . d_trad('selectall') . '</option>';
    $query = 'select productfamilyid,productfamilyname,productfamilygroupname,productdepartmentname from productfamily,productfamilygroup,productdepartment where productfamilygroup.productdepartmentid=productdepartment.productdepartmentid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid order by productdepartmentname,productfamilygroupname,productfamilyname';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      echo '<option value="' . $query_result[$i]['productfamilyid'] . '">' . $query_result[$i]['productdepartmentname'] . '/' . $query_result[$i]['productfamilygroupname'] . '/' . $query_result[$i]['productfamilyname'] . '</option>';
    }
    echo '</select></td></tr>';

    ###

    ?>

    <?php
    # TODO currentpurchasebatchid
    #<tr><td>Tracking automatique:</td><td><input type="text" STYLE="text-align:right" name="supplierbatchname_a" size=20></td></tr>
    ?>
    <tr><td>Tracking manuel (lot fournisseur):</td><td><input type="text" STYLE="text-align:right" name="supplierbatchname" size=20></td></tr>


    <tr><td>Afficher commentaire facture</td>
    <td><input type="checkbox" name="showinvoicecomment" value="1"></td></tr>

    <tr><td>Ranger par</td>
    <td><select name="orderby">
    <option value=0>Numéro facture</option>
    <option value=1>Client,Produit</option>
    <option value=2>Géo (clients sans Île non affichés)</option>
    </select></td></tr>

    <tr><td colspan="2" align="center">
    <input type=hidden name="report" value="prodsales">
    <input type="submit" value="Valider"></td></tr></table></form><?php


  break;

  default:

  break;
}
?>

<?php
require ('inc/bottom.php');
?>