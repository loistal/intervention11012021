<?php

$tva_encaissement = $_SESSION['ds_tva_encaissement'];
$prorata = 0; if (isset($_POST['prorata']) && $_POST['prorata'] > 0 && $_POST['prorata'] <= 100) { $prorata = $_POST['prorata']; }

if ($_SESSION['ds_userid'] > 0 && file_exists('inc/doquery.php'))
{
  $datename = 'startdate';
  require('inc/datepickerresult.php');
  $datename = 'stopdate';
  require('inc/datepickerresult.php');
  $year = mb_substr($startdate, 0, 4) + 0;
  $startdate = d_builddate(1, 1, $year);
  $stopdate = d_builddate(31, 8, $year);

  $query = 'select * from companyinfo where companyinfoid=1';
  $query_prm = array();
  require('inc/doquery.php');
  $numero_tahiti = $query_result[0]['idtahiti'];
  $nom_raison_sociale = $query_result[0]['companyname'];
  $activite_exercee = $query_result[0]['infoactivity'];
  $telephone = $query_result[0]['infophonenumber'];
  $adresse = $query_result[0]['infoaddress1'];
  $adresse_mail_societe_ou_representant_legal = $query_result[0]['infoaddress2'];
  $boite_postale = $query_result[0]['postaladdress'];
  $code_postale = $query_result[0]['postalcode'];
  $commune = $query_result[0]['infocity'];

  require('declaration/10X0_calc.php');

  # specific to 1020
  $f[3] = d_multiply($f[1], 5);
  $f[3] = myround(d_divide($f[3], 100, 2)); # hardcode 5%
  $f[4] = d_multiply($f[2], 5);
  $f[4] = myround(d_divide($f[4], 100, 2)); # hardcode 5%
  $f[5] = d_add($f[3], $f[4]);
  $f[6] = $f[10];
  #$f[7] = 0; 2016 09 26 reading from 207
  $f[7] = $f[207];
  $f[8] = d_add($f[6], $f[7]);
  $f[11] = d_subtract($f[5], $f[8]);
  ### prorata
  if ($prorata > 0)
  {
    $kladd = d_multiply($f[8],$prorata);
    $kladd = d_divide($kladd, 100, 0);
    $f[11] = d_subtract($f[11], $kladd);
  }    
  ###

  if (d_compare($f[11], 0) == -1)
  {
    $f[9] = d_abs($f[11]);
    $f[11] = 0;
  }

  #$f[19] = $f[9]; 2016 09 27
  $f[19] = 0;

  echo '<!doctype html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>DECL. 1020</title>
    <link rel="stylesheet" href="declaration/bootstrap.css">
    <link rel="stylesheet" href="declaration/1020.css">
    <link rel="stylesheet" href="declaration/print.css">
  </head>
  <body>';
}
else
{
  $duedate = '';
  $period = '<br>';

  #company info
  $numero_tahiti = "";
  $nom_raison_sociale = "";
  $activite_exercee = "";
  $telephone = "";
  $adresse = "";
  $adresse_mail_societe_ou_representant_legal = "";
  $boite_postale = "";
  $code_postale = "";
  $commune = "";

  $f = array();

  echo '<!doctype html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>DECL. 1020</title>
    <link rel="stylesheet" href="declaration/bootstrap.css">
    <link rel="stylesheet" href="declaration/1020.css">
    <link rel="stylesheet" href="declaration/print.css">
  </head>
  <body>';
}
?>

<section id="share">
  <a href="javascript:window.print()" class="btn btn-success">Imprimer cette déclaration</a>
</section>

