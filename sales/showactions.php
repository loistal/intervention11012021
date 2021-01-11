<h2>Rapport évènements :</h2>
<form method="post" action="reportwindow.php" target="_blank">
  <table>
    <tr>
      <td>Debut:</td>
      <td>
        <?php
        $datename = 'startdate'; if ($_SESSION['ds_restrict_sales_reports']) { $dp_datepicker_min = (mb_substr($_SESSION['ds_curdate'],0,4)-1).'-01-01'; }
        require('inc/datepicker.php');
        ?>
      </td>
    </tr>

    <tr>
      <td>Fin:</td>
      <td>
        <?php
        $datename = 'stopdate';
        require('inc/datepicker.php');
        ?>
      </td>
    </tr>
    <tr>
      <td>
        <?php
        $dp_itemname = 'user';
        $dp_description = 'Utilisateur';
        $dp_allowall = 1;
        $dp_selectedid = -1;
        $dp_noblank = 1;
        require('inc/selectitem.php');
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

    <tr>
      <td>
        <?php
        $dp_itemname = 'employee';
        $dp_description = 'Employé(e)';
        $dp_allowall = 1;
        $dp_selectedid = -1;
        require('inc/selectitem.php');
        ?>
      </td>
    </tr>

    <?php if ($_SESSION['ds_restrict_sales_reports'] == 0) { ?>
    <tr>
      <td>
        <?php
        $dp_itemname = 'clientactioncat';
        $dp_description = 'Catégorie d\'action';
        $dp_allowall = 1;
        $dp_selectedid = -1;
        require('inc/selectitem.php');
        ?>
      </td>
    </tr>
    <?php } ?>

    <?php if ($_SESSION['ds_term_clientactionfield1'] != '')
    { ?>
      <tr>
        <td><?php print d_output($_SESSION['ds_term_clientactionfield1']); ?></td>
        <td>
          <input type="text" name="field1" size="20">
          (à <input type="text" name="field1stop" size="20">)
        </td>
      </tr>
    <?php } ?>

    <tr>
      <td>Afficher Supprimés:</td>
      <td>
        <select name="showdeleted">
          <option value="-1">Non</option>
          <option value="1">Oui</option>
          <option value="2">Tous</option>
        </select>
      </td>
    </tr>

    <tr>
      <td colspan="2" align="center">
        <input type=hidden name="report" value="showactionsreport">
        <input type="submit" value="Valider">
      </td>
    </tr>
  </table>
</form>
<?php
require('reportwindow/showactionsreport_cf.php');
require('inc/configreport.php');
?>