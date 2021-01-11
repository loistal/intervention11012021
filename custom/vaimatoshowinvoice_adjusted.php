<?php

$offset = $_POST['offset']+0;

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

.items2 {
  position: absolute;
  left: 615px; /* was 635,650 */
  top: <?php echo (420+$offset); ?>px;
  font-size: <?php echo $itemfontsize; ?>;
}

.itemsline {
  font-family: 'Courier New';
  font-size: <?php echo $itemfontsize; ?>;
}

.totalhtbox {
  position: absolute;
  left: 650px;
  top: <?php echo (910+$offset); ?>px; /* was 900 */
}

.totalhtboxR {
  position: absolute;
  left: 600px; /* was 610 */
  top: <?php echo (925+$offset); ?>px; /* was 910 */
}

.vatbox {
  position: absolute;
  left: 650px;
  top: <?php echo (960+$offset); ?>px /* was 950 */
}

.vatboxR {
  position: absolute;
  left: 600px; /* was 610 */
  top: <?php echo (975+$offset); ?>px /* was 960 */
}

.vatbox2 {
  position: absolute;
  left: 500px;
  top: <?php echo (950+$offset); ?>px
}

.vatbox2R {
  position: absolute;
  left: 440px; /* was 500 */
  top: <?php echo (975+$offset); ?>px /* was 965 */
}

.totalttcbox {
  position: absolute;
  left: 650px;
  top: <?php echo (1010+$offset); ?>px /* was 1000 */
}

.totalttcboxR {
  position: absolute;
  left: 600px; /* was 610 */
  top: <?php echo (1025+$offset); ?>px /* was 1010 */
}

