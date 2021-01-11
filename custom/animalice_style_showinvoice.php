<style type="text/css">
  /*********************************************/
  /*          Base rules                       */
  /*********************************************/
  body {
    background-color: #DBD7D7;
  }

  body {
    line-height: 1;
    color: #000; 
  }

  p {
    margin: 0.2em 0 0.2em 0;
    padding: 0;
  }
  
  <?php
if ($_SESSION['ds_userid'] == 5 && 1==0)
{
  echo '.main {
    height: 6cm;
    width: 20cm;
    font-size: 200%;
  }';
}
else {
?>  
  
  .main {
    height: 3cm;
    width: 10cm;
  }
<?php } ?>


  .clearfix {
    clear: both;
  }

  .btn {
    display: inline-block;
    padding: 6px 14px;
    margin-bottom: 0;
    font-size: 16px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;

    text-decoration: none;
  }

  .btn-success {
    color: #fff;
    background-color: #5cb85c;
    border-color: #4cae4c;
  }

  .btn-success:hover {
    color: #fff;
    background-color: #449d44;
    border-color: #398439;
  }


  /*********************************************/
  /*          Invoice infos table             */
  /*********************************************/
  
  table.receipt {
    border: 0px;
    padding: 15px;
<?php
if ($_SESSION['ds_userid'] == 5 && 1==0)
{
  echo ' font-size: 200%;
  width: 20cm;';
}
else {
?>   
width: 10cm;
<?php } ?> 
  } 
  
  #titlebold
  {
    text-align: center;
    font-weight: bold;
    font-family: Verdana;    
    font-size: 14px;       
  }
  
  #title
  {
    text-align: center;
    font-size: 14px;   
    font-family: Trebuchet MS;   
  }
 
  #main
  {
    text-align: left;
    font-size: 12px;  
    font-family: Trebuchet MS;   
  }
    
  
  #mainprice
  {
    text-align: right;
    font-size: 12px;   
    font-family: Trebuchet MS;     
  }
  
  #maintdnumberswithoutborder {
    border: 0px;
    text-align: right;
    white-space: nowrap;
    vertical-align: text-top;
    font-size: 12px;   
    font-family: Trebuchet MS; 
  }  

  #vattdnumbers {
    border-left: 1px solid #000;
    border-right: 1px solid #000;
    border-top: 0px;
    border-bottom: 0px;
    text-align: right;
    white-space: nowrap;
    vertical-align: text-top;  
    font-size: 12px;   
    font-family: Trebuchet MS;      
  }   
  
  .vat {
    border-collapse: collapse;
    border: 1px solid #000;  
    font-size: 12px;   
    font-family: Trebuchet MS;   
  }  
  
  #vattdtitle {
    border: 1px solid #000;
    border-right: 1px solid #000;
    text-align: center;
    white-space: nowrap;
    vertical-align: text-top; 
    font-size: 12px;   
    font-family: Trebuchet MS;  
  }
  
  #totaltext
  {
    text-align: right;   
  }
  
  #totalnumber
  {
    text-align: right;
    border-top: 1px dashed;
    border-bottom: 1px solid;
    font-size: 12px;   
    font-family: Trebuchet MS;     
  }  

</style>