<?php
$portrait = 1;
$paysage = 0;
?>

<style type="text/css">
  /*********************************************/
  /*        Print Rules                        */
  /*********************************************/
  @media print {
    * {
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }

    html {
      margin: 0 !important;
      padding: 0 !important;
    }

    body {
      margin: 0 !important;
      padding: 0 !important;
      background-color: #FFF !important;
      font-family: <?php echo $_SESSION['ds_user_font_print']; ?>;
    }

    .main {
      margin-top: 0 !important;
      margin-bottom: 0 !important;

      -moz-box-shadow: none !important;
      -webkit-box-shadow: none !important;
      box-shadow: none !important;

      padding: 0 !important;
    }

    .container-fluid {
      padding-right: 15px !important;
      padding-left: 15px !important;
    }

    .share {
      display: none !important;
      margin-top: 0 !important;
      margin-bottom: 0 !important;
    }

    .no-print, .no-print *
    {
        display: none !important;
    }

  }

  <?php if($portrait == 1): ?>

  @page {
    size: A4;
    margin: 0.5cm;
  }

  <?php elseif($paysage == 1): ?>

  @page {
    size: A4 landscape;
    margin: 0.5cm;
  }

  <?php endif; ?>

  /*********************************************/
  /*         General rules                     */
  /*********************************************/
  <?php if($portrait == 1): ?>
  .main {
    min-height: 281mm;
    width: 210mm;
  }

  .share {
    width: 210mm;
  }

  <?php elseif($paysage == 1): ?>
  .main {
    width: 281mm;
    min-height: 210mm;
  }

  .share {
    width: 281mm;
  }

  <?php endif; ?>

  .container-fluid {
    padding-right: 4px;
    padding-left: 4px;
  }

  .main {
    background-color: #FFF;
    padding: 10px 0;

    position: relative;

    margin: 20px auto 20px auto;

    -moz-box-shadow: 0px 0px 20px rgba(80, 80, 80, 0.7);
    -webkit-box-shadow: 0px 0px 20px rgba(80, 80, 80, 0.7);
    box-shadow: 0px 0px 20px rgba(80, 80, 80, 0.7);
  }

  table tr td {
    vertical-align: top;
    font-size: 14px;
  }

  .small-percent {
    font-size: 11px !important;
  }

  /*********************************************/
  /*        Share invoice                      */
  /*********************************************/
  .share {
    margin: 0 auto;
  }

  .share .container-fluid {
    padding-left: 4px;
    padding-right: 4px;
  }
  /*********************************************/
  /*          Table Rules                    */
  /*********************************************/
  table.invoiceitems {
    border-collapse: collapse;
    border: 1px solid;
    width: 100%;
  }

  table.report td {
    border: 1px solid #696969;
  }
  
  td.numbers {
    text-align: right;
    white-space: nowrap;
    vertical-align: text-top;
  }
  
  /* *** new styles for d_td */

td.emphasis {
  font-weight: bold;
}

td.right {
  text-align: right;
}

td.int {
  text-align: right;
}

td.decimal {
  text-align: right;
}

td.currency {
  text-align: right;
}

td.percentage {
  text-align: right;
}

td.date {
  text-align: right;
}

td.center {
  text-align: center;
}



  th.numbers {
    text-align: right;
    white-space: nowrap;
    vertical-align: text-top;
  }

  table td.letters {
    text-align: left;
    white-space: normal;
    vertical-align: text-top;
  }

  table th.letters {
    white-space: normal;
    text-align: left;
    vertical-align: text-top;
  }
  
  .bold {
    font-weight: bold;
  }

  table.report td {
    padding: 2px;
  }

  table.report td.breakme {
    white-space: normal;
  }

  /*********************************************/
  /*      Tables bootstrap                     */
  /*********************************************/
  .table > tbody > tr > td {
    padding: 2px;
  }

  /*********************************************/
  /*      Tables comment                       */
  /*********************************************/
  .item-comment {
    font-size: 12px;
    font-style: italic;
  }

  /*********************************************/
  /*       Logo Tem                            */
  /*********************************************/
  .logo-tem {
    position: absolute;
    bottom: 0;
    right: 10px;
  }

  .dlogo {
    position: absolute;
    bottom: 0;
    right: 15px;
  }
</style