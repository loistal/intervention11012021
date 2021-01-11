<?php

#echo 'session.gc_maxlifetime = ' . ini_get('session.gc_maxlifetime');
#echo '<br>session.gc_probability = ' . ini_get('session.gc_probability');
#echo '<br>session.gc_divisor = ' . ini_get('session.gc_divisor');

ini_set('display_errors', 'On');
error_reporting(E_ALL);

### keep this
if ($_SESSION['ds_systemaccess'] != 1) { require('logout.php'); exit; }
require ('inc/standard.php');
$dauphin_currentmenu = basename(__FILE__, '.php');
require ('inc/top.php');
echo '<h2>Test page</h2><br>';
echo 'Running PHP version : '.phpversion().'<br>';
###

#########################
function _getServerLoadLinuxData()
{
    if (is_readable("/proc/stat"))
    {
        $stats = @file_get_contents("/proc/stat");

        if ($stats !== false)
        {
            // Remove double spaces to make it easier to extract values with explode()
            $stats = preg_replace("/[[:blank:]]+/", " ", $stats);

            // Separate lines
            $stats = str_replace(array("\r\n", "\n\r", "\r"), "\n", $stats);
            $stats = explode("\n", $stats);

            // Separate values and find line for main CPU load
            foreach ($stats as $statLine)
            {
                $statLineData = explode(" ", trim($statLine));

                // Found!
                if
                (
                    (count($statLineData) >= 5) &&
                    ($statLineData[0] == "cpu")
                )
                {
                    return array(
                        $statLineData[1],
                        $statLineData[2],
                        $statLineData[3],
                        $statLineData[4],
                    );
                }
            }
        }
    }

    return null;
}

// Returns server load in percent (just number, without percent sign)
function getServerLoad()
{
    $load = null;

    if (stristr(PHP_OS, "win"))
    {
        $cmd = "wmic cpu get loadpercentage /all";
        @exec($cmd, $output);

        if ($output)
        {
            foreach ($output as $line)
            {
                if ($line && preg_match("/^[0-9]+\$/", $line))
                {
                    $load = $line;
                    break;
                }
            }
        }
    }
    else
    {
        if (is_readable("/proc/stat"))
        {
            // Collect 2 samples - each with 1 second period
            // See: https://de.wikipedia.org/wiki/Load#Der_Load_Average_auf_Unix-Systemen
            $statData1 = _getServerLoadLinuxData();
            sleep(1);
            $statData2 = _getServerLoadLinuxData();

            if
            (
                (!is_null($statData1)) &&
                (!is_null($statData2))
            )
            {
                // Get difference
                $statData2[0] -= $statData1[0];
                $statData2[1] -= $statData1[1];
                $statData2[2] -= $statData1[2];
                $statData2[3] -= $statData1[3];

                // Sum up the 4 values for User, Nice, System and Idle and calculate
                // the percentage of idle time (which is part of the 4 values!)
                $cpuTime = $statData2[0] + $statData2[1] + $statData2[2] + $statData2[3];

                // Invert percentage to get CPU time, not idle time
                $load = 100 - ($statData2[3] * 100 / $cpuTime);
            }
        }
    }

    return $load;
}

//----------------------------

$cpuLoad = getServerLoad();
if (is_null($cpuLoad)) {
    echo "CPU load not estimateable (maybe too old Windows or missing rights at Linux or Windows)<br>";
}
else {
    echo 'CPU load : '.$cpuLoad.'%<br>';
}


function getServerMemoryUsage($getPercentage=true)
{
    $memoryTotal = null;
    $memoryFree = null;

    if (stristr(PHP_OS, "win")) {
        // Get total physical memory (this is in bytes)
        $cmd = "wmic ComputerSystem get TotalPhysicalMemory";
        @exec($cmd, $outputTotalPhysicalMemory);

        // Get free physical memory (this is in kibibytes!)
        $cmd = "wmic OS get FreePhysicalMemory";
        @exec($cmd, $outputFreePhysicalMemory);

        if ($outputTotalPhysicalMemory && $outputFreePhysicalMemory) {
            // Find total value
            foreach ($outputTotalPhysicalMemory as $line) {
                if ($line && preg_match("/^[0-9]+\$/", $line)) {
                    $memoryTotal = $line;
                    break;
                }
            }

            // Find free value
            foreach ($outputFreePhysicalMemory as $line) {
                if ($line && preg_match("/^[0-9]+\$/", $line)) {
                    $memoryFree = $line;
                    $memoryFree *= 1024;  // convert from kibibytes to bytes
                    break;
                }
            }
        }
    }
    else
    {
        if (is_readable("/proc/meminfo"))
        {
            $stats = @file_get_contents("/proc/meminfo");

            if ($stats !== false) {
                // Separate lines
                $stats = str_replace(array("\r\n", "\n\r", "\r"), "\n", $stats);
                $stats = explode("\n", $stats);

                // Separate values and find correct lines for total and free mem
                foreach ($stats as $statLine) {
                    $statLineData = explode(":", trim($statLine));

                    //
                    // Extract size (TODO: It seems that (at least) the two values for total and free memory have the unit "kB" always. Is this correct?
                    //

                    // Total memory
                    if (count($statLineData) == 2 && trim($statLineData[0]) == "MemTotal") {
                        $memoryTotal = trim($statLineData[1]);
                        $memoryTotal = explode(" ", $memoryTotal);
                        $memoryTotal = $memoryTotal[0];
                        $memoryTotal *= 1024;  // convert from kibibytes to bytes
                    }

                    // Free memory
                    if (count($statLineData) == 2 && trim($statLineData[0]) == "MemFree") {
                        $memoryFree = trim($statLineData[1]);
                        $memoryFree = explode(" ", $memoryFree);
                        $memoryFree = $memoryFree[0];
                        $memoryFree *= 1024;  // convert from kibibytes to bytes
                    }
                }
            }
        }
    }

    if (is_null($memoryTotal) || is_null($memoryFree)) {
        return null;
    } else {
        if ($getPercentage) {
            return (100 - ($memoryFree * 100 / $memoryTotal));
        } else {
            return array(
                "total" => $memoryTotal,
                "free" => $memoryFree,
            );
        }
    }
}

function getNiceFileSize($bytes, $binaryPrefix=true) {
    if ($binaryPrefix) {
        $unit=array('B','KiB','MiB','GiB','TiB','PiB');
        if ($bytes==0) return '0 ' . $unit[0];
        return @round($bytes/pow(1024,($i=floor(log($bytes,1024)))),2) .' '. (isset($unit[$i]) ? $unit[$i] : 'B');
    } else {
        $unit=array('B','KB','MB','GB','TB','PB');
        if ($bytes==0) return '0 ' . $unit[0];
        return @round($bytes/pow(1000,($i=floor(log($bytes,1000)))),2) .' '. (isset($unit[$i]) ? $unit[$i] : 'B');
    }
}

