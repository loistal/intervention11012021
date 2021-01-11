 <?php

#ini_set('display_errors', 'On');
#error_reporting(E_ALL);

$txtfile = 0;
if (isset($_POST['txtfile'])) { $txtfile = (int) $_POST['txtfile']; }

# Build web page
require ('inc/standard.php');
if ($txtfile == 1)
{
  /* ?><html>
  <head>
  <meta http-equiv=content-type content="text/plain; charset=UTF-8">
  </head>
  <body><?php */
  header("Content-Disposition: attachment; filename=yourfile.txt");
}
else
{
  require ('inc/top.php');
  require ('inc/logo.php');
  require ('inc/menu.php');


# table
?>
</div><div id="wrapper">
<title>TEM Wing Chong</title>
<div id="leftmenu">
<div id="selectactionbar">
<?php # ONLY ACTIVATE DURING INVENTORY
if (1==1||$_SESSION['ds_systemaccess'])
{
  ?><div class="selectaction">
    <div class="selectactiontitle">Tout utilisateurs:</div>  
    <div class="selectactionlist">
      <a href="custom.php?custommenu=setplacement">Comptage stock</a><br>
      <a href="custom.php?custommenu=palletcountreport">Rapport comptage</a><br>
    </div>
  </div><?php
}
?>
<?php
if ($_SESSION['ds_userid'] == 1
|| $_SESSION['ds_userid'] == 75
|| $_SESSION['ds_userid'] == 29
|| $_SESSION['ds_userid'] == 81
|| $_SESSION['ds_userid'] == 31
|| $_SESSION['ds_userid'] == 26
|| $_SESSION['ds_userid'] == 27
)
{
  /*select username from usertable where userid=75
or userid=29
or userid=81
or userid=31
or userid=26
or userid=27*/
  ?>
  <div class="selectaction">
    <div class="selectactiontitle">6 vendeurs (par id):</div>  
    <div class="selectactionlist">
      <a href="custom.php?custommenu=productcatalogue_mod">Catalogue Produit [Wing Chong]</a><br>
      <a href="custom.php?custommenu=fc">Feuille de commande</a><br>
    </div>
  </div>
  <?php 
}

$query = 'select employeecategoryid from employee where employeeid=?';
$query_prm = array($_SESSION['ds_myemployeeid']);
require('inc/doquery.php');
if ($num_results
&& $query_result[0]['employeecategoryid'] != 1
&& $_SESSION['ds_userrepresentsclientid'] == 0) # exclude Nestlé
{
  ?>
  <div class="selectaction">
    <div class="selectactiontitle">Employés (hors N):</div>  
    <div class="selectactionlist">
      <a href="custom.php?custommenu=productaction">Évènement produit [Wing Chong]</a><br>
      <a href="custom.php?custommenu=showproductactions">Rapport évènement produit [Rapports]</a><br>
    </div>
  </div>
  <?php 
}

if ($_SESSION['ds_accountingaccess'])
{?>
  <div class="selectaction">
    <div class="selectactiontitle">Accès Compta</div>  
    <div class="selectactionlist">
      <a href="custom.php?custommenu=tosage2">Export SAGE</a><br>
      <a href="custom.php?custommenu=redosage">Re-faire export</a><br>
      <a href="custom.php?custommenu=notexported">Non-exporté</a>
      <!--<a href="custom.php?custommenu=tosage">(ancien)</a><br>'; -->
    </div>
  </div>
<?php 
}?>

<div class="selectaction">
  <?php if ($_SESSION['ds_systemaccess'])
  {?>
    <div class="selectactiontitle">Accès Système</div>  
    <div class="selectactionlist">
      <a href="custom.php?custommenu=bdlvalue">Valeur BdL</a><br>
      <!-- <a href="custom.php?custommenu=stockwc">Stock WC Fin année</a><br> -->
      <a href="custom.php?custommenu=cdj">Caisse du jour</a><br>
      <a href="custom.php?custommenu=nestledaily">Nestlé vente/jour (txt)</a><br>
      <a href="custom.php?custommenu=valstock3112">Val stock 31/12</a><br>
      <a href="custom.php?custommenu=rfa">RFA</a><br>
      <a href="custom.php?custommenu=cadstockncsv">Nestlé Cad CSV</a><br>
      <a href="custom.php?custommenu=stockval">Valeur Stock</a><br>
      <a href="custom.php?custommenu=promnestle1">Promo Nestlé</a><br>
      <a href="custom.php?custommenu=nestlevolume">Volume Nestlé</a><br>
      <!-- <a href="custom.php?custommenu=1client">1client</a><br> -->
      <a href="custom.php?custommenu=tracing">Tracabilité</a><br>
      <!-- <a href="custom.php?custommenu=nestlebdl">Rapport BdL Nestlé</a><br> -->
      <!-- <a href="custom.php?custommenu=confirmbdl">Confirmer BdL Nestlé</a><br> -->
      <a href="custom.php?custommenu=sohreport">SOH report (txt)</a><br>
      <a href="custom.php?custommenu=purchasebatchreport">Lots de stock [Rapports]</a><br>
      <a href="custom.php?custommenu=prodcat">Catalogue Produit [Wing Chong]</a><br>
      <a href="custom.php?custommenu=fc">Feuille de commande</a><br>
      <a href="custom.php?custommenu=reportplacement">Rapport date comptage</a><br>
      <a href="reportwindow.php?report=preparationinterface" target=_blank>Interface Préparation</a><br>
    </div>
  <?php 
  }
  elseif ($_SESSION['ds_restrictbyplanning'])
  {
    echo '<a href="custom.php?custommenu=fc">Feuille de commande</a><br>';
  }

?></div><?php
if ($_SESSION['ds_systemaccess'] || $_SESSION['ds_userid'] == 13 || $_SESSION['ds_userid'] == 63
|| $_SESSION['ds_userid'] == 65 || $_SESSION['ds_userid'] == 75)
{?>
  <div class="selectaction">
    <div class="selectactiontitle">Accès Système<br>+Ronald,AssCom,Lara,CelineB<br></div>  
    <div class="selectactionlist">
      <a href="custom.php?custommenu=sellbydatereal">Date limite de vente [Rapports]</a><br>
      <a href="custom.php?custommenu=prodclimonth">Produit / client / mois [Rapports]</a><br>
    </div>
  </div>
<?php 
}
if ($_SESSION['ds_systemaccess'] || $_SESSION['ds_userid'] == 7 || $_SESSION['ds_userid'] == 3)
{ ?>
  <div class="selectaction">
    <div class="selectactiontitle">Accès Système<br>+MC+Vaimiti<br></div>  
    <div class="selectactionlist">   
      <a href="custom.php?custommenu=insurancereport">Rapport assurance</a><br>
    </div>
  </div>
<?php
}
if ($_SESSION['ds_systemaccess'] || $_SESSION['ds_userid'] == 7)
{ ?>
  <div class="selectaction">
    <div class="selectactiontitle">Accès Système<br>+MC<br></div>  
    <div class="selectactionlist">
      <a href="custom.php?custommenu=modinvoicedate">Modif date fact</a><br>
    </div>
  </div>
<?php
}

if ($_SESSION['ds_systemaccess'] || $_SESSION['ds_userid'] == 65)
{?>
  <div class="selectaction">
    <div class="selectactiontitle">Accès Système +Lara<br></div>  
    <div class="selectactionlist">
      <a href="custom.php?custommenu=promo">Promotions [Rapports]</a><br>
      <a href="custom.php?custommenu=showproductactions">Rapport Évènements Produit [Rapports]</a><br>
      <a href="custom.php?custommenu=productcatalogue">Catalogue Produit [Rapports]</a><br>
      <!-- <a href="custom.php?custommenu=mod_prod">Modif Produit</a><br> -->
      <!-- <a href="custom.php?custommenu=salespricelog">Historique Prix [Rapports]</a><br> -->
    </div>
  </div>
<?php 
}

if ($_SESSION['ds_systemaccess'] || $_SESSION['ds_userid'] == 65 || $_SESSION['ds_userid'] == 75)
{?>
  <div class="selectaction">
    <div class="selectactiontitle">Accès Système +Lara,CelineB<br></div>  
    <div class="selectactionlist">
      <a href="custom.php?custommenu=nosales">Produits sans ventes [Rapports]</a><br>
    </div>
  </div>
<?php 
}

if ($_SESSION['ds_userid'] == 75 || $_SESSION['ds_userid'] == 1)
{?>
  <div class="selectaction">
  <div class="selectactiontitle"><?php echo d_trad('planning'); ?></div>
  <div class="selectactionlist"> 
  <a class="leftmenu" href="custom.php?custommenu=planning"><?php echo d_trad('add'); ?></a><br>
  <a class="leftmenu" href="custom.php?custommenu=planningform&actionform=admin"><?php echo d_trad('modify'); ?></a><br>
  <?php
  echo '<a class="leftmenu" href="custom.php?custommenu=planningform&actionform=reportwindow">' . d_trad('report') . '</a><br>';
  ?>
  <a class="leftmenu" href="custom.php?custommenu=calendarform"><?php echo d_trad('calendar'); ?></a><br>
  </div>
  </div>
<?php 
}

if ($_SESSION['ds_userid'] == 1)
{
  #echo '<br><br><br>&nbsp; <a href="custom.php?custommenu=mapcoord_import">Import map coord</a><br>';
  #echo '<br><br><br>&nbsp; <a href="custom.php?custommenu=barcodes">Barcodes for VA</a><br>';
  #echo '<a href="custom.php?custommenu=transferstock">[stockfinannée]</a><br>';
}
?>
</div><br>
<?php
require ('inc/searchbox.php');
require ('inc/copyright.php');
?>
</div><div id="mainprogram">
<?php

}

$custommenu = "";
if (isset($_GET['custommenu'])) { $custommenu = $_GET['custommenu']; }
if (isset($_POST['custommenu'])) { $custommenu = $_POST['custommenu']; }
$custommenu = d_safebasename($custommenu);

$currentstep = 0;
if (isset($_POST['step'])) { $currentstep = $_POST['step']+0; }