<div id="main">

  <div class="container-fluid">
    <div class="section-top-header">
      <div class="invoiceid pull-right">
        DECL. 1020
      </div>
    </div>

    <div class="section-header">
      <div class="row">
        <div class="header">
          <div class="col-xs-4">
            <div class="logo">
              <img class="img-responsive" src="../pics/dicp.jpg">
            </div>
          </div>

          <div class="col-xs-4">
            <div class="title-invoice">
              <h1>Taxe sur la valeur ajoutée</h1>

              <h2>Régime simplifié</h2>

              <div class="separator"></div>
              <p class="text-center">(Déclaration d'acompte)<br> à
                déposer au plus tard le 30 septembre de l'année</p>
            </div>
          </div>

          <div class="col-xs-4">
            <div class="declaration-period">
              <div class="title">Période de déclaration</div>

              <div class="row">
                <div class="col-xs-6">
                  <label>Période du</label>
                </div>

                <div class="col-xs-6">
                  <!-- <div class="value-input"> -->
                  <?php print datefix2($startdate); ?>
                  <!-- </div> -->
                </div>
              </div>

              <div class="row">
                <div class="col-xs-6">
                  <label>au</label>
                </div>

                <div class="col-xs-6">
                  <!-- <div class="value-input"> -->
                  <?php print datefix2($stopdate); ?>
                  <!-- </div> -->
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-6">
      <div class="row">
        <div class="col-xs-12">
          <h3 class="title-receipts">
            Recette des impôts
          </h3>
        </div>

        <div class="col-xs-5">
          <label>N°TAHITI</label>
        </div>

        <div class="col-xs-7">
          <div class="value-input"><?php print $numero_tahiti; ?></div>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-5">
          <label>Nom/Raison sociale</label>
        </div>

        <div class="col-xs-7">
          <div class="value-input"><?php print $nom_raison_sociale; ?></div>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-5">
          <label>Activité exercée</label>
        </div>

        <div class="col-xs-7">
          <div class="value-input"><?php print $activite_exercee; ?></div>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-5">
          <label>Téléphone</label>
        </div>

        <div class="col-xs-7">
          <div class="value-input"><?php print $telephone; ?></div>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-5">
          <label>Adresse</label>
        </div>

        <div class="col-xs-7">
          <div class="value-input"><?php print $adresse; ?></div>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-5">
          <label>Adresse mail de la societe ou du réprésentant légal</label>
        </div>

        <div class="col-xs-7">
          <div
            class="value-input"><?php print $adresse_mail_societe_ou_representant_legal; ?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-4">
      <div class="row">
        <div class="col-xs-6">
          <label>Boîte postale</label>
        </div>

        <div class="col-xs-6">
          <div class="value-input"><?php print $boite_postale; ?></div>
        </div>
      </div>
    </div>

    <div class="col-xs-4">
      <div class="row">
        <div class="col-xs-6">
          <label>Code postale</label>
        </div>
        <div class="col-xs-6">
          <div class="value-input"><?php print $code_postale; ?></div>
        </div>
      </div>
    </div>

    <div class="col-xs-4">
      <div class="row">
        <div class="col-xs-3">
          <label>Commune</label>
        </div>

        <div class="col-xs-9">
          <div class="value-input"><?php print $commune; ?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <table class="table-bordered full-width-table table-condensed">
        <tbody>
        <tr>
          <td colspan="7" class="title is-white">A - TVA exigible</td>
        </tr>

        <tr>
          <td class="title text-initial" colspan="3">Chiffre d'affaire (Hors TVA)</td>
          <td class="title text-initial" colspan="3">Coefficient de l'entreprise</td>
          <td class="title text-initial">Taxe due</td>
        </tr>

        <tr>
          <td class="number is-white" style="width: 5%;">01</td>
          <td>Ventes</td>
          <td class="text-right" style="width: 20%;"><?php print myfix($f[1]); ?></td>

          <td class="text-center">X</td>
          <td class="text-center">5%</td>

          <td class="number is-white" style="width: 5%;">03</td>
          <td class="text-right" style="width: 20%;"><?php print myfix($f[3]); ?></td>
        </tr>

        <tr>
          <td class="number is-white">02</td>
          <td>Prestations de services</td>
          <td class="text-right"><?php print myfix($f[2]); ?></td>

          <td class="text-center">X</td>
          <td class="text-center">5%</td>

          <td class="number is-white">04</td>
          <td class="text-right"><?php print myfix($f[4]); ?></td>
        </tr>

        <tr>
          <td class="number is-white">05</td>
          <td class="text-right" colspan="4">
            <strong>Total (lignes 03 + 04) :</strong>
          </td>

          <td colspan="2" class="text-right"><?php print myfix($f[5]); ?></td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <table class="table-bordered full-width-table table-condensed">
        <tbody>
        <tr>
          <td colspan="3" class="title is-white">B - TVA deductible</td>
        </tr>

        <tr>
          <td class="number is-white" style="width: 5%;">06</td>
          <td>TVA sur biens consituant des immobilisations</td>
          <td class="text-right" style="width: 20%;"><?php print myfix($f[6]); ?></td>
        </tr>

        <tr>
          <td class="number is-white">07</td>
          <td>Crédit apparaissant sur la précédente déclaration</td>
          <td class="text-right"><?php print myfix($f[7]); ?></td>
        </tr>
        </tbody>
      </table>

      <table style="margin-top: 0; border-top: 0; border-bottom: 0;" class="table-bordered full-width-table">
        <tbody>
        <tr>
          <td style="width: 40%; border-top: 0; border-bottom: 0; font-style: italic;">Indiquer ici le pourcentage de déduction applicable pour la période</td>
          <td class="text-right" style="width: 20%;  border-top: 0; border-bottom: 0;"><?php print $prorata; ?> %</td>
          <td style="width: 40%;  border-top: 0; border-bottom: 0;"></td>
        </tr>
        </tbody>
      </table>

      <table style="margin-top: 0; border-top: 0; border-bottom: 0;" class="table-bordered full-width-table table-condensed">
        <tbody>
        <tr>
          <td class="number is-white" style="width: 5%;">08</td>
          <td class="text-right">
            <strong>Total (lignes 06 + 07) :</strong>
          </td>
          <td class="text-right" style="width: 20%;"><?php print myfix($f[8]); ?></td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <table class="table-bordered full-width-table table-condensed">
        <tbody>
        <tr>
          <td colspan="3" class="title is-white">C - Crédit</td>
          <td colspan="3" class="title is-white">D - TVA à payer</td>
        </tr>

        <tr>
          <td class="number is-white" style="width: 5%;">09</td>

          <td>
            <span class="text-uppercase"><strong>Credit de TVA<br> (lignes 08 - 05)</strong></span>
          </td>

          <td class="text-right" style="width: 20%;"><?php print myfix($f[9]); ?></td>

          <td class="number is-white" style="width: 5%;">11</td>

          <td>
              <span class="text-uppercase">
                <strong>TVA nette due <br> (lignes 08 - 05)</strong>
              </span>
          </td>

          <td class="text-right" style="width: 20%;"><?php print myfix($f[11]); ?></td>
        </tr>

        <tr>
          <td class="number is-white">19</td>

          <td>
            Remboursement demandé :
            <br> (Demande de remboursement ci-jointe)
          </td>

          <td class="text-right"><?php print myfix($f[19]); ?></td>
        <tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-6">
      <h3 class="title-paiement">Moyens de paiement</h3>

      <div class="checkbox">
        <label>
          <input type="checkbox"> Chèque (à l'ordre du Trésor Public)
        </label>
      </div>

      <div class="checkbox">
        <label>
          <input type="checkbox"> Espèces
        </label>
      </div>

      <div class="checkbox">
        <label>
          <input type="checkbox"> Virement bancaire (Préciser le N°TAHITI, la
          taxe payée et la période concernée
          sans omettre de déposer ou poster votre déclaration)
        </label>
      </div>
    </div>

    <div class="col-xs-6">
      <h3 class="title-signature">Date et signature</h3>

      <div class="row">
        <div class="col-xs-6">
          <div class="row">
            <div class="col-xs-2">
              <label>A</label>
            </div>
            <div class="col-xs-10">
              <div class="value-input"></div>
            </div>
          </div>
        </div>

        <div class="col-xs-6">
          <div class="row">
            <div class="col-xs-2">
              <label>le</label>
            </div>
            <div class="col-xs-10">
              <div class="value-input"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-12">
          <div class="signature">Signature</div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <table class="table-bordered full-width-table cadre-reservation">
        <tbody>
        <tr>
          <td colspan="3" class="title is-white text-initial">
            Cadre réservé à l'administration
          </td>
        </tr>

        <tr>
          <td rowspan="2">
            <div class="title-reference">Références comptables :</div>

            <div class="row">
              <div class="col-xs-3">
                <label>Date</label>
              </div>
              <div class="col-xs-9">
                <div class="value-input"></div>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-3">
                <label>N°</label>
              </div>
              <div class="col-xs-9">
                <div class="value-input"></div>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-3">
                <label>Montant</label>
              </div>
              <div class="col-xs-9">
                <div class="value-input"></div>
              </div>
            </div>
          </td>

          <td>
            <div class="row">
              <div class="col-xs-6">
                <label>N°déclaration</label>
              </div>
              <div class="col-xs-6">
                <div class="value-input"></div>
              </div>
            </div>
          </td>

          <td rowspan="2">
            <div class="title-reference text-center">Date de réception</div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="row">
              <div class="col-xs-4">
                <label>Pénalités</label>
              </div>
              <div class="col-xs-8">
                <div class="value-input"></div>
              </div>
            </div>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="message-footer">
        <p>
          "Les dispositions des articles 39 et 40 de la loi n°78-17 du 6
          janvier
          1978 relative à l'informatique, aux fichiers et
          aux libertés, modifiée par la loi n°2004-801 du 6 août 2004,
          garantissent les droits des personnes physqiues à
          l'égard des traitements des données à caractère personnel. "
        </p>
        <hr class="separator-line">
        <p class="contact">
          Recette des Impôts (CCP 14168-00001-9062004Y068-32) - B.P 72 - 98713
          PAPEETE - Tél : 40 46 13 56 - Fax : 40 46 13 03
        </p>
      </div>
    </div>
  </div>
</div>
</body>
</html>