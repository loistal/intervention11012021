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
  <title>Modèle compte resultat 2</title>
  <link rel="stylesheet" href="declaration/bootstrap.css">
  <link rel="stylesheet" href="declaration/modelecompteresultat2.css">
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
        <p><strong>522-2. MODELE DE COMPTE DE RESULTAT (en tableau)</strong></p>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <table class="table-bordered full-width-table">
          <tbody>
          <tr>
            <td class="title">Charges (hors taxes)</td>
            <td class="title">Exercice N</td>
            <td class="title">Exercice (N-1)</td>
            <td class="title">Produits (hors taxes)</td>
            <td class="title">Exercice N</td>
            <td class="title">Exercice (N-1)</td>
          </tr>

          <tr class="no-border-bottom">
            <td>Charges d'exploitation</td>
            <td></td>
            <td></td>
            <td>Produits d'exploitation</td>
            <td></td>
            <td></td>
          </tr>

          <tr class="text-right no-border-top">
            <td>Achats de marchandises (a)</td>
            <td>0</td>
            <td>0</td>
            <td>Ventes de marchandises</td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr class="text-right">
            <td>Variation des stocks [marchandises] (b)</td>
            <td>0</td>
            <td>0</td>
            <td>Production vendue (bien et services) (c)</td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr class="text-right">
            <td>Achats d'approvisionnements (a)</td>
            <td>0</td>
            <td>0</td>
            <td>Production stockée (d)</td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr class="text-right">
            <td>Variation des stocks [approvisionnements] (b)</td>
            <td>0</td>
            <td>0</td>
            <td>Production immobilisée (d)</td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr class="text-right">
            <td>Autres charges externes</td>
            <td>0</td>
            <td>0</td>
            <td>Subventions d'exploitation</td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr class="text-right">
            <td>Impôts, taxes et versements assimilés</td>
            <td>0</td>
            <td>0</td>
            <td>Autres produits (2)</td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr class="text-right">
            <td>Rénumération du personnel</td>
            <td>0</td>
            <td>0</td>
            <td class="no-border-bottom"></td>
            <td class="no-border-bottom"></td>
            <td class="no-border-bottom"></td>
          </tr>

          <tr class="text-right">
            <td>Charges sociales</td>
            <td>0</td>
            <td>0</td>
            <td class="no-border-bottom no-border-top"></td>
            <td class="no-border-bottom no-border-top"></td>
            <td class="no-border-bottom no-border-top"></td>
          </tr>

          <tr class="text-right">
            <td>Charges sociales</td>
            <td>0</td>
            <td>0</td>
            <td class="no-border-bottom no-border-top"></td>
            <td class="no-border-bottom no-border-top"></td>
            <td class="no-border-bottom no-border-top"></td>
          </tr>

          <tr class="text-right">
            <td>Dotations aux amortissements</td>
            <td>0</td>
            <td>0</td>
            <td class="no-border-bottom no-border-top"></td>
            <td class="no-border-bottom no-border-top"></td>
            <td class="no-border-bottom no-border-top"></td>
          </tr>

          <tr class="text-right">
            <td>Dotations aux provisions</td>
            <td>0</td>
            <td>0</td>
            <td class="no-border-bottom no-border-top"></td>
            <td class="no-border-bottom no-border-top"></td>
            <td class="no-border-bottom no-border-top"></td>
          </tr>

          <tr class="text-right">
            <td>Autres charges</td>
            <td>0</td>
            <td>0</td>
            <td class="no-border-top"></td>
            <td class="no-border-top"></td>
            <td class="no-border-top"></td>
          </tr>

          <tr>
            <td>Charges financières</td>
            <td class="text-right">0</td>
            <td class="text-right">0</td>
            <td>Produits financiers (2)</td>
            <td class="text-right">0</td>
            <td class="text-right">0</td>
          </tr>

          <tr class="text-right">
            <td><strong>Total I</strong></td>
            <td>0</td>
            <td>0</td>
            <td><strong>Total I</strong></td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr>
            <td>Charges exceptionnelles <strong>(III)</strong></td>
            <td class="text-right">0</td>
            <td class="text-right">0</td>
            <td class="text-right">dont à l'exportation</td>
            <td class="text-right">0</td>
            <td class="text-right">0</td>
          </tr>

          <tr>
            <td>Impôts sur les bénéfices <strong>(III)</strong></td>
            <td class="text-right">0</td>
            <td class="text-right">0</td>
            <td>Produits exceptionnels (2) <strong>(III)</strong></td>
            <td class="text-right">0</td>
            <td class="text-right">0</td>
          </tr>

          <tr class="text-right">
            <td><strong>Total des charges (I + II + III)</strong></td>
            <td>0</td>
            <td>0</td>
            <td><strong>Total des produits (I + II)</strong></td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr>
            <td>Solde créditeur : <strong>bénéfice</strong> (1)</td>
            <td class="text-right">0</td>
            <td class="text-right">0</td>
            <td>Solde débiteur : <strong>perte</strong> (3)</td>
            <td class="text-right">0</td>
            <td class="text-right">0</td>
          </tr>

          <tr class="text-right">
            <td class="text-uppercase"><strong>Total général</strong></td>
            <td>0</td>
            <td>0</td>
            <td class="text-uppercase"><strong>Total général</strong></td>
            <td>0</td>
            <td>0</td>
          </tr>

          <tr>
            <td>Y compris</td>
            <td></td>
            <td></td>
            <td class="no-border-bottom"></td>
            <td class="no-border-bottom"></td>
            <td class="no-border-bottom"></td>
          </tr>

          <tr>
            <td>- redevances de crédit-bail mobilier</td>
            <td></td>
            <td></td>
            <td class="no-border-bottom no-border-top"></td>
            <td class="no-border-bottom no-border-top"></td>
            <td class="no-border-bottom no-border-top"></td>
          </tr>

          <tr>
            <td>- redevances de crédit-bail immobilier</td>
            <td></td>
            <td></td>
            <td class="no-border-bottom no-border-top"></td>
            <td class="no-border-bottom no-border-top"></td>
            <td class="no-border-bottom no-border-top"></td>
          </tr>

          <tr>
            <td>(1) Compte tenu d'un résultat exceptionnel avant impôt de</td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td>(2) Dont reprises sur provisions (et amortissements)</td>
            <td class="text-right"></td>
            <td class="text-right"></td>
          </tr>

          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>(3) Compte tenu d'un résultat exceptionnel avant impôt de</td>
            <td class="text-right"></td>
            <td class="text-right"></td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <p>(a) Y compris droits de douane</p>

        <p>(b) Stock initial moins stock final : montant de la variation en moins entre parenthèses ou précédé du signe (-)</p>

        <p>(c) A s'inscrire, le cas écheant </p>

        <p>(d) Stock final moins stock initial, </p>
      </div>
    </div>
  </div>
</div>
</body>