// Memory usage: 4.55 GiB / 23.91 GiB (19.013557664178%)
$memUsage = getServerMemoryUsage(false);
echo sprintf("Memory usage: %s / %s (%s%%)",
    getNiceFileSize($memUsage["total"] - $memUsage["free"]),
    getNiceFileSize($memUsage["total"]),
    getServerMemoryUsage(true)
);

echo '<br><br>';

phpinfo();

/*
$query = 'update adjustment set matchingid=0 where accountingnumberid=?';
$query_prm = array(24);
require('inc/doquery.php');*/
/*
$query = 'select adjustmentgroupid from adjustmentgroup where integrated=2';
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  $query = 'update adjustment set matchingid=0 where adjustmentgroupid=?';
  $query_prm = array($main_result[$i]['adjustmentgroupid']);
  require ('inc/doquery.php');
}*/

#########################

### 2020 08 17 correct isclient for Wing Chong
/*
$query = 'select clientid from client where isclient=0 and issupplier=0 and isemployee=0 and isother=0';
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  echo $i . ' ' . $main_result[$i]['clientid'] . '<br>';
  $query = 'update client set isclient=1 where clientid=?';
  $query_prm = array($main_result[$i]['clientid']);
  require('inc/doquery.php');
}
*/

### 2020 08 17 correct user stock for Animalice
/*
$query = 'select productid,amount from purchasebatch where userid=1 and arrivaldate>="2020-08-01"';
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  echo $i . ' ' . $main_result[$i]['productid'] . ' ' . $main_result[$i]['amount'] . '<br>';
  $query = 'insert into modifiedstock_user (netchange,productid,changedate,userid,foruserid) values (?,?,curdate(),1,2)';
  $query_prm = array($main_result[$i]['amount'],$main_result[$i]['productid']);
  require('inc/doquery.php');
}*/
###

/*
#2020 02 19 extra barcodes for Wing Chong
$barcodelist = 'CICU9708614
CICU9790387
CICU9825400 
CICU9863020 
CICU9928516 
CICU8146298 
CICU9662895 
CICU9662909 
CICU9679012 
CICU9679028 
CICU9725709 
CICU9725695 
CICU9860946 
CICU9860951 
CICU9860967 
CICU9968843 
CICU9968822 
CICU9968864 
CICU9968817 
CICU9968838 
CICU9968859
CICU8584360
CICU1442298 
CICU1442282
VF F-G
VF H
VF A-B-C-D';

$height = 200;
$height_barcode = 120;
$width = 300;
$fontsize_b = 80;
$framewidth = '29.4cm'; # 27cm   adjusted for "default" margins with Chrome
$frameheight = '8.8cm'; # 8cm
$frameheight_a = '6.2cm';
$frameheight_b = '1.8cm';
$frameheight_c = '0.8cm';
$fontsize = $fontsize_a = 120;
$bgcolor = 'yellow'; ##ccff00
$i=0;

$barcodelistA = preg_split('/\r\n|\r|\n/', $barcodelist);
echo '<p class=breakhere></p>';
foreach ($barcodelistA as $barcode)
{
  $i++;
  echo '<div style="width: '.$framewidth.'; height: '.$frameheight_c.'; background: '.$bgcolor.'; display:flex;
  justify-content:center; align-items:center;"></div>';
  echo '<div style="width: '.$framewidth.'; height: '.$frameheight_a.'; background: '.$bgcolor.'; display:flex;
  justify-content:center; align-items:center;">
  <span style="font-size: ' . $fontsize . 'px; font-family: calibri; font-weight: bold; -webkit-transform:scale(1,1.2); ">
  ' . $barcode . '
  <span style="font-size: ' . $fontsize_a . 'px; background: white;">
  <img src="barcode.php?text=' . $barcode . '" width=' . $width . '; height=' . $height_barcode . '>
  </span></span>&nbsp;</div>';
  echo '<div style="width: '.$framewidth.'; height: '.$frameheight_b.'; background: '.$bgcolor.'; display:flex;
  justify-content:center; align-items:center;">
  <span style="font-size: ' . $fontsize_b . 'px; vertical-align: bottom; font-family: calibri; font-weight: bold;
  -webkit-transform:scale(1,0.78); ">
  </span></div>';
  if ($i%2==0) { echo '<p class=breakhere></p>'; }
  else { echo '<br><br>'; }
  if ($i%16==0) { echo '<br><br>'; }
}

*/



/*


#see https://stackoverflow.com/questions/41486345/jsignature-jquery-plugin-make-an-image-from-signature-stored-in-db-base30
require_once ('jq/jSignature_Tools_Base30.php');
function base30_to_png($base30_string)
{
  $data = str_replace ( 'image/jsignature;base30,', '', $base30_string );
  $converter = new jSignature_Tools_Base30 ();
  $raw = $converter->Base64ToNative ( $data );
  // Calculate dimensions
  $width = 0;
  $height = 0;
  foreach ( $raw as $line ) {
      if (max ( $line ['x'] ) > $width)
          $width = max ( $line ['x'] );
      if (max ( $line ['y'] ) > $height)
          $height = max ( $line ['y'] );
  }

  // Create an image
  $im = imagecreatetruecolor ( $width + 20, $height + 20 );

  // Save transparency for PNG
  imagesavealpha ( $im, true );
  // Fill background with transparency
  $trans_colour = imagecolorallocatealpha ( $im, 255, 255, 255, 127 );
  imagefill ( $im, 0, 0, $trans_colour );
  // Set pen thickness
  imagesetthickness ( $im, 2 );
  // Set pen color to black
  $black = imagecolorallocate ( $im, 0, 0, 0 );
  // Loop through array pairs from each signature word
  for($i = 0; $i < count ( $raw ); $i ++) {
      // Loop through each pair in a word
      for($j = 0; $j < count ( $raw [$i] ['x'] ); $j ++) {
          // Make sure we are not on the last coordinate in the array
          if (! isset ( $raw [$i] ['x'] [$j] ))
              break;
          if (! isset ( $raw [$i] ['x'] [$j + 1] ))
              // Draw the dot for the coordinate
              imagesetpixel ( $im, $raw [$i] ['x'] [$j], $raw [$i] ['y'] [$j], $black );
          else
              // Draw the line for the coordinate pair
              imageline ( $im, $raw [$i] ['x'] [$j], $raw [$i] ['y'] [$j], $raw [$i] ['x'] [$j + 1], $raw [$i] ['y'] [$j + 1], $black );
      }
  }

  
  ob_start();
  imagepng($im);
  $image_content = ob_get_clean();
  return $image_content;
}



if (isset($_POST['hiddenSigData']))
{
  $sig_invoiceid = 0;
  $query = 'insert into image (sig_invoiceid,image,imagetype) values (?,?,?)';
  $query_prm = array($sig_invoiceid,base30_to_png ($_POST['hiddenSigData']),'png');
  require('inc/doquery.php');
  
  #base30_to_jpeg ($_POST['hiddenSigData'], 'test.png');
  #echo '"'.$_POST['hiddenSigData'].'"<br><br>';
  echo 'image saved to db';
}


?>
<form action="testpage.php" method="post">
<div id="signatureparent">
<div id="signature"></div>
<button type="button" onclick="$('#signature').jSignature('clear')">Effacer</button>
<button type="submit" id="btnSave">Enregistrer</button>
</div>
<input type="hidden" id="hiddenSigData" name="hiddenSigData" />
</form>
<div id="scrollgrabber"></div>
<script src="jq/jquery.js"></script>
<script src="jq/jSignature.js"></script>
<script src="jq/plugins/jSignature.CompressorBase30.js"></script>
<script src="jq/plugins/jSignature.CompressorSVG.js"></script>
<script src="jq/plugins/jSignature.UndoButton.js"></script> 
<script>
    $(document).ready(function() {
        var $sigdiv = $("#signature").jSignature({'UndoButton':false});

        // -- i explain from here...
        $('#btnSave').click(function(){
            var sigData = $('#signature').jSignature('getData','base30');
            $('#hiddenSigData').val(sigData);
        });
        // -- ... to here.

    })
</script>
<?php

*/


