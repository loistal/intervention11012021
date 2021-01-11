<?php

# Build web page
require ('inc/standard.php');
require('custom/oldfunc.php');
$reportwindow = 1; $printwindow = 1;
require ('inc/top.php');
function showtitle($title)
{
  echo '<TITLE>' . $title . '</TITLE></HEAD><BODY>';
}

$report = $_POST['report'];
if ($report == "" && $_GET['report'] != "") { $report = $_GET['report']; }

################################### from some random website
// fonction de convertion d'un chiffre à 3 digits en lettre 
function cenvtir($Valeur)
{ 
  
    $code = ""; 
    //texte en clair 
	$SUnit = array(1=>"et un ", 2=>"deux ", 3=>"trois ", 4=>"quatre ", 5=>"cinq ", 6=>"six ", 7=>"sept ", 8=>"huit ", 9=>"neuf ", 10=>"dix ", 11=>"onze ", 12=>"douze ", 13=>"treize ", 14=>"quatorze ", 15=>"quinze ", 16=>"seize ", 17=>"dix-sept ", 18=>"dix-huit ", 19=>"dix-neuf "); 
	$sDiz = array(20=> "vingt ", 30=> "trente ", 40=>"quarante ", 50=>"cinquante ", 60=>"soixante ", 70=>"soixante dix ", 80=>"quatre vingt ", 90=>"quatre vingt dix ");
  
    if ($Valeur>99)
	{ 
		$N1= intval($Valeur/100); 
		if ($N1>1)
		{ 
        	$code = $code.$SUnit[$N1]; 
		} 
		$Valeur = $Valeur - ($N1*100); 
        if ($code != "")
		{ 
			if ($Valeur == 0)
			{
	    		$code = $code." cents "; 
			}else
			{
	            $code = $code." cent "; 
			}
        }else
		{ 
            $code = " cent "; 
        } 
    } 
    if ($Valeur != 0)
	{ 
        if ($Valeur > 19) {
    $N1 = intval($Valeur/10)*10;
    if ( (($Valeur>70) and($Valeur<80)or($Valeur>90)) && $Valeur-$N1!=0 ) {
      $code = $code.$sDiz[$N1-10];
      if ( $Valeur>70 && $Valeur<80 && $Valeur-$N1==1 )
        $code = $code." et ";
    }else
      $code = $code.$sDiz[$N1];
    if (($Valeur>70) and($Valeur<80)or($Valeur>90))
      $Valeur = $Valeur + 10;
    $Valeur = $Valeur - $N1;
  }
        if ($Valeur > 0)
		{ 
            $code = $code." ".$SUnit[$Valeur]; 
        } 
    } 
    return $code; 
} 

function convertir($Montant)
{ 
	$grade = array(0 => "zero ",1=>" milliards ",2=>" millions ",3=>" mille "); 
#    $Mon = array(0=>" Euro",1=>" Euros",2=>" Cent",3=>" Cents"); 
    $Mon = array(0=>" franc",1=>" francs",2=>" Cent",3=>" Cents"); 
  
    // Mise au format pour les chéques et le SWI 
    $Montant = number_format($Montant,2,".",""); 

    if ($Montant == 0)
	{ 
        $result = $grade[0].$Mon[0]; 
    }else
	{ 
        $result = ""; 

        // Calcule des Unités 
        $montant = intval($Montant); 
  
        // Calcul des centimes 
        $centime = round(($Montant * 100) - ($montant * 100),0); 
  
        // Traitement pour les Milliards 
        $nombre = $montant / 1000000000; 
        $nombre = intval($nombre); 
        if ($nombre > 0)
		{ 
            if ($nombre > 1)
			{ 
                $result = $result.cenvtir($nombre).$grade[1]; 
            }else
			{ 
                $result = $result." Un ".$grade[1]; 
                $result = mb_substr($result,0,13)." "; 
            } 
            $montant = $montant - ($nombre * 1000000000); 
        } 
  
        // Traitement pour les Millions 
        $nombre = $montant / 1000000; 
        $nombre = intval($nombre); 
        if ($nombre > 0)
		{ 
            if ($nombre > 1)
			{ 
                $result = $result.cenvtir($nombre).$grade[2]; 
            }else
			{ 
                $result = $result." Un ".$grade[2]; 
                $result = mb_substr($result,0,12)." "; 
            } 
            $montant = $montant - ($nombre * 1000000); 
        } 
  
        // Traitement pour les Milliers 
        $nombre = $montant / 1000; 
        $nombre = intval($nombre); 
        if ($nombre > 0)
		{ 
            if ($nombre > 1)
			{ 
                $result = $result.cenvtir($nombre).$grade[3]; 
            }else
			{ 
                $result = $result.$grade[3]; 
            } 
            $montant = $montant - ($nombre * 1000); 
        } 
  
        // Traitement pour les Centaines & centimes 
        $nombre = $montant; 
        if ($nombre>0)
		{ 
            $result = $result.cenvtir($nombre); 
        } 
        // Traitement si le montant = 1 
        if ((substr($result,0,7) == " et un " and mb_strlen($result) == 7))
		{ 
            $result = mb_substr($result,3,3); 
            $result = $result.$Mon[0]; 
            if (intval($centime) != 0)
			{ 
                $differ = cenvtir(intval($centime)); 
                if (substr($differ,0,7) == " et un ")
				{ 
                    if ($result == "")
					{ 
                            $differ = mb_substr($differ,3); 
                    } 
                    $result = $result." ".$differ.$Mon[2]; 
                }else
				{ 
                        $result = $result." et ".$differ.$Mon[3]; 
                } 
            } 
        // Traitement si le montant > 1 ou = 0 
        }else
		{ 
            if ($result != "")
			{ 
                $result = $result.$Mon[1]; 
            } 
            if (intval($centime) != 0)
			{ 
                $differ = cenvtir(intval($centime)); 
                if (substr($differ,0,7) == " et un ")
				{ 
                    if ($result == "")
					{ 
                    	$differ = mb_substr($differ,3); 
                    } 
                    $result = $result." ".$differ.$Mon[2]; 
                }else
				{ 
                    if ($result != "")
					{ 
                    	$result = $result." et ".$differ.$Mon[3]; 
                    }else
					{ 
                        $result = $differ.$Mon[3]; 
                    } 
                } 
            } 
        } 
    } 
    return $result; 
} 
###################################


