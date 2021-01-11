<h2>Rapport évènements produit :</h2>
<form method="post" action="reportwindow.php" target="_blank">
  <table>
    <tr>
      <td>Debut :</td>
      <td>
        <?php
        $datename = 'startdate';
        require('inc/datepicker.php');
        ?>
      </td>
    </tr>

    <tr>
      <td>Fin :</td>
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
        require('inc/selectproduct.php');
        ?>
      </td>
    </tr>
<?php
$dp_itemname = 'competitor'; $dp_description = 'Entreprise concurrente'; $dp_allowall = 1; $dp_noblank = 1;
require('inc/selectitem.php');
?>
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

    <tr>
      <td>
        <?php
        $dp_itemname = 'productactioncat';
        $dp_description = 'Catégorie d\'action';
        $dp_allowall = 1;
        $dp_selectedid = -1;
        require('inc/selectitem.php');
        ?>
      </td>
    </tr>

    <tr>
      <td>Afficher supprimés</td>
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
        <input type=hidden name="report" value="showproductactionsreport">
        <input type="submit" value="Valider">
      </td>
    </tr>
  </table>
</form>
<?php
require('reportwindow/showproductactionsreport_cf.php');
require('inc/configreport.php');
?>