/*
$emailaddress = 'svein.tjonndal@gmail.com';
$replytoaddress = 'svein.tjonndal@gmail.com';
$subject = 'BdL Nestlé créé';
$messagetext = 'test BdL - message à créér';
if (d_sendemail($emailaddress,$replytoaddress,$subject,$messagetext)) { echo 'sent'; }
else { echo 'nope'; }
*/

/*
#2018 12 05 extra barcodes for Wing Chong
$barcodelist = 'CE1
CE2
CE3
CT1
CT2
TG1
TG2
TP1
TP2
TP3
TP21
TP22
TP23
TP24
TT1
TT2
TT3
VE1
VE2
VE3
VE51
VE52
VE53
VE54
VT1
VT2
VT3
VT4';
$barcodelistA = preg_split('/\r\n|\r|\n/', $barcodelist);

foreach ($barcodelistA as $barcode)
{
  echo '<table class=transparent><tr><td width=600 align=center>';
  echo '<p style="font-size: 1500%;  font-style: monospace; align: center">'.$barcode.'</p>';
  echo '<tr><td>';
  echo '<img src="barcode.php?size=30&text='.$barcode.'" width=600>';
  echo '</table>';
  echo '<p class=breakhere></p>';
}
*/

/*
require("phpqrcode/qrlib.php");

$errorCorrectionLevel = 'L'; # array('L','M','Q','H')
$matrixPointSize = 6; # 1 to 10
$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'customfiles'.DIRECTORY_SEPARATOR;
$PNG_WEB_DIR = 'customfiles/';
$employeeid = 5;
$url = 'http://' . $_SERVER['SERVER_NAME'] . '/employeeqrscan.php?employeeid='.$employeeid;

$filename = $PNG_TEMP_DIR.'test'.md5($url.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';

QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

echo '<img src="'.$PNG_WEB_DIR.basename($filename).'">';
*/






################### TO RUN ON ALL DBs

/*

echo '<h2>Country table import</h2>';
$separator = ';';
$fp = fopen ('countries.csv','r');
$i = 0;

require('preload/clientcategory.php');
require('preload/town.php');
require('preload/island.php');

echo '<table class=report>';
while ($line=fgets($fp))
{
  $i++;
  $lineA = explode($separator, $line);
  echo '<tr>';
  for ($x=0; $x < 17; $x++)
  {
    if(isset($lineA[$x])) { echo '<td>',' [',$x,'] ',$lineA[$x]; }
  }
  if ($lineA[5] != '')
  {
    $rank = $lineA[4]; if ($rank == 0) { $rank = 100; }
    $countryname = $lineA[1]; if ($countryname == '') { $countryname = $lineA[6]; }
    $query = 'select countryid from country where countryname=?';
    $query_prm = array($countryname);
    require ('inc/doquery.php');
    if ($num_results)
    {
      $countryid = $query_result[0]['countryid'];
      $query = 'update country set fenixcode=? where countryid=?';
      $query_prm = array($lineA[5], $countryid);
      require ('inc/doquery.php');
      if ($num_results) { echo '<td>Updated.'; }
    }
    else
    {
      $query = 'insert into country (countryname,fenixcode,rank) values (?,?,?)';
      $query_prm = array($countryname,$lineA[5],$rank);
      require ('inc/doquery.php');
      if ($num_results) { echo '<td>Inserted.'; }
    }
  }
}

*/


### 2018 02 13 Natural and Organic, convert all products to HT, set HT = current TTC
/*
if ($_SESSION['ds_customname'] == 'Natural & Organic')
{
  require('preload/taxcode.php');
  $query = 'select productid,salesprice,taxcodeid from product where taxcodeid>1';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0; $i < $num_results_main; $i++)
  {
    echo $main_result[$i]['productid'] . ' ' . $main_result[$i]['salesprice'] . ' ' . $taxcodeA[$main_result[$i]['taxcodeid']];
    echo ' ' . round($main_result[$i]['salesprice'] * (1 + $taxcodeA[$main_result[$i]['taxcodeid']]/100)) . '<br>';
    $price = round($main_result[$i]['salesprice'] * (1 + $taxcodeA[$main_result[$i]['taxcodeid']]/100));
    $query = 'update product set taxcodeid=1,salesprice=? where productid=?';
    $query_prm = array($price, $main_result[$i]['productid']);
    require('inc/doquery.php');
  }
}
*/
###


########################################################## functional
/*
function f() # lambda
{
  return function ($x)
  {
    return $x + 1;
  };
}

$a = f();

echo '<br>',$a(1);

function f2($g, $x)
{
  return $g($x);
}

echo '<br>',f2(function ($x) { return $x+1; }, 1); # lambda

###
# composition

function o($g, $f)
{
  return function ($x) use ($g, $f)
  {
    return $g($f($x));
  };
}

$f = function ($s) { return 'f'.$s; };
$g = function ($s) { return 'g'.$s; };

$gf = o($g, $f);

echo '<br>',$gf('');

###
# currying

function curried_plus($x)
{
  return function ($y) use ($x)
  {
    return $x + $y;
  };
}

echo '<br>',curried_plus(1)(2);
*/
##########################################################

