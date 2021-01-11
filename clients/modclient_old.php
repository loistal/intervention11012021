<?php

# 2016 03 11 refactor/remake needed

#clients/modclient

#require('preload/country.php');

$err_clientname_dup = false;
$err_clientname_empty = false;

//UPDATE
if ($_POST['saveme'] == 1 && $_POST['clientid'] > 0)
{  
  $clientid = $_POST['clientid'];
  
  $clientname = d_encode($_POST['name']);
  $clientfirstname = $_POST['clientfirstname'];
  $clientcode = $_POST['clientcode'];
  $companytypename = $_POST['companytypename'];  
  $datename = 'creationdate'; $dp_allowempty = 1; require('inc/datepickerresult.php');
  if ($creationdate == NULL) { $creationdate = ''; }
  
  $blocked = $_POST['blocked'];
  $query = 'select blocked from client where clientid=?';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  $old_blocked = $query_result[0]['blocked'];
  if ($old_blocked != $blocked)
  {
    if ($blocked == 0) { $temp_text = 'Client Débloqué'; }
    elseif ($blocked == 1) { $temp_text = 'Client Interdit'; }
    elseif ($blocked == 2) { $temp_text = 'Client Suspendu'; }
    $query = 'insert into clientaction (clientid,actiondate,employeeid,clientactioncatid,actionname,userid) values (?,?,?,?,?,?)';
    $query_prm = array($clientid, $_SESSION['ds_curdate'], $_SESSION['ds_myemployeeid'], 0, $temp_text, $_SESSION['ds_userid']);
    require('inc/doquery.php');
  }
  
  $usedetail = $_POST['usedetail'];  
  $surcharge = $_POST['surcharge'];   if ($surcharge < 0 || $surcharge > 100) { $surcharge = 0; } 
  $vatexempt = $_POST['vatexempt'];  
  $clienttermid = $_POST['clienttermid'];  

  $issupplier = (int) $_POST['issupplier'];
  $isclient = (int) $_POST['isclient'];
  $isemployee = (int) $_POST['isemployee'];
  $isother = (int) $_POST['isother'];
  $leadtime = $_POST['leadtime'];

  $employeeid = $_POST['employeeid'];
  $employeeid2 = $_POST['employeeid2'];

  $address = $_POST['address'];
  $postaladdress = $_POST['postaladdress'];
  $postalcode = $_POST['postalcode'];
  $townid = $_POST['townid'];
  $town_name = $_POST['town_name'];
  $countryid = (int) $_POST['countryid']; #if ($countryid != 0 && mb_strtolower($countryA[$countryid]) != mb_strtolower('Polynésie française')) { $townid = 0; }  
  #if(empty($countryid)){$countryid = '156';} noooooooooooooooo
  $quarter = $_POST['quarter'];  
  $contact = $_POST['contact'];
  $contact2 = $_POST['contact2'];
  $contact3 = $_POST['contact3'];
  $telephone = $_POST['telephone'];
  $cellphone = $_POST['cellphone'];
  $telephone3 = $_POST['telephone3'];
  $telephone4 = $_POST['telephone4'];
  $fax = $_POST['fax'];
  $email = $_POST['email'];
  $email2 = $_POST['email2'];
  $email3 = $_POST['email3'];
  $email4 = $_POST['email4'];
  $batchemail = $_POST['batchemail'];
  
  $clientcategoryid = $_POST['clientcategoryid'];
  $clientcategory2id = $_POST['clientcategory2id'];
  $clientcategory3id = $_POST['clientcategory3id'];
  $clientsectorid = $_POST['clientsectorid'];
  $tahitinumber = $_POST['tahitinumber'];  
  $rc = $_POST['rc'];
  $outstandinglimit = $_POST['outstandinglimit']; if ($outstandinglimit == "") { $outstandinglimit = 0; }  
  $bankreference = $_POST['bankreference'];
  $comment = $_POST['comment'];
  $clienthistory = $_POST['clienthistory'];

  $titu = $_POST['titu'];
  $domi = $_POST['domi'];
  $codebanque = $_POST['codebanque'];
  $guichet = $_POST['guichet'];  
  $account = $_POST['account'];
  $clerib = $_POST['clerib'];
  $clientfield1 = $_POST['clientfield1'];
  $clientfield2 = $_POST['clientfield2'];
  $clientfield3 = $_POST['clientfield3'];
  $clientfield4 = $_POST['clientfield4'];
  $clientfield5 = $_POST['clientfield5'];
  $clientfield6 = $_POST['clientfield6'];
  $client_customdate1 = $_POST['client_customdate1'];
  $client_customdate2 = $_POST['client_customdate2'];
  $client_customdate3 = $_POST['client_customdate3'];
  
  $use_loyalty_points = (int) $_POST['use_loyalty_points'];
  $datename = 'loyaltydate'; $dp_allowempty = 1; require('inc/datepickerresult.php');
  if ($loyaltydate == NULL) { $loyaltydate = ''; }
  $loyalty_start = (int) $_POST['loyalty_start'];
  $dossier = (int) $_POST['dossier'];
  
  $discount = $_POST['discount']; if ($discount == "") { $discount = 0; }
  
  //UPDATE
  $query_update = 'update client set ';
  
  if ($_SESSION['ds_purchaseaccess'] == 1) 
  { 
    $query_update = $query_update . 'issupplier=?,leadtime=?,'; 
  }
  $query_update = $query_update. 'dossier=?,loyalty_start=?,use_loyalty_points=?,loyaltydate=?,isclient=?,isemployee=?,isother=?,clientcode=?,clienthistory=?,contact2=?,contact3=?,surcharge=?,countryid=?,employeeid2=?,
  clientsectorid=?,clerib=?,guichet=?,codebanque=?,domi=?,titu=?,usedetail=?,discount=?,quarter=?,postaladdress=?,outstandinglimit=?,companytypename=?,
  postalcode=?,employeeid=?,blocked=?,account=?,vatexempt=?,address=?,townid=?,contact=?,fax=?,clientcategoryid=?,
  clientcategory2id=?,clientcategory3id=?,bankreference=?,clienttermid=?,surcharge=?,tahitinumber=?,rc=?,clientcomment=?,creationdate=?,clientfirstname=?
  ,clientfield1=?,clientfield2=?,clientfield3=?,clientfield4=?,clientfield5=?,clientfield6=?,client_customdate1=?,client_customdate2=?,client_customdate3=?,town_name=?';
 
  //check if name already exist
  $clientname_modified = false;        
  if($clientname  == '')
  {
    $err_clientname_empty = true;
  }
  else
  {  
    $query = 'select clientid from client where clientname=? and clientid<>?';
    $query_prm = array($clientname,$clientid);
    require('inc/doquery.php');
    if ($num_results > 0)
    {
      $err_clientname_dup = true;
    }
  }
     
  if(!$err_clientname_empty && !$err_clientname_dup)
  {
    $query_update .= ',clientname=?';
    $clientname_modified = true;
  }

  $query_prm = array($dossier,$loyalty_start,$use_loyalty_points,$loyaltydate,$isclient,$isemployee,$isother,$clientcode,$clienthistory,$contact2,$contact3,$surcharge,$countryid,$employeeid2,
  $clientsectorid,$clerib,$guichet,$codebanque,$domi,$titu,$usedetail,$discount,$quarter, $postaladdress,$outstandinglimit,$companytypename,
  $postalcode,$employeeid,$blocked,$account,$vatexempt,$address,$townid,$contact,$fax,$clientcategoryid,
  $clientcategory2id,$clientcategory3id,$bankreference,$clienttermid,$surcharge,$tahitinumber,$rc,$comment,$creationdate,$clientfirstname
  ,$clientfield1,$clientfield2,$clientfield3,$clientfield4,$clientfield5,$clientfield6,$client_customdate1,$client_customdate2,$client_customdate3,$town_name);

  if ($_SESSION['ds_purchaseaccess'] == 1) 
  {
    array_unshift($query_prm,$issupplier,$leadtime);
  }
  if($clientname_modified)
  {
    array_push($query_prm,$clientname);
  }
  
  if ($_SESSION['ds_term_client_telephone'] != '') { $query_update .= ',telephone=?'; array_push($query_prm,$telephone); }
  if ($_SESSION['ds_term_client_cellphone'] != '') { $query_update .= ',cellphone=?'; array_push($query_prm,$cellphone); }
  if ($_SESSION['ds_term_client_telephone3'] != '') { $query_update .= ',telephone3=?'; array_push($query_prm,$telephone3); }
  if ($_SESSION['ds_term_client_telephone4'] != '') { $query_update .= ',telephone4=?'; array_push($query_prm,$telephone4); }
  if ($_SESSION['ds_term_client_email'] != '') { $query_update .= ',email=?'; array_push($query_prm,$email); }
  if ($_SESSION['ds_term_client_email2'] != '') { $query_update .= ',email2=?'; array_push($query_prm,$email2); }
  if ($_SESSION['ds_term_client_email3'] != '') { $query_update .= ',email3=?'; array_push($query_prm,$email3); }
  if ($_SESSION['ds_term_client_email4'] != '') { $query_update .= ',email4=?'; array_push($query_prm,$email4); }
  $query_update .= ',batchemail=?'; array_push($query_prm,$batchemail);
  
  array_push($query_prm,$clientid);
  $query = $query_update . ' where clientid=?';
  require('inc/doquery.php');
  if($num_results > 0)
  {
   $was_modified = 1;
  }
   
  $showclientname = $clientid;
  //client name shown only if no error
  if(!$err_clientname_empty && !$err_clientname_dup)
  {
    $showclientname .= '(' . d_output(d_decode($clientname)) .')';
  }

  #feedback
  if($was_modified == 1)
  {
      echo '<p>Client ' . $showclientname . ' modifié.</p>';
  }
  if($err_clientname_dup)
  {
    # TODO this needs to be fixed!
    #echo '<p class="alert">Le client ' . d_output(d_decode($clientname)) . ' existe déjà.</p>';
  }
  if($err_clientname_empty)
  {
    echo '<p class="alert">Le nom du client doit être renseigné.</span></p>';
  }
}