# Go to the menuitem
switch($custommenu)
{
  case 'planning': # copy
  if (!isset($simple_form)) { $simple_form = 0; $action = 'admin.php'; }
  else { $action = 'sales.php'; }
  $action = 'custom.php';

  $modplanningid = $_POST['modplanningid'];
  if(!isset($modplanningid)){$modplanningid = $_GET['modplanningid'];}

  if ($modplanningid > 0)
  {
    $planningid = $modplanningid+0;
    $saveme = 0;
    $query = 'select * from planning where planningid=?';
    $query_prm = array($planningid);
    require('inc/doquery.php');
    $planningstart = $query_result[0]['planningstart'];
    $planningstop = $query_result[0]['planningstop'];
    $planningtimestart = $query_result[0]['planningtimestart'];
    $planningtimestop = $query_result[0]['planningtimestop'];
    $planningdate = $query_result[0]['planningdate'];
    $periodic = $query_result[0]['periodic'];
    $planningname = $query_result[0]['planningname'];
    $planningcomment = $query_result[0]['planningcomment'];
    $periodic_spec_weekly = $query_result[0]['periodic_spec'];
    $periodic_spec_monthly = $query_result[0]['periodic_spec'];
    $dayofweek = $query_result[0]['dayofweek'];
    $day_monthly = mb_substr($planningdate,8,2)+0;
    $day_yearly = mb_substr($planningdate,8,2)+0;
    $month_yearly = mb_substr($planningdate,5,2)+0;
    $deleted = $query_result[0]['deleted'];
    if ($simple_form)
    {
      $query = 'select clientid from planning_client where linenr=1 and planningid=?';
      $query_prm = array($planningid);
      require('inc/doquery.php');
      $simple_form_clientid = $query_result[0]['clientid'];
    }
  }
  else
  {
    $planningid = $_POST['planningid']+0;
    $saveme = $_POST['saveme']+0;
    $datename = 'planningstart';
    require('inc/datepickerresult.php');
    if (!isset($_POST[$datename])) { $$datename = $_SESSION['ds_startyear'].'-01-01'; }
    $planningtimestart = $_POST['planningtimestart'];
    $datename = 'planningstop';
    require('inc/datepickerresult.php');
    if (!isset($_POST[$datename])) { $$datename = $_SESSION['ds_endyear'].'-01-01'; }
    $planningtimestop = $_POST['planningtimestop']; if ($planningtimestop < $planningtimestart) { $planningtimestop = $planningtimestart; }
    $datename = 'planningdate';
    require('inc/datepickerresult.php');
    $periodic = $_POST['periodic']+0;
    $planningname = $_POST['planningname'];
    $planningcomment = $_POST['planningcomment'];
    $periodic_spec_weekly = $_POST['periodic_spec_weekly']+0;
    $dayofweek = $_POST['dayofweek']+0;
    $day_monthly = $_POST['day_monthly']+0;
    $periodic_spec_monthly = $_POST['periodic_spec_monthly']+0;
    $day_yearly = $_POST['day_yearly']+0;
    $month_yearly = $_POST['month_yearly']+0;
    $deleted = $_POST['deleted']+0;
  }

  if ($saveme)
  {
    if ($periodic == 1) { $periodic_spec = $periodic_spec_weekly;}
    else { $periodic_spec = $periodic_spec_monthly; }
    if ($periodic == 2 || $periodic == 3)
    {
      $planningdate_year = mb_substr($planningdate,0,4);
      $planningdate_month = mb_substr($planningdate,5,2);
      if ($periodic == 2)
      {
        $planningdate_day = $day_monthly;
      }
      elseif ($periodic == 3)
      {
        $planningdate_month = $month_yearly;
        $planningdate_day = $day_yearly;
      }
      $planningdate = d_builddate($planningdate_day,$planningdate_month,$planningdate_year);
    }
    if ($planningtimestart == '') {$planningtimestart = NULL;}
    if ($planningtimestop == '') { $planningtimestop = NULL; }
    
    if ($planningid > 0)
    {
      $query = 'update planning set deleted=?,planningdate=?,planningstart=?,planningstop=?,planningtimestart=?,planningtimestop=?,planningname=?,planningcomment=?,dayofweek=?,periodic=?,periodic_spec=?';
      $query_prm = array($deleted,$planningdate,$planningstart,$planningstop,$planningtimestart,$planningtimestop,$planningname,$planningcomment,$dayofweek,$periodic,$periodic_spec);
      $query = $query . ' where planningid=?'; array_push($query_prm,$planningid);
      require('inc/doquery.php');
      echo '<p>' . d_trad('planningmodified') . '</p><br>';
    }
    else
    {
      $savetime = 1;
      $query = 'insert into planning (planningdate,planningstart,planningstop,planningname,planningcomment,dayofweek,periodic,periodic_spec';
      if ($savetime) { $query = $query . ',planningtimestart,planningtimestop'; }
      $query = $query . ') values (?,?,?,?,?,?,?,?';
      if ($savetime) { $query = $query . ',?,?'; }
      $query = $query . ')';
      $query_prm = array($planningdate,$planningstart,$planningstop,$planningname,$planningcomment,$dayofweek,$periodic,$periodic_spec);
      if ($savetime) { array_push($query_prm,$planningtimestart,$planningtimestop); }
      require('inc/doquery.php');
      $planningid = $query_insert_id;
      echo '<p>' . d_trad('planningadded') . '</p><br>';
    }
    if ($simple_form)
    {
      $query = 'select planning_employeeid from planning_employee where linenr=1 and planningid=?';
      $query_prm = array($planningid);
      require('inc/doquery.php');
      $planning_employeeid = $query_result[0]['planning_employeeid'];
      if ($planning_employeeid > 0)
      {
        $query = 'update planning_employee set employeeid=? where planning_employeeid=?';
        $query_prm = array($employee1id,$planning_employeeid);
        require('inc/doquery.php');
      }
      elseif ($employee1id > 0 && $planningid > 0)
      {
        $query = 'insert into planning_employee (planningid,employeeid,linenr) values (?,?,1)';
        $query_prm = array($planningid,$employee1id);
        require('inc/doquery.php');
      }
      
      require('inc/findclient.php'); # getting $clientid from $client
      $query = 'select planning_clientid from planning_client where linenr=1 and planningid=?';
      $query_prm = array($planningid);
      require('inc/doquery.php');
      $planning_clientid = $query_result[0]['planning_clientid'];
      if ($planning_clientid > 0)
      {
        $query = 'update planning_client set clientid=? where planning_clientid=?';
        $query_prm = array($clientid,$planning_clientid);
        require('inc/doquery.php');
      }
      elseif ($clientid > 0 && $planningid > 0)
      {
        $query = 'insert into planning_client (planningid,clientid,linenr) values (?,?,1)';
        $query_prm = array($planningid,$clientid);
        require('inc/doquery.php');
      }
    }
  }

  if ($planningid > 0)
  { 
    if ($simple_form) { echo '<br><h2>Modifier RDV</h2>'; }
    else { echo '<h2>' . d_trad('modplanning:') . '</h2>'; }
  }
  else 
  {
    if ($simple_form) { echo '<br><h2>Prendre RDV</h2>'; }
    else { echo '<h2>' . d_trad('addplanning:') . '</h2>'; }
  }

  echo '<form method="post" action="'.$action.'"><table>';
  echo '<tr><td>' . d_trad('planningname:') . '</td><td colspan=2>';
  echo '<input autofocus type="text" name="planningname" value="'.$planningname.'" size=40 ></td></tr>';

  if ($simple_form)
  {
    echo '<input type=hidden name="planningstart" value="2000-01-01"><input type=hidden name="planningstop" value="3000-01-01">';
  }
  else
  {
    echo '<tr><td>' . d_trad('validity:') . '</td><td colspan=2>';
    $datename = 'planningstart'; $selecteddate = $$datename;
    require('inc/datepicker.php');
    echo ' &nbsp; '.d_trad('validity_to') .' &nbsp; ';
    $datename = 'planningstop'; $selecteddate = $$datename;
    require('inc/datepicker.php');
    $planningtimestart = mb_substr($planningtimestart,0,5);
  }

  if ($simple_form)
  {
    echo '<input type=hidden name="periodic" value=0><input type=hidden name="employee1id" value="'.$_SESSION['ds_myemployeeid'].'">';
    $client = $simple_form_clientid;
    echo '<tr><td>'; require('inc/selectclient.php');
    echo '<tr><td>Date:<td>';
    $datename = 'planningdate'; $selecteddate = $$datename;
    require('inc/datepicker.php');
  }
  else
  {
    echo '<tr><td>' . d_trad('planningtype') . ':</td><td>';
    echo '<input type=radio name=periodic value=0'; if ($periodic == 0) { echo ' checked'; }

    echo '>'.d_trad('punctual') .'</td><td>';
    $datename = 'planningdate'; $selecteddate = $$datename;
    require('inc/datepicker.php');
    echo '</td></tr><tr><td></td><td>';

    echo '<input type=radio name=periodic value=1'; if ($periodic == 1) { echo ' checked'; }
    echo '>'.d_trad('weekly') .'</td><td>';
    echo '<select name="periodic_spec_weekly">';
    #Every week
    echo '<option value=0'; if ($periodic_spec_weekly == 0) { echo ' selected'; } echo '>'.d_trad('allweeks') .'</option>';
    #Every odd week
    echo '<option value=1'; if ($periodic_spec_weekly == 1) { echo ' selected'; } echo '>'.d_trad('periodic_spec_weekly_1').'</option>';
    #Every even week
    echo '<option value=2'; if ($periodic_spec_weekly == 2) { echo ' selected'; } echo '>'.d_trad('periodic_spec_weekly_2').'</option>';
    #Every 1st week of month
    echo '<option value=3'; if ($periodic_spec_weekly == 3) { echo ' selected'; } echo '>'.d_trad('periodic_spec_weekly_3').'</option>';
    #Every 2nd week of month
    echo '<option value=4'; if ($periodic_spec_weekly == 4) { echo ' selected'; } echo '>'.d_trad('periodic_spec_weekly_4').'</option>';
    #Every 3rd week of month
    echo '<option value=5'; if ($periodic_spec_weekly == 5) { echo ' selected'; } echo '>'.d_trad('periodic_spec_weekly_5').'</option>';
    #Every 4th week of month
    echo '<option value=6'; if ($periodic_spec_weekly == 6) { echo ' selected'; } echo '>'.d_trad('periodic_spec_weekly_6').'</option>';
    echo '</select>';
    echo ' <select name="dayofweek">';
    for ($i=1;$i<=7;$i++)
    {
      echo '<option value='.$i; if ($dayofweek == $i) { echo ' selected'; }
      echo '>'. d_trad('dayofweek' . $i) .'</option>';
    }
    echo '</select>';
    
    echo '<tr><td></td><td>';

    echo '<input type=radio name=periodic value=2'; if ($periodic == 2) { echo ' checked'; }
    echo '>'.d_trad('monthly').'</td><td>';
    echo '<select name="periodic_spec_monthly">';
    #Every month
    echo '<option value=0'; if ($periodic_spec_monthly == 0) { echo ' selected'; } echo '>'.d_trad('allmonths').'</option>';
    #Every odd month
    echo '<option value=1'; if ($periodic_spec_monthly == 1) { echo ' selected'; } echo '>'.d_trad('periodic_spec_monthly1').'</option>';
    #Every even month
    echo '<option value=2'; if ($periodic_spec_monthly == 2) { echo ' selected'; } echo '>'.d_trad('periodic_spec_monthly2').'</option>';
    #Every quarter
    echo '<option value=3'; if ($periodic_spec_monthly == 3) { echo ' selected'; } echo '>'.d_trad('periodic_spec_monthly3').'</option>';
    #Every semester
    echo '<option value=4'; if ($periodic_spec_monthly == 4) { echo ' selected'; } echo '>'.d_trad('periodic_spec_monthly4').'</option>';

    echo '</select>';
    echo ' ' . d_trad('prefix_specificdate') . ' <select name="day_monthly">';
    for ($i=1;$i<=31;$i++)
    {
      echo '<option value='.$i; if ($day_monthly == $i) { echo ' selected'; }
      echo '>'.$i.'</option>';
    }
    echo '</select>';
    echo '</td></tr><tr><td></td><td>';

    echo '<input type=radio name=periodic value=3'; if ($periodic == 3) { echo ' checked'; }
    echo '>'.d_trad('yearly').'</td><td>';
    echo '<select name="day_yearly">';
    for ($i=1;$i<=31;$i++)
    {
      echo '<option value='.$i; if ($day_yearly == $i) { echo ' selected'; }
      echo '>'.$i.'</option>';
    }
    echo '</select>';
    echo '  <select name="month_yearly">';
    for ($i=1;$i<=12;$i++)
    {
      echo '<option value='.$i; if ($month_yearly == $i) { echo ' selected'; }
      echo '>'. d_trad('month2_' . $i ) . '</option>';
    }
    echo '</select>';
    echo '</td></tr>';
  }

  echo '<tr><td>'.d_trad('time:').'</td><td colspan=2>';
  echo '<input type=time name=planningtimestart value="' . $planningtimestart . '" size=5> &nbsp; '.d_trad('time_to').' &nbsp; <input type=time name=planningtimestop value="' . $planningtimestop . '" size=5></td></tr>';

  echo '<tr><td>' . d_trad('planningcomment') . ':</td><td colspan=2>';
  echo '<input type=text name="planningcomment" value="'.$planningcomment.'" size=80></td></tr>';

  if ($planningid > 0)
  {
    echo '<tr><td>' . d_trad('deleted:') . '</td><td colspan=2><input type=checkbox name="deleted"';
    if ($deleted == 1) { echo ' checked'; }
    echo ' value=1 ></td></tr>';
  }
  echo '<tr><td colspan="2" align="center"><input type=hidden name="saveme" value="1"><input type=hidden name="planningid" value="' . $planningid . '">
  <input type=hidden name="custommenu" value="' . $custommenu . '"><input type=hidden name="salesmenu" value="' . $salesmenu . '">';
  echo '<tr><td colspan="3" align="center"><input type="submit" value="' . d_trad('save') . '"></td></tr>';
  echo '</table>';

  if (!$simple_form)
  {
    echo '<br><br><table border=0 cellspacing=1 cellpadding=1>';
    echo '<tr><td><b>' . d_trad('employee') . '</b></td><td>&nbsp;&nbsp;&nbsp;</td><td><b>' . d_trad('client') . '</b></td><td>&nbsp;&nbsp;&nbsp;</td><td><b>' . d_trad('resource') . '</b></td></tr>';

    $num_resources = $_SESSION['ds_num_resources'];
    $query = 'select planning_employeeid,employeeid,linenr from planning_employee where planningid=?';
    $query_prm = array($planningid);
    require('inc/doquery.php');
    if ($num_results > $num_resources) { $num_resources = $num_results; }
    for ($i=0;$i<$num_results;$i++)
    {
      $linenr = $query_result[$i]['linenr'];
      $p_eidA[$linenr] = $query_result[$i]['planning_employeeid'];
      $eidA[$linenr] = $query_result[$i]['employeeid'];
    }
    $query = 'select planning_clientid,clientid,linenr from planning_client where planningid=?';
    $query_prm = array($planningid);
    require('inc/doquery.php');
    if ($num_results > $num_resources) { $num_resources = $num_results; }
    for ($i=0;$i<$num_results;$i++)
    {
      $linenr = $query_result[$i]['linenr'];
      $p_cidA[$linenr] = $query_result[$i]['planning_clientid'];
      $cidA[$linenr] = $query_result[$i]['clientid'];
    }
    $query = 'select planning_resourceid,resourceid,linenr from planning_resource where planningid=?';
    $query_prm = array($planningid);
    require('inc/doquery.php');
    if ($num_results > $num_resources) { $num_resources = $num_results; }
    for ($i=0;$i<$num_results;$i++)
    {
      $linenr = $query_result[$i]['linenr'];
      $p_ridA[$linenr] = $query_result[$i]['planning_resourceid'];
      $ridA[$linenr] = $query_result[$i]['resourceid'];
    }

    for ($i=1;$i<=$num_resources;$i++)
    {
      echo '<tr><td>';
      $dp_itemname = 'employee'; $dp_addtoid = $i;
      $dp_selectedid = $_POST[$dp_itemname . $i . 'id'];

      $planning_employeeid = $p_eidA[$i];
      if ($modplanningid > 0) { $dp_selectedid = $eidA[$i]; }
      if ($saveme)
      {
        if ($planning_employeeid > 0)
        {
          $query = 'update planning_employee set employeeid=? where planning_employeeid=?';
          $query_prm = array($dp_selectedid,$planning_employeeid);
          require('inc/doquery.php');
        }
        elseif ($dp_selectedid > 0 && $planningid > 0)
        {
          $query = 'insert into planning_employee (planningid,employeeid,linenr) values (?,?,?)';
          $query_prm = array($planningid,$dp_selectedid,$i);
          require('inc/doquery.php');
        }
      }
      require('inc/selectitem.php');
      echo '</td><td></td>';
      
      echo '<td>';
      $client = $_POST['client'.$i];
      $noautofocus = 1; $dp_addtoid = $i;

      $planning_clientid = $p_cidA[$i];
      if ($modplanningid > 0) { $client = $cidA[$i];if ($client == 0) { $client = ''; } }
      if ($saveme)
      {
        if ($clientid < 1) { $clientid = 0; }
        require('inc/findclient.php'); # getting $clientid from $client
        if ($planning_clientid > 0)
        {
          $query = 'update planning_client set clientid=? where planning_clientid=?';
          $query_prm = array($clientid,$planning_clientid);
          require('inc/doquery.php');
        }
        elseif ($clientid > 0 && $planningid > 0)
        {
          $query = 'insert into planning_client (planningid,clientid,linenr) values (?,?,?)';
          $query_prm = array($planningid,$clientid,$i);
          require('inc/doquery.php');
        }
      }
      $dp_nodescription = 1;$dp_addtoid=$i;  
      require('inc/selectclient.php');
      echo '</td><td></td><td>';
      
      $dp_itemname = 'resource'; $dp_addtoid = $i;
      $dp_selectedid = $_POST[$dp_itemname . $i . 'id'];
      /*
      $query = 'select planning_resourceid,resourceid from planning_resource where planningid=? and linenr=?';
      $query_prm = array($planningid,$i);
      require('inc/doquery.php');
      */
      $planning_resourceid = $p_ridA[$i];
      if ($modplanningid > 0) { $dp_selectedid = $ridA[$i]; }
      if ($saveme)
      {
        if ($planning_resourceid > 0)
        {
          $query = 'update planning_resource set resourceid=? where planning_resourceid=?';
          $query_prm = array($dp_selectedid,$planning_resourceid);
          require('inc/doquery.php');
        }
        elseif ($dp_selectedid > 0 && $planningid > 0)
        {
          $query = 'insert into planning_resource (planningid,resourceid,linenr) values (?,?,?)';
          $query_prm = array($planningid,$dp_selectedid,$i);
          require('inc/doquery.php');
        }
      }
      require('inc/selectitem.php');
      echo '</td><td></td>';
      echo '</tr>';
    }
    echo '</table>';
  }
  echo '</form>';
  break;
  
  case 'planningform': # copy
  $ds_curdate = $_SESSION['ds_curdate'];
  $ds_userid = $_SESSION['ds_userid'];
  if($_SESSION['ds_myemployeeid'] > 0){ $ds_userid = $_SESSION['ds_myemployeeid'];}
  $currentday = mb_substr($ds_curdate,8,2);
  $currentmonth = mb_substr($ds_curdate,5,2);
  $currentyear = mb_substr($ds_curdate,0,4);
  $currenttimestamp = mktime(0,0,0,$currentmonth,$currentday,$currentyear); # TODO don't use mktime
  $currentweek = date("W",$currenttimestamp);
  $actionform = $_GET['actionform'];
  $target = '';
  $form = 1;
  if($actionform == 'reportwindow')
  {
    $target = 'target="_blank"';
    echo '<h2>' . d_trad('planningreport:') . '</h2>';
  }
  elseif($actionform == 'admin')
  {
    $actionform = 'custom';
    echo '<h2>' . d_trad('modifyplanning:') . '</h2>';	
  }

  if($form)
  {
    echo '<form method="post" action="' . $actionform . '.php" ' .$target .' >';
    ?>
      <table>
        <tr>
          <td><?php echo d_trad('planningtype:'); ?></td>    
          <td><input type='radio' name='periodic' value=-1 checked /><?php echo d_trad('all'); ?></td>
        </tr>
        <tr>
          <td></td>
          <td><input type='radio' name='periodic' value=0 /><?php echo d_trad('punctual'); ?></td>
        </tr>    
        <tr>
          <td></td>
          <td><input type='radio' name='periodic' value=1 /><?php echo d_trad('weekly'); ?></td>
        </tr>
        <tr>
          <td></td>
          <td><input type='radio' name='periodic' value=2 /><?php echo d_trad('monthly'); ?></td>
        </tr>
        <tr>
          <td></td>
          <td><input type='radio' name='periodic' value=3 /><?php echo d_trad('yearly'); ?></td>
        </tr>
        <tr>
          <td><?php echo d_trad('startdate:'); ?></td>
          <td><?php $datename = 'startdate'; $dp_setempty=1;require('inc/datepicker.php');?></td>
        </tr>
        <tr>
          <td><?php echo d_trad('stopdate:'); ?></td>
          <td><?php $datename = 'stopdate'; $dp_setempty=1;require('inc/datepicker.php');?></td>
        </tr>
        <tr>
          <?php $dp_itemname = 'employee'; $dp_selectedid = $ds_userid; $dp_allowall= 1; $dp_noblank=1;$dp_description = d_trad('employee');?>
          <td><?php require('inc/selectitem.php');?></td>
        </tr>
        <tr>
          <td><?php require('inc/selectclient.php');?></td>
        </tr>    
        <tr>
          <?php $dp_itemname = 'resource'; $dp_allowall= 1; $dp_noblank=1;$dp_description = d_trad('resource'); ?>
          <td><?php require('inc/selectitem.php');?></td>
        </tr>
        <tr>
        <td colspan=2 align=right>
          <input type=hidden name="report" value="planningreport">
          <input type=hidden name="custommenu" value="modplanning">			
          <input type="submit" value="<?php echo d_trad('validate');?>">
        </td></tr>
      </table>
    </form>
  <?php }
  break;
  
  case 'modplanning':
  echo '<h2>' . d_trad('modplanning:') . '</h2>';
  $periodic = $_POST['periodic'];
  $datename = 'startdate'; require('inc/datepickerresult.php');
  $datename = 'stopdate'; require('inc/datepickerresult.php');
  $employeeid = $_POST['employeeid'];
  $resourceid = $_POST['resourceid'];
  $num_results=0;$client = $_POST['client'];require('inc/findclient.php');$clientnum_results=$num_results;
  $MAX_RESULTS = 100;
  session_write_close();

  #SELECT
  $query = 'select p.planningid,p.planningdate,p.planningstart,p.planningstop,p.planningtimestart,p.planningtimestop,p.planningname,p.planningcomment,p.dayofweek,p.periodic,p.periodic_spec ';
  $query_prm = array();
  $employeeidempty = 1;
  $clientempty = 1;
  $clientidempty = 1;
  $resourceidempty = 1;
  if(!empty($employeeid) && $employeeid > -1)
  {
    $employeeidempty = 0;
    $query .= ',concat(employeename," ",employeefirstname) as employeename';
  } 
  if(!empty($client))
  {
    $clientempty = 0;
    if(!empty($clientid) && $clientid > -1)
    { 
      $clientidempty = 0;
    }
    $query .= ',c.clientname';
  }
  if(!empty($resourceid) && $resourceid > -1)
  { 
    $resourceidempty = 0; 
    $query .= ',r.resourcename';
  }

  #FROM
  $query .= ' from planning p';
  if (!$employeeidempty) { $query .= ',planning_employee pe, employee e'; }
  if (!$clientidempty || !$clientempty) { $query .= ',planning_client pc,client c'; }
  if(!$resourceidempty) { $query .= ',planning_resource pr,resource r'; }

  #WHERE
  $query .= ' where p.deleted = 0';
  if($startdate > 0)
  {
      $query .= ' and p.planningstop >= ?';
      array_push($query_prm,$startdate);
  }
  if($stopdate > 0)
  {
      $query .= ' and p.planningstart <= ?';
      array_push($query_prm,$stopdate);
  }
  if(!$employeeidempty)
  {
      $query .= ' and p.planningid = pe.planningid and pe.employeeid = e.employeeid and pe.employeeid=?';
      array_push($query_prm,$employeeid);
  }
  if(!$clientidempty)
  {
    $query .= ' and p.planningid = pc.planningid and c.clientid=pc.clientid and pc.clientid = ?'; 
    array_push($query_prm,$clientid);
  }
  elseif(!$clientempty)
  {
    $query .= ' and p.planningid = pc.planningid and c.clientid=pc.clientid and c.clientname LIKE ?';    
    array_push($query_prm,'%' . $client . '%');
  }
  if(!$resourceidempty)
  {
      $query .= ' and p.planningid = pr.planningid and pr.resourceid = r.resourceid and pr.resourceid=?'; 
      array_push($query_prm,$resourceid);    
  }
  if($periodic != -1)
  {
    $query .= ' and p.periodic = ?';
    array_push($query_prm,$periodic);
  }

  #ORDER BY
  $query .= ' order by periodic,planningstart,planningdate limit '.$MAX_RESULTS;   

  require('inc/doquery.php');

  if(!$employeeidempty){ $ourparams .= '<p>'. d_trad('employeeparam',$query_result[0]['employeename']) .'</p>';}
  if(!$clientempty)
  {
    if(!$clientidempty)
    { 
      $ourparams .= '<p>'. d_trad('clientparams',array($query_result[0]['clientname'],$clientid)) .'</p>';
    }
    else 
    {
      $ourparams .= '<p>'. d_trad('clientparam',$client) .'</p>';  
    }
  }
  if(!$resourceidempty){ $ourparams .= '<p>'. d_trad('resourceparam',$query_result[0]['resourcename']) .'</p><br>';}
  echo $ourparams;

  echo '<form method="post" action="custom.php"><table class=report>';

  $lastperiodic = -1;
  for ($i=0;$i<$num_results;$i++)
  {
    $periodic = $query_result[$i]['periodic'];
    if ($i == 0 || $periodic != $lastperiodic)
    {
      echo '<thead>';
      if ($periodic == 0) { echo '<th colspan=2>' . d_trad('punctual') . '</th><th colspan=2>' . d_trad('date') . '</th><th>' . d_trad('validity') .'</th><th>' . d_trad('planningcomment'); }
      if ($periodic == 1) { echo '<th colspan=4>' . d_trad('weekly'). '</th><th>' . d_trad('validity') .'</th><th>' . d_trad('planningcomment'); }
      if ($periodic == 2) { echo '<th colspan=4>' . d_trad('monthly'). '</th><th>' . d_trad('validity') .'</th><th>' . d_trad('planningcomment'); }
      if ($periodic == 3) { echo '<th colspan=4>' . d_trad('yearly'). '</th><th>' . d_trad('validity') .'</th><th>' . d_trad('planningcomment'); }
      echo '</th></thead>';
    }
    echo d_tr();
    if($planningmenu == 'list')
    {
      echo '<td colspan=2>';  
    }
    else
    {
      echo '<td><input type=radio name=modplanningid value="' . $query_result[$i]['planningid'] . '"><td>';
    }
    echo $query_result[$i]['planningname'] . '</td>'; 
    if ($periodic == 0)
    {
      echo '<td colspan=2>' . datefix2($query_result[$i]['planningdate']) . '</td>';
    }
    if ($periodic == 1)
    {
      if ($query_result[$i]['periodic_spec'] == 0) { $kladd = 'allweeks'; }
      else { $kladd = 'periodic_spec_weekly_' . $query_result[$i]['periodic_spec']; }
      echo '<td>' . d_trad($kladd) . '</td><td>' . d_trad('dayofweek'. $query_result[$i]['dayofweek']) . '</td>';
    }
    if ($periodic == 2)
    {
      # starting which month? $kladd2
      $kladd2 = '';
      if ($query_result[$i]['periodic_spec'] == 0) { $kladd = 'allmonths'; }
      else { $kladd = 'periodic_spec_monthly' . $query_result[$i]['periodic_spec']; }
      echo '<td>' . d_trad($kladd) . $kladd2 . '</td><td>' . d_trad('prefix_specificdate') . ' ' . (mb_substr($query_result[$i]['planningdate'],8,2)+0) . '</td>';
    }
    if ($periodic == 3)
    {
      echo '<td colspan=2>' . (mb_substr($query_result[$i]['planningdate'],8,2)+0) . ' ' . d_trad('month2_' . (mb_substr($query_result[$i]['planningdate'],5,2)+0)) . '</td>';
    }
    echo '<td>' . datefix2($query_result[$i]['planningstart']) . ' &nbsp; ' . d_trad('validity_to') . ' &nbsp; ' . datefix2($query_result[$i]['planningstop']) . '</td>';
    echo '<td>' . $query_result[$i]['planningcomment'] . '</td></tr>';
    $lastperiodic = $periodic;
  }
  if($planningmenu != 'list')
  {
    echo '<tr><td colspan="6" align="center"><input type=hidden name="custommenu" value="planning"><input type="submit" value="' . d_trad('modify') . '"></td></tr>';
  }
  echo '</table></form>';
  break;
  
  case 'calendarform':
  require('admin/calendarform.php');
  break;
  
  case 'salespricelog':
  require('products/salespricelog.php');
  break;
  
  case 'stockval':
  ?><h2>Valeur Stock</h2>
  <p class=alert>Avant d'executer ce rapport, executer Cadence Stock avec l'option "Mettre à jour stock par mois"</p>
  <form method="post" action="customreportwindow.php" target=_blank><table><?php # pour fournisseur 4126 
  echo '<tr><td>&nbsp;</td><td><select name="mychoice"><option value=1>Nestlé</option><option value=2>Wing Chong (très lourd)</option></select>';
  $month = substr($_SESSION['ds_curdate'],5,2);
  $year = substr($_SESSION['ds_curdate'],0,4);
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
  <tr><td colspan=2><input type=hidden name="report" value="stockval"><input type="submit" value="Rapport"></td></tr></form><?php
  break;
  
  
  case 'productcatalogue_mod':
  echo '<h2>Catalogue Produits</h2>';
  echo '<form method="post" action="reportwindow.php" target=_blank><table>';
  $dp_itemname='productdepartment';$dp_description = d_trad('department'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
  $dp_description = d_trad('family'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamilygroup.php');
  $dp_description = d_trad('subfamily'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamily.php');
  ?>
  <tr><td>&nbsp;
  <tr><td>Afficher images:<td><input type=radio name="showimages" value=1>Un
  <tr><td><td><input type=radio name="showimages" value=2 checked>Tous
  <tr><td>&nbsp;
  <tr><td>Champs:<td><input type=checkbox name="showeancode" value=1 checked>Code EAN unité
  <?php
  echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="productcatalogue"><input type="submit" value="' . d_trad('validate') .'"></td></tr></table></form>';
  break;
  
  case 'productcatalogue':
  require('reports/productcatalogue.php');
  break;
  
  case 'prodsales':

    require('preload/returnreason.php');
    ?>
    <h2>Produits vendus:</h2>
    <form method="post" action="reportwindow.php" target="_blank"><table>
    <?php

    echo '<tr><td>Date:</td><td><select name="datefield"><option value=0>' . $_SESSION['ds_term_accountingdate'] . '</option>';
    if ($_SESSION['ds_hidedeliverydate'] == 0) { echo '<option value=1>' . $_SESSION['ds_term_deliverydate'] . '</option>'; }
    echo '<option value=2>Saisie</option><option value=3>Payable</option></select></td></tr>';
    echo '<tr><td>De:</td><td>';
    $datename = 'startdate';
    require('inc/datepicker.php');
    echo '</td></tr>';
    echo '<tr><td>A:</td><td>';
    $datename = 'stopdate';
    require('inc/datepicker.php');
    echo '</td></tr>';
    echo '<tr><td>Produit:</td><td><input type="text" STYLE="text-align:right" name="product" size=20></td></tr>';
    ?>
    <tr><td>Client:</td><td><input type="text" STYLE="text-align:right" name="client" size=20></td></tr>
    <tr><td>Facturier:</td>
    <td><select name="userid"><?php
    $query = 'select userid,name from usertable where userid<>1 and deleted=0 order by name';
    $query_prm = array();
      require('inc/doquery.php');
    echo '<option value="-1"> </option>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      echo '<option value="' . $row2['userid'] . '">' . $row2['name'] . '</option>';
    }
    ?></select></td></tr>
    <tr><td>Employé(e):</td>
    <td><select name="employeeid"><option value="0"> </option><?php
    $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee where issales=1 and deleted=0 order by employeename';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row2['employeeid'] == $employeeid) { echo '<option value="' . $row2['employeeid'] . '" SELECTED>' . $row2['employeename'] . '</option>'; }
      else { echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeename'] . '</option>'; }
    }
    ?></select> <input type="checkbox" name="showpayments" value="1"> Afficher paiements</td></tr>


    <tr><td>Status:</td>
    <td><select name="mychoice"><option value=1><?php echo d_trad('selectall'); ?></option><?php echo d_trad('selectall'); ?> sauf annulées</option><option value=3>Non confirmées</option><option value=2>Confirmées</option><option value=8>Confirmées et non lettrées</option><option value=9>Lettrées</option><option value=4>Annulées</option></select></td></tr>

    <tr><td>Type:</td>
    <td><select name="mychoice2"><option value=1><?php echo d_trad('selectall'); ?></option><option value=2>Factures</option><option value=5>Avoirs</option></select></td></tr>

    <?php
    if (isset($returnreasonA))
    {
      $dp_itemname = 'returnreason'; $dp_description = ' &nbsp; Reason d\'avoir'; $dp_allowall = 1; $dp_selectedid = -1;
      require('inc/selectitem.php');
    }
    ?>

    <tr><td> &nbsp; Proforma:</td>
    <td><select name="mychoice3"><option value=1><?php echo d_trad('selectall'); ?></option><option value=2>Proformas</option><option value=3>Non proforma</option></select></td></tr>

    <?php

    echo '<tr><td> &nbsp; ' . $_SESSION['ds_term_invoicenotice'] . ':</td>
    <td><select name="mychoice4"><option value=1>' . d_trad('selectall') . '</option><option value=2>' . $_SESSION['ds_term_invoicenotice'] . '</option><option value=3>Non ' . $_SESSION['ds_term_invoicenotice'] . '</option></select></td></tr>';

    /*
    <option value=6>Proforma</option><option value=7>
    <?php echo $_SESSION['ds_term_invoicenotice']; ?>
    </option>
    */

    if ($_SESSION['ds_useinvoicetag'])
    {
      $dp_itemname = 'invoicetag'; $dp_description = ' &nbsp; ' . $_SESSION['ds_term_invoicetag']; $dp_allowall = 1; $dp_selectedid = -1;
      require ('inc/selectitem.php');
    }

    echo '<tr><td>Classe produit:</td><td><select name="productdepartmentid"><option value=0>' . d_trad('selectall') . '</option>';
    $query = 'select productdepartmentname,productdepartmentid from productdepartment order by productdepartmentname';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      echo '<option value="' . $query_result[$i]['productdepartmentid'] . '">' . $query_result[$i]['productdepartmentname'] . '</option>';
    }
    echo '</select></td></tr>';

    echo '<tr><td>Famille produit:</td><td><select name="productfamilygroupid"><option value=0>' . d_trad('selectall') . '</option>';
    $query = 'select productfamilygroupname,productfamilygroupid,productdepartmentname from productfamilygroup,productdepartment where productfamilygroup.productdepartmentid=productdepartment.productdepartmentid order by productdepartmentname,productfamilygroupname';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      echo '<option value="' . $query_result[$i]['productfamilygroupid'] . '">' . $query_result[$i]['productdepartmentname'] . '/' . $query_result[$i]['productfamilygroupname'] . '</option>';
    }
    echo '</select></td></tr>';

    echo '<tr><td>Sous-famille produit:</td><td><select name="productfamilyid"><option value=0>' . d_trad('selectall') . '</option>';
    $query = 'select productfamilyid,productfamilyname,productfamilygroupname,productdepartmentname from productfamily,productfamilygroup,productdepartment where productfamilygroup.productdepartmentid=productdepartment.productdepartmentid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid order by productdepartmentname,productfamilygroupname,productfamilyname';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      echo '<option value="' . $query_result[$i]['productfamilyid'] . '">' . $query_result[$i]['productdepartmentname'] . '/' . $query_result[$i]['productfamilygroupname'] . '/' . $query_result[$i]['productfamilyname'] . '</option>';
    }
    echo '</select></td></tr>';

    ###

    ?>

    <?php
    # TODO currentpurchasebatchid
    #<tr><td>Tracking automatique:</td><td><input type="text" STYLE="text-align:right" name="supplierbatchname_a" size=20></td></tr>
    ?>
    <tr><td>Tracking manuel (lot fournisseur):</td><td><input type="text" STYLE="text-align:right" name="supplierbatchname" size=20></td></tr>


    <tr><td>Afficher commentaire facture</td>
    <td><input type="checkbox" name="showinvoicecomment" value="1"></td></tr>

    <tr><td>Ranger par</td>
    <td><select name="orderby">
    <option value=0>Numéro facture</option>
    <option value=1>Client,Produit</option>
    <option value=2>Géo (clients sans Île non affichés)</option>
    </select></td></tr>

    <tr><td colspan="2" align="center">
    <input type=hidden name="report" value="prodsales">
    <input type="submit" value="Valider"></td></tr></table></form><?php


  break;
  
  case 'mod_prod':
  if (isset($_POST['productid'])) { $productid=$_POST['productid'];}
  else
  {
  $product = $_POST['product'];
  require ('inc/findproduct.php');
  }
  if ($productid > 0)
  {
    if ($_POST['saveme'] == 1)
    {
      $query = 'update product set promotext=? where productid=?';
      $query_prm = array($_POST['promotext'],$_POST['productid']);
      require('inc/doquery.php');
      echo 'Produit modifié.';
    }
    else
    {
      $query = 'select productname,promotext from product where productid=?';
      $query_prm = array($productid);
      require('inc/doquery.php');
      echo '<h2>Modif produit '.$productid.' '.d_decode($query_result[0]['productname']).'</h2>
      <form method="post" action="custom.php">
      <table><tr><td>Info promos:<input type=text name=promotext size=80 value='.d_input($query_result[0]['promotext']).'>
      <tr><td colspan="2" align="center"><input type=hidden name="custommenu" value="mod_prod">
      <input type=hidden name="saveme" value=1><input type=hidden name="productid" value='.$productid.'><input type="submit" value="Valider"></td></tr>';
      echo '</table></form>';
    }
  }
  else
  {
    echo '<h2>Modif produit</h2>
    <form method="post" action="custom.php">
    <table><tr><td>';
    require ('inc/selectproduct.php');
    echo '<tr><td colspan="2" align="center"><input type=hidden name="custommenu" value="mod_prod"><input type="submit" value="Valider"></td></tr>';
    echo '</table></form>';
  }
  break;
  
  case 'mod_client':
  if (isset($_POST['clientid'])) { $clientid=$_POST['clientid'];}
  else
  {
  $dp_no_suppliers = 1;
  require ('inc/findclient.php');
  }
  if ($clientid > 0)
  {
    if ($_POST['saveme'] == 1)
    {
      $query = 'update client set blocked=? where clientid=?';
      $query_prm = array($_POST['blocked'],$_POST['clientid']);
      require('inc/doquery.php');
      echo 'Client modifié.';
    }
    else
    {
      $query = 'select clientname,blocked from client where clientid=?';
      $query_prm = array($clientid);
      require('inc/doquery.php');
      echo '<h2>Modif client '.$clientid.' '.d_decode($query_result[0]['clientname']).'</h2>
      <form method="post" action="custom.php">
      <table><tr><td>Peut acheter (interdit):<td><select name="blocked">
                <option value="0"></option>
                <option value="2"'; if ($query_result[0]['blocked'] == 2){ echo 'SELECTED';} echo '>COMPTE SUSPENDU</option>
                <option value="1"'; if ($query_result[0]['blocked'] == 1){ echo 'SELECTED';} echo '>COMPTE INTERDIT</option>
            </select>
      <tr><td colspan="2" align="center"><input type=hidden name="custommenu" value="mod_client">
      <input type=hidden name="saveme" value=1><input type=hidden name="clientid" value='.$clientid.'><input type="submit" value="Valider"></td></tr>';
      echo '</table></form>';
    }
  }
  else
  {
    echo '<h2>Modif client</h2>
    <form method="post" action="custom.php">
    <table><tr><td>';
    require ('inc/selectclient.php');
    echo '<tr><td colspan="2" align="center"><input type=hidden name="custommenu" value="mod_client"><input type="submit" value="Valider"></td></tr>';
    echo '</table></form>';
  }
  break;
  
  case 'showproductactions':
  require('products/showproductactions.php');
  break;
  
  case 'productaction':
  $product = $_POST['product'];
  require ('inc/findproduct.php');

  if ($productid > 0 && $_POST['actionname'] != '')
  {
    $datename = 'actiondate';
    require('inc/datepickerresult.php');
    if ($_POST['productactionfield1'] == '') { $_POST['productactionfield1'] = ''; }
    $query = 'insert into productaction (productid,actiondate,employeeid,productactioncatid,actionname,userid,productactionfield1,priceinfo) values (?,?,?,?,?,?,?,?)';
    $query_prm = array($productid, $actiondate, $_POST['employeeid'], $_POST['productactioncatid'], $_POST['actionname'], $_SESSION['ds_userid'], $_POST['productactionfield1'], $_POST['priceinfo']);
    require('inc/doquery.php');
    if ($num_results) { echo '<p>Évènement ajouté pour produit ' . $productid . '.</p><br>'; }
  }


  ?>
  <h2>Évènement</h2>
  <form method="post" action="custom.php">
  <table><tr><td>
  <?php
  require ('inc/selectproduct.php');
  ?>
  </td></tr>
  <tr><td>Date:</td><td><?php
  $datename = 'actiondate'; #$dp_datepicker_min = $_SESSION['ds_curdate'];
  require('inc/datepicker.php');
  ?></td></tr>
  <tr><td>Employé(e):</td>
  <td><select name="employeeid"><option value="0"></option><?php
  $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee where deleted=0 order by employeename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row2 = $query_result[$i];
    if ($row2['employeeid'] == $_POST['employeeid']) { echo '<option value="' . $row2['employeeid'] . '" selected>' . $row2['employeename'] . '</option>'; }
    else { echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeename'] . '</option>'; }
  }
  ?></select></td></tr>
  <tr><td>Catégorie d'action:</td>
  <td><select name="productactioncatid"><option value="0"></option><?php

  $query = 'select productactioncatid,productactioncatname from productactioncat where deleted=0 order by productactioncatname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row2 = $query_result[$i];
    if ($row2['productactioncatid'] == $_POST['productactioncatid']) { echo '<option value="' . $row2['productactioncatid'] . '" selected>' . $row2['productactioncatname'] . '</option>'; }
    else { echo '<option value="' . $row2['productactioncatid'] . '">' . $row2['productactioncatname'] . '</option>'; }
  }
  echo '</select></td></tr>';

  $dp_itemname = 'competitor'; $dp_description = 'Entreprise concurrente'; $dp_selectedid = $_POST['competitorid'];
  require('inc/selectitem.php');

  if (isset($_SESSION['ds_term_productactionfield1']) && $_SESSION['ds_term_productactionfield1'] != '')
  {
    echo '<tr><td>' . d_output($_SESSION['ds_term_productactionfield1']) . ':</td><td><input type="text" STYLE="text-align:left" name="productactionfield1" value="' . $_POST['productactionfield1'] . '" size=80></td></tr>';
  }
  echo '<tr><td>Évènement:</td><td><input type="text" STYLE="text-align:left" name="actionname" value="' . $_POST['actionname'] . '" size=80></td></tr>';
  echo '<tr><td>Info prix:</td><td><input type="text" STYLE="text-align:left" name="priceinfo" value="' . $_POST['priceinfo'] . '" size=80></td></tr>';
  echo '<tr><td colspan="2" align="center"><input type=hidden name="custommenu" value="productaction"><input type="submit" value="Valider"></td></tr>';
  echo '</table></form>';
  break;
  
  
  
  case 'barcodes':
    echo '<h2>Barcodes for VA</h2>
    <form enctype="multipart/form-data" method="post" action="customreportwindow.php" target=_blank>
    <table>
    <tr><td>File:</td><td><input type="hidden" name="MAX_FILE_SIZE" value="1000000"><input type="file" name="userfile" size=50></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="report" value="barcodes"><input type="submit" value="Valider"></td></tr>
    </table></form>';
  break;
  
  
  
  case 'nestlebdl':
    require('preload/clientcategory.php');
    require('preload/clientcategory2.php');

    echo '<h2>Rapport de BdL Nestlé:</h2><form method="post" action="customreportwindow.php" target="_blank"><table>';
    
    echo '<input type=hidden name="datefield" value=1>';
    
    echo '<tr><td>De:</td><td>';
    $dp_datepicker_min = d_builddate(1,1,mb_substr($_SESSION['ds_curdate'],0,4)); $dp_datepicker_max = d_builddate(31,12,mb_substr($_SESSION['ds_curdate'],0,4));
    $datename = 'startdate';
    require('inc/datepicker.php');
    echo '</td></tr>';
    echo '<tr><td>A:</td><td>';
    $dp_datepicker_min = d_builddate(1,1,mb_substr($_SESSION['ds_curdate'],0,4)); $dp_datepicker_max = d_builddate(31,12,mb_substr($_SESSION['ds_curdate'],0,4));
    $datename = 'stopdate';
    require('inc/datepicker.php');
    echo '</td></tr>';
    #echo '<tr><td align=right><input type=checkbox name="bynumber" value=1></td><td>Par numéro: <input type="text" STYLE="text-align:right" name="startid" size=10> à <input type="text" STYLE="text-align:right" name="stopid" size=10> (et ne pas par date)</td></tr>';
    /*
    ?><tr><td>Client:</td><td><input autofocus type="text" STYLE="text-align:right" name="client" size=20></td></tr><?php
    */
    #$dp_itemname = 'employee'; $dp_addtoid = 'f'; $dp_issales = 1; $dp_description = 'Employé (facture)'; $dp_allowall = 1; $dp_selectedid = -1;
    #require ('inc/selectitem.php');
    
    echo '<tr><td>Employé:<td><select name="employeefid"><option value="-1">'. d_trad('selectall') .'</option>';
    $query = 'select employeeid,employeename from employee where employeecategoryid=1 and deleted=0 order by employeename';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      echo '<option value="' . $query_result[$i]['employeeid'] . '">' . d_input($query_result[$i]['employeename']) . '</option>';
    }
    echo '</select>';    
    ?>

    <input type=hidden name="mychoice" value=2>
    <tr><td>Type:</td><?php
    echo '<td><select name="mychoice2"><option value=7>' . $_SESSION['ds_term_invoicenotice'] . '</option></select></td></tr>'; # <option value=8>Avoir ' . $_SESSION['ds_term_invoicenotice'] . '</option>

    ?><tr><td>Ranger par:</td><td><select name="mychoice3"><option value=1>Numéro facture</option><option value=2>Numéro client</option>
    <option value=3><?php echo $_SESSION['ds_term_reference']; ?></option>
    <?php
    if ($_SESSION['ds_term_field1'] != "")
    {
      echo '<option value=4>' . $_SESSION['ds_term_field1'] . '</option>';
    }
    if ($_SESSION['ds_term_field2'] != "")
    {
      echo '<option value=5>' . $_SESSION['ds_term_field2'] . '</option>';
    }
    ?>
    </select></td></tr>
    <tr><td colspan="2" align="center"><input type="submit" value="Valider"></td></tr>
    </table><input type=hidden name="report" value="nestlebdl"></form>
    <?php
  break;
  
  case 'modinvoicedate':
  
    $PA['invoiceid'] = 'uint';
    require('inc/readpost.php');
    if ($invoiceid > 0)
    {
      $history = 'history';
      $query = 'select deliverydate,accountingdate from invoice'.$history.' where invoiceid=?';
      $query_prm = array($invoiceid);
      require('inc/doquery.php');
      if ($num_results == 0)
      {
        $history = '';
        $query = 'select deliverydate,accountingdate from invoice'.$history.' where invoiceid=?';
        $query_prm = array($invoiceid);
        require('inc/doquery.php');
      }
      if ($num_results)
      {
        $deliverydate = $query_result[0]['deliverydate'];
        $accountingdate = $query_result[0]['accountingdate'];
        echo '<p><a href="printwindow.php?report=showinvoice&invoiceid='.$invoiceid.'" target=_blank>Facture '.$invoiceid.'</a>';
        echo '<br>Date comptable : '.datefix($accountingdate,'short');
        echo '<br>Date livraison : '.datefix($deliverydate,'short');
        $day = mb_substr($accountingdate,8,2);
        if ($day >= 28)
        {
          $day = 1;
          $month = mb_substr($accountingdate,5,2)+1;
          $year = mb_substr($accountingdate,0,4);
          if ($month == 13) { $month = 1; $year++; }
          $newdate = d_builddate($day, $month, $year);
          $query = 'update invoice'.$history.' set accountingdate=? where invoiceid=?';
          $query_prm = array($newdate, $invoiceid);
          require('inc/doquery.php');
          echo '<p>Nouveau date comptable : '.datefix2($newdate,'short').'.</p>';
        }
        echo '</p><br>';
      }
    }
    
    ?><h2>Post-dater facture</h2>
    <form method="post" action="custom.php"><table>
    <tr><td>Numéro facture/avoir avec date comptable 28+: </td><td><input autofocus type="text" STYLE="text-align:right" name="invoiceid" size=10></td>
    <tr><td colspan="2" align="center">
    <input type=hidden name="custommenu" value="<?php echo $custommenu; ?>">
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
  
  break;
  
  case 'purchasebatchreport':
    require('reports/purchasebatchreport.php');
  break;
  
  case 'sellbydatereal':
    require('reports/sellbydate.php');
  break;
  
  case 'nosales':
    require('reports/nosales.php');
  break;
  
  case 'promo':
    require('reports/promo.php');
  break;
  
  case 'palletcountreport':
    $lastyear = (substr($_SESSION['ds_curdate'],0,4)-1);
    if (substr($_SESSION['ds_curdate'],5,2) == 12) { $lastyear++; }
    echo '<h2>Rapport Inventaire par Produit ',$lastyear,'</h2>
    <form method="post" action="customreportwindow.php" target=_blank>
    <table>
    <tr><td colspan="2" align="center"><input type=checkbox name="exnestle" value="1"> Exclure Nestlé</td></tr>';
    if ($_SESSION['ds_systemaccess'])
    {
      echo '<tr><td colspan="2" align="center">&nbsp;';
      echo '<tr><td colspan="2" align="center"><input type=checkbox name="updateendyearstock" value="1"> Mettre à jours stock fin année';
      echo '<tr><td colspan="2" align="center">&nbsp;';
      echo '<tr><td colspan="2" align="center"><input type=checkbox name="show_values" value="1"> Afficher valeurs';
    }
    echo '<tr><td colspan="2" align="center">&nbsp;';
    $dp_itemname = 'warehouse'; $dp_description = 'Entrepôt'; $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
    echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="palletcountreport"><input type="submit" value="Valider"></td></tr>
    </table></form>';
  break;
  
  case 'reportplacement':
    echo '<h2>Rapport Comptage</h2>
    <form method="post" action="reportwindow.php" target=_blank>
    <table>
    <tr><td colspan="2" align="center"><input type=hidden name="report" value="placementcountreport"><input type="submit" value="Valider"></td></tr>
    </table></form>';
  break;
  
  case 'setplacement':
    require('preload/placement.php');
    require('preload/user.php');
    
    $PA['maxnumpallets'] = 'uint';
    $PA['placementid'] = 'uint';
    $PA['saveme'] = 'uint';
    $PA['placementname'] = '';
    require('inc/readpost.php');
    if ($maxnumpallets < 1) { $maxnumpallets = 1; }

    if ($placementid < 1 && $placementname != '')
    {
      $placementid = array_search($placementname, $placementA);
    }

    if ($placementid > 0)
    {
      require('preload/product.php'); # to show unittype
      require('preload/unittype.php'); # to show unittype
      if ($saveme)
      {
        $query = 'update placement set userid=?,counteddate=curdate(),countedtime=curtime() where placementid=?';
        $query_prm = array($_SESSION['ds_userid'],$placementid);
        require('inc/doquery.php');
        if ($num_results)
        {
          for ($i=0;$i<$maxnumpallets;$i++)
          {
            $query = 'select palletid from pallet_counted where placementid=? and linenr=?';
            $query_prm = array($placementid,$i);
            require('inc/doquery.php');
            $barcode = $_POST['barcode'.$i]; if ($barcode == NULL) { $barcode = ''; }
            $productid = (int) $_POST['product'.$i]; if ($productid < 1) { $productid = 0; }
            $quantity = (double) $_POST['quantity'.$i]; if ($quantity < 0) { $quantity = 0; }
            $quantityrest = (double) $_POST['quantityrest'.$i]; if ($quantityrest < 0) { $quantityrest = 0; }
            $datename = 'pallet_exp' . $i; require('inc/datepickerresult.php');
            if (isset($query_result[0]['palletid']) && $query_result[0]['palletid'] > 0)
            {
              #echo 'updating';
              $query = 'update pallet_counted set barcode=?,productid=?,quantity=?,quantityrest=?,expiredate=? where placementid=? and linenr=?';
              $query_prm = array($barcode,$productid,$quantity,$quantityrest,$$datename,$placementid,$i);
              require('inc/doquery.php');
            }
            else
            {
              #echo 'inserting';
              $query = 'insert into pallet_counted (barcode,productid,quantity,quantityrest,expiredate,linenr,placementid) values (?,?,?,?,?,?,?)';
              $query_prm = array($barcode,$productid,$quantity,$quantityrest,$$datename,$i,$placementid);
              require('inc/doquery.php');
            }
          }
        }
      }
      
      $query = 'select max(linenr) as maxlinenr from pallet_counted where placementid=?';
      $query_prm = array($placementid);
      require('inc/doquery.php');
      $read_maxnumpallets = $query_result[0]['maxlinenr']+1;
      if ($read_maxnumpallets > $maxnumpallets) { $maxnumpallets = $read_maxnumpallets; }
      
      $query = 'select counteddate,countedtime,userid from placement where placementid=?';
      $query_prm = array($placementid);
      require('inc/doquery.php');
      if ($query_result[0]['counteddate'] != NULL) { $performedby = datefix2($query_result[0]['counteddate']) . ' ' . $query_result[0]['countedtime'] . ' par ' . $userA[$query_result[0]['userid']]; }
      else { $performedby = 'Jamais'; }
      
      $query = 'select barcode,pallet_counted.productid,quantity,quantityrest,expiredate,suppliercode
      from pallet_counted,product
      where pallet_counted.productid=product.productid
      and placementid=? order by linenr';
      $query_prm = array($placementid);
      require('inc/doquery.php');
      $main_result = $query_result;
      echo '<h2><a href="custom.php?custommenu=setplacement">Comptage emplacement</a> ' . $placementA[$placementid] . '</h2>
      <form method="post" action="custom.php"><table class="report">
      <tr><td colspan=10>Effectué: <i>'.$performedby.'</i></td></tr>
      <tr><td><b>Palette</td><td><b>Produit</td><td><b>Quantité';
      echo '</td><td><b>Quantité (sous-unités)</td><td><b>DLV</td></tr>';
      for ($i=0;$i<$maxnumpallets;$i++)
      {
        if (!isset($main_result[$i]))
        {
          $barcode = $productid = $quantity = $quantityrest = '';
        }
        else
        {
          $barcode = $main_result[$i]['barcode']; if ($barcode == NULL) { $barcode = ''; }
          $productid = $main_result[$i]['productid']; if ($productid < 1) { $productid = ''; }
          $quantity = $main_result[$i]['quantity']+0; if ($quantity < 1) { $quantity = ''; }
          $quantityrest = $main_result[$i]['quantityrest']+0; if ($quantityrest < 1) { $quantityrest = ''; }
          #echo '<br>'.$i.' '.$productid.' '.$quantity;
          if ($i == ($maxnumpallets-1)) { $maxnumpallets++; }
        }
        if (1==1)
        {
          echo '<tr><td><input type=text STYLE="text-align:right" name="barcode'.$i.'" value="'.d_input($barcode).'" size=10><td>';
          $fp_counter = $i;
          #if (isset($_POST['product' . $fp_counter])) { $product = $_POST['product' . $fp_counter]; }
          #else { $product = $productid; }
          $product = $productid;
          ###
          
          require('inc/autocomplete_product.php');

          require ('inc/findproduct.php');

          if (!isset($fp_counter)) { $fp_counter = ''; }

          if ($num_products < 1 || $num_products > 20)
          {
            echo '<input ';
            if ($fp_counter == '') { echo 'autofocus '; }
            echo 'type="text" STYLE="text-align:right" id="product_autocomplete' . $fp_counter . '" autocomplete="off" name="product' . $fp_counter . '" value="' . d_input($product) . '" size=10>';
            if (isset($_POST['product' . $fp_counter]) && $_POST['product' . $fp_counter] != '')
            {
              if ($num_products < 1 && $fp_counter == '') { echo ' &nbsp; <span class="alert">Aucun produit trouvé.</span>'; }
              else { echo ' &nbsp; <span class="alert">' . $num_products . ' produits trouvés.</span>'; }
            }
          }
          elseif ($num_products != 1)
          {
            echo '<select ';
            if ($fp_counter == '') { echo 'autofocus '; }
            echo 'name="product' . $fp_counter . '">';
            for ($i_temp=0;$i_temp<$num_products;$i_temp++)
            {
              if ($_SESSION['ds_useproductcode'] == 1)
              {
                echo '<option value="' . d_input(d_decode($query_result[$i_temp]['suppliercode'])) . '">' . d_output(d_decode($query_result[$i_temp]['suppliercode']));
              }
              else
              {
                echo '<option value="' . $query_result[$i_temp]['productid'] . '">' . $query_result[$i_temp]['productid'];
              }
              echo ': ' . d_output(d_decode($query_result[$i_temp]['productname'])) . '</option>';
            }
            echo '</select>';
            echo ' &nbsp; <span class="alert">' . $num_products . ' produits trouvés.</span>';
          }
          else
          {
            echo '<input type="text" STYLE="text-align:right" id="product_autocomplete' . $fp_counter . '" autocomplete="off" name="product' . $fp_counter . '" value="' . d_input($product) . '" size=10> ';
            echo d_output($productname);
          }
          
          ###
          if (isset($productid) && $productid)
          {
            echo ' (<b>' . $unittypeA[$product_unittypeidA[$productid]] . '</b>) ',$main_result[$i]['suppliercode'];
          }
          echo '<td><input type=number STYLE="text-align:right" name="quantity'.$i.'" value="'.d_input($quantity).'" size=5>';
          echo '<td><input type=number STYLE="text-align:right" name="quantityrest'.$i.'" value="'.d_input($quantityrest).'" size=5>';
          echo '<td>';
          $datename = 'pallet_exp' . $i;
          if (isset($main_result[$i]['expiredate'])) { $selecteddate = $main_result[$i]['expiredate']; }
          require('inc/datepicker.php');
        }
      }
      echo '<tr><td colspan=10 align=center>
      <input type=hidden name="custommenu" value="' . $custommenu . '"><input type=hidden name="maxnumpallets" value="' . $maxnumpallets . '">
      <input type=hidden name="saveme" value="1"><input type=hidden name="placementid" value="' . $placementid . '">
      <input type="submit" value="Valider"></td></tr>
      </table></form>';
    }

    if ($placementid < 1)
    {
      echo '<h2><a href="custom.php?custommenu=setplacement">Comptage emplacement</a> ';
      if (isset($placementA[$placementid])) { echo $placementA[$placementid]; }
      echo '</h2>
      <form method="post" action="custom.php"><table border=0 cellpadding=1 cellspacing=1>
      <tr><td>Emplacement:</td><td><input autofocus type="text" STYLE="text-align:right" name="placementname" size=20></td></tr>
      <tr><td>Numéro de palettes par emplacement:</td><td><input type="number" STYLE="text-align:right" name="maxnumpallets" value=4 size=5></td></tr>
      <tr><td colspan=2><input type=hidden name="custommenu" value="' . $custommenu . '">
      <input type="submit" value="Valider"></td></tr>
      </table></form>'; # hardcoded to 4
    }
  break;
  
  case 'mapcoord_import':
  # config
  $separator = ',';

  echo '<h2>Import map coords</h2>';

  if ($_POST['importme'] == 1)
  {
    $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
    $i = 0;
    echo '<table class=report>';
    while ($line=fgets($fp))
    {
      $i++;
      $lineA = explode($separator, $line);
      
      if ($i == 1) { echo '<thead><th>Placement<th>mapid<th>x<th>y<th>x_stop<th>y_stop</thead>'; }
      
      echo '<tr>';
      echo '<td>' . $lineA[1];
      echo '<td>' . $lineA[12];
      echo '<td>' . $lineA[13];
      echo '<td>' . $lineA[14];
      echo '<td>' . $lineA[15];
      echo '<td>' . $lineA[16];
      
      $query = 'update placement set mapid=?,map_start_x=?,map_start_y=?,map_stop_x=?,map_stop_y=? where placementname=?';
      $query_prm = array($lineA[12],$lineA[13],$lineA[14],$lineA[15],$lineA[16],$lineA[1]);
      require('inc/doquery.php');
      if ($num_results) { echo '<td>Updated'; }
      else { echo '<td>'; }

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
  
  case 'redosage':
  switch($currentstep)
  {
    case 0:
    ?><h2>Re-exporter journée</h2>
   
    <form method="post" action="custom.php"><table>
    <?php
    echo '<tr><td>Date:</td><td>';
    $datename = 'startdate';
    require('inc/datepicker.php');
    echo '</td></tr>';

    ?>
    <tr><td colspan=2>
    <input type=hidden name="step" value="1"><input type=hidden name="custommenu" value="redosage">
    <input type="submit" value="Re-exporter journée"></td></tr>
    </table></form>
    <?php
    break;

    case 1:
    $datename = 'startdate'; require('inc/datepickerresult.php');
  
    $query = 'update invoicehistory set exported=0 where accountingdate=?';
    ###
    # ignore invoices with decimals (this problem will not repeat itself)
    /*$query .= '
    and invoiceid<>580342
    and invoiceid<>580326
    ';*/
    ###
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $query = 'update payment set exported=0 where paymentdate=?';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $query = 'update adjustmentgroup set exported=0 where adjustmentdate=?';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $query = 'update purchasebatch set exported=0 where arrivaldate=?';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    
    echo datefix2($startdate) . ' peut etre re-exporté';
    
    break;
  }
  break;

  case 'transferstock':
  exit; # disabled
  if ($_SESSION['ds_userid'] != 1) { echo 'que pour TEM admin'; exit; }
  $year = 2016; # set manually
  $lastyear = $year - 1;

  # ?
  $query = 'select productid,stock from endofyearstock where year=?';
  $query_prm = array($year);
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0; $i < $num_results_main; $i++)
  {
    $query = 'select endofyearstockid from endofyearstock where productid=? and year=2014';
    $query_prm = array($main_result[$i]['productid']);
    require('inc/doquery.php');
    if ($num_results) { $query = 'update endofyearstock set stock=? where productid=? and year=?'; }
    else { $query = 'insert into endofyearstock (stock,productid,year) values (?,?,?)'; }
    $query_prm = array($main_result[$i]['stock'],$main_result[$i]['productid'],2014);
    require('inc/doquery.php');
    echo '<br>Setting productid '.$main_result[$i]['productid'].' stockunits '.$main_result[$i]['stock'];
  }


  # ?
  $query = 'SELECT monthlystock.productid,stock
  FROM monthlystock,product
  where product.productid=monthlystock.productid and month=12 and year=2014 and supplierid=4126 and stock>0';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0; $i < $num_results_main; $i++)
  {
    $query = 'select endofyearstockid from endofyearstock where productid=? and year=2014';
    $query_prm = array($main_result[$i]['productid']);
    require('inc/doquery.php');
    if ($num_results) { $query = 'update endofyearstock set stock=? where productid=? and year=?'; }
    else { $query = 'insert into endofyearstock (stock,productid,year) values (?,?,?)'; }
    $query_prm = array($main_result[$i]['stock'],$main_result[$i]['productid'],2014);
    require('inc/doquery.php');
    echo '<br>Setting productid '.$main_result[$i]['productid'].' stockunits '.$main_result[$i]['stock'];
  }
  
  # fix dmp 1000
  $query = 'select productid from product where unittypeid=6 and supplierid<>4126 and discontinued=0;';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0; $i < $num_results_main; $i++)
  {
    $query = 'select endofyearstockid,stock from endofyearstock where year=? and productid=?';
    $query_prm = array($lastyear, $main_result[$i]['productid']);
    require('inc/doquery.php');
    if ($num_results)
    {
      $kladd = $query_result[0]['stock'] * 1000;
      $query = 'update endofyearstock set stock='.$kladd.' where endofyearstockid='.$query_result[0]['endofyearstockid'];
      $query_prm = array();
      require('inc/doquery.php');
      echo $query . '<br>';
    }
  }
  echo '<br>Done';
  break;

  case 'tracing':
  echo '<h2>Tracabilité</h2>
  <form method="post" action="customreportwindow.php" target="_blank">
  <table>
  <tr><td>'; require('inc/selectproduct.php');
  echo '<tr><td>Lot:</td><td><input type=text STYLE="text-align:right" name="batchname" size=20</td></tr>
  <tr><td>Lot fournisseur:</td><td><input type=text STYLE="text-align:right" name="supplierbatchname" size=20</td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="report" value="tracing"><input type="submit" value="Valider"></td></tr>
  </table></form>';
  break;

  case 'sellbydate':
  ?><h2><?php echo d_trad('sellbydate:');?></h2>
  <p class=alert>Ce rapport remplacé dans module Rapport</p>
  <form method="post" action="customreportwindow.php" target="_blank">
  <table>
  <tr>
    <td><?php echo d_trad('numberofdays:');?></td>
    <td><input type="text" STYLE="text-align:right" name="days" value=180 size=10></td>
  </tr>
  <tr><td><?php require('inc/selectproduct.php');?></td></tr>
  <?php
  $dp_itemname='productdepartment';$dp_description = d_trad('department'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
  $dp_description = d_trad('family'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamilygroup.php');
  $dp_description = d_trad('subfamily'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamily.php');
  ?>
  <tr><td><?php echo d_trad('supplier'); ?> (numéro): <td><input type=text name=supplierid> &nbsp;<?php echo d_trad('exclude');?><input type="checkbox" name="excludesupplier" value="1"></td></tr>
  <tr><td><?php echo d_trad('temperature:');?></td><td><?php
  $dp_itemname = 'temperature'; $dp_allowall = 1;#$dp_selectedid = -1;
  require('inc/selectitem.php');
  ?></td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="report" value="sellbydatereport"><input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
  </table>
  </form>
  <?php
  break;

  case 'insurancereport':
  ?><h2>Rapport assurance:</h2>
  <p class=alert>Ce rapport est à TESTER - mis à jour 11 09 2020</p>
  <form method="post" action="customreportwindow.php" target="_blank"><table>
  <tr><td>Début de rapport:</td><td><?php
  $datename = 'startdate'; require('inc/datepicker.php');
  ?></td></tr>
  <tr><td>Fin de rapport:</td><td><?php
  $datename = 'stopdate'; require('inc/datepicker.php');
  ?></td></tr>
  <?php
  $dp_itemname = 'localvessel'; $dp_description = 'Navire'; $dp_noblank = 1;   require('inc/selectitem.php');
  ?>
  <tr><td>Produit assurance:</td><td><input type=text STYLE="text-align:right" name="productid" value=4204 size=5>
  <tr><td>Produit frêt congelé:</td><td><input type=text STYLE="text-align:right" name="productid_freight_frozen" value=4203 size=5>
  <tr><td>Produit frêt maritime:</td><td><input type=text STYLE="text-align:right" name="productid_freight" value=4206 size=5>
  <tr><td colspan="2" align="center"><input type=hidden name="report" value="insurancereport"><input type="submit" value="Valider"></td></tr>
  </table></form><?php

  break;

  case '1client':
  ?><h2>Ventes annuels:</h2>
  <form method="post" action="customreportwindow.php" target="_blank"><table>
  <tr><td>Compte client:</td><td><input autofocus type="text" STYLE="text-align:right" name="clientid" size=5></td></tr>
  <tr><td>Categorie de client:</td>
  <td><select name="categoryid"><?php
  echo '<option value="0">Tous</option>';
  
  $query = 'select clientcategoryid,clientcategoryname from clientcategory order by clientcategoryname';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $num_results = mysql_num_rows($result);
  for ($i=0; $i < $num_results; $i++)
  {
    $row = mysql_fetch_array($result);
    echo '<option value="' . $row['clientcategoryid'] . '">' . $row['clientcategoryname'] . '</option>';
  }
  ?></select></td></tr>
  <?php
  
  $query = 'select curdate() as curdate;';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $row = mysql_fetch_array($result);
  $year = substr($row['curdate'],0,4);
  ?><tr><td>Année:</td><td><select name="year"><?php
  for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
  {
    if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select></td></tr>
  <tr><td>Produits:</td><td><select name="myprods"><option value=0>Tous</option>
  <option value=1>Nestlé</option><option value=2>Wing Chong</option></td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="report" value="1client"><input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  case 'notexported':
  ?><h2>Non exporté:</h2>
  <form method="post" action="customreportwindow.php" target="_blank"><table>
  <tr><td>Début de rapport:</td><td><?php
  $datename = 'startdate'; require('inc/datepicker.php');
  ?></td></tr>
  <tr><td>Fin de rapport:</td><td><?php
  $datename = 'stopdate'; require('inc/datepicker.php');
  ?></td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="report" value="notexported"><input type="submit" value="Valider"></td></tr>
  </table></form><?php

  break;

  case 'fc':
  ?><h2>Feuille de commande:</h2>
  <form method="post" action="customreportwindow.php" target="_blank"><table>
  <tr><td><?php
  $noblockedclients = 1;
  require('inc/selectclient.php');
  ?>
  <tr><td>Produits:</td><td><select name="myprods"><option value=3>Tous</option>
  <option value=1>Nestlé</option><option value=2>Divers</option></select>
  <?php 
  /*<tr><td>Début de rapport:</td><td><?php
  $datename = 'startdate'; require('inc/datepicker.php');
  ?></td></tr>
  <tr><td>Fin de rapport:</td><td><?php
  $datename = 'stopdate'; require('inc/datepicker.php');
  ?></td></tr>
  */ ?>
  <tr><td colspan="2" align="center"><input type=hidden name="report" value="fc"><input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  case 'nestlevolume':
  ?><h2>Nestlé Volume:</h2>
  <p class=alert>Avant d'executer ce rapport, executer Cadence Stock pour fournisseur 4126 avec l'option "Mettre à jour stock par mois"</p>
 <!-- <p class=alert>uses CURRENT stock</p> -->
  <form method="post" action="customreportwindow.php" target=_blank><table><?php
  
  $month = substr($_SESSION['ds_curdate'],5,2);
  $year = substr($_SESSION['ds_curdate'],0,4);
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
  <tr><td colspan="2" align="center">
  <input type=hidden name="report" value="nestlevolume">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  case 'promnestle1':
  ?><h2>Promo Nestlé:</h2>
  <form method="post" action="customreportwindow.php" target=_blank><table>
  <tr><td colspan="2" align="center"><input type=hidden name="report" value="promnestle1"><input type="submit" value="Valider"></td></tr></table></form>
  <?php
  break;

  case 'bdlvalue':
  ?><h2>Valeur BdL:</h2>
  <form method="post" action="customreportwindow.php" target=_blank><table>
  <tr><td>De:</td><td><?php
  $datename = 'startdate';
  require('inc/datepicker.php');
  ?></td></tr>
  <tr><td>à:</td><td><?php
  $datename = 'stopdate';
  require('inc/datepicker.php');
  echo '<tr><td>Ranger par:<td><select name="orderby"><option value=0>Date</option><option value=1>Type de produit</option><option value=2>Client</option></select>';
  ?>
  <tr><td colspan="2" align="center"><input type=hidden name="report" value="bdlvalue"><input type="submit" value="Valider"></td></tr></table></form>
  <?php
  break;

  case 'dlvs':
  ?><h2>Rapport detaillé des DLVs:</h2>
  <form method="post" action="customreportwindow.php" target="_blank"><table>
  <tr><td>Numéro de produit:</td><td><input type="text" STYLE="text-align:right" name="productid" size=5> (optionnel)</td></tr>
  <tr><td>Famille de produits:</td>
  <td><select name="productfamilyid"><?php
  echo '<option value="0">Tous</option>';
  
  $query = 'select productfamilyid,productfamilyname,productfamilygroupname from productfamily,productfamilygroup where productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid order by productfamilygroupname,productfamilyname';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $num_results = mysql_num_rows($result);
  for ($i=0; $i < $num_results; $i++)
  {
    $row = mysql_fetch_array($result);
    echo '<option value="' . $row['productfamilyid'] . '">' . $row['productfamilygroupname'] . '/' . $row['productfamilyname'] . '</option>';
  }
  ?></select> (optionnel) </td></tr>
  <tr><td>Expire au plus tôt:</td><td><select name="beginday"><?php
  for ($i=1; $i <= 31; $i++) { echo '<option value="' . $i . '">' . $i . '</option>'; }
  ?></select><select name="beginmonth"><?php
  for ($i=1; $i <= 12; $i++) { echo '<option value="' . $i . '">' . $i . '</option>'; }
  ?></select><select name="beginyear"><?php
  for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
  {
    if ($i == $_SESSION['year']) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select></td></tr>
  <tr><td>Expire au plus tard:</td><td><select name="stopday"><?php
  for ($i=1; $i <= 31; $i++)
  {
    if ($i == $_SESSION['day']) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="stopmonth"><?php
  for ($i=1; $i <= 12; $i++)
  {
    if ($i == $_SESSION['month']) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="stopyear"><?php
  for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
  {
    if ($i == $_SESSION['year']) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="report" value="dlvs"><input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  case 'cadstockncsv':
  switch($currentstep)
  {

    # Confirm
    case 0:
    ?><h2>Créer fichier CAD STOCK NESTLE CSV</h2>
    <form method="post" action="custom.php"><table><?php
    $month = substr($_SESSION['ds_curdate'],5,2);
    $year = substr($_SESSION['ds_curdate'],0,4);
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
    ?></select>
    <tr><td colspan=2>
    <input type=hidden name="step" value="1"><input type=hidden name="custommenu" value="cadstockncsv">
    <input type="submit" value="Créer fichier ">
    </table></form>
    <?php
    break;

    # Make file
    case 1:
set_time_limit (60*10);
$filename = 'customfiles/cadence_stock_nestle_' . date("Y_m_d_H_i_s") . '.csv';
$file = fopen($filename, "w");
if (!$file) { exit; }

$year = (int) $_POST['year'];
$currentmonth = (int) $_POST['month'];
$executiondate = $_SESSION['ds_curdate'];

  ### Stock by year ###


  $su = 0;
  $postsupplierid = 4126;
  $postproductid = "";
  $productfamilygroupid = 0;
  $productdepartmentid = 0;
  $nonestle = 0;
  $sep = chr(13) . chr(10);

  $lastyear = ($year - 1);
  $nextyear = $lastyear + 2;
  

  $query = 'select suppliercode,productid,numberperunit,netweightlabel,productname,productfamilyname,productfamilygroupname,productdepartmentname from product,productfamily,productfamilygroup,productdepartment where discontinued=0 and product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and productfamilygroup.productdepartmentid=productdepartment.productdepartmentid';
  if ($postproductid != "") { $postproductid = (int) $postproductid; $query = $query . ' and productid="' . $postproductid . '"'; }
  if ($postsupplierid != "") { $postsupplierid = (int) $postsupplierid; $query = $query . ' and supplierid="' . $postsupplierid . '"'; }
  if ($productfamilygroupid != 0) { $query = $query . ' and productfamily.productfamilygroupid="' . $productfamilygroupid . '"'; }
  if ($productdepartmentid != 0) { $query = $query . ' and productfamilygroup.productdepartmentid="' . $productdepartmentid . '"'; }
  if ($nonestle != 0) { $query = $query . ' and supplierid<>126'; }
  $query = $query . ' order by departmentrank,familygrouprank,familyrank';
  #$query = $query . ' limit 3'; # debug

#$reportstring = $query . $sep; # debug
#fwrite($file, $reportstring); # debug

$reportstring = '# cadence stock nestlé ' . $currentmonth . ' ' . $year . $sep;
$reportstring = $reportstring . '# Code,Num,Produit,Vente,BdL,Total,Achat,Stock' . $sep;
  fwrite($file, $reportstring);

  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result;
  $num_results34 = $num_results;

  for ($xyz=1;$xyz <= $num_results34; $xyz++)
  {
    $reportstring = "";
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

    $row34 = $main_result[($xyz-1)];
    $productid = $row34['productid'];
    $suppliercode = $row34['suppliercode'];
    $numberperunit = $row34['numberperunit']; if ($numberperunit == 0) { $numberperunit = 1; }
    $cond = $row34['numberperunit'] . ' x ' . $row34['netweightlabel'];
    $productname = $row34['productname']; $productname = str_replace(",", " ", $productname);
    $productfamilyname = $row34['productfamilyname']; $productfamilyname = str_replace(",", " ", $productfamilyname);
    $productfamilygroupname = $row34['productfamilygroupname']; $productfamilygroupname = str_replace(",", " ", $productfamilygroupname);
    $productdepartmentname = $row34['productdepartmentname']; $productdepartmentname = str_replace(",", " ", $productdepartmentname);

    $lastpdn = $productdepartmentname;
    $lastpfgn = $productfamilygroupname;
    $lastpfn = $productfamilyname;

  ### start single product report ###

  $query = 'select stock from endofyearstock where year="' . $lastyear . '" and productid="' . $productid . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row = $query_result[0];
  $endyearstock = floor($row['stock'] / $numberperunit);
  $endyearstockunits = $row['stock'] % $numberperunit;

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

############# copy everything from wingchongreportwindow

  $query = 'select sum(quantity) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and isreturn=0 and isnotice=0 and cancelledid=0 and invoiceitem.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $year . '"';
  $query .= ' group by month';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0;$i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
    $sales[$kladd] = $row['sales'];
  }
  $query = 'select sum(quantity) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and isreturn=0 and isnotice=0 and cancelledid=0 and invoiceitemhistory.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $year . '" group by month';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0;$i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
    $sales[$kladd] += $row['sales'];
  }
  for ($i=1;$i <= 12; $i++)
  {
    $salesunits[$i] = $sales[$i] % $numberperunit;
    $sales[$i] =  floor($sales[$i] / $numberperunit);
  }

  $query = 'select sum(quantity) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and isreturn=0 and isnotice <> 0 and cancelledid=0 and invoiceitem.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $year . '" group by month';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0;$i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
    $destock[$kladd] = floor($row['sales'] / $numberperunit); $testcartonspermonth = $testcartonspermonth + $destock[$kladd];
    $destockunits[$kladd] = $row['sales'] % $numberperunit;
  }
  $query = 'select sum(quantity) as sales,DATE_FORMAT(accountingdate,"%c") as month from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and isreturn=0 and isnotice <> 0 and cancelledid=0 and invoiceitemhistory.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $year . '" group by month';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0;$i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
    $destock[$kladd] = $destock[$kladd] + floor($row['sales'] / $numberperunit); $testcartonspermonth = $testcartonspermonth + floor($row['sales'] / $numberperunit);
    $destockunits[$kladd] = $destockunits[$kladd] + $row['sales'] % $numberperunit;
  }
  
  $query = 'select sum(origamount) as purchase,DATE_FORMAT(arrivaldate,"%c") as month from purchasebatch where productid="' . $productid . '" and DATE_FORMAT(arrivaldate,"%Y")="' . $year . '" group by month';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0;$i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $myi = $row['month'];  if ($myi > $highestmonth) { $highestmonth = $myi; }
    $purchase[$myi] = floor($row['purchase'] / $numberperunit);
    $purchaseunits[$myi] = $row['purchase'] % $numberperunit;
  }
  
  $query = 'select sum(quantity) as loss,DATE_FORMAT(accountingdate,"%c") as month from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and isreturn=1 and returntostock=1 and isnotice=0 and cancelledid=0 and invoiceitem.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $year . '" group by month';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0;$i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
    $loss[$kladd] = floor($row['loss'] / $numberperunit);
    $lossunits[$kladd] = $row['loss'] % $numberperunit;
  }
  $query = 'select sum(quantity) as loss,DATE_FORMAT(accountingdate,"%c") as month from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and isreturn=1 and returntostock=1 and isnotice=0 and cancelledid=0 and invoiceitemhistory.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $year . '" group by month';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0;$i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
    $loss[$kladd] += floor($row['loss'] / $numberperunit);
    $lossunits[$kladd] += $row['loss'] % $numberperunit;
  }
  
  $query = 'select sum(quantity) as loss,DATE_FORMAT(accountingdate,"%c") as month from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and isreturn=1 and isnotice=1 and cancelledid=0 and invoiceitem.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $year . '" group by month';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0;$i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
    $loss2[$kladd] = floor($row['loss'] / $numberperunit);
    $lossunits2[$kladd] = $row['loss'] % $numberperunit;
  }
  $query = 'select sum(quantity) as loss,DATE_FORMAT(accountingdate,"%c") as month from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and isreturn=1 and isnotice=1 and cancelledid=0 and invoiceitemhistory.productid="' . $productid . '" and DATE_FORMAT(accountingdate,"%Y")="' . $year . '" group by month';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0;$i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
    $loss2[$kladd] += floor($row['loss'] / $numberperunit);
    $lossunits2[$kladd] += $row['loss'] % $numberperunit;
  }
  
  $query = 'select productid,sum(netchange) as netchange,DATE_FORMAT(changedate,"%c") as month from modifiedstock where productid="' . $productid . '" and DATE_FORMAT(changedate,"%Y")="' . $year . '" group by month';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0;$i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $kladd = $row['month']; if ($kladd > $highestmonth) { $highestmonth = $kladd; }
    $netchange[$kladd] = floor($row['netchange'] / $numberperunit);
    $netchangeunits[$kladd] = $row['netchange'] % $numberperunit;
    if ($netchange[$kladd] < 0 && $netchangeunits[$kladd] <> 0) { $netchange[$kladd] = $netchange[$kladd] + 1; } # hack for floor on negative numbers
  }

