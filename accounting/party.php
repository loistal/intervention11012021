<?php

# modified copy of clients/modlclient.php
# TODO refactor

require('preload/country.php');

$err_clientname_dup = false;
$err_clientname_empty = false;

//UPDATE
if ($_POST['saveme'] == 1 && $_POST['clientid'] > 0)
{    
  $clientid = $_POST['clientid'];
  $clientname = d_encode($_POST['name']); # TODO encode used wrong in several places
  $clientcode = $_POST['clientcode'];
  $companytypename = $_POST['companytypename'];  
  $datename = 'creationdate'; $dp_allowempty = 1; require('inc/datepickerresult.php');
  if ($creationdate == NULL) { $creationdate = ''; }
  $deleted = (int) $_POST['deleted'];
  $usedetail = $_POST['usedetail'];  
  $surcharge = $_POST['surcharge'];   if ($surcharge < 0 || $surcharge > 100) { $surcharge = 0; } 
  $vatexempt = $_POST['vatexempt'];  
  $clienttermid = $_POST['clienttermid'];  

  /*
  $issupplier = $_POST['issupplier']+0;
  $isclient = $_POST['isclient']+0;
  $isemployee = $_POST['isemployee']+0;
  $isother = $_POST['isother']+0;
  */
  if ($_POST['clienttype'] == 1) { $isclient = 1; }
  elseif ($_POST['clienttype'] == 2) { $issupplier = 1; }
  elseif ($_POST['clienttype'] == 3) { $isemployee = 1; }
  elseif ($_POST['clienttype'] == 4) { $isother = 1; }
  
  $leadtime = $_POST['leadtime'];

  $employeeid = $_POST['employeeid'];
  $employeeid2 = $_POST['employeeid2'];

  $address = $_POST['address'];
  $postaladdress = $_POST['postaladdress'];
  $postalcode = $_POST['postalcode'];
  $townid = $_POST['townid'];
  $countryid = (int) $_POST['countryid']; #if ($countryid != 0 && mb_strtolower($countryA[$countryid]) != mb_strtolower('Polynésie française')) { $townid = 0; }  
  if(empty($countryid)){$countryid = '156';}
  $quarter = $_POST['quarter'];  
  $contact = $_POST['contact'];
  $contact2 = $_POST['contact2'];
  $contact3 = $_POST['contact3'];
  $telephone = $_POST['telephone'];
  $cellphone = $_POST['cellphone'];
  $fax = $_POST['fax'];
  $email = $_POST['email'];
  
  $clientcategoryid = $_POST['clientcategoryid'];
  $clientcategory2id = $_POST['clientcategory2id'];
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
  
  //To be deleted?
  $discount = $_POST['discount']; if ($discount == "") { $discount = 0; }
  
  //UPDATE
  $query_update = 'update client set ';
  
  if ($_SESSION['ds_purchaseaccess'] == 1) 
  { 
    $query_update = $query_update . 'issupplier=?,leadtime=?,'; 
  }
  $query_update = $query_update. 'isclient=?,isemployee=?,isother=?,clientcode=?,clienthistory=?,contact2=?,contact3=?,surcharge=?,countryid=?,employeeid2=?,clientsectorid=?,clerib=?,guichet=?,codebanque=?,domi=?,titu=?,usedetail=?,discount=?,quarter=?,postaladdress=?,outstandinglimit=?,companytypename=?,cellphone=?,postalcode=?,employeeid=?,deleted=?,account=?,vatexempt=?,address=?,townid=?,contact=?,telephone=?,fax=?,email=?,clientcategoryid=?,clientcategory2id=?,bankreference=?,clienttermid=?,surcharge=?,tahitinumber=?,rc=?,clientcomment=?,creationdate=?';
 
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
     
  $query = $query_update . ' where clientid=?';
  $query_prm = array($isclient,$isemployee,$isother,$clientcode,$clienthistory,$contact2,$contact3,$surcharge,$countryid,$employeeid2,$clientsectorid,$clerib,$guichet,$codebanque,$domi,$titu,$usedetail,$discount,$quarter, $postaladdress,$outstandinglimit,$companytypename,$cellphone,$postalcode,$employeeid,$deleted,$account,$vatexempt,$address,$townid,$contact,$telephone,$fax,$email,$clientcategoryid,$clientcategory2id,$bankreference,$clienttermid,$surcharge,$tahitinumber,$rc,$comment,$creationdate);

  if ($_SESSION['ds_purchaseaccess'] == 1)
  {
    array_unshift($query_prm,$issupplier,$leadtime);
  }
  if($clientname_modified)
  {
    array_push($query_prm,$clientname);
  }
  array_push($query_prm,$clientid);
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
      echo '<p>Tiers ' . $showclientname . ' modifié.</p>';
  }
  if($err_clientname_dup)
  {
    echo '<p class="alert">Le tiers ' . d_output(d_decode($clientname)) . ' existe déjà.</p>';
  }
  if($err_clientname_empty)
  {
    echo '<p class="alert">Le nom du tiers doit être renseigné.</span></p>';
  }
}