/*
# for overtime and rate calc
$a = array_fill(0, 1440, 0); # whole day
$b = array_fill(0, 60, 1); # minutes worked
$a = array_replace($a, $b); # merge
$c = array_slice($a, 0, 60); # first hour
echo array_sum($c); # total minutes worked in period
var_dump($a);
*/

/*
$date = d_builddate(1,6,2017);
echo datefix($date,'short'),'<br>',datefix2($date);
*/

/*
# find latest prev and update product table
$query = 'SELECT t1.productid,t1.prev,t1.arrivaldate
FROM purchasebatch AS t1
LEFT OUTER JOIN purchasebatch AS t2
  ON t1.productid = t2.productid AND t1.arrivaldate < t2.arrivaldate
WHERE t2.productid IS NULL';
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  echo $main_result[$i]['productid'] . ' ' . $main_result[$i]['prev'] . '<br>';
  if ($main_result[$i]['productid'] > 0)
  {
    $query = 'update product set recent_prev=? where productid=?';
    $query_prm = array($main_result[$i]['prev'],$main_result[$i]['productid']);
    require('inc/doquery.php');
  }
}
*/

#$placement_map = 'custom/' . $_SESSION['ds_customname'] . '_warehousemap_'.$mapid.'.png';
#echo $placement_map;
#echo '<img src="warehouse_map.php" width=600 height=800>';

/*
$query = 'select adjustmentdate from adjustmentgroup where closing=1 and deleted=0 order by adjustmentdate desc limit 1';
$query_prm = array();
require('inc/doquery.php');
echo $query_result[0]['adjustmentdate'];
*/

/*
echo '<img alt="12345" src="barcode.php?size=40&text=12345" width=400><br>
<img alt="abcdef" src="barcode.php?size=40&text=abcdef" width=800><br>
<img alt="gthyuj" src="barcode.php?size=40&text=gthyuj" width=200>';
*/

/*
$filename = 'customfiles/audreytest' . date("Y_m_d_H_i_s") . '.txt';
$file = fopen($filename, "w");
if (!$file) { echo "Cannot create the file!<br>"; exit; }

$writebuffer = '';

$writebuffer = 'hello;i;am;a;test';

fwrite($file, $writebuffer);
fclose($file);

echo '<br><br><p>Fichier <a href="customfiles/' . basename($filename) . '"><font color=blue><i>' . basename($filename) . '</i></font></a> créé.</p>';
?><p>- Cliquer sur le bouton droit de la souris</p>
<p>- Enregistrer la cible sous...</p><?php
*/

/*

if(PHP_INT_SIZE === 8) { echo '64 bit'; }
else { echo '32 bit'; }
echo '<br>test jan1 2050 ' . date('N', strtotime('2050-01-01'));

echo '<br>% test 2400 % 60 = ', 2400%60;
*/

/*
require('inc/func_email.php');
echo 'envoi de mail';
sendEmail('audreymolies@yahoo.fr','test','Ceci est un test','Ceci est un test html.<br> Un passage à la ligne<br>');
*/

/*
echo d_divide(286, 100, 2);
echo myround(d_divide(286, 100, 2));
*/

/*
require('preload/client.php');
echo $clientA[1697];
*/

/*
$query_prm = array();
for ($i=1; $i <= 25; $i ++)
{
  $query = '
  insert into save_name (save_name) values ('.$i.');
  ';
  echo $query . '<br>';
  require('inc/doquery.php');
}
*/

/*
$query_prm = array();
for ($i=6; $i <= 25; $i ++)
{
  $query = '
  create table accountinggroup_save'.$i.' like accountinggroup;
  create table accountingnumber_save'.$i.' like accountingnumber;
  create table accounting_simplified_save'.$i.' like accounting_simplified;
  create table accounting_simplifiedgroup_save'.$i.' like accounting_simplifiedgroup;
  ';
  echo $query . '<br>';
  require('inc/doquery.php');
}
*/

#echo date('Y-m-d H:i:s', PHP_INT_MAX);


#echo '<a href="printcheck.php?amount=12346789" target=_blank>Imprimer cheque</a>';

/*

############## VERIFICATION ADJUSTMENTGROUP DEBIT VS CREDIT

echo '<h2>Vérification Ecritures</h2><br>';
# purposefully not using group by, reading each line
$query = 'select adjustment.adjustmentgroupid,value,debit from adjustment order by adjustmentgroupid';
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
$debit = 0; $credit = 0;
for ($i=0; $i < $num_results_main; $i++)
{
  if ($main_result[$i]['debit'] == 1) { $debit += $main_result[$i]['value']; }
  else { $credit += $main_result[$i]['value']; }
  if ($i == $num_results_main || $main_result[$i]['adjustmentgroupid'] != $main_result[($i+1)]['adjustmentgroupid'])
  {
    #echo '<br>('.$main_result[$i]['adjustmentgroupid'].') D= '.$debit.' C= '.$credit;
    if ($debit != $credit)
    {
      echo '<br>Error with Ecriture '.$main_result[$i]['adjustmentgroupid'].': deleting...';
      $query = 'update adjustmentgroup set deleted=1 where adjustmentgroupid=?';
      $query_prm = array($main_result[$i]['adjustmentgroupid']);
      require('inc/doquery.php'); # TODO also need delettrer and de-reconciliate!
    }
    $debit = 0; $credit = 0;
  }
}

###########################################################

*/

/* INTEGRATION ACCOUNTS CAGEST

# config
$separator = ';';

echo '<h2>acc import</h2>';
if ($_POST['importme'] == 1)
{
  $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
  $i = 0;
  echo '<table class=report>';
  echo '<tr><td>#<td>name';
  while ($line=fgets($fp))
  {
    $i++;
    $lineA = explode($separator, $line);
    $num = $lineA[0]+0;
    $name = $lineA[1];
    $agid = substr($num,0,1);
    
    if ($num > 0)
    {
      echo '<tr><td>' . $num;
      echo '<td>' . $name;
      echo '<td>' . $agid;

      # product insert
      $query = 'insert into accountingnumber (acnumber,acname,accountinggroupid) values (?,?,?)';
      $query_prm = array($num,$name,$agid);
      require('inc/doquery.php');
    }
    
  }
  echo '</table>';
}
else
{
  ?>
  <form enctype="multipart/form-data" method="post" action="testpage.php">
  <table>
  <tr><td>File:</td><td><input type="hidden" name="MAX_FILE_SIZE" value="1000000"><input type="file" name="userfile" size=50></td></tr>
  <tr><td colspan="2" align="left"><input type=hidden name="importme" value="1"><input type=hidden name="systemmenu" value="<?php echo $systemmenu; ?>"><input type="submit" value="Import"></form></td></tr></table><?php
}

*/