############

$reportstring = $reportstring . $suppliercode . ',';
$reportstring = $reportstring . $productid;
  $result = $endyearstock; $totalsales = 0; $totalloss = 0; $totalloss2 = 0; $totalpurchase = 0; $totaldestock = 0; $totalnetchange = 0; $counter = 0;
  $resultunits = $endyearstockunits;
### LOOP LINE 1
  for ($i=1;$i <= 12; $i++)
  {
if ($i == 1) { $reportstring = $reportstring . ',' . $productname; }
if ($i == $currentmonth) { $reportstring = $reportstring . ',' . ($sales[$i]+0); }
/*
$result = $result - $sales[$i] - $destock[$i] + $loss[$i] + $loss2[$i] + $purchase[$i] + $netchange[$i];
    $resultunits = $resultunits - $salesunits[$i] - $destockunits[$i] + $lossunits[$i] + $loss2units[$i] + $purchaseunits[$i] + $netchangeunits[$i];
#echo '<br>result=' . $result . ' resultunits=' . $resultunits;
    $kladdresult = ($result * $numberperunit) + $resultunits;
    $result = floor($kladdresult / $numberperunit);
    $resultunits = $kladdresult % $numberperunit; if ($result < 0 && $resultunits <> 0) { $result = $result + 1; }
    $monthresult[$i] = $result;
*/
    $result = $result - $sales[$i] - $destock[$i] + $loss[$i] + $loss2[$i] + $purchase[$i] + $netchange[$i];
    /*
    echo '<br>sales=' . $sales[$i];
    echo '<br>destock=' . $destock[$i];
    echo '<br>loss=' . $loss[$i];
    echo '<br>loss2=' . $loss2[$i];
    echo '<br>loss=' . $purchase[$i];
    echo '<br>loss=' . $netchange[$i];
    */
    $resultunits = $resultunits - $salesunits[$i] - $destockunits[$i] - $lossunits[$i] + $purchaseunits[$i] + $netchangeunits[$i];
    #echo '<br>result=' . $result . ' resultunits=' . $resultunits;
    $kladdresult = ($result * $numberperunit) + $resultunits;
    $result = floor($kladdresult / $numberperunit);
    $resultunits = $kladdresult % $numberperunit; if ($result < 0 && $resultunits <> 0) { $result = $result + 1; }
    $monthresult[$i] = $result;
    $monthresultunits[$i] = $resultunits;
    $totalsales = $totalsales + ($sales[$i] * $numberperunit) + $salesunits[$i];
    $totaldestock = $totaldestock + ($destock[$i] * $numberperunit) + $destockunits[$i];
    $totalloss = $totalloss + ($loss[$i] * $numberperunit) + $lossunits[$i];
    $totalloss2 = $totalloss2 + ($loss2[$i] * $numberperunit);
    $totalpurchase = $totalpurchase + ($purchase[$i] * $numberperunit) + $purchaseunits[$i];
    $totalnetchange = $totalnetchange + ($netchange[$i] * $numberperunit) + $netchangeunits[$i];
    if ($sales[$i] > 0 || $destock[$i] > 0 || $loss[$i] > 0 || $purchase[$i] > 0) { $counter = $i; }
    if ($counter == 0) { $counter = 1; }
  }
