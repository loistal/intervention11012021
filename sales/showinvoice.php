<?php 
$ds_customname= strtolower($_SESSION['ds_customname']);
$ds_hidedefaultinvoice = $_SESSION['ds_hidedefaultinvoice'];
$ds_invoicetemplate = $_SESSION['ds_invoicetemplate'];

$custominvoice_exists = 0;
?>

<h2>Afficher facture:</h2>

<form method="post" action="printwindow.php" target="_blank">
  <table>
    <tr>
      <td>Numéro:</td>
      <td>
        <input autofocus type="text" STYLE="text-align:right" name="invoiceid" size=10>
      </td>
    </tr>
    
    <?php
    if ($_SESSION['ds_customname'] == 'TEM'||1==1
    || $_SESSION['ds_customname'] == 'TERE UTA'
    || $_SESSION['ds_customname'] == 'Aquasplash'
    || $_SESSION['ds_customname'] == 'Fenua AC Cleaner') # TODO option
    {
      echo '<tr><td>Fusion des factures:<td><input type="text" STYLE="text-align:right" name="invoicemerge" size=20>
      <span class="alert">BETA Lister les factures à fusionner</span>';
    }
    ?>

    <tr>
      <td>Page:</td>
      <td>
        <input type="text" STYLE="text-align:right" name="pagenumber" size=5 value="1">
      </td>
    </tr>

    <tr>
      <td>Lignes / Page:</td>
      <td>
        <input type="text" STYLE="text-align:right" name="linesperpage" size=5 value="<?php echo $_SESSION['ds_invoicelines']; ?>">
      </td>
    </tr>

        <input type="hidden" name="itemfontsize" value="100">

    <tr>
      <td>Masquer les remises:</td>
      <td><input type="checkbox" name="hidediscount" value="1"></td>
    </tr>
    <tr>
      <td>Masquer les prix:</td>
      <td><input type="checkbox" name="hideprices" value="1"></td>
    </tr>

    <tr>
      <td>Décaler:</td>
      <td>
        <select name="offset">
          <option value=0></option>
          <?php
          $testvar = $_SESSION['ds_vaimato_decaler'];

          for ($i = -10; $i >= -100; $i -= 10) {
            echo '<option value="' . $i . '"';
            if ($i == $testvar) {
              echo ' selected';
            }
            echo '>' . $i . '</option>';
          }
          ?>
        </select>
      </td>
    </tr>
   
    <?php 
                /*
                if( file_exists('printwindow/' . $ds_customname  .'showinvoiceretailsale.php'))
                {           
                  echo '<option value=100>Facture Aming vente détail</option>';
                }
                if( file_exists('printwindow/' . $ds_customname  .'showinvoicewholesale.php'))
                {           
                  echo '<option value=101>Facture Aming vente gros</option>';
                }    
                if( file_exists('printwindow/' . $ds_customname  .'showinvoicereceipt.php'))
                {           
                  echo '<option value=102>Ticket de caisse</option>';
                }
*/

# refactor note: horrible structure, remove open/close php tags, etc
$defaultinvoice = $_SESSION['ds_invoicetemplate']+0;
if ($_SESSION['ds_custominvoiceisdefault'] == 1 && file_exists('custom/' . $ds_customname . 'showinvoice.php')) { $defaultinvoice = 99; } # TODO make 100 and 101 possible # TODO also move all file exists to setaccess.php
?>
<tr>
  <td>Modèle:</td>
  <td>
    <select name="template">
      <?php
      if ($_SESSION['ds_hidedefaultinvoice'] != 1)
      {
        echo '<option value="1">Basique</option>';

        if ($defaultinvoice == 2) { echo '<option value="2" selected>Classique</option>'; }
        else { echo '<option value="2">Classique</option>'; }
/*
        if ($defaultinvoice == 3): ?>
          <option value="3" selected>3: Bannière</option>
        <?php else: ?>
          <option value="3">3: Bannière</option>
        <?php endif; ?>

        <?php if ($defaultinvoice == 4): ?>
          <option value="4" selected>4: Sans logo</option>
        <?php else: ?>
          <option value="4">4: Sans logo</option>
        <?php endif; ?>

        <?php if ($defaultinvoice == 5): ?>
          <option value="5" selected>5: Experimental</option>
        <?php else: ?>
          <option value="5">5: Experimental</option>
        <?php endif; ?>

        <?php*/ if ($defaultinvoice == 6): ?>
          <option value="6" selected>2017</option>
        <?php else: ?>
          <option value="6">2017</option>
        <?php endif; ?>
        
        <?php if ($defaultinvoice == 7): ?>
          <option value="7" selected>Standard</option>
        <?php else: ?>
          <option value="7">Standard</option>
        <?php endif;
      }

      # TODO nameing of the custom invoices
      if ($_SESSION['ds_customname'] != "" && file_exists(strtolower('custom/' . $_SESSION['ds_customname']) . 'showinvoice.php'))
      {
        echo '<option value=99'; if ($defaultinvoice == 99) { echo ' selected'; }
        echo '>', $_SESSION['ds_customname'], '</option>';
        $custominvoice_exists = 1;
      }
      
      if ($_SESSION['ds_customname'] != "" && file_exists(strtolower('custom/' . $_SESSION['ds_customname']) . 'showinvoice0.php'))
      {
        echo '<option value=100'; if ($defaultinvoice == 100) { echo ' selected'; }
        echo '>', $_SESSION['ds_customname'], '(alt)</option>';
        $custominvoice_exists = 1;
      }
      
      if ($_SESSION['ds_customname'] != "" && file_exists(strtolower('custom/' . $_SESSION['ds_customname']) . 'showinvoice1.php'))
      {
        echo '<option value=101'; if ($defaultinvoice == 101) { echo ' selected'; }
        echo '>', $_SESSION['ds_customname'], '(alt2)</option>';
        $custominvoice_exists = 1;
      }
        
      ?>
    </select>
  </td>