/*
$invoiceA = array('279410');

# load accountingnumberids from taxcode
$query = 'select taxcodeid,accountingnumberid,base_accountingnumberid from taxcode order by taxcodeid';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $acctax[$query_result[$i]['taxcodeid']] = $query_result[$i]['accountingnumberid'];
  $base_acctax[$query_result[$i]['taxcodeid']] = $query_result[$i]['base_accountingnumberid'];
}

foreach ($invoiceA as $invoiceid)
{
  $query = 'select clientid,accountingdate,isreturn from invoicehistory where invoiceid=?';
  $query_prm = array($invoiceid);
  require('inc/doquery.php');
  $accountingdate = $query_result[0]['accountingdate'];
  $clientid = $query_result[0]['clientid'];
  if ($query_result[0]['isreturn'] == 1)
  {
    $comment = 'Avoir ' . $invoiceid;
    $debit = 0;
  }
  else
  {
    $comment = 'Facture ' . $invoiceid;
    $debit = 1;
  }
  unset($netA,$vatA,$total);
  $query = 'select lineprice,linevat,linetaxcodeid,accountingnumberid from invoiceitemhistory,product
  where invoiceitemhistory.productid=product.productid and invoiceid=?';
  $query_prm = array($invoiceid);
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $accnumid = $query_result[$i]['accountingnumberid'];
    if ($accnumid == 0)
    {
      $accnumid = $acctax[$query_result[$i]['linetaxcodeid']];
    }
    $base_accnumid = $base_acctax[$query_result[$i]['linetaxcodeid']];
    $netA[$base_accnumid] += $query_result[$i]['lineprice'];
    $vatA[$accnumid] += $query_result[$i]['linevat'];
    $total += ($query_result[$i]['lineprice'] + $query_result[$i]['linevat']);
  }
  if ($total > 0)
  {
    $query = 'insert into adjustmentgroup (userid,adjustmentdate,originaladjustmentdate,adjustmenttime,adjustmentcomment) values (?, ?, curdate(), curtime(), ?)';
    $query_prm = array($_SESSION['ds_userid'], $accountingdate, $comment);
    require('inc/doquery.php');
    $adjustmentgroupid = $query_insert_id;
    $query = 'insert into adjustment (debit,adjustmentgroupid,value,referenceid,accountingnumberid,matchingid) values (?,?,?,?,?,0)';
    $query_prm = array($debit, $adjustmentgroupid, $total, $clientid, 1); # hardcode accountingnumberid=1 for client sales
    require('inc/doquery.php');
    if ($debit == 1) { $debit = 0; }
    else { $debit = 1; }
    foreach ($netA as $id => $value)
    {
      if ($value > 0)
      {
        $query = 'insert into adjustment (debit,adjustmentgroupid,value,accountingnumberid,matchingid) values (?,?,?,?,0)';
        $query_prm = array($debit, $adjustmentgroupid, $value, $id);
        require('inc/doquery.php');
      }
    }
    foreach ($vatA as $id => $value)
    {
      if ($value > 0)
      {
        $query = 'insert into adjustment (debit,adjustmentgroupid,value,accountingnumberid,matchingid) values (?,?,?,?,0)';
        $query_prm = array($debit, $adjustmentgroupid, $value, $id);
        require('inc/doquery.php');
      }
    }        
  }
}
*/

/*
$in_confirmed = '(5,6,8,125,';
$invoiceA = explode(',',substr($in_confirmed,1,-1));
var_dump($invoiceA);
*/

/*
$test = 'abc def';
function d_debugTEST($var)
{
  global $$var;
  echo $var. ' = ' . $$var. '<br>';
}
d_debugTEST('test');
*/

/*

echo $_SERVER['HTTP_HOST'] . '<br>';

$testval = '123 456.78';
echo $testval . '<br>';
echo $testval+0 . '<br>';
echo (int) $testval . '<br>';
echo intval($testval) . '<br>';

*/

# try filter_var()
#$string = preg_replace('/\s+/', '', $string); # remove all whitespace - probably the best solution


