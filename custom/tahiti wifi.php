<?php

# Build web page
require ('inc/standard.php');
require ('inc/top.php');
require ('inc/logo.php');
require ('inc/menu.php');

# table
?>
</div><div id="wrapper">
<title>Tahiti Wifi</title>
<div id="leftmenu">
  <div id="selectactionbar">
    <div class="selectaction">
      <?php
      if ($_SESSION['ds_systemaccess'])
      {
        echo '&nbsp; <a href="custom.php?custommenu=import">Import clients Planyo</a><br>';
      }
      ?>
      <br>
    </div>
  </div>

<?php
require ('inc/copyright.php');
?>
</div><div id="mainprogram">
<?php

$custommenu = "";
if (isset($_GET['custommenu'])) { $custommenu = $_GET['custommenu']; }
if (isset($_POST['custommenu'])) { $custommenu = $_POST['custommenu']; }
$custommenu = d_safebasename($custommenu);

$currentstep = 0;
if (isset($_POST['step'])) { $currentstep = $_POST['step']+0; }

# Go to the menuitem
switch($custommenu)
{
  case 'import':
  
  $separator = ',';

  echo '<h2>Clients importés de Planyo:</h2>';

  if ($_POST['importme'] == 1)
  {
    $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
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
      for ($x=0; $x < 14; $x++)
      {
        $lineA[$x] = trim($lineA[$x],'"');
        $lineA[$x] = trim($lineA[$x]);
        echo '<td>',' [',$x,'] ',$lineA[$x];
      }
      
      if ($i > 1)
      {
        $clientcode = $lineA[0];
        $clientfirstname = $lineA[1];
        $clientname = d_encode($lineA[2]);
        $email = $lineA[3]; # should be unique
        $countryname = $lineA[4];
        $address = $lineA[5];
        $town_name = $lineA[6]; # both in PF and not, need new field outside PF
        $postalcode = $lineA[7];
        $postaladdress = $lineA[8]; # "Département / Canton / Province"
        $telephone = $lineA[9];
        $cellphone = $lineA[10];
        $clientcomment = $lineA[11];
        $clientfield1 = $lineA[12]; # Réservations (int)
        $client_customdate1 = substr($lineA[13],0,10); # Réservations ajoutées récemment (10 first chars = date)
        # HERE
        
        $use_loyalty_points = 0;       
        $loyalty_start = 0;
        $notahiti = '';
        $contact = '';
        $fax = '';
        $townid = 1;
        
        $countryid = 156;
        $query = 'select countryid from country where countryname=?';
        $query_prm = array($countryname);
        require('inc/doquery.php');
        if ($num_results) { $countryid = $query_result[0]['countryid']; }
        else
        {
          echo 'cannot find country';
        }
        
        $clienttermid = 1;
        $clientcategoryid = 1;
        $companytypename = '';
        
        if ($email != '')
        {
          $query = 'select clientid from client where email=?';
          $query_prm = array($email);
          require('inc/doquery.php');
          if ($num_results)
          {
            $clientid = $query_result[0]['clientid'];
            $query = 'update client set clientfirstname=?,clientname=?,countryid=?,address=?,town_name=?,postalcode=?,postaladdress=?,telephone=?,cellphone=?,clientcomment=?,clientfield1=?,client_customdate1=? where clientid=?';
            $query_prm = array($clientfirstname,$clientname,$countryid,$address,$town_name,$postalcode,$postaladdress,$telephone,$cellphone,$clientcomment,$clientfield1,$client_customdate1,$clientid);
            require('inc/doquery.php');
          }
          else
          {
            $query = 'insert into client (client_customdate1,clientfield1,clientcomment,town_name,clientfirstname,cellphone,fax,companytypename,clientname,tahitinumber,contact,telephone,email,townid,clientcode,postalcode,address,postaladdress,loyalty_start,clienttermid,countryid,clientcategoryid)
            values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
            $query_prm = array($client_customdate1,$clientfield1,$clientcomment,$town_name,$clientfirstname,$cellphone,$fax,$companytypename,$clientname,$notahiti,$contact,$telephone,$email,$townid,$clientcode,$postalcode,$address,$postaladdress,$loyalty_start,$clienttermid,$countryid,$clientcategoryid);
            require('inc/doquery.php');
          }
        }
        
      }
      
      
    }
    echo '</table>';
  }
  else
  {
    ?>
    <form enctype="multipart/form-data" method="post" action="custom.php">
    <table>
    <tr><td>File:</td><td><input type="hidden" name="MAX_FILE_SIZE" value="1000000"><input type="file" name="userfile" size=50></td></tr>
    <tr><td colspan="2" align="left"><input type=hidden name="importme" value="1"><input type=hidden name="custommenu" value="<?php echo $custommenu; ?>"><input type="submit" value="Import"></form></td></tr></table><?php
  }

  break;

  default:

  break;
}
?>

<?php
require ('inc/bottom.php');
?>