### LOOP LINE 2
### LOOP LINE 3
  for ($i=1;$i <= 12; $i++)
  {
if ($i == $currentmonth) {  $reportstring = $reportstring . ',' . ($destock[$i]+0); }
if ($i == $currentmonth) {  $reportstring = $reportstring . ',' . ($sales[$i] + $destock[$i] + 0); }
  }
### LOOP LINE 4
### LOOP LINE 5
  for ($i=1;$i <= 12; $i++)
  {
if ($i == $currentmonth) { $reportstring = $reportstring . ',' . ($purchase[$i]+0); }
  }
### LOOP LINE 6
### END DISPLAY LOOP

/*
    $query = 'select stock from monthlystock where year="' . $year . '" and month="' . $currentmonth . '" and productid="' . $productid . '"';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $row = mysql_fetch_array($result);
    */
    $reportstring = $reportstring . ',' . $monthresult[$currentmonth] . $sep;
  $supertotal = 0;
  ### end single product report ###
fwrite($file, $reportstring);

  }

$reportstring = '# fin';
fwrite($file, $reportstring);
fclose($file);

echo '<p>Fichier <a href="customfiles/' . basename($filename) . '"><font color=blue><i>' . basename($filename) . '</i></font></a> créé.</p><p>- Cliquer sur le bouton droit de la souris</p>';
break;
}
  break;

  case 'caddlv':
  ?><form method="post" action="customreportwindow.php" target="_blank">

  Combien de jours: <input autofocus type="text" STYLE="text-align:right" name="days" value=180 size=10><br>
  <input type="radio" name="mycat" value="0" CHECKED> Wing Chong<br>
  <input type="radio" name="mycat" value="5"> Wing Chong Réfrigéré<br>
  <input type="radio" name="mycat" value="4"> Wing Chong Surgelé<br>
  <?php #<input type="radio" name="mycat" value="1"> Nestlé Surgelé<br>
  ?>
  <input type="radio" name="mycat" value="2"> Nestlé Petfood<br>
  <input type="radio" name="mycat" value="3"> Nestlé Grocery<br>
  <br>
  <input type=hidden name="report" value="caddlv"><input type="submit" value="Catalogue des DLVs"></form><?php
  break;

  ### Create SAGE import file ###
  ###################################### 2013 04 21 optimising, single day exports only
    case 'tosage2':
  switch($currentstep)
  {

    # Confirm
    case 0:
    ?><h2>Créer fichier à importer dans SAGE</h2>
   
    <form method="post" action="custom.php"><table>
    <?php
    echo '<tr><td>Date:</td><td>';
    $datename = 'startdate';
    require('inc/datepicker.php');
    echo '</td></tr>';
    /*
    echo '<tr><td>Fin:</td><td>';
    $datename = 'stopdate';
    require('inc/datepicker.php');
    echo '</td></tr>';
    */
    #<tr><td>Max lignes (par type):</td><td><input type=text STYLE="text-align:right" name="limit" value="100"></td></tr>
    ?>
    <tr><td colspan=2>
    <input type=hidden name="step" value="1"><input type=hidden name="custommenu" value="tosage2">
    <input type="submit" value="Créer fichier SAGE"></td></tr>
    </table></form>
    <br><p><b>infos:</b><br>
    FRET: produit 4203 et 4206<br>
    ASSURANCE: produit 4204<br>
    deposit banks are hardcoded: CCP, BT, SOC, BP
    </p>
    <?php
    break;

    # Make file
    case 1:
    #set_time_limit ( 60*60 );
    ini_set('max_execution_time', 600);
    $sep = chr(9); # tab
    $endline = chr(13) . chr(10);
    
    /*
    $exportyear = "99999";
    $query = 'select year(curdate()) as year';
    $query_prm = array();
    require('inc/doquery.php');
    $exportyear = $query_result[0]['year'];
    if ($_POST['allinvoices'] == 1)
    {
      $exportyear--;
    }
    if ($exportyear < 1900) { exit; }
    */
    
    #$limit = (int) $_POST['limit'];
    $limit = 10000;
    $datename = 'startdate'; require('inc/datepickerresult.php');
    $stopdate = $startdate; #not used

    echo '<h2>Export SAGE '.datefix($startdate).'</h2>';

    # Account Number definitions
    $anclient = "411000"; # show clientid + payby
    $ansalesnet = "707250";
    $anvat2 = "445711"; # 5%
    $anvat3 = "445715"; # 10%
    $anvat4 = "445712"; # 16%
    $anfreight = "707301";
    $aninsurance = "707310";
    $anlocalpurchase = "601000";
    $anlocalvat = "445660";
    $anlocalsupplier = "401000"; # show clientid + payby
    $animport = "602000";
    $animportvat = "445660";
    $anportfees = "608000";
    $animportsupplier = "402000";
    $anporttaxes = "444000";
    $anOD = "654000";
    $anBC = "514000";
    $anBT = "512100";
    $anSOC = "515000";
    $anBP = "512000";
    $anCS = "530000";
    $ansalarydeduct = "421000";
    $anlossorprofit = "654000";

    #$filename = 'customfiles/sage' . date("Y_m_d_H_i_s") . '.txt';
    $filename = '/var/www/html/customfiles/sage' . date("Y_m_d_H_i_s") . '.txt';
    $file = fopen($filename, "w");
    if (!$file) { echo "Cannot create the file!<br>"; var_dump(error_get_last()); exit; }
    
    $writebuffer = '';
    
    #$writebuffer = 'test'.$sep.'tab' . $newline;
    #fwrite($file, $writebuffer);

/*
    # clients
    $query = 'select clientid,clientname,contact,postaladdress,postalcode,townname,islandname,tahitinumber,telephone,fax,email from client,town,island where client.townid=town.townid and town.islandid=island.islandid and exported=0';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      $id = 'C' . $row['clientid'];
      $writebuffer = makeMPCT($id,$row['clientname'],'0',$anclient,$row['contact'],$row['postaladdress'],$row['islandname'],$row['postalcode'],$row['townname'],"Polynésie Française",$row['tahitinumber'],$row['telephone'],$row['fax'],$row['email'],"");
      fwrite($file, $writebuffer);
      $query = 'update client set exported=1,exportdate=curdate() where clientid="' . $row['clientid'] . '"';
      $result2 = mysql_query($query, $db_conn); querycheck($result2);
    }

*/

    ### invoices
    $vat2 = 0; $vat3 = 0; $vat4 = 0; $freight = 0; $insurance = 0; $total = 0;
    $mainquery = 'select sum(round(linevat)) as oursum from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
    and cancelledid=0 and isnotice=0 and isreturn=0 and confirmed=1 and accountingdate=? and invoicehistory.exported=0';
    
    $query = $mainquery . ' and linetaxcodeid=2';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $vat2 = $query_result[0]['oursum'];
    
    $query = $mainquery . ' and linetaxcodeid=3';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $vat3 = $query_result[0]['oursum'];
    
    $query = $mainquery . ' and linetaxcodeid=4';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $vat4 = $query_result[0]['oursum'];
    
    $mainquery = 'select sum(round(lineprice)) as oursum from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
    and cancelledid=0 and isnotice=0 and isreturn=0 and confirmed=1 and accountingdate=? and invoicehistory.exported=0';
    
    $query = $mainquery . ' and (productid=4206 or productid=4203)';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $freight = $query_result[0]['oursum'];

    $query = $mainquery . ' and productid=4204';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $insurance = $query_result[0]['oursum'];
    
    $query = 'select reference,invoiceid,invoicehistory.clientid,date_format(accountingdate,"%d%m%y") as showdate,round(invoiceprice) as invoiceprice,clientname,date_format(paybydate,"%d%m%y") as paybydate
    from invoicehistory,client
    where invoicehistory.clientid=client.clientid and cancelledid=0 and isnotice=0 and confirmed=1 and 
    accountingdate=?
    and isreturn=0 and invoicehistory.exported=0
    order by accountingdate,invoiceid';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $main_result = $query_result; $num_results_main = $num_results;
    #if ($num_results_main >= $limit) { echo '<p class="alert">Limite atteinte (avoirs): Créer encore un autre fichier pour cette periode.</p>'; }
    
    for ($i=0; $i < $num_results_main; $i++)
    {
      $row = $main_result[$i];

      # invoice debit, avoir credit
      $writebuffer .= 'VT' . $sep;
      $writebuffer .= $row['showdate'] . $sep;
      $writebuffer .= 'FA' . $row['invoiceid'] . $sep;
      $writebuffer .= 'FA' . $row['invoiceid'] . $sep;
      $writebuffer .= $anclient . $sep;
      $writebuffer .= 'C' . $row['clientid'] . $sep;
      $writebuffer .= substr(str_replace ($sep, ' ', 'FA' . $row['invoiceid'] . ' ' . trim(d_decode($row['clientname'])) . ' ' . trim($row['reference'])),0,35) . $sep; #FA
      $writebuffer .= myround($row['invoiceprice']) . $sep; # debit
      $writebuffer .= $sep; # credit
      $writebuffer .= $row['paybydate'] . $sep;
      $writebuffer .= $endline;
      
      $total += myround($row['invoiceprice']);

    }
    $lastshowdate = $row['showdate'];
    #subtotal by day
    # $anvat2, credit
    if ($vat2 > 0)
    {
      $writebuffer .= 'VT' . $sep;
      $writebuffer .= $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $anvat2 . $sep;
      $writebuffer .= $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $sep; # debit
      $writebuffer .= myround($vat2) . $sep; # credit
      $writebuffer .= $sep; # payby
      $writebuffer .= $endline; # reference
    }
    # $anvat3, credit
    if ($vat3 > 0)
    {
      $writebuffer .= 'VT' . $sep;
      $writebuffer .= $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $anvat3 . $sep;
      $writebuffer .= $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $sep; # debit
      $writebuffer .= myround($vat3) . $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= $endline;
    }
    # $anvat4, credit
    if ($vat4 > 0)
    {
      $writebuffer .= 'VT' . $sep;
      $writebuffer .= $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $anvat4 . $sep;
      $writebuffer .= $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $sep; # debit
      $writebuffer .= myround($vat4) . $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= $endline;
    }
    # $anfreight, credit
    if ($freight > 0)
    {
      $writebuffer .= 'VT' . $sep;
      $writebuffer .= $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $anfreight . $sep;
      $writebuffer .= $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $sep; # debit
      $writebuffer .= myround($freight) . $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= $endline;
    }
    # $aninsurance, credit
    if ($insurance > 0)
    {
      $writebuffer .= 'VT' . $sep;
      $writebuffer .= $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $aninsurance . $sep;
      $writebuffer .= $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $sep; # debit
      $writebuffer .= myround($insurance) . $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= $endline;
    }
    # $ansalesnet, credit
    $totalnet = $total - $vat2 - $vat3 - $vat4 - $freight - $insurance;
    if ($totalnet > 0)
    {
      $writebuffer .= 'VT' . $sep;
      $writebuffer .= $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $ansalesnet . $sep;
      $writebuffer .= $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $sep; # debit
      $writebuffer .= myround($totalnet) . $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= $endline;
    }
    

    ### returns
    $vat2 = 0; $vat3 = 0; $vat4 = 0; $freight = 0; $insurance = 0; $total = 0;
    $mainquery = 'select sum(round(linevat)) as oursum from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
    and cancelledid=0 and isnotice=0 and isreturn=1 and confirmed=1 and accountingdate=? and invoicehistory.exported=0';
    
    $query = $mainquery . ' and linetaxcodeid=2';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $vat2 = $query_result[0]['oursum'];
    
    $query = $mainquery . ' and linetaxcodeid=3';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $vat3 = $query_result[0]['oursum'];
    
    $query = $mainquery . ' and linetaxcodeid=4';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $vat4 = $query_result[0]['oursum'];
    
    $mainquery = 'select sum(round(lineprice)) as oursum from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
    and cancelledid=0 and isnotice=0 and isreturn=1 and confirmed=1 and accountingdate=? and invoicehistory.exported=0';
    
    $query = $mainquery . ' and (productid=4206 or productid=4203)';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $freight = $query_result[0]['oursum'];

    $query = $mainquery . ' and productid=4204';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $insurance = $query_result[0]['oursum'];
    
    $query = 'select reference,invoiceid,invoicehistory.clientid,date_format(accountingdate,"%d%m%y") as showdate,round(invoiceprice) as invoiceprice,clientname,date_format(paybydate,"%d%m%y") as paybydate
    from invoicehistory,client
    where invoicehistory.clientid=client.clientid and cancelledid=0 and isnotice=0 and confirmed=1 and 
    accountingdate=?
    and isreturn=1 and invoicehistory.exported=0
    order by accountingdate,invoiceid';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $main_result = $query_result; $num_results_main = $num_results;
    #if ($num_results_main >= $limit) { echo '<p class="alert">Limite atteinte (avoirs): Créer encore un autre fichier pour cette periode.</p>'; }
    
    for ($i=0; $i < $num_results_main; $i++)
    {
      $row = $main_result[$i];

      # invoice debit, avoir credit
      $writebuffer .= 'VT' . $sep;
      $writebuffer .= $row['showdate'] . $sep;
      $writebuffer .= 'AV' . $row['invoiceid'] . $sep;
      $writebuffer .= 'AV' . $row['invoiceid'] . $sep;
      $writebuffer .= $anclient . $sep;
      $writebuffer .= 'C' . $row['clientid'] . $sep;
      $writebuffer .= substr(str_replace ($sep, ' ', 'AV' . $row['invoiceid'] . ' ' . trim(d_decode($row['clientname'])) . ' ' . trim($row['reference'])),0,35) . $sep; #AV
      $writebuffer .= $sep; # debit
      $writebuffer .= myround($row['invoiceprice']) . $sep; # credit
      $writebuffer .= $row['paybydate'] . $sep;
      $writebuffer .= $endline;
      
      $total += $row['invoiceprice'];

    }
    $lastshowdate = $row['showdate'];
    #subtotal by day
    # $anvat2, credit
    if ($vat2 > 0)
    {
      $writebuffer .= 'VT' . $sep;
      $writebuffer .= $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $anvat2 . $sep;
      $writebuffer .= $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= myround($vat2) . $sep; # debit
      $writebuffer .= $sep; # credit
      $writebuffer .= $sep; # payby
      $writebuffer .= $endline; # reference
    }
    # $anvat3, credit
    if ($vat3 > 0)
    {
      $writebuffer .= 'VT' . $sep;
      $writebuffer .= $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $anvat3 . $sep;
      $writebuffer .= $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= myround($vat3) . $sep; # debit
      $writebuffer .= $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= $endline;
    }
    # $anvat4, credit
    if ($vat4 > 0)
    {
      $writebuffer .= 'VT' . $sep;
      $writebuffer .= $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $anvat4 . $sep;
      $writebuffer .= $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= myround($vat4) . $sep; # debit
      $writebuffer .= $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= $endline;
    }
    # $anfreight, credit
    if ($freight > 0)
    {
      $writebuffer .= 'VT' . $sep;
      $writebuffer .= $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $anfreight . $sep;
      $writebuffer .= $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= myround($freight) . $sep; # debit
      $writebuffer .= $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= $endline;
    }
    # $aninsurance, credit
    if ($insurance > 0)
    {
      $writebuffer .= 'VT' . $sep;
      $writebuffer .= $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $aninsurance . $sep;
      $writebuffer .= $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= myround($insurance) . $sep; # debit
      $writebuffer .= $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= $endline;
    }
    # $ansalesnet, credit
    $totalnet = $total - $vat2 - $vat3 - $vat4 - $freight - $insurance;
    if ($totalnet > 0)
    {
      $writebuffer .= 'VT' . $sep;
      $writebuffer .= $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= $ansalesnet . $sep;
      $writebuffer .= $sep;
      $writebuffer .= 'total' . $lastshowdate . $sep;
      $writebuffer .= myround($totalnet) . $sep; # debit
      $writebuffer .= $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= $endline;
    }



  
    
    /*
    # local purchases
    $query = 'select purchasebatchid,supplier.supplierid as supplierid,suppliername,arrivaldate,totalcost,vat from purchasebatch,product,supplier where purchasebatch.productid=product.productid and product.supplierid=supplier.supplierid and purchasebatch.exported=0 and local>0 and totalcost>0 and to_days(curdate())-to_days(arrivaldate) > 1 and DATE_FORMAT(arrivaldate,"%Y")>=' . $exportyear;
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      $reference = 'AL' . $row['purchasebatchid'];
      $id = 'F' . $row['supplierid'];
      $title = $id . ' ' . $row['suppliername'];
      $nettotal = $row['totalcost'] - $row['vat'];
      $writebuffer = makeMECG("AL",$row['arrivaldate'],$reference,"",$anlocalpurchase,"","0","D",$nettotal,$title); fwrite($file, $writebuffer);
      if ($row['vat'] > 0) { $writebuffer = makeMECG("AL",$row['arrivaldate'],$reference,"",$anlocalvat,"","0","D",$row['vat'],$title); fwrite($file, $writebuffer); }
      $writebuffer = makeMECG("AL",$row['arrivaldate'],$reference,"",$anlocalsupplier,$id,"0","C",$row['totalcost'],$title); fwrite($file, $writebuffer);
      $query = 'update purchasebatch set exported=1,exportdate=curdate() where purchasebatchid="' . $row['purchasebatchid'] . '"';
      $result2 = mysql_query($query, $db_conn); querycheck($result2);
    }
    */
    
    $query = 'select purchasebatchid,product.supplierid,clientname,date_format(arrivaldate,"%d%m%y") as showdate,totalcost,vat
    from purchasebatch,product,client
    where purchasebatch.productid=product.productid and product.supplierid=client.clientid and
    arrivaldate=? and purchasebatch.exported=0
    and client.countryid=156
    order by arrivaldate,purchasebatchid';
    $query .= ' limit ' . $limit;
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $main_result = $query_result; $num_results_main = $num_results;
    #if ($num_results_main >= $limit) { echo '<p class="alert">Limite atteinte (achats): Créer encore un autre fichier pour cette periode.</p>'; }
    for ($i=0; $i < $num_results_main; $i++)
    {
      $row = $main_result[$i];
      /*
      $query = 'update purchasebatch set exported=1,exportdate=curdate() where purchasebatchid=?';
      $query_prm = array($row['purchasebatchid']);
      require('inc/doquery.php');
      */
      $reference = 'F' . $row['supplierid'] . ' ' . trim(d_decode($row['clientname']));
      
      # batch itself, credit
      $writebuffer .= 'AL' . $sep;
      $writebuffer .= $row['showdate'] . $sep;
      $writebuffer .= 'AL' .$row['purchasebatchid'] . $sep;#'AL' . 
      $writebuffer .= 'AL' .$row['purchasebatchid'] . $sep;#'AL' . 
      $writebuffer .= $anlocalsupplier . $sep;
      $writebuffer .= 'F' . $row['supplierid'] . $sep;
      $writebuffer .= substr(str_replace ($sep, ' ', $reference),0,35) . $sep;
      $writebuffer .= $sep; # debit
      $writebuffer .= myround($row['totalcost']) . $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= $endline;
      
      # net batch, debit
      $writebuffer .= 'AL' . $sep;
      $writebuffer .= $row['showdate'] . $sep;
      $writebuffer .= 'AL' .$row['purchasebatchid'] . $sep;#'AL' . 
      $writebuffer .= 'AL' .$row['purchasebatchid'] . $sep;#'AL' . 
      $writebuffer .= $anlocalpurchase . $sep;
      $writebuffer .= $sep; #'F' . $row['supplierid'] . 
      $writebuffer .= substr(str_replace ($sep, ' ', $reference),0,35) . $sep;
      $writebuffer .= myround($row['totalcost'] - $row['vat']) . $sep; # debit
      $writebuffer .= $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= $endline;
      
      if ($row['vat'] > 0)
      {
        # vat batch, debit
        $writebuffer .= 'AL' . $sep;
        $writebuffer .= $row['showdate'] . $sep;
        $writebuffer .= 'AL' .$row['purchasebatchid'] . $sep;#'AL' . 
        $writebuffer .= 'AL' .$row['purchasebatchid'] . $sep;#'AL' . 
        $writebuffer .= $anlocalvat . $sep;
        $writebuffer .= $sep; # 'F' . $row['supplierid'] . 
        $writebuffer .= substr(str_replace ($sep, ' ', $reference),0,35) . $sep;
        $writebuffer .= myround($row['vat']) . $sep; # debit
        $writebuffer .= $sep; # credit
        $writebuffer .= $sep;
        $writebuffer .= $endline;
      }
    }
    
    # payments
    $query = 'select paymentcomment as reference,paymentid,clientid,date_format(paymentdate,"%d%m%y") as showdate,value,bankid,depositbankid,paymenttypeid,forinvoiceid,chequeno
    from payment
    where paymentdate=?
    and reimbursement=0 and exported=0
    order by paymentdate,paymentid';
    $query .= ' limit ' . $limit;
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $main_result = $query_result; $num_results_main = $num_results;
    #if ($num_results_main >= $limit) { echo '<p class="alert">Limite atteinte (paiements): Créer encore un autre fichier pour cette periode.</p>'; }
    for ($i=0; $i < $num_results_main; $i++)
    {
      $row = $main_result[$i];
      /*
      $query = 'update payment set exported=1,exportdate=curdate() where paymentid=?';
      $query_prm = array($row['paymentid']);
      require('inc/doquery.php');
      */
      $journalcode = "CS"; $annumber = $anCS;
      if ($row['paymenttypeid'] == 2)
      {
        if ($row['depositbankid'] == 1) { $journalcode = "BC"; $annumber = $anBC; }
        if ($row['depositbankid'] == 2) { $journalcode = "BT"; $annumber = $anBT; }
        if ($row['depositbankid'] == 3) { $journalcode = "SOC"; $annumber = $anSOC; }
        if ($row['depositbankid'] == 4) { $journalcode = "BP"; $annumber = $anBP; }
      }
      if ($row['paymenttypeid'] == 3 || $row['paymenttypeid'] == 4 || $row['paymenttypeid'] == 5)
      {
        if ($row['bankid'] == 1) { $journalcode = "BC"; $annumber = $anBC; }
        if ($row['bankid'] == 2) { $journalcode = "BT"; $annumber = $anBT; }
        if ($row['bankid'] == 3) { $journalcode = "SOC"; $annumber = $anSOC; }
        if ($row['bankid'] == 4) { $journalcode = "BP"; $annumber = $anBP; }
      }
      if ($row['paymenttypeid'] == 6) { $journalcode = "OD"; $annumber = $ansalarydeduct; }
      if ($row['paymenttypeid'] == 7) { $journalcode = "OD"; $annumber = $anlossorprofit; }
      
      # payment itself, credit
      $writebuffer .= $journalcode . $sep;
      $writebuffer .= $row['showdate'] . $sep;
      $writebuffer .= 'P' . $row['paymentid'] . $sep;
      if ($row['forinvoiceid'] > 0) { $writebuffer .= 'FA' . $row['forinvoiceid'] . $sep; }
      else { $writebuffer .= $sep; }
      $writebuffer .= $anclient . $sep;
      $writebuffer .= 'C' . $row['clientid'] . $sep;
      $writebuffer .= substr(str_replace ($sep, ' ', trim($row['reference'])),0,35) . $sep;
      $writebuffer .= $sep; # debit
      $writebuffer .= myround($row['value']) . $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= substr($row['chequeno'],0,17) . $endline;
      
      # counterpart, debit
      $writebuffer .= $journalcode . $sep;
      $writebuffer .= $row['showdate'] . $sep;
      $writebuffer .= 'P' . $row['paymentid'] . $sep;
      $writebuffer .= $sep; # 'P' . $row['paymentid'] . 
      $writebuffer .= $annumber . $sep;
      $writebuffer .= $sep;
      $writebuffer .= substr(str_replace ($sep, ' ', trim($row['reference'])),0,35) . $sep;
      $writebuffer .= myround($row['value']) . $sep; # debit
      $writebuffer .= $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= substr($row['chequeno'],0,17) . $endline;
      
    }
    
    # reimbursements, copy from payments
    $query = 'select paymentcomment as reference,paymentid,clientid,date_format(paymentdate,"%d%m%y") as showdate,value,bankid,depositbankid,paymenttypeid,forinvoiceid,chequeno
    from payment
    where paymentdate=?
    and reimbursement=1 and exported=0
    order by paymentdate,paymentid';
    $query .= ' limit ' . $limit;
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $main_result = $query_result; $num_results_main = $num_results;
    #if ($num_results_main >= $limit) { echo '<p class="alert">Limite atteinte (remboursements): Créer encore un autre fichier pour cette periode.</p>'; }
    for ($i=0; $i < $num_results_main; $i++)
    {
      $row = $main_result[$i];
      /*
      $query = 'update payment set exported=1,exportdate=curdate() where paymentid=?';
      $query_prm = array($row['paymentid']);
      require('inc/doquery.php');
      */
      $journalcode = "CS"; $annumber = $anCS;
      if ($row['paymenttypeid'] == 2)
      {
        if ($row['depositbankid'] == 1) { $journalcode = "BC"; $annumber = $anBC; }
        if ($row['depositbankid'] == 2) { $journalcode = "BT"; $annumber = $anBT; }
        if ($row['depositbankid'] == 3) { $journalcode = "SOC"; $annumber = $anSOC; }
        if ($row['depositbankid'] == 4) { $journalcode = "BP"; $annumber = $anBP; }
      }
      if ($row['paymenttypeid'] == 3 || $row['paymenttypeid'] == 4 || $row['paymenttypeid'] == 5)
      {
        if ($row['bankid'] == 1) { $journalcode = "BC"; $annumber = $anBC; }
        if ($row['bankid'] == 2) { $journalcode = "BT"; $annumber = $anBT; }
        if ($row['bankid'] == 3) { $journalcode = "SOC"; $annumber = $anSOC; }
        if ($row['bankid'] == 4) { $journalcode = "BP"; $annumber = $anBP; }
      }
      if ($row['paymenttypeid'] == 6) { $journalcode = "OD"; $annumber = $ansalarydeduct; }
      if ($row['paymenttypeid'] == 7) { $journalcode = "OD"; $annumber = $anlossorprofit; }
      
      # payment itself, debit
      $writebuffer .= $journalcode . $sep;
      $writebuffer .= $row['showdate'] . $sep;
      $writebuffer .= 'P' . $row['paymentid'] . $sep;
      if ($row['forinvoiceid'] > 0) { $writebuffer .= 'FA' . $row['forinvoiceid'] . $sep; }
      else { $writebuffer .= $sep; }
      $writebuffer .= $anclient . $sep;
      $writebuffer .= 'C' . $row['clientid'] . $sep;
      $writebuffer .= substr(str_replace ($sep, ' ', trim($row['reference'])),0,35) . $sep;
      $writebuffer .= myround($row['value']) . $sep; # debit
      $writebuffer .= $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= substr($row['chequeno'],0,17) . $endline;
      
      # counterpart, credit
      $writebuffer .= $journalcode . $sep;
      $writebuffer .= $row['showdate'] . $sep;
      $writebuffer .= 'P' . $row['paymentid'] . $sep;
      $writebuffer .= 'P' . $row['paymentid'] . $sep;
      $writebuffer .= $annumber . $sep;
      $writebuffer .= $sep;
      $writebuffer .= substr(str_replace ($sep, ' ', trim($row['reference'])),0,35) . $sep;
      $writebuffer .= $sep; # debit
      $writebuffer .= myround($row['value']) . $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= substr($row['chequeno'],0,17) . $endline;
      
    }
      
    /*

    # import debit adjustments
    $query = 'select adjustmentid,clientid,adjustmentdate,amount,accountingnumber,operationdate from adjustment where (type=0 or type=3) and exported=0 and DATE_FORMAT(adjustmentdate,"%Y")>=' . $exportyear;
#  and matchingid>0
    if ($_POST['allinvoices'] == 1) { $query = 'select adjustmentid,clientid,adjustmentdate,amount,accountingnumber,operationdate from adjustment where (type=0 or type=3) and exported=0 and DATE_FORMAT(adjustmentdate,"%Y")=' . $exportyear; }
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      $matchingid = $row['matchingid'];
      $reference = 'OD' . $row['adjustmentid'];
      $id = 'C' . $row['clientid'];
      $title = $id;
      $tocreditnumber = $anOD;
      if ($row['accountingnumber'] > 0) { $tocreditnumber = $row['accountingnumber']; }
      $date = $row['adjustmentdate'];
      if (substr($row['operationdate'],0,4) > 0) { $date = $row['operationdate']; }
      $writebuffer = makeMECG("OD",$date,$reference,$matchingid,$anclient,$id,"0","D",$row['amount'],$title); fwrite($file, $writebuffer);
      $writebuffer = makeMECG("OD",$date,$reference,$matchingid,$tocreditnumber,"","0","C",$row['amount'],$title); fwrite($file, $writebuffer);
      $query = 'update adjustment set exported=1,exportdate=curdate() where adjustmentid="' . $row['adjustmentid'] . '"';
      $result2 = mysql_query($query, $db_conn); querycheck($result2);
    }

    # import credit adjustments
    $query = 'select adjustmentid,clientid,adjustmentdate,amount,operationdate from adjustment where (type=1 or type=2) and exported=0 and DATE_FORMAT(adjustmentdate,"%Y")>=' . $exportyear;
#  and matchingid>0
    if ($_POST['allinvoices'] == 1) { $query = 'select adjustmentid,clientid,adjustmentdate,amount,operationdate from adjustment where (type=1 or type=2) and exported=0 and DATE_FORMAT(adjustmentdate,"%Y")=' . $exportyear; }
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      $matchingid = $row['matchingid'];
      $reference = 'OD' . $row['adjustmentid'];
      $id = 'C' . $row['clientid'];
      $title = $id;
      $date = $row['adjustmentdate'];
      if (substr($row['operationdate'],0,4) > 0) { $date = $row['operationdate']; }
      $writebuffer = makeMECG("OD",$date,$reference,$matchingid,$anclient,$id,"0","C",$row['amount'],$title); fwrite($file, $writebuffer);
      $writebuffer = makeMECG("OD",$date,$reference,$matchingid,$anOD,"","0","D",$row['amount'],$title); fwrite($file, $writebuffer);
      $query = 'update adjustment set exported=1,exportdate=curdate() where adjustmentid="' . $row['adjustmentid'] . '"';
      $result2 = mysql_query($query, $db_conn); querycheck($result2);
    }
*/

    # adjustment (écriture)
    $query = 'select adjustment.adjustmentgroupid,adjustment.adjustmentid,date_format(adjustmentdate,"%d%m%y") as showdate,value,debit,acnumber,referenceid,adjustmentcomment
    from adjustment,accountingnumber,adjustmentgroup
    where adjustment.accountingnumberid=accountingnumber.accountingnumberid and adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and
    adjustmentdate=? and exported=0
    order by adjustmentdate,adjustment.adjustmentgroupid,adjustment.adjustmentid';
    $query .= ' limit ' . $limit;
    $query_prm = array($startdate);
    require('inc/doquery.php');
    $main_result = $query_result; $num_results_main = $num_results;
    #if ($num_results_main >= $limit) { echo '<p class="alert">Limite atteinte (ajustements): Créer encore un autre fichier pour cette periode.</p>'; }
    for ($i=0; $i < $num_results_main; $i++)
    {
      $row = $main_result[$i];
      /*
      $query = 'update adjustmentgroup set exported=1,exportdate=curdate() where adjustmentgroupid=?';
      $query_prm = array($row['adjustmentgroupid']);
      require('inc/doquery.php');
      */
      # one adjustment, debit or credit
      $writebuffer .= 'OD' . $sep;
      $writebuffer .= $row['showdate'] . $sep;
      $writebuffer .= 'OL' . $row['adjustmentid'] . $sep;
      $writebuffer .= 'OD' . $row['adjustmentgroupid'] . $sep;
      $writebuffer .= $row['acnumber'] . $sep;
      if ($row['acnumber'] == $anclient || $row['acnumber'] == $anlocalsupplier) { $writebuffer .= 'C' . $row['referenceid'] . $sep; }
      else { $writebuffer .= $sep; }
      $writebuffer .= substr(str_replace ($sep, ' ', trim($row['adjustmentcomment'])),0,35) . $sep;
      if ($row['debit'] == 1)
      {
        $writebuffer .= myround($row['value']) . $sep; # debit
        $writebuffer .= $sep; # credit
      }
      else
      {
        $writebuffer .= $sep; # debit
        $writebuffer .= myround($row['value']) . $sep; # credit
      }
      $writebuffer .= $sep;
      $writebuffer .= $endline;
    }
    
    $query = 'update invoicehistory set exported=1 where accountingdate=?';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    
    $query = 'update payment set exported=1 where paymentdate=?';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    
    $query = 'update purchasebatch set exported=1 where arrivaldate=?';
    $query_prm = array($startdate);
    require('inc/doquery.php');
    
    $query = 'update adjustmentgroup set exported=1 where adjustmentdate=?';
    $query_prm = array($startdate);
    require('inc/doquery.php');
      
      
    #$writebuffer = '#FIN' . $sep;
    $writebuffer = str_replace ('é', 'e', $writebuffer);
    $writebuffer = str_replace ('è', 'e', $writebuffer);
    $writebuffer = str_replace ('à', 'a', $writebuffer);
    $writebuffer = str_replace ('ç', 'c', $writebuffer);
    $writebuffer = str_replace ('ï', 'i', $writebuffer);
    $writebuffer = str_replace (chr(195), 'e', $writebuffer);
    $writebuffer = str_replace (chr(169), '', $writebuffer);
    #$writebuffer = iconv('UTF-8', 'ASCII//TRANSLIT', $writebuffer);
    fwrite($file, $writebuffer);
    fclose($file);

    echo '<p>Fichier <a href="customfiles/' . basename($filename) . '"><font color=blue><i>' . basename($filename) . '</i></font></a> créé.</p>';
    ?><p>- Cliquer sur le bouton droit de la souris</p>
    <p>- Enregistrer la cible sous z:\Donnsage\Import Gestion\</p><?php
    break;

  }
  break;

    
  case 'commissions':
  ?><h2>Commissions:</h2>
  <form method="post" action="customreportwindow.php" target="_blank"><table>
  <tr><td colspan=2>De
  <?php
  $datename = 'startdate';
  require('inc/datepicker.php');
  echo ' à ';
  $datename = 'stopdate';
  require('inc/datepicker.php');
  ?></td></tr>
  <tr><td>Employé:
  <?php
  $dp_itemname = 'employee'; $dp_issales=1; $dp_allowall = 1; $dp_selectedid = -1;
  require('inc/selectitem.php');
  ?></td></tr>
  <tr><td>Catégorie employé:
  <?php
  $dp_itemname = 'employeecategory'; $dp_allowall = 1; $dp_selectedid = -1;
  require('inc/selectitem.php');
  ?></td></tr>
  <tr><td>Fournisseur:</td><td><input type=number min=0 name=supplierid>&nbsp;<input type=checkbox name=excludesupplier value=1> Exclure</td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="report" value="commissions"><input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  case 'rfa':
  echo '<h2>RFA</h2>';
  echo '<p>Les valeurs sont HT.</p>';
  echo '<p class="alert">produits 279 et 280</p>';
  echo '<form method="post" action="customreportwindow.php" target=_blank><table><tr><td>Du:</td><td>';
  $datename = 'startdate';
  require('inc/datepicker.php');
  echo '</td></tr><tr><td>Au:</td><td>';
  $datename = 'stopdate';
  require('inc/datepicker.php');
  echo '</td></tr>';  
  echo '<tr><td>';
  require('inc/selectclient.php');
  ?><tr><td>Île:</td>
  <td><select name="islandid"><?php
  $query = 'select islandid,islandname from island order by islandname';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<option value="-1">&lt;Tous&gt;</option>';
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<option value="' . $row['islandid'] . '">' . $row['islandname'] . '</option>';
  }
  ?></select></td></tr><?php
  $dp_itemname = 'clientsector'; $dp_description = 'Secteur client'; $dp_allowall = 1; $dp_noblank = 1;
  require('inc/selectitem.php');
  /*
  echo '<tr><td>Sous-famille de produit:</td><td><select name="productfamilyid">';
  $query = 'select productfamilyid,productfamilyname from productfamily order by productfamilyname';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $num_results = mysql_num_rows($result);
#  echo '<option value="-1">&lt;Tous&gt;</option>';
  for ($i=0; $i < $num_results; $i++)
  {
    $row = mysql_fetch_array($result);
    echo '<option value="' . $row['productfamilyid'] . '">' . $row['productfamilyname'] . '</option>';
  }
  #<option value="7">1.5 l (RFA)</option>
  echo '</select></td></tr>';
  */
  echo '<tr><td>20 - 49:</td><td><input type=number STYLE="text-align:right" name="range1" value="5" size=5>%</td></tr>';
  echo '<tr><td>50 - 99:</td><td><input type=number STYLE="text-align:right" name="range2" value="6" size=5>%</td></tr>';
  echo '<tr><td>100+:</td><td><input type=number STYLE="text-align:right" name="range3" value="10" size=5>%</td></tr>';
  echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="rfa"><input type="submit" value="Valider"></td></tr></table></form>';
  break;

  case 'toorder':
  ?><h2>Produits à commander:</h2>
    <form method="post" action="customreportwindow.php" target="_blank"><table>
    <tr><td>Classe de produit:</td>
    <td><select name="productfamilygroupid"><?php
    echo '<option value="0">Tous</option>';
    
    $query = 'select productfamilygroupid,productfamilygroupname,productdepartmentname from productdepartment,productfamilygroup where productfamilygroup.productdepartmentid=productdepartment.productdepartmentid order by departmentrank,familygrouprank';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      echo '<option value="' . $row['productfamilygroupid'] . '">' . $row['productdepartmentname'] . '/' . $row['productfamilygroupname'] . '</option>';
    }
    ?></select></td></tr>
    <tr><td>Numéro de fournisseur:</td><td><input type="text" STYLE="text-align:right" name="supplierid" size=5></td></tr>
    <tr><td colspan=2>Exclure produits avec arrivages <input type="checkbox" name="exarr" value="1"></td></tr>
    <tr><td colspan=2>Alerte Ventes Exceptionnelles (mois > 150% moyenne) <input type="checkbox" name="vexcpt" value="1"></td></tr>
