<?php
/* Variables for display informations */
$display_logo = 1;

$display_company_informations = 1;

$display_client_informations = 1;

$display_invoice_title = 1;
$display_invoice_informations = 1;
?>

<?php require_once('printwindow/invoice_options.php'); ?>

<div class="main">
  <div class="company-logo">
    <?php if ($display_logo == 1): ?>
      <?php if (file_exists($ourlogofile)): ?>
        <div class="logo"><img src="<?php print $ourlogofile; ?>" style="max-height:125px;"></div>
      <?php endif; ?>
    <?php endif; ?>
  </div>

<?php if ($_SESSION['ds_customname'] != 'Fenua Pharm') { ?>
    <div class="logo-tem">
      <img src="pics/logo.png" height="50">
    </div>
<?php } ?>

<p class="xx-small" style="text-align: center; position: absolute; bottom: 10px; left: 100px; right: 100px;">
  <?php
    if (!$confirmed && $_SESSION['ds_quote_info'] != '') { echo $_SESSION['ds_quote_info']; }
    else { echo $_SESSION['ds_infofact']; }
  ?>
</p>

  <div class="company">
    <div class="client-informations">
      <?php if ($display_client_informations == 1): ?>
        <p>
          <?php echo d_output(d_decode($clientname)),' ',d_output($tahitinumber); ?>

          <?php if (isset($extraname) && empty($extraname)): ?>
            <?php print ' ' . d_output($extraname); ?>
          <?php endif; ?>
        </p>

        <?php if ($extraaddressid < 1): ?>
          <?php if (isset($address) && !empty($address)): ?>
            <p><?php print d_output($address); ?></p>
          <?php endif; ?>

          <?php if (isset($postaladdress) && !empty($postaladdress)): ?>
            <p><?php print d_output($postaladdress); ?></p>
          <?php endif; ?>

          <p><?php print d_output($postalcode); ?> <?php print d_output($townA[$townid]); ?></p>
        <?php else : ?>
          <p><?php print $address; ?></p>

          <p><?php print $postalcode; ?> ' ' <?php print $townname; ?></p>
        <?php endif; ?>

        <p><?php print $islandA[$town_islandidA[$townid]]; ?></p>

        <?php if (isset($telephone) && !empty($telephone)
          && $_SESSION['ds_customname'] != 'Alarme Scorpion'): ?>
          <p><?php print $telephone; ?> </p>
        <?php endif; ?>

        <?php if (isset($email) && !empty($email)
          && $_SESSION['ds_customname'] != 'Alarme Scorpion'): ?>
          <p><?php print $email; ?> </p>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>

  <div class="box2">
    
  </div>

  <div class="box3">
    <br>
    <table style="width:100%;">
      <tr>
        <th><?php echo $typetext; ?></th>
        <th>Date</th>
        <th>Échéance</th>
      </tr>
      <tr>
        <td><?php print $format_invoiceid; ?></td>
        <td><?php print datefix2($accountingdate); ?></td>
        <td><?php print datefix2($paybydate); ?></td>
      </tr>
    </table>
    
  </div>

  <div class="box1">
    <div class="company-informations">
      <?php if ($display_company_informations == 1): ?>
        <span><?php print $_SESSION['ds_companyinfo']; ?></span>
      <?php endif; ?>
    </div>
    <div class="invoice-informations">
      <?php if ($display_invoice_informations == 1): ?>

        <?php if ($_SESSION['ds_showtimeprinted'] == 1): ?>
          <br> <?php print datefix2($_SESSION['ds_curdate']); ?> <?php print $_SESSION['ds_curtime']; ?> par: <?php print d_output($_SESSION['ds_initials']); ?>
        <?php endif; ?>

        <?php if (isset($employeename) && ($employeename != '')): ?>
          <p>
            <b>
              <u><?php print $_SESSION['ds_term_servedby']; ?>:</u> <?php print $employeename; ?>
            </b>
          </p>
        <?php endif; ?>

        <?php if (isset($vesselname) && !empty($vesselname)): ?>
          <p>
            <b>
              Navire : <?php print $vesselname; ?>
            </b>
          </p>
        <?php endif; ?>

        <?php if ($cancelledid): ?>
          &nbsp; <font>ANNULEE</font><br>
        <?php endif; ?>

        <?php if (isset($reference) && !empty($reference)): ?>
          <?php if (isset($_SESSION['ds_term_reference']) && !empty($_SESSION['ds_term_reference'])): ?>
            <p>
              <b><?php print $_SESSION['ds_term_reference']; ?> : </b>
              <?php print $reference; ?>
            </p>
          <?php else: ?>
            <p>
              <b>Réference:</b>
              <?php print $reference; ?>
            </p>
          <?php endif; ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['ds_term_extraname']) && !empty($_SESSION['ds_term_extraname']) && isset($extraname) && !empty($extraname)): ?>
          <b><?php print $_SESSION['ds_term_extraname']; ?>:</b> <?php print $extraname; ?>
        <?php endif; ?>

        <?php if ((isset($reference) && !empty($reference)) || (isset($_SESSION['ds_term_extraname']) && !empty($_SESSION['ds_term_extraname']))): ?>
          <br>
        <?php endif; ?>

        <?php if ($proforma == 1): ?>
          &nbsp; <b>PROFORMA</b>
        <?php endif; ?>

        <?php if (($deliverydate != $accountingdate) && ($_SESSION['ds_term_accountingdate'] != $_SESSION['ds_term_deliverydate']) && $_SESSION['ds_hidedeliverydate'] != 1): ?>
          &nbsp;
          <b><?php print $_SESSION['ds_term_deliverydate']; ?> </b> : <?php print datefix2($deliverydate); ?>
        <?php endif; ?>

        <?php if ($invoicetagid > 0): ?>
          &nbsp;
          <b>
            <?php if ($_SESSION['ds_term_invoicetag']): ?>
              <?php print $_SESSION['ds_term_invoicetag']; ?>:
            <?php else: ?>
              Tag:
            <?php endif; ?>
            <br>
          </b>
          <?php print $invoicetagname; ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['ds_term_field1']) && !empty($_SESSION['ds_term_field1']) && isset($field1) && !empty($field1)): ?>
          <?php print d_output($_SESSION['ds_term_field1']); ?> : <?php print d_output($field1); ?>
          <br>
        <?php endif; ?>

        <?php if (isset($_SESSION['ds_term_field2']) && !empty($_SESSION['ds_term_field2']) && isset($field2) && !empty($field2)): ?>
          <?php print d_output($_SESSION['ds_term_field2']); ?> : <?php print d_output($field2); ?>
          <br>
        <?php endif; ?>

        <?php if (isset($invoicecomment) && !empty($invoicecomment)): ?>
          <?php print str_replace('§', '<br>', d_output($invoicecomment)); ?>
        <?php endif; ?>

        <?php if (isset($invoicecomment2) && !empty($invoicecomment2)): ?>
          <br>
          <span class="tiny"> <?php print str_replace('§', '<br>', d_output($invoicecomment2)); ?> </span>
        <?php endif; ?>
      <?php endif; ?>

      <?php /*<p>Devis N°<?php print $format_invoiceid; ?></p>
      <p>BC N° <?php print $field1; ?></p> */ ?>

    </div>
  </div>

  <div class="items">
    <table class="report" style="width: 100%">
      <tr>
        <?php if ($_SESSION['ds_useitemadd']): ?>
          <td>
            <b> Date </b>
          </td>

          <td>
            <b> Début </b>
          </td>

          <td>
            <b> Fin </b>
          </td>

          <td>
            <b>Employé<b>
          </td>
        <?php endif; ?>

        <td>
          <b> Produit </b>
        </td>

        <td>
          <b> Quantité </b>
        </td>

        <?php if (!$isnotice): ?>
          <td colspan="2"><b>Prix UHT</b></td>

          <?php if ($totalrebate > 0): ?>
            <td><b>Remise</b>
            <?php
            if ($_SESSION['ds_customname'] == 'Vaimato') { echo '<td><b>OP'; }
            ?>
          <?php endif; ?>

          <td>
            <b>TVA</b>
          </td>
          <td>
            <b>Total HT</b>
          </td>
        <?php endif; ?>
      </tr>

      <?php print $informationTable; ?>
      <?php print $informationTotalPages; ?>
    </table>

    <?php if ($totaltva > 0 && ($totalpages == 1 || $pagenumber == $totalpages)): ?>
      <br>

      <table class="report">
        <tr>
          <td><b>Taux TVA</b></td>
          <td><b>Base HT</b></td>
          <td><b>Montant TVA</b></td>
        </tr>

        <?php foreach ($taxcodeA as $taxcodeid => $taxcode): ?>
          <?php if (isset($tvaM[$taxcode]) && $tvaM[$taxcode] > 0): ?>
            <tr>
              <td align="right"> <?php print $taxcode; ?><span class="small-percent">%</span></td>
              <td align="right">  <?php print myfix($tvaMt[$taxcode]); ?></td>
              <td align="right"> <?php print myfix($tvaM[$taxcode]); ?> </td>
            </tr>
          <?php endif; ?>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>

   

    <?php if ($totalpages == 1 || ($totalpages > 1 && $pagenumber == $totalpages)): ?>
      <?php if ($proforma == 0 && $isnotice == 0): ?>
        <br>
        <p> Arrêté la présente facture à la somme de : <?php print convertir($invoiceprice); ?> CFP.</p>
      <?php endif; ?>
    <?php endif; ?>
    
    <?php if ($isnotice == 0): ?>
      <?php print $informationIsNotice; ?>
    <?php endif; ?>
    
    <p>
      En votre aimable règlement :
      <br>
      <strong>Banque de Tahiti: 12239 - 00011 - 21531401000 - 14</strong>
    </p>    
  </div>
</div>