/*

  # sorts a query result
  function d_sortresultsNEW(array &$qA, $fieldname, $num) # TODO add array of fields to sort and merge them to one string
  {
    $copyA = $qA;
    for ($i = 0; $i < $num; $i++)
    {
      if (is_array($fieldname))
      {
        $tosortA[$i] = '';
        foreach($fieldname as $part)
        {
          $tosortA[$i] .= $qA[$i][$part];
        }
      }
      else
      {
        $tosortA[$i] = $qA[$i][$fieldname];
      }
    }
    
    #asort($tosortA);
    #$collator = new Collator('root');
    #$collator->asort($tosortA);
    if (extension_loaded('intl') === true)
    {
      collator_asort(collator_create('fr_FR'), $tosortA);
    }
    else
    {
      asort($tosortA);
    }
    
    $i = -1;
    foreach($tosortA as $key => $v)
    {
      $i++;
      $qA[$i] = $copyA[$key];
    }
  }

if (extension_loaded('intl') === true) { echo 'yes'; }
else
{
echo 'no';
phpinfo();
}

$query = 'select productcomment,productname,productid from product limit 50'; # wrongly ordered query
$query_prm = array();
require('inc/doquery.php');

echo '<table class=report>';


echo '<tr><td colspan=4>&nbsp;</td></tr>';
echo '<tr><td colspan=4><b>BY Productcomment:</b></td></tr>';
d_sortresultsNEW($query_result,'Productcomment', $num_results);
for ($i=0; $i < $num_results; $i++)
{
  echo '<tr><td align=right>' . $i . '</td><td>' . $query_result[$i]['productid'] . '</td><td>' . $query_result[$i]['productname'] . '</td><td>' . $query_result[$i]['productcomment'] . '</td></tr>';
}

echo '<tr><td colspan=4>&nbsp;</td></tr>';
echo '<tr><td colspan=4><b>BY Productcomment, productname:</b></td></tr>';
d_sortresultsNEW($query_result, array('productcomment','productname'), $num_results);
for ($i=0; $i < $num_results; $i++)
{
  echo '<tr><td align=right>' . $i . '</td><td>' . $query_result[$i]['productid'] . '</td><td>' . $query_result[$i]['productname'] . '</td><td>' . $query_result[$i]['productcomment'] . '</td></tr>';
}


echo '<tr><td colspan=4>&nbsp;</td></tr>';
echo '<tr><td colspan=4><b>BY Productname, productcomment:</b></td></tr>';
d_sortresultsNEW($query_result, array('productname','productcomment'), $num_results);
for ($i=0; $i < $num_results; $i++)
{
  echo '<tr><td align=right>' . $i . '</td><td>' . $query_result[$i]['productid'] . '</td><td>' . $query_result[$i]['productname'] . '</td><td>' . $query_result[$i]['productcomment'] . '</td></tr>';
}


echo '<tr><td colspan=4>&nbsp;</td></tr>';
echo '<tr><td colspan=4><b>BY Productid, productname:</b></td></tr>';
d_sortresultsNEW($query_result, array('productid','productname'), $num_results);
for ($i=0; $i < $num_results; $i++)
{
  echo '<tr><td align=right>' . $i . '</td><td>' . $query_result[$i]['productid'] . '</td><td>' . $query_result[$i]['productname'] . '</td><td>' . $query_result[$i]['productcomment'] . '</td></tr>';
}

echo '<tr><td colspan=4>&nbsp;</td></tr>';
echo '<tr><td colspan=4><b>BY Productname, productid:</b></td></tr>';
d_sortresultsNEW($query_result, array('productname','productid'), $num_results);
for ($i=0; $i < $num_results; $i++)
{
  echo '<tr><td align=right>' . $i . '</td><td>' . $query_result[$i]['productid'] . '</td><td>' . $query_result[$i]['productname'] . '</td><td>' . $query_result[$i]['productcomment'] . '</td></tr>';
}

echo '<tr><td colspan=4>&nbsp;</td></tr>';
echo '<tr><td colspan=4><b>BY Productcomment, productid:</b></td></tr>';
d_sortresultsNEW($query_result, array('Productcomment','productid'), $num_results);
for ($i=0; $i < $num_results; $i++)
{
  echo '<tr><td align=right>' . $i . '</td><td>' . $query_result[$i]['productid'] . '</td><td>' . $query_result[$i]['productname'] . '</td><td>' . $query_result[$i]['productcomment'] . '</td></tr>';
}

echo '<tr><td colspan=4>&nbsp;</td></tr>';
echo '<tr><td colspan=4><b>BY Productid, productcomment:</b></td></tr>';
d_sortresultsNEW($query_result, array('Productid','productcomment'), $num_results);
for ($i=0; $i < $num_results; $i++)
{
  echo '<tr><td align=right>' . $i . '</td><td>' . $query_result[$i]['productid'] . '</td><td>' . $query_result[$i]['productname'] . '</td><td>' . $query_result[$i]['productcomment'] . '</td></tr>';
}

echo '<tr><td colspan=4>&nbsp;</td></tr>';
echo '<tr><td colspan=4><b>BY Productcomment, productname, productid:</b></td></tr>';
d_sortresultsNEW($query_result, array('Productcomment','productname','productid'), $num_results);
for ($i=0; $i < $num_results; $i++)
{
  echo '<tr><td align=right>' . $i . '</td><td>' . $query_result[$i]['productid'] . '</td><td>' . $query_result[$i]['productname'] . '</td><td>' . $query_result[$i]['productcomment'] . '</td></tr>';
}

echo '<tr><td colspan=4>&nbsp;</td></tr>';
echo '<tr><td colspan=4><b>BY Productcomment, productname, productid:</b></td></tr>';
d_sortresultsNEW($query_result, array('productcomment','productname','productid'), $num_results);
for ($i=0; $i < $num_results; $i++)
{
  echo '<tr><td align=right>' . $i . '</td><td>' . $query_result[$i]['productid'] . '</td><td>' . $query_result[$i]['productname'] . '</td><td>' . $query_result[$i]['productcomment'] . '</td></tr>';
}

echo '<tr><td colspan=4>&nbsp;</td></tr>';
echo '<tr><td colspan=4><b>BY Productid, productcomment, productname:</b></td></tr>';
d_sortresultsNEW($query_result, array('productid','productcomment','productname'), $num_results);
for ($i=0; $i < $num_results; $i++)
{
  echo '<tr><td align=right>' . $i . '</td><td>' . $query_result[$i]['productid'] . '</td><td>' . $query_result[$i]['productname'] . '</td><td>' . $query_result[$i]['productcomment'] . '</td></tr>';
}

$query = 'select clientname,townname,islandname from client,town,island
where client.townid=town.townid and town.islandid=island.islandid order by clientid limit 50 '; # wrongly ordered query
$query_prm = array();
require('inc/doquery.php');

echo '<table class=report><tr><td colspan=4><b>UNSORTED:</b></td></tr>';
for ($i=0; $i < $num_results; $i++)
{
  echo '<tr><td align=right>' . $i . '</td><td>' . $query_result[$i]['clientname'] . '</td><td>' . $query_result[$i]['townname'] . '</td><td>' . $query_result[$i]['islandname'] . '</td></tr>';
}

echo '<tr><td colspan=4><b>BY CLIENTNAME:</b></td></tr>';
d_sortresultsNEW($query_result, 'clientname', $num_results);
for ($i=0; $i < $num_results; $i++)
{
  echo '<tr><td align=right>' . $i . '</td><td>' . $query_result[$i]['clientname'] . '</td><td>' . $query_result[$i]['townname'] . '</td><td>' . $query_result[$i]['islandname'] . '</td></tr>';
}

echo '<tr><td colspan=4><b>BY ISLANDNAME, CLIENTNAME:</b></td></tr>';
d_sortresultsNEW($query_result, array('islandname','clientname'), $num_results);
for ($i=0; $i < $num_results; $i++)
{
  echo '<tr><td align=right>' . $i . '</td><td>' . $query_result[$i]['clientname'] . '</td><td>' . $query_result[$i]['townname'] . '</td><td>' . $query_result[$i]['islandname'] . '</td></tr>';
}

echo '<tr><td colspan=4><b>BY ISLANDNAME, TOWNNAME, CLIENTNAME:</b></td></tr>';
d_sortresultsNEW($query_result, array('islandname','townname','clientname'), $num_results);
for ($i=0; $i < $num_results; $i++)
{
  echo '<tr><td align=right>' . $i . '</td><td>' . $query_result[$i]['clientname'] . '</td><td>' . $query_result[$i]['townname'] . '</td><td>' . $query_result[$i]['islandname'] . '</td></tr>';
}

echo '</table>';

*/

/*

echo '<div class="myblock">';
echo '<h2>Test:</h2>';

$myvar = "<b>&n<br>'" . " '" . ' "';
$myvar = $myvar . '汉字' . 'ñ';
$myvar = $myvar . ' <script>this is a script</script>';
$myvar = $myvar . ' &#60;br&#62;after break';
$myvar = $myvar . '</b>';

echo '<p>myvar= ' . $myvar;
echo '<p>d_output(myvar)= ' . d_output($myvar);
echo '<p>allowtags= ' . d_output($myvar, TRUE);

echo '<br><br>mb_internal_encoding: ' . mb_internal_encoding();

echo '<br><br><p>mb_strlen(abc)= ' . mb_strlen("abc");
echo '<p>mb_strlen(éèà)= ' . mb_strlen("éèà");
echo '<p>mb_mb_strlen(abc)= ' . mb_mb_strlen("abc");
echo '<p>mb_mb_strlen(éèà)= ' . mb_mb_strlen("éèà");

echo '</div>';
*/