.daystopaybox {
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

$usehistory = 0;
if (isset($_POST['showrentals']) && $_POST['showrentals'] == 1)
{
  $query = 'select quarter,invoice.invoiceid,invoice.clientid,clientname,extraname,invoicedate,invoicetime,deliverydate,paybydate,name,tahitinumber,rc,freightcost,insurancecost,invoiceprice,invoicevat,isreturn,proforma,invoicecomment,reference,cancelledid,companytypename,address,postaladdress,postalcode,telephone,cellphone,townname,islandname,extraaddressid,clienttermname,concat(employeename," ",employeefirstname) as employeename from invoice,client,usertable,town,island,clientterm,employee where invoice.clientid=client.clientid and invoice.userid=usertable.userid and client.townid=town.townid and town.islandid=island.islandid and client.clienttermid=clientterm.clienttermid and client.employeeid=employee.employeeid and month(accountingdate)="' . $_POST['month'] . '" and year(accountingdate)="' . $_POST['year'] . '"';
  $query = $query . ' UNION ';
  $query = $query . 'select quarter,invoicehistory.invoiceid,invoicehistory.clientid,clientname,extraname,invoicedate,invoicetime,deliverydate,paybydate,name,tahitinumber,rc,freightcost,insurancecost,invoiceprice,invoicevat,isreturn,proforma,invoicecomment,reference,cancelledid,companytypename,address,postaladdress,postalcode,telephone,cellphone,townname,islandname,extraaddressid,clienttermname,concat(employeename," ",employeefirstname) as employeename from invoicehistory,client,usertable,town,island,clientterm,employee where invoicehistory.clientid=client.clientid and invoicehistory.userid=usertable.userid and client.townid=town.townid and town.islandid=island.islandid and client.clienttermid=clientterm.clienttermid and client.employeeid=employee.employeeid and month(accountingdate)="' . $_POST['month'] . '" and year(accountingdate)="' . $_POST['year'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  if (!$num_results) { echo '<p class="alert">Facture inéxistante.</p>'; exit; }
}
else
{
  if (isset($_POST['invoiceid'])) { $invoiceid = $_POST['invoiceid']; }
  if (isset($_GET['invoiceid']) && $_GET['invoiceid'] > 0) { $invoiceid = $_GET['invoiceid']; }
  $invoiceid = (int) $invoiceid;
  $query = 'select quarter,accountingdate,invoice.clientid,clientname,extraname,invoicedate,invoicetime,deliverydate,paybydate,name,tahitinumber,rc,freightcost,insurancecost,invoiceprice,invoicevat,isreturn,proforma,invoicecomment,reference,cancelledid,companytypename,address,postaladdress,postalcode,telephone,cellphone,townname,islandname,extraaddressid,clienttermname,invoice.employeeid from invoice,client,usertable,town,island,clientterm where invoice.clientid=client.clientid and invoice.userid=usertable.userid and client.townid=town.townid and town.islandid=island.islandid and client.clienttermid=clientterm.clienttermid and invoice.invoiceid="' . $invoiceid . '"';
  # and client.employeeid=employee.employeeid
  $query_prm = array();
  require('inc/doquery.php');
  if (!$num_results)
  {
    $usehistory = 1;
    $query = 'select quarter,accountingdate,invoicehistory.clientid,clientname,extraname,invoicedate,invoicetime,deliverydate,paybydate,name,tahitinumber,rc,freightcost,insurancecost,invoiceprice,invoicevat,isreturn,proforma,invoicecomment,reference,cancelledid,companytypename,address,postaladdress,postalcode,telephone,cellphone,townname,islandname,extraaddressid,clienttermname,invoicehistory.employeeid from invoicehistory,client,usertable,town,island,clientterm where invoicehistory.clientid=client.clientid and invoicehistory.userid=usertable.userid and client.townid=town.townid and town.islandid=island.islandid and client.clienttermid=clientterm.clienttermid and invoicehistory.invoiceid="' . $invoiceid . '"';
    # and client.employeeid=employee.employeeid
    $query_prm = array();
    require('inc/doquery.php');
  }
  if (!$num_results) { echo '<p class="alert">Facture inéxistante.</p>'; exit; }
}
# start loop many invoices
$num_resultsX = $num_results; $main_result = $query_result;
for ($x=1; $x <= $num_resultsX; $x++)
{
  $row = $main_result[($x-1)];
  $accountingdate = $row['accountingdate'];
  if (isset($_POST['showrentals']) && $_POST['showrentals'] == 1) { $invoiceid = $row['invoiceid']; }

  $typetext = 'Facture '; if ($row['isreturn'] == 1) { $typetext = 'Avoir '; }
  showtitle($typetext . $invoiceid);
  
  echo '<div class="clientbox">';
  echo '<p>' . d_output(d_decode($row['clientname'])) . ' ' . $row['companytypename'] . ' ' . $row['extraname'] . '</p>';
  if ($row['extraaddressid'] < 1)
  {
    if ($row['postaladdress'] != "") { $address = $row['postaladdress']; }
    elseif ($row['address'] != "") { $address = $row['address']; }
    else { $address = $row['quarter']; }
    echo '<p>' . $address . '</p>';
    echo '<p>' . $row['postalcode'] . ' ' . $row['townname'] . '</p>';
    echo '<p>' . $row['islandname'] . '</p>';
  }
  else
  {
    $query = 'select address,postaladdress,postalcode,telephone,townname,islandname from extraaddress,town,island where extraaddress.townid=town.townid and town.islandid=island.islandid and extraaddressid="' . $row['extraaddressid'] . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $row3 = $query_result[0];
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
    $query = 'select concat(employeename," ",employeefirstname) as employeename from employee where employeeid="' . $row['employeeid'] . '"';
    $query_prm = array();
    require('inc/doquery.php');
    echo '<div class="employeebox">';
    echo $query_result[0]['employeename'];
    echo '</div>';
  }

  $query = 'select productname,unittypename,quantity,numberperunit,invoiceitem.productid,netweightlabel,givenrebate,basecartonprice,lineprice,linevat,itemcomment,taxcode from invoiceitem,product,unittype,taxcode where invoiceitem.productid=product.productid and product.unittypeid=unittype.unittypeid and product.taxcodeid=taxcode.taxcodeid and invoiceitem.invoiceid="' . $invoiceid . '" order by invoiceitem.productid,quantity desc';
  if ($usehistory) { $query = 'select productname,unittypename,quantity,numberperunit,invoiceitemhistory.productid,netweightlabel,givenrebate,basecartonprice,lineprice,linevat,itemcomment,taxcode from invoiceitemhistory,product,unittype,taxcode where invoiceitemhistory.productid=product.productid and product.unittypeid=unittype.unittypeid and product.taxcodeid=taxcode.taxcodeid and invoiceitemhistory.invoiceid="' . $invoiceid . '" order by invoiceitemhistory.productid,quantity desc'; }
  $query_prm = array();
  require('inc/doquery.php');
  $num_results2 = $num_results;
  $main_result2 = $query_result;

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
    $row2 = $main_result2[($y-1)];
    if ($y==1) { $firstlinevatcode = $row2['taxcode']+0; }
    elseif ($firstlinevatcode == 0) { $firstlinevatcode = $row2['taxcode']+0; }
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
      if (isset($_POST['hidediscount']) && $_POST['hidediscount'] == 1) { $bcp = myround($row2['lineprice']); }
      echo 'Prix&nbsp;' . $bcp . '&nbsp;&nbsp;';
      if ($row2['givenrebate'] > 0 && (!isset($_POST['hidediscount']) || $_POST['hidediscount'] != 1)) { echo 'Promo&nbsp;' . $gr . '&nbsp;&nbsp;'; }
      echo 'TVA&nbsp;' . round($row2['taxcode']) . '%';
    }
    echo '</span></td></tr>';
    $subtotal[$y] = myround($row2['lineprice']);
  }
  echo '</table></div>';
  
  #if ($hidelinefield)
  #{
    echo '<div class="vatbox2R">'; # R
    #$numspaces = 10 - mb_strlen($taxcode); # ???
    $numspaces = 10;
    for ($xyz=1; $xyz<= $numspaces; $xyz++) { echo '&nbsp;'; }
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
      for ($xyz=1; $xyz<= $numspaces; $xyz++) { echo '&nbsp;'; }
      echo $subtotal[$y] . '</span></td></tr>';
    }
    echo '</table></div>';
  }
  


  echo '<div class="totalhtboxR">'; #R on these 3
  echo myround($row['invoiceprice'] - $row['invoicevat']);
  echo '</div>';

  echo '<div class="vatboxR">';
  echo myround($row['invoicevat']);
  echo '</div>';

  echo '<div class="totalttcboxR">';
  echo myround($row['invoiceprice']);
  echo '</div>';

  echo '<div class="daystopaybox">';
  echo 'Arrêté la présente facture à la somme de : ';
  if ($hidelinefield == 1) { echo '<br>'; }
  $total = $row['invoiceprice']+0;
  echo mb_strtoupper(convertir($total));
  #echo mb_strtoupper($total);
  echo ' CFP.';
  # show payments
  $invoiceprice = $row['invoiceprice'];
  $totalpaid = 0; $paymentid = 0;
  $query = 'select paymentid,value,reimbursement,paymenttypename,payment.paymenttypeid,bankid,chequeno from payment,paymenttype where payment.paymenttypeid=paymenttype.paymenttypeid and forinvoiceid="' . $invoiceid . '"';
  $query_prm = array();
  require('inc/doquery.php');
  for ($y=1; $y <= $num_results; $y++)
  {
    $row = $query_result[($y-1)];
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
          $query_prm = array();
          require('inc/doquery.php');
          echo $query_result[0]['bankname'];
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

if ($logmeout == 1)
{
  session_unset();
  session_destroy();
}

?>