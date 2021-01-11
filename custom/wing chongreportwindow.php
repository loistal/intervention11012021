<?php

$report = $_POST['report'];
if ($report == "" && $_GET['report'] != "") { $report = $_GET['report']; }

$reportwindow = 1;
require ('inc/top.php');
function showtitle($title)
{
  echo '<TITLE>' . $title . '</TITLE></HEAD><BODY>';
}

# Go to the menuitem
switch($report)
{
  case 'barcodes':
  
  $barcode = 'VA001A1';
  $height = 200;
  $height_barcode = 120;
  $width = 300;
  $fontsize_b = 80;
  $framewidth = '29.4cm'; # 27cm   adjusted for "default" margins with Chrome
  $frameheight = '8.8cm'; # 8cm
  $frameheight_a = '6.2cm';
  $frameheight_b = '1.8cm';
  $frameheight_c = '0.8cm';
  
  $i=0;
  $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
  while ($line=fgets($fp))
  {
    $i++;
    $line = trim($line);
    $barcode = $line;
    $white_part = -2;
    $fontsize = 180;
    $fontsize_a = 180;
    if (strlen($line) == 8) { $white_part = -3; $fontsize = $fontsize_a = 170; }
    if (strlen($line) == 5) { $white_part = -3; } # latest correction
    if (substr($line,-2, 1) == 'A' || substr($line,-3, 1) == 'A')
    {
      echo '
      <div style="width: '.$framewidth.'; height: '.$frameheight_c.'; background: yellow; display:flex; justify-content:center; align-items:center;">
      </div>
      
      <div style="width: '.$framewidth.'; height: '.$frameheight_a.'; background: yellow; display:flex; justify-content:center; align-items:center;">
      <span style="font-size: ' . $fontsize . 'px; font-family: calibri; font-weight: bold; -webkit-transform:scale(1,1.2); ">
      ' . substr($barcode,0,$white_part) . '
      <span style="font-size: ' . $fontsize_a . 'px; background: white;">
      ' . substr($barcode,$white_part) . ' <img src="barcode.php?text=' . $barcode . '" width=' . $width . '; height=' . $height_barcode . '>
      </span>
      </span>&nbsp;
      </div>
      
      <div style="width: '.$framewidth.'; height: '.$frameheight_b.'; background: yellow; display:flex; justify-content:center; align-items:center;">
      <span style="font-size: ' . $fontsize_b . 'px; vertical-align: bottom; font-family: calibri; font-weight: bold; -webkit-transform:scale(1,0.78); ">
      &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &darr; &darr;
      </span>
      </div>
      ';
    }
    else
    {
      echo '
      <div style="width: '.$framewidth.'; height: '.$frameheight_b.'; background: yellow; display:flex; justify-content:center; align-items:center;">
      <span style="font-size: ' . $fontsize_b . 'px; font-family: calibri; font-weight: bold; -webkit-transform:scale(1,1); ">
      &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &uarr; &uarr;
      </span>
      </div>
      
      <div style="width: '.$framewidth.'; height: '.$frameheight_a.'; background: yellow; display:flex; justify-content:center; align-items:center;">
      <span style="font-size: ' . $fontsize . 'px; font-family: calibri; font-weight: bold; -webkit-transform:scale(1,1.2); ">
      ' . substr($barcode,0,$white_part) . '
      <span style="font-size: ' . $fontsize_a . 'px; background: white;">
      ' . substr($barcode,$white_part) . ' <img src="barcode.php?text=' . $barcode . '" width=' . $width . '; height=' . $height_barcode . '>
      </span>
      </span>&nbsp;
      </div>
      
      <div style="width: '.$framewidth.'; height: '.$frameheight_c.'; background: yellow; display:flex; justify-content:center; align-items:center;">
      </div>
      ';
    }
    if ($i%2==0) { echo '<p class=breakhere></p>'; }
    else { echo '<br><br>'; }
    if ($i%16==0) { echo '<br><br>'; }
  }
  
  /*
  echo '
  <div style="width: '.$framewidth.'; height: '.$frameheight_c.'; background: yellow; display:flex; justify-content:center; align-items:center;">
  </div>
  
  <div style="width: '.$framewidth.'; height: '.$frameheight_a.'; background: yellow; display:flex; justify-content:center; align-items:center;">
  <span style="font-size: ' . $fontsize . 'px; font-family: calibri; font-weight: bold; -webkit-transform:scale(1,1.2); ">
  ' . substr($barcode,0,-2) . '
  <span style="font-size: ' . $fontsize_a . 'px; background: white;">
  ' . substr($barcode,-2) . ' <img src="barcode.php?text=' . $barcode . '" width=' . $width . '; height=' . $height_barcode . '>
  </span>
  </span>&nbsp;
  </div>
  
  <div style="width: '.$framewidth.'; height: '.$frameheight_b.'; background: yellow; display:flex; justify-content:center; align-items:center;">
  <span style="font-size: ' . $fontsize_b . 'px; vertical-align: bottom; font-family: calibri; font-weight: bold; -webkit-transform:scale(1,0.78); ">
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &darr; &darr;
  </span>
  </div>
  ';
  
  
  echo '<br><br>'; $barcode = 'VA001A2';
  
  echo '
  <div style="width: '.$framewidth.'; height: '.$frameheight_b.'; background: yellow; display:flex; justify-content:center; align-items:center;">
  <span style="font-size: ' . $fontsize_b . 'px; font-family: calibri; font-weight: bold; -webkit-transform:scale(1,1); ">
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &uarr; &uarr;
  </span>
  </div>
  
  <div style="width: '.$framewidth.'; height: '.$frameheight_a.'; background: yellow; display:flex; justify-content:center; align-items:center;">
  <span style="font-size: ' . $fontsize . 'px; font-family: calibri; font-weight: bold; -webkit-transform:scale(1,1.2); ">
  ' . substr($barcode,0,-2) . '
  <span style="font-size: ' . $fontsize_a . 'px; background: white;">
  ' . substr($barcode,-2) . ' <img src="barcode.php?text=' . $barcode . '" width=' . $width . '; height=' . $height_barcode . '>
  </span>
  </span>&nbsp;
  </div>
  
  <div style="width: '.$framewidth.'; height: '.$frameheight_c.'; background: yellow; display:flex; justify-content:center; align-items:center;">
  </div>
  ';
  
  #echo '<p class=breakhere></p>';
  */
  
  break;

  case 'nestlebdl':
  #ini_set('display_errors', 'On');
  #error_reporting(E_ALL);
  

        ### OLD functions not to be used
        function d_table_old()
        {
          if ($_SESSION['ds_csv'] == 1)
          {
            if ($_SESSION['ds_csvfile'] == 1)
            {
              echo chr(13) . chr(10);
            }
            else
            {
              echo '<br>';
            }
          }
          else
          {
            echo '<table class="report">';
          }
        }

        function d_table_end_old()
        {
          if ($_SESSION['ds_csv'] == 1)
          {
            #echo 'EOF';
          }
          else
          {
            echo '</table>';
          }
        }

        function d_tr_remove($text)
        {
          if ($_SESSION['ds_csv'] == 1)
          {
            echo $text;
            if ($_SESSION['ds_csvfile'] == 1)
            {
              echo chr(13) . chr(10);
            }
            else
            {
              echo '<br>';
            }
          }
          else
          {
            echo '<tr>' . $text . '</tr>';
          }
        }

        function d_td_remove($text, $rightalign = 0, $bold = 0, $colspan = 0)
        {
          $text = d_output($text);
          if ($_SESSION['ds_csv'] == 1)
          {
          $text = str_replace('&nbsp;',' ',$text);
            $text = str_replace(';','',$text);
            if ($colspan > 1)
            {
              $result = $text;
              for($i=0;$i<$colspan;$i++)
              {
                $result = $result . ';';
              }
            }
            else
            {
              $result = $text . ';';
            }
          }
          else
          {
            $result = '<td';
            if ($rightalign == 1)
            {
              #$result .= ' class="d_td_right"';
              $result .= ' align=right';
            }
            else { $result .= ' class="d_td"'; }
            if ($colspan > 1) { $result .= ' colspan="' . $colspan . '"'; }
            $result .= '>';
            if ($bold == 1) { $result .= '<b>'; }
            $result .= $text .  '</td>';
          }
          return $result;
        }





        $ouruserid = $_POST['userid']+0;
        if ($ouruserid < 0) { $ouruserid = 0; }
        if ($_SESSION['ds_clientaccess'] == 1)
        {
          #$client = $_SESSION['ds_clientaccess_clientid'];
          #require ('inc/findclient.php');
          #if ($clientid < 1) { exit; }
          $clientid = $_SESSION['ds_clientaccess_clientid'];
          $ouruserid = 0;
        }
        elseif ($_POST['client'] == "") { $clientid = ""; }
        else
        {
          $client = $_POST['client'];
          if (!isset($client)) { $client = $_GET['client']; }
          require ('inc/findclient.php');
        }

        if ($clientid < 1 && $_POST['client'] != "")
        {
          echo '<form method="post" action="reportwindow.php"><table><tr><td>';
          require ('inc/selectclient.php');
          echo '</td></tr><tr><td colspan="2" align="center"><input type=hidden name="step" value="1">';
          echo '<input type=hidden name="day" value="' . $_POST['day'] . '">';
          echo '<input type=hidden name="month" value="' . $_POST['month'] . '">';
          echo '<input type=hidden name="year" value="' . $_POST['year'] . '">';
          echo '<input type=hidden name="stopday" value="' . $_POST['stopday'] . '">';
          echo '<input type=hidden name="stopmonth" value="' . $_POST['stopmonth'] . '">';
          echo '<input type=hidden name="stopyear" value="' . $_POST['stopyear'] . '">';
          echo '<input type=hidden name="userid" value="' . $ouruserid . '">';
          echo '<input type=hidden name="employeefid" value="' . $_POST['employeefid'] . '">';
          echo '<input type=hidden name="employeeid" value="' . $_POST['employeeid'] . '">';
          echo '<input type=hidden name="employee2id" value="' . $_POST['employee2id'] . '">';
          echo '<input type=hidden name="reference" value="' . $_POST['reference'] . '">';
          echo '<input type=hidden name="extraname" value="' . $_POST['extraname'] . '">';
          echo '<input type=hidden name="mychoice" value="' . $_POST['mychoice'] . '">';
          echo '<input type=hidden name="mychoice2" value="' . $_POST['mychoice2'] . '">';
          echo '<input type=hidden name="mychoice3" value="' . $_POST['mychoice3'] . '">';
          echo '<input type=hidden name="showvat" value="' . $_POST['showvat'] . '">';
          echo '<input type=hidden name="csv" value="' . $_POST['csv'] . '">';
          echo '<input type=hidden name="clientcategoryid" value="' . $_POST['clientcategoryid'] . '">';
          echo '<input type=hidden name="clientcategory2id" value="' . $_POST['clientcategory2id'] . '">';
          echo '<input type=hidden name="report" value="invoicereport"><input type="submit" value="Valider"></td></tr></table></form>';
        }

        else
        {

        require('preload/employee.php');
        require('preload/clientcategory.php');
        require('preload/clientcategory2.php');
        require('preload/clientterm.php');
        require('preload/localvessel.php');
        require('preload/returnreason.php');
        if ($_SESSION['ds_usedelivery'] > 0) { require('preload/deliverytype.php'); }

        $datefield = 'accountingdate'; $datedescr = $_SESSION['ds_term_accountingdate'];
        if ($_POST['datefield'] == 1) { $datefield = 'deliverydate'; $datedescr = $_SESSION['ds_term_deliverydate']; }
        if ($_POST['datefield'] == 2) { $datefield = 'invoicedate'; $datedescr = 'Date Saisie'; }
        if ($_POST['datefield'] == 3) { $datefield = 'paybydate'; $datedescr = 'Date Payable'; }

        $employeefid = $_POST['employeefid'];
        $employeeid = $_POST['employeeid'];
        $employee2id = $_POST['employee2id'];
        $clientcategoryid = $_POST['clientcategoryid'];
        $clientcategory2id = $_POST['clientcategory2id'];
        $clienttermid = $_POST['clienttermid'];
        $mychoice3 = $_POST['mychoice3'];
        $startid = $_POST['startid']+0;
        $stopid = $_POST['stopid']+0;

        $csv = $_POST['csv']+0;
        $csvfile = $_POST['csvfile']+0; # no longer used, always 0
        if ($csv == 1) 
        {
          $_SESSION['ds_csv'] = 1;
          require('preload/invoicetag.php');
        }
        else { $_SESSION['ds_csv'] = 0; }
        if ($csvfile == 1)
        {
          $_SESSION['ds_csvfile'] = 1;
        }
        else { $_SESSION['ds_csvfile'] = 0; }

        $total = 0; $totalht = 0; $totalvat = 0; $subtotal = 0; $subtotalht = 0; $subtotalvat = 0;
        $lastitem = -1;

        $datename = 'startdate'; require('inc/datepickerresult.php'); $date = $startdate;
        $datename = 'stopdate'; require('inc/datepickerresult.php');

        if ($stopdate < $date) { $stopdate = $date; }


        $ourtitle = 'Rapport des factures ';
        if ($_SESSION['ds_clientaccess'] == 1) { $ourtitle = 'Factures pour ' . $clientname . ' '; }

        if ($_POST['bynumber'] == 1) { $ourtitle = $ourtitle . ' numéros ' . $startid . ' à ' . $stopid; }
        else { $ourtitle = $ourtitle . datefix($date) . ' à ' . datefix($stopdate); }
        if ($csv && $csvfile)
        {
          #$sep = chr(13) . chr(10);
          #echo $ourtitle . $sep;
          echo $ourtitle;
        }
        else 
        {
          showtitle($ourtitle);
          echo '<h2>' . $ourtitle . '</h2>';
        }

        $findserial = 0;
        if ($_SESSION['ds_useserialnumbers'] && $_POST['serial'] != '')
        {
          $findserial = 1;
          $serial = $_POST['serial'];
          echo '<p><b>No Serie:</b> ' . d_output($serial) . '</p>';
        }

        $findfield1 = 0;
        if ($_POST['field1'] != '')
        {
          $findfield1 = 1;
          $field1 = $_POST['field1'];
          echo '<p><b>' . $_SESSION['ds_term_field1'] . ':</b> ' . d_output($field1) . '</p>';
        }

        $findfield2 = 0;
        if ($_POST['field2'] != '')
        {
          $findfield2 = 1;
          $field2 = $_POST['field2'];
          echo '<p><b>' . $_SESSION['ds_term_field2'] . ':</b> ' . d_output($field2) . '</p>';
        }

        if ($clientid != "")
        {
          $showclientname = $clientname . ' (' . $clientid . ')';
          echo '<p><b>Client:</b> ' . d_output($showclientname) . '</p>';
        }
        if ($clientcategoryid > 0)
        {
          echo '<p><b>Catégorie Client:</b> ' . $clientcategoryA[$clientcategoryid] . '</p>';
        }
        if ($clientcategory2id > 0)
        {
          echo '<p><b>Catégorie Client 2:</b> ' . $clientcategory2A[$clientcategory2id] . '</p>';
        }
        if ($clienttermid > 0)
        {
          echo '<p><b>Paiement:</b> ' . $clienttermA[$clienttermid] . '</p>';
        }
        if ($ouruserid > 0)
        {
          $query3 = 'select name from usertable where userid="' . $ouruserid . '"';
          $result3 = mysql_query($query3, $db_conn); querycheck($result3);
          $row3 = mysql_fetch_array($result3);
          $name = $row3['name'];
          echo '<p><b>Facturier:</b> ' . $name . '</p>';
        }
        if ($employeefid > -1)
        {
          if ($employeefid == 0) { echo '<p><b>Employé (facture):</b> &lt;Aucun&gt;</p>'; }
          else
          {
            $employeename = $employeeA[$employeefid];
            echo '<p><b>Employé (facture):</b> ' . $employeename . '</p>';
          }
        }
        $employeeid = -1;
        $employee2id = -1;
        /*
        if ($employeeid > -1)
        {
          if ($employeeid == 0) { echo '<p><b>Employé ' . $_SESSION['ds_term_clientemployee1'] . ':</b> &lt;Aucun&gt;</p>'; }
          else
          {
            $employeename = $employeeA[$employeeid];
            echo '<p><b>Employé ' . $_SESSION['ds_term_clientemployee1'] . ':</b> ' . $employeename . '</p>';
          }
        }
        if ($employee2id > -1)
        {
          if ($employee2id == 0) { echo '<p><b>Employé ' . $_SESSION['ds_term_clientemployee2'] . ':</b> &lt;Aucun&gt;</p>'; }
          else
          {
            $employeename = $employeeA[$employee2id];
            echo '<p><b>Employé ' . $_SESSION['ds_term_clientemployee2'] . ':</b> ' . $employeename . '</p>';
          }
        }
        */
        if ($_POST['reference'] != "")
        {
          echo '<p><b>';
          if ($_SESSION['ds_term_reference'] != "") { echo $_SESSION['ds_term_reference']; }
          else { echo 'Référence'; }
          if ($_POST['excluderef'] == 1) { echo ' (exclu)'; }
          echo ':</b> "' . $_POST['reference'] . '"</p>';
        }
        if ($_POST['extraname'] != "")
        {
          echo '<p><b>';
          if ($_SESSION['ds_term_extraname'] != "") { echo $_SESSION['ds_term_extraname']; }
          else { echo 'Extension du nom'; }
          echo ':</b> "' . $_POST['extraname'] . '"</p>';
        }
        if ($_POST['mychoice'] > 1)
        {
          echo '<p><b>Status:</b> ';
          if ($_POST['mychoice'] == 3) { echo 'Non confirmées'; }
          if ($_POST['mychoice'] == 2) { echo 'Confirmées'; }
          if ($_POST['mychoice'] == 8) { echo 'Confirmées et non lettrées'; }
          if ($_POST['mychoice'] == 9) { echo 'Lettrées'; }
          if ($_POST['mychoice'] == 4) { echo 'Annulées'; }
          echo '</p>';
        }
        if ($_POST['mychoice2'] > 1)
        {
          echo '<p><b>Type:</b> ';
          if ($_POST['mychoice2'] == 2) { echo 'Factures'; }
          if ($_POST['mychoice2'] == 5) { echo 'Avoirs'; }
          if ($_POST['mychoice2'] == 6) { echo 'Proforma'; }
          if ($_POST['mychoice2'] == 7) { echo 'Bons'; }
          echo '</p>';
        }
        $invoicetagid = $_POST['invoicetagid']+0;
        if ($invoicetagid > 0)
        {
          require('preload/invoicetag.php');
          echo '<p><b>' . $_SESSION['ds_term_invoicetag'];
          if ($_POST['excludetag'] == 1) { echo ' (exclu)'; }
          echo ':</b> ' . d_output($invoicetagA[$invoicetagid]) . '</p>';
        }
        if ($invoicetagid == 0 && $_POST['excludetag'] == 1)
        {
          echo '<p><b>' . $_SESSION['ds_term_invoicetag'] . ' non defini exclus</p>';
        }
        # "NC" as exported,
        $query = 'select field1,field2,returntostock,returnreasonid,localvesselid,deliverytypeid,invoicetagid,invoice.employeeid,initials,isreturn,isnotice,proforma,accountingdate,deliverydate,' . $datefield . ' as ourdate,invoice.invoiceid,client.clientid as clientid,clientname,extraname,name,invoicevat,invoiceprice,cancelledid,confirmed,matchingid,invoicecomment,reference
        from invoice,client,usertable,employee';
        $query .= ' where invoice.userid=usertable.userid and invoice.clientid=client.clientid and invoice.employeeid=employee.employeeid and employee.employeecategoryid=1 and isreturn=0';
        #if ($findserial) { $query .= ' and serial like "%' . $serial . '%"'; }
        #if ($findfield1) { $query .= ' and field1 like "%' . $field1 . '%"'; }
        #if ($findfield2) { $query .= ' and field2 like "%' . $field2 . '%"'; }
        if ($_POST['bynumber'] == 1) { $query = $query . ' and invoice.invoiceid>="' . $startid . '" and invoice.invoiceid<="' . $stopid . '"'; }
        else { $query = $query . ' and ' . $datefield . '>="' . $date . '" and ' . $datefield . '<="' . $stopdate . '"'; }
        if ($_POST['mychoice'] == 1) { $query = $query . ' and cancelledid=0'; }
        if ($_POST['mychoice'] == 2) { $query = $query . ' and confirmed=1 and cancelledid=0'; }
        if ($_POST['mychoice'] == 3) { $query = $query . ' and confirmed=0 and cancelledid=0'; }
        if ($_POST['mychoice'] == 4) { $query = $query . ' and cancelledid>0'; }
        if ($_POST['mychoice2'] == 2) { $query = $query . ' and isreturn=0 and proforma=0 and isnotice=0'; }
        if ($_POST['mychoice2'] == 5) { $query = $query . ' and isreturn=1'; }
        if ($_POST['mychoice2'] == 6) { $query = $query . ' and proforma=1'; }
        if ($_POST['mychoice2'] == 7) { $query = $query . ' and isnotice=1'; }
        if ($_POST['mychoice2'] == 8) { $query = $query . ' and isnotice=1 and isreturn=1'; }
        if ($_POST['mychoice'] == 8) { $query = $query . ' and confirmed=1 and matchingid=0 and cancelledid=0'; }
        if ($_POST['mychoice'] == 9) { $query = $query . ' and confirmed=1 and matchingid>0 and cancelledid=0'; }
        if ($ouruserid > 0 && $_SESSION['ds_clientaccess'] == 0) { $query = $query . ' and invoice.userid=' . $ouruserid; }
        if ($employeefid > -1) { $query = $query . ' and invoice.employeeid=' . $employeefid; }
        if ($employeeid > -1) { $query = $query . ' and client.employeeid=' . $employeeid; }
        if ($employee2id > -1) { $query = $query . ' and client.employeeid2=' . $employee2id; }
        if ($_POST['reference'] != "")
        {
          if ($_POST['excluderef'] == 1) { $query = $query . ' and lower(reference) not like "%' . mb_strtolower($_POST['reference']) . '%"'; }
          else { $query = $query . ' and lower(reference) like "%' . mb_strtolower($_POST['reference']) . '%"'; }
        }
        if ($_POST['extraname'] != "") { $query = $query . ' and extraname like "%' . $_POST['extraname'] . '%"'; }
        if ($clientcategoryid > 0) { $query = $query . ' and client.clientcategoryid="' . $clientcategoryid . '"'; }
        if ($clientcategory2id > 0) { $query = $query . ' and client.clientcategory2id="' . $clientcategory2id . '"'; }
        if ($clienttermid > 0) { $query = $query . ' and client.clienttermid="' . $clienttermid . '"'; }
        if ($clientid != "") { $query = $query . ' and invoice.clientid=' . $clientid; }
        if ($invoicetagid > 0 || $_POST['excludetag'] == 1)
        {
          $query = $query . ' and invoice.invoicetagid';
          if ($_POST['excludetag'] == 1) { $query = $query . '<>'; }
          else { $query = $query . '='; }
          $query = $query . $invoicetagid;
        }
        if ($_SESSION['ds_allowedclientlist'] != '') { $query = $query . ' and invoice.clientid in ' . $_SESSION['ds_allowedclientlist']; }
        if ($_SESSION['ds_confirmonlyown'] == 1)
        {
          $queryadd = ' and (invoice.userid="'.$_SESSION['ds_userid'].'"';
          if ($_SESSION['ds_myemployeeid'] > 0)
          {
            $queryadd .= ' or invoice.employeeid="'.$_SESSION['ds_myemployeeid'].'"';
          }
          $query .= $queryadd.')';
        }
        $query = $query . ' UNION '; # invoicehistory.exported,
        $query = $query . 'select field1,field2,returntostock,returnreasonid,localvesselid,deliverytypeid,invoicetagid,invoicehistory.employeeid,initials,isreturn,isnotice,proforma,accountingdate,deliverydate,' . $datefield . ' as ourdate,invoicehistory.invoiceid,client.clientid as clientid,clientname,extraname,name,invoicevat,invoiceprice,cancelledid,confirmed,matchingid,invoicecomment,reference
        from invoicehistory,client,usertable,employee';
        $query .= ' where invoicehistory.userid=usertable.userid and invoicehistory.clientid=client.clientid and invoicehistory.employeeid=employee.employeeid and employee.employeecategoryid=1 and isreturn=0';
        #if ($findserial) { $query .= ' serial like "%' . $serial . '%"'; }
        #if ($findfield1) { $query .= ' and field1 like "%' . $field1 . '%"'; }
        #if ($findfield2) { $query .= ' and field2 like "%' . $field2 . '%"'; }
        if ($_POST['bynumber'] == 1) { $query = $query . ' and invoicehistory.invoiceid>="' . $startid . '" and invoicehistory.invoiceid<="' . $stopid . '"'; }
        else { $query = $query . ' and ' . $datefield . '>="' . $date . '" and ' . $datefield . '<="' . $stopdate . '"'; }
        if ($_POST['mychoice'] == 1) { $query = $query . ' and cancelledid=0'; }
        if ($_POST['mychoice'] == 2) { $query = $query . ' and confirmed=1 and cancelledid=0'; }
        if ($_POST['mychoice'] == 3) { $query = $query . ' and confirmed=0 and cancelledid=0'; }
        if ($_POST['mychoice'] == 4) { $query = $query . ' and cancelledid>0'; }
        if ($_POST['mychoice2'] == 2) { $query = $query . ' and isreturn=0 and proforma=0 and isnotice=0'; }
        if ($_POST['mychoice2'] == 5) { $query = $query . ' and isreturn=1'; }
        if ($_POST['mychoice2'] == 6) { $query = $query . ' and proforma=1'; }
        if ($_POST['mychoice2'] == 7) { $query = $query . ' and isnotice=1'; }
        if ($_POST['mychoice2'] == 8) { $query = $query . ' and isnotice=1 and isreturn=1'; }
        if ($_POST['mychoice'] == 8) { $query = $query . ' and confirmed=1 and matchingid=0 and cancelledid=0'; }
        if ($_POST['mychoice'] == 9) { $query = $query . ' and confirmed=1 and matchingid>0 and cancelledid=0'; }
        if ($ouruserid > 0 && $_SESSION['ds_clientaccess'] == 0) { $query = $query . ' and invoicehistory.userid=' . $ouruserid; }
        if ($employeefid > -1) { $query = $query . ' and invoicehistory.employeeid=' . $employeefid; }
        if ($employeeid > -1) { $query = $query . ' and client.employeeid=' . $employeeid; }
        if ($employee2id > -1) { $query = $query . ' and client.employeeid2=' . $employee2id; }
        if ($_POST['reference'] != "")
        {
          if ($_POST['excluderef'] == 1) { $query = $query . ' and reference not like "%' . $_POST['reference'] . '%"'; }
          else { $query = $query . ' and reference like "%' . $_POST['reference'] . '%"'; }
        }
        if ($_POST['extraname'] != "") { $query = $query . ' and extraname like "%' . $_POST['extraname'] . '%"'; }
        if ($clientcategoryid > 0) { $query = $query . ' and client.clientcategoryid="' . $clientcategoryid . '"'; }
        if ($clientcategory2id > 0) { $query = $query . ' and client.clientcategory2id="' . $clientcategory2id . '"'; }
        if ($clienttermid > 0) { $query = $query . ' and client.clienttermid="' . $clienttermid . '"'; }
        if ($clientid != "") { $query = $query . ' and invoicehistory.clientid=' . $clientid; }
        if ($invoicetagid > 0 || $_POST['excludetag'] == 1)
        {
          $query = $query . ' and invoicehistory.invoicetagid';
          if ($_POST['excludetag'] == 1) { $query = $query . '<>'; }
          else { $query = $query . '='; }
          $query = $query . $invoicetagid;
        }
        if ($_SESSION['ds_allowedclientlist'] != '') { $query = $query . ' and invoicehistory.clientid in ' . $_SESSION['ds_allowedclientlist']; }
        if ($_SESSION['ds_confirmonlyown'] == 1)
        {
          $queryadd = ' and (invoicehistory.userid="'.$_SESSION['ds_userid'].'"';
          if ($_SESSION['ds_myemployeeid'] > 0)
          {
            $queryadd .= ' or invoicehistory.employeeid="'.$_SESSION['ds_myemployeeid'].'"';
          }
          $query .= $queryadd.')';
        }
        if ($mychoice3 == 1) { $query = $query . ' order by invoiceid'; }
        if ($mychoice3 == 2) { $query = $query . ' order by clientid,accountingdate,invoiceid'; }
        if ($mychoice3 == 3) { $query = $query . ' order by reference,invoiceid'; }
        if ($mychoice3 == 4) { $query = $query . ' order by field1,invoiceid'; }
        if ($mychoice3 == 5) { $query = $query . ' order by field2,invoiceid'; }
        if ($_SESSION['ds_sqllimit'] > 0) { $query = $query . ' limit ' . $_SESSION['ds_sqllimit']; }
        #if ($_SESSION['ds_userid'] == 1) { echo $query; }
        $result2 = mysql_query($query, $db_conn); querycheck($result2);
        $num_results2 = mysql_num_rows($result2);
        if ($_SESSION['ds_sqllimit'] > 0 && $num_results2 == $_SESSION['ds_sqllimit']) { echo '<p class=alert>Limite de ' . $_SESSION['ds_sqllimit'] . ' atteinte.</p>'; }

        d_table_old();
        $header = d_td_remove("Facture",0,1);
        #if ($ouruserid == 0) { $header .= d_td_remove("Facturier",0,1); }
        if ($employeefid == -1) { $header .= d_td_remove("Employé",0,1); }
        #if ($_SESSION['ds_term_accountingdate'] != "") { $header .= d_td_remove($_SESSION['ds_term_accountingdate'],0,1); }
        #else { $header .= d_td_remove("Date",0,1); }
        $header .= d_td_remove($_SESSION['ds_term_accountingdate'],0,1);
        #if ($datefield != 'accountingdate') { 
        $header .= d_td_remove($_SESSION['ds_term_deliverydate'],0,1);
        #}
        if ($_POST['datefield'] > 1)
        {
          $header .= d_td_remove($datedescr,0,1);
        }
        /*
        if ($_SESSION['ds_hidedeliverydate'] == 1)
        {
          // do nothing
        }
        else
        {
          if ($_SESSION['ds_term_deliverydate'] != "") { $header .= d_td_remove($_SESSION['ds_term_deliverydate'],0,1); }
          else { $header .= d_td_remove('Livraison',0,1); }
        }
        */
        if ($_SESSION['ds_term_field1'] != "") { $header .= d_td_remove($_SESSION['ds_term_field1'],0,1); }
        if ($_SESSION['ds_term_field2'] != "") { $header .= d_td_remove($_SESSION['ds_term_field2'],0,1); }
        if ($_SESSION['ds_clientaccess'] == 0 && $clientid == "")
        {
          if ($_SESSION['ds_csv'] == 1) { $header .= d_td_remove("Num client",0,1); $header .= d_td_remove("Nom client",0,1); }
          else { $header .= d_td_remove("Client",0,1); }
        }
        /*
        if ($_SESSION['ds_allowsalesreportsvalues'])
        {
          if ($_POST['showvat'] == 1 || $_SESSION['ds_csv'] == 1) { $header .= d_td_remove("HT",0,1); $header .= d_td_remove("TVA",0,1); }
          $header .= d_td_remove("TTC",0,1);
        }
        if ($_SESSION['ds_term_reference'] != "") { $header .= d_td_remove($_SESSION['ds_term_reference'],0,1); }
        else { $header .= d_td_remove('Référence',0,1); }
        $header .= d_td_remove('Commentaires',0,1);
        if ($_POST['mychoice'] == 1) { $header .= d_td_remove('Status',0,1); }
        if ($_SESSION['ds_csv'] == 1) { $header .= d_td_remove($_SESSION['ds_term_invoicetag'],0,1); }
        if ($_SESSION['ds_usedelivery'] > 0) { $header .= d_td_remove('Livraison',0,1); }
        if (isset($localvesselA)) { $header .= d_td_remove('Bateau',0,1); }
        */
        d_tr_remove($header);

        $invoiceA = array(); $lastinvoicetoopen = 0; $maxopeninvoices = 50; # TODO 50 for now
        $ourspaces = 5; # TODO fix
        if ($_SESSION['ds_term_field1'] != "") { $ourspaces++; }
        if ($_SESSION['ds_term_field2'] != "") { $ourspaces++; }
        if ($_POST['datefield'] > 1) { $ourspaces++; }
        if ($clientid != 0) { $ourspaces--; }
        #if ($ouruserid > 0) { $ourspaces--; }
        if ($employeefid != -1) { $ourspaces--; }
        #if ($_SESSION['ds_hidedeliverydate'] == 1) { $ourspaces--; }
        unset($clientcount);
        for ($y=1; $y <= $num_results2; $y++)
        {
          $row2 = mysql_fetch_array($result2);
          if ($y < $num_results2 && $y < $maxopeninvoices) { array_push($invoiceA, $row2['invoiceid']); } # for javascript, see below
          if ($y == $maxopeninvoices) { $lastinvoicetoopen = $row2['invoiceid']; }
          $clientcount[$row2['clientid']] = 1;
          if ($mychoice3 == 2) # clientid subtotal
          {
            if ($lastitem != $row2['clientid'] && $y != 1 && $_SESSION['ds_allowsalesreportsvalues'])
            {
              /*
              echo $d_tr;
              echo '<td colspan=' . $colspan . '>&nbsp;</td>';
              echo $d_tdh . 'Total client' . $d_tdhx;
              if ($_POST['showvat'] == 1) { echo '<td align=right><b>' . myfix($subtotalht) . '</td><td align=right><b>' . myfix($subtotalvat) . '</td>'; }
              echo '<td align=right><b>' . myfix($subtotal) . '</td><td colspan=3>&nbsp;</td></tr>';
              */
              $header = d_td_remove("&nbsp;",0,0, $ourspaces);
              if ($_SESSION['ds_csv'] == 1) { $header .= d_td_remove("&nbsp;",0,0); }
              $header .= d_td_remove("Total client",0,1);
              if ($_SESSION['ds_allowsalesreportsvalues'])
              {
                if ($_POST['showvat'] == 1) { $header .= d_td_remove(myfix($subtotalht),1,1); $header .= d_td_remove(myfix($subtotalvat),1,1); }
                $header .= d_td_remove(myfix($subtotal),1,1);
              }
              $header .= d_td_remove("&nbsp;",0,0,5);
              d_tr_remove($header);
              $subtotal = 0; $subtotalht = 0; $subtotalvat = 0;
            }
            $lastitem = $row2['clientid'];
          }
          
          $status = '<font color="' . $_SESSION['ds_alertcolor'] . '"></font>';
          if ($row2['confirmed'] == 0) { $status = 'Non confirmée'; }
          if ($row2['confirmed'] == 1) { $status = 'Confirmée'; }
          if ($row2['matchingid'] > 0) { $status = 'Lettrée'; }
          if ($row2['cancelledid'] > 0) { $status = 'Annulée'; }
          $deliveryagent = "";
          #echo '<tr>';
          $showinvoiceid = $row2['invoiceid'];
          $negative = 0;
          if ($row2['proforma'] == 1) { $showinvoiceid = '(Proforma) ' . $showinvoiceid; }
          if ($row2['isnotice'] == 1) { $showinvoiceid = '(Bon) ' . $showinvoiceid; }
          if ($row2['isreturn'] == 1) { $showinvoiceid = '(Avoir) ' . $showinvoiceid; $negative = 1; }
          #echo '<td align=right><a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $row2['invoiceid'] . '" target=_blank>' . $showinvoiceid . '</a></td>';
          $kladd = d_td_remove($showinvoiceid,1); #$kladd = d_td_remove($showinvoiceid . ' ' . $row2['exported'],1);
          #'<a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $row2['invoiceid'] . '" target=_blank>' '</a>'
          # hack to insert link
          $kladd = str_replace('<td align=right>', '<td align=right><a href="printwindow.php?report=showinvoice&invoiceid=' . $row2['invoiceid'] . '" target=_blank>', $kladd);
          $kladd = str_replace('</td>', '</a></td>', $kladd);
          $line = $kladd;
          #if ($ouruserid == 0) { echo '<td>' . $row2['name'] . '</td>'; }
          #if ($ouruserid == 0) { $line .= d_td_remove($row2['name']); }
          if ($employeefid == -1)
          { 
            $employeename = '&nbsp;';
            if ($row2['employeeid'] > 0)
            {
              $employeeid = $row2['employeeid'];
              $employeename = $employeeA[$employeeid];
            }
            #echo '<td>' . $employeename . '</td>';
            $line .= d_td_remove($employeename);
          }
          #echo '<td>' . datefix2($row2['accountingdate']) . '</td>';
          if ($_SESSION['ds_csv'] == 1) { $line .= d_td_remove(date("d m y",strtotime($row2['accountingdate']))); }
          else { $line .= d_td_remove(datefix2($row2['accountingdate'])); }
          #if ($datefield != 'accountingdate')
          #{
            if ($_SESSION['ds_csv'] == 1) { $line .= d_td_remove(date("d m y",strtotime($row2['deliverydate']))); }
            else { $line .= d_td_remove(datefix2($row2['deliverydate'])); }
          #}
          if ($_POST['datefield'] > 1)
          {
            if ($_SESSION['ds_csv'] == 1) { $line .= d_td_remove(date("d m y",strtotime($row2['ourdate']))); }
            else { $line .= d_td_remove(datefix2($row2['ourdate'])); }
          }
          /*
          if ($_SESSION['ds_hidedeliverydate'] == 1)
          {
            // do nothing
          }
          else
          {
            #echo '<td>' . datefix2($row2['deliverydate']) . '</td>';
            if ($_SESSION['ds_csv'] == 1) { $line .= d_td_remove(date("d m y",strtotime($row2['deliverydate']))); }
            else { $line .= d_td_remove(datefix2($row2['deliverydate'])); }
          }
          */
          if ($_SESSION['ds_term_field1'] != "") { $line .= d_td_remove($row2['field1']); }
          if ($_SESSION['ds_term_field2'] != "") { $line .= d_td_remove($row2['field2']); }
          if ($_SESSION['ds_clientaccess'] == 0 && $clientid == "")
          {
            if ($_SESSION['ds_csv'] == 1)
            {
              $line .= d_td_remove($row2['clientid']);
              $line .= d_td_remove(d_decode($row2['clientname']));
            }
            else
            {
              #$showclientname =  $row2['clientid'] . ': ' . d_output(d_decode($row2['clientname'])) . ' ' . $row2['extraname'];
              $showclientname = d_decode($row2['clientname']) . ' ' . $row2['extraname'] . ' (' . $row2['clientid'] . ')';

              $kladd = d_td_remove($showclientname);
              #$kladd = str_replace('<td class="d_td">', '<td class="d_td">'.'<a href="printwindow.php?report=showclient&usedefaultstyle=1&client=' . $row2['clientid'] . '" target=_blank>', $kladd);
              #$kladd = str_replace('</td>', '</a></td>', $kladd);
              $line .= $kladd;
            }
          }
          /*
          if ($_SESSION['ds_allowsalesreportsvalues'])
          {
            if ($_POST['showvat'] == 1 || $_SESSION['ds_csv'] == 1)
            {
              /*
              echo '<td align=right>';
              if ($negative) { echo '-'; }
              echo myfix($row2['invoiceprice'] - $row2['invoicevat']) . '</td><td align=right>';
              if ($negative) { echo '-'; }
              echo myfix($row2['invoicevat']) . '</td>';
              
              $kladd = myfix($row2['invoiceprice'] - $row2['invoicevat']); if ($negative) { $kladd = '-'.$kladd; }
              $line .= d_td_remove($kladd,1);
              $kladd = myfix($row2['invoicevat']); if ($negative) { $kladd = '-'.$kladd; }
              $line .= d_td_remove($kladd,1);
            }
          #echo '<td align=right>';
          #if ($negative) { echo '-'; }
            $kladd = myfix($row2['invoiceprice']); if ($negative) { $kladd = '-'.$kladd; }
            $line .= d_td_remove($kladd,1);
          }
          $line .= d_td_remove($row2['reference']);
          $invoicecomment = $row2['invoicecomment'];
          if ($row2['returnreasonid'] > 0) { $invoicecomment = $returnreasonA[$row2['returnreasonid']].'&nbsp;'.$invoicecomment; }
          if ($row2['returntostock'] == 1) { $invoicecomment = '[retour&nbsp;march.]&nbsp;'.$invoicecomment; }
          $line .= d_td_remove($invoicecomment);
          if ($_POST['mychoice'] == 1)
          {
            $line .= d_td_remove($status);
          }
          #echo '</tr>';
          if ($_SESSION['ds_csv'] == 1)
          {
            $line .= d_td_remove($invoicetagA[$row2['invoicetagid']]);
          }
          if ($_SESSION['ds_usedelivery'] > 0)
          {
            $line .= d_td_remove($deliverytypeA[$row2['deliverytypeid']]);
          }
          if (isset($localvesselA)) { $line .= d_td_remove($localvesselA[$row2['localvesselid']]); }
          */
          d_tr_remove($line);
          if ($negative)
          {
            #$total = $total - str_replace("&nbsp;", "",myfix($row2['invoiceprice']));
            $total = $total - $row2['invoiceprice'];
            $subtotal = $subtotal - $row2['invoiceprice'];
            if ($_POST['showvat'] == 1 || $_SESSION['ds_csv'] == 1)
            {
              #$kladdht = str_replace("&nbsp;", "",myfix($row2['invoiceprice'])) - str_replace("&nbsp;", "",myfix($row2['invoicevat']));
              $kladdht = $row2['invoiceprice'] - $row2['invoicevat'];
              $totalht = $totalht - $kladdht;
              $subtotalht = $subtotalht - $kladdht;
              #$totalvat = $totalvat - str_replace("&nbsp;", "",myfix($row2['invoicevat']));
              $totalvat = $totalvat - $row2['invoicevat'];
              $subtotalvat = $subtotalvat - $row2['invoicevat'];
            }
          }
          else
          {
            #$total = $total + str_replace("&nbsp;", "",myfix($row2['invoiceprice']));
            $total = $total + $row2['invoiceprice'];
            $subtotal = $subtotal + $row2['invoiceprice'];
            if ($_POST['showvat'] == 1 || $_SESSION['ds_csv'] == 1)
            {
              #$kladdht = str_replace("&nbsp;", "",myfix($row2['invoiceprice'])) - str_replace("&nbsp;", "",myfix($row2['invoicevat']));
              $kladdht = $row2['invoiceprice'] - $row2['invoicevat'];
              $totalht = $totalht + $kladdht;
              $subtotalht = $subtotalht + $kladdht;
              #$totalvat = $totalvat + str_replace("&nbsp;", "",myfix($row2['invoicevat']));
              $totalvat = $totalvat + $row2['invoicevat'];
              $subtotalvat = $subtotalvat + $row2['invoicevat'];
            }
          }
        }

        if ($mychoice3 == 2 && $_SESSION['ds_allowsalesreportsvalues']) # clientid subtotal, copy from above
        {
          $header = d_td_remove("&nbsp;",0,0, $ourspaces);
          if ($_SESSION['ds_csv'] == 1) { $header .= d_td_remove("&nbsp;",0,0); }
          $header .= d_td_remove("Total client",0,1);
          if ($_SESSION['ds_allowsalesreportsvalues'])
          {
            if ($_POST['showvat'] == 1) { $header .= d_td_remove(myfix($subtotalht),1,1); $header .= d_td_remove(myfix($subtotalvat),1,1); }
            $header .= d_td_remove(myfix($subtotal),1,1);
          }
          $header .= d_td_remove("&nbsp;",0,0,5);
          d_tr_remove($header);
        }

        /*
        echo '<tr><td><b>Total</td><td colspan="' . $ourspaces . '">&nbsp;</td>';
        if ($_POST['showvat'] == 1) { echo '<td align=right><b>' . myfix($totalht) . '</td><td align=right><b>' . myfix($totalvat) . '</td>'; }
        echo '<td align=right><b>' . myfix($total) . '</td><td colspan=3>&nbsp;</td></tr>';
        echo '</table>';
        */

        d_table_end_old();

        }

        $_SESSION['ds_csv'] = 0;
        $_SESSION['ds_csvfile'] = 0;

  
  break;
  
  
  
  case 'palletcountreport':
  
    require('preload/placement.php');
    require('preload/unittype.php');

    $lastyear = (substr($_SESSION['ds_curdate'],0,4)-1);
    if (substr($_SESSION['ds_curdate'],5,2) == 12) { $lastyear++; }
    $show_values = (int) $_POST['show_values'];
    $warehouseid = (int) $_POST['warehouseid'];
    
    if ($show_values) { $_POST['updateendyearstock'] = 0; }
    if ($_POST['updateendyearstock'] == 1) # IMPORTANT
    {
      $query = 'update endofyearstock set stock=0 where year=?';
      $query_prm = array($lastyear);
      require('inc/doquery.php');
      echo '<p>Stock fin année modifié...</p>';
    }
      
    echo '<h2>Rapport Inventaire par Produit ',$lastyear,'</h2>';
    if ($_POST['exnestle'] == 1) { $exnestle = 1; echo '<p>Nestlé exclu</p>'; }
    
    $query = 'select stock,productid from monthlystock where month=12 and year=?';
    $query_prm = array($lastyear);
    require('inc/doquery.php');
    for ($i=0;$i<$num_results;$i++)
    {
      $pid = $query_result[$i]['productid'];
      $lastyearstockA[$pid] = $query_result[$i]['stock'];
    }#echo $lastyearstockA[4040],' ',$lastyear;

    #$query = 'select barcode,pallet_counted.productid,productname,numberperunit,netweightlabel,quantity,quantityrest,expiredate,placementid from pallet_counted,product where pallet_counted.productid=product.productid order by productid,expiredate';
    $query = 'select unittypeid,barcode,pallet_counted.productid,productname,numberperunit,netweightlabel,quantity,quantityrest,expiredate,placementid,productdepartmentname,productfamilygroupname,productfamilyname
    from pallet_counted,product,productfamily pf,productfamilygroup pfg,productdepartment pd
    where pallet_counted.productid=product.productid and product.productfamilyid=pf.productfamilyid and pf.productfamilygroupid=pfg.productfamilygroupid and pfg.productdepartmentid=pd.productdepartmentid';
    if ($exnestle == 1) { $query .= ' and supplierid<>4126'; }
#$query.=' and pallet_counted.productid=4750';
    $query .= ' order by departmentrank,productdepartmentname,familygrouprank,productfamilygroupname,familyrank,productfamilyname,productname,productid,expiredate';
    $query_prm = array();
    require('inc/doquery.php');
    $main_result = $query_result; $num_results_main = $num_results;
    echo '<table class=report><tr><td colspan=2><b>Produit</td><td><b>Emplacement</td><td><b>DLV</td><td><b>Quantité (carton)</td>';
    if ($show_values)
    {
      echo '<td><b>PRev</td><td><b>Valeur</td></tr>';
    }
    else
    {
      echo '<td><b>31/12/'.$lastyear.'</td><td><b>Ecart</td></tr>';
    }
    $lastproductid = -1; $subtotal = 0; $lastnpu = 1; $subtotalunits = 0; $lastpfn = 'error';
    for ($i=0;$i<$num_results_main;$i++)
    {
      #
      $foundproductsA[$i] = $main_result[$i]['productid'];
      $foundplacementA[$i] = $main_result[$i]['placementid'];
      #
      if ($main_result[$i]['productfamilyname'] != $lastpfn)
      {
        echo '<tr><td colspan=20><b>' . $main_result[$i]['productdepartmentname'] .'/' . $main_result[$i]['productfamilygroupname'] .'/'. $main_result[$i]['productfamilyname'];
      }
      $lastpfn = $main_result[$i]['productfamilyname'];
      $showthisline = 1;
      if ($warehouseid > 0)
      {
        if ($placement_warehouseidA[$main_result[$i]['placementid']] != $warehouseid) { $showthisline = 0; }
      }
      if ($showthisline)
      {
        echo '<tr><td align=right>' . $main_result[$i]['productid'] . '</td><td>' . $main_result[$i]['productname'] . ' ';
        if ($_SESSION['ds_useunits'] && $main_result[$i]['numberperunit'] > 1) { echo $main_result[$i]['numberperunit'] . ' x '; }
        echo $main_result[$i]['netweightlabel']
        .'<td align=right>';
        if (isset($placementA[$main_result[$i]['placementid']])) { echo $placementA[$main_result[$i]['placementid']]; }
        echo '<td align=right>' . datefix2($main_result[$i]['expiredate'])
        .'<td align=right>' . $main_result[$i]['quantity']/$unittype_dmpA[$main_result[$i]['unittypeid']];
        if ($main_result[$i]['quantityrest'] > 0)
        {
          echo '<font size=-2>' . $main_result[$i]['quantityrest'] . '</font>';
          $subtotalunits = $subtotalunits + $main_result[$i]['quantityrest'];
        }
        if ($show_values)
        {
          $query = 'select cost,prev from purchasebatch where productid=? and year(arrivaldate)=? order by arrivaldate desc limit 10';
          $query_prm = array($main_result[$i]['productid'], $lastyear);
          require('inc/doquery.php');
          $y = 0; $done = 0;
          while (!$done)
          {
            $cost = round($query_result[$y]['cost'] * $main_result[$i]['numberperunit']);
            if ($unittype_dmpA[$main_result[$i]['unittypeid']] > 1) { $cost = $cost / $unittype_dmpA[$main_result[$i]['unittypeid']]; }
            if ($cost == 0) { $cost = $query_result[$y]['prev']+0; }
            $y++;
            if ($cost > 0 || $y >= 10) { $done = 1; }
          }
          echo '<td align=right>',$cost,'<td align=right>',myfix($cost*$main_result[$i]['quantity']);
        }
        else
        {
          echo '<td><td>';
        }
      }
      $lastproductid = $main_result[$i]['productid'];
      $lastnpu = $main_result[$i]['numberperunit']; if ($lastnpu < 1) { $lastnpu = 1; }
      $subtotal = $subtotal + $main_result[$i]['quantity'];
      if ((!isset($main_result[$i+1]['productid']) || $main_result[$i]['productid'] != $main_result[$i+1]['productid']) && !$show_values)
      {
        if ($subtotalunits > 0)
        {
          $subtotal = $subtotal + floor($subtotalunits/$lastnpu);
          $showsubtotal = $subtotal . '<font size=-2>' . $subtotalunits%$lastnpu . '</font>';
        }
        else
        {
          $showsubtotal = $subtotal/$unittype_dmpA[$main_result[$i]['unittypeid']];
        }
        if (!isset($lastyearstockA[$main_result[$i]['productid']])) { $lastyearstockA[$main_result[$i]['productid']] = 0; }
        $currentstockrest = $lastyearstockA[$main_result[$i]['productid']] % $lastnpu;
        $currentstock = floor($lastyearstockA[$main_result[$i]['productid']] / $lastnpu) * $unittype_dmpA[$main_result[$i]['unittypeid']];
        echo '<tr><td colspan=4>&nbsp;
        <td align=right><b>' . $showsubtotal . '<td align=right>';
        if (!$show_values) { echo $currentstock/$unittype_dmpA[$main_result[$i]['unittypeid']]; }
        if ($currentstockrest > 0) { echo ' <font size=-2>' . $currentstockrest . '</font>'; }
        $diff = ($subtotal * $lastnpu) + $subtotalunits - ($currentstock * $lastnpu) - $currentstockrest;
        ###
        
        if (isset($_POST['updateendyearstock']) && $_POST['updateendyearstock'] == 1)
        {
          $kladd = ($subtotal * $lastnpu) + $subtotalunits;
          #if ($unittype_dmpA[$main_result[$i]['unittypeid']] > 1) { $kladd = $kladd / $unittype_dmpA[$main_result[$i]['unittypeid']]; }
          ##
          $query = 'select endofyearstockid from endofyearstock where productid=? and year=?';
          $query_prm = array($main_result[$i]['productid'],$lastyear);
          require('inc/doquery.php');
          if ($num_results) { $query = 'update endofyearstock set stock=? where productid=? and year=?'; }
          else { $query = 'insert into endofyearstock (stock,productid,year) values (?,?,?)'; }
          $query_prm = array($kladd,$main_result[$i]['productid'],$lastyear);
          require('inc/doquery.php');
          #echo $query; var_dump($query_prm);
          ##
        }
        
        ###
        echo '</td><td align=right>';
        if (!$show_values)
        {
          $diffshow = d_abs($diff);
          $diffunits = $diffshow % $lastnpu;
          $diffshow = floor($diffshow / $lastnpu);
          if ($diff < 0) { echo '-'; }
          echo $diffshow/$unittype_dmpA[$main_result[$i]['unittypeid']];
          if ($diffunits > 0) { echo ' <font size=-2>' . $diffunits . '</font>'; }
        }
        $subtotal = 0; $subtotalunits = 0;
      }
    }
    echo '</table>';

    $foundproductsA = array_filter(array_unique($foundproductsA));
    sort($foundproductsA);
    $foundproducts = '(';
    foreach ($foundproductsA as $kladd)
    {
      $foundproducts .= $kladd . ',';
    }
    $foundproducts = rtrim($foundproducts,',') . ')';
    if ($foundproducts == '()') { $foundproducts = '(-1)'; }
    $query = 'select monthlystock.productid,stock,productname,numberperunit as npu
    from monthlystock,product,productfamily pf,productfamilygroup pfg,productdepartment pd
    where monthlystock.productid=product.productid and product.productfamilyid=pf.productfamilyid and pf.productfamilygroupid=pfg.productfamilygroupid and pfg.productdepartmentid=pd.productdepartmentid
    and year=? and month=12 and discontinued=0 and stock>0
    and monthlystock.productid not in '.$foundproducts;
    if ($exnestle == 1) { $query .= ' and supplierid<>4126'; }
    $query .= ' order by departmentrank,productdepartmentname,familygrouprank,productfamilygroupname,familyrank,productfamilyname,productname';
    $query_prm = array($lastyear);
    require('inc/doquery.php');
    if ($num_results)
    {
      echo '<br><table class=report><tr><th>Produits non-presents avec stock 12/'.$lastyear.' (et non discontinués)<th>Stock 31/12';
      for ($i=0;$i<$num_results;$i++)
      {
        $stock = floor($query_result[$i]['stock'] / $query_result[$i]['npu']);
        $stockrest = $query_result[$i]['stock'] % $query_result[$i]['npu'];
        echo '<tr><td>' . $query_result[$i]['productid'] . ': ' . d_output(d_decode($query_result[$i]['productname'])) . '<td align=right>' . $stock;
        if ($stockrest > 0) { echo ' <font size=-2>' . $stockrest . '</font>'; }
      }
      echo '</table>';
    }

    $foundplacementA = array_filter(array_unique($foundplacementA));
    sort($foundplacementA);
    $foundplacements = '(';
    foreach ($foundplacementA as $kladd)
    {
      $foundplacements .= $kladd . ',';
    }
    $foundplacements = rtrim($foundplacements,',') . ')';
    if ($foundplacements == '()') { $foundplacements = '(-1)'; }
    $query = 'select placementname from placement 
    where placementid not in '.$foundplacements;
    $query .= ' order by placementrank,placementname';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results)
    {
      echo '<br><table class=report><tr><th>Emplacements vides';
      for ($i=0;$i<$num_results;$i++)
      {
        echo '<tr><td>' . d_output($query_result[$i]['placementname']);
      }
      echo '</table>';
    }
  
  break;
  
  
  
  case 'tracing':
  echo '<h2>Tracabilité</h2>';
  $product = $_POST['product']; require('inc/findproduct.php');
  $batchname = $_POST['batchname'];
  $supplierbatchname = $_POST['supplierbatchname'];
  if ($batchname == '' && $supplierbatchname == '')
  {
    echo '<p class=alert>Veuillez définir un lot.</p>';
  }
  else
  {
    $query = 'select purchasebatchid,purchasebatch.productid,productname,arrivaldate,batchname,supplierbatchname,useby,numberperunit from purchasebatch,product where purchasebatch.productid=product.productid';
    $query_prm = array();
    if ($productid > 0)
    {
      $query .= ' and purchasebatch.productid=?';
      array_push($query_prm,$productid);
    }
    if ($batchname != '')
    {
      $query .= ' and batchname=?';
      array_push($query_prm,$batchname);
    }
    if ($supplierbatchname != '')
    {
      $query .= ' and supplierbatchname=?';
      array_push($query_prm,$supplierbatchname);
    }
    $query .= ' limit 1';
    require('inc/doquery.php');
    if ($num_results)
    {
      $npu = $query_result[0]['numberperunit'];
      $purchasebatchid = $query_result[0]['purchasebatchid'];
      echo '<p>Lot '.$purchasebatchid.' ('.d_output($query_result[0]['batchname']);
      if ($query_result[0]['supplierbatchname'] != '') { echo ' - ' . d_output($query_result[0]['supplierbatchname']); }
      echo '), produit '.$query_result[0]['productid'].' : '.d_output(d_decode($query_result[0]['productname'])).',
      arrivé le '.datefix($query_result[0]['arrivaldate']);
      if ($query_result[0]['useby'] != NULL) { echo ', DLV ' . datefix($query_result[0]['useby']); }
      echo '</p>';
      
      require('preload/user.php');
      require('preload/employee.php');
      $query = 'select distinct invoiceitemhistory.invoiceid,invoicehistory.clientid,clientname,invoicehistory.userid,quantity,invoicehistory.employeeid,accountingdate,basecartonprice,givenrebate,lineprice,isreturn
      from invoiceitemhistory,invoicehistory,client
      where invoicehistory.invoiceid=invoiceitemhistory.invoiceid and invoicehistory.clientid=client.clientid
      and currentpurchasebatchid=? and cancelledid=0';
      $query_prm = array($purchasebatchid);
      require('inc/doquery.php');
      echo '<table class=report><th>Facture<th>Client<th>Facturier<th>Employé<th>Date<th>Quantité<th>Prix/unité<th>Remise<th>Prix HT';
      for ($i=0; $i < $num_results; $i++)
      {
        echo d_tr();
        $link = 'printwindow.php?report=showinvoice&invoiceid=' . $query_result[$i]['invoiceid'] . '"';
        $kladd = $query_result[$i]['invoiceid']; if ($query_result[$i]['isreturn'] == 1) { $kladd = '(Avoir) ' .  $kladd; }
        echo d_td_old($kladd,1,0,0,$link);
        $link = 'printwindow.php?report=showclient&client=' . $query_result[$i]['clientid'] . '"';
        echo d_td_old(d_decode($query_result[$i]['clientname']).' ('.$query_result[$i]['clientid'].')',0,0,0,$link);
        echo d_td_old($userA[$query_result[$i]['userid']]);
        echo d_td_old($employeeA[$query_result[$i]['employeeid']]);
        echo d_td_old(datefix2($query_result[$i]['accountingdate']));
        if ($npu > 0)
        {
          $quantity = floor($query_result[$i]['quantity'] / $npu);
          if (($query_result[$i]['quantity'] % $npu) > 0) { $quantity .= ' '.($query_result[$i]['quantity'] % $npu); }
        }
        else { $quantity = $query_result[$i]['quantity']; }
        echo d_td_old($quantity,1);
        echo d_td_old(myfix($query_result[$i]['basecartonprice']),1);
        echo d_td_old(myfix($query_result[$i]['givenrebate']),1);
        echo d_td_old(myfix($query_result[$i]['lineprice']),1);
      }
      echo '</table>';
    }
    else { echo '<p class=alert>Lot '.d_output($batchname).' introuvable.</p>'; }
  }
  break;
  
  case 'sellbydatereport':
  $days = (int)$_POST['days'];
  $excludesupplier = $_POST['excludesupplier'];
  
  $supplierid = (int) $_POST['supplierid']; $suppliername=$supplierid; if ($supplierid == 0) { $supplierid = ''; }
  
  $product = $_POST['product']; require('inc/findproduct.php');
  $productdepartmentid = $_POST['productdepartmentid'];
  $productfamilygroupid = $_POST['productfamilygroupid'];
  $productfamilyid = $_POST['productfamilyid'];
  $temperatureid = $_POST['temperatureid'];
  $ds_useunits = $_SESSION['ds_useunits'];
  $currentyear = mb_substr($_SESSION['ds_curdate'],0,4);
  $t_sellbydateproducts = d_trad('sellbydateproducts',$days);
  $t_noresult = d_trad('noresult');

  if($excludesupplier > 0)
  {
    $t_supplier = d_trad('excludedsupplierwithid:',$supplierid);
  }
  else
  {
    $t_supplier = d_trad('supplierwithid:',$supplierid);
  }

  $t_temperature = d_trad('temperature:');
  $t_refrigerated = d_trad('refrigerated');
  $t_frozen = d_trad('frozen');
  $t_product = d_trad('product');
  $t_packaging = d_trad('packaging');
  $t_arrivaldate = d_trad('arrivaldate');
  $t_stock = d_trad('stock');
  $t_SBD = d_trad('SBD');
  $t_wholesalepricewithouttax = d_trad('wholesalepricewithouttax');
  $t_value = d_trad('value');
  $t_productfamily = d_trad('productfamily:');

  session_write_close();


  echo '<title>' . $t_sellbydateproducts . '</title>';
  echo '<h2>' . $t_sellbydateproducts . '</h2><br>';

  if ($supplierid != "")
  {
    $query = 'select clientname from client where clientid=?';
    $query_prm = array($supplierid);
    require('inc/doquery.php');
    echo '<p>' . $t_supplier . '&nbsp;' . d_output(d_decode($query_result[0]['clientname'])) . '</p>';
  }
  if($productfamilygroupid > 0 || $productdepartmentid > 0 || $productfamilyid > 0)
  {
    echo '<p>' . $t_productfamily . '&nbsp;';
    if(!isset($productdepartmentA)){require('preload/productdepartment.php');}
    if(!isset($productfamilygroupA)){require('preload/productfamilygroup.php');}  
    if(!isset($productfamilyA)){require('preload/productfamily.php');}  
    if ($productfamilyid > 0)
    {
      echo d_output($productdepartmentA[$productfamilygroup_pdidA[$productfamily_pfgidA[$productfamilyid]]] . ' / ' . $productfamilygroupA[$productfamily_pfgidA[$productfamilyid]] . ' / ' . $productfamilyA[$productfamilyid]);
    }
    else if($productfamilygroupid > 0)
    {
      echo d_output($productdepartmentA[$productfamilygroup_pdidA[$productfamilygroupid]] . ' / ' . $productfamilygroupA[$productfamilygroupid] );
    }
    else if($productdepartmentid > 0)
    {
      echo d_output($productdepartmentA[$productdepartmentid]);      
    } 
    echo '</p>';
  }

  if ($temperatureid > 0)
  {
    echo '<p>' . $t_temperature . '&nbsp;';
    switch($temperatureid)
    {
      case 1:
        echo $t_refrigerated;
        break;
      case 2:
        echo $t_frozen;
        break;
    }
    echo '</p>';
  }

  $query = 'select p.salesprice,p.unittypeid,p.currentstock,p.currentstockrest,p.productid,p.productname,p.numberperunit,p.netweightlabel,pf.productfamilyname,pg.productfamilygroupname,pd.productdepartmentname,u.unittypename,u.displaymultiplier as dmp
  from product p,productfamily pf,productfamilygroup pg,productdepartment pd,unittype u
  where p.unittypeid=u.unittypeid and p.productfamilyid=pf.productfamilyid
  and pf.productfamilygroupid=pg.productfamilygroupid and pg.productdepartmentid=pd.productdepartmentid
  and p.discontinued=0';
  $query_prm = array();

  if ($product > 0) { $query .= ' and p.productid=?'; array_push($query_prm,$product); }
  if ($supplierid  > 0) { if($excludesupplier){$query .= ' and p.supplierid!=?';}else{$query .= ' and p.supplierid=?';}array_push($query_prm,$supplierid); }
  if ($productfamilyid  > 0) { $query .= ' and p.productfamilyid=?';array_push($query_prm,$productfamilyid); }
  if ($productfamilygroupid  > 0) { $query .= ' and pf.productfamilygroupid=?';array_push($query_prm,$productfamilygroupid); }
  if ($productdepartmentid  > 0) { $query .= ' and pg.productdepartmentid=?';array_push($query_prm,$productdepartmentid);}
  if ($temperatureid >= 0) { $query .= ' and p.temperatureid=?';array_push($query_prm,$temperatureid);}

  $query .= ' order by pd.departmentrank,pg.familygrouprank,pf.familyrank,productname';
  require('inc/doquery.php');
  $rowproduct = $query_result; $num_rows = $num_results; unset($query_result, $num_results);
  $counter = 0;

  for ($i=0;$i < $num_rows; $i++)
  {
    $row = $rowproduct[$i];
    $dmp = $row['dmp'];
    $stock = ($row['currentstock'] * $row['numberperunit']) + $row['currentstockrest'];
    $query = 'select purchasebatchid,cost,prev,arrivaldate,amount,useby,to_days(CURDATE()) as currentdays,to_days(useby) as usebydays from purchasebatch where productid=?
    order by arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc';
    $query_prm = array($row['productid']);
    require('inc/doquery.php');
    $rowpurchase = $query_result; $num_results2 = $num_results; unset($query_result, $num_results);

    for ($y=0; $y < $num_results2; $y++)
    {
      if ($stock > 0)
      {
        $row2 = $rowpurchase[$y];
        $amount = $row2['amount'];
        $mydays = $row2['usebydays'] - $row2['currentdays'];
        $stock = $stock - $amount;
        $amountleft = $amount;
        if ($stock < 0) { $amountleft = $amountleft + $stock; }
        if ($amountleft < 0) { $amountleft = 0; }
        if ($y == $num_results2 && $stock > 0) { $amountleft = $amountleft + $stock; $stock = 0; }
        if ($mydays <= $days)
        { 
          # create array to be sorted
          $counter++;
          $descA[$counter] = d_decode($row['productname']) . ' (' . $row['productid'] . ')';
          $salespriceA[$counter] = myfix($row['salesprice'] * $dmp);
          $arrivaldateA[$counter] = datefix2($row2['arrivaldate']);
          $condA[$counter] = $row['netweightlabel'];
          if ($row['numberperunit'] > 1) { $condA[$counter] = $row['numberperunit'] . ' x ' . $row['netweightlabel']; }
          $stockA[$counter] = floor(($amountleft/$dmp)/$row['numberperunit']);
          $usebyA[$counter] = datefix($row2['useby']);
          if (is_null($row2['useby'])) { $usebyA[$counter] = ''; }
          $prevA[$counter] = $row2['prev'];
          if ($prevA[$counter] == 0) { $prevA[$counter] = $row2['cost']*$row['numberperunit']; } # backwards compat
          $mydaysA[$counter] = $mydays;
          $orderA[$counter] = 0;
        }
      }
    }
  }
  $totalcount = $counter;
  ######### sort array ########
  for ($y=1; $y <= $totalcount; $y++)
  {
    $mydays = $days + 1;
    for ($i=1; $i <= $totalcount; $i++)
    {
      if ($orderA[$i] == 0 && $mydaysA[$i] < $mydays) { $currentindex = $i; $mydays = $mydaysA[$i]; }
    }
    $orderA[$currentindex] = $y; # mark as ordered
    $todisplay[$y] = $currentindex; # save order
  }
  ############################
  if(isset($todisplay))
  {
    echo '<table class="report"><thead><th>' . $t_product . '</th><th>' . $t_packaging . '</th><th>' . $t_arrivaldate .'</th></th><th>' .$t_stock . '</th><th>' . $t_SBD . '</th><th>' . $t_wholesalepricewithouttax . '</th><th>' . $t_value . '</th></th></thead>';
    for ($i=1; $i <= $totalcount; $i++)
    {
      $y = $todisplay[$i];
      if ($stockA[$y] > 0) #$usebyA[$y] != '' && 
      {
        echo d_tr() .'<td>' . d_output($descA[$y]) . '</td><td>' . d_output($condA[$y]) . '</td><td>' . $arrivaldateA[$y] . '</td><td align=right>' . $stockA[$y] . '</td><td align=right>' . $usebyA[$y] . '</td><td align=right>' . $salespriceA[$y] . '</td><td align=right>' . myfix($prevA[$y]*$stockA[$y]) . '</td></tr>';
      }
    }
  }
  else
  {
    echo '<p>' . $t_noresult . '</p>';
  }
  echo '</table>';
  break;

  case 'insurancereport':
  require('preload/localvessel.php');
  $localvesselid = (int) $_POST['localvesselid'];
  $productid = (int) $_POST['productid'];
  $datename = 'startdate'; require('inc/datepickerresult.php');
  $datename = 'stopdate'; require('inc/datepickerresult.php');
  
  $PA['productid_freight_frozen'] = 'uint';
  $PA['productid_freight'] = 'uint';
  $PA['productid_cold'] = 'uint';
  require('inc/readpost.php');
  
  $title = 'Rapport assurance '. $localvesselA[$localvesselid] . ' livraisons ' . datefix2($startdate) . ' à ' . datefix2($stopdate);
  showtitle($title);
  echo '<h2>' . $title . '</h2>';
  
  $query = 'select invoicehistory.invoiceid,clientname,(lineprice+givenrebate) as value,invoiceitemhistory.productid,temperatureid
  from invoicehistory,invoiceitemhistory,client,product
  where invoicehistory.invoiceid=invoiceitemhistory.invoiceid and invoicehistory.clientid=client.clientid
  and invoiceitemhistory.productid=product.productid
  and deliverydate>=? and deliverydate<=? and invoicehistory.localvesselid=?
  order by invoiceitemid';
  $query_prm = array($startdate, $stopdate, $localvesselid);
  require('inc/doquery.php');
  
  $lastinvoiceid = -1; $value = $insurancevalue = $insurancevalue_cold = $hascold = $ff = $fm = 0;
  $tvalue = $tinsurancevalue = $tinsurancevalue_cold = $tff = $tfm = 0;
  echo '<table class="report"><thead><th>Facture<th>Client<th>Valeur HT
  <th>Frêt maritime<th>Frêt congelé<th>Assurance<th>Assurance frigo</thead>';
  for ($i = 0; $i < $num_results; $i++)
  {
    if ($query_result[$i]['invoiceid'] != $lastinvoiceid && $i > 0)
    {
      if ($insurancevalue > 0 || $insurancevalue_cold > 0 )
      {
        echo '<tr><td align=right><a href=\'printwindow.php?report=showinvoice&invoiceid=' . $lastinvoiceid . '\' target=_blank>' . myfix($lastinvoiceid) . '</a>
        <td>' . d_output(d_decode($lastclientname)) . '
        <td align=right>' . myfix($value) . '
        <td align=right>' . myfix($fm) . '
        <td align=right>' . myfix($ff) . '
        <td align=right>' . myfix($insurancevalue) . '
        <td align=right>' . myfix($insurancevalue_cold);
        $tvalue += $value;
        $tfm += $fm;
        $tff += $ff;
        $tinsurancevalue += $insurancevalue;
        $tinsurancevalue_cold += $insurancevalue_cold;
      }
      $value = $insurancevalue = $ff = $fm = $insurancevalue_cold = $hascold = 0;
    }
    $lastinvoiceid = $query_result[$i]['invoiceid'];
    if ($query_result[$i]['productid'] == $productid)
    {
      if ($hascold) { $insurancevalue_cold += $query_result[$i]['value']; }
      else { $insurancevalue += $query_result[$i]['value']; }
    }
    elseif ($query_result[$i]['productid'] == $productid_freight_frozen) { $ff += $query_result[$i]['value']; }
    elseif ($query_result[$i]['productid'] == $productid_freight) { $fm += $query_result[$i]['value']; }
    else { $value += $query_result[$i]['value']; }
    if ($query_result[$i]['temperatureid'] > 0) { $hascold = 1; }
    $lastclientname = $query_result[$i]['clientname'];
  }
  echo '<tr><td colspan=2><b>TOTAL<td align=right><b>' . myfix($tvalue) . '
  <td align=right><b>' . myfix($tfm) . '
  <td align=right><b>' . myfix($tff) . '
  <td align=right><b>' . myfix($tinsurancevalue) . '
  <td align=right><b>' . myfix($tinsurancevalue_cold) . '
  </table>';
  break;

  case '1client';
  
  
  $query = 'select clientname from client where clientid="' . $_POST['clientid'] . '"';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $row = mysql_fetch_array($result);
  $clientname = $row['clientname'];
  if ($_POST['clientid'] == "" && $_POST['categoryid'] == 0) { exit; }
  if ($_POST['clientid'] == "" && $_POST['categoryid'] > 0)
  {
    $query = 'select categoryname from category where categoryid="' . $_POST['categoryid'] . '"';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $row = mysql_fetch_array($result);
    $clientname = $row['categoryname'];
  }
  echo '<TITLE>Vente pour client ' . $_POST['clientid'] . ': ' . $clientname . ' en ' . $_POST['year'] . '</TITLE>';
  echo '</HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>';
  echo '<h2>Vente pour client ' . $_POST['clientid'] . ': ' . $clientname . ' en ' . $_POST['year'] . '</h2>';
  echo '<h2 class=alert>Rapport à vérifier</h2>';
  $query = 'select month(accountingdate) as month,sum(quantity) as quantity,product.productid as productid,productname,numberperunit,netweightlabel from product,invoicehistory,invoiceitemhistory,client where ';
  $query = $query . ' invoiceitemhistory.productid=product.productid and invoicehistory.invoiceid=invoiceitemhistory.invoiceid and invoicehistory.clientid=client.clientid and confirmed=1 and cancelledid=0 and isnotice=0';
  if ($_POST['clientid'] != "") { $query = $query . ' and invoicehistory.clientid="' . $_POST['clientid'] . '"'; }
  if ($_POST['categoryid'] > 0) { $query = $query . ' and clientcategoryid="' . $_POST['categoryid'] . '"'; }
  if ($_POST['myprods'] == 1) { $query = $query . ' and supplierid=4126'; }
  if ($_POST['myprods'] == 2) { $query = $query . ' and supplierid<>4126'; }
  $query = $query . ' and year(accountingdate)="' . $_POST['year'] . '" group by invoiceitemhistory.productid,month order by productname,product.productid,month';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $num_results = mysql_num_rows($result);
  echo '<table class=report>';
  echo '<tr><td><b>Code</b></td><td><b>Produit</b></td><td><b>Cond.</b></td><td><b>Jan</b></td><td><b>Fev</b></td><td><b>Mars</b></td><td><b>Avril</b></td><td><b>Mai</b></td><td><b>Juin</b></td><td><b>Juil</b></td><td><b>Aout</b></td><td><b>Sept</b></td><td><b>Oct</b></td><td><b>Nov</b></td><td><b>Dec</b></td><td><b>Total</b></td><td><b>Moyen</b></td></tr>';
  $grandtotal = 0; $highestmonth = 0;
  for ($i=1;$i <= $num_results; $i++)
  {
    $row = mysql_fetch_array($result);
    $month[$i] = $row['month'];
    if ($month[$i] > $highestmonth) { $highestmonth = $month[$i]; }
    $productid[$i] = $row['productid'];
    $productname[$i] = $row['productname'];
    $cond[$i] = $row['numberperunit'] . ' x ' . $row['netweightlabel'];
    $quantity[$productid[$i]][$month[$i]] = $row['quantity'] / $row['numberperunit'];
    $grandtotal = $grandtotal + $quantity[$productid[$i]][$month[$i]];
  }
  for ($y=1;$y <= 12; $y++)
  {
    $monthtotal[$y] = 0;
  }
  $total = 0;
  for ($i=1;$i <= $num_results; $i++)
  {
    if ($productid[$i] != $lastproductid)
    {
      $subtotal = 0;
      echo '<tr><td>' . $productid[$i] . '</td><td>' . $productname[$i] . '</td><td>' . $cond[$i] . '</td>';
      for ($y=1;$y <= 12; $y++)
      {
        $todisplay =  myfix($quantity[$productid[$i]][$y]);
        if ($todisplay == '0') { $todisplay = '&nbsp'; }
        echo '<td align=right>' . $todisplay . '</td>';
        $subtotal = $subtotal + $quantity[$productid[$i]][$y];
        $monthtotal[$y] = $monthtotal[$y] + $quantity[$productid[$i]][$y];
      }
      echo '<td align=right>&nbsp;' . myfix($subtotal) . '</td>';
      echo '<td align=right>&nbsp;' . myfix($subtotal/$highestmonth) . '</td>';
    }
    echo '</tr>';
    $lastproductid = $productid[$i];
  }
  echo '<tr><td><b>Total</b><td>&nbsp;</td><td>&nbsp;</td>';
  for ($y=1;$y <= 12; $y++)
  {
    echo '<td align=right><b>' . myfix($monthtotal[$y]) . '</td>';
  }
  echo '<td align=right><b>' . myfix($grandtotal) . '</b></td><td colspan=2><b>&nbsp;</b></td></tr>';
  echo '</table>';
  break;

  case 'notexported':
  $datename = 'startdate'; require('inc/datepickerresult.php');
  $datename = 'stopdate'; require('inc/datepickerresult.php');
  $query = 'select invoiceid,accountingdate,matchingid from invoicehistory where
  accountingdate>? and accountingdate<? and exported=0 and cancelledid=0 and confirmed=1 and invoiceprice>0 and isnotice=0
  order by accountingdate,invoiceid'; # and matchingid>0
  $query_prm = array($startdate,$stopdate);
  require('inc/doquery.php');
  $main_result = $query_result; unset($query_result); $num_results_main = $num_results;
  $ourtitle = 'Non exporté '.datefix2($startdate).' à '.datefix2($stopdate);
  showtitle($ourtitle);
  echo '<h2>' . d_output($ourtitle) . '</h2>';
  echo '<p class ="alert">Factures/avoirs non-exportés (BdL et valeurs zero non-inclus)</p>';
  echo '<table class="report"><thead><th>Facture</td><th>Date</td><th>Lettré?</td></thead>';
  for ($i=0;$i<$num_results_main;$i++)
  {
    if ($main_result[$i]['matchingid'] > 0) { $kladd = '&radic;'; }
    else { $kladd = '&nbsp;'; }
    echo '<td align=right><a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $main_result[$i]['invoiceid'] . '" target=_blank>' . $main_result[$i]['invoiceid'] . '</a></td>';
    echo '<td>' . datefix2($main_result[$i]['accountingdate']) . '</td><td align=center>' . $kladd . '</td></tr>';
  }
  echo '</table>';
  break;

  case 'fc':
  require('inc/findclient.php');
  if ($clientid < 1) { exit; }
  require('preload/town.php');
  
  $PA['myprods'] = 'uint';
  require('inc/readpost.php');
  
  $query = 'select clientname,clientcategory3id,townid from client where clientid=? limit 1';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  $row = $query_result[0];
  $cc3id = $query_result[0]['clientcategory3id'];
  $islandid = $town_islandidA[$query_result[0]['townid']];
  $i = 0; $p = 0; $done = 0; $totaldebit = 0; $totalcredit = 0; $lastinvoiceid = -1; $lastpdn = -1;

  echo '<TITLE>Feuille de commande client ' . $clientid . ': ' . d_decode($row['clientname']) . '</TITLE>';
  echo '</HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>';
  echo '<h2>Feuille de commande client ' . $clientid . ': ' . d_decode($row['clientname']) . '</h2>';
  #echo '<h3>' . datefix($startdate) . ' à ' . datefix($stopdate) . '</h2>';
  echo '<table class="report"><tr><td colspan=2><b>Produit</b></td><td><b>Cond</b></td><td colspan=5 style="min-width:200px"><b>Commande</b></td><td><b>Dern Prix</b></td>';