</tr>

    <?php if ($_SESSION['ds_allowinvoiceshare'] == 1): ?>
      <tr>
        <td>Partager:</td>
        <td><input type=checkbox name="shareinvoice" value=1></td>
      </tr>
    <?php endif; ?>
    
    <?php if ($custominvoice_exists): ?>
      <tr>
        <td><input type=checkbox name="custominvoice_changefields" value=1></td>
        <td>Modifier champs sur facture customisé</td>
      </tr>
    <?php endif; ?>

    <tr>
      <td colspan="2" align="center">
        <input type="hidden" name="report" value="showinvoice">
        <input type="submit" value="Valider">
      </td>
    </tr>
  </table>
</form>

<br><br>

<?php
/*

Client
- type de facture  (Tout / avoir / Facture)
- Etat facture (pouvoir afficher que les confirmées)

*/
?>

<h2>Afficher multiples factures (format simplifié):</h2>
<form method="post" action="reportwindow.php" target="_blank"><table>
<table><?php
echo '<tr><td>' . d_trad('startdate:') . '</td><td>';
$datename = 'startdate'; if ($_SESSION['ds_restrict_sales_reports']) { $dp_datepicker_min = (mb_substr($_SESSION['ds_curdate'],0,4)-1).'-01-01'; }
require('inc/datepicker.php');
echo '<tr><td>' . d_trad('stopdate:') . '</td><td>';
$datename = 'stopdate';
require('inc/datepicker.php');
if ($_SESSION['ds_restrict_sales_reports'] == 0)
{
  echo '<tr><td align=right><input type=checkbox name="bynumber" value=1></td><td>' . d_trad('bynumber:') . '<input type="text" STYLE="text-align:right" name="startid" size=10>&nbsp;'. d_trad('to') . '&nbsp;<input type="text" STYLE="text-align:right" name="stopid" size=10>&nbsp(' . d_trad('notbydate') . ')</td></tr>';
}
?><tr><td><?php
require('inc/selectclient.php');
echo '<tr><td>' . d_trad('type:') . '</td><td><select name="invoicetype">
<option value=-1>' . d_trad('selectall') . '</option>
<option value=1>' . d_trad('invoice') . '</option>
<option value=2>' . d_trad('isreturn') . '</option>
<option value=3>' . d_trad('proforma') . '</option>
<option value=4>' . $_SESSION['ds_term_invoicenotice'] . '</option>
<option value=5>' . d_trad('isreturnparam',$_SESSION['ds_term_invoicenotice']) . '</option>
</select></td></tr>';
if ($_SESSION['ds_term_localvessel'] != '')
{
  $dp_itemname = 'localvessel'; $dp_description = $_SESSION['ds_term_localvessel']; $dp_allowall = 1; $dp_selectedid = -1;
  require('inc/selectitem.php');
  echo '<tr><td>Livraison:<td><select name="invoice_grouped">
  <option value=0>' . d_trad('selectall') . '</option>
  <option value=1>Non livrées</option>
  <option value=2>Livrées</option>
  </select>';
}
$dp_itemname = 'user'; $dp_description = d_trad('user'); $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1; 
require('inc/selectitem.php');
?>
<tr><td>Dupliquer:<td><input type=checkbox name="duplicate" value=1>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="showinvoices">
<input type="submit" value="Valider"></td></tr>
</table></form>