# Go to the menuitem
switch($report)
{


  case 'fonrep';
    echo '<style>
  table {
    white-space: normal;
  }
  </style>';
  $date = d_builddate($_POST['day'],$_POST['month'],$_POST['year']);
  $stopdate = d_builddate($_POST['stopday'],$_POST['stopmonth'],$_POST['stopyear']);
  $date2 = d_builddate($_POST['day2'],$_POST['month2'],$_POST['year2']);
  $stopdate2 = d_builddate($_POST['stopday2'],$_POST['stopmonth2'],$_POST['stopyear2']);
  $date3 = d_builddate($_POST['day3'],$_POST['month3'],$_POST['year3']);
  $stopdate3 = d_builddate($_POST['stopday3'],$_POST['stopmonth3'],$_POST['stopyear3']);
  $date4 = d_builddate($_POST['day4'],$_POST['month4'],$_POST['year4']);
  $stopdate4 = d_builddate($_POST['stopday4'],$_POST['stopmonth4'],$_POST['stopyear4']);
  $gratuit = $_POST['gratuit']+0;
  
  $query = 'select vmt_fountain.rentalid,fntcomments,maintdate,changedate,servicedate,fountainname,suppliername,fountaincatname,brandname,fountaindescname';
  $query = $query . ' from vmt_fountain,vmt_brand,supplier,vmt_fountaincat,vmt_fountaindesc';
  if ($_POST['clientsectorid'] > 0 || $gratuit >= 0) { $query = $query . ',vmt_rental'; }
  if ($_POST['clientsectorid'] > 0) { $query = $query . ',client'; }
  $query = $query . ' where vmt_fountain.fountaindescid=vmt_fountaindesc.fountaindescid and vmt_fountain.fountaincatid=vmt_fountaincat.fountaincatid and vmt_fountain.brandid=vmt_brand.brandid and vmt_brand.supplierid=supplier.supplierid and notused=0';
  if ($_POST['rentalstatus'] == 1) { $query = $query . ' and vmt_fountain.rentalid=0'; }
  if ($_POST['rentalstatus'] == 2) { $query = $query . ' and vmt_fountain.rentalid>0'; }
  if ($_POST['usedate1'] == 1) { $query = $query . ' and changedate>="' . $date . '" and changedate<="' . $stopdate . '"'; }
  if ($_POST['usedate2'] == 1) { $query = $query . ' and servicedate>="' . $date2 . '" and servicedate<="' . $stopdate2 . '"'; }
  if ($_POST['usedate3'] == 1) { $query = $query . ' and maintdate>="' . $date3 . '" and maintdate<="' . $stopdate3 . '"'; }
  if ($_POST['usedate4'] == 1) { $query = $query . ' and maintdate2>="' . $date4 . '" and maintdate2<="' . $stopdate4 . '"'; }
  if ($_POST['brandid'] > 0) { $query = $query . ' and vmt_fountain.brandid="' . $_POST['brandid'] . '"'; }
  if ($_POST['fountaincatid'] > 0) { $query = $query . ' and vmt_fountain.fountaincatid="' . $_POST['fountaincatid'] . '"'; }
  if ($_POST['fountaindescid'] > 0) { $query = $query . ' and vmt_fountain.fountaindescid="' . $_POST['fountaindescid'] . '"'; }
  if ($_POST['clientsectorid'] > 0 || $gratuit >= 0) { $query = $query . ' and vmt_fountain.rentalid=vmt_rental.rentalid'; }
  if ($_POST['clientsectorid'] > 0) { $query = $query . ' and vmt_rental.clientid=client.clientid and client.clientsectorid="' . $_POST['clientsectorid'] . '"'; }
  if ($gratuit == 0) { $query = $query . ' and gratuit=0'; }
  if ($gratuit == 1) { $query = $query . ' and gratuit=1'; }
  $query = $query . ' order by suppliername,fountainname';

  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  
  echo '<h2>Rapport fontaines ' . datefix2($_SESSION['ds_curdate']) . '</h2><br>';
  for ($i=0; $i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    if ($i == 0)
    {
      if ($_POST['brandid'] > 0) { echo '<p>Marque: ' . $row['brandname'] . '</p>'; }
      if ($_POST['fountaincatid'] > 0) { echo '<p>Catégorie: ' . $row['fountaincatname'] . '</p>'; }
      if ($_POST['fountaindescid'] > 0) { echo '<p>Etat: ' . $row['fountaindescname'] . '</p>'; }
      echo '<table class="report" border=1 cellpadding=2 cellspacing=2><tr><td><b>Numéro</b></td><td><b>Etat</b></td><td><b>Marque</b></td><td><b>Catégorie</b></td><td><b>Fournisseur</b></td><td><b>Contrat</b></td><td><b>Client</b></td><td><b>Adresse</b></td><td><b>Tél.</b></td><td><b>Date entretien</b></td><td><b>Date service</b></td><td><b>Date changement état</b></td><td><b>Remarques</b></td></tr>';
    }
    $telephone = ''; $cellphone = '';
    if ($row['rentalid'] > 0)
    {
      $query = 'select vmt_rental.clientid,clientname,reference,telephone,cellphone,address,clientsectorname from vmt_rental,client,clientsector where vmt_rental.clientid=client.clientid and client.clientsectorid=clientsector.clientsectorid and vmt_rental.rentalid=' . $row['rentalid'];
      $query_prm = array();
      require('inc/doquery.php');
      $row2 = $query_result[0];
      $clientname = $row2['clientid'] . ': ' . d_output(d_decode($row2['clientname']));
      $address = $row2['address'];
      $sector = $row2['clientsectorname'];
      $reference = $row2['reference'];
      $telephone = $row2['telephone'];
      $cellphone = $row2['cellphone'];
    }
    else
    {
      $clientname = '&nbsp;'; $reference = '&nbsp;'; $address = '&nbsp;'; $sector = '&nbsp;';
    }
    #  #$query = $query . ',"&nbsp;" as clientid,"&nbsp;" as clientname,"&nbsp;" as reference';
    echo '<tr><td>' . $row['fountainname'] . '</td><td>' . $row['fountaindescname'] . '</td><td>' . $row['brandname'] . '</td><td>' . $row['fountaincatname'] . '</td><td>' . $row['suppliername'] . '</td><td>' . $reference . '</td><td>' . $clientname . '</td><td>' . $address . '</td>';
	# echo '<td>' . $sector . '</td>';
	echo '<td>' . $telephone . ' ' . $cellphone . '</td><td>' . datefix2($row['maintdate']) . '</td><td>' . datefix2($row['servicedate']) . '</td><td>' . datefix2($row['changedate']) . '</td><td>' . $row['fntcomments'] . '</td></tr>';
  }
  echo '<tr><td colspan=14 align=center><b>' . $num_results_main . ' fontaines total</td></tr>';
  echo '</table>';
  exit;
  
  ### invoice ###
  case '1':
  $usehistory = 0;
  
  if ($_POST['showrentals'] == 1)
  {
    $query = 'select invoice.invoiceid,invoice.clientid,clientname,extraname,invoicedate,invoicetime,deliverydate,paybydate,name,tahitinumber,rc,freightcost,insurancecost,invoiceprice,invoicevat,isreturn,proforma,invoicecomment,reference,cancelledid,companytypename,address,postaladdress,postalcode,telephone,cellphone,townname,islandname,extraaddressid,clienttermname,concat(employeename," ",employeefirstname) as employeename from invoice,client,usertable,town,island,clientterm,employee where invoice.clientid=client.clientid and invoice.userid=usertable.userid and client.townid=town.townid and town.islandid=island.islandid and client.clienttermid=clientterm.clienttermid and client.employeeid=employee.employeeid and month(accountingdate)="' . $_POST['month'] . '" and year(accountingdate)="' . $_POST['year'] . '"';
    $query = $query . ' UNION ';
    $query = $query . 'select invoicehistory.invoiceid,invoicehistory.clientid,clientname,extraname,invoicedate,invoicetime,deliverydate,paybydate,name,tahitinumber,rc,freightcost,insurancecost,invoiceprice,invoicevat,isreturn,proforma,invoicecomment,reference,cancelledid,companytypename,address,postaladdress,postalcode,telephone,cellphone,townname,islandname,extraaddressid,clienttermname,concat(employeename," ",employeefirstname) as employeename from invoicehistory,client,usertable,town,island,clientterm,employee where invoicehistory.clientid=client.clientid and invoicehistory.userid=usertable.userid and client.townid=town.townid and town.islandid=island.islandid and client.clienttermid=clientterm.clienttermid and client.employeeid=employee.employeeid and month(accountingdate)="' . $_POST['month'] . '" and year(accountingdate)="' . $_POST['year'] . '"';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    if (!$num_results) { echo '<p class="alert">Facture inéxistante.</p>'; exit; }
  }
  else
  {
    $invoiceid = $_POST['invoiceid'];
    if ($_GET['invoiceid'] > 0) { $invoiceid = $_GET['invoiceid']; }
    $invoiceid = (int) $invoiceid;
    $query = 'select accountingdate,invoice.clientid,clientname,extraname,invoicedate,invoicetime,deliverydate,paybydate,name,tahitinumber,rc,freightcost,insurancecost,invoiceprice,invoicevat,isreturn,proforma,invoicecomment,reference,cancelledid,companytypename,address,postaladdress,postalcode,telephone,cellphone,townname,islandname,extraaddressid,clienttermname,invoice.employeeid from invoice,client,usertable,town,island,clientterm where invoice.clientid=client.clientid and invoice.userid=usertable.userid and client.townid=town.townid and town.islandid=island.islandid and client.clienttermid=clientterm.clienttermid and invoice.invoiceid="' . $invoiceid . '"';
# and client.employeeid=employee.employeeid
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    if (!$num_results)
    {
      $usehistory = 1;
      $query = 'select accountingdate,invoicehistory.clientid,clientname,extraname,invoicedate,invoicetime,deliverydate,paybydate,name,tahitinumber,rc,freightcost,insurancecost,invoiceprice,invoicevat,isreturn,proforma,invoicecomment,reference,cancelledid,companytypename,address,postaladdress,postalcode,telephone,cellphone,townname,islandname,extraaddressid,clienttermname,invoicehistory.employeeid from invoicehistory,client,usertable,town,island,clientterm where invoicehistory.clientid=client.clientid and invoicehistory.userid=usertable.userid and client.townid=town.townid and town.islandid=island.islandid and client.clienttermid=clientterm.clienttermid and invoicehistory.invoiceid="' . $invoiceid . '"';
# and client.employeeid=employee.employeeid
      $result = mysql_query($query, $db_conn); querycheck($result);
      $num_results = mysql_num_rows($result);
    }
    if (!$num_results) { echo '<p class="alert">Facture inéxistante.</p>'; exit; }
  }
# start loop many invoices
$num_resultsX = $num_results;
for ($x=1; $x <= $num_resultsX; $x++)
{
  $row = mysql_fetch_array($result);
  $accountingdate = $row['accountingdate'];
  if ($_POST['showrentals'] == 1) { $invoiceid = $row['invoiceid']; }

  $typetext = 'Facture '; if ($row['isreturn'] == 1) { $typetext = 'Avoir '; }
  showtitle($typetext . $invoiceid);
  
  echo '<div class="clientbox">';
  echo '<p>' . d_output(d_decode($row['clientname'])) . ' ' . $row['companytypename'] . ' ' . $row['extraname'] . '</p>';
  if ($row['extraaddressid'] < 1)
  {
    echo '<p>' . $row['quarter'] . '</p>';
    if ($row['postaladdress'] != "") { $address = $row['postaladdress']; }
    else { $address = $row['address']; }
    echo '<p>' . $address . '</p>';
    echo '<p>' . $row['postalcode'] . ' ' . $row['townname'] . '</p>';
    echo '<p>' . $row['islandname'] . '</p>';
  }
  else
  {
    $query3 = 'select address,postaladdress,postalcode,telephone,townname,islandname from extraaddress,town,island where extraaddress.townid=town.townid and town.islandid=island.islandid and extraaddressid="' . $row['extraaddressid'] . '"';
    $result3 = mysql_query($query3, $db_conn); querycheck($result3);
    $row3 = mysql_fetch_array($result3);
    if ($row3['postaladdress'] != "") { $address = $row3['postaladdress']; }
    else { $address = $row3['address']; }
    echo '<p>' . $address . '</p>';
    echo '<p>' . $row3['postalcode'] . ' ' . $row3['townname'] . '</p>';
    echo '<p>' . $row3['islandname'] . '</p>';
  }
  echo '</div>';

  echo '<div class="datebox">';
  echo datefix2($row['accountingdate']);
  echo '</div>';

  echo '<div class="invoiceidbox">';
  echo $invoiceid;
  echo '</div>';

  echo '<div class="clientidbox">';
  echo $row['clientid'];
  echo '</div>';

  echo '<div class="relevebox">';
  if ($row['tahitinumber'] != "") { echo 'NT ' . $row['tahitinumber']; }
  echo ' &nbsp; ';
  if ($row['rc'] != "") { echo 'RC ' . $row['rc']; }
  echo '</div>';

  if ($row['employeeid'] > 0)
  {
    $queryE = 'select concat(employeename," ",employeefirstname) as employeename from employee where employeeid="' . $row['employeeid'] . '"';
    $resultE = mysql_query($queryE, $db_conn); querycheck($resultE);
    $rowE = mysql_fetch_array($resultE);
    echo '<div class="employeebox">';
    echo $rowE['employeename'];
    echo '</div>';
  }

  $query2 = 'select productname,unittypename,quantity,numberperunit,invoiceitem.productid,netweightlabel,givenrebate,basecartonprice,lineprice,linevat,itemcomment,taxcode from invoiceitem,product,unittype,taxcode where invoiceitem.productid=product.productid and product.unittypeid=unittype.unittypeid and product.taxcodeid=taxcode.taxcodeid and invoiceitem.invoiceid="' . $invoiceid . '" order by invoiceitem.productid,quantity desc';
  if ($usehistory) { $query2 = 'select productname,unittypename,quantity,numberperunit,invoiceitemhistory.productid,netweightlabel,givenrebate,basecartonprice,lineprice,linevat,itemcomment,taxcode from invoiceitemhistory,product,unittype,taxcode where invoiceitemhistory.productid=product.productid and product.unittypeid=unittype.unittypeid and product.taxcodeid=taxcode.taxcodeid and invoiceitemhistory.invoiceid="' . $invoiceid . '" order by invoiceitemhistory.productid,quantity desc'; }
  $result2 = mysql_query($query2, $db_conn); querycheck($result2);
  $num_results2 = mysql_num_rows($result2);

  echo '<div class="items">';
  if ($row['cancelledid']) { echo ' &nbsp; <font color="' . $alertcolor . '">ANNULEE</font><br>'; }
  if ($row['reference'] != "")
  {
    $reference = $row['reference'];
    $addtoproductname = '';
    $hidelinefield = 0;
    if (mb_substr($reference, 0, 7) == "Contrat")
    {
      $kladd_starthere = mb_strpos($reference, ",") - 8;
      $addtoproductname = $reference;
      $reference = mb_substr($reference, 8, $kladd_starthere);
    if (1 == 1 || $invoiceid == 70500 || $invoiceid == 71091)
    {
      $kladd = mb_substr($addtoproductname, ($kladd_starthere+8));
    #echo '<br>debug: '.$kladd.'<br>';
      $month = mb_substr($kladd,1,mb_strpos($kladd, "/")-1)+0;
      if ($month < 10) { $month = '0' . $month; }
    #echo '<br>month= '.$month.'<br>';
      $starthere = mb_strpos($kladd, "au")-5;
      $year = mb_substr($kladd,$starthere,4)+0;
      if ($year == 0)
      {
        #echo '(0)';
        $month = mb_substr($accountingdate,5,2);
        $year = mb_substr($accountingdate,0,4);
        $kladd_date = d_builddate(31,$month,$year);
        $day2 = mb_substr($kladd_date,8,2);
        $month2 = mb_substr($kladd_date,5,2);
        $year2 = mb_substr($kladd_date,0,4);
      }
      else
      {
    #echo '<br>year= '.$year.'<br>';
        $kladd = mb_substr($kladd,$starthere+7);
    #echo '<br>debug: '.$kladd.'<br>';
        $month2 = mb_substr($kladd,1,mb_strpos($kladd, "/")-1)+0;
    #echo '<br>month2= '.$month2.'<br>';
        $starthere = mb_strpos($kladd, "/")+1;
        $year2 = mb_substr($kladd,$starthere,4)+0;
    #echo '<br>year2= '.$year2.'<br>';
        if (strlen($year2) == 2) { $year2 = '20'.$year2; }
        $kladd_date = d_builddate(31,$month2,$year2); # added 20
    #echo '<br>$kladd_date="'.$kladd_date.'"';
        $day2 = mb_substr($kladd_date,8,2);
        if (strlen($month2) == 1) { $month2 = '0'.$month2; }
        #$month2 = mb_substr($kladd_date,5,2); # no need
        #$year2 = mb_substr($kladd_date,0,4); # no need
      }
      $addtoproductname = ' du 01/' . $month . '/' . $year . ' au ' . $day2 . '/' . $month2 . '/' . $year2 . '<br>';
      $hidelinefield = 1;
    }
    else
    {
        $month = mb_substr($accountingdate,5,2);
        $year = mb_substr($accountingdate,0,4);
        $kladd_date = d_builddate(31,$month,$year);
        $day2 = mb_substr($kladd_date,8,2);
        $month2 = mb_substr($kladd_date,5,2);
        $year2 = mb_substr($kladd_date,0,4);
        $addtoproductname = ' du 01/' . $month . '/' . $year . ' au ' . $day2 . '/' . $month2 . '/' . $year2 . '<br>';
      $hidelinefield = 1;
    }
    }
    echo ' &nbsp <b>Référence:</b> ' . $reference . '<br>';
  }
  if ($row['proforma'] == 1) { echo ' &nbsp <b>PROFORMA</b> '; }
  if ($row['invoicecomment'] != "") { echo $row['invoicecomment']; }
  echo '<table border=0 cellspacing=1 cellpadding=1 width=99%>';
  for ($y=1; $y <= $num_results2; $y++)
  {
    $row2 = mysql_fetch_array($result2);
    if ($y==1) { $firstlinevatcode = $row2['taxcode']+0; }
    $quantity = $row2['quantity']/$row2['numberperunit']; $unittypename = $row2['unittypename'];
    $bcp = myround($row2['basecartonprice']);
    if ($row2['quantity']%$row2['numberperunit']) { $quantity = $row2['quantity']; $unittypename = 'pièce'; $bcp = myround($bcp/$row2['numberperunit']); }
#    if($quantity > 1) { $unittypename = $unittypename . 's'; }
    $gr = myround($row2['givenrebate']);
    $tva = myround($row2['linevat']);
    $productname = $row2['productname'] . ' ';
    if ($_SESSION['ds_useunits'] && $row2['numberperunit'] > 1) { $productname = $productname . $row2['numberperunit'] . ' x '; }
    $productname = $productname . $addtoproductname . $row2['netweightlabel'] . ' ' . $row2['itemcomment'];
    echo '<tr><td><span class="itemsline">';
    if ($hidelinefield == 0) 
    {
      echo $quantity . '&nbsp;' . $unittypename . '&nbsp;&nbsp;';
    }
    echo $productname;
    if ($hidelinefield == 0)
    {
      if ($_POST['hidediscount'] == 1) { $bcp = myround($row2['lineprice']); }
      echo 'Prix&nbsp;' . $bcp . '&nbsp;&nbsp;';
      if ($row2['givenrebate'] > 0 && $_POST['hidediscount'] != 1) { echo 'Promo&nbsp;' . $gr . '&nbsp;&nbsp;'; }
      echo 'TVA&nbsp;' . round($row2['taxcode']) . '%';
    }
    echo '</span></td></tr>';
    $subtotal[$y] = myround($row2['lineprice']);
  }
  echo '</table></div>';
  
  #if ($hidelinefield)
  #{
    echo '<div class="vatbox2">';
    $numspaces = 10 - mb_strlen($taxcode);
    for ($x=1; $x<= $numspaces; $x++) { echo '&nbsp;'; }
    echo $firstlinevatcode . '&nbsp;%'; # show first line vat
    echo '</div>';
  #}

  if ($hidelinefield == 0)
  {
    echo '<div class="items2">';
    if ($row['invoicecomment'] != "") { echo '<br>'; }
    echo '<table border=0 cellspacing=1 cellpadding=1 width=99%>';
    for ($y=1; $y <= $num_results2; $y++)
    {
      echo '<tr><td><span class="itemsline">';
      $numspaces = 10 - mb_strlen($subtotal[$y]);
      for ($x=1; $x<= $numspaces; $x++) { echo '&nbsp;'; }
      echo $subtotal[$y] . '</span></td></tr>';
    }
    echo '</table></div>';
  }
  


  echo '<div class="totalhtbox">';
  echo myround($row['invoiceprice'] - $row['invoicevat']);
  echo '</div>';

  echo '<div class="vatbox">';
  echo myround($row['invoicevat']);
  echo '</div>';

  echo '<div class="totalttcbox">';
  echo myround($row['invoiceprice']);
  echo '</div>';

  echo '<div class="daystopaybox">';
  echo 'Arrêté la présente facture à la somme de : ';
  if ($hidelinefield == 1) { echo '<br>'; }
  $total = $row['invoiceprice']+0;
  echo mb_strtoupper(convertir($total)) . ' CFP.';
  # show payments
  $invoiceprice = $row['invoiceprice'];
  $totalpaid = 0; $paymentid = 0;
  $query = 'select paymentid,value,reimbursement,paymenttypename,payment.paymenttypeid,bankid,chequeno from payment,paymenttype where payment.paymenttypeid=paymenttype.paymenttypeid and forinvoiceid="' . $invoiceid . '"';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $num_results = mysql_num_rows($result);
  for ($y=1; $y <= $num_results; $y++)
  {
    $row = mysql_fetch_array($result);
    if ($row['reimbursement'] == 1) { $totalpaid = $totalpaid - $row['value']; }
    else
    { 
      $totalpaid = $totalpaid + $row['value'];
      if ($paymentid > 0) { $paymentid = -1; }
      if ($paymentid == 0)
      { 
        $paymentid = $row['paymentid']; $paymenttypename = $row['paymenttypename'];  $paymenttypeid = $row['paymenttypeid']; $bankid = $row['bankid']; $chequeno = $row['chequeno'];
      }
    }
  }
  if ($totalpaid >= $invoiceprice)
  {
    echo '<p>Cette facture a été entierement reglé.';
    if ($paymentid > 0)
    {
      echo ' (Paiement ' . $paymentid . ', ' . $paymenttypename;
      if ($paymenttypeid > 1)
      {
        echo ': ';
        if ($bankid > 0)
        {
          $query = 'select bankname from bank where bankid="' . $bankid . '"';
          $result = mysql_query($query, $db_conn); querycheck($result);
          $row = mysql_fetch_array($result);
          echo $row['bankname'];
        }
        echo ' ' . $chequeno;
      }
      echo ')';
    }
    echo '</p>';
  }
  else
  {
    echo '<p>Si le paiement a déja été effectué, veuillez ne pas tenir compte de cette facture.</p>';
  }
  # end show payments
  echo '</div>';
  
  #echo '<div class="infofact">';
  #echo $_SESSION['ds_infofact'];
  #echo '</div>';
  
#  if ($hidelinefield == 0)
#  {
#  echo '<div class="daystopaybox">';
#  echo '<p>Payer avant le ' . datefix($row['paybydate']) . '</p>';
#  echo '</div>';
#  }

  # page break
  #echo '<DIV style="page-break-after:always"></DIV>';
}



#  if ($row['freightcost'] > 0) { echo '<tr><td colspan=4>Frêt</td><td align=right>' . myround($row['freightcost']) . '</td><td>&nbsp;</td></tr>'; }
#  if ($row['insurancecost'] > 0) { echo '<tr><td colspan=4>Assurance</td><td align=right>' . myround($row['insurancecost']) . '</td><td>&nbsp;</td></tr>'; }
#  echo '<tr><td colspan=4>';
#  if ($row['isreturn']) { echo 'Total à rembourser'; }
#  else { echo 'Total à payer'; }
#  echo '</td><td align=right><b>' . myround($row['invoiceprice']) . '</b></td><td>&nbsp;</td></tr>';
  break;


  ### invoice ### actually relevé custom
  case 'custrel':
  $offset = $_POST['offset']+0;
  ?>


<STYLE type="text/css">

* {
  margin: 0;
}
html, body {
#  height: 100%;
}

body {
  background-color: transparent;
  color: #000000;
  font-family: 'Times New Roman',Times,serif;
  font-size: 14pt; /* was 13pt */
  text-align: justify;
  margin-top: 0px;
  margin-left: 0px;
  border-collapse: collapse;
}

.tiny {
  font-size: 75%
}

p {
  text-indent: 0em;
}

h1 {
  background: transparent;
  font-weight: bold;
  margin-right: 4%;
  font-size: 200%;
  text-align: right;
  white-space: nowrap;
  margin: 1
}

h2 {
  background: transparent;
  margin-left: 4%;
  margin-right: 4%;
  text-align: left;
  font-size: 150%;
  font-weight: bold;
  white-space: nowrap;
  margin: 1
}

h6 {
  background: <?php echo $menucolor; ?>;
  margin-left: 0%;
  margin-right: 0%;
  vertical-align: bottom;
  font-size: 100%;
  font-weight: bold;
  text-align: center;
  margin-top: 0px;
  margin-left: 0px;
  font-family: "Verdana" 
}

table {
  border-collapse: collapse;
  font-size: 14pt; /* was default */
}

input { 
  background-color: <?php echo $formcolor; ?>;
  color: <?php echo $fgcolor; ?> 
}
select { 
  background-color: <?php echo $formcolor; ?>; 
  color: <?php echo $fgcolor; ?> 
}
textarea { 
  background-color: <?php echo $formcolor; ?>; 
  color: <?php echo $fgcolor; ?> 
}

blockquote {
  font-style: italic;
  margin-left: 6%;
  margin-right: 6%
}

a:link {
  color: <?php echo $linkcolor; ?>;
  background: transparent;
  text-decoration: none
}

a:visited {
  color: <?php echo $linkcolor; ?>;
  background: transparent;
  text-decoration: none
}

a:active {
  color: <?php echo $linkcolor; ?>;
  background: transparent;
  text-decoration: none
}

a:hover {
  color: <?php echo $linkcolor; ?>;
  background: transparent;
  text-decoration: none
}

.menu {
  text-indent: 0em;
  font-size: 65%
}

.normal {
  text-align: justify;
  text-indent: 0em
}

.bar {
  text-indent: 0em;
  font-size: 75%
}

.selectaction {
  text-align: left
}

.clientbox {
  text-align: left;
  position: absolute;
  left: 425px;
  top: 150px;
}

.datebox {
  text-align: left;
  position: absolute;
  left: 250px;
  top: <?php echo (275+($offset/2)); ?>px /* was 270 */
}

.invoiceidbox {
  text-align: left;
  position: absolute;
  left: 45px; /* was 35 */
  top: <?php echo (360+$offset); ?>px;
  font-size: 90%;
}

.relevebox {
  text-align: left;
  position: absolute;
  left: 200px;
  top: <?php echo (360+$offset); ?>px;
  font-size: 90%;
}

.clientidbox {
  text-align: left;
  position: absolute;
  left: 460px; /* was 480 */
  top: <?php echo (360+$offset); ?>px;
  font-size: 90%;
}

.employeebox {
  text-align: left;
  position: absolute;
  left: 565px; /* was 590 */
  top: <?php echo (360+$offset); ?>px;
  font-size: 90%;
}

<?php
$itemfontsize = '75%';
if ($_POST['itemfontsize'] != '') { $itemfontsize = $_POST['itemfontsize'] + 0; $itemfontsize = $itemfontsize . '%'; }
?>

.items {
  position: absolute;
  left: 55px; /* was 25,35 */
  top: <?php echo (420+$offset); ?>px;
  width: 500px;
  font-size: <?php echo $itemfontsize; ?>;
}

.itemsR {
  position: absolute;
  left: 25px; /* was 25,35 */
  top: <?php echo (420+$offset); ?>px;
  width: 500px;
  font-size: <?php echo $itemfontsize; ?>;
}

.items2 {
  position: absolute;
  left: 600px; /* was 635,650 */
  top: <?php echo (420+$offset); ?>px;
  font-size: <?php echo $itemfontsize; ?>;
}

.itemsline {
  font-family: 'Courier New';
  font-size: <?php echo $itemfontsize; ?>;
}

.totalhtbox {
  position: absolute;
  left: 570px;
  top: <?php echo (895+$offset); ?>px; /* was 900 */
}

.totalhtboxR {
  position: absolute;
  left: 570px; /* was 610 */
  top: <?php echo (925+$offset); ?>px; /* was 910 */
}

.vatbox {
  position: absolute;
  left: 570px;
  top: <?php echo (945+$offset); ?>px /* was 950 */
}

.vatboxR {
  position: absolute;
  left: 570px; /* was 610 */
  top: <?php echo (975+$offset); ?>px /* was 960 */
}

.vatbox2 {
  position: absolute;
  left: 420px;
  top: <?php echo (945+$offset); ?>px
}

.vatbox2R {
  position: absolute;
  left: 420px; /* was 500 */
  top: <?php echo (975+$offset); ?>px /* was 965 */
}

.totalttcbox {
  position: absolute;
  left: 570px;
  top: <?php echo (995+$offset); ?>px /* was 1000 */
}

.totalttcboxR {
  position: absolute;
  left: 570px; /* was 610 */
  top: <?php echo (1025+$offset); ?>px /* was 1010 */
}

.daystopaybox {
  position: absolute;
  left: 65px; /* was 25 */
  top: <?php echo (835+$offset); ?>px; /* was 840 */
  font-size: 80%;
  font-weight: bold;
}

.daystopayboxR {
  position: absolute;
  left: 35px; /* was 25 */
  top: <?php echo (835+$offset); ?>px; /* was 840 */
  font-size: 80%;
  font-weight: bold;
}

.infofact {
  position: absolute;
  left: 0px;
  bottom: 0;
  text-align: center;
  font-size: 50%;
  height: 80px;
  width: 80%
}

.alert {
  color: <?php echo $alertcolor; ?>;
}

.sup {
  vertical-align: super;
  font-size: 75%;
}

.releve1 {
  position: absolute;
  left: 400px;
  top: 0px;
  width: 250px;
  height: 125px
}

.releve2 {
  position: absolute;
  left: 400px;
  top: 75px;
  width: 250px;
  height: 250px
}

P.breakhere {page-break-before: always}

</STYLE>
<?php
  
  
  if ($_POST['month'] == "" || $_POST['year'] == "")
  {
    ?>
    <h2>Releve format Vaimato:</h2>
    <form method="post" action="customprintwindow.php"><table><?php
    $month = mb_substr($_SESSION['ds_curdate'],5,2);
    $year = mb_substr($_SESSION['ds_curdate'],0,4);
    echo '<tr><td>Numéro client:</td><td><input autofocus type="text" STYLE="text-align:right" name="clientid" size=10></td></tr>';
    ?><tr><td>Mois:</td><td><select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>Exclure locations:</td><td><input type="checkbox" name="noloc" value=1></td></tr>
    <tr><td colspan="2" align="center">
    <input type=hidden name="report" value="1">
    <input type="submit" value="Valider"></td></tr></table></form><?php
  }
  
  else {
  
  
  $clientid = (int) $_POST['clientid'];
  
  if ($clientid <= 0) { echo 'Numéro client mal saisi'; exit; }
  #$releveid = $clientid . date("YmdH");
  $month = $_POST['month']+0;
  $year = $_POST['year']+0;
  $toshowdate = d_builddate(31,$month,$year);
  $releveid = $clientid . str_replace("-", "", $toshowdate);
  $shownotaxexpl = 0;

  $typetext = 'Relevé ';
  showtitle($typetext . $releveid);
  
  $pagenumber = $_POST['pagenumber']+0;
  $linesperpage = $_POST['linesperpage']+0;
  $exclude75 = (int) $_POST['exclude75'];
  
  require('preload/taxcode.php');
  $total = 0; $vat = 0; $taxok = 1;
  echo '<div class="itemsR">';
  echo '<table border=0 cellspacing=1 cellpadding=1 width=99%><br>';
  $query = 'select isreturn,taxcodeid,reference,accountingdate,invoiceitemhistory.productid,productname,quantity,numberperunit,basecartonprice,lineprice,linevat,invoicehistory.employeeid from invoicehistory,invoiceitemhistory,product where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and invoiceitemhistory.productid=product.productid and invoicehistory.clientid="' . $clientid . '" and confirmed=1 and month(accountingdate)="' . $month . '" and year(accountingdate)="' . $year . '"';
  $query = $query . '  and invoiceitemhistory.productid=10';
  if ($_POST['nomatched'] == 1) { $query = $query . ' and matchingid=0'; }
  $query = $query . ' order by accountingdate asc,invoicehistory.invoiceid asc,lineprice desc';
#echo $query.'<br>';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $num_results = mysql_num_rows($result);
#echo 'found results: '.$num_results;
  $newystart = $num_results + 1;
  for ($y=1; $y <= $num_results; $y++)
  {
    $row = mysql_fetch_array($result);
    $taxcodeid = $row['taxcodeid'];
    $taxcode = $taxcodeA[$taxcodeid]; if ($y == 1) { $lasttaxcode = $taxcode; }
    if ($taxcode != $lasttaxcode) { $taxok = 0; }
    $notax = 0; if ($taxcode == 0) { $notax = 1; $shownotaxexpl = 1; }
    $quantity = $row['quantity']/$row['numberperunit']; $unittypename = $row['unittypename'];
    $bcp = myround($row['basecartonprice']);
    if ($row2['quantity']%$row['numberperunit']) { $quantity = $row['quantity']; $unittypename = 'pièce'; $bcp = myround($bcp/$row['numberperunit']); }
    $gr = myround($row['givenrebate']);
    $tva = myround($row['linevat']);
    $productname = $row['productname'];
    if ($_SESSION['ds_useunits'] && $row['numberperunit'] > 1) { $productname = $productname . 'x ' . $row['numberperunit']; }
    if ($row['netweightlabel'] != "") { $productname = $productname . ' ' . $row['netweightlabel']; }
    if ($row['itemcomment'] != "") { $productname = $productname . ' ' . $row['itemcomment']; }
    $lineprice = $row['lineprice']+0;
    $ok[$y] = 1; if ($_POST['nozero'] == 1 && $lineprice == 0) { $ok[$y] = 0; }
    #echo '<tr>checking ' .$lineprice .'<br>';
    if ($ok[$y])
    {
      if (ceil($y/$linesperpage) == $pagenumber)
      {
        echo '<tr><td><span class="itemsline">';
        $numspaces = 5 - mb_strlen($quantity);
        for ($x=1; $x<= $numspaces; $x++) { echo '&nbsp;'; }
        echo $quantity . '&nbsp;';
        #$productname = mb_substr($productname, 0, 17);
        echo str_replace(" ", "&nbsp;", $productname);
        #$numspaces = 17 - mb_strlen($productname);
        #for ($x=1; $x<= $numspaces; $x++) { echo '&nbsp;'; }
        $xday = mb_substr($row['accountingdate'],8,2);
        $xmonth = mb_substr($row['accountingdate'],5,2);
        $xyear = mb_substr($row['accountingdate'],0,4);
        $xshowdate = $xday . '/' . $xmonth . '/' . $xyear;
        echo '&nbsp;liv&nbsp;le&nbsp;' . $xshowdate . '&nbsp;&nbsp;BL&nbsp;' . $row['reference'];
        if ($notax) { echo '&nbsp;*'; }
        echo '</span></td></tr>';
      }
      #echo '<td align=right>' . myfix($row['lineprice']) . ' TTC</td>';
      $linetotal[$y] = myround($row['lineprice']);
      #if ($row['isreturn'] == 1) { $linetotal[$y] = 0 - $linetotal[$y]; }
      $isreturn = $row['isreturn']+0;
      if ($isreturn == 1) { $linetotal[$y] = 0 - $linetotal[$y]; $vat = $vat - myround($row['linevat']); }
      else { $vat = $vat + myround($row['linevat']); }
      $total = $total + $linetotal[$y];
      #echo ' t='.$total;
    }
  }
  $query = 'select isreturn,taxcode,reference,accountingdate,tahitinumber,rc,invoiceitemhistory.productid,productname,quantity,numberperunit,unittypename,basecartonprice,lineprice,linevat,clientname,quarter,address,postaladdress,postalcode,townname,islandname,invoicehistory.employeeid from invoicehistory,invoiceitemhistory,product,client,town,island,unittype,taxcode where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and invoiceitemhistory.productid=product.productid and invoicehistory.clientid=client.clientid and client.townid=town.townid and town.islandid=island.islandid and product.unittypeid=unittype.unittypeid and product.taxcodeid=taxcode.taxcodeid and invoicehistory.clientid="' . $clientid . '" and confirmed=1 and month(accountingdate)="' . $month . '" and year(accountingdate)="' . $year . '"';
  $query = $query . '  and invoiceitemhistory.productid<>10';
  if ($exclude75 == 1) { $query .= ' and invoiceitemhistory.productid<>75'; }
  if ($_POST['nomatched'] == 1) { $query = $query . ' and matchingid=0'; }
  if ($_POST['noloc'] == 1) { $query = $query . ' and invoiceitemhistory.productid NOT IN (11,18,20,21,22,23,30,31,32,33,51,53)'; }
  $query = $query . ' order by accountingdate asc,invoicehistory.invoiceid asc,lineprice desc';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $num_results = mysql_num_rows($result);
  $numr2 = $newystart + $num_results;
  #echo 'newystart=' . $newystart . ' numr2='.$numr2;
  for ($y=$newystart; $y < $numr2; $y++)
  {
    $row = mysql_fetch_array($result);
    $taxcode = $row['taxcode']; if ($y == 1) { $lasttaxcode = $taxcode; }
    if ($taxcode != $lasttaxcode) { $taxok = 0; }
    $notax = 0; if ($taxcode == 0) { $notax = 1; $shownotaxexpl = 1; }
    $quantity = $row['quantity']/$row['numberperunit']; $unittypename = $row['unittypename'];
    $bcp = myround($row['basecartonprice']);
    if ($row2['quantity']%$row['numberperunit']) { $quantity = $row['quantity']; $unittypename = 'pièce'; $bcp = myround($bcp/$row['numberperunit']); }
    $gr = myround($row['givenrebate']);
    $tva = myround($row['linevat']);
    $productname = $row['productname'] . ' ';
    if ($_SESSION['ds_useunits'] && $row['numberperunit'] > 1) { $productname = $productname . 'x ' . $row['numberperunit']; }
    if ($row['netweightlabel'] != "") { $productname = $productname . ' ' . $row['netweightlabel']; }
    if ($row['itemcomment'] != "") { $productname = $productname . ' ' . $row['itemcomment']; }
    $lineprice = $row['lineprice']+0;
    $ok[$y] = 1; if ($_POST['nozero'] == 1 && $lineprice == 0) { $ok[$y] = 0; }
    #echo '<tr>checking ' .$lineprice .'<br>';
    if ($ok[$y])
    {
      if (ceil($y/$linesperpage) == $pagenumber)
      {
        echo '<tr><td><span class="itemsline">';
        $numspaces = 5 - mb_strlen($quantity);
        for ($x=1; $x<= $numspaces; $x++) { echo '&nbsp;'; }
        echo $quantity . '&nbsp;';
        #$productname = mb_substr($productname, 0, 17);
        echo str_replace(" ", "&nbsp;", $productname);
        #$numspaces = 17 - mb_strlen($productname);
        #for ($x=1; $x<= $numspaces; $x++) { echo '&nbsp;'; }
        $xday = mb_substr($row['accountingdate'],8,2);
        $xmonth = mb_substr($row['accountingdate'],5,2);
        $xyear = mb_substr($row['accountingdate'],0,4);
        $xshowdate = $xday . '/' . $xmonth . '/' . $xyear;
        echo '&nbsp;liv&nbsp;le&nbsp;' . $xshowdate . '&nbsp;&nbsp;BL&nbsp;' . $row['reference'];
        if ($notax) { echo '&nbsp;*'; }
        echo '</span></td></tr>';
      }
      #echo '<td align=right>' . myfix($row['lineprice']) . ' TTC</td>';
      $linetotal[$y] = myround($row['lineprice']);
      if ($row['isreturn'] == 1) { $linetotal[$y] = 0 - $linetotal[$y]; $vat = $vat - myround($row['linevat']); }
      else { $vat = $vat + myround($row['linevat']); }
      $total = $total + $linetotal[$y];
      #echo ' t='.$total;
    }
  }
  echo '</table>';
  if ($shownotaxexpl) { echo '<br> &nbsp; &nbsp; * exonéré de TVA'; }
  echo '</div>';
  $totalpages = ceil($numr2/$linesperpage);
  
  echo '<div class="items2">';
  echo '<table border=0 cellspacing=1 cellpadding=1 width=99%><br>';
  for ($y=1; $y <= $numr2; $y++)
  {
    if (ceil($y/$linesperpage) == $pagenumber && $ok[$y] == 1)
    {
      echo '<tr><td><span class="itemsline">';
      $numspaces = 8 - mb_strlen($linetotal[$y]);
      for ($x=1; $x<= $numspaces; $x++) { echo '&nbsp;'; }
      echo $linetotal[$y] . '</span></td></tr>';
    }
  }
  echo '</table></div>';
  
  $query = 'select clientname,companytypename,quarter,postaladdress,address,postalcode,townname,islandname from client,town,island where client.townid=town.townid and town.islandid=island.islandid and clientid=?';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  
  echo '<div class="clientbox">';
  echo '<p><b>' . d_decode($query_result[0]['clientname']) . ' ' . $query_result[0]['companytypename'] . ' ' . $row['extraname'] . '</b></p>';
  #if ($row['extraaddressid'] < 1)
  #{
    #echo '<p>' . $query_result[0]['quarter'] . '</p>';
    if ($query_result[0]['postaladdress'] != "") { $address = $query_result[0]['postaladdress']; }
    else { $address = $query_result[0]['address']; }
    echo '<p>' . $address . '</p>';
    echo '<p>' . $query_result[0]['postalcode'] . ' ' . $query_result[0]['townname'] . '</p>';
    echo '<p>' . $query_result[0]['islandname'] . '</p>';
  #}
  #else
  #{
  #  $query3 = 'select address,postaladdress,postalcode,telephone,townname,islandname from extraaddress,town,island where extraaddress.townid=town.townid and town.islandid=island.islandid and extraaddressid="' . $row['extraaddressid'] . '"';
  #  $result3 = mysql_query($query3, $db_conn); querycheck($result3);
  #  $row3 = mysql_fetch_array($result3);
  #  if ($row3['postaladdress'] != "") { $address = $row3['postaladdress']; }
  #  else { $address = $row3['address']; }
  #  echo '<p>' . $address . '</p>';
  #  echo '<p>' . $row3['postalcode'] . ' ' . $row3['townname'] . '</p>';
  #  echo '<p>' . $row3['islandname'] . '</p>';
  #}
  echo '</div>';

  echo '<div class="datebox">';
  echo datefix2($toshowdate);
  echo '</div>';

  echo '<div class="invoiceidbox">';
  echo $releveid;
  if ($numr2 > $linesperpage) { echo '<br>Page ' . $pagenumber . '/' . $totalpages; }
  echo '</div>';

  echo '<div class="clientidbox">';
  echo $clientid;
  echo '</div>';

  echo '<div class="relevebox">';
  if ($row['tahitinumber'] != "") { echo 'NT ' . $row['tahitinumber']; }
  echo ' &nbsp; ';
  if ($row['rc'] != "") { echo 'RC ' . $row['rc']; }
  echo '</div>';

  if ($row['employeeid'] > 0)
  {
    $queryE = 'select concat(employeename," ",employeefirstname) as employeename from employee where employeeid="' . $row['employeeid'] . '"';
    $resultE = mysql_query($queryE, $db_conn); querycheck($resultE);
    $rowE = mysql_fetch_array($resultE);
    echo '<div class="employeebox">';
    echo $rowE['employeename'];
    echo '</div>';
  }

  if ($totalpages == 1 || $pagenumber == $totalpages)
  {
    echo '<div class="totalhtboxR"><font face="courier new">';
    $numspaces = 10 - mb_strlen($total);
    for ($x=1; $x<= $numspaces; $x++) { echo '&nbsp;'; }
    echo $total;
    echo '</font></div>';

    echo '<div class="vatboxR"><font face="courier new">';
    $numspaces = 10 - mb_strlen($vat);
    for ($x=1; $x<= $numspaces; $x++) { echo '&nbsp;'; }
    echo $vat;
    echo '</font></div>';

    $total = $total + $vat;
    echo '<div class="totalttcboxR"><font face="courier new">';
    $numspaces = 10 - mb_strlen($total);
    for ($x=1; $x<= $numspaces; $x++) { echo '&nbsp;'; }
    echo $total;
    echo '</font></div>';
    
    if ($taxok)
    {
      echo '<div class="vatbox2R"><font face="courier new">';
      $numspaces = 10 - mb_strlen($taxcode);
      for ($x=1; $x<= $numspaces; $x++) { echo '&nbsp;'; }
      echo ($taxcode+0) . '&nbsp;%';
      echo '</font></div>';
    }
    
    echo '<div class="daystopayboxR">';
    echo ' &nbsp; Arrête la présente facture à la somme de<br>';
    echo ' &nbsp; ' . mb_strtoupper(convertir($total)) . ' CFP';
    echo '<p>Si le paiement a déja été effectué, veuillez ne pas tenir compte de cette facture</p>';
    echo '</div>';
  }
  
  if ($row['employeeid'] > 0)
  {
    $queryE = 'select concat(employeename," ",employeefirstname) as employeename from employee where employeeid="' . $row['employeeid'] . '"';
    $resultE = mysql_query($queryE, $db_conn); querycheck($resultE);
    $rowE = mysql_fetch_array($resultE);
    echo '<div class="employeebox">';
    echo $rowE['employeename'];
    echo '</div>';
  }

  # page break
  #echo '<DIV style="page-break-after:always"></DIV>';
  }
  break;






  default:
  showtitle('Print window');
  echo '<p>this is the print window</p>';
  break;

}

  require ('inc/bottom.php');

?>