# <td><b>Moyenne</b></td>
  echo '<td colspan=3><b>Derniers trois</b></td>';
# <td><b>Total</b></td><td><b># achats</b></td>
  echo '</tr>';
  
  $mecid = 0;
  if ($_SESSION['ds_myemployeeid'] > 0)
  {
    $query = 'select employeecategoryid from employee where employeeid=?';
    $query_prm = array($_SESSION['ds_myemployeeid']);
    require('inc/doquery.php');
    $mecid = $query_result[0]['employeecategoryid']; /* 1 Nestlé, 2 WC, 3 Both */
  }
  if ($cc3id == 3 || $cc3id == 4) { $mecid = 0; }
  if ($_SESSION['ds_userid'] == 81 && $islandid < 3)
  {
    $mecid = 2;echo $islandid;
  }
  if ($mecid == 0 || $mecid > 2)
  {
    $mecid = $myprods;
  }

  $query = 'select basecartonprice,invoiceitemhistory.invoiceid as invoiceid,product.productid as productid,productname,numberperunit,netweightlabel,quantity,productfamilyname,productfamilygroupname,productdepartmentname,accountingdate 
  from product,invoicehistory,invoiceitemhistory,productfamily,productfamilygroup,productdepartment
  where invoiceitemhistory.productid=product.productid and invoiceitemhistory.invoiceid=invoicehistory.invoiceid and product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and productfamilygroup.productdepartmentid=productdepartment.productdepartmentid
  and clientid="' . $clientid . '" and cancelledid=0 and isreturn=0 and confirmed=1 and discontinued=0 and notforsale=0';
  if ($mecid == 1) { $query = $query . ' and supplierid=4126'; echo '<p>Produits Nestlé</p>'; }
  elseif ($mecid == 2) { $query = $query . ' and supplierid<>4126'; echo '<p>Produits Divers</p>'; }
  else { echo '<p>Tous produits</p>'; }

  $query = $query . ' order by departmentrank,familygrouprank,familyrank,productname,productid,deliverydate';
  $query_prm = array();
  require('inc/doquery.php');
  $y = 0; $lastproductid = 0;
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    if ($row['productid'] != $lastproductid)
    {
      if ($i > 0)
      {
        if ($totalpurchases[$y] == 0) { $totalpurchases[$y] = 1; }
        $averagequantity[$y] = $totalquantity[$y] / $totalpurchases[$y];
      }
      $y++;
      $totalquantity[$y] = 0;
      $subquantity[$y] = 0;
      $last3[$y] = 0;
      $last2[$y] = 0;
      $last1[$y] = 0;
      $totalpurchases[$y] = 0;
      $productid[$y] = $row['productid'];
      $productname[$y] = d_decode($row['productname']);
      $pfn[$y] = $row['productfamilyname'];
      $pfgn[$y] = $row['productfamilygroupname'];
      $pdn[$y] = $row['productdepartmentname'];
      $cond[$y] = $row['numberperunit'] . ' x ' . $row['netweightlabel'];
    }
    $totalquantity[$y] = $totalquantity[$y] + ($row['quantity'] / $row['numberperunit']);
    $subquantity[$y] = $subquantity[$y] + ($row['quantity'] / $row['numberperunit']);
    if ($row['invoiceid'] != $lastinvoiceid || $row['productid'] != $lastproductid)
    {
      $totalpurchases[$y]++;
      $last3[$y] = $last2[$y];
      if (isset($date2[$y])) { $date3[$y] = $date2[$y]; }
      $last2[$y] = $last1[$y];
      if (isset($date1[$y])) { $date2[$y] = $date1[$y]; }
      $last1[$y] = ($row['quantity'] / $row['numberperunit']);
      $date1[$y] = $row['accountingdate'];
      $lastprice[$y] = $row['basecartonprice'];
      $subquantity[$y] = 0;
    }
    if ($row['invoiceid'] == $lastinvoiceid && $row['productid'] == $lastproductid)
    {
      $last1[$y] = $last1[$y] + ($row['quantity'] / $row['numberperunit']);
    }
    $lastproductid = $row['productid'];
    $lastinvoiceid = $row['invoiceid'];