/*
echo 'debug test d_sortresults()<br><br>Before<br>';
$query = 'select productname,productid,productfamilyname,product.discontinued from product,productfamily where product.productfamilyid=productfamily.productfamilyid and productid<26';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  echo $i . ' ' . $query_result[$i]['productname'] . ' &nbsp; ' . $query_result[$i]['productfamilyname'] . '<br>';
}
echo '<br><br>After:<br>';
d_sortresults($query_result, 'productfamilyname', $num_results);
d_sortresults($query_result, 'productname', $num_results);
for ($i=0; $i < $num_results; $i++)
{
  echo $i . ' ' . $query_result[$i]['productname'] . ' &nbsp; ' . $query_result[$i]['productfamilyname'] . '<br>';
}
*/

/*
echo 'debug test d_sortresults()<br><br>';
$query = 'select concat(employeename," ",employeefirstname) as employeename,employeeid,employeecategoryname,employee.deleted from employee,employeecategory where employee.employeecategoryid=employeecategory.employeecategoryid';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  echo $i . ' ' . $query_result[$i]['employeename'] . ' &nbsp; ' . $query_result[$i]['employeecategoryname'] . '<br>';
}
echo '<br><br>';
d_sortresults($query_result, 'employeecategoryname', $num_results);
d_sortresults($query_result, 'employeename', $num_results);
for ($i=0; $i < $num_results; $i++)
{
  echo $i . ' ' . $query_result[$i]['employeename'] . ' &nbsp; ' . $query_result[$i]['employeecategoryname'] . '<br>';
}
*/

/*
foreach($sortA as $key => $value) # or for loop
{
  echo "$name[$key] = $value<br>";
}
*/

/*
$query = 'select tradstring from trad where lang=? and string=?';
      $query_prm = array($_SESSION['ds_language'], 'selectall');
      require('inc/doquery.php');
      echo $num_results;
      echo $query_result[0]['tradstring'];
*/

#echo d_trad('selectall');

#echo date('d/m/Y == H:i:s');

/*
# correct way to sort results from DB
echo 'DEBUG<br><br>before sort:<br>';

$name[0] = 'Tom'; $date[0] = '2013-02-05';
$name[1] = 'Peter'; $date[1] = '2013-01-05';
$name[2] = 'Alice'; $date[2] = '2012-12-12';
$name[3] = 'Bob'; $date[3] = '2013-03-01';

foreach($date as $key => $value)
{
  echo "$name[$key] = $value<br>";
}

asort($date);
echo '<br>after sort:<br>';
foreach($date as $key => $value)
{
  echo "$name[$key] = $value<br>";
}
*/

/* moving to table
if ($_SESSION['ds_userid'] == 1) #debug
{
  echo '<style>
  form.testme {
  padding: 0.5em;
  border: none;
  background: '. $_SESSION['ds_bgcolor'] .';
  }
  
  input[type=submit] {
    color: '. $_SESSION['ds_linkcolor'] .';
    background:none!important;
    border:none; 
    padding:0!important;
    font-weight: bold;
    font-size: 200%;
  }
  </style>';
  echo '<form class="testme" method="post" action="https://www.tem-saas.com/temsupport/" target=_blank>
  <input type=hidden name="key" value="yMqfqT7a7kF9vhfhac2GhAF9">
  <input type=hidden name="userid" value="' . $_SESSION['ds_userid'] . '">
  <input type=hidden name="customname" value="' . $_SESSION['ds_customname'] . '">
  <input type=hidden name="name" value="' . $_SESSION['ds_name'] . '">
  <input type="submit" value="Site Web SUPPORT technique"></form><br>';
}
*/

/*

echo 'Test function myround()<br><br>';

function showmyround($number,$precision)
{
  for ($i=0;$i<=$precision;$i++)
  {
    echo 'myround(' .$number .',' . $i .') = ' . myround($number,$i) . '<br>';
  }
  
  echo '<br>';
}

showmyround(100.44,2);  
showmyround(100.49,2);
showmyround(100.50,2);
showmyround(100.99,2);
showmyround(555.55,2);
showmyround(999.99,2);

showmyround(-100.44,2);  
showmyround(-100.49,2);
showmyround(-100.50,2);
showmyround(-100.99,2);
showmyround(-555.55,2);
showmyround(-999.99,2);

showmyround(100.4444444,7);  
showmyround(100.4444449,7);
showmyround(100.5555555,7);
showmyround(100.9999999,7);   
showmyround(555.5555555,7); 
showmyround(555.555555,7); 
showmyround(999.999999,7);

showmyround(-100.4444444,7);  
showmyround(-100.4444449,7); 
showmyround(-100.9999999,7);
showmyround(-100.9999999,7);   
showmyround(-555.5555555,7); 
showmyround(-555.555555,7);  
showmyround(-999.999999,7); 

*/

#phpinfo();

#echo 'realpath='.dirname($_SERVER['DOCUMENT_ROOT']) . '/../tmp';

#echo mb_stripos('abcd', 'c', 0);
/*
<script src="jq/jquery.js" type="text/javascript"></script>
<script src="jq/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
 
$(document).ready(function(){
	var ac_config = {
		source: "demo_cities.php",
		select: function(event, ui){
			$("#city").val(ui.item.city);
			$("#state").val(ui.item.state);
			$("#zip").val(ui.item.zip);
		},
		minLength:1
	};
	$("#city").autocomplete(ac_config);
});
</script>
<form action="#" method="post">
	 <p><label for="city">City</label><br />
		 <input type="text" name="city" id="city" value="" /></p>
	 <p><label for="state">State</label><br />
		 <input type="text" name="state" id="state" value="" /></p>
	 <p><label for="zip">Zip</label><br />
	 	 <input type="text" name="zip" id="zip" value="" /></p>
</form>
*/