//FIND CLIENT
if ($_GET['client'] > 0) { $client = (int) $_GET['client']; }
else { $client = $_POST['client']; }
if (isset($_POST['clientid'])) { $clientid = $_POST['clientid']; }
else { $dp_allowdeletedclients = 1; require ('inc/findclient.php'); }

//UPDATE CLIENT FORM
if ($clientid < 1 && $_POST['addclient'] != 1) 
{
  echo '<form method="post" action="accounting.php">';
  echo '<fieldset><legend>Modifier tiers</legend>';

  $dp_description = 'Tiers:'; $canaddclient = 1;
  require('inc/selectclient.php');

  echo '<input type=hidden name="step" value="0"><input type=hidden name="accountingmenu" value="' . $accountingmenu . '"><input type=hidden name="accountingmenu_sa" value="admin">';
  echo '<input type="submit" value="Valider"></fieldset></form><br>';
  
  echo '<h2>Plan de Tiers</h2><table class=report><thead><th>Tiers<th>Adresse<th>Contact<th>Téléphone</thead>';
  $query = 'select clientid,clientname,address,contact,telephone from client where deleted=0 order by clientname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i = 0; $i < $num_results; $i++)
  {
    echo d_tr();
    echo '<td><a href="accounting.php?accountingmenu=party&accountingmenu_sa=admin&client='.$query_result[$i]['clientid'].'">',d_output(d_decode($query_result[$i]['clientname'])),'</a>';
    echo '<td>',d_output($query_result[$i]['address']);
    echo '<td>',d_output($query_result[$i]['contact']);
    echo '<td>',d_output($query_result[$i]['telephone']);
  }
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
  echo '<h2>Modifier tiers ' .  $clientid . ':</h2>';
  #echo '<span class="alert">Merci de bien sélectionner ci-dessous le type de tiers : client, fournisseur, salarié ou autres</span><br>';
  ?>
  
  <form method="post" action="accounting.php">
  
  <input type=hidden name="step" value="0">
  <input type=hidden name="saveme" value="1">
  <input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
  <input type=hidden name="clientid" value="<?php echo $clientid ?>">
  
  <table>
  
  <?php
  /*
  <tr><td colspan=10>
  <input type=checkbox name='isclient' value=1 <?php if ($rowclient['isclient'] == 1) { echo ' checked'; } ?>>Client
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type=checkbox name='issupplier' value=1 <?php if ($rowclient['issupplier'] == 1) { echo ' checked'; } ?>>Fournisseur
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type=checkbox name='isemployee' value=1 <?php if ($rowclient['isemployee'] == 1) { echo ' checked'; } ?>>Salarié
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type=checkbox name='isother' value=1 <?php if ($rowclient['isother'] == 1) { echo ' checked'; } ?>>Autre
  </td></tr>
  */
  ?>
  <tr><td colspan=10>
  <input type=radio name='clienttype' value=1 <?php if ($rowclient['isclient'] == 1) { echo ' checked'; } ?>>Client
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type=radio name='clienttype' value=2 <?php if ($rowclient['issupplier'] == 1) { echo ' checked'; } ?>>Fournisseur
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type=radio name='clienttype' value=3 <?php if ($rowclient['isemployee'] == 1) { echo ' checked'; } ?>>Salarié
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type=radio name='clienttype' value=4 <?php if ($rowclient['isother'] == 1) { echo ' checked'; } ?>>Autre
  </td></tr>
  
  <tr>
    <td>Nom:</td>
    <?php $css_alert = '';if($err_clientname_empty || $err_clientname_dup){$css_alert = 'class="alert"';} ?>
    <td><input type="text" name="name" value="<?php echo d_input(d_decode($clientname));?>" size=50" ' . $css_alert .'></td>
  </tr> 
  
  <tr>
    <td>Code client:</td>
    <td><input type="text" name="clientcode" value="<?php echo d_input($rowclient['clientcode']);?>" size=50"></td>
  </tr>
  <tr>
    <td>Raison sociale:</td>
    <td><input type="text" name="companytypename" value="<?php echo d_input($rowclient['companytypename']);?>" size=20></td>    
  </tr>
  
  <tr>
    <td>Date de création:</td>
    <td>
      <?php $datename = 'creationdate'; $selecteddate = $rowclient['creationdate']; 
      if ($selecteddate == NULL) { $dp_setempty = 1; }
      require('inc/datepicker.php');?>
    </td>
   </tr>
   
  <tr>
    <td>Supprimé:</td><td>
    <input type=checkbox name='deleted' value=1 <?php if ($rowclient['deleted'] == 1) { echo ' checked'; } ?>>
  </tr>

  
 

  <tr>
    <td>Adresse ligne 1:</td>
    <td><input type="text" name="address" value="<?php echo d_input($rowclient['address']); ?>"  size=50></td>
  </tr>
  <tr>
    <td>Adresse ligne 2:</td>
    <td><input type="text" name="postaladdress" value="<?php echo d_input($rowclient['postaladdress']);?>"  size=50></td>
  </tr>
  <tr>
    <td>Code Postal:</td>
    <td><input type="text" name="postalcode" value="<?php echo d_input($rowclient['postalcode']);?>"  size=50></td>
  </tr>
  <tr>
    <td>Ile/Ville:</td>
    <td><select name="townid">
          <?php $query = 'select townid,townname,islandname from town,island where town.islandid=island.islandid order by islandname,townname';
          require('inc/doquery.php');
          for ($i=0; $i < $num_results; $i++)
          {
            $selected = '';          
            if ($query_result[$i]['townid'] == $rowclient['townid']){$selected = ' SELECTED';}        
            echo '<option value="' . $query_result[$i]['townid'] . '"' . $selected . '>' . d_input($query_result[$i]['islandname']) . '/' . $query_result[$i]['townname'] . '</option>'; 
          }?>
        </select>
    </td>
  </tr>
  <tr>
    <td>Pays:</td>
    <td><select name="countryid">
          <option value="0"> </option>
          <?php $query = 'select countryid,countryname from country order by rank, countryname';
          require('inc/doquery.php');
          for ($i=0; $i < $num_results; $i++)
          {
            $selected = '';          
            if ($query_result[$i]['countryid'] == $rowclient['countryid']){$selected = ' SELECTED';} 
            echo '<option value="' . $query_result[$i]['countryid'] . '"' . $selected .'>' . d_input($query_result[$i]['countryname']) . '</option>';
          }?>
        </select>
    </td>
  </tr>

  <tr>
    <td>Adresse géo:</td>
    <td><input type="text" name="quarter" value="<?php echo d_input($rowclient['quarter']);?>"  size=50> (comment trouver ce client)</td>
  </tr>
  <tr>
    <td>Contact:</td>
    <td><input type="text" name="contact" value="<?php echo d_input($rowclient['contact']);?>"  size=80></td>
  </tr>
  <tr>
    <td>Contact 2:</td>
    <td><input type="text" name="contact2" value="<?php echo d_input($rowclient['contact2']);?>"  size=80></td>
  </tr>
  <tr>
    <td>Contact 3:</td>
    <td><input type="text" name="contact3" value="<?php echo d_input($rowclient['contact3']);?>"  size=80></td>
  </tr>
  <tr>
    <td>Téléphone:</td>
    <td><input type="text" name="telephone" value="<?php echo d_input($rowclient['telephone']);?>"  size=80></td>
  </tr>
  <tr>
    <td>Vini:</td>
    <td><input type="text" name="cellphone" value="<?php echo d_input($rowclient['cellphone']);?>"  size=80></td>
  </tr>
  <tr>
    <td>Fax:</td>
    <td><input type="text" name="fax" value="<?php echo d_input($rowclient['fax']);?>"  size=80></td>
  </tr>
  <tr>
    <td>Email:</td>
    <td><input type="text" name="email" value="<?php echo d_input($rowclient['email']);?>"  size=80></td>
  </tr>
  <?php /*
  <tr><td colspan=2>&nbsp;</td></tr>
  
  <tr>
    <td>Catégorie:</td>
    <td>
    <?php
    $dp_itemname = 'clientcategory'; $dp_selectedid = $rowclient['clientcategoryid']; require('inc/selectitem.php');
    ?>
  </tr>
  <tr>
    <td>Catégorie 2:</td>
    <td>
    <?php
    $dp_itemname = 'clientcategory2'; $dp_selectedid = $rowclient['clientcategory2id']; require('inc/selectitem.php');
    ?>
    </td>
  </tr>
  <tr>
    <td>Secteur:</td>
    <td><select name="clientsectorid">
          <?php $query = 'select clientsectorid,clientsectorname from clientsector order by clientsectorname';
          require('inc/doquery.php');
          for ($i=0; $i < $num_results; $i++)
          {
            $selected = '';          
            if ($query_result[$i]['clientsectorid'] == $rowclient['clientsectorid']){$selected = ' SELECTED';} 
            echo '<option value="' . $query_result[$i]['clientsectorid'] . '"' . $selected .'>' . d_input($query_result[$i]['clientsectorname']) . '</option>';
          }?>
        </select>
    </td>
  </tr> */ ?>
  <tr>
    <td>No Tahiti:</td>
    <td><input type="text" name="tahitinumber" value="<?php echo d_input($rowclient['tahitinumber']);?>"  size=20></td>
  </tr>
  <tr>
    <td>RC:</td>
    <td><input type="text" name="rc" value="<?php echo d_input($rowclient['rc']);?>"  size=20></td>
  </tr>
  <?php /*
  <tr>
    <td>Limite de crédit:</td>
    <td><input type="text" name="outstandinglimit" value="<?php echo d_input($rowclient['outstandinglimit']);?>"  size=20></td>
  </tr>
  <tr>
    <td>Référence de banque:</td>
    <td><input type="text" name="bankreference" value="<?php echo d_input($rowclient['bankreference']);?>"  size=30></td>
  </tr> */ ?>
  <tr>
    <td>Commentaire:</td>
    <td><input type="text" name="comment" value="<?php echo d_input($rowclient['clientcomment']);?>"  size=100></td>
  </tr>
  <tr>
    <td valign=top>Historique:</td>
    <td><textarea name="clienthistory" rows=6 cols=80><?php echo d_input($rowclient['clienthistory']);?></textarea></td>
  </tr>
  <?php /*
  <tr><td>&nbsp;</td></tr>
  
  <tr><td colspan=2 align=left>Coordonnées bancaires</td></tr>
  
  <tr>
    <td>Titulaire:</td>
    <td><input type="text" name="titu" value="<?php echo d_input($rowclient['titu']);?>"  size=50></td>
  </tr>
  <tr>
    <td>Domiciliation:</td>
    <td><input type="text" name="domi" value="<?php echo d_input($rowclient['domi']);?>"  size=50></td>
  </tr>
  <tr>
    <td>Code Banque (5):</td>
    <td><input type="text" name="codebanque" value="<?php echo d_input($rowclient['codebanque']);?>"  size=50></td>
  </tr>
  <tr>
    <td>Code Guichet (5):</td>
    <td><input type="text" name="guichet" value="<?php echo d_input($rowclient['guichet']);?>"  size=50></td>
  </tr>
  <tr>
    <td>No Compte (11):</td>
    <td><input type="text" name="account" value="<?php echo d_input($rowclient['account']);?>"  size=50></td>
  </tr>
  <tr>
    <td>Clé RIB:</td>
    <td><input type="text" name="clerib" value="<?php echo d_input($rowclient['clerib']);?>"  size=50></td>
  </tr>
    */ ?>
  <tr><td colspan="2" align="center"><input type=hidden name="accountingmenu_sa" value="admin">
  <input type="submit" value="Valider"></td>
  </tr> 
  </table>
  </form>
<?php
}
?>