#echo '<br>invoice=' . $row['invoiceid'] . ' p=' . $row['productid'] . ' q=' . $row['quantity'] . ' $last1[$y]=' . $last1[$y];
  }
  if ($totalpurchases[$y] == 0) { $totalpurchases[$y] = 1; }
  $averagequantity[$y] = $totalquantity[$y] / $totalpurchases[$y];
  for ($i=1; $i <= $y; $i++)
  {
    if ($last1[$i] == 0) { $last1[$i] = "&nbsp;"; }
    else { $last1[$i] = round($last1[$i],2) . ' &nbsp;' . date("d/m/y",strtotime($date1[$i])); }
    if ($last2[$i] == 0) { $last2[$i] = "&nbsp;"; }
    else { $last2[$i] = round($last2[$i],2) . ' &nbsp;' . date("d/m/y",strtotime($date2[$i])); }
    if ($last3[$i] == 0) { $last3[$i] = "&nbsp;"; }
    else { $last3[$i] = round($last3[$i],2) . ' &nbsp;' . date("d/m/y",strtotime($date3[$i])); }
    if ($pdn[$i] != $lastpdn || $i == 1) { echo '<tr><td colspan=15><b>' . $pdn[$i] . '</b></td></tr>'; }
    echo '<tr><td align=right>' . $productid[$i] . '</td><td class="breakme">' . $productname[$i] . '</td><td align=right>' . $cond[$i] . '</td><td width=80>&nbsp;</td><td width=80>&nbsp;</td><td width=80>&nbsp;</td><td width=80>&nbsp;</td><td width=80>&nbsp;</td><td align=right>' . myfix($lastprice[$i]) . '</td>';
    echo '<td align=right>' . $last1[$i] . '</td><td align=right>' . $last2[$i] . '</td><td align=right>' . $last3[$i] . '</td>';
    echo '</tr>';
    $lastpdn = $pdn[$i];
  }
  echo '</table>';
  break;

  ### nestle volume ###
  case 'nestlevolume':

  # set parameters
  
  $postmonth = $_POST['month'];
  $postyear = $_POST['year']; $corrlastyear = $_POST['year'] - 1;

  # 2013 03 11 load monthlystock - TODO limit to Nestlé products
  $query = 'select productid,stock from monthlystock where year=? and month=?';
  $query_prm = array($postyear,$postmonth);
  require('inc/doquery.php');
  for ($i=0;$i<$num_results;$i++)
  {
    $monthlystockA[$query_result[$i]['productid']] = $query_result[$i]['stock'];
  }
  
  # 2020 11 24 load ouraverageA
  for ($y=0;$y <= 2; $y++)
  {
    $month = $postmonth - $y; $year = $postyear;
    if ($month < 1) { $month = $month + 12; $year = $year - 1; }
    if ($month < 10) { $month = '0' . $month; }
    $ourdate = $year . '-' .  $month . '-01';
    $month = $month + 0;

    $query = 'select invoiceitemhistory.productid,sum(quantity) as sales
    from invoicehistory,invoiceitemhistory,product
    where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
    and invoiceitemhistory.productid=product.productid
    and cancelledid=0 and confirmed=1 and isreturn=0 and supplierid=4126
    and year(accountingdate)=? and month(accountingdate)=?
    group by productid';
    $query_prm = array($year, $month);
    require('inc/doquery.php');
    for ($i=0;$i < $num_results; $i++)
    {
      if (!isset($ouraverageA[$query_result[$i]['productid']])) { $ouraverageA[$query_result[$i]['productid']] = 0; }
      $ouraverageA[$query_result[$i]['productid']] += $query_result[$i]['sales'];
    }
  }

  $query = 'select currentstock,volume,weight,product.productid as productid,productname,salesprice,numberperunit,netweightlabel,productfamilyname,productfamilygroupname,productdepartmentname';
  $query = $query . ' from product,productfamily,productfamilygroup,productdepartment where product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and productfamilygroup.productdepartmentid=productdepartment.productdepartmentid and supplierid=4126 and discontinued=0';
#$query = $query . ' and (productid=1287 or productid=1288 or productid=2363 or productid=2364 or productid=897 or productid=898 or productid=899)';
  $query = $query . ' order by departmentrank,familygrouprank,familyrank,productname';
  $query_prm = array($postyear,$postmonth);
  require('inc/doquery.php');
  $main_result = $query_result;
  $num_results_main = $num_results;
  echo '<TITLE>Volume Produits Nestlé ' . $postmonth . '/' . $postyear;
  echo '</TITLE>';
  echo '</HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>';
  echo '<h1>Volume Produits Nestlé ' . $postmonth . '/' . $postyear;
  echo '</h1>';
  $totalstock = 0; $total = 0;
  $totalaverage = 0;
  $totaloverstock = 0;
  $subtotal = 0; $valuest = 0; $avgsalesst = 0;
  $t1 = 0; $t2 = 0; $t3 = 0;
  for ($i=1;$i <= $num_results_main; $i++)
  {
    $row = $main_result[($i-1)];
    $amount = floor($monthlystockA[$row['productid']] / $row['numberperunit']);
    if ($amount > 0)
    {
      if ($i != 1 && $lastpfn != $row['productfamilyname'])
      {
        $subtotal = 0; $valuest = 0; $avgsalesst = 0;
        echo '</table>';
      }
      if ($i == 1 || $lastpdn != $row['productdepartmentname']) { echo '<h2>Département ' . $row['productdepartmentname'] . '</h2>'; }
      if ($i == 1 || $lastpfgn != $row['productfamilygroupname']) { echo '<h3>Famille ' . $row['productfamilygroupname'] . '</h3>'; }
      if ($i == 1 || $lastpfn != $row['productfamilyname'])
      {
        echo '<h4>Classe ' . $row['productfamilyname'] . '</h4>';
        echo '<table class="report"><tr><td><b>Produit</b></td><td><b>Numéro</b></td>';
        echo '<td><b>Conditionnement</b></td><td><b>Stock</b></td><td><b>m<sup>3</sup></b></td><td><b>3 mois vente ctn</b></td><td><b>3 mois vente m<sup>3</sup></b></td><td><b>Excès</b></td></tr>';
      }
      echo '<tr><td>' . $row['productname'] . '</td><td align=right>' . $row['productid'] . '</td>';

      $ouraverage = $ouraverageA[$row['productid']] / $row['numberperunit'];

      $ratio = round($row['volume'] * $amount,3) - round($row['volume'] * $ouraverage,3);
      $total = $total + $ratio;

      echo '<td align=right>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td><td align=right>' . myfix($amount) . '</td><td align=right>' . round($row['volume'] * $amount,3) . '</td><td align=right>' . myfix($ouraverage) . '</td><td align=right>' . round($row['volume'] * $ouraverage,3) . '</td><td align=right>' . round($ratio,3) . '</td>';

      $t1 = $t1 + $row['salesprice'] * $amount;
      $t2 = $t2 + $ouraverage;
      #$t3 = $t3 + $overstock;
      $lastpdn = $row['productdepartmentname'];
      $lastpfgn = $row['productfamilygroupname'];
      $lastpfn = $row['productfamilyname'];
      $totalstock = $totalstock + $row['salesprice'] * $amount;
      $totalaverage = $totalaverage + $ouraverage;
      #$totaloverstock = $totaloverstock + $overstock;
      #$subtotal = $subtotal + $overstock;
      $valuest = $valuest + ($row['salesprice'] * $amount);
    }
  }
  echo '</table>';
  echo '<h2>Total excès: ' . $total . '</h2>';
  break;

  case 'promnestle1':
  
  require('preload/taxcode.php');

  $postmonth = substr($_SESSION['ds_curdate'],5,2);
  $postyear = substr($_SESSION['ds_curdate'],0,4); $corrlastyear = substr($_SESSION['ds_curdate'],0,4) - 1;
  if ($postmonth == 1) { $postyear = $postyear - 1; $postmonth = 12; }
  else { $postmonth = $postmonth - 1; }
  $executiondate = $_SESSION['ds_curdate'];
  if ($postmonth == 1 || $postmonth == 3 || $postmonth == 5 || $postmonth == 7 || $postmonth == 8 || $postmonth == 10 || $postmonth == 12) { $stopday = 31; }
  if ($postmonth == 2) { $stopday = 28; }
  if ($postmonth == 2 && $postyear%4 == 0 && $postyear%100 != 0) { $stopday = 29; }
  if ($postmonth == 4 || $postmonth == 6 || $postmonth == 9 || $postmonth == 11) { $stopday = 30; } 

  $suppliername = "Nestlé";
  $postwhosupplier = 2; $postmycat = 1; $posthundred = 3;
  $postsupplierid = 0; $postproductid = 0; $postproductfamilygroupid = 0;
  $reportstring = "";

  $begindate = d_builddate(1,$postmonth,$postyear);
  $stopdate = d_builddate($stopday,$postmonth,$postyear);
  $subtotal = 0; $total = 0; $subtvat = 0; $subt2 = 0; $tvat = 0; $t2 = 0;
  $query = 'select taxcodeid,lineprice,suppliercode,basecartonprice,givenrebate,accountingdate,invoicehistory.invoiceid,clientname,product.productid as productid,productname,quantity,numberperunit,netweightlabel,unittypename from invoiceitemhistory,invoicehistory,client,product,unittype,productfamily,productfamilygroup';
  $query = $query . ' where accountingdate >= "' . $begindate . '" and accountingdate <= "' . $stopdate . '" and cancelledid=0 and isreturn=0 and isnotice=0';
  if ($postwhosupplier == 2) { $query = $query . ' and supplierid=4126'; }
  if ($postwhosupplier == 3) { $query = $query . ' and supplierid<>4126'; }
  if ($postmycat == 1) { $query = $query . ' and productfamilygroup.productfamilygroupid<>25 and productfamilygroup.productdepartmentid<>3'; $mycat = 'Tout sauf Petfood et Surgelé'; }
  if ($postmycat == 2) { $query = $query . ' and productfamilygroup.productfamilygroupid=25'; $mycat = 'Petfood'; }
  if ($postmycat == 3) { $query = $query . ' and productfamilygroup.productdepartmentid=3'; $mycat = 'Surgelé'; }
  if ($posthundred == 1) { $query = $query . ' and givenrebate = 100'; $mytitle = ' Gratuits'; }
  if ($posthundred == 2) { $query = $query . ' and givenrebate = 1'; $mytitle = ' 1%'; }
  if ($posthundred == 3) { $query = $query . ' and givenrebate>0 and lineprice>0'; $mytitle = ' >1% <100%'; }
  if ($postsupplierid > 0) { $query = $query . ' and product.supplierid="' . $postsupplierid . '"'; }
  if ($postproductid > 0) { $query = $query . ' and product.productid="' . $postproductid . '"'; }
  if ($postproductfamilygroupid > 0) { $query = $query . ' and productfamily.productfamilygroupid="' . $postproductfamilygroupid . '"'; }
  $query = $query . ' and productfamilygroup.productdepartmentid<>6 and product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and product.unittypeid=unittype.unittypeid and product.productid=invoiceitemhistory.productid and invoicehistory.clientid=client.clientid and invoicehistory.invoiceid=invoiceitemhistory.invoiceid order by productname,givenrebate';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  $reportstring = $reportstring . '<TITLE>Promotions ' . $suppliername . ' ' . $mycat . ' ' . $mytitle . '</TITLE>';
  $reportstring = $reportstring . '</HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>';
  $reportstring = $reportstring . '<h1>Promotions ' . $suppliername . ' ' . $mycat . ' ' . $mytitle . ' entre ' . datefix($begindate) . ' et ' . datefix($stopdate) . '</h1>';
  for ($i=0;$i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    $givenrebate = round(100*($row['givenrebate']/$row['basecartonprice'])/($row['quantity']/$row['numberperunit']),1);
    if ($row['lineprice'] == 0) { $givenrebate = 100; }
    $productname = d_decode($row['productname']);
    if ($i != 0 && $lastpn != $productname)
    {
      $colspan = 8; if ($postwhosupplier == 2) { $colspan++; }
      $reportstring = $reportstring . '<tr><td colspan=' . $colspan . '>&nbsp;</td><td align=right>' . myfix($subtotal) . '<td align=right>' . myfix($subtvat) . '<td align=right>' . myfix($subt2) . '<td>';
      $reportstring = $reportstring . '</table>';
      $subtotal = 0; $subtvat = 0; $subt2 = 0;
    }
    if ($i == 0 || $lastpn != $productname)
    {
      $reportstring = $reportstring . '<h2>' . $row['productid'] . ' ' . $productname . '</h2>';
      $reportstring = $reportstring . '<table class="report"><tr><td><b>Facture</b></td><td><b>Date</b></td><td><b>Client</b></td><td><b>Code Produit</b></td><td><b>Produit</b></td>';
      if ($postwhosupplier == 2) { $reportstring = $reportstring . '<td><b>Code Nestlé</b></td>'; }
      $reportstring = $reportstring . '<td><b>Quantité</b></td><td><b>Conditionnement</b></td><td><b>%</b></td><td><b>Montant HT<td><b>Montant TVA<td><b>Montant TTC<td><b>Qte Facture</b></td></tr>';
    }
    $value = ceil($row['basecartonprice'] * ($row['quantity']/$row['numberperunit']) * ($givenrebate/100));
    $subtotal = $subtotal + $value;
    $total = $total + $value;
    $vat = myround($value * $taxcodeA[$row['taxcodeid']] / 100);
    $value2 = $value + $vat;
    $tvat += $vat;
    $t2 += $value2;
    $subtvat += $vat;
    $subt2 += $value2;
    $value = myfix($value);
    $quantitycartons = floor($row['quantity'] / $row['numberperunit']);
    $quantityunits = $row['quantity'] - ($quantitycartons * $row['numberperunit']);
    $quantitytext = "";
    if ($quantitycartons > 0) { $quantitytext = $quantitycartons . ' ' . $row['unittypename'] . ' '; }
    if ($quantityunits > 0) { $quantitytext = $quantitytext . $quantityunits . ' unités'; }
    $reportstring = $reportstring . '<tr><td align=right>' . $row['invoiceid'] . '</td><td>' . datefix($row['accountingdate']) . '</td><td>' . $row['clientname'] . '</td><td align=right>' . $row['productid'] . '</td><td>' . $row['productname'] . '</td>';
    if ($postwhosupplier == 2) { $reportstring = $reportstring . '<td align=right>' . $row['suppliercode'] . '</td>'; }
    $reportstring = $reportstring . '<td align=right>' . $quantitytext . '</td><td>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td><td align=right>' . $givenrebate . '</td><td align=right>' . $value . '</td>';
    $reportstring .= '<td align=right>' . myfix($vat) . '<td align=right>' . myfix($value2);
      $query = 'select sum(quantity) as quantity from invoiceitemhistory where invoiceid="' . $row['invoiceid'] . '" and productid="' . $row['productid'] . '"';
      $query_prm = array();
      require('inc/doquery.php');
      $row2 = $query_result[0];
      $reportstring = $reportstring . '<td align=right>' . myfix($row2['quantity']/$row['numberperunit']) . '</td>';
    $reportstring = $reportstring . '</tr>';
    $lastpn = $productname;
    echo $reportstring;
    $reportstring = "";
  }
  $colspan = 8; if ($postwhosupplier == 2) { $colspan++; }
  $reportstring = $reportstring . '<tr><td colspan=' . $colspan . '>&nbsp;</td><td align=right>' . myfix($subtotal) . '<td align=right>' . myfix($subtvat) . '<td align=right>' . myfix($subt2) . '<td>';
  $reportstring = $reportstring . '<tr><td colspan=' . ($colspan + 3) . '>&nbsp;</td><td>&nbsp;</td></tr>';
  $reportstring = $reportstring . '<tr><td colspan=' . $colspan . '>&nbsp;</td><td align=right><b>' . myfix($total) . '<td align=right>' . myfix($tvat) . '<td align=right>' . myfix($t2) . '<td>';
  $reportstring = $reportstring . '</table>';
  echo $reportstring;


  #### report 2

  $reportstring = "";
  $suppliername = "Nestlé";
  $postwhosupplier = 2; $postmycat = 1; $posthundred = 1;
  $postsupplierid = 0; $postproductid = 0; $postproductfamilygroupid = 0;
  $reportstring = "";

  $begindate = d_builddate(1,$postmonth,$postyear);
  $stopdate = d_builddate($stopday,$postmonth,$postyear);
  $subtotal = 0; $total = 0; $subtvat = 0; $subt2 = 0; $tvat = 0; $t2 = 0;
  $query = 'select taxcodeid,lineprice,suppliercode,basecartonprice,givenrebate,accountingdate,invoicehistory.invoiceid,clientname,product.productid as productid,productname,quantity,numberperunit,netweightlabel,unittypename from invoiceitemhistory,invoicehistory,client,product,unittype,productfamily,productfamilygroup';
  $query = $query . ' where accountingdate >= "' . $begindate . '" and accountingdate <= "' . $stopdate . '" and cancelledid=0 and isreturn=0 and isnotice=0';
  if ($postwhosupplier == 2) { $query = $query . ' and supplierid=4126'; }
  if ($postwhosupplier == 3) { $query = $query . ' and supplierid<>4126'; }
  if ($postmycat == 1) { $query = $query . ' and productfamilygroup.productfamilygroupid<>25 and productfamilygroup.productdepartmentid<>3'; $mycat = 'Tout sauf Petfood et Surgelé'; }
  if ($postmycat == 2) { $query = $query . ' and productfamilygroup.productfamilygroupid=25'; $mycat = 'Petfood'; }
  if ($postmycat == 3) { $query = $query . ' and productfamilygroup.productdepartmentid=3'; $mycat = 'Surgelé'; }
  if ($posthundred == 1) { $query = $query . ' and givenrebate>0 and lineprice=0'; $mytitle = ' Gratuits'; }
  if ($posthundred == 2) { $query = $query . ' and givenrebate = 1'; $mytitle = ' 1%'; }
  if ($posthundred == 3) { $query = $query . ' and givenrebate>1 and givenrebate<100'; $mytitle = ' >1% <100%'; }
  if ($postsupplierid > 0) { $query = $query . ' and product.supplierid="' . $postsupplierid . '"'; }
  if ($postproductid > 0) { $query = $query . ' and product.productid="' . $postproductid . '"'; }
  if ($postproductfamilygroupid > 0) { $query = $query . ' and productfamily.productfamilygroupid="' . $postproductfamilygroupid . '"'; }
  $query = $query . ' and productfamilygroup.productdepartmentid<>6 and product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and product.unittypeid=unittype.unittypeid and product.productid=invoiceitemhistory.productid and invoicehistory.clientid=client.clientid and invoicehistory.invoiceid=invoiceitemhistory.invoiceid order by productname,givenrebate';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  $reportstring = $reportstring . '<h1>Promotions ' . $suppliername . ' ' . $mycat . ' ' . $mytitle . ' entre ' . datefix($begindate) . ' et ' . datefix($stopdate) . '</h1>';
  for ($i=0;$i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    $givenrebate = round(100*($row['givenrebate']/$row['basecartonprice'])/($row['quantity']/$row['numberperunit']),1);
    if ($row['lineprice'] == 0) { $givenrebate = 100; }
    $productname = d_decode($row['productname']);
    if ($i != 0 && $lastpn != $productname)
    {
      $colspan = 8; if ($postwhosupplier == 2) { $colspan++; }
      $reportstring = $reportstring . '<tr><td colspan=' . $colspan . '>&nbsp;</td><td align=right>' . myfix($subtotal) . '<td align=right>' . myfix($subtvat) . '<td align=right>' . myfix($subt2) . '<td>';
      $reportstring = $reportstring . '</table>';
      $subtotal = 0; $subtvat = 0; $subt2 = 0;
    }
    if ($i == 0 || $lastpn != $productname)
    {
      $reportstring = $reportstring . '<h2>' . $row['productid'] . ' ' . $productname . '</h2>';
      $reportstring = $reportstring . '<table class="report"><tr><td><b>Facture</b></td><td><b>Date</b></td><td><b>Client</b></td><td><b>Code Produit</b></td><td><b>Produit</b></td>';
      if ($postwhosupplier == 2) { $reportstring = $reportstring . '<td><b>Code Nestlé</b></td>'; }
      $reportstring = $reportstring . '<td><b>Quantité</b></td><td><b>Conditionnement</b></td><td><b>%</b></td><td><b>Montant HT<td><b>Montant TVA<td><b>Montant TTC<td><b>Qte Facture</b></td></tr>';
    }
    $value = ceil($row['basecartonprice'] * ($row['quantity']/$row['numberperunit']) * ($givenrebate/100));
    $subtotal = $subtotal + $value;
    $total = $total + $value;
    $vat = myround($value * $taxcodeA[$row['taxcodeid']] / 100);
    $value2 = $value + $vat;
    $tvat += $vat;
    $t2 += $value2;
    $subtvat += $vat;
    $subt2 += $value2;
    $value = myfix($value);
    $quantitycartons = floor($row['quantity'] / $row['numberperunit']);
    $quantityunits = $row['quantity'] - ($quantitycartons * $row['numberperunit']);
    $quantitytext = "";
    if ($quantitycartons > 0) { $quantitytext = $quantitycartons . ' ' . $row['unittypename'] . ' '; }
    if ($quantityunits > 0) { $quantitytext = $quantitytext . $quantityunits . ' unités'; }
    $reportstring = $reportstring . '<tr><td align=right>' . $row['invoiceid'] . '</td><td>' . datefix($row['accountingdate']) . '</td><td>' . $row['clientname'] . '</td><td align=right>' . $row['productid'] . '</td><td>' . $row['productname'] . '</td>';
    if ($postwhosupplier == 2) { $reportstring = $reportstring . '<td align=right>' . $row['suppliercode'] . '</td>'; }
    $reportstring = $reportstring . '<td align=right>' . $quantitytext . '</td><td>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td><td align=right>' . $givenrebate . '</td><td align=right>' . $value . '</td>';
    $reportstring .= '<td align=right>' . myfix($vat) . '<td align=right>' . myfix($value2);
      $query = 'select sum(quantity) as quantity from invoiceitemhistory where invoiceid="' . $row['invoiceid'] . '" and productid="' . $row['productid'] . '"';
      $query_prm = array();
      require('inc/doquery.php');
      $row2 = $query_result[0];
      $reportstring = $reportstring . '<td align=right>' . myfix($row2['quantity']/$row['numberperunit']) . '</td>';
    $reportstring = $reportstring . '</tr>';
    $lastpn = $productname;
    #fwrite($file, $reportstring);
    echo $reportstring;
    $reportstring = "";
  }
  $colspan = 8; if ($postwhosupplier == 2) { $colspan++; }
  $reportstring = $reportstring . '<tr><td colspan=' . $colspan . '>&nbsp;</td><td align=right>' . myfix($subtotal) . '<td align=right>' . myfix($subtvat) . '<td align=right>' . myfix($subt2) . '<td>';
  $reportstring = $reportstring . '<tr><td colspan=' . ($colspan + 3) . '>&nbsp;</td><td>&nbsp;</td></tr>';
  $reportstring = $reportstring . '<tr><td colspan=' . $colspan . '>&nbsp;</td><td align=right><b>' . myfix($total) . '<td align=right>' . myfix($tvat) . '<td align=right>' . myfix($t2) . '<td>';
  $reportstring = $reportstring . '</table>';

  #fwrite($file, $reportstring);
  echo $reportstring;

  #### report 3

  #$filename = '/home/wicosys/apache/htdocs/scheduledreports/promnestle3.html';
  #$file = fopen($filename, "w");
  #if (!$file) { exit; }

  $reportstring = "";
  #$reportstring = file_get_contents('/home/wicosys/apache/htdocs/inc/topnotitle.php');
  #fwrite($file, $reportstring);

  $suppliername = "Nestlé";
  $postwhosupplier = 2; $postmycat = 2; $posthundred = 3;
  $postsupplierid = 0; $postproductid = 0; $postproductfamilygroupid = 0;
  $reportstring = "";

  $begindate = d_builddate(1,$postmonth,$postyear);
  $stopdate = d_builddate($stopday,$postmonth,$postyear);
  $subtotal = 0; $total = 0; $subtvat = 0; $subt2 = 0; $tvat = 0; $t2 = 0;
  $query = 'select taxcodeid,lineprice,suppliercode,basecartonprice,givenrebate,accountingdate,invoicehistory.invoiceid,clientname,product.productid as productid,productname,quantity,numberperunit,netweightlabel,unittypename from invoiceitemhistory,invoicehistory,client,product,unittype,productfamily,productfamilygroup';
  $query = $query . ' where accountingdate >= "' . $begindate . '" and accountingdate <= "' . $stopdate . '" and cancelledid=0 and isreturn=0 and isnotice=0';
  if ($postwhosupplier == 2) { $query = $query . ' and supplierid=4126'; }
  if ($postwhosupplier == 3) { $query = $query . ' and supplierid<>4126'; }
  if ($postmycat == 1) { $query = $query . ' and productfamilygroup.productfamilygroupid<>25 and productfamilygroup.productdepartmentid<>3'; $mycat = 'Tout sauf Petfood et Surgelé'; }
  if ($postmycat == 2) { $query = $query . ' and productfamilygroup.productfamilygroupid=25'; $mycat = 'Petfood'; }
  if ($postmycat == 3) { $query = $query . ' and productfamilygroup.productdepartmentid=3'; $mycat = 'Surgelé'; }
  if ($posthundred == 1) { $query = $query . ' and givenrebate = 100'; $mytitle = ' Gratuits'; }
  if ($posthundred == 2) { $query = $query . ' and givenrebate = 1'; $mytitle = ' 1%'; }
  if ($posthundred == 3) { $query = $query . ' and givenrebate>0 and lineprice>0'; $mytitle = ' >1% <100%'; }
  if ($postsupplierid > 0) { $query = $query . ' and product.supplierid="' . $postsupplierid . '"'; }
  if ($postproductid > 0) { $query = $query . ' and product.productid="' . $postproductid . '"'; }
  if ($postproductfamilygroupid > 0) { $query = $query . ' and productfamily.productfamilygroupid="' . $postproductfamilygroupid . '"'; }
  $query = $query . ' and productfamilygroup.productdepartmentid<>6 and product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and product.unittypeid=unittype.unittypeid and product.productid=invoiceitemhistory.productid and invoicehistory.clientid=client.clientid and invoicehistory.invoiceid=invoiceitemhistory.invoiceid order by productname,givenrebate';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
