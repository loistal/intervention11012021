<style type="text/css">
  /*********************************************/
  /*        General rules                      */
  /*********************************************/
  body {
    background-color: #DBD7D7;
  }

  body {
    font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, Sans-serif;
  }

  .img-left img {
    float: left;
  }

  .img-right img {
    float: right;
  }

  .disabled {
    border: 1px solid #999;
    color: #333;
    opacity: 0.5;
  }

  /*********************************************/
  /*      Company informations                 */
  /*********************************************/
  .company-logo img {
    margin: 0 auto;
  }

  /*********************************************/
  /*         Invoice header                    */
  /*********************************************/
  .invoice-header {
    margin-top: 10px;
    display: inline-block;
  }

  .invoice-header .title-top {
    font-size: 15px;
    margin-bottom: 5px;
  }

  .invoice-header .title {
    background: #045191;
    color: #FFF;
    text-transform: uppercase;

    font-size: 20px;
    padding: 15px;
    font-family: Sanchez, Serif;
  }

  @media print {
    .invoice-header .title {
      background-color: #FFF;
      color: #000;
      padding: 0;
    }
  }

  .invoice-informations {
    margin-top: 10px;
  }

  /*********************************************/
  /*        Client informations                */
  /*********************************************/
  .client-info {
    margin-top: 20px;
  }

  .client-info .client-name {
    font-weight: bold;
    font-size: 15px;
    text-transform: uppercase;
    margin: 5px 0;
  }

  .client-info div {
    margin-bottom: 3px;
  }

  .client-info span {
    display: block;
  }

  /*********************************************/
  /*         Currency                          */
  /*********************************************/
  .currency {
    margin-top: 10px;
    text-align: right;
    color: #858585;
    font-style: italic;
    font-size: 12px;
  }

  /*********************************************/
  /*         Tables                            */
  /*********************************************/
  .items {
    margin-top: 10px;
  }

  .items table th {
    font-family: Sanchez, Serif;
    font-size: 12px;
    text-transform: uppercase;

    padding: 10px 10px;

    text-align: right;
    background: #b0b4b3;
    color: #FFF;
  }

  .items table td {
    padding: 10px 10px;
    text-align: right;
    border-bottom: 1px solid #DDD;
  }

  /*********************************************/
  /*         Sums                              */
  /*********************************************/
  .sums {
    margin-top: 10px;
  }

  .sums table tr th, .sums table tr td {
    padding: 8px 3px;
  }

  .sums table tr.amount-total td {
    background: #415472;
    color: #FFF;
    font-family: Sanchez, Serif;
    font-size: 25px;
    line-height: 1em;
    padding: 7px;
  }

  .sums table tr.due-amount th, .sums table tr.due-amount td {
    font-weight: bold;
  }

  /*********************************************/
  /*        Terms                              */
  /*********************************************/
  .terms {
    font-size: 13px;
    margin-top: 10px;
  }

  .terms > span {
    font-weight: bold;
    display: inline-block;
    min-width: 20px;
    text-transform: uppercase;
  }
  
  .sign_box {
  float: right;
  width: 200px;
  height: 100px;
  border: 2px solid black;
  border-radius: 5px;
}
</style>