<?php
#    <tr><td colspan=2><font color=red>Recalculer la moyenne/mois<br>(12 derniers mois, incluant uniquement les mois avec ventes et sans rupture de stock)</font> <input type="checkbox" name="recalc" value="1"></td></tr>
/*
    <tr><td>Sec/Frigo (needs change):</td>
    <td><select name="frigo"><option value="0">Tous</option><option value="2">Sec</option><option value="1">Frigo</option>
    */
    $dp_description = 'Temperature'; $dp_itemname = 'temperature'; $dp_allowall = 1; $dp_selectedid = -1;
    require('inc/selectitem.php');
    ?>
    <?php
    #<option value="3">Réfrigéré</option></td></tr>
    ?>
    <tr><td>Calcul coefficient:</td><td><select name="whichavg"><option value="1">Utiliser moyenne spécifié</option><option value="2">Utiliser moyenne calculé</option></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="report" value="toorder"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
  break;

  case 'valstock3112':
    $year = substr($_SESSION['ds_curdate'],0,4)-1;
    ?><h2>Valorisation stock au 31/12:</h2>
    <form method="post" action="customreportwindow.php" target="_blank">
    <table>
    <tr><td>Année:</td><td><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td colspan=2><font size=-1><input type="checkbox" name="shownestle" value="1"> Afficher produits Néstle:</font><br>
    PPN=<input type="text" STYLE="text-align:right" name="ppnperc" value="11" size=4>%<br>
    Autres=<input type="text" STYLE="text-align:right" name="otherperc" value="19" size=4>%<br>
    Congéle=<input type="text" STYLE="text-align:right" name="frozenperc" value="29" size=4>%
    </tr>
    <tr>
      <td>Temperature:
        <?php
          $dp_itemname = "temperature";
          $dp_allowall = 1;
          require('inc/selectitem.php');
        ?>
      </td>
    </tr>
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td colspan=2><font size=-1><input type="checkbox" name="split_prev" value="1"> Afficher valeur par lot</font>
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td colspan=2><input type=hidden name="report" value="valstock3112"><input type="submit" value="Valider"></td></tr></form></table><?php
  break;
  
  case 'prodclimonth':
    require('reports/prodclimonth.php');
  break;


  case 'cadvente';
    
    ?><h2>Cadence de Vente / Année:</h2>
    <p class=alert>Attention Proformas sont inclus dans les ventes</p>
    <form method="post" action="customreportwindow.php" target="_blank"><table>
    <?php
    ?><tr><td>Année:</td><td><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $_SESSION['year']) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>Departement de produit:</td>
    <td><select name="productdepartmentid"><?php
    echo '<option value="0">Tous</option>';
    
    $query = 'select productdepartmentid,productdepartmentname from productdepartment order by departmentrank';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      echo '<option value="' . $row['productdepartmentid'] . '">' . $row['productdepartmentname'] . '</option>';
    }
    ?></select></td></tr>
    <tr><td>Classe de produit:</td>
    <td><select name="productfamilygroupid"><?php
    echo '<option value="0">Tous</option>';
    
    $query = 'select productfamilygroupid,productfamilygroupname,productdepartmentname from productdepartment,productfamilygroup where productfamilygroup.productdepartmentid=productdepartment.productdepartmentid order by departmentrank,familygrouprank';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      echo '<option value="' . $row['productfamilygroupid'] . '">' . $row['productdepartmentname'] . '/' . $row['productfamilygroupname'] . '</option>';
    }
    ?></select></td></tr>
    <tr><td>Code produit:</td><td><input type="text" STYLE="text-align:right" name="productid" size=10></td></tr>
    <tr><td>Code fournisseur:</td><td><input type="text" STYLE="text-align:right" name="supplierid" size=10> <input type="checkbox" name="excludesupplier" value="1">Exclure </td></tr>