#  $reportstring = $reportstring . '<TITLE>Promotions ' . $suppliername . ' ' . $mycat . ' ' . $mytitle . '</TITLE>';
#  $reportstring = $reportstring . '</HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>';
  $reportstring = $reportstring . '<h1>Promotions ' . $suppliername . ' ' . $mycat . ' ' . $mytitle . ' entre ' . datefix($begindate) . ' et ' . datefix($stopdate) . '</h1>';
  for ($i=0;$i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    $givenrebate = round(100*($row['givenrebate']/$row['basecartonprice'])/($row['quantity']/$row['numberperunit']),1);
    if ($row['lineprice'] == 0) { $givenrebate = 100; }
    $productname = d_decode($row['productname']);
    if ($i != 0 && $lastpn != $productname)
    {
      $colspan = 8; if ($postwhosupplier == 2) { $colspan++; }
      $reportstring = $reportstring . '<tr><td colspan=' . $colspan . '>&nbsp;</td><td align=right>' . myfix($subtotal) . '<td align=right>' . myfix($subtvat) . '<td align=right>' . myfix($subt2) . '<td>';
      $reportstring = $reportstring . '</table>';
      $subtotal = 0; $subtvat = 0; $subt2 = 0;
    }
    if ($i == 0 || $lastpn != $productname)
    {
      $reportstring = $reportstring . '<h2>' . $row['productid'] . ' ' . $productname . '</h2>';
      $reportstring = $reportstring . '<table class="report"><tr><td><b>Facture</b></td><td><b>Date</b></td><td><b>Client</b></td><td><b>Code Produit</b></td><td><b>Produit</b></td>';
      if ($postwhosupplier == 2) { $reportstring = $reportstring . '<td><b>Code Nestlé</b></td>'; }
      $reportstring = $reportstring . '<td><b>Quantité</b></td><td><b>Conditionnement</b></td><td><b>%</b></td><td><b>Montant HT<td><b>Montant TVA<td><b>Montant TTC<td><b>Qte Facture</b></td></tr>';
    }
    $value = ceil($row['basecartonprice'] * ($row['quantity']/$row['numberperunit']) * ($givenrebate/100));
    $subtotal = $subtotal + $value;
    $total = $total + $value;
    $vat = myround($value * $taxcodeA[$row['taxcodeid']] / 100);
    $value2 = $value + $vat;
    $tvat += $vat;
    $t2 += $value2;
    $subtvat += $vat;
    $subt2 += $value2;
    $value = myfix($value);
    $quantitycartons = floor($row['quantity'] / $row['numberperunit']);
    $quantityunits = $row['quantity'] - ($quantitycartons * $row['numberperunit']);
    $quantitytext = "";
    if ($quantitycartons > 0) { $quantitytext = $quantitycartons . ' ' . $row['unittypename'] . ' '; }
    if ($quantityunits > 0) { $quantitytext = $quantitytext . $quantityunits . ' unités'; }
    $reportstring = $reportstring . '<tr><td align=right>' . $row['invoiceid'] . '</td><td>' . datefix($row['accountingdate']) . '</td><td>' . $row['clientname'] . '</td><td align=right>' . $row['productid'] . '</td><td>' . $row['productname'] . '</td>';
    if ($postwhosupplier == 2) { $reportstring = $reportstring . '<td align=right>' . $row['suppliercode'] . '</td>'; }
    $reportstring = $reportstring . '<td align=right>' . $quantitytext . '</td><td>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td><td align=right>' . $givenrebate . '</td><td align=right>' . $value . '</td>';
    $reportstring .= '<td align=right>' . myfix($vat) . '<td align=right>' . myfix($value2);
      $query = 'select sum(quantity) as quantity from invoiceitemhistory where invoiceid="' . $row['invoiceid'] . '" and productid="' . $row['productid'] . '"';
      $query_prm = array();
      require('inc/doquery.php');
      $row2 = $query_result[0];
      $reportstring = $reportstring . '<td align=right>' . myfix($row2['quantity']/$row['numberperunit']) . '</td>';
    $reportstring = $reportstring . '</tr>';
    $lastpn = $productname;
    #fwrite($file, $reportstring);
    echo $reportstring;
    $reportstring = "";
  }
  $colspan = 8; if ($postwhosupplier == 2) { $colspan++; }
  $reportstring = $reportstring . '<tr><td colspan=' . $colspan . '>&nbsp;</td><td align=right>' . myfix($subtotal) . '<td align=right>' . myfix($subtvat) . '<td align=right>' . myfix($subt2) . '<td>';
  $reportstring = $reportstring . '<tr><td colspan=' . ($colspan + 3) . '>&nbsp;</td><td>&nbsp;</td></tr>';
  $reportstring = $reportstring . '<tr><td colspan=' . $colspan . '>&nbsp;</td><td align=right><b>' . myfix($total) . '<td align=right>' . myfix($tvat) . '<td align=right>' . myfix($t2) . '<td>';
  $reportstring = $reportstring . '</table>';

  #fwrite($file, $reportstring);
  echo $reportstring;

  #fclose($file);

  #### report 4

  #$filename = '/home/wicosys/apache/htdocs/scheduledreports/promnestle4.html';
  #$file = fopen($filename, "w");
  #if (!$file) { exit; }

  $reportstring = "";
  #$reportstring = file_get_contents('/home/wicosys/apache/htdocs/inc/topnotitle.php');
  #fwrite($file, $reportstring);

  $suppliername = "Nestlé";
  $postwhosupplier = 2; $postmycat = 2; $posthundred = 1;
  $postsupplierid = 0; $postproductid = 0; $postproductfamilygroupid = 0;
  $reportstring = "";

  $begindate = d_builddate(1,$postmonth,$postyear);
  $stopdate = d_builddate($stopday,$postmonth,$postyear);
  $subtotal = 0; $total = 0; $subtvat = 0; $subt2 = 0; $tvat = 0; $t2 = 0;
  $query = 'select taxcodeid,lineprice,suppliercode,basecartonprice,givenrebate,accountingdate,invoicehistory.invoiceid,clientname,product.productid as productid,productname,quantity,numberperunit,netweightlabel,unittypename from invoiceitemhistory,invoicehistory,client,product,unittype,productfamily,productfamilygroup';
  $query = $query . ' where accountingdate >= "' . $begindate . '" and accountingdate <= "' . $stopdate . '" and cancelledid=0 and isreturn=0 and isnotice=0';
  if ($postwhosupplier == 2) { $query = $query . ' and supplierid=4126'; }
  if ($postwhosupplier == 3) { $query = $query . ' and supplierid<>4126'; }
  if ($postmycat == 1) { $query = $query . ' and productfamilygroup.productfamilygroupid<>25 and productfamilygroup.productdepartmentid<>3'; $mycat = 'Tout sauf Petfood et Surgelé'; }
  if ($postmycat == 2) { $query = $query . ' and productfamilygroup.productfamilygroupid=25'; $mycat = 'Petfood'; }
  if ($postmycat == 3) { $query = $query . ' and productfamilygroup.productdepartmentid=3'; $mycat = 'Surgelé'; }
  if ($posthundred == 1) { $query = $query . ' and givenrebate>0 and lineprice=0'; $mytitle = ' Gratuits'; }
  if ($posthundred == 2) { $query = $query . ' and givenrebate = 1'; $mytitle = ' 1%'; }
  if ($posthundred == 3) { $query = $query . ' and givenrebate>1 and givenrebate<100'; $mytitle = ' >1% <100%'; }
  if ($postsupplierid > 0) { $query = $query . ' and product.supplierid="' . $postsupplierid . '"'; }
  if ($postproductid > 0) { $query = $query . ' and product.productid="' . $postproductid . '"'; }
  if ($postproductfamilygroupid > 0) { $query = $query . ' and productfamily.productfamilygroupid="' . $postproductfamilygroupid . '"'; }
  $query = $query . ' and productfamilygroup.productdepartmentid<>6 and product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and product.unittypeid=unittype.unittypeid and product.productid=invoiceitemhistory.productid and invoicehistory.clientid=client.clientid and invoicehistory.invoiceid=invoiceitemhistory.invoiceid order by productname,givenrebate';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
#  $reportstring = $reportstring . '<TITLE>Promotions ' . $suppliername . ' ' . $mycat . ' ' . $mytitle . '</TITLE>';
#  $reportstring = $reportstring . '</HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>';
  $reportstring = $reportstring . '<h1>Promotions ' . $suppliername . ' ' . $mycat . ' ' . $mytitle . ' entre ' . datefix($begindate) . ' et ' . datefix($stopdate) . '</h1>';
  for ($i=0;$i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    $givenrebate = round(100*($row['givenrebate']/$row['basecartonprice'])/($row['quantity']/$row['numberperunit']),1);
    if ($row['lineprice'] == 0) { $givenrebate = 100; }
    $productname = d_decode($row['productname']);
    if ($i != 0 && $lastpn != $productname)
    {
      $colspan = 8; if ($postwhosupplier == 2) { $colspan++; }
      $reportstring = $reportstring . '<tr><td colspan=' . $colspan . '>&nbsp;</td><td align=right>' . myfix($subtotal) . '<td align=right>' . myfix($subtvat) . '<td align=right>' . myfix($subt2) . '<td>';
      $reportstring = $reportstring . '</table>';
      $subtotal = 0; $subtvat = 0; $subt2 = 0;
    }
    if ($i == 0 || $lastpn != $productname)
    {
      $reportstring = $reportstring . '<h2>' . $row['productid'] . ' ' . $productname . '</h2>';
      $reportstring = $reportstring . '<table class="report"><tr><td><b>Facture</b></td><td><b>Date</b></td><td><b>Client</b></td><td><b>Code Produit</b></td><td><b>Produit</b></td>';
      if ($postwhosupplier == 2) { $reportstring = $reportstring . '<td><b>Code Nestlé</b></td>'; }
      $reportstring = $reportstring . '<td><b>Quantité</b></td><td><b>Conditionnement</b></td><td><b>%</b></td><td><b>Montant HT<td><b>Montant TVA<td><b>Montant TTC<td><b>Qte Facture</b></td></tr>';
    }
    $value = ceil($row['basecartonprice'] * ($row['quantity']/$row['numberperunit']) * ($givenrebate/100));
    $subtotal = $subtotal + $value;
    $total = $total + $value;
    $vat = myround($value * $taxcodeA[$row['taxcodeid']] / 100);
    $value2 = $value + $vat;
    $tvat += $vat;
    $t2 += $value2;
    $subtvat += $vat;
    $subt2 += $value2;
    $value = myfix($value);
    $quantitycartons = floor($row['quantity'] / $row['numberperunit']);
    $quantityunits = $row['quantity'] - ($quantitycartons * $row['numberperunit']);
    $quantitytext = "";
    if ($quantitycartons > 0) { $quantitytext = $quantitycartons . ' ' . $row['unittypename'] . ' '; }
    if ($quantityunits > 0) { $quantitytext = $quantitytext . $quantityunits . ' unités'; }
    $reportstring = $reportstring . '<tr><td align=right>' . $row['invoiceid'] . '</td><td>' . datefix($row['accountingdate']) . '</td><td>' . $row['clientname'] . '</td><td align=right>' . $row['productid'] . '</td><td>' . $row['productname'] . '</td>';
    if ($postwhosupplier == 2) { $reportstring = $reportstring . '<td align=right>' . $row['suppliercode'] . '</td>'; }
    $reportstring = $reportstring . '<td align=right>' . $quantitytext . '</td><td>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td><td align=right>' . $givenrebate . '</td><td align=right>' . $value . '</td>';
    $reportstring .= '<td align=right>' . myfix($vat) . '<td align=right>' . myfix($value2);
      $query = 'select sum(quantity) as quantity from invoiceitemhistory where invoiceid="' . $row['invoiceid'] . '" and productid="' . $row['productid'] . '"';
      $query_prm = array();
      require('inc/doquery.php');
      $row2 = $query_result[0];
      $reportstring = $reportstring . '<td align=right>' . myfix($row2['quantity']/$row['numberperunit']) . '</td>';
    $reportstring = $reportstring . '</tr>';
    $lastpn = $productname;
    #fwrite($file, $reportstring);
    echo $reportstring;
    $reportstring = "";
  }
  $colspan = 8; if ($postwhosupplier == 2) { $colspan++; }
  $reportstring = $reportstring . '<tr><td colspan=' . $colspan . '>&nbsp;</td><td align=right>' . myfix($subtotal) . '<td align=right>' . myfix($subtvat) . '<td align=right>' . myfix($subt2) . '<td>';
  $reportstring = $reportstring . '<tr><td colspan=' . ($colspan + 3) . '>&nbsp;</td><td>&nbsp;</td></tr>';
  $reportstring = $reportstring . '<tr><td colspan=' . $colspan . '>&nbsp;</td><td align=right><b>' . myfix($total) . '<td align=right>' . myfix($tvat) . '<td align=right>' . myfix($t2) . '<td>';
  $reportstring = $reportstring . '</table>';

  #fwrite($file, $reportstring);
  echo $reportstring;

#fclose($file);

############################


