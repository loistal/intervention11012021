<?php
$save = (int) $_POST['saveme'];

if (isset($save) && $save == 1)
{
  $position_logo_default = (int) $_POST['position-logo-default'];
  $position_informations_client_default = (int) $_POST['position-informations-client-default'];
  $position_title_invoice_default = (int) $_POST['position-title-invoice-default'];
  $invoice_dateformat = (int) $_POST['invoice-dateformat'];

  $query = 'UPDATE globalvariables
            SET showinvoice_position_logo_default  = ?,
                showinvoice_position_client_information_default = ?,
                showinvoice_position_title_invoice_default  = ?,
                showinvoice_dateformat = ?';

  $query_prm = array(
    $position_logo_default,
    $position_informations_client_default,
    $position_title_invoice_default,
    $invoice_dateformat
  );

  require('inc/doquery.php');

  $message = 'Options facture enregistrées.';
}

$query = 'SELECT showinvoice_position_logo_default, showinvoice_position_client_information_default, showinvoice_position_title_invoice_default, showinvoice_dateformat
          FROM globalvariables';

require('inc/doquery.php');

$position_logo_default = $query_result[0]['showinvoice_position_logo_default'];
$position_informations_client_default = $query_result[0]['showinvoice_position_client_information_default'];
$position_title_invoice_default = $query_result[0]['showinvoice_position_title_invoice_default'];
$invoice_dateformat = $query_result[0]['showinvoice_dateformat'];
?>

<?php if (isset($message)) : ?>
  <?php print $message; ?>
<?php endif; ?>

<h2>Options factures</h2>

<form method="post" action="system.php">
  <table>
    <tr>
      <td>
        Options facture par défaut :
      </td>
    </tr>

    <tr>
      <td> Position Logo</td>
      <td>
        <select name="position-logo-default">
          <option value="1" <?php ($position_logo_default == 1) ? print 'selected' : print ''; ?>>Gauche</option>
          <option value="2" <?php ($position_logo_default == 2) ? print 'selected' : print ''; ?>>Millieu</option>
          <option value="3" <?php ($position_logo_default == 3) ? print 'selected' : print ''; ?>>Droite</option>
        </select>
      </td>
    </tr>

    <tr>
      <td> Position Informations Client</td>
      <td>
        <select name="position-informations-client-default">
          <option value="1" <?php ($position_informations_client_default == 1) ? print 'selected' : print ''; ?>>Gauche</option>
          <option value="2" <?php ($position_informations_client_default == 2) ? print 'selected' : print ''; ?>>Millieu</option>
          <option value="3" <?php ($position_informations_client_default == 3) ? print 'selected' : print ''; ?>>Droite</option>
        </select>
      </td>
    </tr>

    <tr>
      <td> Position Titre Facture</td>
      <td>
        <select name="position-title-invoice-default">
          <option value="1" <?php ($position_title_invoice_default == 1) ? print 'selected' : print ''; ?>>Gauche</option>
          <option value="2" <?php ($position_title_invoice_default == 2) ? print 'selected' : print ''; ?>>Millieu</option>
          <option value="3" <?php ($position_title_invoice_default == 3) ? print 'selected' : print ''; ?>>Droite</option>
        </select>
      </td>
    </tr>

    <tr>
      <td>
        Afficher Année Titre Facture
      </td>

      <td>
        <select name="invoice-dateformat">
          <option value="0" <?php ($invoice_dateformat == 0) ? print 'selected' : print ''; ?>>Par défaut</option>
          <option value="1" <?php ($invoice_dateformat == 1) ? print 'selected' : print ''; ?>>4 nombres</option>
          <option value="2" <?php ($invoice_dateformat == 2) ? print 'selected' : print ''; ?>>5 nombres</option>
        </select>
      </td>
    </tr>

    <tr>
      <td colspan="2" align="center">
        <input type="hidden" name="saveme" value="1">
        <input type=hidden name="systemmenu" value="<?php print $systemmenu; ?>">
        <input type="submit" value="Valider">
      </td>
    </tr>
  </table>
</form>
