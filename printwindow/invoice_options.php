<script type="text/javascript" src="jq/jquery.js"></script>

<script type="text/javascript">
  $(document).ready(function () {
    <?php if ($template == 6 && $_SESSION['ds_showinvoice_modifiy_options'] == 0): ?>

    /* Options for Logo */
    var $company_informations = $('#header-top-left').html();
    var $company_logo = $('#header-top-right').html();

    switch ($('#position-logo').val()) {
      case 'left':
        $('#header-top-left').removeClass('col-xs-4');
        $('#header-top-left').removeClass('col-xs-8');
        $('#header-top-left').removeClass('img-left');
        $('#header-top-left').removeClass('img-right');

        $('#header-top-right').removeClass('col-xs-4');
        $('#header-top-right').removeClass('col-xs-8');
        $('#header-top-right').removeClass('img-left');
        $('#header-top-right').removeClass('img-right');

        $('#header-top-left').addClass('col-xs-8');
        $('#header-top-left').addClass('img-left');

        $('#header-top-right').addClass('col-xs-4');

        $('#header-top-left').html($company_logo);
        $('#header-top-right').html($company_informations);
        break;
      case 'center':
        $('#header-top-left').removeClass('col-xs-4');
        $('#header-top-left').removeClass('col-xs-8');
        $('#header-top-left').removeClass('img-left');
        $('#header-top-left').removeClass('img-right');

        $('#header-top-right').removeClass('col-xs-4');
        $('#header-top-right').removeClass('col-xs-8');
        $('#header-top-right').removeClass('img-left');
        $('#header-top-right').removeClass('img-right');

        $('#header-top-left').addClass('col-xs-4');
        $('#header-top-right').addClass('col-xs-8');
        $('#header-top-right').addClass('img-left');

        $('#header-top-left').html($company_informations);
        $('#header-top-right').html($company_logo);
        break;
      case 'right':
        $('#header-top-left').removeClass('col-xs-4');
        $('#header-top-left').removeClass('col-xs-8');
        $('#header-top-left').removeClass('img-left');
        $('#header-top-left').removeClass('img-right');

        $('#header-top-right').removeClass('col-xs-4');
        $('#header-top-right').removeClass('col-xs-8');
        $('#header-top-right').removeClass('img-left');
        $('#header-top-right').removeClass('img-right');

        $('#header-top-left').addClass('col-xs-4');
        $('#header-top-right').addClass('col-xs-8');
        $('#header-top-right').addClass('img-right');

        $('#header-top-left').html($company_informations);
        $('#header-top-right').html($company_logo);
        break;
    }

    $('#position-logo').change(function () {
      switch ($(this).val()) {
        case 'left':
          $('#header-top-left').removeClass('col-xs-4');
          $('#header-top-left').removeClass('col-xs-8');
          $('#header-top-left').removeClass('img-left');
          $('#header-top-left').removeClass('img-right');

          $('#header-top-right').removeClass('col-xs-4');
          $('#header-top-right').removeClass('col-xs-8');
          $('#header-top-right').removeClass('img-left');
          $('#header-top-right').removeClass('img-right');

          $('#header-top-left').addClass('col-xs-8');
          $('#header-top-left').addClass('img-left');

          $('#header-top-right').addClass('col-xs-4');

          $('#header-top-left').html($company_logo);
          $('#header-top-right').html($company_informations);
          break;
        case 'center':
          $('#header-top-left').removeClass('col-xs-4');
          $('#header-top-left').removeClass('col-xs-8');
          $('#header-top-left').removeClass('img-left');
          $('#header-top-left').removeClass('img-right');

          $('#header-top-right').removeClass('col-xs-4');
          $('#header-top-right').removeClass('col-xs-8');
          $('#header-top-right').removeClass('img-left');
          $('#header-top-right').removeClass('img-right');

          $('#header-top-left').addClass('col-xs-4');

          $('#header-top-right').addClass('col-xs-4');
          $('#header-top-right').addClass('img-left');

          $('#header-top-left').html($company_informations);
          $('#header-top-right').html($company_logo);
          break;
        case 'right':
          $('#header-top-left').removeClass('col-xs-4');
          $('#header-top-left').removeClass('col-xs-8');
          $('#header-top-left').removeClass('img-left');
          $('#header-top-left').removeClass('img-right');

          $('#header-top-right').removeClass('col-xs-4');
          $('#header-top-right').removeClass('col-xs-8');
          $('#header-top-right').removeClass('img-left');
          $('#header-top-right').removeClass('img-right');

          $('#header-top-left').addClass('col-xs-4');

          $('#header-top-right').addClass('col-xs-8');
          $('#header-top-right').addClass('img-right');

          $('#header-top-left').html($company_informations);
          $('#header-top-right').html($company_logo);
          break;
      }
    });

    /* Options Informations Client */
    var $invoice_informations = $('#informations-left').html();
    var $empty = $('#informations-center').html();
    var $client_informations = $('#informations-right').html();

    switch ($('#position-informations-client').val()) {
      case 'left':
        $('#informations-left').html($client_informations);
        $('#informations-center').html($invoice_informations);
        $('#informations-right').html($empty);
        break;

      case 'center':
        $('#informations-left').html($invoice_informations);
        $('#informations-center').html($client_informations);
        $('#informations-right').html($empty);
        break;

      case 'right':
        $('#informations-left').html($invoice_informations);
        $('#informations-center').html($empty);
        $('#informations-right').html($client_informations);
        break;
    }

    $('#position-informations-client').change(function () {
      switch ($(this).val()) {
        case 'left':
          $('#informations-left').html($client_informations);
          $('#informations-center').html($invoice_informations);
          $('#informations-right').html($empty);
          break;

        case 'center':
          $('#informations-left').html($invoice_informations);
          $('#informations-center').html($client_informations);
          $('#informations-right').html($empty);
          break;

        case 'right':
          $('#informations-left').html($invoice_informations);
          $('#informations-center').html($empty);
          $('#informations-right').html($client_informations);
          break;
      }
    });

    /* Options Title Invoice */
    var $title_invoice = $('#title-left').html();
    var $empty = $('#title-center').html();
    var $invoice_informations_title = $('#title-right').html();

    switch ($('#position-title-invoice').val()) {
      case 'left':
        $('#title-left').html($title_invoice);
        $('#title-center').html($empty);
        $('#title-right').html($invoice_informations_title);
        break;
      case 'center':
        $('#title-left').html($empty);
        $('#title-center').html($title_invoice);
        $('#title-right').html($invoice_informations_title);
        break;
      case 'right':
        $('#title-left').html($empty);
        $('#title-center').html($invoice_informations_title);
        $('#title-right').html($title_invoice);
        break;
    }

    $('#position-title-invoice').change(function () {
      switch ($(this).val()) {
        case 'left':
          $('#title-left').html($title_invoice);
          $('#title-center').html($empty);
          $('#title-right').html($invoice_informations_title);
          break;
        case 'center':
          $('#title-left').html($empty);
          $('#title-center').html($title_invoice);
          $('#title-right').html($invoice_informations_title);
          break;
        case 'right':
          $('#title-left').html($invoice_informations_title);
          $('#title-center').html($empty);
          $('#title-right').html($title_invoice);
          break;
      }
    });

    <?php endif; ?>

    /* Options Font Size */
    $('#font-size-invoice-table').change(function () {
      $('table tr td').css('font-size', $(this).val() + 'px');
    });
  });