###########################################################################################################
  break;

  
  case 'bdlvalue';
  require('preload/taxcode.php');
  $datename = 'startdate'; require('inc/datepickerresult.php');
  $datename = 'stopdate'; require('inc/datepickerresult.php');
  $orderby = (int) $_POST['orderby'];  
  
  echo '<h2>Valeur BdL '.datefix2($startdate).' à '.datefix2($stopdate).'</h2>';

  
  $query = 'select isreturn,accountingdate,invoicehistory.invoiceid,invoiceitemhistory.productid,quantity,numberperunit
  ,salesprice,lineprice,taxcodeid,clientid,linevalue,producttypeid
  from invoicehistory,invoiceitemhistory,product
  where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and invoiceitemhistory.productid=product.productid
  and isnotice=1 and cancelledid=0 and confirmed=1 and accountingdate>=? and accountingdate<=?';
  if ($orderby == 1) { $query .= ' order by producttypeid,accountingdate,invoicehistory.invoiceid'; }
  elseif ($orderby == 2) { $query .= ' order by clientid,accountingdate,invoicehistory.invoiceid'; }
  else { $query .= ' order by accountingdate,clientid,invoicehistory.invoiceid'; }
  $query_prm = array($startdate,$stopdate);
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  
  if ($orderby == 1)
  {
    require('preload/producttype.php');
    echo '<table class="report"><tr><td><b>Type de produit</td><td><b>Date</td><td><b>Fact</td><td><b>HT<td><b>TVA<td><b>Valeur';
    $invoicetotal = 0; unset($salespriceA); $total = 0; $subtotal = 0; $invoice_ht = 0;
    for ($i=0;$i<$num_results_main;$i++)
    {
      if ($main_result[$i]['lineprice'] == 0)
      {
        $kladd = $main_result[$i]['linevalue'];
        if ($kladd <= 0)
        {
          $kladd = ($main_result[$i]['quantity'] / $main_result[$i]['numberperunit']) * $main_result[$i]['salesprice'];
          $kladd = $kladd + ($kladd * $taxcodeA[$main_result[$i]['taxcodeid']] / 100);
        }
        $invoice_ht += ($kladd / (1 + ($taxcodeA[$main_result[$i]['taxcodeid']] / 100)));
        if ($main_result[$i]['isreturn'] == 1)
        {
          $kladd = 0 - $kladd;
          $invoice_ht = 0 - $invoice_ht;
        }
        $invoicetotal += $kladd;
      }
      else { $invoicetotal += $main_result[$i]['lineprice']; }
      
      if ($i == ($num_results_main-1) || $main_result[$i]['invoiceid'] != $main_result[($i+1)]['invoiceid'])
      {
        echo '<tr><td align=right>';
        echo $producttypeA[$main_result[$i]['producttypeid']];
        echo '</td><td>';
        echo datefix2($main_result[$i]['accountingdate']);
        echo '</td><td align=right>';
        if ($main_result[$i]['isreturn'] == 1) { echo '(Avoir)&nbsp;'; }
        echo '<a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $main_result[$i]['invoiceid'] . '" target=_blank>';
        echo $main_result[$i]['invoiceid'] . '</a></td>
        <td align=right>' . myfix($invoice_ht).'
        <td align=right>' . myfix($invoicetotal-$invoice_ht).'
        <td align=right>' . myfix($invoicetotal);
        $total += $invoicetotal;
        $subtotal += $invoicetotal;
        $invoicetotal = 0; $invoice_ht = 0;
        if ($i == ($num_results_main-1) || $main_result[$i]['producttypeid'] != $main_result[($i+1)]['producttypeid'])
        {
          echo '<tr><td colspan=3><b>Total ',$producttypeA[$main_result[$i]['producttypeid']],'<td><td><td align=right><b>',myfix($subtotal);
          $subtotal = 0;
        }
      }
    }
    echo '<tr><td colspan=3><b>Total</b></td><td><td><td align=right><b>'.myfix($total).'</b></td></tr>';
    echo '</table>';
  }
  elseif ($orderby == 2)
  {
    echo '<table class="report"><tr><td><b>Client</td><td><b>Date</td><td><b>Fact</td><td><b>HT<td><b>TVA<td><b>Valeur</td></tr>';
    $invoicetotal = 0; unset($salespriceA); $total = 0; $subtotal = 0; $invoice_ht = 0;
    for ($i=0;$i<$num_results_main;$i++)
    {
      if ($main_result[$i]['lineprice'] == 0)
      {
        $kladd = $main_result[$i]['linevalue'];
        if ($kladd <= 0)
        {
          $kladd = ($main_result[$i]['quantity'] / $main_result[$i]['numberperunit']) * $main_result[$i]['salesprice'];
          $kladd = $kladd + ($kladd * $taxcodeA[$main_result[$i]['taxcodeid']] / 100);
        }
        $invoice_ht += ($kladd / (1 + ($taxcodeA[$main_result[$i]['taxcodeid']] / 100)));
        if ($main_result[$i]['isreturn'] == 1)
        {
          $kladd = 0 - $kladd;
          $invoice_ht = 0 - $invoice_ht;
        }
        $invoicetotal += $kladd;
      }
      else { $invoicetotal += $main_result[$i]['lineprice']; }
      
      if ($i == ($num_results_main-1) || $main_result[$i]['invoiceid'] != $main_result[($i+1)]['invoiceid'])
      {
        echo '<tr><td align=right>';
        echo $main_result[$i]['clientid'];
        echo '</td><td>';
        echo datefix2($main_result[$i]['accountingdate']);
        echo '</td><td align=right>';
        if ($main_result[$i]['isreturn'] == 1) { echo '(Avoir)&nbsp;'; }
        echo '<a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $main_result[$i]['invoiceid'] . '" target=_blank>';
        echo $main_result[$i]['invoiceid'] . '</a></td>
        <td align=right>' . myfix($invoice_ht).'
        <td align=right>' . myfix($invoicetotal-$invoice_ht).'
        <td align=right>' . myfix($invoicetotal);
        $total += $invoicetotal;
        $subtotal += $invoicetotal;
        $invoicetotal = 0; $invoice_ht = 0;
        if ($i == ($num_results_main-1) || $main_result[$i]['clientid'] != $main_result[($i+1)]['clientid'])
        {
          echo '<tr><td colspan=3><b>Total client ',$main_result[$i]['clientid'],'<td><td><td align=right><b>',myfix($subtotal);
          $subtotal = 0;
        }
      }
    }
    echo '<tr><td colspan=3><b>Total</b></td><td><td><td align=right><b>'.myfix($total).'</b></td></tr>';
    echo '</table>';
  }
  elseif ($orderby == 0)
  {
    echo '<table class="report"><tr><td><b>Date</td><td><b>Client</td><td><b>Fact</td><td><b>HT<td><b>TVA<td><b>Valeur</td></tr>';
    $lastinvoiceid = -1; $invoicetotal = 0; unset($salespriceA); $lastshowndate = -1; $lastshowclientid = - 1; $total = 0; $invoice_ht = 0;
    for ($i=0;$i<$num_results_main;$i++)
    {
      if ($i != 0 && $lastinvoiceid != $main_result[$i]['invoiceid'])
      {
        echo '<tr><td>';
        if ($lastdate != $lastshowndate) { echo datefix2($lastdate); $lastshowndate = $lastdate; }
        echo '</td><td>';
        if ($lastclientid != $lastshowclientid) { echo $lastclientid; $lastshowclientid = $lastclientid; }
        echo '</td><td align=right>';
        if ($lastisreturn == 1) { echo '(Avoir)&nbsp;'; }
        echo '<a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $lastinvoiceid . '" target=_blank>';
        echo $lastinvoiceid . '</a></td><td align=right>' . myfix($invoice_ht).'
        <td align=right>' . myfix($invoicetotal-$invoice_ht).'
        <td align=right>' . myfix($invoicetotal) . '</td></tr>';
        $total += $invoicetotal;
        $invoicetotal = 0; $invoice_ht = 0;
      }
      if ($main_result[$i]['lineprice'] == 0)
      {
        $kladd = $main_result[$i]['linevalue'];
        if ($kladd <= 0)
        {
          $kladd = ($main_result[$i]['quantity'] / $main_result[$i]['numberperunit']) * $main_result[$i]['salesprice'];
          $kladd = $kladd + ($kladd * $taxcodeA[$main_result[$i]['taxcodeid']] / 100);
        }
        $invoice_ht += ($kladd / (1 + ($taxcodeA[$main_result[$i]['taxcodeid']] / 100)));
        if ($main_result[$i]['isreturn'] == 1)
        {
          $kladd = 0 - $kladd;
          $invoice_ht = 0 - $invoice_ht;
        }
        $invoicetotal += $kladd;
      }
      else { $invoicetotal += $main_result[$i]['lineprice']; }
      $lastinvoiceid = $main_result[$i]['invoiceid'];
      $lastclientid = $main_result[$i]['clientid'];
      $lastdate = $main_result[$i]['accountingdate'];
      $lastisreturn = $main_result[$i]['isreturn'];
    }
    ### copy from above
    echo '<tr><td>';
    if ($lastdate != $lastshowndate) { echo datefix2($lastdate); $lastshowndate = $lastdate; }
    echo '</td><td>';
    if ($lastclientid != $lastshowclientid) { echo $lastclientid; $lastshowclientid = $lastclientid; }
    echo '</td><td align=right>';
    if ($lastisreturn == 1) { echo '(Avoir)&nbsp;'; }
    echo '<a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $lastinvoiceid . '" target=_blank>';
    echo $lastinvoiceid . '</a></td><td align=right>' . myfix($invoice_ht).'
    <td align=right>' . myfix($invoicetotal-$invoice_ht).'
    <td align=right>' . myfix($invoicetotal) . '</td></tr>';
    $total += $invoicetotal;
    $invoicetotal = 0; $invoice_ht = 0;
    ###
    echo '<tr><td colspan=3><b>Total</b></td><td><td><td align=right><b>'.myfix($total).'</b></td></tr>';
    echo '</table>';
  }
  break;

  ### Usebys ###
  case 'dlvs':
  echo '<TITLE>Rapport: DLVs</TITLE>';
  echo '</HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>';/*
  $beginmonth = $_POST['beginmonth']; if ($beginmonth < 10) { $beginmonth = '0' . $beginmonth; }
  $stopmonth = $_POST['stopmonth']; if ($stopmonth < 10) { $stopmonth = '0' . $stopmonth; }
  $beginday = $_POST['beginday']; if ($beginday < 10) { $beginday = '0' . $beginday; }
  $stopday = $_POST['stopday']; if ($stopday < 10) { $stopday = '0' . $stopday; }
  $begindate = $_POST['beginyear'] . '-' .  $beginmonth . '-' . $beginday;
  $stopdate = $_POST['stopyear'] . '-' .  $stopmonth . '-' . $stopday;*/
  $begindate = d_builddate($_POST['beginday'],$_POST['beginmonth'],$_POST['beginyear']);
  $stopdate = d_builddate($_POST['stopday'],$_POST['stopmonth'],$_POST['stopyear']);
  
  
  require('preload/unittype.php');


  $query = 'select unittypeid,currentstock,currentstockrest,curdate() as curdate,product.productid as productid,productname,numberperunit,netweightlabel,productfamilyname,productfamilygroupname,productdepartmentname';
  $query = $query . ' from product,productfamily,productfamilygroup,productdepartment where product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and productfamilygroup.productdepartmentid=productdepartment.productdepartmentid';
  if ($_POST['productid'] != "") { $query = $query . ' and product.productid=' . $_POST['productid']; }
  if ($_POST['productdepartmentid'] != 0) { $query = $query . ' and productfamilygroup.productdepartmentid=' . $_POST['productdepartmentid']; }
  if ($_POST['productfamilygroupid'] != 0) { $query = $query . ' and productfamily.productfamilygroupid=' . $_POST['productfamilygroupid']; }
  if ($_POST['productfamilyid'] != 0) { $query = $query . ' and product.productfamilyid=' . $_POST['productfamilyid']; }
  $query = $query . ' order by departmentrank,familygrouprank,familyrank,productname';
  $result2 = mysql_query($query, $db_conn); querycheck($result2);
  $num_resultsX = mysql_num_rows($result2);


  echo '<table class="report"><tr><td><b>Produit</b></td></td>';
  echo '<td><b>Conditionnement</b></td><td><b>Stock</b></td><td><b>DLV</b></td></tr>';
  for ($y=1;$y <= $num_resultsX; $y++)
  {
    $row2 = mysql_fetch_array($result2);
    $currentstock = ($row2['currentstock'] * $row2['numberperunit']) + $row2['currentstockrest'];
    $productid = $row2['productid'];
    $numberperunit = $row2['numberperunit'];
    $dmp = $unittype_dmpA[$row2['unittypeid']];
/*
#######
        $query = 'select purchasebatchid,arrivaldate,amount,useby from purchasebatch where productid="' . $row['productid'] . '" order by arrivaldate desc,useby desc';
        $result2 = mysql_query($query, $db_conn); querycheck($result2);
        $num_results2 = mysql_num_rows($result2);
        for ($y=1; $y <= $num_results2; $y++)
        {
          if ($stock > 0)
          {
            $row2 = mysql_fetch_array($result2);
            $amount = $row2['amount'];
            $stock = $stock - $amount;
            $amountleft = $amount;
            if ($stock < 0) { $amountleft = $amountleft + $stock; }
            if ($amountleft < 0) { $amountleft = 0; }
            if ($y == $num_results2 && $stock > 0) { $amountleft = $amountleft + $stock; $stock = 0; }
            if ($row2['useby'] >= $begindate && $row2['useby'] <= $stopdate)
            { 
              echo '<tr><td>' . $row['productid'] . ': ' . $row['productname'] . '</td><td align=right>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td><td align=right>' . floor($amountleft/$row['numberperunit']) . '</td><td align=right>' . datefix($row2['useby']) . '</td></tr>';
            }
          }
        }
##########
*/
$query = 'select shipmentid,batchname,arrivaldate,amount,prev,prevmaj,pgros,pdetail,cost,useby
from purchasebatch where productid="' . $productid . '"';
$query .= ' order by arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc'; 
$result = mysql_query($query, $db_conn); querycheck($result);
$num_results = mysql_num_rows($result);
for ($i=1; $i <= $num_results; $i++)
{
  $row = mysql_fetch_array($result);

  if ($currentstock >= $row['amount']) { $quantityleft = $row['amount']; }
  elseif ($currentstock > 0) { $quantityleft = $currentstock; }
  else { $quantityleft = 0; } #echo $quantityleft .' '.$numberperunit .'<br>';
  $currentstock -= $row['amount'];
/*
  $margintype = "%";
  if ($producttypename == "PPN") { $margintype = "XPF"; }
  if ($producttypename == "PAO") { $margintype = "PAO"; } # might be different for PAO
  $prev = $row['prev'] * $dmp;
  if ($prev == 0) { $prev = $row['cost']*$numberperunit; } # backwards compat
  */
  $quantity = round($row['amount'] / ($numberperunit*$dmp));
  $quantityleft = round($quantityleft / ($numberperunit*$dmp));
  /*
  echo '<tr><td align=right>' . $row['shipmentid'] . '</td><td align=right>' . $row['batchname'] . '</td><td>' . datefix($row['arrivaldate']) . '</td><td align=right>' . $quantity . '</td>
  <td align=right>' . $quantityleft . '</td>
  <td align=right>' . $prev . '</td><td align=right>' . $row['prevmaj'] . '</td><td align=right>' . round($margin) . ' ' . $margintype . '</td><td align=right>' . $row['pgros'] . '</td><td align=right>' . $row['pdetail'] . '</td></tr>';
  */
  if ($quantityleft > 0 && $row['useby'] >= $begindate && $row['useby'] <= $stopdate)
  { 
    echo '<tr><td>' . $productid . ': ' . d_decode($row2['productname']) . '</td><td align=right>' . $row2['numberperunit'] . ' x ' . $row2['netweightlabel'] . '</td><td align=right>' . $quantityleft . '</td><td align=right>' . datefix2($row['useby']) . '</td></tr>';
  }
}

  }
  echo '</table>';
  break;

  ### DLV catalogue ###
  case 'caddlv':
  require('preload/unittype.php');
  $mycat = "Tout";
  $days = (int) $_POST['days'];
  
  
  $counter = 0;
  $query = 'select salesprice,unittypeid,currentstock,currentstockrest,curdate() as curdate,product.productid as productid,productname,numberperunit,netweightlabel,productfamilyname,productfamilygroupname,productdepartmentname';
  $query = $query . ' from product,productfamily,productfamilygroup,productdepartment where product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and productfamilygroup.productdepartmentid=productdepartment.productdepartmentid';
  if ($_POST['mycat'] == 0) { $query = $query . ' and supplierid <> 4126'; $mycat = "Wing Chong"; }
  if ($_POST['mycat'] == 5) { $query = $query . ' and supplierid <> 4126 and product.temperatureid=1'; $mycat = "Wing Chong Réfrigéré"; }
  if ($_POST['mycat'] == 4) { $query = $query . ' and supplierid <> 4126 and product.temperatureid=2'; $mycat = "Wing Chong Surgelé"; } #productdepartment.productdepartmentid=3
  if ($_POST['mycat'] == 1) { $query = $query . ' and supplierid = 4126 and product.temperatureid=2'; $mycat = "Nestlé Surgelé"; } #productdepartment.productdepartmentid=3
  if ($_POST['mycat'] == 2) { $query = $query . ' and supplierid = 4126 and productfamilygroup.productfamilygroupid=25'; $mycat = "Nestlé Petfood"; }
  if ($_POST['mycat'] == 3) { $query = $query . ' and supplierid = 4126 and productfamilygroup.productfamilygroupid <> 25 and productdepartment.productdepartmentid <> 3'; $mycat = "Nestlé Grocery"; }
  $query = $query . ' order by departmentrank,familygrouprank,familyrank,productname';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $num_results = mysql_num_rows($result);
  echo '<TITLE>Produits DLVs proche - ' . $mycat . ' ' . $days . 'jours</TITLE>';
  echo '</HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>';
  echo '<h2>Produits DLVs proche - ' . $mycat . ' ' . $days . 'jours</h2>';
  echo '<table class="report"><thead><tr><td>Produit</td><td>Conditionnement</td><td>Arrivage</td></td>';
  echo '<td>Stock</td><td>DLV</td><td>Prix Gros HT</td><td>Valeur</td></tr></thead>';
  for ($i=1;$i <= $num_results; $i++)
  {
    $row = mysql_fetch_array($result);
    $dmp = $unittype_dmpA[$row['unittypeid']];
    $currentyear = substr($row['curdate'],0,4);
$stock = ($row['currentstock'] * $row['numberperunit']) + $row['currentstockrest'];
#        $query = 'select stock from endofyearstock where productid="' . $row['productid'] . '" and year="' . ($currentyear-1) . '"';
#        $result2 = mysql_query($query, $db_conn); querycheck($result2);
#        $row2 = mysql_fetch_array($result2);
#        $stock = $row2['stock'];
#        $query = 'select SUM(amount) as stock from purchasebatch where productid="' . $row['productid'] . '" and DATE_FORMAT(arrivaldate,"%Y")="' . $currentyear . '" and arrivaldate <> "2005-01-09"';
#        $result2 = mysql_query($query, $db_conn); querycheck($result2);
#        $row2 = mysql_fetch_array($result2);
#        $stock = $stock + $row2['stock'];
#        $query = 'select SUM(quantity) as stock from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and productid="' . $row['productid'] . '" and DATE_FORMAT(accountingdate,"%Y")="' . $currentyear . '" and cancelled=0';
#        $result2 = mysql_query($query, $db_conn); querycheck($result2);
#        $row2 = mysql_fetch_array($result2);
#        $stock = $stock - $row2['stock'];
#        $query = 'select SUM(netchange) as stock from modifiedstock,purchasebatch where modifiedstock.purchasebatchid=purchasebatch.purchasebatchid and purchasebatch.productid="' . $row['productid'] . '" and DATE_FORMAT(changedate,"%Y")="' . $currentyear . '"';
#        $result2 = mysql_query($query, $db_conn); querycheck($result2);
#        $row2 = mysql_fetch_array($result2);
#        $stock = $stock + $row2['stock'];
#        $query = 'select SUM(quantity) as stock from return where productid="' . $row['productid'] . '" and DATE_FORMAT(returndate,"%Y")="' . $currentyear . '" and wasrestocked=1 and cancelled=0';
#        $result2 = mysql_query($query, $db_conn); querycheck($result2);
#        $row2 = mysql_fetch_array($result2);
#        $stock = $stock + $row2['stock'];

        $query = 'select cost,prev,purchasebatchid,arrivaldate,amount,useby,to_days(CURDATE()) as currentdays,to_days(useby) as usebydays from purchasebatch where productid="' . $row['productid'] . '" order by arrivaldate desc,useby desc';
        $result2 = mysql_query($query, $db_conn); querycheck($result2);
        $num_results2 = mysql_num_rows($result2);
        for ($y=1; $y <= $num_results2; $y++)
        {
          if ($stock > 0)
          {
            $row2 = mysql_fetch_array($result2);
            #$query3 = 'select SUM(netchange) as netchange from modifiedstock where purchasebatchid="' . $row2['purchasebatchid'] . '"'; #   and DATE_FORMAT(changedate,"%Y")="' . $currentyear . '" and specificbatch=1
            #$result3 = mysql_query($query3, $db_conn); querycheck($result3);
            #$row3 = mysql_fetch_array($result3);
            $amount = $row2['amount']; # + $row3['netchange'];
            $mydays = $row2['usebydays'] - $row2['currentdays'];
            $stock = $stock - $amount;
            $amountleft = $amount;
            if ($stock < 0) { $amountleft = $amountleft + $stock; }
            if ($amountleft < 0) { $amountleft = 0; }
            if ($y == $num_results2 && $stock > 0) { $amountleft = $amountleft + $stock; $stock = 0; }
            if ($mydays <= $days)
            { 
              # create array to be sorted
              $counter++;
              $descA[$counter] = $row['productid'] . ': ' . d_decode($row['productname']);
              $salespriceA[$counter] = myfix($row['salesprice'] * $dmp);
              $arrivaldateA[$counter] = datefix2($row2['arrivaldate']);
              $condA[$counter] = $row['netweightlabel'];
              if ($row['numberperunit'] > 1) { $condA[$counter] = $row['numberperunit'] . ' x ' . $row['netweightlabel']; }
              $stockA[$counter] = floor(($amountleft/$dmp)/$row['numberperunit']);
              $usebyA[$counter] = datefix($row2['useby']);
              if (is_null($row2['useby'])) { $usebyA[$counter] = ''; }
              ### value
              #$prev = $row['prev'] * $dmp;
              #if ($prev == 0) { $prev = $row['cost']*$numberperunit; } # backwards compat
              $prevA[$counter] = $row2['prev'];
              if ($prevA[$counter] == 0) { $prevA[$counter] = $row2['cost']*$row['numberperunit']; } # backwards compat
              ###
              $mydaysA[$counter] = $mydays;
              $orderA[$counter] = 0;
            }
          }
        }
  }
  $totalcount = $counter;
  ######### sort array ########
  for ($y=1; $y <= $totalcount; $y++)
  {
    $mydays = $days + 1;
    for ($i=1; $i <= $totalcount; $i++)
    {
      if ($orderA[$i] == 0 && $mydaysA[$i] < $mydays) { $currentindex = $i; $mydays = $mydaysA[$i]; }
    }
    $orderA[$currentindex] = $y; # mark as ordered
    $todisplay[$y] = $currentindex; # save order
  }
  ############################
  for ($i=1; $i <= $totalcount; $i++)
  {
    # display array
    $y = $todisplay[$i]; # user ordering
    echo '<tr><td>' . d_output($descA[$y]) . '</td><td>' . d_output($condA[$y]) . '</td><td>' . $arrivaldateA[$y] . '</td><td align=right>' . $stockA[$y] . '</td><td align=right>' . $usebyA[$y] . '</td><td align=right>' . $salespriceA[$y] . '</td><td align=right>' . myfix($prevA[$y]*$stockA[$y]) . '</td></tr>';
  }
  echo '</table>';
  break;


  case 'commissions': # generalizing for TEM

  require('preload/employee.php');
  require('preload/employeecategory.php');
  require('preload/commissionrate.php'); $num_commissionrate = $num_results;
  require('preload/clientcategory.php');
  require('preload/town.php');
  
  $postemployeeid = (int) $_POST['employeeid'];
  $employeecategoryid = (int) $_POST['employeecategoryid'];
  $excludesupplier = (int) $_POST['excludesupplier'];
  $supplierid = (int) $_POST['supplierid'];
  $datename = 'startdate'; require('inc/datepickerresult.php');
  $datename = 'stopdate'; require('inc/datepickerresult.php'); 
  
  $title = 'Commissions ' . datefix2($startdate) . ' à ' . datefix2($stopdate);
  showtitle($title);
  echo '<h2>' . $title . '</h2>';
  if ($postemployeeid == 0) { echo '<p>Employé: &lt;Vide&gt;</p>'; }
  if ($postemployeeid > 0) { echo '<p>Employé: ' . d_output($employeeA[$postemployeeid]) . '</p>'; }
  if ($employeecategoryid == 0) { echo '<p>Catégorie employé: &lt;Vide&gt;</p>'; }
  if ($employeecategoryid > 0) { echo '<p>Catégorie employé: ' . d_output($employeecategoryA[$employeecategoryid]) . '</p>'; }
  if ($supplierid > 0)
  {
    echo '<p>Fournisseur: ' . $supplierid;
    if ($excludesupplier) { echo ' exclu'; }
    echo '</p>';
  }
  
  $query = 'select sum(invoiceitemhistory.lineprice) as lineprice,commissionrateid,invoicehistory.employeeid,invoicehistory.clientid,clientname,townid,clientcategoryid,isreturn
  from product,invoiceitemhistory,invoicehistory,client';
  if ($employeecategoryid> -1) { $query .= ',employee'; }
  $query .= ' where invoiceitemhistory.productid=product.productid and invoiceitemhistory.invoiceid=invoicehistory.invoiceid and invoicehistory.clientid=client.clientid
  and cancelledid=0 and confirmed=1 and isnotice=0 and accountingdate>=? and accountingdate<=?';
  $query_prm = array($startdate,$stopdate);
  if ($supplierid > 0)
  {
    if ($excludesupplier) { $query = $query . ' and supplierid<>?'; array_push($query_prm,$supplierid); }
    else { $query = $query . ' and supplierid=?'; array_push($query_prm,$supplierid); }
  }
  if ($postemployeeid> -1) { $query .= ' and invoicehistory.employeeid=?'; array_push($query_prm,$postemployeeid); }
  if ($employeecategoryid> -1) { $query .= ' and invoicehistory.employeeid=employee.employeeid and employee.employeecategoryid=?'; array_push($query_prm,$employeecategoryid); }
  $query .=' group by client.clientid,isreturn,commissionrateid order by client.clientcategoryid,clientname,isreturn,commissionrateid';
  require('inc/doquery.php');
  for ($i=0;$i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $clientid[$i] = $row['clientid'];
    $isreturn[$i] = $row['isreturn']+0;
    $clientname[$i] = d_decode($row['clientname']);
    #$employeeid[$i] = $row['employeeid'];
    $clientcategoryid[$i] = $row['clientcategoryid'];
    $townid[$i] = $row['townid'];
    $lineprice_temp = $row['lineprice']+0; if ($row['isreturn'] == 1) { $lineprice_temp = 0 - $lineprice_temp; }
    $lineprice[$i] = $lineprice_temp;
    $clienttotal[$clientid[$i]][$row['commissionrateid']][$isreturn[$i]] = $lineprice_temp;
    $grandtotal = $grandtotal + $lineprice_temp;
#echo '<br>cid='.$clientid[$i] . ' isreturn='.$isreturn[$i]. ' value='.$lineprice[$i];
  }
  echo '<table class="report"><thead><tr><th>Compte</th><th>Client</th><th>Commune</th>';
  foreach ($commissionrateA as $commissionrateid => $commissionrate)
  {
    echo '<th align=right>' . $commissionrate . '%</th>';
    $commissiontotalA[$commissionrateid] = 0;
    $commissionsubtotalA[$commissionrateid] = 0;
  }
  echo '<th>Commission</th></tr></thead><tbody>';
  
  $commissiontotal = 0; $commissionsubtotal = 0;
  for ($i=0;$i < $num_results; $i++)
  {
    if ($clientcategoryid[$i] != $lastclientcategoryid && $i != 0)
    {
      echo '<tr><td colspan=3><b>' . $clientcategoryA[$lastclientcategoryid];
      foreach ($commissionrateA as $commissionrateid => $commissionrate)
      {
        echo '<td align=right><b>' . myfix($commissionsubtotalA[$commissionrateid]) . '</td>';
        $commissionsubtotalA[$commissionrateid] = 0;
      }
      echo '<td align=right><b>' . myfix($commissionsubtotal) . '</td></tr>';
      $commissionsubtotal = 0;
    }
    if ($clientid[$i] != $lastclientid || $isreturn[$i] != $lastisreturn)
    {
      if ($clientid[$i] != $lastclientid) { echo '<tr><td align=right>' . $clientid[$i] . '</td><td>' . d_output($clientname[$i]) . '</td><td>' . $townA[$townid[$i]] . '</td>'; }
      else { echo '<tr><td colspan=3>&nbsp;</td>'; }
      $linetotal = 0;
      foreach ($commissionrateA as $commissionrateid => $commissionrate)
      {
        echo '<td align=right>' . myfix($clienttotal[$clientid[$i]][$commissionrateid][$isreturn[$i]]) . '</td>';
        $kladd = $clienttotal[$clientid[$i]][$commissionrateid][$isreturn[$i]];
        $commissiontotalA[$commissionrateid] += $kladd;
        $commissionsubtotalA[$commissionrateid] += $kladd;
        $linetotal += $kladd * ($commissionrate/100);
      }
      echo '<td align=right>' . myfix($linetotal) . '</td></tr>';
      $commissiontotal += $linetotal;
      $commissionsubtotal += $linetotal;
    }
    $lastclientid = $clientid[$i];
    $lastisreturn = $isreturn[$i];
    $lastclientcategoryid = $clientcategoryid[$i];
  }
  
  ###copy subtotal from above
        echo '<tr><td colspan=3><b>' . $clientcategoryA[$lastclientcategoryid];
      foreach ($commissionrateA as $commissionrateid => $commissionrate)
      {
        echo '<td align=right><b>' . myfix($commissionsubtotalA[$commissionrateid]) . '</td>';
        $commissionsubtotalA[$commissionrateid] = 0;
      }
      echo '<td align=right><b>' . myfix($commissionsubtotal) . '</td></tr>';
      $commissionsubtotal = 0;
  ###

  echo '</tbody><tfoot><tr><td><b>Total</b><td colspan=2>&nbsp;</td>';
  $totaloverzero = 0;
  foreach ($commissionrateA as $commissionrateid => $commissionrate)
  {
    if ($commissionrate > 0) { $totaloverzero += $commissiontotalA[$commissionrateid]; }
    echo '<td align=right><b>' . myfix($commissiontotalA[$commissionrateid]) . '</td>';
  }
  echo '<td align=right><b>' . myfix($commissiontotal) . '</b></td></tr>';
  echo '<tr><td colspan=7>CA hors 0%</td><td align=right>' . myfix($totaloverzero) . '</td></tr>';
  echo '</tfoot></table>';
  break;
  
  
  

case 'rfa':

require('preload/town.php');
require('preload/island.php');
require('preload/regulationzone.php');

$islandid = (int) $_POST['islandid'];
$clientsectorid = (int) $_POST['clientsectorid'];
$client = $_POST['client']; require('inc/findclient.php');
$productfamilyid = (int) $_POST['productfamilyid'];
$islandid = (int) $_POST['islandid'];
$range1 = (int) $_POST['range1'];
$range2 = (int) $_POST['range2'];
$range3 = (int) $_POST['range3'];
$datename = 'startdate'; require('inc/datepickerresult.php'); 
$datename = 'stopdate'; require('inc/datepickerresult.php'); 

$ourtitle = 'RFA PERIODE du ' . datefix2($startdate) . ' au ' . datefix2($stopdate);
showtitle($ourtitle);

$query = 'select postalcode,townid,address,postaladdress,isreturn,address,invoicehistory.invoiceid,productid,invoicehistory.clientid,clientname,accountingdate,sum(quantity) as quantity,sum(lineprice) as lineprice';
$query = $query . ' from invoicehistory,invoiceitemhistory,client';
if ($islandid > 0) { $query = $query . ',town'; }
$query = $query . ' where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and invoicehistory.clientid=client.clientid';
if ($islandid > 0) { $query = $query . ' and client.townid=town.townid'; }
$query = $query . ' and accountingdate>=? and accountingdate<=?';
$query_prm = array($startdate,$stopdate);
$query = $query . ' and (productid=279 or productid=280) and confirmed=1 and cancelledid=0'; # hardcoding the two product ids (279,280) and npu = (6,12)       and isreturn=0
# client,island,sector
if ($clientid > 0) { $query = $query . ' and invoicehistory.clientid=?'; array_push($query_prm,$clientid); }
if ($clientsectorid > 0) { $query = $query . ' and client.clientsectorid=?'; array_push($query_prm,$clientsectorid); }
if ($islandid > 0) { $query = $query . ' and town.islandid=?'; array_push($query_prm,$islandid); }
$query = $query . ' group by clientid,invoiceid,productid';
$query = $query . ' order by clientid,invoiceid,productid';
require('inc/doquery.php');

