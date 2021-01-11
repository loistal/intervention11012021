<?php
#Turn off error reporting php.ini
error_reporting(0);

function myfix($val)
{
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Modèle compte resultat 1</title>
  <link rel="stylesheet" href="declaration/bootstrap.css">
  <link rel="stylesheet" href="declaration/modelecompteresultat1.css">
  <link rel="stylesheet" href="declaration/print.css">
</head>

<body>
<div id="main">
  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 text-left">
        <p>www.plancomptable.com</p>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12 text-center">
        <p>Système abrégé</p>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12 text-center">
        <p><strong>522-1. MODELE DE BILAN (avant répartition)</strong></p>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <table class="table-bordered full-width-table">
          <tbody>
          <tr>
            <td rowspan="2" class="title">Actif</td>
            <td colspan="3" class="title">Exercice N</td>
            <td class="title">Exercice N-1</td>
            <td rowspan=2" class="title">Passif</td>
            <td rowspan="2" class="title">Exercice N</td>
            <td rowspan="2" class="title">Exercice N-1</td>
          </tr>

          <tr>
            <td class="title">Brut</td>
            <td class="title">Ammortissements et provisions (à déduire)</td>
            <td class="title">Net</td>

            <td class="title">Net</td>
          </tr>

          <tr class="no-border-bottom">
            <td>Actifs immobilisé (a) :</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

            <td>Capitaux propres (c) :</td>
            <td></td>
            <td></td>
          </tr>

          <tr class="no-border-top text-right">
            <td>Immobilisations incorporelles</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

            <td>Capital</td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr class="text-right">
            <td>- fonds commercial (b)</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>

            <td>Ecart de réévaluation (c)</td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr class="text-right">
            <td>- autres</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>

            <td>Réserves</td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr class="text-right">
            <td>Immobilisations corporelles</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>

            <td>- réserve légale</td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr class="text-right">
            <td>Immobilisations financières (1)</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>

            <td>- réserves réglementées</td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr class="text-right">
            <td><strong>Total I</strong></td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>

            <td>- autres (4)</td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr>
            <td>Actif circulant</td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>

            <td>Report à nouveau (d)</td>
            <td class="text-right">0</td>
            <td class="text-right">0</td>
          </tr>

          <tr class="text-right">
            <td>Stock et en-cours [autres que marchandises] (a)</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>

            <td><strong>Résultat de l'exercice [bénéfice ou perte] (d)</strong>
            </td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr class="text-right">
            <td>Marchandises (a)</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>

            <td>Provisions réglementées</td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr class="text-right">
            <td>Avances et acomptes versés sur commandes</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>

            <td><strong>Total I</strong></td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr>
            <td class="no-border-bottom">Créances (2)</td>
            <td class="no-border-bottom"></td>
            <td class="no-border-bottom"></td>
            <td class="no-border-bottom"></td>
            <td class="no-border-bottom"></td>

            <td>Provisions pour risques et charges (II)</td>
            <td class="text-right">0</td>
            <td class="text-right">0</td>
          </tr>

          <tr>
            <td class="no-border-top text-right">- clients et comptes rattachés (a)</td>
            <td class="no-border-top text-right">0</td>
            <td class="no-border-top text-right">0</td>
            <td class="no-border-top text-right">0</td>
            <td class="no-border-top text-right">0</td>

            <td class="no-border-bottom">Dettes (5) :</td>
            <td class="no-border-bottom"></td>
            <td class="no-border-bottom"></td>
          </tr>

          <tr class="text-right">
            <td>- autres</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>

            <td class="no-border-top">Emprunts et dettes assimilées</td>
            <td class="no-border-top">0</td>
            <td class="no-border-top">0</td>
          </tr>

          <tr class="text-right">
            <td>Valeurs mobilières de placement</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>

            <td>Avances et acomptes reçues sur commandes en cours</td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr class="text-right">
            <td>Disponiblités (autres que caisse)</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>

            <td>Fournisseurs et comptes rattachés</td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr class="text-right">
            <td>Caisse</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>

            <td>Autres (3)</td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr class="text-right">
            <td><strong>Total II</strong></td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>

            <td><strong>Total III</strong></td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr>
            <td>Charges constatées d'avacne (2)(*)(III)</td>
            <td class="text-right">0</td>
            <td class="text-right">0</td>
            <td class="text-right">0</td>
            <td class="text-right">0</td>

            <td>Produits constatés d'avance (2) (IV)</td>
            <td class="text-right">0</td>
            <td class="text-right">0</td>
          </tr>

          <tr class="text-right">
            <td class="text-uppercase">
              <strong>Total général (I + II + III)</strong></td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>

            <td class="text-uppercase">
              <strong>Total général (I + II + III + IV)</strong></td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr>
            <td>(1) Dont à moins d'un an</td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>

            <td>(4) Dont réserves statutaires</td>
            <td class="text-right"></td>
            <td class="text-right"></td>
          </tr>

          <tr>
            <td>(2) Dont à plus d'un an</td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>

            <td>(5) Dont à plus de 5 ans</td>
            <td class="text-right"></td>
            <td class="text-right"></td>
          </tr>

          <tr>
            <td>(3) Dont comptes courant d'associés</td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>

            <td>(5) Dont à plus d'un an et moins de 5 ans</td>
            <td class="text-right"></td>
            <td class="text-right"></td>
          </tr>

          <tr>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>

            <td>Dont à moins d'un an</td>
            <td class="text-right"></td>
            <td class="text-right"></td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <p>
          (a) Les actifs avec clause de réserve de propriété sont regroupés sur
          une ligne distincte portant la mention " dont... avec clause de réserve
          de propriété ".
          En cas d’impossibilité d’identifier les biens, un renvoi au pied du
          bilan indique le montant restant à payer sur ces biens. Le montant à
          payer comprend
          celui des effets non échus.
        </p>

        <p>
          (b) Y compris droit au bail.
        </p>

        <p>
          (c) A détailler conformément à la législation en vigueur.
        </p>

        <p>
          (d) Montant entre parenthèses ou précédé du signe moins (-) lorsqu'il
          s'agit de pertes.
        </p>

        <p>
          (*) Le cas échéant, les entités ouvrent un poste "Charges à répartir sur
          plusieurs exercices" qui forme le total III, le total général étant
          modifié en conséquence.
        </p>
      </div>
    </div>
  </div>
</div>
</body>