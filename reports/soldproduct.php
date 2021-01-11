<h2><?php print d_trad('soldproduct:'); ?></h2>
<form method="post" action="reportwindow.php" target="_blank">
  <table>
    <tr>
      <td><?php print d_trad('date:'); ?></td>
      <td>
        <select name="datefield">
          <option
            value="0"><?php print $_SESSION['ds_term_accountingdate']; ?></option>
          <?php if ($_SESSION['ds_hidedeliverydate'] == 0)
          { ?>
            <option value="1"><?php print $_SESSION['ds_term_deliverydate']; ?></option>
          <?php } ?>

          <option value="2"><?php print d_trad('inputdate'); ?></option>
          <option value="3"><?php print d_trad('tobepaidbefore'); ?></option>
        </select>
      </td>
    </tr>

    <tr>
      <td><?php print d_trad('startdate:'); ?></td>
      <td>
        <?php
        $datename = 'startdate'; if ($_SESSION['ds_restrict_sales_reports']) { $dp_datepicker_min = (mb_substr($_SESSION['ds_curdate'],0,4)-1).'-01-01'; }
        require('inc/datepicker.php');
        ?>
      </td>
    </tr>

    <tr>
      <td><?php print d_trad('stopdate:'); ?></td>
      <td>
        <?php
        $datename = 'stopdate';
        require('inc/datepicker.php');
        ?>
      </td>
    </tr>

    <tr>
      <td>Type:</td>
      <td>
        <select name="invoicetype">
          <option value="-1"><?php print d_trad('selectall'); ?></option>
          <option value="2"><?php print d_trad('isreturn'); ?></option>
          <option value="3"><?php print d_trad('proforma'); ?></option>
          <option value="4"><?php print $_SESSION['ds_term_invoicenotice']; ?></option>
          <option value="5"><?php print d_trad('isreturnparam', $_SESSION['ds_term_invoicenotice']); ?></option>
        </select>
      </td>
    </tr>

    <tr>
      <td><?php print d_trad('status:'); ?></td>
      <td>
        <select name="invoicestatus">
          <?php
          # echo '<option value="-1">'. d_trad('selectall') .'</option>'; do NOT use UNION
          ?>
          <option value="0"><?php print d_trad('confirmed1'); ?></option>
          <option value="1"><?php print d_trad('confirmedandnotmatched'); ?></option>
          <option value="2"><?php print d_trad('matched'); ?></option>
          <option value="3"><?php print d_trad('notconfirmed'); ?></option>
          <option value="4"><?php print d_trad('cancelled'); ?></option>
        </select>
      </td>
    </tr>
    
    <?php
    if ($_SESSION['ds_usedelivery'] > 0)
    {
      echo '<tr><td>Livraison:';
      $dp_itemname = 'deliverytype'; $dp_noblank = 1; $dp_allowall = 1;
      require('inc/selectitem.php');
    }
    ?>

    <?php if ($_SESSION['ds_restrict_sales_reports'] == 0) { ?>
    <tr>
      <?php
      $dp_itemname = 'productfamily';
      $dp_description = d_trad('subfamily');
      $dp_allowall = 1;
      $dp_noblank = 1;
      require('inc/selectitem_productfamily.php');

      $dp_itemname = 'productfamilygroup';
      $dp_description = d_trad('family');
      $dp_allowall = 1;
      $dp_noblank = 1;
      require('inc/selectitem_productfamilygroup.php');

      $dp_itemname = 'productdepartment';
      $dp_description = d_trad('department');
      $dp_allowall = 1;
      $dp_noblank = 1;
      require('inc/selectitem.php');
      ?>
    </tr>
    <?php } ?>

    <tr>
      <td>
        <?php
        require('inc/selectproduct.php');
        ?>
      </td>
    </tr>

    <tr>
      <td>
        <?php
        require('inc/selectclient.php');
        ?>
      </td>
    </tr>

    <?php if ($_SESSION['ds_restrict_sales_reports'] == 0) { ?>
    <tr>
      <td>
        <?php
        $dp_addtoid = 'supplier';
        $dp_description = d_trad('supplier:');
        require('inc/selectclient.php');
        ?>
      </td>
    </tr>
    <?php } ?>

    <?php
    $dp_itemname = 'island';
    $dp_description = d_trad('island');
    $dp_allowall = 1;
    $dp_selectedid = -1;
    $dp_noblank = 1;
    require('inc/selectitem.php');
    
    if ($_SESSION['ds_term_localvessel'] != '')
    {
      $dp_itemname = 'localvessel'; $dp_description = $_SESSION['ds_term_localvessel']; $dp_allowall = 1; $dp_selectedid = -1;
      require('inc/selectitem.php');
    }

    $dp_itemname = 'user';
    $dp_description = d_trad('user');
    $dp_allowall = 1;
    $dp_selectedid = -1;
    $dp_noblank = 1;
    require('inc/selectitem.php');

    $dp_itemname = 'employee';
    $dp_issales = 1;
    $dp_description = d_trad('invoiceemployee');
    $dp_allowall = 1;
    $dp_selectedid = -1;
    require('inc/selectitem.php');

    $dp_itemname = 'employee';
    $dp_addtoid = '1';
    $dp_iscashier = 1;
    $dp_description = d_trad('employeewithparam', $_SESSION['ds_term_clientemployee1']);
    $dp_allowall = 1;
    $dp_selectedid = -1;
    require('inc/selectitem.php');

    $dp_itemname = 'employee';
    $dp_addtoid = '2';
    $dp_iscashier = 1;
    $dp_description = d_trad('employeewithparam', $_SESSION['ds_term_clientemployee2']);
    $dp_allowall = 1;
    $dp_selectedid = -1;
    require('inc/selectitem.php');

    if ($_SESSION['ds_restrict_sales_reports'] == 0)
    {
      $dp_itemname = 'clientcategory';
      $dp_description = d_trad('clientcategory');
      $dp_allowall = 1;
      require('inc/selectitem.php');

      $dp_itemname = 'clientcategory2';
      $dp_description = d_trad('clientcategory2');
      $dp_allowall = 1;
      require('inc/selectitem.php');

      $dp_itemname = 'clientterm';
      $dp_description = d_trad('clientterm');
      $dp_allowall = 1;
      $dp_selectedid = -1;
      $dp_noblank = 1;
      require('inc/selectitem.php');
    }

    $dp_itemname = 'temperature';
    $dp_description = 'Température';
    $dp_allowall = 1;
    require('inc/selectitem.php');

    if ($_SESSION['ds_restrict_sales_reports'] == 0)
    {
      $dp_itemname = 'unittype';
      $dp_description = 'Type d\'unité';
      $dp_allowall = 1;
      $dp_noblank = 1;
      require('inc/selectitem.php');

      $dp_itemname = 'country';
      $dp_description = 'Pays';
      $dp_allowall = 1;
      require('inc/selectitem.php');

      $dp_itemname = 'producttype';
      $dp_description = 'Type du produit';
      $dp_allowall = 1;
      require('inc/selectitem.php');
      ?>

      <tr>
        <td>Marque :</td>
        <td>
          <input type="text" name="brand">
        </td>
      </tr>
    <?php } ?>

    <tr>
      <td><?php print $_SESSION['ds_term_reference']; ?> :</td>
      <td>
        <input type="text" name="reference">
        <input type="checkbox" name="exreference" value="1"> Exclure
      </td>
    </tr>

    <tr>
      <td><?php print $_SESSION['ds_term_extraname']; ?>':</td>
      <td>
        <input type="text" name="extraname">
        <input type=checkbox name="exextraname" value="1"> Exclure
      </td>
    </tr>
    
    <tr>
      <td>Commentaire (ligne) :</td>
      <td>
        <input type="text" name="itemcomment">
      </td>
    </tr>

    <?php
    if ($_SESSION['ds_term_field1'] != "")
    {
      print '<tr>';
      print '<td>' . $_SESSION['ds_term_field1'] . ':</td>';
      print '<td>';
      print '<input type="text" name="field1">';
      print '</td>';
      print '</tr>';
    }

    if ($_SESSION['ds_term_field2'] != "")
    {
      print '<tr>';
      print '<td>' . $_SESSION['ds_term_field2'] . ':</td>';
      print '<td>';
      print '<input type="text" name="field2">';
      print '</td>';
      print '</tr>';
    }

    if ($_SESSION['ds_useserialnumbers'])
    {
      print '<tr>';
      print '<td>No Serie:</td>';
      print '<td>';
      print '<input type="text" name="serial">';
      print '</td>';
      print '</tr>';
    }
    ?>

    <tr>
      <td><?php print d_trad('orderby:'); ?></td>
      <td>
        <select name="orderby">
          <option value="0"><?php print d_trad('invoicenumber'); ?></option>
          <option value="1"><?php print d_trad('clientnumber'); ?></option>
          <option value="5"><?php print d_trad('clientname'); ?></option>
          <?php
          if ($_SESSION['ds_term_reference'] != '')
          {
            print '<option value="2">' . $_SESSION['ds_term_reference'] . '</option>';
          }
          if ($_SESSION['ds_term_field1'] != '')
          {
            print '<option value="3">' . $_SESSION['ds_term_field1'] . '</option>';
          }
          if ($_SESSION['ds_term_field2'] != '')
          {
            print '<option value="4">' . $_SESSION['ds_term_field2'] . '</option>';
          }
          if ($_SESSION['ds_useproductcode'])
          {
            print '<option value="6">' . d_trad('productcode') . '</option>';
          }
          else
          {
            print '<option value="7">' . d_trad('productnumber') . '</option>';
          }
          ?>
        </select>
      </td>
    </tr>

    <tr>
      <td colspan="2" align="center">
        <input type=hidden name="report" value="soldproductreport">
        <input type="submit" value="Valider">
      </td>
    </tr>
  </table>
</form>

<?php
require('reportwindow/soldproductreport_cf.php');
require('inc/configreport.php');
?>