$lastclientid = -1; $lastinvoiceid = -1; $lastproductid = -1; $linequantity = 0; $linetotal = 0; #$linevalue = 0; $clienttotal = 0;
for ($i=0;$i<$num_results;$i++)
{
  if ($i != 0 && $query_result[$i]['invoiceid'] != $lastinvoiceid)
  {
    if ($lastproductid == 279) { echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>'; }
    echo '<td align=right>' . myfix($linetotal) .'</td></tr>';
    $linetotal = 0;
    # $linequantity = 0; $linevalue = 0; 
  }
  if ($query_result[$i]['clientid'] != $lastclientid)
  {
    if ($i > 0)
    {
      echo '<tr><td colspan=8>TOTAL</td><td align=right>' . myfix($clienttotal) .'</td></tr></table>';
      $clienttotal = 0;
      echo '<p class=breakhere></p>';
    }
    echo '<h2>REMISE DE FIN D\'ANNEE SUR VAIMATO 1,5L<br>PERIODE du ' . datefix2($startdate) . ' au ' . datefix2($stopdate) . '</h2>';
    ###################### copied header from releves, edited
    $outputstring = "";
    $row = $query_result[$i];
    $clientid = $row['clientid'];
    $clientname = $row['clientname'];
    $outputstring = $outputstring . '<table class="transparent" border=0 cellspacing=1 cellpadding=1><tr><td valign=top width=400>';

    $ourlogofile = 'custom_available/' . mb_strtolower($_SESSION['ds_customname']) . '.jpg';
    if (file_exists($ourlogofile)) { $outputstring = $outputstring . '<p><img src="' . $ourlogofile . '"></p>'; }
    $outputstring = $outputstring . '<p>';
    $outputstring = $outputstring . $_SESSION['ds_accounttop'];
    $outputstring = $outputstring . '</p>';

    $outputstring = $outputstring . '</td><td valign=top>&nbsp; &nbsp; &nbsp;</td><td>';
    if ($_POST['showoperations'] == 1) { $outputstring = $outputstring . '<p><b>Relevé client</b></p><p>Toutes transactions<br>' . datefix($startdate) . ' au ' . datefix($stopdate) . '</p>'; }
    else { $outputstring = $outputstring . '<p><b>Compte client</b></p><p>' . datefix($_SESSION['ds_curdate']) . '</p>'; }

    $outputstring = $outputstring . '<p>Client n<span class=sup>o</span> ' . $row['clientid'];
    if ($_POST['dateref'] == 1) { $outputstring = $outputstring . '<br>Numéro relevé: ' . $row['clientid'] . date("YmdH"); }
    $outputstring = $outputstring . '</p>';
    if ($row['tahitinumber'] != "") { $outputstring = $outputstring . '<p>Numéro Tahiti ' . $row['tahitinumber'] . '</p>'; }
    else { $outputstring = $outputstring . '<p>&nbsp;</p>'; }
    if ($showtelephone)
    {
      if ($row['telephone'] != "" || $row['cellphone'] != "") { $outputstring .= '<p>Tél ' . $row['telephone'] . ' ' . $row['cellphone'] . '</p>'; }
    }
    $outputstring = $outputstring . '<p>' . d_output(d_decode($row['clientname'])) . ' ' . $row['companytypename'];
    if ($row['address'] != "") { $outputstring = $outputstring . '<br>' . $row['address']; }
    if ($row['postaladdress'] != "") { $outputstring = $outputstring . '<br>' . $row['postaladdress']; }
    $outputstring = $outputstring . '<br>' . $row['postalcode'] . ' ' . $townA[$row['townid']];
    $outputstring = $outputstring . '<br>' . $islandA[$town_islandidA[$row['townid']]];
    $zoneid = $island_regulationzoneidA[$town_islandidA[$row['townid']]];
    $zone = $regulationzoneA[$zoneid];
    if ($zone != '' and $zone != $islandA[$town_islandidA[$row['townid']]]) { $outputstring = $outputstring . '<br>' . $regulationzoneA[$zoneid]; }
    $outputstring = $outputstring . '</p>';
    $outputstring = $outputstring . '</td></tr></table>';
    #$outputstring .= '<b>Règlement : &nbsp; </b>' . $clienttermA[$row['clienttermid']];
    echo $outputstring;
    ######################
    #echo '<p>' . d_output(d_decode($query_result[$i]['clientname'])) . ' (' . $query_result[$i]['clientid'] . ') ' . d_output($query_result[$i]['address']) . '</p>'; # +address
    echo '<table class="report"><tr><td><b>Date</td><td><b>No Facture</td><td><b>6x1.5l</td><td><b>Valeur</td><td><b>Taux remise</td><td><b>12x1.5l</td><td><b>Valeur</td><td><b>Taux remise</td><td><b>Remise</td></tr>';
  }
  if ($i == 0 || $query_result[$i]['invoiceid'] != $lastinvoiceid)
  {
    echo '<tr><td align=right>' . datefix2($query_result[$i]['accountingdate']) . '</td><td align=right>';
    if ($query_result[$i]['isreturn'] == 1) { echo '(Avoir) '; }
    echo $query_result[$i]['invoiceid'] . '</td>';
  }
  $productid = $query_result[$i]['productid'];
  $quantity = $query_result[$i]['quantity'];
  if ($productid == 279) { $npu = 6; $divider = 1; }
  else { $npu = 12; $divider = 1; }
  $quantity = $quantity / $npu;
  if ($productid == 280 && $query_result[$i]['invoiceid'] != $lastinvoiceid) { echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>'; }
  $lineprice = $query_result[$i]['lineprice']; if ($query_result[$i]['isreturn'] == 1) { $lineprice = 0 - $lineprice; }
  echo '<td align=right>' . myfix($quantity) . '</td><td align=right>' . myfix($lineprice) . '</td>';
    $taux = $range3;
    if ($quantity < 100) { $taux = $range2; }
    if ($quantity < 50) { $taux = $range1; }
    if ($quantity < 20) { $taux = 0; }
    $remise = myround($lineprice * $taux/100);
    $clienttotal = $clienttotal + $remise;
    $linetotal = $linetotal + $remise;
	echo '<td align=right>'.$taux.'%</td>';
#  $linequantity = $linequantity + ($quantity/$divider);
#  $linevalue = $linevalue + $query_result[$i]['lineprice'];
  $lastclientid = $query_result[$i]['clientid'];
  $lastinvoiceid = $query_result[$i]['invoiceid'];
  $lastproductid = $query_result[$i]['productid'];
}
if ($num_results)
{
  if ($lastproductid == 279) { echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>'; }
  echo '<td align=right>' . myfix($linetotal) .'</td></tr>';
  echo '<tr><td colspan=8>TOTAL</td><td align=right>' . myfix($clienttotal) .'</td></tr></table>';
}

break;



case 'toorder':
require('preload/unittype.php');
echo '<TITLE>Produits à commander</TITLE>';
echo '</HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>';
echo '<h1>Produits à commander</h1>';



$query = 'select curdate() as curdate';
$result = mysql_query($query, $db_conn); querycheck($result);
$row = mysql_fetch_array($result);
$currentdate = $row['curdate'];
    $currentday = substr($currentdate,8,2);
    $currentmonth = substr($currentdate,5,2);
    $currentyear = substr($currentdate,0,4);
$currentdate = d_builddate(1,$currentmonth,$currentyear);
$lastyeardate = d_builddate(1,$currentmonth,$currentyear-1);

###### functions from DAUPHIN
  function d_d_builddate($day,$month,$year)
  {
    $day = $day + 0; $month = $month + 0;
    if ($day < 10) { $day = '0' . $day; }
    if ($month < 10) { $month = '0' . $month; }
    $date = $year . '-' .  $month . '-' . $day;
    return $date;
  }

  function d_correctdate($date)
  {
    $day = substr($date,8,2);
    $month = substr($date,5,2);
    $year = substr($date,0,4);
    switch ($month)
    {
      case 2:
        if ($year%4 == 0) { $maxday = 29; }
        else { $maxday = 28; }
      break;
      case 4:
      case 6:
      case 9:
      case 11:
        $maxday = 30;
      break;
      default:
        $maxday = 31;
      break;
    }
    if ($day > $maxday) { $day = $maxday; }
    $fixeddate = d_d_builddate($day,$month,$year);
    return $fixeddate;
  }
###################

$monthstart = d_correctdate(d_d_builddate($currentday,$currentmonth-1,$currentyear));
$monthend = d_correctdate(d_d_builddate($currentday,$currentmonth,$currentyear));
#echo 'debug dates= ' . $monthstart . ' ' . $monthend . '<br>';

################ calc avgmonthly if asked for
  if ($_POST['recalc'] == 1 && 1 === 0) # 2013 01 19 disabled
  {

    # calc all stock
    $query = 'select productid,numberperunit,unittypeid from product,productfamily where product.productfamilyid=productfamily.productfamilyid and discontinued<>1';
    if ($_POST['productfamilygroupid'] != 0) { $query = $query . ' and productfamilygroupid="' . $_POST['productfamilygroupid'] . '"'; }
    if ($_POST['frigo'] > 0)
    {
      $query = 'select productid,numberperunit,unittypeid from product,productfamily,productfamilygroup where product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and discontinued<>1';
      if ($_POST['productfamilygroupid'] != 0) { $query = $query . ' and productfamily.productfamilygroupid="' . $_POST['productfamilygroupid'] . '"'; }
      #if ($_POST['frigo'] == 1) { $query = $query . ' and productfamilygroup.productdepartmentid=3 and product.regulationtypeid<>6 and product.regulationtypeid<>7 and product.regulationtypeid<>15 and product.regulationtypeid<>16'; }
      #if ($_POST['frigo'] == 2) { $query = $query . ' and productfamilygroup.productdepartmentid<>3 and product.regulationtypeid<>6 and product.regulationtypeid<>7 and product.regulationtypeid<>15 and product.regulationtypeid<>16'; }
      #if ($_POST['frigo'] == 3) { $query = $query . ' and productfamilygroup.productdepartmentid<>3 and product.regulationtypeid=6 or product.regulationtypeid=7 or product.regulationtypeid=15 or product.regulationtypeid=16'; }
      if ($_POST['frigo'] == 1) { $query = $query . ' and productfamilygroup.productdepartmentid=3'; }
      if ($_POST['frigo'] == 2) { $query = $query . ' and productfamilygroup.productdepartmentid<>3'; }
    }
    $result4 = mysql_query($query, $db_conn); querycheck($result4);
    $num_results4 = mysql_num_rows($result4);
    for ($x=1; $x <= $num_results4; $x++)
    {
      $row4 = mysql_fetch_array($result4);
      $productid = $row4['productid'];
      $mydivider = $row4['numberperunit']; if ($mydivider == 0) { $mydivider = 1; }

    ########## single product calc

    $total = 0;
    for ($i=1;$i <= 12; $i++)
    {
      $sales[$i] = 0;
    }

    if ($row4['unittypeid'] == 6)
    {
      $query = 'select sum(weightone+weighttwo+weightthree+weightfour+weightfive+weightsix) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and deliveryagentid=0 and cancelled=0 and invoiceitem.productid="' . $productid . '" and accountingdate>="' . $lastyeardate . '" and accountingdate<"' . $currentdate . '" group by month';
      $result = mysql_query($query, $db_conn); querycheck($result);
      $num_results = mysql_num_rows($result);
      for ($i=1;$i <= $num_results; $i++)
      {
        $row = mysql_fetch_array($result);
        $kladd = $row['month'];
        $sales[$kladd] = $row['sales'];
        $total = $total + $row['sales'];
      }
      $query = 'select sum(weightone+weighttwo+weightthree+weightfour+weightfive+weightsix) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and deliveryagentid=0 and cancelled=0 and invoiceitemhistory.productid="' . $productid . '" and accountingdate>="' . $lastyeardate . '" and accountingdate<"' . $currentdate . '" group by month';
      $result = mysql_query($query, $db_conn); querycheck($result);
      $num_results = mysql_num_rows($result);
      for ($i=1;$i <= $num_results; $i++)
      {
        $row = mysql_fetch_array($result);
        $kladd = $row['month'];
        $sales[$kladd] = $sales[$kladd] + $row['sales'];
        $total = $total + $row['sales'];
      }
      $query = 'select sum(weightone+weighttwo+weightthree+weightfour+weightfive+weightsix) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoicetemp,invoiceitemtemp where invoiceitemtemp.invoiceid=invoicetemp.invoiceid and deliveryagentid=0 and cancelled=0 and invoiceitemtemp.productid="' . $productid . '" and accountingdate>="' . $lastyeardate . '" and accountingdate<"' . $currentdate . '" group by month';
      $result = mysql_query($query, $db_conn); querycheck($result);
      $num_results = mysql_num_rows($result);
      for ($i=1;$i <= $num_results; $i++)
      {
        $row = mysql_fetch_array($result);
        $kladd = $row['month'];
        $sales[$kladd] = $sales[$kladd] + $row['sales'];
        $total = $total + $row['sales'];
      }
    }
    else
    {
      $query = 'select sum(quantity) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and deliveryagentid=0 and cancelled=0 and invoiceitem.productid="' . $productid . '" and accountingdate>="' . $lastyeardate . '" and accountingdate<"' . $currentdate . '" group by month';
    #echo $query . '<br>';
      $result = mysql_query($query, $db_conn); querycheck($result);
      $num_results = mysql_num_rows($result);
      for ($i=1;$i <= $num_results; $i++)
      {
        $row = mysql_fetch_array($result);
        $kladd = $row['month'];
        $sales[$kladd] = $row['sales'];
        $total = $total + $row['sales'];
      }
      $query = 'select sum(quantity) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and deliveryagentid=0 and cancelled=0 and invoiceitemhistory.productid="' . $productid . '" and accountingdate>="' . $lastyeardate . '" and accountingdate<"' . $currentdate . '" group by month';
      $result = mysql_query($query, $db_conn); querycheck($result);
      $num_results = mysql_num_rows($result);
      for ($i=1;$i <= $num_results; $i++)
      {
        $row = mysql_fetch_array($result);
        $kladd = $row['month'];
        $sales[$kladd] = $sales[$kladd] + $row['sales'];
        $total = $total + $row['sales'];
      }
      $query = 'select sum(quantity) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoicetemp,invoiceitemtemp where invoiceitemtemp.invoiceid=invoicetemp.invoiceid and deliveryagentid=0 and cancelled=0 and invoiceitemtemp.productid="' . $productid . '" and accountingdate>="' . $lastyeardate . '" and accountingdate<"' . $currentdate . '" group by month';
      $result = mysql_query($query, $db_conn); querycheck($result);
      $num_results = mysql_num_rows($result);
      for ($i=1;$i <= $num_results; $i++)
      {
        $row = mysql_fetch_array($result);
        $kladd = $row['month'];
        $sales[$kladd] = $sales[$kladd] + $row['sales'];
        $total = $total + $row['sales'];
      }
    }

    $minmonth = floor($total/12); $total = 0; $monthcount = 0;

    for ($i=1;$i <= 12; $i++)
    {
    #  if ($sales[$i] >= $minmonth)
      if ($sales[$i] >= 0)
      {
        $XYZyear = $currentyear;
        if ($i >= $currentmonth) { $XYZyear = $currentyear - 1; }
        $queryXYZ = 'select stock from monthlystock where productid="' . $productid . '" and month="' . $i . '" and year="' . $XYZyear . '"';
        $resultXYZ = mysql_query($queryXYZ, $db_conn); querycheck($resultXYZ);
        $rowXYZ = mysql_fetch_array($resultXYZ);
    #echo $queryXYZ . '<br>';
    #echo $rowXYZ['stock'] . '<br>';
        if ($rowXYZ['stock'] > 0)
        {
    #echo 'counted<br>';
          $monthcount++;
          $total = $total + $sales[$i];
        }
      }
    }

    if ($monthcount < 1) { $monthcount = 1; }
    $avgmonthly = round($total/$monthcount);

    $query = 'update product set avgmonthly="' . $avgmonthly . '" where productid="' . $productid . '"';
    #echo $query . '<br>';
    $result = mysql_query($query, $db_conn); querycheck($result);

    ###########

    }

  }
################ end calc avgmonthly

  $exarr = $_POST['exarr'];
  if ($exarr <> 1) { $exarr = 0; }

  echo '<table class="report">';
  $query = 'select unittypeid,client.clientid as supplierid,product.productid,productname,numberperunit,netweightlabel,client.leadtime,currentstock,clientname as suppliername';
  if ($_POST['whichavg'] == 1) { $query .= ',(if(avgmonthlyspec=0,avgmonthly,avgmonthlyspec)/numberperunit) as avgmonthly,(currentstock/(if(avgmonthlyspec=0,avgmonthly,avgmonthlyspec)/numberperunit)) as coeff'; }
  else { $query .= ',(avgmonthly/numberperunit) as avgmonthly,(currentstock/(avgmonthly/numberperunit)) as coeff'; }
  $query .= ' from product,productfamily,client,productfamilygroup where product.supplierid=client.clientid and product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and notforsale=0 and todiscontinue=0 and discontinued=0 and avgmonthly>0 and client.leadtime>0';
  #$query = $query . ' and supplier.countryid<>156';?
  #  and unittypeid<>14
  if ($_POST['productfamilygroupid'] != 0) { $query = $query . ' and productfamily.productfamilygroupid="' . $_POST['productfamilygroupid'] . '"'; }
  #if ($_POST['frigo'] == 1) { $query = $query . ' and productfamilygroup.productdepartmentid=3 and product.regulationtypeid<>6 and product.regulationtypeid<>7 and product.regulationtypeid<>15 and product.regulationtypeid<>16'; }
  #if ($_POST['frigo'] == 2) { $query = $query . ' and productfamilygroup.productdepartmentid<>3 and product.regulationtypeid<>6 and product.regulationtypeid<>7 and product.regulationtypeid<>15 and product.regulationtypeid<>16'; }
  #if ($_POST['frigo'] == 3) { $query = $query . ' and productfamilygroup.productdepartmentid<>3 and product.regulationtypeid=6 or product.regulationtypeid=7 or product.regulationtypeid=15 or product.regulationtypeid=16'; }
  if ($_POST['frigo'] == 1) { $query = $query . ' and productfamilygroup.productdepartmentid=3'; }
  if ($_POST['frigo'] == 2) { $query = $query . ' and productfamilygroup.productdepartmentid<>3'; }
  $temp = (int) $_POST['temperatureid'];
  if ($temp > -1) { $query = $query . ' and product.temperatureid="'.$temp.'"'; }
  if ($_POST['supplierid'] != "")
  {
    $supplierid = (int) $_POST['supplierid'];
    $query = $query . ' and product.supplierid=' . $_POST['supplierid']; #echo $query;
  }
### show all products even if ordered
#  $query = $query . ' and (currentstock - (supplier.leadtime * (avgmonthly/numberperunit)))<=0';
### init dont show
$exclcounter = 0; unset($excludeid);
  $query = $query . ' order by suppliername,coeff asc';

  $result2 = mysql_query($query, $db_conn); querycheck($result2);
  $num_results2 = mysql_num_rows($result2);
  echo '<tr><td><b>Produit</td><td><b>Cond</td><td><b>Stock</td><td><b>ETA</td><td><b>Coeff</td><td><b>Lead months</td><td><b>Avg monthly</td><td><b>Fournisseur</td></tr>';
  for ($y=1; $y <= $num_results2; $y++)
  {
    $row2 = mysql_fetch_array($result2);
    $dmp = $unittype_dmpA[$row2['unittypeid']];
    $row2['currentstock'] /= $dmp;
    $row2['coeff'] /= $dmp;
    $npu = $row2['numberperunit'];
#echo 'DEBUG checking productid ' . $row2['productid'] . '<br>';

    ### NEW check purchase and local purchase for incoming stock

    $incoming = 0;
    $query5 = 'select sum(amount) as amount from purchase,shipment where purchase.shipmentid=shipment.shipmentid and productid="' . $row2['productid'] . '" and shipmentstatus<>"Fini"';
    $result5 = mysql_query($query5, $db_conn); querycheck($result5);
    $row5 = mysql_fetch_array($result5);
    if ($row5['amount'] > 0)
    {
    /*
      $query6 = 'select numberperunit from product where productid="' . $row2['productid'] . '"';
      $result6 = mysql_query($query6, $db_conn); querycheck($result6);
      $row6 = mysql_fetch_array($result6);
      $npu = $row6['numberperunit'];
      $incoming = $incoming + ($row5['amount'] / $row6['numberperunit']);
      */
      $incoming += ($row5['amount'] / $npu);
    }
/*
    $query5 = 'select sum(amount) as amount from lpurchase,containerpurchase where lpurchase.containerpurchaseid=containerpurchase.containerpurchaseid and productid="' . $row2['productid'] . '" and finished=0';
    $result5 = mysql_query($query5, $db_conn); querycheck($result5);
    $row5 = mysql_fetch_array($result5);
    if ($row5['amount'] > 0)
    {
      $query6 = 'select numberperunit from product where productid="' . $row2['productid'] . '"';
      $result6 = mysql_query($query6, $db_conn); querycheck($result6);
      $row6 = mysql_fetch_array($result6);
      $npu = $row6['numberperunit'];
      $incoming = $incoming + ($row5['amount'] / $row6['numberperunit']);
    }
*/
    ###

    $currentstock = myfix($row2['currentstock']);
    $kladd = (($row2['currentstock']+$incoming) - ($row2['leadtime'] * $row2['avgmonthly']));
#echo 'DEBUG cstock= ' . $row2['currentstock'] . '<br>';
#echo 'DEBUG incoming= ' . $incoming . '<br>';
#echo 'DEBUG leadtime*avgmonthly= ' . ($row2['leadtime'] * $row2['avgmonthly']) . '<br>';
    if ($exarr == 0 || ($exarr == 1 && $incoming == 0))
    {
      $kladd = -1; ### show all products even if ordered
      if ($row2['coeff'] > $row2['leadtime'])
      {
        $excludeid[$exclcounter] = $row2['productid'] . ': ' . d_decode($row2['productname']) . ' ' . $row2['numberperunit'] . ' x ' . $row2['netweightlabel'] . ' &nbsp; [Coeff= ' . $row2['coeff'] . ' &nbsp; Lead= ' . $row2['leadtime'] . ']';
        $exclcounter++;
        $kladd = 1; # dont show
        #if ($row2['productid'] == 150) { $kladd = 0; } #debug
      }

      ### check for ventes exceptionnelles
      if ($_POST['vexcpt'] == 1)
      {
        $total = 0; $vexcpt_alert = 0;

        $query = 'select sum(quantity) as sales from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and isnotice=0 and proforma=1 and cancelledid=0 and confirmed=1 and invoiceitemhistory.productid="' . $row2['productid'] . '" and accountingdate>="' . $monthstart . '" and accountingdate<"' . $monthend . '"';
        $result = mysql_query($query, $db_conn); querycheck($result);
        $num_results = mysql_num_rows($result);
        for ($i=1;$i <= $num_results; $i++)
        {
          $row = mysql_fetch_array($result);
          $total = $total + $row['sales'];
        }
        /*
        $query = 'select sum(quantity) as sales from invoicetemp,invoiceitemtemp where invoiceitemtemp.invoiceid=invoicetemp.invoiceid and isnotice=0 and cancelledid=0 and invoiceitemtemp.productid="' . $row2['productid'] . '" and accountingdate>="' . $monthstart . '" and accountingdate<"' . $monthend . '"';
        $result = mysql_query($query, $db_conn); querycheck($result);
        $num_results = mysql_num_rows($result);
        for ($i=1;$i <= $num_results; $i++)
        {
          $row = mysql_fetch_array($result);
          $total = $total + $row['sales'];
        }
        */


        $total = $total / $row2['numberperunit'];
        $ourtestmonthly = $row2['avgmonthly'] * 1.5;
        #echo 'debug month total = ' . $total . '  150%avg= ' . $ourtestmonthly . '<br>';
        if ($total > $ourtestmonthly) { $kladd = -1; $vexcpt_alert = 1; }
      }

      if ($kladd <= 0)
      {
        echo '<tr><td valign=top>' . $row2['productid'] . ': ' . d_decode($row2['productname']);
        if ($vexcpt_alert == 1) { echo ' <font color=red>AVE</font>'; }
        echo '</td><td align=right valign=top>' . $row2['numberperunit'] . ' x ' . $row2['netweightlabel'] . '</td><td align=right valign=top>' . $currentstock . '</td><td valign=top><font color=blue>';
        if ($incoming > 0)
        {
          $query5 = 'select amount,arrivaldate from purchase,shipment where purchase.shipmentid=shipment.shipmentid and productid="' . $row2['productid'] . '" and shipmentstatus<>"Fini"';
          $result5 = mysql_query($query5, $db_conn); querycheck($result5);
          $num_results5 = mysql_num_rows($result5);
          for ($x=1; $x <= $num_results5; $x++)
          {
            $row5 = mysql_fetch_array($result5);
            echo datefix2($row5['arrivaldate']) . ' +' . $row5['amount']/$npu;
            echo '<br>';
          }
          /*
          $query5 = 'select amount,arrivaldate from lpurchase,containerpurchase where lpurchase.containerpurchaseid=containerpurchase.containerpurchaseid and productid="' . $row2['productid'] . '" and finished=0';
          $result5 = mysql_query($query5, $db_conn); querycheck($result5);
          $num_results5 = mysql_num_rows($result5);
          for ($x=1; $x <= $num_results5; $x++)
          {
            $row5 = mysql_fetch_array($result5);
            echo datefix2($row5['arrivaldate']) . ' +' . $row5['amount']/$npu;
            echo '<br>';
          }
          */
        }
        else { echo '&nbsp;'; }
        echo '</font></td><td align=right valign=top>' . round($row2['coeff'],2) . '</td><td align=right valign=top>' . $row2['leadtime'] . '</td><td align=right valign=top>' . myfix($row2['avgmonthly']) . '</td><td valign=top>' . $row2['supplierid'] . ': ' . $row2['suppliername'] . '</td></tr>';
      }
    }
  }
  echo '</table>';
  echo '<p><font color=red>Les produits DISCONTINUE, A DISCONTINUER, NON MIS A LA VENTE ou CO-PACK ne figurent pas dans ce rapport.</font></p>';
  echo '<p><font color=red>Les produits des fournisseurs polynesiens et des fournisseurs avec un LEAD TIME ZERO ne figurent pas dans ce rapport.</font></p>';

  echo '<p><font color=red>Les produits suivants ne figurent pas dans ce rapport, car leur Coeff > Lead months:</font></p>';
  echo '<font color=red>';
  for ($y=0; $y < $exclcounter; $y++)
  {
    echo $excludeid[$y] . '<br>';
  }
  echo '<br>';

  echo '<p><font color=red>Les produits suivants ne figurent pas dans ce rapport, car les ventes moyenne par mois sont ZERO:</font></p>';
  echo '<font color=red>';
  $query = 'select productid,productname,numberperunit,netweightlabel from product,client where product.supplierid=client.clientid and notforsale=0 and discontinued=0 and avgmonthly=0 and client.leadtime>0';
  if ($temp > -1) { $query = $query . ' and product.temperatureid="'.$temp.'"'; }
  if ($_POST['supplierid'] != "")
  {
    $supplierid = (int) $_POST['supplierid'];
    $query = $query . ' and product.supplierid=' . $_POST['supplierid']; #echo $query;
  }
  $result = mysql_query($query, $db_conn); querycheck($result);
  $num_results = mysql_num_rows($result);
  for ($y=1; $y <= $num_results; $y++)
  {
    $row = mysql_fetch_array($result);
    echo ' ' . $row['productid'] . ': ' . d_decode($row['productname']) . ' ' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '<br>';
  }
  break;

  
  
  case 'valstock3112':
  require('preload/unittype.php');
  
  $PA['shownestle'] = 'uint';
  $PA['year'] = 'uint';
  $PA['split_prev'] = 'uint';
  require('inc/readpost.php');
  $total = 0;
  
  echo '<TITLE>Valorisation stock au 31/12/' . $year . '</TITLE>';
  echo '</HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>';
  echo '<h1>Valorisation stock au 31/12/' . $year . '</h1>';

  $supplierstring = '';
  if ($shownestle == 1)
  {
    echo '<h2>Produits Nestlé</h2>';
    $supplierstring = 'and supplierid=4126';
    $prrstring = ' PRR';
  }
  else
  {
    echo '<h2>Produits Wing Chong</h2>';
    $supplierstring = 'and supplierid<>4126';
    $prrstring = '';
  }
  if ($_POST['temperatureid'] >= 0)
  {
    $temp = (int) $_POST['temperatureid'];
    $supplierstring .= ' and temperatureid='.$temp;
  }

  echo '<table class="report">';

  echo '<tr><td><b>Produits PPN (hors Dép. Surgelé)</td><td><b>Code</td><td><b>Cond.</td><td><b>Quantité</td><td><b>PRU</td><td><b>Total Valeur</td></tr>';
  $subtotal = 0;
  $query = 'select unittypeid,productname,product.productid,netweightlabel,numberperunit,stock from product,endofyearstock,productfamily,productfamilygroup';
  $query = $query . ' where endofyearstock.productid=product.productid and product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid
  and year=' . $year . ' and productfamilygroup.productdepartmentid<>3 and product.producttypeid=1 and discontinued=0 ' . $supplierstring;
  $query = $query . ' order by productname';
#$query .= ' limit 10';
  $query_prm = array();
  require ('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0; $i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    $stock = $row['stock'];
    if ($row['numberperunit'] > 0) { $stock = floor($row['stock'] / $row['numberperunit']); }
    if ($unittype_dmpA[$row['unittypeid']] > 0) { $stock /= $unittype_dmpA[$row['unittypeid']]; }
    $value = 0;
    if ($stock > 0)
    {
      if ($split_prev == 0)
      {
        # 2013 04 03 will read through lots until a value is found
        $query = 'select cost,prev from purchasebatch where productid=? and year(arrivaldate)=? order by arrivaldate desc limit 10';
        $query_prm = array($row['productid'], $year);
        require('inc/doquery.php');
        if ($num_results)
        {
          $y = 0; $done = 0;
          while (!$done)
          {
            $cost = round($query_result[$y]['cost'] * $row['numberperunit']);
            if ($cost == 0) { $cost = $query_result[$y]['prev']+0; }
            $y++;
            if ($cost > 0 || $y >= 10) { $done = 1; }
          }
        }
        else { $cost = 0; }
        
        $value = round($stock * $cost);
        if ($shownestle == 1) { $value = $value - round($value * $_POST['ppnperc']/100); }
        echo '<tr><td>' . d_decode($row['productname']) . '</td><td align=right>' . $row['productid'];
        echo '<td>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td><td align=right>' . $stock . '</td><td align=right>' . $cost . '</td><td align=right>' . $value;
      }
      else
      {
        ###
        # 2018 01 16 list of all purchasebatches
        $showemptylots = 0; $numberperunit = $row['numberperunit'];
        $query = 'select prev,shipmentid,batchname,supplierbatchname,initials,purchasebatch.userid,purchasebatchid,arrivaldate,origamount,amount,totalcost,vat,useby';
        $query = $query . ' from purchasebatch,usertable';
        $query = $query . ' where purchasebatch.userid=usertable.userid and purchasebatch.deleted=0 and productid=? and year(arrivaldate)<=?';
        $query = $query . ' order by arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc';
        $query_prm = array($row['productid'], $year);
        require('inc/doquery.php');
        $main_result2 = $query_result; $num_results_main2 = $num_results;
        for ($y=0; $y < $num_results_main2; $y++)
        {
          $row2 = $main_result2[$y];
          if ($showemptylots > -1)
          {
            $lotsize = $row2['amount'];
            $showlotsize = floor($lotsize/$numberperunit); $showlotsizerest = $lotsize%$numberperunit;
            $showlotorigsize = floor($row2['origamount']/$numberperunit); $showlotorigsizerest = $lotsize%$numberperunit;
            if ($_SESSION['ds_useunits'] && $showlotsizerest)
            {
              $showlotsize = $showlotsize . ' <font size=-1>' . $showlotsizerest . '</font>';
              $showlotorigsize = $showlotorigsize . ' <font size=-1>' . $showlotorigsizerest . '</font>';
            }
            $stock = $stock - $lotsize;
            $amountleft = $lotsize;
            if ($stock < 0) { $amountleft = $amountleft + $stock; }
            if ($amountleft < 0) { $amountleft = 0; }
            $showamountleft = floor($amountleft/$numberperunit); $showamountleftrest = $amountleft%$numberperunit;
            if ($_SESSION['ds_useunits'] && $showamountleftrest) { $showamountleft = $showamountleft . ' <font size=-1>' . $showamountleftrest . '</font>'; }
            if ($stock <= 0) { $showemptylots--; }
            $prev = $row2['prev']+0;
            if ($lotsize > 0)
            {
              $value = round($amountleft * $prev);
              if ($_POST['shownestle'] == 1) { $value = $value - round($value * $_POST['ppnperc']/100); }
              echo '<tr><td>' . d_decode($row['productname']) . '</td><td align=right>' . $row['productid'];
              echo '<td>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td><td align=right>' . ($amountleft+0) . '</td><td align=right>' . $prev . '</td><td align=right>' . $value;
            }
          }
        }
        ###
      }
    }
    $subtotal = $subtotal + $value;
  }
  echo '<tr><td colspan=5><b>Total Valorisation PPN' . $prrstring . ' (hors Dép. Surgelé)</td><td align=right><b>' . $subtotal . '</td></tr><tr><td colspan=6>&nbsp;</td></tr>';
  $total = $total + $subtotal;
  echo '<tr><td><b>Autres Produits (hors Dép. Surgelé)</td><td><b>Code</td><td><b>Cond.</td><td><b>Quantité</td><td><b>PRU</td><td><b>Total Valeur</td></tr>';
  $subtotal = 0;
  $query = 'select unittypeid,productname,product.productid,netweightlabel,numberperunit,stock from product,endofyearstock,productfamily,productfamilygroup';
  $query = $query . ' where endofyearstock.productid=product.productid and product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid
  and year=' . $year . ' and productfamilygroup.productdepartmentid<>3 and product.producttypeid<>1 and discontinued=0 ' . $supplierstring;
  $query = $query . ' order by productname';
#$query .= ' limit 10';
  $query_prm = array();
  require ('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0; $i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    $stock = floor(($row['stock'] / $row['numberperunit'])/$unittype_dmpA[$row['unittypeid']]);
    $value = 0;
    if ($stock > 0)
    {
      if ($split_prev == 0)
      {
        # 2013 04 03 will read through lots until a value is found
        $query = 'select cost,prev from purchasebatch where productid=? and year(arrivaldate)=? order by arrivaldate desc limit 10';
        $query_prm = array($row['productid'], $year);
        require('inc/doquery.php');
        if ($num_results)
        {
          $y = 0; $done = 0;
          while (!$done)
          {
            $cost = round($query_result[$y]['cost'] * $row['numberperunit']);
            if ($cost == 0) { $cost = $query_result[$y]['prev']+0; }
            $y++;
            if ($cost > 0 || $y >= 10) { $done = 1; }
          }
        } else { $cost = 0; }
        
        $value = round($stock * $cost);
        if ($shownestle == 1) { $value = $value - round($value * $_POST['ppnperc']/100); }
        echo '<tr><td>' . d_decode($row['productname']) . '</td><td align=right>' . $row['productid'];
        echo '<td>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td><td align=right>' . $stock . '</td><td align=right>' . $cost . '</td><td align=right>' . $value;
      }
      else
      {
        ###
        # 2018 01 16 list of all purchasebatches
        $showemptylots = 0; $numberperunit = $row['numberperunit'];
        $query = 'select prev,shipmentid,batchname,supplierbatchname,initials,purchasebatch.userid,purchasebatchid,arrivaldate,origamount,amount,totalcost,vat,useby';
        $query = $query . ' from purchasebatch,usertable';
        $query = $query . ' where purchasebatch.userid=usertable.userid and purchasebatch.deleted=0 and productid=? and year(arrivaldate)<=?';
        $query = $query . ' order by arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc';
        $query_prm = array($row['productid'], $year);
        require('inc/doquery.php');
        $main_result2 = $query_result; $num_results_main2 = $num_results;
        for ($y=0; $y < $num_results_main2; $y++)
        {
          $row2 = $main_result2[$y];
          if ($showemptylots > -1)
          {
            $lotsize = $row2['amount'];
            $showlotsize = floor($lotsize/$numberperunit); $showlotsizerest = $lotsize%$numberperunit;
            $showlotorigsize = floor($row2['origamount']/$numberperunit); $showlotorigsizerest = $lotsize%$numberperunit;
            if ($_SESSION['ds_useunits'] && $showlotsizerest)
            {
              $showlotsize = $showlotsize . ' <font size=-1>' . $showlotsizerest . '</font>';
              $showlotorigsize = $showlotorigsize . ' <font size=-1>' . $showlotorigsizerest . '</font>';
            }
            $stock = $stock - $lotsize;
            $amountleft = $lotsize;
            if ($stock < 0) { $amountleft = $amountleft + $stock; }
            if ($amountleft < 0) { $amountleft = 0; }
            $showamountleft = floor($amountleft/$numberperunit); $showamountleftrest = $amountleft%$numberperunit;
            if ($_SESSION['ds_useunits'] && $showamountleftrest) { $showamountleft = $showamountleft . ' <font size=-1>' . $showamountleftrest . '</font>'; }
            if ($stock <= 0) { $showemptylots--; }
            $prev = $row2['prev']+0;
            if ($lotsize > 0)
            {
              $value = round($amountleft * $prev);
              if ($_POST['shownestle'] == 1) { $value = $value - round($value * $_POST['ppnperc']/100); }
              echo '<tr><td>' . d_decode($row['productname']) . '</td><td align=right>' . $row['productid'];
              echo '<td>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td><td align=right>' . ($amountleft+0) . '</td><td align=right>' . $prev . '</td><td align=right>' . $value;
            }
          }
        }
        ###
      }
    }
    $subtotal = $subtotal + $value;
  }
  echo '<tr><td colspan=5><b>Total Valorisation Autres Produits' . $prrstring . ' (hors Dép. Surgelé)</td><td align=right><b>' . $subtotal . '</td></tr><tr><td colspan=6>&nbsp;</td></tr>';
  $total = $total + $subtotal;

  echo '<tr><td><b>Produits Dép. Surgéle</td><td><b>Code</td><td><b>Cond.</td><td><b>Quantité</td><td><b>PRU</td><td><b>Total Valeur</td></tr>';
  $subtotal = 0;
  $query = 'select unittypeid,productname,product.productid,netweightlabel,numberperunit,stock from product,endofyearstock,productfamily,productfamilygroup';
  $query = $query . ' where endofyearstock.productid=product.productid and product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and year=' . $year . ' and productfamilygroup.productdepartmentid=3 and discontinued=0 ' . $supplierstring;
  $query = $query . ' order by productname';
#$query .= ' limit 10';
  $query_prm = array();
  require ('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0; $i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    $stock = floor(($row['stock'] / $row['numberperunit'])/$unittype_dmpA[$row['unittypeid']]);
    $value = 0;
    if ($stock > 0)
    {
      if ($split_prev == 0)
      {
        # 2013 04 03 will read through lots until a value is found
        $query = 'select cost,prev from purchasebatch where productid=? and year(arrivaldate)=? order by arrivaldate desc limit 10';
        $query_prm = array($row['productid'], $year);
        require('inc/doquery.php');
        if ($num_results)
        {
          $y = 0; $done = 0;
          while (!$done)
          {
            $cost = round($query_result[$y]['cost'] * $row['numberperunit']);
            if ($cost == 0) { $cost = $query_result[$y]['prev']+0; }
            $y++;
            if ($cost > 0 || $y >= 10) { $done = 1; }
          }
        } else { $cost = 0; }
        
        $value = round($stock * $cost);
        if ($_POST['shownestle'] == 1) { $value = $value - round($value * $_POST['ppnperc']/100); }
        echo '<tr><td>' . d_decode($row['productname']) . '</td><td align=right>' . $row['productid'];
        echo '<td>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td><td align=right>' . $stock . '</td><td align=right>' . $cost . '</td><td align=right>' . $value;
      }
      else
      {
        ###
        # 2018 01 16 list of all purchasebatches
        $showemptylots = 0; $numberperunit = $row['numberperunit'];
        $query = 'select prev,shipmentid,batchname,supplierbatchname,initials,purchasebatch.userid,purchasebatchid,arrivaldate,origamount,amount,totalcost,vat,useby';
        $query = $query . ' from purchasebatch,usertable';
        $query = $query . ' where purchasebatch.userid=usertable.userid and purchasebatch.deleted=0 and productid=? and year(arrivaldate)<=?';
        $query = $query . ' order by arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc';
        $query_prm = array($row['productid'], $year);
        require('inc/doquery.php');
        $main_result2 = $query_result; $num_results_main2 = $num_results;
        for ($y=0; $y < $num_results_main2; $y++)
        {
          $row2 = $main_result2[$y];
          if ($showemptylots > -1)
          {
            $lotsize = $row2['amount'];
            $showlotsize = floor($lotsize/$numberperunit); $showlotsizerest = $lotsize%$numberperunit;
            $showlotorigsize = floor($row2['origamount']/$numberperunit); $showlotorigsizerest = $lotsize%$numberperunit;
            if ($_SESSION['ds_useunits'] && $showlotsizerest)
            {
              $showlotsize = $showlotsize . ' <font size=-1>' . $showlotsizerest . '</font>';
              $showlotorigsize = $showlotorigsize . ' <font size=-1>' . $showlotorigsizerest . '</font>';
            }
            $stock = $stock - $lotsize;
            $amountleft = $lotsize;
            if ($stock < 0) { $amountleft = $amountleft + $stock; }
            if ($amountleft < 0) { $amountleft = 0; }
            $showamountleft = floor($amountleft/$numberperunit); $showamountleftrest = $amountleft%$numberperunit;
            if ($_SESSION['ds_useunits'] && $showamountleftrest) { $showamountleft = $showamountleft . ' <font size=-1>' . $showamountleftrest . '</font>'; }
            if ($stock <= 0) { $showemptylots--; }
            $prev = $row2['prev']+0;
            if ($lotsize > 0)
            {
              $value = round($amountleft * $prev);
              if ($_POST['shownestle'] == 1) { $value = $value - round($value * $_POST['ppnperc']/100); }
              echo '<tr><td>' . d_decode($row['productname']) . '</td><td align=right>' . $row['productid'];
              echo '<td>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td><td align=right>' . ($amountleft+0) . '</td><td align=right>' . $prev . '</td><td align=right>' . $value;
            }
          }
        }
        ###
      }
    }
    $subtotal = $subtotal + $value;
  }
  echo '<tr><td colspan=5><b>Total Valorisation Dép. Surgéle' . $prrstring . '</td><td align=right><b>' . $subtotal . '</td></tr><tr><td colspan=6>&nbsp;</td></tr>';
  $total = $total + $subtotal;
  echo '<tr><td colspan=5><b>Total Valorisation' . $prrstring . '</td><td align=right><b>' . $total . '</td></tr>';

  echo '</table>';
  break;

  case 'cadvente':
  $su = $_POST['showunits'];

  $lastyear = ($_POST['year'] - 1);
  $nextyear = $lastyear + 2;
  $showvalue = $_POST['showvalue'];


  $stockmonths = $_POST['stockmonths'];
  if ($stockmonths == "") { $stockmonths = 9999; }


  $cartonspermonth = $_POST['cartonspermonth'];
  if ($cartonspermonth == "") { $cartonspermonth = 9999999; }
  $testcartonspermonth = 0;
 
  

  $query = 'select curdate() as curdate';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $row = mysql_fetch_array($result);
  $currentyear = substr($row['curdate'],0,4);

  echo '<title>Cadence de vente ' .  $_POST['year'] . '</title>';
  echo '</HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>';
  echo '<h1>Cadence de vente ' .  $_POST['year'] . '</h1>';

  $query34 = 'select currentstock,productid,numberperunit,netweightlabel,productname,productfamilyname,productfamilygroupname,productdepartmentname,unittypename from product,productfamily,productfamilygroup,productdepartment,unittype where product.unittypeid=unittype.unittypeid and product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and productfamilygroup.productdepartmentid=productdepartment.productdepartmentid and product.discontinued=0';
  if ($_POST['productid'] != "") { $query34 = $query34 . ' and productid="' . $_POST['productid'] . '"'; }
  if ($_POST['supplierid'] != "") { $query34 = $query34 . ' and supplierid="' . $_POST['supplierid'] . '"'; }
  if ($_POST['productfamilygroupid'] != 0) { $query34 = $query34 . ' and productfamily.productfamilygroupid="' . $_POST['productfamilygroupid'] . '"'; }
  if ($_POST['productdepartmentid'] != 0) { $query34 = $query34 . ' and productfamilygroup.productdepartmentid="' . $_POST['productdepartmentid'] . '"'; }
  if ($_POST['nonestle'] == 1) { $query34 = $query34 . ' and supplierid<>"' . $_POST['supplierid'] . '"'; }
  $query34 = $query34 . ' order by departmentrank,familygrouprank,familyrank';

  if ($_POST['supplierid'] != "")
  {
    $query = 'select suppliername from supplier where supplierid="' . $_POST['supplierid'] . '"';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $row = mysql_fetch_array($result);
    echo '<p>Fournisseur: ' . $_POST['supplierid'] . ' ' . $row['suppliername'] . '</p>';
  }

  $result34 = mysql_query($query34, $db_conn); querycheck($result34);
  $num_results34 = mysql_num_rows($result34);
  $thisonewasshown = 0;

  for ($xyz=1;$xyz <= $num_results34; $xyz++)
  {
    $endyearstock = 0;
    $oversold = 0;
    $currentstock = 0;
    for ($i=1;$i <= 12; $i++)
    {
      $sales[$i] = 0;
      $destock[$i] = 0;
      $purchase[$i] = 0;
      $loss[$i] = 0;
      $loss2[$i] = 0;
      $netchange[$i] = 0;
    }

    $row34 = mysql_fetch_array($result34);
    $testcurrentstock = $row34['currentstock'];
    if ($xyz !=1 && $thisonewasshown == 1) { echo '<tr><td colspan=18>&nbsp;</td></tr>'; echo '<tr><td colspan=18>&nbsp;</td></tr>'; }
    $productid = $row34['productid'];
    $numberperunit = $row34['numberperunit']; if ($numberperunit == 0) { $numberperunit = 1; }
    $cond = $row34['numberperunit'] . ' x ' . $row34['netweightlabel'];
    $unittypename = $row34['unittypename'];
    $productname = $row34['productname'];
    $productfamilyname = $row34['productfamilyname'];
    $productfamilygroupname = $row34['productfamilygroupname'];
    $productdepartmentname = $row34['productdepartmentname'];
    $currentstock = $row34['currentstock'];

    if (($productdepartmentname != $lastpdn || $productfamilygroupname != $lastpfgn || $productfamilyname != $lastpfn) && $xyz > 1) { echo '</table>'; }
    if ($xyz == 1 || $productdepartmentname != $lastpdn) { echo '<h2>Département ' . $productdepartmentname . '</h2>'; }

    if ($xyz == 1 || $productfamilygroupname != $lastpfgn) { echo '<h3>Famille ' . $productfamilygroupname . '</h3>'; }
    if ($xyz == 1 || $productfamilyname != $lastpfn) { echo '<h4>Classe ' . $productfamilyname . '</h4>'; }

    if ($xyz == 1 || $productdepartmentname != $lastpdn || $productfamilygroupname != $lastpfgn || $productfamilyname != $lastpfn) { echo '<table class="report">'; }

    $lastpdn = $productdepartmentname;
    $lastpfgn = $productfamilygroupname;
    $lastpfn = $productfamilyname;

  ### start single product report ###
#mysleep();
$debug = $_POST['debug']+0;

  $query = 'select stock from endofyearstock where year="' . $lastyear . '" and productid="' . $productid . '"';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $row = mysql_fetch_array($result);
  $endyearstock = floor($row['stock'] / $numberperunit);
  $endyearstockunits = $row['stock'] % $numberperunit;

  if ($showvalue)
  {
    $query87 = 'select cost from purchasebatch where productid=' . $productid . ' order by arrivaldate desc LIMIT 1';
    $result87 = mysql_query($query87, $db_conn); querycheck($result87);
    $row87 = mysql_fetch_array($result87);
    $cartonvalue = $row87['cost'] * $numberperunit;
    $endyearstock = $endyearstock * $cartonvalue;
  }

  for ($i=1;$i <= 12; $i++)
  {
    $salesunits[$i] = 0;
    $destockunits[$i] = 0;
    $purchaseunits[$i] = 0;
    $netchangeunits[$i] = 0;
    $lossunits[$i] = 0;
    $lossunits2[$i] = 0;
  }
  $highestmonth = 1;


$query = 'select sum(quantity) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and isreturn=0 and isnotice=0 and cancelledid=0 and invoiceitem.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $_POST['year'] . '" group by month';
$result = mysql_query($query, $db_conn); querycheck($result);
$num_results = mysql_num_rows($result);
for ($i=1;$i <= $num_results; $i++)
{
  $row = mysql_fetch_array($result);
  $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
  $sales[$kladd] = floor($row['sales'] / $numberperunit); $testcartonspermonth = $testcartonspermonth + $sales[$kladd];
  $salesunits[$kladd] = $row['sales'] % $numberperunit;
}
if ($debug)
{
  echo $query . '<br>';
  for ($i=1;$i <= 12; $i++)
  {
    if ($sales[$i] > 0) { echo 'vente(' . $i . ')= ' . $sales[$i] . '<br>'; }
  }
  echo '<br>';
}
$query = 'select sum(quantity) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and isreturn=0 and isnotice=0 and cancelledid=0 and invoiceitemhistory.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $_POST['year'] . '" group by month';
$result = mysql_query($query, $db_conn); querycheck($result);
$num_results = mysql_num_rows($result);
for ($i=1;$i <= $num_results; $i++)
{
  $row = mysql_fetch_array($result);
  $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
  $sales[$kladd] = $sales[$kladd] + floor($row['sales'] / $numberperunit); $testcartonspermonth = $testcartonspermonth + floor($row['sales'] / $numberperunit);
  $salesunits[$kladd] = $salesunits[$kladd] + $row['sales'] % $numberperunit;
}
if ($debug)
{
  echo $query . '<br>';
  for ($i=1;$i <= 12; $i++)
  {
    if ($sales[$i] > 0) { echo 'vente(' . $i . ')= ' . $sales[$i] . '<br>'; }
  }
  echo '<br>';
}



$query = 'select sum(quantity) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and isreturn=0 and isnotice <> 0 and cancelledid=0 and invoiceitem.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $_POST['year'] . '" group by month';
$result = mysql_query($query, $db_conn); querycheck($result);
$num_results = mysql_num_rows($result);
for ($i=1;$i <= $num_results; $i++)
{
  $row = mysql_fetch_array($result);
  $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
  $destock[$kladd] = floor($row['sales'] / $numberperunit); $testcartonspermonth = $testcartonspermonth + $destock[$kladd];
  $destockunits[$kladd] = $row['sales'] % $numberperunit;
}
$query = 'select sum(quantity) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and isreturn=0 and isnotice <> 0 and cancelledid=0 and invoiceitemhistory.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $_POST['year'] . '" group by month';
$result = mysql_query($query, $db_conn); querycheck($result);
$num_results = mysql_num_rows($result);
for ($i=1;$i <= $num_results; $i++)
{
  $row = mysql_fetch_array($result);
  $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
  $destock[$kladd] = $destock[$kladd] + floor($row['sales'] / $numberperunit); $testcartonspermonth = $testcartonspermonth + floor($row['sales'] / $numberperunit);
  $destockunits[$kladd] = $destockunits[$kladd] + $row['sales'] % $numberperunit;
}
if ($debug)
{
  for ($i=1;$i <= 12; $i++)
  {
    if ($destock[$i] > 0) { echo 'BdL(' . $i . ')= ' . $destock[$i] . '<br>'; }
  }
}

##########

/* 
  $query = 'select sum(quantity) as loss,DATE_FORMAT(returndate,"%c") as month from return where productid="' . $productid . '" and isnotice=0 and cancelledid=0 and DATE_FORMAT(returndate,"%Y")="' . $_POST['year'] . '" group by month';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $num_results = mysql_num_rows($result);
  for ($i=1;$i <= $num_results; $i++)
  {
    $row = mysql_fetch_array($result);
    $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
    $loss[$kladd] = floor($row['loss'] / $numberperunit);
    $lossunits[$kladd] = $row['loss'] % $numberperunit;
  }
*/
# retour avoir
$query = 'select sum(quantity) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and isreturn=1 and isnotice=0 and cancelledid=0 and invoiceitem.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $_POST['year'] . '" group by month';
$result = mysql_query($query, $db_conn); querycheck($result);
$num_results = mysql_num_rows($result);
for ($i=1;$i <= $num_results; $i++)
{
  $row = mysql_fetch_array($result);
  $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
  $loss[$kladd] = floor($row['sales'] / $numberperunit);
  $lossunits[$kladd] = $row['sales'] % $numberperunit;
}
$query = 'select sum(quantity) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and isreturn=1 and isnotice=0 and cancelledid=0 and invoiceitemhistory.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $_POST['year'] . '" group by month';
$result = mysql_query($query, $db_conn); querycheck($result);
$num_results = mysql_num_rows($result);
for ($i=1;$i <= $num_results; $i++)
{
  $row = mysql_fetch_array($result);
  $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
  $loss[$kladd] += floor($row['sales'] / $numberperunit);
  $lossunits[$kladd] += $row['sales'] % $numberperunit;
}
/*
  $query = 'select sum(quantity) as loss,DATE_FORMAT(returndate,"%c") as month from return where productid="' . $productid . '" and isnotice>0 and cancelledid=0 and DATE_FORMAT(returndate,"%Y")="' . $_POST['year'] . '" group by month';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $num_results = mysql_num_rows($result);
  for ($i=1;$i <= $num_results; $i++)
  {
    $row = mysql_fetch_array($result);
    $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
    $loss2[$kladd] = floor($row['loss'] / $numberperunit);
    $lossunits2[$kladd] = $row['loss'] % $numberperunit;
  }
*/
# retour avoir BdL
$query = 'select sum(quantity) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and isreturn=1 and isnotice=1 and cancelledid=0 and invoiceitem.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $_POST['year'] . '" group by month';
$result = mysql_query($query, $db_conn); querycheck($result);
$num_results = mysql_num_rows($result);
for ($i=1;$i <= $num_results; $i++)
{
  $row = mysql_fetch_array($result);
  $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
  $loss2[$kladd] = floor($row['sales'] / $numberperunit);
  $lossunits2[$kladd] = $row['sales'] % $numberperunit;
}
$query = 'select sum(quantity) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and isreturn=1 and isnotice=1 and cancelledid=0 and invoiceitemhistory.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $_POST['year'] . '" group by month';
$result = mysql_query($query, $db_conn); querycheck($result);
$num_results = mysql_num_rows($result);
for ($i=1;$i <= $num_results; $i++)
{
  $row = mysql_fetch_array($result);
  $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
  $loss2[$kladd] += floor($row['sales'] / $numberperunit);
  $lossunits2[$kladd] += $row['sales'] % $numberperunit;
}

### BL DESTOCKAGE PERTE ###
  $query = 'select sum(quantity) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and isnotice <> 0 and cancelledid=0 and invoiceitem.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $_POST['year'] . '" and reference="BL DESTOCKAGE PERTE" group by month';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $num_results = mysql_num_rows($result);
  for ($i=1;$i <= $num_results; $i++)
  {
    $row = mysql_fetch_array($result);
    $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
    $destock[$kladd] = $destock[$kladd] - floor($row['sales'] / $numberperunit); $testcartonspermonth = $testcartonspermonth - $destock[$kladd];
    $destockunits[$kladd] = $destockunits[$kladd] - ($row['sales'] % $numberperunit);
    $loss2[$kladd] = $loss2[$kladd] + $destock[$kladd] - floor($row['sales'] / $numberperunit);
    $loss2units[$kladd] = $loss2units[$kladd] - ($row['sales'] % $numberperunit);
  }
  $query = 'select sum(quantity) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and isnotice <> 0 and cancelledid=0 and invoiceitemhistory.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $_POST['year'] . '" and reference="BL DESTOCKAGE PERTE" group by month';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $num_results = mysql_num_rows($result);
  for ($i=1;$i <= $num_results; $i++)
  {
    $row = mysql_fetch_array($result);
    $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
    $destock[$kladd] = $destock[$kladd] - floor($row['sales'] / $numberperunit); $testcartonspermonth = $testcartonspermonth - $destock[$kladd];
    $destockunits[$kladd] = $destockunits[$kladd] - ($row['sales'] % $numberperunit);
    $loss2[$kladd] = $loss2[$kladd] + $destock[$kladd] - floor($row['sales'] / $numberperunit);
    $loss2units[$kladd] = $loss2units[$kladd] - ($row['sales'] % $numberperunit);
  }


  if ($showvalue)
  {
    for ($kladd=1;$kladd <= 12; $kladd++)
    {
      $sales[$kladd] = $sales[$kladd] * $cartonvalue;
      $destock[$kladd] = $destock[$kladd] * $cartonvalue;
      $purchase[$kladd] = $purchase[$kladd] * $cartonvalue;
      $loss[$kladd] = $loss[$kladd] * $cartonvalue;
      $loss2[$kladd] = $loss2[$kladd] * $cartonvalue;
      $netchange[$kladd] = $netchange[$kladd] * $cartonvalue;
    }
  }

 $totalexit = 0;
 for ($i=1;$i <= 12; $i++)
 {
   $totalexit = $totalexit + $sales[$i];
   $totalexit = $totalexit + $destock[$i];
 }
 $totalexit = round($stockmonths * ($totalexit / $highestmonth));

  $testcartonspermonth = $testcartonspermonth / $highestmonth;
  $thisonewasshown = 0;
#echo '<br>testing $cartonspermonth (' . $cartonspermonth . ') > $testcartonspermonth (' . $testcartonspermonth . ')<br>';
#echo '<br>testing $testcurrentstock (' . $testcurrentstock . ') < $totalexit (' . $totalexit . ')<br>';
#  if ($testcurrentstock < $totalexit && $cartonspermonth > $testcartonspermonth)
# Not sure what $totalexit is for
  if ($cartonspermonth > $testcartonspermonth)
  {
  ### start stockmonths
  $thisonewasshown = 1;

  echo '<tr><td><b>Produit ' . $productid . '</b></td><td>&nbsp;</td><td><b>Jan</b></td><td><b>Fev</b></td><td><b>Mars</b></td><td><b>Avril</b></td><td><b>Mai</b></td><td><b>Juin</b></td><td><b>Juil</b></td><td><b>Aout</b></td><td><b>Sept</b></td><td><b>Oct</b></td><td><b>Nov</b></td><td><b>Dec</b></td><td>&nbsp;</td><td><b>Total</b></td><td><b>Moyenne</b></td></tr>';
  $result = $endyearstock; $totalsales = 0; $totalloss = 0; $totalloss2 = 0; $totalpurchase = 0; $totaldestock = 0; $totalnetchange = 0; $counter = 0;
  $resultunits = $endyearstockunits;
  for ($i=1;$i <= 12; $i++)
  {
    if ($sales[$i] == 0) { $sales[$i] = '&nbsp;'; }
    if ($loss[$i] == 0) { $loss[$i] = '&nbsp;'; }
    if ($destock[$i] == 0) { $destock[$i] = '&nbsp;'; }
    if ($loss2[$i] == 0) { $loss2[$i] = '&nbsp;'; }
    if ($purchase[$i] == 0) { $purchase[$i] = '&nbsp;'; }
    if ($netchange[$i] == 0) { $netchange[$i] = '&nbsp;'; }
    if ($salesunits[$i] == 0 && $sales[$i] == 0) { $salesunits[$i] = ''; }
    if ($lossunits[$i] == 0 && $loss[$i] == 0) { $lossunits[$i] = ''; }
    if ($destockunits[$i] == 0 && $destock[$i] == 0) { $destockunits[$i] = ''; }
    if ($loss2units[$i] == 0 && $loss2[$i] == 0) { $loss2units[$i] = ''; }
    if ($purchaseunits[$i] == 0 && $purchase[$i] == 0) { $purchaseunits[$i] = ''; }
    if ($netchangeunits[$i] == 0 && $netchange[$i] == 0) { $netchangeunits[$i] = ''; }
    if ($i == 1) { echo '<tr><td valign=top>' . $productname . '<br>' . $cond . '<br><br>' . $productdepartmentname . '<br>' . $productfamilygroupname . '<br>' . $productfamilyname . '</td><td>Vente<br>&nbsp;Avoir<br>BdL<br>&nbsp;Avoir<br>&nbsp;<br>&nbsp;</td>'; }
    echo '<td align=right>&nbsp;' . $sales[$i];
    if ($su) { echo ' <font size=-2>' . $salesunits[$i] . '</font>'; }
    echo '<br>&nbsp;' . $loss[$i];
    if ($su) { echo ' <font size=-2>' . $lossunits[$i] . '</font>'; }
    echo '<br>&nbsp;' . $destock[$i];
    if ($su) { echo ' <font size=-2>' . $destockunits[$i] . '</font>'; }
    echo '<br>&nbsp;' . $loss2[$i];
    if ($su) { echo ' <font size=-2>' . $loss2units[$i] . '</font>'; }
    echo '<br>&nbsp;<br>&nbsp;';
    $result = $sales[$i] + $destock[$i] - $loss[$i] - $loss2[$i];
    $resultunits = $salesunits[$i] + $destockunits[$i] - $lossunits[$i] - $loss2units[$i];
#echo '<br>result=' . $result . ' resultunits=' . $resultunits;
    $kladdresult = ($result * $numberperunit) + $resultunits;
    $result = floor($kladdresult / $numberperunit);
    $resultunits = $kladdresult % $numberperunit; if ($result < 0 && $resultunits <> 0) { $result = $result + 1; }
    $monthresult[$i] = $result;
    $monthresultunits[$i] = $resultunits;
#echo ' . . . . result=' . $result . ' resultunits=' . $resultunits;
    $totalsales = $totalsales + ($sales[$i] * $numberperunit) + $salesunits[$i];
    $totaldestock = $totaldestock + ($destock[$i] * $numberperunit) + $destockunits[$i];
    $totalloss = $totalloss + ($loss[$i] * $numberperunit) + $lossunits[$i];
    $totalloss2 = $totalloss2 + ($loss2[$i] * $numberperunit) + $loss2units[$i];
    $totalpurchase = $totalpurchase + ($purchase[$i] * $numberperunit) + $purchaseunits[$i];
    $totalnetchange = $totalnetchange + ($netchange[$i] * $numberperunit) + $netchangeunits[$i];
    if ($sales[$i] > 0 || $destock[$i] > 0 || $loss[$i] > 0 || $purchase[$i] > 0) { $counter = $i; }
    if ($counter == 0) { $counter = 1; }
    if ($i == 12)
    {
      echo '<td>Vente<br>&nbsp;Avoir<br>BdL<br>&nbsp;Avoir<br>&nbsp;<br>&nbsp;</td><td align=right><b>&nbsp;' . round($totalsales / $numberperunit);
      echo '<br>&nbsp;' . round($totalloss / $numberperunit);
      echo '<br>&nbsp;' . round($totaldestock / $numberperunit);
      echo '<br>&nbsp;' . round($totalloss2 / $numberperunit);
      echo '<br>&nbsp;';
      echo '<br>&nbsp;';
      echo '</b></td><td align=right><b>&nbsp;' . round($totalsales/$counter/$numberperunit);
      echo '<br>&nbsp;' . round($totalloss/$counter/$numberperunit);
      echo '<br>&nbsp;' . round($totaldestock/$counter/$numberperunit);
      echo '<br>&nbsp;' . round($totalloss2/$counter/$numberperunit);
      echo '<br>&nbsp;';
      echo '<br>&nbsp;';
      echo '</b></td></tr>';
    }
  }
  echo '<tr><td><b>Stock: ' . $currentstock;
  echo '</b></td><td><b>Total</b></td>';

  for ($i=1;$i <= 12; $i++)
  {
    $counter=12; # IMPORTANT SET COUNTER TO 12 SO WE POPULATE FULL YEAR
    if ($counter >= $i)
    {
      echo '<td align=right><b>' . $monthresult[$i];
      if ($su) { echo ' <font size=-2>' . $monthresultunits[$i] . '</font>'; }
      echo '</b></td>';
    }
    else { echo '<td>&nbsp;</td>'; }
  }
  $supertotal = 0;
  echo '<td><b>Total</b></td><td>&nbsp;</td><td>&nbsp;</td>';


  }
  ### end stockmonths

  ### end single product report ###

  }
  echo '</table>';
  break;

  
  
  
  
  ### Product catalogue ###
  case 'prodcat':
  $showunittype = 1;
  $showweight = 1;
  $lastyear = (substr($_SESSION['ds_curdate'],0,4)-1);
  $showpromotext = (int) $_POST['showpromotext'];
  $showean = (int) $_POST['showean'];
  $showbarcodes = (int) $_POST['showbarcodes'];
  if ($showean == 0) { $showbarcodes = 0; }
  require('preload/unittype.php');
  $mycat = "Tout"; $salesrep = 0; $islandsalesrep = 0;
  if ($_POST['salesrep'] == 1) { $salesrep = 1; }
  if ($_POST['islandsalesrep'] == 1) { $salesrep = 1; $islandsalesrep = 1; }
  $query = 'select volume,weight,promotext,unittypeid,commissionrateid,supplierid,currentstock,product.productid,eancode,eancode2
  ,curdate() as curdate,creationdate,taxcodeid,productid,productname,salesprice,detailsalesprice,islandregulatedprice
  ,retailprice,numberperunit,netweightlabel,productfamilyname,productfamilygroupname,productdepartmentname,producttypename';
  if ($_POST['mycat'] == 7) { $query = $query . ',suppliercode'; }
  $query = $query . ' from product,productfamily,productfamilygroup,productdepartment,producttype where product.producttypeid=producttype.producttypeid and product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and productfamilygroup.productdepartmentid=productdepartment.productdepartmentid';
  if ($_POST['mycat'] == 0) { $query = $query . ' and discontinued=0'; }
  if ($_POST['mycat'] == 1) { $query = $query . ' and discontinued=0 and supplierid = 4126 and productdepartment.productdepartmentid=3'; $mycat = "Nestlé surgelé"; }
  if ($_POST['mycat'] == 2) { $query = $query . ' and discontinued=0 and productfamilygroup.productfamilygroupid=25 and product.productfamilyid<>48'; $mycat = "Petfood"; }
  if ($_POST['mycat'] == 3) { $query = $query . ' and discontinued=0 and (productdepartment.productdepartmentid <= 7 || productdepartment.productdepartmentid=11)'; $mycat = "Standard"; } # and productdepartment.productdepartmentid <> 6
  if ($_POST['mycat'] == 4) { $query = $query . ' and discontinued=0 and productdepartment.productdepartmentid <= 7'; $mycat = "Food Service"; }
  if ($_POST['mycat'] == 5 || $_POST['mycat'] == 55) { $query = $query . ' and discontinued=0 and supplierid <> 4126'; $mycat = "Wing Chong"; }
  if ($_POST['mycat'] == 6) { $query = $query . ' and discontinued=0 and productdepartment.productdepartmentid <> 6 and supplierid = 4126'; $mycat = "Nestlé"; }
  if ($_POST['mycat'] == 7) { $query = $query . ' and discontinued=0 and supplierid = 4126'; $mycat = "Nestlé avec codes fournisseur"; }
  if ($_POST['mycat'] == 8) { $query = $query . ' and notforsale=1'; $mycat = "Produits non mis à la vente"; }
  if ($_POST['mycat'] == 9) { $query = $query . ' and notforsale=0'; $mycat = "Produits en rupture de stock (not sure if this is working)"; }
  if ($_POST['mycat'] == 10) { $query = $query . ' and discontinued=1'; $mycat = "Produits discontinué"; }
  if ($_POST['mycat'] == 11) { $query = $query . ' and (commissionrateid=1 or commissionrateid=2)'; $mycat = "Produits hors commission ou 0.25%"; }
  $query = $query . ' order by departmentrank,productdepartmentname,familygrouprank,productfamilygroupname,familyrank,productfamilyname,productname';
  $query_prm = array('dauphin');
  require ('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  
  $title = 'Catalogue de produits - ' . $mycat;
  if ($salesrep == 1) { $title .= ' Commercial'; }
  if ($islandsalesrep == 1) { $title .= ' Archipel TMA'; }
  showtitle($title);
  echo '<h1>',$title,'</h1>';
  
  for ($i=1; $i <= $num_results_main; $i++)
  {
    $row = $main_result[$i-1];
    $specialchar = "&nbsp;";
    if ($row['producttypename'] == "PPN") { $specialchar = "+"; }
    if ($row['producttypename'] == "PGL") { $specialchar = "*"; }
    if ($row['producttypename'] == "PGC") { $specialchar = "#"; }
    $currentyear = substr($row['curdate'],0,4);
    if ($i != 1 && $lastpfn != $row['productfamilyname']) { echo '</table>'; }
    if ($i == 1 || $lastpdn != $row['productdepartmentname']) { echo '<h2>Département ' . $row['productdepartmentname'] . '</h2>'; }
    if ($i == 1 || $lastpfgn != $row['productfamilygroupname']) { echo '<h3>Famille ' . $row['productfamilygroupname'] . '</h3>'; }
    if ($i == 1 || $lastpfn != $row['productfamilyname'])
    {
      echo '<h4>Classe ' . $row['productfamilyname'] . '</h4>';
      echo '<table class="report"><tr><td><b>Produit</b></td><td><b>Numéro</b></td>';
      if ($showunittype) { echo '<td><b>'; } # title?
      if ($_POST['mycat'] == 7) { echo '<td><b>Code Nestlé</b></td>'; }
      echo '<td><b>Conditionnement</b></td>';
      if ($showweight) { echo '<td><b>Poids'; }
      if ($_POST['mycat'] == 55) { echo '<td><b>Volume'; }
      echo '<td><b>Categorie</b></td><td><b>Stock</b></td>';
      if ($_POST['mycat'] != 4)
      {
        if ($salesrep != 1) { echo '<td><b>Prix G</b></td><td><b>Prix SG</b></td>'; }
        if ($salesrep == 1 && $islandsalesrep != 1) { echo '<td><b>Prix G</b></td><td><b>Prix G Unitaire</b></td>'; }
        if ($salesrep == 1 && $islandsalesrep == 1) { echo '<td><b>Prix G Tahiti</b></td><td><b>Prix G Tahiti Unitaire</b></td>'; }
        if ($salesrep != 1) { echo '<td><b>Prix Iles</b></td>'; }
        if ($islandsalesrep == 1) { echo '<td><b>Prix Iles</b></td><td><b>Prix Iles Unitaire</b></td>'; }
        echo '<td><b>Prix Detail</b></td>';
      }
      else
      {
        echo '<td><b>Prix FS</b></td><td><b>Prix FS Unitaire</b></td>';
      }
      if ($_POST['mycat'] == 10) { echo '<td><b>Date entré</td>'; }
      if ($showean)
      {
        echo '<td><b>EAN unité';
        if ($showbarcodes) { echo '<td>'; }
        echo '<td><b>EAN carton';
        if ($showbarcodes) { echo '<td>'; }
      }
      if ($showpromotext) { echo '<td><b>Promo</td>'; }
      echo '<td><b>TVA</td><td>&nbsp;</td>';
      if ($_POST['mycat'] == 55)
      {
        echo '<td><b>P Gros</td><td><b>P Reviens</td><td><b>Margin</td>';
      }
      if ($_POST['mycat'] == 11)
      {
        echo '<td><b>Comm/Fourn</b></td>';
      }
      echo '</tr>';
    }
    $npu = $row['numberperunit']; if ($npu == 0) { $npu = 1; }
    $stock = $row['currentstock'];

    if (isset($_POST['lastyearstock']) && $_POST['lastyearstock'] == 1)
    {
      $productid = $row['productid']; $currentyear = $lastyear; $numberperunit = $npu; $dp_donotupdate = 1;
      require('inc/calcstock.php');
      $stock = $currentstock;
    }

    $dmp = $unittype_dmpA[$row['unittypeid']];
    $stock /= $dmp;
    $row['salesprice'] *= $dmp;
    $row['islandregulatedprice'] *= $dmp;
    $row['retailprice'] *= $dmp;
    $row['detailsalesprice'] *= $dmp;

    if ($_POST['mycat'] != 4)
    {
      if ($_POST['mycat'] != 9 || $stock <= 0) { echo '<tr><td>' . d_decode($row['productname']) . '</td><td align=right>' . $row['productid'] . '</td>'; }
      if ($_POST['mycat'] == 7)
      {
        $suppliercode = $row['suppliercode'];
        if ($suppliercode == "") { $suppliercode = '&nbsp;'; }
        echo '<td align=right>' . $suppliercode . '</td>';
      }
      if (($_POST['mycat'] != 9  && $salesrep != 1) || ($stock == 0 && $salesrep != 1))
      {
        if ($showunittype) { echo '<td>',$unittypeA[$row['unittypeid']]; }
        echo '<td align=right>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td>';
        if ($showweight) { echo '<td align=right>',$row['weight']/1000; }
        if ($_POST['mycat'] == 55) { echo '<td align=right>',$row['volume']; }
        echo '<td>' . $row['producttypename'] . '</td><td align=right>' . floor($stock) . '</td><td align=right>' . myfix($row['salesprice']) . '</td><td align=right>' . myfix($row['detailsalesprice']) . '</td><td align=right>' . myfix($row['islandregulatedprice']) . '</td><td align=right>' . myfix($row['retailprice'] / $row['numberperunit']) . '</td>';
      }
      if ($salesrep == 1 && $islandsalesrep != 1)
      {
        if ($showunittype) { echo '<td>',$unittypeA[$row['unittypeid']]; }
        echo '<td align=right>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td>';
        if ($showweight) { echo '<td align=right>',$row['weight']/1000; }
        if ($_POST['mycat'] == 55) { echo '<td align=right>',$row['volume']; }
        echo '<td>' . $row['producttypename'] . '</td><td align=right>' . floor($stock) . '</td><td align=right>' . myfix($row['salesprice']) . '</td><td align=right>' . myfix($row['salesprice'] / $row['numberperunit']) . '</td><td align=right>' . myfix($row['retailprice'] / $row['numberperunit']) . '</td>';
      }
      if ($islandsalesrep == 1)
      {
        if ($showunittype) { echo '<td>',$unittypeA[$row['unittypeid']]; }
        echo '<td align=right>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td>';
        if ($showweight) { echo '<td align=right>',$row['weight']/1000; }
        if ($_POST['mycat'] == 55) { echo '<td align=right>',$row['volume']; }
        echo '<td>' . $row['producttypename'] . '</td><td align=right>' . floor($stock) . '</td><td align=right>' . myfix($row['salesprice']) . '</td><td align=right>' . myfix($row['salesprice'] / $row['numberperunit']) . '</td><td align=right>' . myfix($row['islandregulatedprice']) . '</td><td align=right>' . myfix($row['islandregulatedprice'] / $row['numberperunit']) . '</td><td align=right>' . myfix($row['retailprice'] / $row['numberperunit']) . '</td>';
      }
    }
    else
    {
      echo '<tr><td>' . d_decode($row['productname']) . '</td><td align=right>' . $row['productid'] . '</td>';
      if ($showunittype) { echo '<td>',$unittypeA[$row['unittypeid']]; }
      echo '<td align=right>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td>';
      if ($showweight) { echo '<td align=right>',$row['weight']/1000; }
      echo '<td>' . $row['producttypename'] . '</td><td align=right>' . floor($stock) . '</td><td align=right>' . myfix($row['detailsalesprice']) . '</td><td align=right>' . myfix($row['detailsalesprice'] / $row['numberperunit']) . '</td>';
    }
    if ($_POST['mycat'] == 10) { echo '<td>' . datefix($row['creationdate']) . '</td>'; }
    if ($showean)
    {
      $eancode = $row['eancode']; if ($eancode == "") { $eancode = '&nbsp;'; }
      echo '<td>' . $eancode;
      if ($showbarcodes)
      {
        echo '<td>'; #&height=' . $height . '&width=' . $width . '"
        if ($eancode != '&nbsp;') { echo '<img src="barcode.php?size=40&text=' . $eancode . '">'; }
      }
      $eancode2 = $row['eancode2']; if ($eancode2 == "") { $eancode2 = '&nbsp;'; }
      echo '<td>' . $eancode2;
      if ($showbarcodes)
      {
        echo '<td>';
        if ($eancode2 != '&nbsp;') { echo '<img src="barcode.php?size=40&text=' . $eancode2 . '">'; }
      }
    }
    if ($showpromotext)
    {
      echo '<td>' . d_output($row['promotext']) . '</td>';
    }
    if ($_POST['mycat'] != 9 || $stock <= 0)
    { 
      echo '<td>';
      if ($row['taxcodeid'] == 1) { echo '*'; }
      else { echo '&nbsp;'; }
      echo '</td>';
    }
    echo '<td>' . $specialchar . '</td>';
    if ($_POST['mycat'] == 55)
    {
      #$query = 'select cost from purchasebatch where productid="' . $row['productid'] . '" order by arrivaldate desc limit 1';
      #$result2 = mysql_query($query, $db_conn); querycheck($result2);
      #$row2 = mysql_fetch_array($result2);
      
      $query = 'select cost from purchasebatch where productid=? order by arrivaldate desc limit 1';
      $query_prm = array($row['productid']);
      require ('inc/doquery.php');
      
      $kladdkladd = $query_result[0]['cost'] * $row['numberperunit'];
      if ($kladdkladd == 0) { $kladdkladd2 = 0; }
      else { $kladdkladd2 = (($row['salesprice']/$kladdkladd)-1)*100; }
      echo '<td align=right>' . myfix($row['salesprice']) . '</td><td align=right>' . myfix($query_result[0]['cost'] * $row['numberperunit']) . '</td>';
      echo '<td align=right>' . myfix($kladdkladd2, 2) . '%</td>';
    }
    if ($_POST['mycat'] == 11)
    {
      echo '<td>';
      if ($row['commissionrateid'] == 2) { echo '0.25%'; }
      if ($row['supplierid'] == 4126) { echo ' Nestlé'; }
      if ($row['commissionrateid'] != 2 && $row['supplierid'] != 4126) { echo '&nbsp;'; }
      echo '</td>';
    }
    echo '</tr>';
    $lastpdn = $row['productdepartmentname'];
    $lastpfgn = $row['productfamilygroupname'];
    $lastpfn = $row['productfamilyname'];
  }
  echo '</table>';
  echo '<br>+ = PPN<br>* Libre sur Tahiti, Moorea, Raiatea, Huahine, Bora Bora, Taha\'a, Rangiroa, Nuku Hiva, Hiva Oa';
  break;

  
  
  
  ### Prix et produits ###
  case 'prixproduits':
    
    $shipmentid = (int) $_POST['shipmentid'];

    $query = 'select promotext,suppliercode,eancode,product.productid,productname,brand,numberperunit,netweightlabel,unittypename,displaymultiplier,sih,productcomment,salesprice,islandregulatedprice,retailprice,currentstock,taxcode,producttypename from product,unittype,taxcode,producttype,purchasebatch where product.productid=purchasebatch.productid and product.unittypeid=unittype.unittypeid and product.taxcodeid=taxcode.taxcodeid and product.producttypeid=producttype.producttypeid and discontinued=0 and shipmentid="' . $shipmentid . '" order by productname';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);#echo $query . ' '.$num_results;
    echo '<h2>Prix et produits dossier ' . $shipmentid . '</h2>';
    echo '<table class="report"><tr><td class="breakme"><b>Numéro produit</b></td><td><b>Description</b></td><td><b>Marque</b></td><td class="breakme"><b>Unité de vente</b></td><td class="breakme"><b>Conditionnement</b></td><td class="breakme"><b>Prix G HT</b></td><td class="breakme"><b>Prix G TTC</b></td><td class="breakme"><b>Prix GU HT</b></td><td class="breakme"><b>Prix GU TTC</b></td><td class="breakme"><b>Prix GI HT</b></td><td class="breakme"><b>Prix GI TTC</b></td><td class="breakme"><b>Prix GIU HT</b></td><td class="breakme"><b>Prix GIU TTC</b></td><td class="breakme"><b>Prix DU HT</b></td><td class="breakme"><b>Prix DU TTC</b></td>';
    echo '<td><b>Promo</b></td><td><b>Commentaire</b></td><td><b>EAN</b></td><td>&nbsp;</td></tr>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      $specialchar = "";
      #if ($row['producttypename'] == "PPN") { $specialchar = "+"; }
      if ($row['producttypename'] == "PGL") { $explainpgl = 1; }
      #if ($row['producttypename'] == "PGC") { $specialchar = "#"; }
      $dmp = $row['displaymultiplier'];
      echo '<tr><td align=right>' . $row['productid'] . '</td><td class="breakme">' . d_decode($row['productname']) . '</td><td class="breakme">' . $row['brand'] . '</td><td>' . $row['unittypename'] . '</td><td class="breakme" algin=right>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td>
      <td align=right>' . myfix($row['salesprice']*$dmp) . '&nbsp;</td><td align=right>' . myfix($row['salesprice']*$dmp + ($row['salesprice']*$dmp * $row['taxcode']/100)) . '&nbsp;</td>';
      echo '<td align=right>' . myfix($row['salesprice']*$dmp/$row['numberperunit']) . '&nbsp;</td><td align=right>' . myfix(($row['salesprice']*$dmp + ($row['salesprice']*$dmp * $row['taxcode']/100))/$row['numberperunit']) . '&nbsp;</td>';
echo '<td align=right>' . myfix($row['islandregulatedprice']*$dmp) . '&nbsp;</td><td align=right>' . myfix($row['islandregulatedprice']*$dmp + ($row['islandregulatedprice']*$dmp * $row['taxcode']/100)) . '&nbsp;</td>';
echo '<td align=right>' . myfix($row['islandregulatedprice']*$dmp/$row['numberperunit']) . '&nbsp;</td><td align=right>' . myfix(($row['islandregulatedprice']*$dmp + ($row['islandregulatedprice']*$dmp * $row['taxcode']/100))/$row['numberperunit']) . '&nbsp;</td>';
      echo '<td align=right>' . myfix($row['retailprice']*$dmp/$row['numberperunit']) . '&nbsp;</td><td align=right>' . myfix(($row['retailprice']*$dmp + ($row['retailprice']*$dmp * $row['taxcode']/100))/$row['numberperunit']) . '&nbsp;</td>';
      echo '<td class="breakme">' . $row['promotext'] . '</td><td class="breakme">' . $row['productcomment'] . '</td><td>' . $row['eancode'] . '</td><td class="breakme">' . $row['producttypename'] . '</td></tr>';
    }
    if ($num_results == 0) { echo '<tr><td colspan=7>Pas de produit trouvé.</td></tr>'; }
    ?></table><?php
    if ($explainpgl)
    {
      echo '<p>PGL libre sur les îsles de: ';
      $query = 'select islandname from island where outerisland=0 order by islandid';
      $query_prm = array();
      require('inc/doquery.php');
      for ($i=0;$i<$num_results;$i++)
      {
        if ($i != 0) { echo ', '; }
        echo $query_result[$i]['islandname'];
      }
      echo '</p>';
    }
  break;
  
  
  
  
  

  case 'stockwc':
  /*
  for ($i=1;$i<=10000;$i++)
  {
    $query = 'insert into wctemp (productid) values (?)';
    $query_prm = array($i);
    require('inc/doquery.php');
  }
  
  */
  require('preload/unittype.php');
  #$_SESSION['debug']=1; $unittype_dmpA
  $pid1 = $_POST['pid1']+0;
  $pid2 = $_POST['pid2']+0;
  $onlydiff = $_POST['onlydiff']+0;
  $ouryear = $_POST['ouryear']+0;
  $currentyear = $ouryear; $dp_donotupdate = 1;
  /*
  $query = 'select purchasebatch.productid,productname,netweightlabel,numberperunit,amountleft,useby,supplierid from purchasebatch,product where purchasebatch.productid=product.productid and amountleft>0';
  $query = $query . ' and purchasebatchid<37480'; # only migrated batches
  $query = $query . ' order by productid,useby desc';
#$query = $query . ' limit 50';
*/
  $query = 'select unittypeid,product.productid,productname,netweightlabel,numberperunit,supplierid,stock from product,wctemp where product.productid=wctemp.productid and product.productid>=? and product.productid<=?';
#$query .= ' and product.unittypeid=6';  
#$query = $query . ' limit 50';
  $query_prm = array($pid1,$pid2);
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  echo '<h2>Stock système WC Fin ' . $ouryear . '</h2><table class="report">';
echo '<span class=alert>Ce rapport n\'affiche que les produits en stock théorique. Si vous comptez un produit non existant en stock théorique il ne s\'affche pas ici.</span>';
echo '<br><span class=alert>Si code fournisseur=4126 (Nestlé), le stock inventaire est lu directe du "Stock Fin Année", et non de Entrepot/Inventaire.</span>';
#echo '<br><br><span class=alert>Veuiller avertir Svein quand le comptage est terminé, le stock début 2014 serait établi..</span>';
  $lastproductid = -1; $subtotal = 0;
  for ($i=0;$i<$num_results_main;$i++)
  {
    if ($i != 0 && $lastproductid != $main_result[$i]['productid'])
    {
      ### save to DB - DONE
      /*
      $query = 'insert into endofyearstock (productid,year,stock) values (?,2011,?)';
      $query_prm = array($lastproductid,$subtotal);
      require('inc/doquery.php');
      $query = 'insert into endofyearstock (productid,year,stock) values (?,2012,?)';
      $query_prm = array($lastproductid,$subtotal);
      require('inc/doquery.php');
      */
      ###
      if (!$onlydiff){
      echo '<tr><td align=right><b>'.$lastproductid.'</td><td colspan=2><b>Stock théorique Wing Chong</td><td align=right><b>' . floor($subtotal/$lastnpu);
      if ($subtotal%$lastnpu > 0) { echo '<font size=-2>'.$subtotal%$lastnpu.'</font>'; }
      echo '</td></tr>';
      }
      $quan = 0; $quanrest = 0; #echo '<br>lsi='.$lastsupplierid;
      if ($lastsupplierid != 4126)
      {
        $query = 'select barcode,quantity,quantityrest,expiredate,placementid from pallet_counted where productid="' . $lastproductid . '" order by expiredate desc';
        $query_prm = array();
        require('inc/doquery.php');
        for ($y=0;$y<$num_results;$y++)
        {
          if (!$onlydiff){
          echo '<tr><td align=right>'.$lastproductid.'</td><td>'.$lastprodname.'</td><td>' . datefix2($query_result[$y]['expiredate']) . '</td>
          <td align=right>' . $query_result[$y]['quantity'];
          if ($query_result[$y]['quantityrest'] > 0) { echo '<font size=-2>'.$query_result[$y]['quantityrest'].'</font>'; }
          echo '</td></tr>';
          }
          $quan += $query_result[$y]['quantity'];
          $quanrest += $query_result[$y]['quantityrest'];
        }
      }
      else
      {
        # take stock from endofyearstock (Nestlé products, counted by Cindy)
        $query = 'select stock from endofyearstock where year=? and productid=?';
        $query_prm = array($ouryear,$lastproductid);
        require('inc/doquery.php');
        $quan += ($query_result[0]['stock']/$lastnpu);
      }
      if (!$onlydiff){
      echo '<tr><td align=right><b>'.$lastproductid.'</td><td colspan=2><b>Inventaire Fin '.$ouryear.'</td><td align=right><b>' . $quan;
      if ($quanrest > 0) { echo '<font size=-2>'.$quanrest.'</font>'; }
      echo '</td></tr>';
      }
      $diff = (($quan*$lastnpu)+$quanrest) - $subtotal;
      if ($diff > 0) { $oursign = '+'; }
      else { $oursign = '-'; }
      if ($diff != 0)
      {
      /*
### update to endofyear stock ### DONE
if ($lastsupplierid != 4126)
{
  $kladdamount = (($quan*$lastnpu)+$quanrest);
  if ($unittype_dmpA[$main_result[$i]['unittypeid']] > 0) { $kladdamount *= $unittype_dmpA[$main_result[$i]['unittypeid']]; }
  if ($kladdamount > 0)
  {
    $query = 'update endofyearstock set stock=? where year=2013 and productid="'.$lastproductid.'"';
    $query_prm = array($kladdamount);
    require('inc/doquery.php');
    echo '<tr><td>'.$query.'</td></tr>';
    if ($num_results == 0)
    {
      $query = 'insert into endofyearstock (stock,year,productid) values (?,2013,'.$lastproductid.')';
      $query_prm = array($kladdamount);
      require('inc/doquery.php');
      echo '<tr><td>'.$query.'</td></tr>';
    }
  }
}
###
*/
      echo '<tr><td align=right>'.$lastproductid.'</td><td>'.$lastprodname.'</td><td>Ecart</td><td align=right><b>' .$oursign. abs(floor($diff/$lastnpu));
      if (abs($diff%$lastnpu) > 0) { echo '<font size=-2>'.abs($diff%$lastnpu).'</font>'; }
      echo '</td></tr>';
      }
      ###
      if (!$onlydiff){
      echo '<tr><td colspan=4>&nbsp;</td></tr>';
      echo '<tr><td colspan=4>&nbsp;</td></tr>';
      }
      
      $subtotal = 0;
    }
    $lastprodname = d_decode($main_result[$i]['productname']) . ' ';
    if ($main_result[$i]['numberperunit'] > 1) { $lastprodname .= $main_result[$i]['numberperunit'] . ' x '; }
    $lastprodname .= $main_result[$i]['netweightlabel'];
    $numberperunit = $main_result[$i]['numberperunit'];
    $productid = $main_result[$i]['productid'];
    if ($main_result[$i]['stock'] > 0)
    {
      $amountleft = $main_result[$i]['stock'];
      $currentstock = floor($main_result[$i]['stock']/$numberperunit);
      $unitstock = $main_result[$i]['stock']%$numberperunit;
    }
    else
    {
      require('inc/calcstock.php');
      $amountleft = $stock; # currentstock $unitstock
      $query = 'update wctemp set stock=? where productid=?';
      $query_prm = array($stock, $productid);
      require('inc/doquery.php');
    }
    if ($unittype_dmpA[$main_result[$i]['unittypeid']] > 0)
    {
      $amountleft = floor($amountleft / $unittype_dmpA[$main_result[$i]['unittypeid']]);
      $currentstock = 0;
      $unitstock = 0;
    }
    if (!$onlydiff){
    echo '<tr><td align=right>' . $main_result[$i]['productid'] . '</td><td>' . $lastprodname;
    echo '</td><td>&nbsp;</td>
    <td align=right>' . $currentstock;
    if ($unitstock > 0) { echo '<font size=-2>'.$unitstock.'</font>'; }
    echo '</td></tr>';
    }
    $lastproductid = $main_result[$i]['productid']; $subtotal += $amountleft; $lastnpu = $main_result[$i]['numberperunit'];
    $lastsupplierid = $main_result[$i]['supplierid'];
  }
      ### copy from above
      if (!$onlydiff){
      echo '<tr><td align=right><b>'.$lastproductid.'</td><td colspan=2><b>Stock théorique Wing Chong</td><td align=right><b>' . floor($subtotal/$lastnpu);
      if ($subtotal>0) { echo '<font size=-2>'.$subtotal%$lastnpu.'</font>'; }
      echo '</td></tr>';
      }
      $quan = 0; $quanrest = 0; #echo '<br>lsi='.$lastsupplierid;
      if ($lastsupplierid != 4126)
      {
        $query = 'select barcode,quantity,quantityrest,expiredate,placementid from pallet_counted where productid="' . $lastproductid . '" order by expiredate desc';
        $query_prm = array();
        require('inc/doquery.php');
        for ($y=0;$y<$num_results;$y++)
        {
          if (!$onlydiff){
          echo '<tr><td align=right>'.$lastproductid.'</td><td>'.$lastprodname.'</td><td>' . datefix2($query_result[$y]['expiredate']) . '</td>
          <td align=right>' . $query_result[$y]['quantity'];
          if ($query_result[$y]['quantityrest'] > 0) { echo '<font size=-2>'.$query_result[$y]['quantityrest'].'</font>'; }
          echo '</td></tr>';
          }
          $quan += $query_result[$y]['quantity'];
          $quanrest += $query_result[$y]['quantityrest'];
        }
      }
      else
      {
        # take stock from endofyearstock (Nestlé products, counted by Cindy)
        $query = 'select stock from endofyearstock where year=? and productid=?';
        $query_prm = array($ouryear,$lastproductid);
        require('inc/doquery.php');
        $quan += ($query_result[0]['stock']/$lastnpu);
      }
      if (!$onlydiff){
      echo '<tr><td align=right><b>'.$lastproductid.'</td><td colspan=2><b>Inventaire Fin '.$ouryear.'</td><td align=right><b>' . $quan;
      if ($quanrest > 0) { echo '<font size=-2>'.$quanrest.'</font>'; }
      echo '</td></tr>';
      }
      $diff = (($quan*$lastnpu)+$quanrest) - $subtotal;
      if ($diff > 0) { $oursign = '+'; }
      else { $oursign = '-'; }
      if ($diff != 0)
      {
### update to endofyear stock ### DONE
#if ($lastsupplierid != 4126)
#{
#  $query = 'update endofyearstock set stock="'.(($quan*$lastnpu)+$quanrest).'" where year=2012 and productid="'.$lastproductid.'"';
#  $query_prm = array();
#  require('inc/doquery.php');
#  echo '<tr><td>'.$query.'</td></tr>';
#}
### 
      echo '<tr><td align=right>'.$lastproductid.'</td><td>'.$lastprodname.'</td><td>Ecart</td><td align=right><b>' .$oursign. abs(floor($diff/$lastnpu));
      if (abs($diff%$lastnpu) > 0) { echo '<font size=-2>'.abs($diff%$lastnpu).'</font>'; }
      echo '</td></tr>';
      }
      ###
  break;

  case 'cdj':
    require('preload/bank.php');
    $datename = 'cdjdate';
    require('inc/datepickerresult.php');
    $date = $cdjdate;
    unset($total);
    echo '<h3>Caisse du jour ' . datefix($date) . '</h3>';
    for ($x = 1; $x <= 2; $x++)
    {
        $subtotal = 0;
        $query = 'select reimbursement,value,name,paymenttypename,paymenttime,depositbankid as bankid from payment,usertable,paymenttype where payment.userid=usertable.userid and payment.paymenttypeid=paymenttype.paymenttypeid and paymentdate="' . $date . '"';
        if ($x == 1) { $query .= ' and paymenttime < "12:00:00"'; echo '<h4>Matin</h4>'; }
        if ($x == 2) { $query .= ' and paymenttime >= "12:00:00"'; echo '<h4>Après-midi</h4>'; }
        $query .= ' order by name,paymenttypename,bankid,reimbursement';
        $query_prm = array();
        require('inc/doquery.php');
        
        echo '<table class="report">';
        echo '<tr><td><b>Utilisateur</b></td><td><b>Type</b></td><td><b>Dépôt banque</b></td><td><b>Montant</b></td></tr>';
        for ($i=0; $i < $num_results; $i++)
        {
          $row = $query_result[$i];
          
          if ($i != 0 && ($lastname != $row['name'] || $lastpaym != $row['paymenttypename'] || $lastbankid != $row['bankid'])) # || $lastreimb != $row['reimbursement']
          {
            echo '<tr><td>' . $lastname . '</td><td>' . $lastpaym . '</td><td>' . $bankA[$lastbankid] . '</td><td align=right>' . myfix($subtotal) . '</td></tr>';
            $subtotal = 0;
          }
          
          $value = $row['value'];
          if ($row['reimbursement'] == 1) { $value = 0 - $value; }
          $lastname = $row['name']; $lastpaym = $row['paymenttypename']; $lastbankid = $row['bankid']; $lastreimb = $row['reimbursement'];
          $total[$row['paymenttypename']] += $value;
          $subtotal += $value;
        }
        ### copy from above
        echo '<tr><td>' . $lastname . '</td><td>' . $lastpaym . '</td><td>' . $bankA[$lastbankid] . '</td><td align=right>' . myfix($subtotal) . '</td></tr>';
        ###
        echo '</table>';
    }

    echo '<h4>Totaux</h4>'; $gtotal = 0;
    echo '<table class="report">';
    foreach ($total as $key => $value)
    {
      echo '<tr><td><b>' . $key . '</td><td align=right>' . myfix($value) . '</td></tr>';
      $gtotal += $value;
    }
    echo '<tr><td><b>Total</td><td align=right><b>' . myfix($gtotal) . '</td></tr>';
    echo '</table>';
  break;

  
  
  

  case 'nestledaily': #this report is available thorugh clientaccess for Nestlé
  require('preload/unittype.php');

  ### preload employee + custom fornestle (could now be changed)
  $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename,iscashier,issales,deleted,employeecategoryid from employee order by employeename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $employeeid_temp = (int) ($query_result[$kladd_i]['employeeid']+0);
    $employeeA[$employeeid_temp] = $query_result[$kladd_i]['employeename'];
    $employee_iscashierA[$employeeid_temp] = $query_result[$kladd_i]['iscashier'];
    $employee_issalesA[$employeeid_temp] = $query_result[$kladd_i]['issales'];
    $employee_deletedA[$employeeid_temp] = $query_result[$kladd_i]['deleted'];
    $employee_fornestleA[$employeeid_temp] = $query_result[$kladd_i]['employeecategoryid']; #fornestle (catid 1 = nestle) catid 3 = also nestle
  }
  ###

  $sep = '<br>';

  $datename = 'fromdate';
  require('inc/datepickerresult.php');
  $today = $fromdate;
  $datename = 'todate';
  require('inc/datepickerresult.php');
  $enddate = $todate;

  $datetype = 'invoicedate'; $extratext = '(Date saisie';
  if ($_POST['mychoice2'] == 1) { $datetype = 'deliverydate'; $extratext = '(Date livraison'; }
  if ($_POST['mychoice2'] == 3) { $datetype = 'accountingdate'; $extratext = '(Date comptable'; }
  
  ## 2017 05 02 see below
  $query = 'SELECT employeeid FROM `employee` where employeename like "%Aucun%" or employeename like "%Nil%"';
  $query_prm = array();
  require('inc/doquery.php');
  for ($y=0;$y < $num_results; $y++)
  $cA = array();
  {
    array_push ($cA, $query_result[$y]['employeeid']);
  }
  ##

  $query = '';
  $query = $query . 'select invoicehistory.employeeid,basecartonprice,unittypeid,invoicedate,deliverydate,accountingdate,product.productid,productname,quantity,invoiceitemhistory.lineprice as totalprice,givenrebate,client.clientid,clientname,islandname,numberperunit,townname,clientcategoryid';
  $query = $query . ' from product,invoiceitemhistory,invoicehistory,client,town,island';
  $query = $query . ' where invoiceitemhistory.productid=product.productid and invoiceitemhistory.invoiceid=invoicehistory.invoiceid and invoicehistory.clientid=client.clientid and client.townid=town.townid and town.islandid=island.islandid';
  $query = $query . ' and supplierid=4126 and cancelledid=0 and isnotice=0 and isreturn=0 and proforma=0';
  $query = $query . ' and confirmed=1';
  $query = $query . ' and ' . $datetype . '>="' . $today . '" and ' . $datetype . '<="' . $enddate . '"';
#$query .= ' and invoicehistory.invoiceid=424835';
  $query = $query . ' order by employeeid,townname,clientname';

  echo '# ventes nestlé ' . datefix2($today) . ' à ' . datefix2($enddate) . ' ' . $extratext . $sep;
  echo '# vendeur,adresse_geo,client,no client,no produit,produit,cartons,montant,remise,date' . $sep;

  #$result = mysql_query($query, $db_conn); querycheck($result);
  #$num_results = mysql_num_rows($result);
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;

  for ($i=0;$i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    ###
    $gr = $row['givenrebate'];
    $quantity = floor($row['quantity']/$row['numberperunit']);
    if ($gr > 0)
    {
      $bcpdivider = $row['basecartonprice']; if ($bcpdivider == 0) { $bcpdivider = 1; }
      $gr = (100*$gr/$bcpdivider)/$quantity;
      if ($row['totalprice'] == 0) { $gr = '100'; }
      $gr  = myround($gr,0).'%';
    }
    else { $gr = ''; }
    
    ###
    $en = $employeeA[$row['employeeid']];
    $fornestle = $employee_fornestleA[$row['employeeid']];
    $islandname = $row['islandname'];
    $clientname = $row['clientname']; $clientid = $row['clientid']; $clientcategoryid = $row['clientcategoryid'];
    $replaceclientname = 1;
    if ($fornestle == 1 || $fornestle == 3) { $replaceclientname = 0; }
    if ($fornestle == 3)
    {
      # Client FOOD SERVICE = TOUS LES CLIENTS SAUF HYPERMARCHE, LIBRE-SERVICE, MAGASIN, STATION SERVICE
      if ($clientcategoryid >= 26 && $clientcategoryid <= 30) { $replaceclientname = 0; }
      else { $replaceclientname = 1; }
    }
    if ($en == "") { $replaceclientname = 1; }
    if ($replaceclientname)
    {
      $en = 'Comptant'; $islandname = 'Tahiti';
      $clientname = 'PARTICULIER'; $clientid = 97;
    }
    /* 2017 05 02
    For all products sales of supplier 4126, made by sales rep belonging to category "WC",
    please show them as Client generic "FOOD SERVICE" without any client names (is it possible not to assign a client ID?)

    For all products sales of supplier 4126, made by sales rep "AUCUN" or "NIL",
    please show them as Client C97 "CLIENT COMPTANT" without any client names (as usually done today)

    AND For all products sales of supplier 4126,
    made by sales rep belonging to category "NP&WC" AND to customers belonging to "FAMILLE CATEG CLIENT",
    please show them as Client "FOOD SERVICE" without any client names (is it possible not to assign a client ID?)
    
    */
    /*
    # in_array $cA
    if (in_array($row['employeeid'], $cA))
    {
      $clientname = 'CLIENT COMPTANT'; $clientid = 97;
    }
    else
    {
      $clientname = 'FOOD SERVICE'; $clientid = 0;
    }
    */
    ###
    
    echo $en . ',' . $islandname . ',' . $clientname . ',';
    if ($clientid > 0) { echo $clientid; }
    echo ',';
    echo $row['productid'] . ',' . d_decode($row['productname']) . ',' . $quantity . ',' . floor($row['totalprice']) . ',' . $gr;
    echo ',' . $row[$datetype] . $sep;
  }

  break;
  
  case 'sohreport':

  $sep = '<br>';
  
  echo '<style>
  body {
    font-size: medium;
    font-family: monospace;
  }
  </style>
  <span class="normalize">';
  echo '# SOH nestlé ' . datefix2($_SESSION['ds_curdate']) . $sep;
  echo '# code fournisseur,code wing chong,description,stock' . $sep;

  $query = 'select productid,suppliercode,productname,currentstock
  from product
  where supplierid=4126 and notforsale=0 and discontinued=0';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;

  for ($i=0;$i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    if ($row['currentstock'] < 0) { $row['currentstock'] = 0; } # specifically demanded per email
    echo $row['suppliercode'] . ',' . $row['productid'] . ',' . d_decode($row['productname']) . ',' . $row['currentstock'] . $sep;
  }
  echo '</span>';

  break;
  
  case 'stockval':
  $executiondate = $_SESSION['ds_curdate'];
  $postyear = (int) $_POST['year'];
  $postmonth = (int) $_POST['month'];
  
  # 2013 03 11 load monthlystock
  $query = 'select productid,stock from monthlystock where year=? and month=?';
  $query_prm = array($postyear,$postmonth);
  require('inc/doquery.php');
  for ($i=0;$i<$num_results;$i++)
  {
    $monthlystockA[$query_result[$i]['productid']] = $query_result[$i]['stock'];
  }
  
  /*### NEW find last 3 months sales
  $ouraverage = 0;
  for ($y=0;$y <= 2; $y++)
  {
    $month = $postmonth - $y; $year = $postyear;
    if ($month < 1) { $month = $month + 12; $year = $year - 1; }
    if ($month < 10) { $month = '0' . $month; }
    $ourdate = $year . '-' .  $month . '-01';
    $month = $month + 0;

    $query = 'select sum(quantity) as sales from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and cancelledid=0 and year(accountingdate)="' . $year . '"and month(accountingdate)="' . $month . '" and productid="' . $row['productid'] . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $ouraverage = $ouraverage + $query_result[0]['sales'];
  }
  ###*/

  # 2020 11 24 load ouraverageA
  for ($y=0;$y <= 2; $y++)
  {
    $month = $postmonth - $y; $year = $postyear;
    if ($month < 1) { $month = $month + 12; $year = $year - 1; }
    if ($month < 10) { $month = '0' . $month; }
    $ourdate = $year . '-' .  $month . '-01';
    $month = $month + 0;

    $query = 'select invoiceitemhistory.productid,sum(quantity) as sales
    from invoicehistory,invoiceitemhistory,product
    where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
    and invoiceitemhistory.productid=product.productid
    and cancelledid=0 and confirmed=1 and isreturn=0
    and year(accountingdate)=? and month(accountingdate)=?';
    if ($_POST['mychoice'] == 1) { $query .= ' and supplierid=4126'; }
    else { $query .= ' and supplierid<>4126'; }
    $query .= ' group by productid';
    $query_prm = array($year, $month);
    require('inc/doquery.php');
    for ($i=0;$i < $num_results; $i++)
    {
      if (!isset($ouraverageA[$query_result[$i]['productid']])) { $ouraverageA[$query_result[$i]['productid']] = 0; }
      $ouraverageA[$query_result[$i]['productid']] += $query_result[$i]['sales'];
    }
  }

  ### Nestle stock value ###

  $query = 'select product.productid as productid,productname,salesprice,numberperunit,netweightlabel,productfamilyname,productfamilygroupname,productdepartmentname,currentstock';
  $query = $query . ' from product,productfamily,productfamilygroup,productdepartment where product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and productfamilygroup.productdepartmentid=productdepartment.productdepartmentid and discontinued=0';
  if ($_POST['mychoice'] == 1) { $query .= ' and supplierid=4126'; }
  else { $query .= ' and supplierid<>4126'; }
#$query = $query . ' and (productid=1287 or productid=1288 or productid=2363 or productid=2364 or productid=897 or productid=898 or productid=899)';
  $query = $query . ' order by departmentrank,familygrouprank,familyrank,productname';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  $reportstring = '<TITLE>VALORISATION du Stock Produits ' . $postmonth . '/' . $postyear;
  $reportstring = $reportstring . '</TITLE>';
  $reportstring = $reportstring . '</HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>';
  $reportstring = $reportstring . '<h1>VALORISATION du Stock Produits ' . $postmonth . '/' . $postyear;
  $reportstring = $reportstring . '</h1>';
  $totalstock = 0;
  $totalaverage = 0;
  $totaloverstock = 0;
  $subtotal = 0; $valuest = 0; $avgsalesst = 0;
  $t1 = 0; $t2 = 0; $t3 = 0;
  for ($i=1;$i <= $num_results_main; $i++)
  {
    $row = $main_result[($i-1)];
    $amount = floor($monthlystockA[$row['productid']] / $row['numberperunit']);
    if ($amount > 0)
    {
      if ($i != 1 && $lastpfn != $row['productfamilyname'])
      {
        $subtotal = 0; $valuest = 0; $avgsalesst = 0;
        $reportstring = $reportstring . '</table>';
      }
      if ($i == 1 || $lastpdn != $row['productdepartmentname']) { $reportstring = $reportstring . '<h2>Département ' . $row['productdepartmentname'] . '</h2>'; }
      if ($i == 1 || $lastpfgn != $row['productfamilygroupname']) { $reportstring = $reportstring . '<h3>Famille ' . $row['productfamilygroupname'] . '</h3>'; }
      if ($i == 1 || $lastpfn != $row['productfamilyname'])
      {
        $reportstring = $reportstring . '<h4>Classe ' . $row['productfamilyname'] . '</h4>';
        $reportstring = $reportstring . '<table class="report"><tr><td><b>Produit</b></td><td><b>Numéro</b></td>';
        $reportstring = $reportstring . '<td><b>Conditionnement</b></td><td><b>Stock</b></td><td><b>Prix G</b></td><td><b>Valeur</b></td><td><b>3 mois vente</b></td><td><b>Surstock</b></td><td><b>Coeff Stock</b></td></tr>';
      }
      $reportstring = $reportstring . '<tr><td>' . d_decode($row['productname']) . '</td><td align=right>' . $row['productid'] . '</td>';

      /*### NEW find last 3 months sales
      $ouraverage = 0;
      for ($y=0;$y <= 2; $y++)
      {
        $month = $postmonth - $y; $year = $postyear;
        if ($month < 1) { $month = $month + 12; $year = $year - 1; }
        if ($month < 10) { $month = '0' . $month; }
        $ourdate = $year . '-' .  $month . '-01';
        $month = $month + 0;

        $query = 'select sum(quantity) as sales from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and cancelledid=0 and year(accountingdate)="' . $year . '"and month(accountingdate)="' . $month . '" and productid="' . $row['productid'] . '"';
        $query_prm = array();
        require('inc/doquery.php');
        $ouraverage = $ouraverage + $query_result[0]['sales'];
      }
      if ($postyear == 2013 && $postmonth == 1) { $ouraverage *= 3; }
      if ($postyear == 2013 && $postmonth == 2) { $ouraverage *= 3/2; }

      $ouraverage = ($ouraverage * $row['salesprice'])/$row['numberperunit'];
      $avgsalesst = $avgsalesst + $ouraverage;
      ###*/
      
      if (isset($ouraverageA[$row['productid']])) { $ouraverage = $ouraverageA[$row['productid']]; }
      else { $ouraverage = 0; }
      $ouraverage = ($ouraverage * $row['salesprice'])/$row['numberperunit'];

      $overstock = ($row['salesprice'] * $amount) - $ouraverage;
      $showoverstock = $overstock; if ($showoverstock < 0) { $showoverstock = '&nbsp;'; $overstock = 0; }
      $reportstring = $reportstring . '<td align=right>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td><td align=right>' . myfix($amount) . '</td><td align=right>' . myfix($row['salesprice']) . '</td><td align=right>' . myfix($row['salesprice'] * $amount) . '</td><td align=right>' . myfix($ouraverage) . '</td><td align=right>' . myfix($showoverstock) . '</td>';
      if ($ouraverage == 0) { $coeff = 0; }
      else { $coeff = round(((3 * $row['salesprice'] * $amount) / $ouraverage),1); }
      $coeff = number_format($coeff,1,",","");
      if ($coeff >= 4) { $coeff = '<b>' . $coeff . '</b>'; }
      $reportstring = $reportstring . '<td align=right>' . $coeff;
      $t1 = $t1 + $row['salesprice'] * $amount;
      $t2 = $t2 + $ouraverage;
      $t3 = $t3 + $overstock;
      $lastpdn = $row['productdepartmentname'];
      $lastpfgn = $row['productfamilygroupname'];
      $lastpfn = $row['productfamilyname'];
      $totalstock = $totalstock + $row['salesprice'] * $amount;
      $totalaverage = $totalaverage + $ouraverage;
      $totaloverstock = $totaloverstock + $overstock;
      $subtotal = $subtotal + $overstock;
      $valuest = $valuest + ($row['salesprice'] * $amount);
    }
  }
  $reportstring = $reportstring . '<tr><td><b>Sous-total</b></td><td colspan=4>&nbsp;</td><td align=right><b>' . myfix($valuest) . '</td><td align=right><b>' . myfix($avgsalesst) . '</td><td align=right><b>' . myfix($subtotal) . '</td><td>&nbsp;</td>';
  $reportstring = $reportstring . '</table>';
  $reportstring = $reportstring . '<br><table class="report">';
  $reportstring = $reportstring . '<tr><td colspan=5 width=400><b>Grand total</b></td><td><b>Valeur</b></td><td><b>3 mois vente</b></td><td><b>Surstock</b></td></tr>';
  $reportstring = $reportstring . '<tr><td colspan=5>&nbsp;</td><td align=right><b>' . myfix($t1) . '</td><td align=right><b>' . myfix($t2) . '</td><td align=right><b>' . myfix($t3) . '</td>';
  $reportstring = $reportstring . '<tr><td colspan=8 align=right><b><font size=+1>Surstock Nestlé: ' . myfix($t1-$t2) . '</font></td></tr>';
  $reportstring = $reportstring . '<tr><td colspan=8 align=right><b><font size=+1>Surstock WC: ' . myfix($totaloverstock) . '</font></td></tr></table>';

  $reportstring = $reportstring . '</td></tr></table>';
  echo $reportstring;
  break;

  default:

  break;
}

require ('inc/bottom.php');

?>


