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
  $stopdate = d_builddate(31, 12, $year);

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
  
  ### 2015 12 09 substract from 105 106 107
  #$f_105_mod = $f[105];
  #$f_106_mod = $f[106];
  #$f_107_mod = $f[107];
  ###

  $f[105] = d_multiply($f[5], 5);
  $f[105] = myround(d_divide($f[105], 100, 2)); # hardcode 5%
  $f[106] = d_multiply($f[6], 13);
  $f[106] = myround(d_divide($f[106], 100, 2)); # hardcode 13%
  $f[107] = d_multiply($f[7], 16);
  $f[107] = myround(d_divide($f[107], 100, 2)); # hardcode 16%
  
  ### 2015 12 09 substract from 105 106 107
  #$f[105] = d_subtract($f[105], $f_105_mod);
  #$f[106] = d_subtract($f[106], $f_106_mod);
  #$f[107] = d_subtract($f[107], $f_107_mod);
  ###
  
  $f[9] = d_add($f[105], $f[106]);
  $f[9] = d_add($f[9], $f[107]);
  $f[9] = d_add($f[9], $f[8]); # TODO f[8] manually defined/saved
  $f[14] = d_add($f[10], $f[11]);
  $f[14] = d_add($f[14], $f[12]);
  $f[14] = d_add($f[14], $f[13]);
  
  /* 2017 02 16 see email CAGEST
  $f[18] = d_subtract($f[9], $f[14]);
  ### prorata
  if ($prorata > 0)
  {
    $kladd = d_multiply($f[14],$prorata);
    $kladd = d_divide($kladd, 100, 0);
    $f[18] = d_subtract($f[18], $kladd);
  }    
  ###
  if (d_compare($f[18], 0) == -1)
  {
    $f[15] = d_abs($f[18]);
    $f[18] = 0;
  }
  */
  
  # 2016 02 29 empty fields if no values
  if ($f[105] == 0 && $f[106] == 0 && $f[107] == 0)
  {
    $f[1] = 0;
    $f[2] = 0;
    #$f[3] = 0;
    #$f[4] = 0;
    $f[5] = 0;
    $f[6] = 0;
    $f[7] = 0;
  }

  /*
  1) 16 et 22 viennent des parametrage comptes Oui c’est bien ça
 
  2) 15, 17, 18, 21, 23, 24 sont calculés comme induqué sur le formulaire Oui c’est bien ça
   
  3) 19 restent vides Oui c’est bien ça
  
  Le champs 20 se calcule si pas de "Remboursement demandé"
  */
  # 2017 02 17
  if (d_compare($f[14], $f[9]) == 1)
  {
    $f[15] = d_subtract($f[14], $f[9]);
  }
  else
  {
    $f[21] = d_subtract($f[9], $f[14]);
  }
  $f[16] = $f[316];
  $f[22] = $f[322];
  $f[17] = d_add($f[15], $f[16]);
  $f[23] = d_add($f[21], $f[22]);
  if (d_compare($f[17], $f[23]) == 1)
  {
    $f[18] = d_subtract($f[17], $f[23]);
  }
  else
  {
    $f[24] = d_subtract($f[23], $f[17]);
  }
  if ($_POST['credit_or_reimburse'] == 1) { }
  else { $f[20] = d_subtract($f[18], $f[19]); }

  echo '<!doctype html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>DECL. 1030</title>
    <link rel="stylesheet" href="declaration/bootstrap.css">
    <link rel="stylesheet" href="declaration/1030.css">
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
    <title>DECL. 1030</title>
    <link rel="stylesheet" href="declaration/bootstrap.css">
    <link rel="stylesheet" href="declaration/1030.css">
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
    <div class="section-header">
      <div class="row">
        <div class="col-xs-8">
          <div class="message-header">
            A déposer au plus tard le <?php echo $duedate; ?>
          </div>
        </div>
        <div class="col-xs-4">
          <div class="invoiceid pull-right">
            DECL. 1030
          </div>
        </div>
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
            <div class="title-invoice text-center">
              <h1>Taxe sur la valeur ajoutée</h1>

              <h2>Régime simplifié</h2>

              <div class="separator"></div>
              <p><strong>(Déclaration récapitulative annuelle)</strong></p>

              <p>
                <strong>à deposer au plus tard le 31 mars de l'année suivante</strong>
              </p>
            </div>
          </div>

          <div class="col-xs-4">
            <div class="declaration-period">
              <div class="title">Période de déclaration</div>
              <div class="row">

                <div class="col-xs-12">
                  <div class="col-xs-6">
                    <label>Période du</label>
                  </div>

                  <div class="col-xs-6">
                    <!-- <div class="value-input"> -->
                    <?php print datefix2($startdate); ?>
                    <!-- </div> -->
                  </div>
                </div>

                <div class="col-xs-12">
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
            <div class="value-input"><?php print $adresse_mail_societe_ou_representant_legal; ?></div>
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
          <div class="col-xs-4">
            <label>Commune</label>
          </div>

          <div class="col-xs-8">
            <div class="value-input"><?php print $commune; ?></div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <table class="table-bordered full-width-table">
          <tbody>

          <tr>
            <td colspan="6" class="title is-white">A - Opérations réalisées</td>
          </tr>

          <tr>
            <td class="title text-initial" colspan="3">
              Montant des opérations imposables Hors TVA
            </td>
            <td class="title text-initial" colspan="3">
              Montant des opérations nom imposables
            </td>
          </tr>

          <tr>
            <td class="number is-white">01</td>
            <td>Ventes</td>
            <td class="text-right" style="width: 20%;"><?php print myfix($f[1]); ?></td>

            <td class="number is-white">03</td>

            <td>Exportations</td>
            <td class="text-right" style="width: 20%;"><?php print myfix($f[3]); ?></td>
          </tr>

          <tr>
            <td class="number is-white">02</td>
            <td>Prestations de services</td>
            <td class="text-right"><?php print myfix($f[2]); ?></td>

            <td class="number is-white">04</td>
            <td>Autres opérations non taxables</td>
            <td class="text-right"><?php print myfix($f[4]); ?></td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <table class="table-bordered full-width-table">
          <tbody>
          <tr>
            <td colspan="4" class="title is-white">B - TVA exigible</td>
          </tr>

          <tr>
            <td colspan="2" class="title text-initial">Opérations imposables (lignes 1 à 2)
            </td>
            <td class="title text-initial">Base Hors TVA</td>
            <td class="title text-initial">TVA due</td>
          </tr>

          <tr>
            <td class="number is-white">05</td>
            <td>Taux Réduit 5%</td>
            <td class="text-right" style="width: 20%;"><?php print myfix($f[5]); ?></td>
            <td class="text-right" style="width: 20%;"><?php print myfix($f[105]); ?></td>
          </tr>

          <tr>
            <td class="number is-white">06</td>
            <td>Taux Intermédiaire 13%</td>
            <td class="text-right"><?php print myfix($f[6]); ?></td>
            <td class="text-right"><?php print myfix($f[106]); ?></td>
          </tr>

          <tr>
            <td class="number is-white">07</td>
            <td>Taux Normal 16%</td>
            <td class="text-right"><?php print myfix($f[7]); ?></td>
            <td class="text-right"><?php print myfix($f[107]); ?></td>
          </tr>

          <tr>
            <td class="number is-white">08</td>
            <td>Régularisation : autre TVA à reverser</td>
            <td class="text-right"><?php print myfix($f[8]); ?></td>
            <td class="text-right"><?php print myfix($f[108]); ?></td>
          </tr>

          <tr>
            <td class="number is-white">09</td>
            <td class="text-right" colspan="2">
              <strong>Total (lignes 05 à 08):<strong>
            </td>
            <td class="text-right"><?php print myfix($f[9]); ?></td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <table class="table-bordered full-width-table">
          <tbody>
          <tr>
            <td colspan="3" class="title is-white">C - TVA Deductible</td>
          </tr>

          <tr>
            <td class="number is-white">10</td>
            <td>TVA sur biens constituant des immobilisations</td>
            <td class="text-right" style="width: 20%;"><?php print myfix($f[10]); ?></td>
          </tr>

          <tr>
            <td class="number is-white">11</td>
            <td>TVA sur autres biens et services</td>
            <td class="text-right"><?php print myfix($f[11]); ?></td>
          </tr>

          <tr>
            <td class="number is-white">12</td>
            <td>Régularisation : autre TVA à déduire</td>
            <td class="text-right"><?php print myfix($f[12]); ?></td>
          </tr>

          <tr>
            <td class="number is-white">13</td>
            <td>
              Report de crédit appraissant sur la ligne 20 de la précédente déclaration
            </td>
            <td class="text-right"><?php print myfix($f[13]); ?></td>
          </tr>

          <tr>
            <td class="number is-white">14</td>
            <td class="text-right"><strong>Total (lignes 10 à 13) :<strong></td>
            <td class="text-right"><?php print myfix($f[14]); ?></td>
          </tr>

          <tr>
            <td class="is-white" colspan="2">
              Prorata de réduction applicable pour la période
            </td>
            <td class="text-right"><?php print $prorata; ?>%</td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <table class="table-bordered full-width-table">
          <tbody>
          <tr>
            <td colspan="3" class="title is-white">D - Crédit</td>
            <td colspan="3" class="title is-white">E - TVA à payer</td>
          </tr>

          <tr>
            <td class="number is-white">15</td>
            <td>
              <strong class="text-uppercase">Difference</strong><strong>(lignes 14 - 09)</strong>
            </td>
            <td class="text-right" style="width: 20%;"><?php print myfix($f[15]); ?></td>
            <td class="number is-white">21</td>
            <td>
              <strong class="text-uppercase">Difference</strong><strong>(lignes 09 - 14)</strong>
            </td>
            <td class="text-right" style="width: 20%;"><?php print myfix($f[21]); ?></td>
          </tr>

          <tr>
            <td class="number is-white">16</td>
            <td class="text-uppercase">Acompte déclaré</td>
            <td class="text-right" style="width: 20%;"><?php print myfix($f[16]); ?></td>

            <td class="number is-white">22</td>
            <td class="text-uppercase">Remboursement obtenu</td>
            <td class="text-right" style="width: 20%;"><?php print myfix($f[22]); ?></td>
          </tr>

          <tr>
            <td class="number is-white">17</td>
            <td>Total (lignes 15 + 16)</td>
            <td class="text-right" style="width: 20%;"><?php print myfix($f[17]); ?></td>

            <td class="number is-white">23</td>
            <td>Total (lignes 21 + 22)</td>
            <td class="text-right" style="width: 20%;"><?php print myfix($f[23]); ?></td>
          </tr>

          <tr>
            <td class="number is-white">18</td>

            <td>
              <strong class="text-uppercase">Excédent</strong><strong>(lignes 17 - 23)</strong>
            </td>

            <td class="text-right" style="width: 20%;"><?php print myfix($f[18]); ?></td>

            <td class="number is-white">24</td>

            <td>
              <strong class="text-uppercase">Net à payer</strong><strong>(lignes 23 - 17)</strong>
            </td>

            <td class="text-right" style="width: 20%;"><?php print myfix($f[24]); ?></td>
          </tr>

          <tr>
            <td class="number is-white">19</td>
            <td>
              Remboursement demandé :
              <br> (Demande de remboursement ci-jointe)
            </td>
            <td class="text-right" style="width: 20%;"><?php print myfix($f[19]); ?></td>
          </tr>

          <tr>
            <td class="number is-white">20</td>
            <td>Crédit à reporter sur la prochaine <br> déclaration
              <strong>(lignes 18 - 19)</strong></td>
            <td class="text-right" style="width: 20%;"><?php print myfix($f[20]); ?></td>
          </tr>
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

          <label class="pull-right">
            <input type="checkbox"> Espèces
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox"> Virement bancaire (Préciser le N°TAHITI,
            la
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
            <td colspan="3" class="title is-white">
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