//FIND CLIENT
$client = $_POST['client'];
if (isset($_POST['clientid'])) { $clientid = $_POST['clientid']; }
else { require ('inc/findclient.php'); }

//UPDATE CLIENT FORM
if ($clientid < 1 && $_POST['addclient'] != 1) 
{
  echo '<form method="post" action="clients.php">';
  echo '<fieldset><legend>Modifier Client';
  if ($_SESSION['ds_purchaseaccess'] || $_SESSION['ds_accountingaccess'])
  {
    echo '/ Fournisseur ';
  }
  echo '</legend>';

  $canaddclient = 1;
  require('inc/selectclient.php');

  echo '<input type=hidden name="step" value="0">
  <input type=hidden name="clientsmenu" value="' . $clientsmenu . '">';
  echo '<input type="submit" value="Valider"></fieldset></form>';
}

//ADDCLIENT
if ($_POST['addclient'] == 1)
{
  $onlyaddclient = 1;
  require('inc/selectclient.php');
}

if ($clientid > 0)
{
  $query = 'select * from client where clientid=?';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  $rowclient = $query_result[0];
  
##+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++  
echo '<h2>Modifier Client ';
if ($_SESSION['ds_purchaseaccess'] || $_SESSION['ds_accountingaccess'])
{
  echo '/ Fournisseur ';
}
echo $clientid,':</h2>';
echo '<form method="post" action="clients.php">
  
  <input type=hidden name="step" value="0">
  <input type=hidden name="saveme" value="1">
  <input type=hidden name="clientsmenu" value="<?php echo $clientsmenu; ">
  <input type=hidden name="clientid" value="' .$clientid .'">';

$css_alert = ''; #if($err_clientname_empty || $err_clientname_dup){$css_alert = 'class="alert"';} ???
echo '<table>';

echo '<tr><td>Nom:<td><input type="text" name="name" value="'.d_input(d_decode($clientname)) .'" size=50>' . $css_alert ;
echo '<tr><td>Prénom:<td><input type="text" name="clientfirstname" value="'.d_input($rowclient['clientfirstname']) .'" size=50>';

echo '<tr><td>Code client:<td><input type="text" name="clientcode" value="' .d_input($rowclient['clientcode']) .'" size=50">';
echo '<tr><td>Raison sociale:<td><input type="text" name="companytypename" value="' .d_input($rowclient['companytypename']) .'" size=20>';
echo '<tr><td>No Tahiti:<td><input type="text" name="tahitinumber" value="' .d_input($rowclient['tahitinumber']) .'"  size=20>';

echo '<tr><td>Date de création:<td>'; $datename = 'creationdate'; $selecteddate = $rowclient['creationdate']; 
      if ($selecteddate == NULL) { $dp_setempty = 1; }
      require('inc/datepicker.php');
      echo ' &nbsp; Dossier: <select name="dossier"><option value=0>Non</option><option value=1';
      if ($rowclient['dossier']) { echo ' selected'; }
      echo '>Oui</option></select>';
 
echo '<tr> <td>Peut acheter (interdit):<td><select name="blocked">
            <option value="0"></option>
            <option value="2"'; if ($rowclient['blocked'] == 2){ echo 'SELECTED';} echo '>COMPTE SUSPENDU</option>
            <option value="1"'; if ($rowclient['blocked'] == 1){ echo 'SELECTED';} echo '>COMPTE INTERDIT</option>
        </select>';
 
echo '<tr><td>Type de prix:<td><select name="usedetail">
      <option value="0"'; if ($rowclient['usedetail'] == 0){ echo 'SELECTED';} echo '>Normal</option>
      <option value="1"'; if ($rowclient['usedetail'] != 0){ echo 'SELECTED';} echo '>' .$_SESSION['ds_term_prixalternatif'] .'</option></select>';

echo '<tr><td>Remise par défaut:<td><input type=number min=0 size=5 name="discount" value="'.d_input($rowclient['discount']) .'"> %';
echo '<tr><td>Majoration:<td><input type=number min=0 size=5 name="surcharge" value="'.d_input($rowclient['surcharge']) .'"> %';

echo '<tr><td>Paiement TVA:<td><select name="vatexempt">
          <option value="0"'; if($rowclient['vatexempt'] == 0){ echo 'SELECTED';} echo '>Oui</option>
          <option value="1"'; if($rowclient['vatexempt'] == 1){ echo 'SELECTED';}echo '>Non</option>
      </select>';
 
echo '<tr><td>Délai de paiement:<td><select name="clienttermid">';
          $query = 'select clienttermid,clienttermname from clientterm order by clienttermid';
          $query_prm = array();
          require('inc/doquery.php');
          for ($i=0; $i < $num_results; $i++)
          {
            $selected = '';
            if ($query_result[$i]['clienttermid'] == $rowclient['clienttermid']){$selected = ' SELECTED';}
            echo '<option value="' .$query_result[$i]['clienttermid'] .'"' . $selected .' >' .$query_result[$i]['clienttermname'] .'</option>';
          }
        echo '</select>';
        
echo '<tr><td colspan=2>&nbsp;';
if ($_SESSION['ds_term_clientfield1'] != '')
{
  echo '<tr><td>',d_output($_SESSION['ds_term_clientfield1']),':<td><input type=text name="clientfield1" value="'.d_input($rowclient['clientfield1']) .'">';
}
if ($_SESSION['ds_term_clientfield2'] != '')
{
  echo '<tr><td>',d_output($_SESSION['ds_term_clientfield2']),':<td><input type=text name="clientfield2" value="'.d_input($rowclient['clientfield2']) .'">';
}
if ($_SESSION['ds_term_clientfield3'] != '')
{
  echo '<tr><td>',d_output($_SESSION['ds_term_clientfield3']),':<td><input type=text name="clientfield3" value="'.d_input($rowclient['clientfield3']) .'">';
}
if ($_SESSION['ds_term_clientfield4'] != '')
{
  echo '<tr><td>',d_output($_SESSION['ds_term_clientfield4']),':<td><input type=text name="clientfield4" value="'.d_input($rowclient['clientfield4']) .'">';
}
if ($_SESSION['ds_term_clientfield5'] != '')
{
  echo '<tr><td>',d_output($_SESSION['ds_term_clientfield5']),':<td><input type=text name="clientfield5" value="'.d_input($rowclient['clientfield5']) .'">';
}
if ($_SESSION['ds_term_clientfield6'] != '')
{
  echo '<tr><td>',d_output($_SESSION['ds_term_clientfield6']),':<td><input type=text name="clientfield6" value="'.d_input($rowclient['clientfield6']) .'">';
}
if ($_SESSION['ds_term_client_customdate1'] != '')
{
  echo '<tr><td>Date '.$_SESSION['ds_term_client_customdate1'].':<td>'; $datename = 'client_customdate1'; $selecteddate = $rowclient['client_customdate1']; 
  if ($selecteddate == NULL) { $dp_setempty = 1; }
  require('inc/datepicker.php');
}
if ($_SESSION['ds_term_client_customdate2'] != '')
{
  echo '<tr><td>Date '.$_SESSION['ds_term_client_customdate2'].':<td>'; $datename = 'client_customdate2'; $selecteddate = $rowclient['client_customdate2']; 
  if ($selecteddate == NULL) { $dp_setempty = 1; }
  require('inc/datepicker.php');
}
if ($_SESSION['ds_term_client_customdate3'] != '')
{
  echo '<tr><td>Date '.$_SESSION['ds_term_client_customdate3'].':<td>'; $datename = 'client_customdate3'; $selecteddate = $rowclient['client_customdate3']; 
  if ($selecteddate == NULL) { $dp_setempty = 1; }
  require('inc/datepicker.php');
}

if ($_SESSION['ds_use_loyalty_points'])
{
  echo '<tr><td colspan=2>&nbsp;<tr><td>Points de Fidelité :<td><input type=checkbox name="use_loyalty_points" value="1"'; if ($rowclient['use_loyalty_points'] == 1) { echo ' checked'; } echo '>';
  echo '<tr><td>Date Fidelité:<td>'; $datename = 'loyaltydate'; $selecteddate = $rowclient['loyaltydate']; 
  if ($selecteddate == NULL) { $dp_setempty = 1; }
  require('inc/datepicker.php');
  echo '<tr><td>Points au début :<td><input type=number name="loyalty_start" value="' . $rowclient['loyalty_start'] . '">';
  
}

echo '<tr><td colspan=2>&nbsp;<tr><td colspan=10>';
echo '<input type=checkbox name="isclient" value="1"'; if ($rowclient['isclient'] == 1) { echo ' checked'; } echo '>Client';
echo '&nbsp; &nbsp; <input type=checkbox name="issupplier" value="1"'; if ($rowclient['issupplier'] == 1) { echo ' checked'; } echo '>Fournisseur';
echo '&nbsp; &nbsp; <input type=checkbox name="isemployee" value="1"'; if ($rowclient['isemployee'] == 1) { echo ' checked'; } echo '>Salarié';
echo'&nbsp; &nbsp; <input type=checkbox name="isother" value=1'; if ($rowclient['isother'] == 1) { echo ' checked'; } echo '>Autre';
    
echo'<tr><td>Lead time (mois):<td><input type=number min=0 name="leadtime" value="' .d_input($rowclient['leadtime']) .'">';; 
  
echo '<tr><td colspan=2>&nbsp;';
echo '<tr><td>Employé '; echo $_SESSION['ds_term_clientemployee1'];
echo '<td><select name="employeeid"><option value="0"></option>';
          $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee where iscashier=1 and deleted=0 order by employeename';
          require('inc/doquery.php');
          for ($i=0; $i < $num_results; $i++)
          {
            $selected = '';
            if ($query_result[$i]['employeeid'] == $rowclient['employeeid']){$selected = ' SELECTED';}
            echo '<option value="' . $query_result[$i]['employeeid'] . '"' . $selected .'>' .d_input($query_result[$i]['employeename']) .'</option>';
           }
          echo '</select>';
  
          
echo '<tr><td>Employé '; echo $_SESSION['ds_term_clientemployee2']; echo '<td><select name="employeeid2"><option value="0"> </option>';
          $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename  from employee where iscashier=1 and deleted=0 order by employeename';
          require('inc/doquery.php');
          for ($i=0; $i < $num_results; $i++)
          {
            $selected = '';          
            if ($query_result[$i]['employeeid'] == $rowclient['employeeid2']){$selected = ' SELECTED';}
            echo '<option value="' . $query_result[$i]['employeeid'] . '"' . $selected . '>' . d_input($query_result[$i]['employeename']) . '</option>';
          }
        echo '</select>';


echo '<tr><td colspan=2>&nbsp;
<tr><td>Adresse ligne 1:<td><input type="text" name="address" value="' .d_input($rowclient['address']) .'"  size=50>';

echo '<tr><td>Adresse ligne 2:<td><input type="text" name="postaladdress" value="' .d_input($rowclient['postaladdress']) .'"  size=50>';

 echo ' <tr>
    <td>Code Postal:<td><input type="text" name="postalcode" value="' .d_input($rowclient['postalcode']) .'"  size=50>';

echo '<tr><td>Ile/Ville:<td><select name="townid">';
          $query = 'select townid,townname,islandname from town,island where town.islandid=island.islandid order by islandname,townname';
          require('inc/doquery.php');
          for ($i=0; $i < $num_results; $i++)
          {
            $selected = '';          
            if ($query_result[$i]['townid'] == $rowclient['townid']){$selected = ' SELECTED';}        
            echo '<option value="' . $query_result[$i]['townid'] . '"' . $selected . '>' . d_input($query_result[$i]['islandname']) . '/' . $query_result[$i]['townname'] . '</option>'; 
          }
        echo '</select>';
        
echo '<tr><td>Ville (hors PF / manuel):<td><input type="text" name="town_name" value="' .d_input($rowclient['town_name']) .'"  size=50>';
 
echo '<tr><td>Pays:<td><select name="countryid">
          <option value="0"> </option>';
          $query = 'select countryid,countryname from country order by `rank`, countryname';
          require('inc/doquery.php');
          for ($i=0; $i < $num_results; $i++)
          {
            $selected = '';          
            if ($query_result[$i]['countryid'] == $rowclient['countryid']){$selected = ' SELECTED';} 
            echo '<option value="' . $query_result[$i]['countryid'] . '"' . $selected .'>' . d_input($query_result[$i]['countryname']) . '</option>';
          }
        echo '</select>';


echo '<tr><td>Adresse géo:<td><input type="text" name="quarter" value="' .d_input($rowclient['quarter']) .'"  size=50> (comment trouver ce client)';

echo '<tr><td>Contact:<td><input type="text" name="contact" value="' .d_input($rowclient['contact']) .'"  size=80>';

echo '<tr><td>Contact 2:<td><input type="text" name="contact2" value="' .d_input($rowclient['contact2']) .'"  size=80>';
 
echo '<tr><td>Contact 3:<td><input type="text" name="contact3" value="' .d_input($rowclient['contact3']) .'"  size=80>';

if ($_SESSION['ds_term_client_telephone'] != '')
{
  echo '<tr><td>',d_output($_SESSION['ds_term_client_telephone']),':<td><input type="text" name="telephone" value="' .d_input($rowclient['telephone']) .'"  size=80>';
}
if ($_SESSION['ds_term_client_cellphone'] != '')
{
  echo '<tr><td>',d_output($_SESSION['ds_term_client_cellphone']),':<td><input type="text" name="cellphone" value="' .d_input($rowclient['cellphone']) .'"  size=80>';
}
if ($_SESSION['ds_term_client_telephone3'] != '')
{
  echo '<tr><td>',d_output($_SESSION['ds_term_client_telephone3']),':<td><input type="text" name="telephone3" value="' .d_input($rowclient['telephone3']) .'"  size=80>';
}
if ($_SESSION['ds_term_client_telephone4'] != '')
{
  echo '<tr><td>',d_output($_SESSION['ds_term_client_telephone4']),':<td><input type="text" name="telephone4" value="' .d_input($rowclient['telephone4']) .'"  size=80>';
}

echo '<tr><td>Fax:<td><input type="text" name="fax" value="' .d_input($rowclient['fax']) .'"  size=80>';

if ($_SESSION['ds_term_client_email'] != '')
{
  echo '<tr><td>',d_output($_SESSION['ds_term_client_email']),':<td><input type="text" name="email" value="' .d_input($rowclient['email']) .'"  size=80>';
}
if ($_SESSION['ds_term_client_email2'] != '')
{
  echo '<tr><td>',d_output($_SESSION['ds_term_client_email2']),':<td><input type="text" name="email2" value="' .d_input($rowclient['email2']) .'"  size=80>';
}
if ($_SESSION['ds_term_client_email3'] != '')
{
  echo '<tr><td>',d_output($_SESSION['ds_term_client_email3']),':<td><input type="text" name="email3" value="' .d_input($rowclient['email3']) .'"  size=80>';
}
if ($_SESSION['ds_term_client_email4'] != '')
{
  echo '<tr><td>',d_output($_SESSION['ds_term_client_email4']),':<td><input type="text" name="email4" value="' .d_input($rowclient['email4']) .'"  size=80>';
}
echo '<tr><td>Email pour Factures/Relevés:<td><input type="text" name="batchemail" value="' .d_input($rowclient['batchemail']) .'"  size=80>';

echo '<tr><td colspan=2>&nbsp;';
$dp_description = $_SESSION['ds_term_clientcategory']; $dp_selectedid = $rowclient['clientcategoryid']; require('inc/selectitem_clientcategory.php');
$dp_description = $_SESSION['ds_term_clientcategory2']; $dp_selectedid = $rowclient['clientcategory2id']; require('inc/selectitem_clientcategory2.php');
$dp_description = $_SESSION['ds_term_clientcategory3']; $dp_selectedid = $rowclient['clientcategory3id']; require('inc/selectitem_clientcategory3.php');

echo '<tr><td>Secteur:<td><select name="clientsectorid">';
          $query = 'select clientsectorid,clientsectorname from clientsector order by clientsectorname';
          require('inc/doquery.php');
          for ($i=0; $i < $num_results; $i++)
          {
            $selected = '';          
            if ($query_result[$i]['clientsectorid'] == $rowclient['clientsectorid']){$selected = ' SELECTED';} 
            echo '<option value="' . $query_result[$i]['clientsectorid'] . '"' . $selected .'>' . d_input($query_result[$i]['clientsectorname']) . '</option>';
          }
        echo '</select>';


 
echo '<tr><td>RC <td><input type="text" name="rc" value="' .d_input($rowclient['rc']) .'"  size=20>';
  
echo '<tr><td>Limite de crédit:<td><input type="text" name="outstandinglimit" value="' .d_input($rowclient['outstandinglimit']) .'"  size=20>';
  
echo '<tr><td>Référence de banque:<td><input type="text" name="bankreference" value="' .d_input($rowclient['bankreference']) .'"  size=30>';
 
echo '<tr><td>Commentaire:<td><input type="text" name="comment" value="' .d_input($rowclient['clientcomment']) .'"  size=100>';
 
echo '<tr><td valign=top>Historique:<td><textarea name="clienthistory" rows=6 cols=80>' .d_input($rowclient['clienthistory']) .'</textarea>';
 
    
echo '<tr><td>&nbsp;<tr><td colspan=2 align=left>Coordonnées bancaires
<tr><td>Titulaire:<td><input type="text" name="titu" value="' .d_input($rowclient['titu']) .'"  size=50>';
  
echo '<tr><td>Domiciliation:<td><input type="text" name="domi" value="' .d_input($rowclient['domi']) .'"  size=50>';
  
echo '<tr><td>Code Banque (5):<td><input type="text" name="codebanque" value="' .d_input($rowclient['codebanque']) .'"  size=50>';
  
echo '<tr><td>Code Guichet (5):<td><input type="text" name="guichet" value="' .d_input($rowclient['guichet']) .'" size=50>';
  
echo '<tr><td>No Compte (11):<td><input type="text" name="account" value="' .d_input($rowclient['account']) .'" size=50>';
 
echo '<tr>
    <td>Clé RIB:</td>
    <td><input type="text" name="clerib" value="' .d_input($rowclient['clerib']) .'"  size=50>'; 

echo '
<input type=hidden name="clientsmenu" value="' . $clientsmenu . '">
<tr><td colspan="2" align="center"><input type="submit" value="Valider">
</table> </form>';

}
?>