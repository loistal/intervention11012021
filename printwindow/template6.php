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
  <div class="container-fluid">
    <section class="company">
      <div class="row">
        <div id="header-top-left" class="col-xs-4">
          <div class="company-informations">
            <?php if ($display_company_informations == 1): ?>
              <?php print $_SESSION['ds_companyinfo']; ?>
            <?php endif; ?>
          </div>
        </div>

        <div id="header-top-right" class="col-xs-8 img-right">
          <div class="company-logo">
            <?php if ($display_logo == 1): ?>
              <?php if (file_exists($ourlogofile)): ?>
                <img class="img-responsive" src="<?php print $ourlogofile; ?>">
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>

    <div class="row">
      <div id="informations-left" class="col-xs-4">
        <section class="invoice-header">
          <div class="invoice-informations">
            <?php if ($display_invoice_informations == 1): ?>
              <div class="title-top">
                <span><?php print datefix2($accountingdate); ?></span>
                  <?php
                  if ($_SESSION['ds_customname'] == 'SYNAPSE')
                  {
                    ?><div>
                    <span>Date de création : <?php print datefix2($invoicedate); ?></span>
                    </div><?php
                  }
                  else
                  {
                  ?><div>
                    <span>Échéance : <?php print datefix2($paybydate); ?></span>
                  </div><?php
                  }
                  ?>
                <?php if (isset($custominvoicedate) && $custominvoicedate != '0000-00-00' && $_SESSION['ds_customname'] != 'SYNAPSE'): ?>
                  <div>
                    <span><?php echo $_SESSION['ds_term_custominvoicedate'],' : ',datefix2($custominvoicedate); ?></span>
                  </div>
                <?php endif; ?>

                <?php if ($_SESSION['ds_showtimeprinted'] == 1): ?>
                  <div>
                    <span>
                      Le
                      <?php print datefix2($_SESSION['ds_curdate']); ?>
                      à <?php print $_SESSION['ds_curtime']; ?> par <?php print d_output($_SESSION['ds_initials']); ?>
                    </span>
                  </div>
                <?php endif; ?>

                <?php if (isset($employeename) && ($employeename != '')): ?>
                  <div>
                    <span><?php print $_SESSION['ds_term_servedby']; ?> : </span>
                    <span><?php print $employeename; ?></span>
                  </div>
                <?php endif; ?>

                <?php if (isset($deliverydate) && !empty($deliverydate) && $deliverydate != $accountingdate): ?>
                  <div>
                    <span>Livré le :</span>
                    <span><?php print datefix2($deliverydate); ?></span>
                  </div>
                <?php endif; ?>

                <?php if (isset($vesselname) && !empty($vesselname)): ?>
                  <div>
                    <span><?php echo $_SESSION['ds_term_localvessel']; ?> :</span>
                    <span><?php print $vesselname; ?></span>
                  </div>
                <?php endif; ?>

              </div>
            <?php endif; ?>
          </div>
        </section>
      </div>

      <div id="informations-center" class="col-xs-4">

      </div>

      <div id="informations-right" class="col-xs-4">
        <div class="client-informations">
          <?php if ($display_client_informations == 1): ?>
            <section class="client-info">

                <div class="client-name">
                <span>
                  <?php echo d_output($clientname);
                  if ($_SESSION['ds_customname'] == 'TT')
                  {
                    echo '<br>'.datefix($client_customdate1,'short');
                  }
                  ?>
                </span>
                </div>

              <div>
                <?php if (empty($extraname)): ?>
                  <span><?php print d_output($extraname); ?></span>
                <?php endif; ?>
                <?php if ($tahitinumber != '') { echo '<br>NT ',d_output($tahitinumber); } ?>
                <?php if ($telephone != '') { echo '<br>Tél ',d_output($telephone); } ?>
              </div>

              <?php if ($extraaddressid < 1): ?>
                <div>
                  <?php if (!empty($address) && isset($address)): ?>
                    <span><?php print d_output($address); ?></span>
                  <?php endif; ?>

                  <?php if (!empty($postaladdress) && isset($postaladdress)): ?>
                    <span><?php print d_output($postaladdress); ?></span>
                  <?php endif; ?>

                  <span><?php
                  if ($_SESSION['ds_customname'] != 'TEM') {
                  echo d_output($postalcode),' ',d_output($townA[$townid]); }
                  
                    if ($_SESSION['ds_customname'] != 'TEM') {
                    echo ' ',d_output($islandA[$town_islandidA[$townid]]); }
                  ?></span>
                  <?php
                  if ($countryname != '') { echo '<span>' . d_output($countryname),'</span>'; }
                  ?>
                  
                </div>
              <?php else: ?>
                <div>
                  <?php if (!empty($address) && isset($address)): ?>
                    <span><?php print $address; ?></span>
                  <?php endif; ?>
                </div>

                <div>
                  <?php if (!empty($row3['postalcode']) && isset($row3['postalcode'])): ?>
                    <span><?php print $row3['postalcode']; ?></span>
                  <?php endif; ?>
                </div>
              <?php endif; ?>

              <?php if (!empty($row3['islandname']) && isset($row3['islandname'])): ?>
                <div>
                  <span><?php print $row3['islandname']; ?></span>
                </div>
              <?php endif; ?>

              <?php if (!empty($row3['telephone']) && isset($row3['telephone'])): ?>
                <div>
                  <span><?php print $row3['telephone']; ?></span>
                </div>
              <?php endif;
              if ($_SESSION['ds_customname'] == 'BTS') # TODO option for showing telephone/email
              {
                if (isset($email) && !empty($email))
                {
                  echo '<div><span>'.$email.'</span></div>';
                }
              }
              ?>
            </section>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="row">
      <div id="title-left" class="col-xs-4">
        <section class="invoice-header">
          <div class="invoice-title">
            <?php if ($display_invoice_title == 1): ?>
              <div class="title">
                <?php print $typetext; ?>
                <?php print $format_invoiceid;
                if ($cancelledid == 2) { echo ' ARCHIVÉ(E)'; }
                ?>
              </div>
            <?php endif; ?>
          </div>
        </section>
      </div>

      <div id="title-center" class="col-xs-4">

      </div>

      <div id="title-right" class="col-xs-4">
        <div class="invoice-informations">
          <?php if ($display_invoice_informations == 1): ?>
            <?php if ($cancelledid == 1): ?>
              <div>
                <span>ANNULEE</span>
              </div>
            <?php endif; ?>

            <?php if (isset($reference) && !empty($reference)): ?>
              <div>
                <?php if (isset($_SESSION['ds_term_reference']) && !empty($_SESSION['ds_term_reference'])): ?>
                  <span><?php print $_SESSION['ds_term_reference']; ?> : </span>
                  <span><?php print $reference; ?></span>
                <?php else: ?>
                  <span>Réference : </span>
                  <span><?php print $reference; ?></span>
                <?php endif; ?>
              </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['ds_term_extraname']) && !empty($_SESSION['ds_term_extraname']) && isset($extraname) && !empty($extraname)): ?>
              <div>
                <span><?php print $_SESSION['ds_term_extraname']; ?> : </span>
                <span><?php print $extraname; ?></span>
              </div>
            <?php endif; ?>

            <?php if ($proforma == 1): ?>
              <div>PROFORMA</div>
            <?php endif; ?>

            <?php if (($deliverydate != $accountingdate) && ($_SESSION['ds_term_accountingdate'] != $_SESSION['ds_term_deliverydate']) && $_SESSION['ds_hidedeliverydate'] != 1): ?>
              <div>
                <span><?php print $_SESSION['ds_term_deliverydate']; ?> : </span>
                <span><?php print datefix2($deliverydate); ?></span>
              </div>
            <?php endif; ?>

            <?php if ($invoicetagid > 0): ?>
              <div>
                <?php if ($_SESSION['ds_term_invoicetag']): ?>
                  <span><?php print $_SESSION['ds_term_invoicetag']; ?> : </span>
                <?php else: ?>
                  <span>Tag :</span>
                <?php endif; ?>

                <span><?php print $invoicetagname; ?></span>
              </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['ds_term_field1']) && !empty($_SESSION['ds_term_field1']) && isset($field1) && !empty($field1)): ?>
              <div>
                <span"><?php print d_output($_SESSION['ds_term_field1']); ?> : </span>
                <span><?php print d_output($field1); ?></span>
              </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['ds_term_field2']) && !empty($_SESSION['ds_term_field2']) && isset($field2) && !empty($field2)): ?>
              <div>
                <span><?php print d_output($_SESSION['ds_term_field2']); ?> : </span>
                <span><?php print d_output($field2); ?></span>
              </div>
            <?php endif; ?>

            <?php if (isset($invoicecomment) && !empty($invoicecomment)): ?>
              <div>
                <?php print str_replace('§', '<br>', d_output($invoicecomment)); ?>
              </div>
            <?php endif; ?>

            <?php if (isset($invoicecomment2) && !empty($invoicecomment2)): ?>
              <div>
                <?php print str_replace('§', '<br>', d_output($invoicecomment2)); ?>
              </div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <section class="items">
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-condensed full-width-table">
            <tr>
              <?php 
              if ($_SESSION['ds_useitemadd']): ?>
                <th class="letters">Date</th>
                <th class="letters">Début</th>
                <th class="letters">Fin</th>
                <th class="letters">Employé</th>
              <?php endif; ?>
              <?php
              
              echo '<th class="letters"';
              echo ' colspan=2';
              echo '>Produit</th>';
              ?>
              <th class="numbers">Quantité</th>
              <?php
              if ($_SESSION['ds_uselocalbol'] == 2) { echo '<th class="numbers">Poids</th>'; }
              ?>

              <?php if ($isnotice && !$fake_isnotice || $hideprices == 1) {} else { ?>
                <th class="numbers">Prix UHT</th>

                <?php if ($totalrebate > 0): ?>
                  <?php
                  if ($_SESSION['ds_discount_line'] == 0) { echo '<th class="numbers">Remise'; }
                  ?>
                  <th class="numbers">PUHT Net</th>
                <?php endif; ?>

                <th class="numbers">TVA</th>
                <th class="numbers">Total</th>
                <?php
                if ($_SESSION['ds_customname'] == 'ANIMALICE') { echo '<th class="numbers">TTC</th>'; }
                ?>
              <?php } ?>
            </tr>

            <?php
            print $informationTable;
            print $informationTotalPages;
            ?>
          </table>
          
        </div>
      </div>
      <?php
          if (isset($split_invoice_show) && $split_invoice_show != '') { echo $split_invoice_show; }
          ?>
    <?php
    if ($show_sig)
    {
      echo $signature_show;
    }
    ?>
    </section>

    <?php if ($isnotice == 0 && $hideprices != 1) { ?>
    <div class="row">
    
      <div class="col-xs-4 table-responsive">
        <section class="items sums">
          <table class="table">
            <tr>
              <th class="text-right">Taux TVA</th>
              <th class="text-right">Base HT</th>
              <th class="text-right">Montant TVA</th>
            </tr>

            <?php foreach ($taxcodeA as $taxcodeid => $taxcode): ?>
              <?php if (isset($tvaM[$taxcode]) && $tvaM[$taxcode] > 0): ?>
                <tr>
                  <td class="numbers"> <?php print $taxcode; ?><span class="small-percent">%</span></td>
                  <td class="numbers"> <?php print myfix($tvaMt[$taxcode]); ?></td>
                  <td class="numbers"> <?php print myfix($tvaM[$taxcode]); ?> </td>
                </tr>
              <?php endif; ?>
            <?php endforeach; ?>
          </table>
        </section>
      </div>
    </div>
    <?php } ?>
    
    <div class="row">
      <div class="col-xs-12">
        <section class="terms">
          <div class="row">
            <div class="col-xs-12">
              <?php if ($totalpages == 1 || ($totalpages > 1 && $pagenumber == $totalpages)): ?>
                <?php if ($proforma == 0 && $isnotice == 0 && $hideprices == 0): ?>
                  <div>Arrêté la présente facture à la somme de : <?php print convertir($invoiceprice); ?> CFP.</div>
                <?php endif; ?>
              <?php endif; ?>

              <?php if ($isnotice && !$fake_isnotice || $hideprices == 1) {} else { ?>
                <div><?php print $informationIsNotice; ?></div>
                <div><?php echo $points_string; ?></div>
              <?php } ?>
              <?php if (!empty($_SESSION['ds_infofact']) && isset($_SESSION['ds_infofact'])): ?>
                <span style="text-align: justify"><?php print $_SESSION['ds_infofact']; ?></span>
              <?php endif; ?>
            </div>
          </div>
        </section>
      </div>


    </div>
<?php if ($_SESSION['ds_customname'] != 'Fenua Pharm' && $_SESSION['ds_customname'] != 'Pro Peinture') { ?>
    <div class="logo-tem">
      <img src="pics/logo.png" height="50">
    </div>
<?php } ?>
  </div>
</div>