</script>


<section class="share">
  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-9">
        <?php if ($template == 6): ?>
          Position Logo :
          <select <?php ($_SESSION['ds_showinvoice_modifiy_options'] == 1) ? print 'disabled  class="disabled"' : print ''; ?> id="position-logo">
            <option value="left" <?php ($_SESSION['ds_showinvoice_position_logo_default'] == 1) ? print 'selected' : print ''; ?>>Gauche</option>
            <option value="center" <?php ($_SESSION['ds_showinvoice_position_logo_default'] == 2) ? print 'selected' : print ''; ?>>Millieu</option>
            <option value="right" <?php ($_SESSION['ds_showinvoice_position_logo_default'] == 3) ? print 'selected' : print ''; ?>>Droite</option>
          </select>

          <br>

          Position Informations Client :
          <select  <?php ($_SESSION['ds_showinvoice_modifiy_options'] == 1) ? print 'disabled class="disabled"' : print ''; ?> id="position-informations-client">
            <option value="left" <?php ($_SESSION['ds_showinvoice_position_client_information_default'] == 1) ? print 'selected' : print ''; ?>>Gauche</option>
            <option value="center" <?php ($_SESSION['ds_showinvoice_position_client_information_default'] == 2) ? print 'selected' : print ''; ?>>Millieu</option>
            <option value="right" <?php ($_SESSION['ds_showinvoice_position_client_information_default'] == 3) ? print 'selected' : print ''; ?>>Droite</option>
          </select>

          <br>

          Position Titre Facture :
          <select  <?php ($_SESSION['ds_showinvoice_modifiy_options'] == 1) ? print 'disabled class="disabled"' : print ''; ?> id="position-title-invoice">
            <option value="left" <?php ($_SESSION['ds_showinvoice_position_title_invoice_default'] == 1) ? print 'selected' : print ''; ?>>Gauche</option>
            <option value="center" <?php ($_SESSION['ds_showinvoice_position_title_invoice_default'] == 2) ? print 'selected' : print ''; ?>>Millieu</option>
            <option value="right" <?php ($_SESSION['ds_showinvoice_position_title_invoice_default'] == 3) ? print 'selected' : print ''; ?>>Droite</option>
          </select>

          <br>

        <?php endif; ?>

        Taille Police Lignes de Produit : &nbsp;
        <select id="font-size-invoice-table">
          <option value="9">9</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14" selected>14</option>
          <option value="15">15</option>
          <option value="16">16</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="19">19</option>
          <option value="20">20</option>
        </select>
      </div>

      <div class="col-xs-3">
        <a href="javascript:window.print()" class="btn btn-success">Imprimer cette facture</a>
      </div>
    </div>
  </div>
</section>