#echo "<html>Path to PHP executable ".$_SERVER['_']."<br>";
#echo "Path to PHP bin directory ".PHP_BINDIR."<br>";
/*
$_SESSION['debug'] = 1; # log actual SQL errors

$query = 'select nothigg from wring eroooooooor';
require('inc/doquery.php');
*/
/*
$w = stream_get_wrappers();
echo 'openssl: ',  extension_loaded  ('openssl') ? 'yes':'no', "\n";
echo 'http wrapper: ', in_array('http', $w) ? 'yes':'no', "\n";
echo 'https wrapper: ', in_array('https', $w) ? 'yes':'no', "\n";
echo 'wrappers: ', var_dump($w);
*/
/*
require('inc/standard.php');
$query = 'SELECT DISTINCT TABLE_NAME 
    FROM INFORMATION_SCHEMA.tables
    WHERE TABLE_SCHEMA="dauphin_dev"';
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0;$i<$num_results_main;$i++)
{
  echo 'alter table ' . $main_result[$i]['TABLE_NAME'] . ' add index ts (ts);<br>';
}
*/
/*
$pizza  = "piece1 piec#!NC!#e2 piece3 pie#!NC!#ce4 piece5 piece6";
$pieces = explode("#!NC!#", $pizza);
var_dump($pieces);
*/
#echo preg_replace('/[\x00-\x1F\x80-\x9F]/u', '', ' spec àçfe f&"#'); remove non UTF8
/*
require('inc/standard.php');
$query = 'SELECT DISTINCT TABLE_NAME 
    FROM INFORMATION_SCHEMA.tables
    WHERE TABLE_SCHEMA="dauphin_dev"';
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0;$i<$num_results_main;$i++)
{
  echo 'alter table ' . $main_result[$i]['TABLE_NAME'] . ' add column ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;<br>';
}
*/
/*
require('inc/standard.php');
$query = 'SHOW CREATE TABLE bank';
$query_prm = array();
try
{
  if (isset($dauphin_port) && $dauphin_port > 0) { $connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';port=' . $dauphin_port . ';dbname=' . $dauphin_instancename; }
  else { $connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename; }
  $dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
  $dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true); # only for mysql
  $sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
  $sth_temp->execute();
  $sth_temp = $dbh_temp->prepare($query);
  $sth_temp->execute($query_prm);
  $query_result = $sth_temp->fetchAll(PDO::FETCH_ASSOC);
  $num_results = $sth_temp->rowCount();
  $dbh_temp = NULL;
}
catch(PDOException $e_temp)
{
  echo $e_temp->getMessage();
}
for ($i=0; $i<$num_results; $i++)
{
  echo '<br>'. $query_result[$i]['Create Table'];
}
*/

#curl_init();

#phpinfo();

/*
require('inc/standard.php');
$query = 'select clientname from client where clientid=2140 limit 1';
$query_prm = array($invoiceid);
require('inc/doquery.php');
$clientname = $query_result[0]['clientname'];
echo $clientname;
$length = strlen($clientname);
for ($i=0; $i<$length; $i++)
{
  echo '<br>'. ord($clientname[$i]);
}
*/
/*
require ('inc/standard.php');
$query = 'select invoice.invoiceid from invoice,invoicehistory where invoice.invoiceid=invoicehistory.invoiceid limit 1';
$query_prm = array($invoiceid);
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0;$i<$num_results_main;$i++)
{
  $invoiceid = $main_result[0]['invoiceid'];
  $query = 'select greatest(max(invoice.invoiceid),max(invoicehistory.invoiceid)) as newval from invoice,invoicehistory;';
  $query_prm = array();
  require('inc/doquery.php');
  $newval = $query_result[0]['newval']+1;
  $query ='INSERT INTO invoice SELECT * FROM invoice AS iv WHERE iv.invoiceid=? ON DUPLICATE KEY UPDATE invoiceid=?';
  $query_prm = array($invoiceid,$newval); echo $query . '<br>' . $invoiceid . '<br>' . $newval . '<br>';
  require('inc/doquery.php');
  $invoiceid = $newval;
}
*/
/*

### test pagination THIS WORKS

require ('inc/standard.php');
require ('inc/top.php');

echo '<div style="position: relative;">';
echo '<div class="tr2">';
echo '<p align=center><b>';
echo $_SESSION['ds_term_accountingdate'];
echo '</b></p><hr><p align=center><b>' . datefix2($row['accountingdate']) . '</b></p>';
echo '</div>';
echo '<p>1 text test text test text test text test text test text test text test </p>';
echo '</div>';

echo '<p class=breakhere></p>';

echo '<div style="position: relative;">';
echo '<div class="tr2">';
echo '<p align=center><b>';
echo $_SESSION['ds_term_accountingdate'];
echo '</b></p><hr><p align=center><b>' . datefix2($row['accountingdate']) . '</b></p>';
echo '</div>';
echo '<p>2 text test text test text test text test text test text test text test </p>';
echo '</div>';

echo '<p class=breakhere></p>';

echo '<div style="position: relative;">';
echo '<div class="tr2">';
echo '<p align=center><b>';
echo $_SESSION['ds_term_accountingdate'];
echo '</b></p><hr><p align=center><b>' . datefix2($row['accountingdate']) . '</b></p>';
echo '</div>';
echo '<p>3 text test text test text test text test text test text test text test </p>';
echo '</div>';

require ('inc/bottom.php');

*/

#phpinfo();
/*
require('inc/standard.php');
$query = 'select productid from product where producttypeid=6';
echo $query.'<br>';
$query_prm = array();
require ('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0;$i<$num_results_main;$i++)
{
  $pid = $main_result[$i]['productid'];
  $query = 'update endofyearstock set stock=stock*1000 where productid="'.$pid.'" and year=2012';
  echo $query.'<br>';
  $query_prm = array();
  require ('inc/doquery.php');
}
*/
/*

require('inc/standard.php');

$lines = file('vvcodes.txt', FILE_IGNORE_NEW_LINES);
foreach ($lines as $key => $value)
{
  $placement = substr($value,0,8);
  if (substr($value,0,1) == 'V')
  {
    $query = 'insert into placement (placementname,warehouseid) values (?,1)';
    $query_prm = array($placement);
    require('inc/doquery.php');
    echo '"'.$placement .'"<br>';
  }
}
*/

/*

echo 'Starting';

$datestart = strtotime('2011-01-01');//you can change it to your timestamp;
$dateend = strtotime('2012-12-31');//you can change it to your timestamp;
$daystep = 86400;
$datebetween = abs(($dateend - $datestart) / $daystep);
$randomday = rand(0, $datebetween);
echo date("Y-m-d", $datestart + ($randomday * $daystep)) . "\n";

echo 'Done';
exit;

*/

/*
require ('inc/standard.php');
echo 'Checking invoice sequentiality.';
$query = 'select invoiceid from invoice union select invoiceid from invoicehistory order by invoiceid';
$query_prm = array();
require ('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0;$i<$num_results_main;$i++)
{
  if (($lastid+1) != $query_result[$i]['invoiceid'] && $i != 0)
  {
    echo '<br>' . $lastid . ' not found!';
  }
  $lastid = $query_result[$i]['invoiceid'];
}
# dont bother to check last invoice
echo '<br>Done.';
*/

/*

echo '<html>test barcode<br>';

require_once("Image/Barcode.php");
$bc = new Image_Barcode;
$bc->draw("abc123", "Code39", "png");


exit;

echo "Loading";
ob_flush();
flush();

sleep(10); // Simulate long process time
echo "Finished";
*/

require ('inc/bottom.php');

?>