<?php
#    <tr><td>Produits à commander, stock inférieur à:</td><td><input type="text" STYLE="text-align:right" name="stockmonths" size=10> mois de stock</td></tr>
#    <tr><td>Produits à faible vente:</td><td><input type="text" STYLE="text-align:right" name="cartonspermonth" size=10> cartons par mois</td></tr>
?>
    <tr><td colspan=2><font size=-1></font></td></tr>
    <tr><td colspan=2><font size=-1>Afficher sous-unités: <input type="checkbox" name="showunits" value="1"></font></td></tr>
<?php
#    <tr><td colspan=2><font size=-1>Afficher valeurs stock (PR): <input type="checkbox" name="showvalue" value="1"></font></td></tr>
?>
    <tr><td colspan="2" align="center"><input type=hidden name="report" value="cadvente"><input type="submit" value="Valider"></td></tr>
    </table></form>
    <br><font color=red size=+1><b>Attention! Utiliser seulement pour un produit à la fois pendant les heures de facturation.</b></font><br>
    <?php
  break;

  case 'prodcat':
    echo '<form method="post" action="customreportwindow.php" target="_blank">';
    if ($_SESSION['ds_userid'] == 13 || $_SESSION['ds_userid'] == 65)
    {
        echo '<input type="radio" name="mycat" value="0" CHECKED> Tout<br>
        <input type="radio" name="mycat" value="3"> Standard<br>
        <input type="radio" name="mycat" value="5"> Wing Chong<br>

        <input type="radio" name="mycat" value="4"> Food Service<br>

        <input type="radio" name="mycat" value="8"> Produits non mis à la vente<br>
        <input type="radio" name="mycat" value="9"> Produits en rupture de stock<br>
        <input type="radio" name="mycat" value="10"> Produits discontinué<br>
        <br>
        Champs: Codes EAN<input type="checkbox" name="showean" value="1"> &nbsp; Code Scan<input type="checkbox" name="showbarcodes" value="1"><br>
        Promo<input type="checkbox" name="showpromotext" value="1"><br>
        Commercial: <input type="checkbox" name="salesrep" value="1"><br>
        Commercial Archipel TMA: <input type="checkbox" name="islandsalesrep" value="1"><br>
        <input type=hidden name="report" value="prodcat"><input type="submit" value="Catalogue de produits"></form>';
    }
    elseif ($_SESSION['ds_userid'] == 63)
    {
        echo '<input type="radio" name="mycat" value="0" CHECKED> Tout<br>
        <input type="radio" name="mycat" value="3"> Standard<br>
        <input type="radio" name="mycat" value="5"> Wing Chong<br>
        <br>
        Champs: Codes EAN<input type="checkbox" name="showean" value="1"> &nbsp; Code Scan<input type="checkbox" name="showbarcodes" value="1"><br>
        Promo<input type="checkbox" name="showpromotext" value="1"><br>
        Commercial: <input type="checkbox" name="salesrep" value="1"><br>
        Commercial Archipel TMA: <input type="checkbox" name="islandsalesrep" value="1"><br>
        <input type=hidden name="report" value="prodcat"><input type="submit" value="Catalogue de produits"></form>';
    }
    elseif ($_SESSION['ds_userid'] == 75)
    {
        echo '<input type="radio" name="mycat" value="0" CHECKED> Tout<br>
        <input type="radio" name="mycat" value="3"> Standard<br>
        <input type="radio" name="mycat" value="5"> Wing Chong<br>
        <input type="radio" name="mycat" value="6"> Nestlé<br>
        <br>
        Champs: Codes EAN<input type="checkbox" name="showean" value="1"> &nbsp; Code Scan<input type="checkbox" name="showbarcodes" value="1"><br>
        Promo<input type="checkbox" name="showpromotext" value="1"><br>
        Commercial: <input type="checkbox" name="salesrep" value="1"><br>
        <input type=hidden name="report" value="prodcat"><input type="submit" value="Catalogue de produits"></form>';
    }
    else
    {
        echo '<input type="radio" name="mycat" value="0" CHECKED> Tout<br>
        <input type="radio" name="mycat" value="3"> Standard<br>
        <input type="radio" name="mycat" value="5"> Wing Chong<br>
        <input type="radio" name="mycat" value="6"> Nestlé<br>
        <input type="radio" name="mycat" value="7"> Nestlé avec codes fournisseurs<br>
        <input type="radio" name="mycat" value="4"> Food Service<br>
        <input type="radio" name="mycat" value="2"> Petfood<br>
        <input type="radio" name="mycat" value="8"> Produits non mis à la vente<br>
        <input type="radio" name="mycat" value="9"> Produits en rupture de stock<br>
        <input type="radio" name="mycat" value="10"> Produits discontinué<br>
        <input type="radio" name="mycat" value="11"> Produits hors commission ou 0.25%<br>
        <input type="radio" name="mycat" value="55"> Wing Chong with margin<br>
        <br>
        Champs: Codes EAN<input type="checkbox" name="showean" value="1"> &nbsp; Code Scan<input type="checkbox" name="showbarcodes" value="1"><br>
        Promo<input type="checkbox" name="showpromotext" value="1"><br>
        Commercial: <input type="checkbox" name="salesrep" value="1"><br>
        Commercial Archipel TMA: <input type="checkbox" name="islandsalesrep" value="1"><br>
        Prendre stock fin année ' . (substr($_SESSION['ds_curdate'],0,4)-1) . ': <input type="checkbox" name="lastyearstock" value="1"><br>
        <input type=hidden name="report" value="prodcat"><input type="submit" value="Catalogue de produits"></form>';
    }
  break;

  case 'prixproduits':
  ?><h2>Prix et produits:</h2>
    <form method="post" action="customreportwindow.php" target="_blank"><table>
    <tr><td>Numéro dossier:</td><td><input autofocus type="text" STYLE="text-align:right" name="shipmentid" size=5></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="report" value="prixproduits"><input type="submit" value="Valider"></td></tr>
    </table></form><?php

  break;
  
  case 'stockwc':
  #if ($_SESSION['ds_userid'] != 1) { echo 'travaux'; exit; }
  echo '<h2>Stock système WC Fin année</h2>
