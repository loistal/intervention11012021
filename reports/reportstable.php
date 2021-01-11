<?php
$i = -1;
$reportA[++$i] = d_trad('revenue'); $reportnA[$i] = 'revenue';
$reportA[++$i] = d_trad('balanceage'); $reportnA[$i] = 'balanceage';
$reportA[++$i] = d_trad('vspreviousyear'); $reportnA[$i] = 'vsly';
$reportA[++$i] = d_trad('promotions'); $reportnA[$i] = 'promo';
$reportA[++$i] = 'Activité d\'un Employé'; $reportnA[$i] = 'employee_one';
$reportA[++$i] = 'Employé(e)s'; $reportnA[$i] = 'employeereport';
$reportA[++$i] = d_trad('saledproductbymonth'); $reportnA[$i] = 'productsmonthly';
$reportA[++$i] = d_trad('productbyclientbymonth'); $reportnA[$i] = 'prodclimonth';
$reportA[++$i] = d_trad('stockcadence'); $reportnA[$i] = 'cadstock';
$reportA[++$i] = d_trad('sellbydate'); $reportnA[$i] = 'sellbydate';
$reportA[++$i] = d_trad('soldproduct'); $reportnA[$i] = 'soldproduct';
$reportA[++$i] = d_trad('vatreport'); $reportnA[$i] = 'vatreport';
$reportA[++$i] = d_trad('invoices'); $reportnA[$i] = 'invoicereport2';
$reportA[++$i] = d_trad('clientbalance'); $reportnA[$i] = 'clientbalance';
$reportA[++$i] = d_trad('topclients'); $reportnA[$i] = 'topclients';
$reportA[++$i] = d_trad('payments'); $reportnA[$i] = 'payments';
$reportA[++$i] = 'Produits sans ventes'; $reportnA[$i] = 'nosales';
$reportA[++$i] = 'Commissions'; $reportnA[$i] = 'commissions';
$reportA[++$i] = 'Marges Brutes'; $reportnA[$i] = 'productmargins';
$reportA[++$i] = 'Catalogue Produits'; $reportnA[$i] = 'productcatalogue';
$reportA[++$i] = 'Lots de Stock'; $reportnA[$i] = 'purchasebatchreport';
$reportA[++$i] = 'Valeur du Stock'; $reportnA[$i] = 'productvalue';
$reportA[++$i] = 'Marge par utilisateur'; $reportnA[$i] = 'usermargin';
$reportA[++$i] = 'Analyse des Avoirs'; $reportnA[$i] = 'return_analysis';
$reportA[++$i] = 'Meilleurs Produits'; $reportnA[$i] = 'top_products';

d_sortarray($reportA);

if ($_SESSION['ds_menustyle'] == 5)
{
?>
<main role="main" >
  <nav id="side-nav">
  <div>
    <div class="close" id="closeSidenav"><span class="marker marker-open"></span></div>
    <ul>
    <?php
    foreach ($reportA as $y => $descr)
    {
      echo '<li><a href="reports.php?reportsmenu=' . $reportnA[$y] . '">' . $descr . '</a></li>';
    }
    ?>
    </ul>
    <?php require('inc/copyright.php'); ?>
  </div>
</nav>
<div class="container" id="main-container">
<?php
}
else
{
?>
</div><div id="wrapper">
<div id="leftmenu">
<div id="selectactionbar">
  <div class="selectaction">
    <div class="selectactionlist">
      <?php
      foreach ($reportA as $y => $descr)
      {
        if ( $_SESSION['ds_displayicons'] == 1 ) { echo '<img src="pics/report.png">';}
        echo '<a class="leftmenu" href="reports.php?reportsmenu=' . $reportnA[$y] . '">' . $descr . '</a><br>';
      }
      ?>
    </div>
  </div>
</div>
<?php
require ('inc/copyright.php');
?>
</div><div id="mainprogram">
<?php
}
?>