<form method="post" action="customreportwindow.php" target=_blank>
<table>
<tr><td colspan=2><input type=checkbox name=onlydiff value=1> N\'afficher que les écarts</td></tr>
<tr><td colspan=2><select name="ouryear"><option value=2014>2014</option><option value=2013>2013</option></select</td></tr>
<tr><td colspan=2>De produit num:<input autofocus type="text" STYLE="text-align:right" name="pid1" size=5></td></tr>
<tr><td colspan=2>A produit num:<input type="text" STYLE="text-align:right" name="pid2" size=5></td></tr>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="stockwc"><input type="submit" value="Valider"></td></tr>
</table></form>';
  break;
  
  case 'cdj':
  ?><h2>Caisse du jour:</h2>
  <form method="post" action="customreportwindow.php" target=_blank><table>
  <tr><td>Date:</td><td><?php
  $datename = 'cdjdate';
  require('inc/datepicker.php');
  ?></td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="report" value="cdj"><input type="submit" value="Valider"></td></tr></table></form>
  <?php
  break;
  
  case 'nestledaily':
  ?><h2>Nestlé vente/jour (txt):</h2>
  <p>Ce rapport n'affiche pas sous-unites</p>
  <p>Ce rapport n'affiche pas les avoirs</p>
  <p>Ce rapport n'affiche ni les BdL ni les proformas</p>
  <p>Ce rapport n'affiche que les factures confirmées</p>
  <p>Si l'employee n'est pas catégories "Nestlé" ou "WC & NP": s'affiche comme Particulier/Comptant/Tahiti</p>
  <p>Si l'employee catégorie "WC & NP": s'affiche comme Particulier/Comptant/Tahiti sauf pour Catégories Client: Magasin, Supermarché, Hypermarché,	Libre-Service, Station Service</p>
  <form method="post" action="customreportwindow.php" target=_blank><table>
  <tr><td>Date <select name="mychoice2"><option value="3">comptable</option><option value="2">saisie</option><option value="1">livraison</option></select> de </td><td><?php
  $datename = 'fromdate';
  require('inc/datepicker.php');
  echo '</td></tr><tr><td> à </td><td>';
  $datename = 'todate';
  require('inc/datepicker.php');
  ?></td></tr>
  <?php
  #<tr><td>Factures:</td><td><select name="mychoice"><option value="1">Confirmées</option><option value="2">Non-confirmées</option></select></td></tr>
  ?>
  <tr><td colspan="2" align="center">
  <input type=hidden name="report" value="nestledaily">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;
  
  case 'sohreport':
  ?><h2>SOH Report (txt):</h2>
  <form method="post" action="customreportwindow.php" target=_blank><table>
  <tr><td colspan="2" align="center">
  <input type=hidden name="report" value="sohreport">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;
  
  case 'confirmbdl':
  
    ?><script type='text/javascript' src='jq/jquery.js'></script>

    <script type='text/javascript'>
    $(document).ready(function(){

    // source http://www.formget.com/checkuncheck-all-checkboxes-using-jquery/

    $("#confirmall").attr("data-type","check");
    $("#confirmall").click(function(){
    if($("#confirmall").attr("data-type")==="check")
    {
    $(".confirm").prop("checked",true);
    $("#confirmall").attr("data-type","uncheck");
    }
    else
    {
    $(".confirm").prop("checked",false);
    $("#confirmall").attr("data-type","check");
    }
    })
    });
    </script>

    <script type='text/javascript'>
    $(document).ready(function(){
    $("#cancelall").attr("data-type","check");
    $("#cancelall").click(function(){
    if($("#cancelall").attr("data-type")==="check")
    {
    $(".cancel").prop("checked",true);
    $("#cancelall").attr("data-type","uncheck");
    }
    else
    {
    $(".cancel").prop("checked",false);
    $("#cancelall").attr("data-type","check");
    }
    })
    });
    </script>
    <?php

    require('preload/localvessel.php');

    $myuserid = $_POST['myuserid']+0;
    $limitdates = $_POST['limitdates']+0;
    if ($_SESSION['ds_confirmonlyown']) { $myuserid = $_SESSION['ds_userid']; }

    if ($myuserid == 0)
    {
      ?><h2>Confirmer / annuler BdL:</h2>
      <form method="post" action="custom.php"><table>
      <input type=hidden name="myuserid" value="-1">
      <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="custommenu" value="<?php echo $custommenu; ?>">
      <input type="submit" value="Valider"></td></tr>
      <tr><td>&nbsp;
      <tr><td><input type=checkbox name="limitdates" value=1>Limiter les dates:<br>
      De: <?php $datename = 'startdate'; require('inc/datepicker.php');
      echo '<br>A: '; $datename = 'stopdate'; require('inc/datepicker.php');
      ?></table></form><?php
    }
    else
    {
      if ($_POST['confirm'])
      {
        $listconfirmed = ''; $listcancelled = ''; $in_confirmed = '(';
        $all_results = $_POST['results']+0;
        for ($i=0; $i < $all_results; $i++)
        {
          if ($_POST['confirmed' . $i] && !$_POST['cancelled' . $i])
          {
            ### check if sum of lines equals invoiceprice
            $ok = 0;
            $query = 'select invoiceprice from invoice where invoiceid=?';
            $query_prm = array($_POST['confirmed' . $i]);
            require('inc/doquery.php');
            $invoicetotal = $query_result[0]['invoiceprice']+0;
            $query = 'select sum(lineprice+linevat) as linetotals from invoiceitem where invoiceid=?';
            $query_prm = array($_POST['confirmed' . $i]);
            require('inc/doquery.php');
            $linetotals = $query_result[0]['linetotals']+0;
            if ($_SESSION['ds_invoicedeductions'] == 1)
            {
              $query = 'select sum(deduction) as linetotals from invoicededuction where invoiceid=?';
              $query_prm = array($_POST['confirmed' . $i]);
              require('inc/doquery.php');
              $linetotals -= $query_result[0]['linetotals']+0;
            }
            #if (myround($linetotals) == myround($invoicetotal) || $_SESSION['ds_invoicedeductions'] == 1) { $ok = 1; } # TODO fix check with deductions
            $linetotals = myround($linetotals); $invoicetotal = myround($invoicetotal); # 2017 01 25
            if ($linetotals == $invoicetotal || $_SESSION['ds_invoicedeductions'] == 1) { $ok = 1; } # TODO fix check with deductions
            else
            {
              echo '<span class="alert">Erreur sur facture ' . $_POST['confirmed' . $i] . ' (annulée)</span> ('.$linetotals.' vs '.$invoicetotal.')<br>';
              $_POST['cancelled' . $i] = $_POST['confirmed' . $i];
              $_POST['confirmed' . $i] = '';
            }
            ###
            if ($ok)
            {
              $querymain = 'update invoice set confirmed=1,proforma=0,invoicedate=curdate(),invoicetime=curtime()';
              if ($_SESSION['ds_confirmchangesdate'] == 1)
              {
                $query = 'select daystopay,special from client,invoice,clientterm where invoice.clientid=client.clientid and client.clienttermid=clientterm.clienttermid and invoiceid=?';
                $query_prm = array($_POST['confirmed' . $i]);
                require('inc/doquery.php');
                $rowEXTRA = $query_result[0];
                $daystopay = $rowEXTRA['daystopay'];
                if ($rowEXTRA['special'] == 1) # end of month
                {
                  $endofmonthdate = new DateTime($_SESSION['ds_curdate']);
                  $endofmonthdate->modify('last day of this month');
                  $daystopay = ((int) $endofmonthdate->format('d')) - ((int) substr($_SESSION['ds_curdate'],8,2));
                }
                $querymain = $querymain . ',accountingdate=curdate(),paybydate=DATE_ADD(curdate(), INTERVAL ' . $daystopay . ' DAY)';
              }
              $query = $querymain . ' where invoiceid=?';
              $query_prm = array($_POST['confirmed' . $i]);
              require('inc/doquery.php');
              if($num_results == count($query_prm))
              {
                $listconfirmed .= ' <a href="printwindow.php?report=showinvoice&invoiceid=' . $_POST['confirmed' . $i] . '" target=_blank>' . $_POST['confirmed' . $i] . '</a>';
                $in_confirmed .= $_POST['confirmed' . $i] . ',';
              }
            }
          }
          if ($_POST['cancelled' . $i] && !$_POST['confirmed' . $i])
          {
            $query = 'update invoice set cancelledid=1,invoicedate=curdate(),invoicetime=curtime() where invoiceid=?';
            $query_prm = array($_POST['cancelled' . $i]);
            require('inc/doquery.php');
            if($num_results == count($query_prm))
            {
              $listcancelled .= ' <a href="printwindow.php?report=showinvoice&invoiceid=' . $_POST['cancelled' . $i] . '" target=_blank>' . $_POST['cancelled' . $i] . '</a>';
            }
          }
        }
        echo 'Factures confirmées:'.$listconfirmed.'<br>Factures annulées:'.$listcancelled.'<br><br>';
        
        require('inc/move_to_history.php');

      }

      $listall = '';
      $query = 'select localvesselid,reference,isnotice,proforma,isreturn,invoiceid,accountingdate,clientname,invoice.clientid as clientid,invoiceprice,initials
      from invoice,client,usertable
      where invoice.userid=usertable.userid and invoice.clientid=client.clientid
      and cancelledid=0 and confirmed=0';
      $query_prm = array();
      $query .= ' and isnotice=1 and isreturn=0';
      if ($myuserid > 0)
      {
        $query .= ' and invoice.userid=?'; array_push($query_prm, $myuserid);
      }
      if ($limitdates == 1)
      {
        $datename = 'startdate'; require('inc/datepickerresult.php');
        $datename = 'stopdate'; require('inc/datepickerresult.php');
        $query .= ' and invoice.accountingdate>=?'; array_push($query_prm, $startdate);
        $query .= ' and invoice.accountingdate<=?'; array_push($query_prm, $stopdate);
      }
      $query = $query . ' order by invoiceid';
      require('inc/doquery.php');
      echo '<form method="post" action="custom.php"><table class="detailinput"><tr><td><b>Confirmer</td><td><b>Facture</td>';
      echo '<td><b>' . $_SESSION['ds_term_accountingdate'] . '</td><td><b>Client</td><td><b>Prix total</td>';
      if ($_SESSION['ds_term_reference'] != "") { echo '<td><b>' . d_output($_SESSION['ds_term_reference']) . '</td>'; }
      else { echo '<td><b>Référence</td>'; }
      echo '<td><b>Facturier</td>';
      if (isset($localvesselA)) { echo '<td><b>Bateau</b></td>'; }
      echo '<td><b>Annuler</td></tr>';
      for ($i=0; $i < $num_results; $i++)
      {
        $row = $query_result[$i];
        $listall = $listall . ' ' . $row['invoiceid'];
        $returntext = ""; if ($row['isreturn'] == 1) { $returntext = '(Avoir) '; }
        if ($row['proforma'] == 1) { $returntext = '(Proforma) '; }
        if ($row['isnotice'] == 1) { $returntext = '('.$_SESSION['ds_term_invoicenotice'].') '; }
        echo '<tr><td> &nbsp; <input type="checkbox" class="confirm" name="confirmed' . $i . '" value="' . $row['invoiceid'] . '"></td><td align=right>' . $returntext . '<a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $row['invoiceid'] . '" target=_blank>' . myfix($row['invoiceid']) . '</a></td><td align=right>' . datefix2($row['accountingdate']) . '</td><td>' . $row['clientid'] . ': ' . d_output(d_decode($row['clientname'])) . '</td><td align=right>' . myfix($row['invoiceprice']) . '</td><td>' . $row['reference'] . '</td><td>' . $row['initials'] . '</td>';
        if (isset($localvesselA)) { echo '<td>' . $localvesselA[$row['localvesselid']] . '</td>'; }
        echo '<td> &nbsp; <input type="checkbox" class="cancel" name="cancelled' . $i . '" value="' . $row['invoiceid'] . '"></td></tr>';
      }
      $colspan=6; if (isset($localvesselA)) { $colspan++; }
      echo '<tr><td> &nbsp; <input type="button" id="confirmall" value="Tous" /></td><td colspan='.$colspan.'></td><td> &nbsp;  <input type="button" id="cancelall" value="Tous" /></td></tr>';
      echo '<tr><td colspan="10" align="center"><input type=hidden name="step" value="1"><input type="hidden" name="listall" value="' . $listall . '"><input type=hidden name="custommenu" value="' . $custommenu . '"><input type=hidden name="confirm" value="1"><input type=hidden name="myuserid" value="' . $_POST['myuserid'] . '"><input type=hidden name="results" value="' . $num_results . '"><input type="submit" value="Confirmer / annuler facture(s)"></td></tr>';
      if ($limitdates == 1)
      {
        echo '<input type=hidden name=limitdates value="' . $limitdates . '"><input type=hidden name=startdate value="' . $startdate . '"><input type=hidden name=stopdate value="' . $stopdate . '">';
      }
      echo '</table></form>';
    }
  
  break;

  default:

  break;
}

if ($txtfile != 1)
{


  ?>

  </td></tr></table>



  <?php

    require ('inc/bottom.php');


}
?>