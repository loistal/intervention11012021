<?php

require('inc/password.php'); # TODO remove once Wing Chong Server at PHP5.5+

$PA['userid'] = 'uint';
$PA['saveme'] = 'uint';
require('inc/readpost.php');

require('inc/findclient.php');
$access_clientid = $clientid;

if ($userid > 0 && $saveme == 1)
{
  $deleted = $_POST['deleted'] + 0;
  $maxattempts = $_POST['maxattempts']+0;
  $attempts = $_POST['attempts']+0;
  
  //check if user already exist
  $err_username_empty = 0;$err_username_dup = 0;
  
  $username = $_POST['username'];
  $query_update = 'update usertable set name=?,initials=?'; 
  $query_update_prm = array($_POST['name'],$_POST['initials']);    
  if($username == '')
  {
    $err_username_empty = 1;  
  }
  else
  {
    $query = 'select userid from usertable where username=? and userid<>?';
    $query_prm = array($username,$userid);
    require('inc/doquery.php');

    if ($num_results > 0)
    {
      $err_username_dup = 1;      
    }
    else
    {
      $query_update .= ',username=?';
      array_push($query_update_prm,$username);
    }
  }
  
  $query = $query_update; 
  $query_prm = $query_update_prm;  
  if ($_POST['salesaccess'] == "") $_POST['salesaccess'] = 0;
  if ($_POST['reportsaccess'] == "") $_POST['reportsaccess'] = 0;
  if ($_POST['purchaseaccess'] == "") $_POST['purchaseaccess'] = 0;
  if ($_POST['usebyaccess'] == "") $_POST['usebyaccess'] = 0;
  if ($_POST['accountingaccess'] == "") $_POST['accountingaccess'] = 0;
  if ($_POST['optionsaccess'] == "") $_POST['optionsaccess'] = 0;
  if ($_POST['adminaccess'] == "") $_POST['adminaccess'] = 0;
  if ($_POST['systemaccess'] == "") $_POST['systemaccess'] = 0;
  if ($_POST['ishrsuperuser'] == "") $_POST['ishrsuperuser'] = 0;
  if ($_POST['manage_qr'] == "") $_POST['manage_qr'] = 0;
  #if ($_POST['ishradmin'] == "") $_POST['ishradmin'] = 0;
  if ($_POST['noinvoicedate'] == "") $_POST['noinvoicedate'] = 0;
  if ($_POST['nopaymentdate'] == "") $_POST['nopaymentdate'] = 0;
  if ($_POST['nopayments'] == "") $_POST['nopayments'] = 0;
  if ($_POST['noreturns'] == "") $_POST['noreturns'] = 0;
  if ($_POST['nomodinvoice'] == "") $_POST['nomodinvoice'] = 0;
  if ($_POST['showinvoice_modify_options'] == "") $_POST['showinvoice_modify_options'] = 0;
  if ($_POST['noconfirm'] == "") $_POST['noconfirm'] = 0;
  $warehouseaccess = $_POST['warehouseaccess'] + 0;
  $deliveryaccess = $_POST['deliveryaccess'] + 0;
  $invoicedirecttopayment = $_POST['invoicedirecttopayment'] + 0;
  $autoconfirminvoices = $_POST['autoconfirminvoices'] + 0;
  $cannotconfirmnotice = (int) $_POST['cannotconfirmnotice'];
  $mywarehouseid = (int) $_POST['warehouseid'];
  $warehouseaccesstype = (int) $_POST['warehouseaccesstype'];
  $can_send_emails = (int) $_POST['can_send_emails'];
  $use_invoiceitemgroup = (int) $_POST['use_invoiceitemgroup'];

  $query .=  ',stockperthisuser=?';array_push($query_prm,$_POST['stockperthisuser']+0);
  $query .=  ',hide_invoice_fields=?';array_push($query_prm,$_POST['hide_invoice_fields']+0);
  $query .=  ',deliveryaccess=?';array_push($query_prm,$deliveryaccess);
  $query .=  ',warehouseaccess=?';array_push($query_prm,$warehouseaccess);
  $query .=  ',salesaccess=?';array_push($query_prm,$_POST['salesaccess']);
  $query .=  ',clientsaccess=?';array_push($query_prm,($_POST['clientsaccess']+0));
  $query .=  ',reportsaccess=?';array_push($query_prm,$_POST['reportsaccess']);
  $query .=  ',purchaseaccess=?';array_push($query_prm,$_POST['purchaseaccess']);
  $query .=  ',usebyaccess=?';array_push($query_prm,$_POST['usebyaccess']);
  $query .=  ',accountingaccess=?';array_push($query_prm,$_POST['accountingaccess']);
  $query .=  ',optionsaccess=?';array_push($query_prm,$_POST['optionsaccess']);
  $query .=  ',adminaccess=?';array_push($query_prm,$_POST['adminaccess']);
  $systemaccess = $_POST['systemaccess'];
  $query .=  ',systemaccess=?';array_push($query_prm,$systemaccess);
  $query .=  ',ishrsuperuser=?';array_push($query_prm,$_POST['ishrsuperuser']);
  $query .=  ',manage_qr_locations=?';array_push($query_prm,$_POST['manage_qr']);
  #$query .=  ',ishradmin=?';array_push($query_prm,$_POST['ishradmin']);
  $query .=  ',noinvoicedate=?';array_push($query_prm,$_POST['noinvoicedate']);
  $query .=  ',nopaymentdate=?';array_push($query_prm,$_POST['nopaymentdate']);
  $query .=  ',nopayments=?';array_push($query_prm,$_POST['nopayments']);
  $query .=  ',noreturns=?';array_push($query_prm,$_POST['noreturns']);
  $query .=  ',nomodinvoice=?';array_push($query_prm,$_POST['nomodinvoice']);
  $query .=  ',showinvoice_modify_options=?';array_push($query_prm,$_POST['showinvoice_modify_options']);
  $query .=  ',noconfirm=?';array_push($query_prm,$_POST['noconfirm']);
  $query .=  ',noprice=?';array_push($query_prm,($_POST['noprice']+0));
  $query .=  ',nostock=?';array_push($query_prm,($_POST['nostock']+0));
  $query .=  ',acc_canmodinvoice=?';array_push($query_prm,($_POST['acc_canmodinvoice']+0));
  $query .=  ',acc_canmodpayment=?';array_push($query_prm,($_POST['acc_canmodpayment']+0));
  $query .=  ',myemployeeid=?';array_push($query_prm,($_POST['employeeid']+0));
  $query .=  ',restrictbyplanning=?';array_push($query_prm,($_POST['restrictbyplanning']+0));
  $enterttcq = $_POST['enterttcq']+0;
  $query .=  ',enterttcq=?';array_push($query_prm,$enterttcq);
  $query .=  ',useremail=?';array_push($query_prm,$_POST['useremail']);
  $query .=  ',deleted=?';array_push($query_prm,$deleted);
  $query .=  ',maxattempts=?';array_push($query_prm,$maxattempts);
  $query .=  ',attempts=?';array_push($query_prm,$attempts);
  $query .=  ',emphasiscolor=?';array_push($query_prm,ltrim($_POST['emphasiscolor'],"#"));
  $query .=  ',invoicedirecttopayment=?';array_push($query_prm,$invoicedirecttopayment);
  $query .=  ',autoconfirminvoices=?';array_push($query_prm,$autoconfirminvoices);
  $query .=  ',cannotconfirmnotice=?';array_push($query_prm,$cannotconfirmnotice);
  $query .=  ',mywarehouseid=?';array_push($query_prm,$mywarehouseid);
  $query .=  ',warehouseaccesstype=?';array_push($query_prm,$warehouseaccesstype);
  $query .=  ',can_send_emails=?';array_push($query_prm,$can_send_emails);
  $query .=  ',use_invoiceitemgroup=?';array_push($query_prm,$use_invoiceitemgroup);
  
  $query .=  ' where userid=?';array_push($query_prm,$userid);

  require('inc/doquery.php');
  
  $query = 'update usertable set access_clientid=?,deliveryaccessinvoices=?,deliveryaccessreturns=?,nosalesreportsvalues=?,sqllimit=?,userrepresentsclientid=?,confirmonlyown=?,monstart=?,monstop=?,tuestart=?,tuestop=?,wedstart=?,wedstop=?,thustart=?,thustop=?,fristart=?,fristop=?,satstart=?,satstop=?,sunstart=?,sunstop=? where userid=?';
  $query_prm = array($access_clientid
  ,(int) $_POST['deliveryaccessinvoices']
  ,(int) $_POST['deliveryaccessreturns']
  ,(int) $_POST['nosalesreportsvalues']
  ,(int) $_POST['sqllimit']
  ,(int) $_POST['userrepresentsclientid']
  ,(int) $_POST['confirmonlyown']
  ,$_POST['monstart']
  ,$_POST['monstop']
  ,$_POST['tuestart']
  ,$_POST['tuestop']
  ,$_POST['wedstart']
  ,$_POST['wedstop']
  ,$_POST['thustart']
  ,$_POST['thustop']
  ,$_POST['fristart']
  ,$_POST['fristop']
  ,$_POST['satstart']
  ,$_POST['satstop']
  ,$_POST['sunstart']
  ,$_POST['sunstop']
  ,$userid);
  require ('inc/doquery.php');
    
  if ($_SESSION['ds_uselocalbol'] == 1)
  {
    $query = 'update usertable set nolocalbol=? where userid=?';
    $query_prm = array($_POST['nolocalbol']+0,$userid);
    require ('inc/doquery.php');
  }
  
  if($err_username_empty)
  {
    echo '<p class="alert">' . d_trad('usermustnotbeempty') . '<p>';
  } 
  elseif($err_username_dup)
  {
    echo '<p class="alert">' . d_trad('useralreadyexists',array($username)) . '<p>';
  } 
  else
  {
    echo '<p>' . d_trad('usermodified',array($username)) . '</p><br>';
  }
  
  if ($_POST['newpasswd'] == 1)
  {
    $oldpassword = '';
    $ourcharacters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $salt = '';
    $ourlength = mb_strlen($ourcharacters) - 1;
    for ($p = 0; $p < 6; $p++)
    {
      $oldpassword .= $ourcharacters[mt_rand(0, $ourlength)];
    }
    for ($p = 0; $p < 10; $p++)
    {
      $salt .= $ourcharacters[mt_rand(0, $ourlength)];
    }
    #$shadow = hash('sha512',$oldpassword . $salt);
    $password_hash = password_hash($oldpassword, PASSWORD_DEFAULT, ["cost" => 13]);
    $passwordok = 0;
    if (mb_strlen($oldpassword) > 5) { $passwordok++; } # 1
    if (mb_strlen($oldpassword) > 11) { $passwordok++; } # 2
    if (preg_match('#[0-9]#',$oldpassword)) { $passwordok++; } # 3
    if (strtolower($oldpassword) != $oldpassword && mb_strtoupper($oldpassword) != $oldpassword) { $passwordok++; } # 4
    if (preg_match('/[|!@#$%&*\/=?,;.:\-_+~^\\\]/', $oldpassword)) { $passwordok++; } # 5
    $query = 'update usertable set shadow="",salt="",password_hash=?,password=? where userid=?';
    $query_prm = array($password_hash,$passwordok,$userid);
    require ('inc/doquery.php');
    #echo 'created salt='.$salt.' and shadow='.$shadow.'<br>';
    echo '<p>' . d_trad('newpwd:') . ' <span class="alert">' . d_output($oldpassword) . '</span></p>';
  }
}

if ($userid > 0)
{
  $query = 'select * from usertable where userid=?';
  $query_prm = array($_POST['userid']+0);
  require('inc/doquery.php');
  $row = $query_result[0]; 
  
  echo '<h2>' . d_trad('modifyuser') . '</h2>'; ?>
  <form method="post" action="system.php"><table>
  <tr><td><?php echo d_trad('namelogin:'); ?></td><td><input type="text" name="username" value="<?php echo $row['username']; ?>" size=20></td></tr>
  <tr><td><?php echo d_trad('completename:'); ?></td><td><input type="text" name="name" value="<?php echo $row['name']; ?>" size=20></td></tr>
  <tr><td><?php echo d_trad('initials:'); ?></td><td><input type="text" name="initials" value="<?php echo $row['initials']; ?>" size=20> <input type=color name=emphasiscolor value="#<?php echo $row['emphasiscolor']; ?>"></td></tr>
  <tr><td><?php echo d_trad('email:'); ?></td><td><input type="text" name="useremail" value="<?php echo $row['useremail']; ?>" size=20></td></tr>
  <?php
  echo '<tr><td>Stock pour cet utilisateur:</td><td><input type="checkbox" name="stockperthisuser" value="1"'; if ($row['stockperthisuser']) echo ' CHECKED'; echo '></td></tr>';

  echo '<tr><td colspan=2>&nbsp;</td></tr>';
  
  echo '<tr><td>' . d_trad('purchaseaccess:') . '</td><td><input type="checkbox" name="salesaccess" value="1"'; if ($row['salesaccess']) echo ' CHECKED'; echo '></td></tr>';
  if ($_SESSION['ds_uselocalbol'] == 1)
  {
    echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="nolocalbol" value="1"'; if ($row['nolocalbol']) echo ' CHECKED'; echo '>&nbsp;' . d_trad('cannotdisplaybilloflading') .'</td></tr>';
  }
  echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="noinvoicedate" value="1"'; if ($row['noinvoicedate']) echo ' CHECKED'; echo '>&nbsp;' . d_trad('cannotchangeinvoicedate') .'</td></tr>';
  echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="nopaymentdate" value="1"'; if ($row['nopaymentdate']) echo ' CHECKED'; echo '>&nbsp;' . d_trad('cannotchangepaymentdate') .'</td></tr>';
  echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="nopayments" value="1"'; if ($row['nopayments']) echo ' CHECKED'; echo '>&nbsp;' . d_trad('cannotcollect') .'</td></tr>';
  echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="noreturns" value="1"'; if ($row['noreturns']) echo ' CHECKED'; echo '>&nbsp;Ne peut pas faire des avoirs';
  echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="nomodinvoice" value="1"'; if ($row['nomodinvoice']) echo ' CHECKED'; echo '>&nbsp;' . d_trad('cannotmodifyinvoices') .'</td></tr>';
  echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="showinvoice_modify_options" value="1"'; if ($row['showinvoice_modify_options']) echo ' CHECKED'; echo '>&nbsp;' . 'Ne peut pas modifier affichage facture' . '</td></tr>';
  echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="noconfirm" value="1"'; if ($row['noconfirm']) echo ' CHECKED'; echo '>&nbsp;' . d_trad('cannotconfirmcancelinvoices') .'</td></tr>';
  echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="cannotconfirmnotice" value="1"'; if ($row['cannotconfirmnotice']) echo ' CHECKED'; echo '>&nbsp;Ne peut pas confirmer '.$_SESSION['ds_term_invoicenotice'].'</td></tr>';
  echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="confirmonlyown" value="1"'; if ($row['confirmonlyown']) echo ' CHECKED'; echo '>&nbsp;' . d_trad('canmanageonlyowninvoices') .'</td></tr>';
  echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="nosalesreportsvalues" value="1"'; if ($row['nosalesreportsvalues']) echo ' CHECKED'; echo '>&nbsp;' . d_trad('cannotdisplayvaluesinreports') .'</td></tr>';
  echo '<tr><td>&nbsp;<td><input type="checkbox" name="use_invoiceitemgroup" value="1"';
  if ($row['use_invoiceitemgroup']) { echo ' checked'; }
  echo '>&nbsp;Regroupement manuel des lignes';
  echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="enterttcq" value="1"'; if ($row['enterttcq']) echo ' CHECKED'; echo '>&nbsp;' . d_trad('displayvatbyquantity') .'</td></tr>';
  echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="invoicedirecttopayment" value="1"'; if ($row['invoicedirecttopayment']) echo ' CHECKED'; echo '>&nbsp;Montre la saisie paiement après saisie facture</td></tr>';
  echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="autoconfirminvoices" value="1"'; if ($row['autoconfirminvoices']) echo ' CHECKED'; echo '>&nbsp;Auto-confirmer factures sur paiement exact</td></tr>';
  echo '<tr><td>&nbsp;</td><td>Cacher champs:<select name="hide_invoice_fields"><option value=0></option><option value=1'; if ($row['hide_invoice_fields'] == 1) { echo ' selected'; }
  echo '>Employé(e), '.d_output($_SESSION['ds_term_localvessel']).', '.d_output($_SESSION['ds_term_reference']).'</option></select></td></tr>';
  echo '<tr><td><td><input type="checkbox" name="restrictbyplanning" value="1"'; if ($row['restrictbyplanning']) echo ' CHECKED'; echo '> Restriction par planning : restreint à l\'employé(e) défini dans RH';
  $urcid = $row['userrepresentsclientid']; if ($urcid == 0) { $urcid = ''; }
  echo '<tr><td></td><td><input type="number" STYLE="text-align:right" min=0 name="userrepresentsclientid" value="' . $urcid . '"> Restriction par fournisseur (aucun accès au RH pour ce type d\'employé)';
  $sqllimit = $row['sqllimit']; if ($sqllimit == 0) { $sqllimit = ''; }
  echo '<tr><td></td><td><input type="number" STYLE="text-align:right" min=0 name="sqllimit" value="' . $sqllimit . '"> Max lignes dans les rapports (rapports factures, paiements, évènements, produits vendus)';
  
  
  if ($_SESSION['ds_usedelivery'] > 0)
  {
    echo '<tr><td colspan=2>&nbsp;</td></tr>';
    
    echo '<tr><td>' . d_trad('deliveryaccess:') .'</td><td><input type="checkbox" name="deliveryaccess" value="1"'; if ($row['deliveryaccess']) echo ' CHECKED'; echo '></td></tr>';
    echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="deliveryaccessinvoices" value="1"'; if ($row['deliveryaccessinvoices']) echo ' CHECKED'; echo '>&nbsp;' . d_trad('deliveryaccessinvoices') .'</td></tr>';
    echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="deliveryaccessreturns" value="1"'; if ($row['deliveryaccessreturns']) echo ' CHECKED'; echo '>&nbsp;' . d_trad('deliveryaccessreturns') .'</td></tr>';
  }
  
  echo '<tr><td colspan=2>&nbsp;</td></tr>';

  echo '<tr><td>' . d_trad('clientaccess:') .'</td><td><input type="checkbox" name="clientsaccess" value="1"'; if ($row['clientsaccess']) echo ' CHECKED'; echo '></td></tr>';
  echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="can_send_emails" value="1"'; if ($row['can_send_emails']) echo ' CHECKED'; echo '>&nbsp;Peut envoyer des e-mails';
  
  echo '<tr><td colspan=2>&nbsp;</td></tr>';
  
  echo '<tr><td>' . d_trad('productaccess:') .'</td><td><input type="checkbox" name="usebyaccess" value="1"'; if ($row['usebyaccess']) echo ' CHECKED'; echo '></td></tr>';
  echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="noprice" value="1"'; if ($row['noprice']) echo ' CHECKED'; echo '>&nbsp;' . d_trad('cannotmodifyprices') .'</td></tr>';
  #echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="nostock" value="1"'; if ($row['nostock']) echo ' CHECKED'; echo '>&nbsp;' . d_trad('cannotmodifystock') .'</td></tr>';
  echo '<tr><td>&nbsp;</td><td><input type="radio" name="nostock" value="0"'; if ($row['nostock'] == 0) echo ' checked'; echo '>&nbsp; Peut modifier';
  echo ' &nbsp; <input type="radio" name="nostock" value="1"'; if ($row['nostock'] == 1) echo ' checked'; echo '>&nbsp;' . d_trad('cannotmodifystock');
  echo ' &nbsp; <input type="radio" name="nostock" value="2"'; if ($row['nostock'] == 2) echo ' checked'; echo '>&nbsp;Stock par produit uniquement';
  
  echo '<tr><td colspan=2>&nbsp;</td></tr>';
  
  echo '<tr><td>' . d_trad('warehouseaccess:') .'</td><td><input type="checkbox" name="warehouseaccess" value="1"'; if ($row['warehouseaccess']) echo ' CHECKED'; echo '> ';
  echo ' &nbsp; <select name="warehouseaccesstype"><option value=0>Mouvement</option><option value=1';
  if ($row['warehouseaccesstype'] == 1) { echo ' selected'; }
  echo '>Tout</option><option value=2';
  if ($row['warehouseaccesstype'] == 2) { echo ' selected'; }
  echo '>Picking</option></select>';
  $dp_itemname = 'warehouse'; $dp_description = 'Entrepôt'; $dp_selectedid = $row['mywarehouseid']; $dp_notable = 1;
  echo '<tr><td><td>';
  require('inc/selectitem.php');
  
  echo '<tr><td colspan=2>&nbsp;</td></tr>';
  
  echo '<tr><td>' . d_trad('salesaccess:') .'</td><td><input type="checkbox" name="purchaseaccess" value="1"'; if ($row['purchaseaccess']) echo ' CHECKED'; echo '></td></tr>';
  
  echo '<tr><td colspan=2>&nbsp;</td></tr>';
  
  echo '<tr><td>' . d_trad('accountingaccess:') .'</td><td><input type="checkbox" name="accountingaccess" value="1"'; if ($row['accountingaccess']) echo ' CHECKED'; echo '></td></tr>';
  echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="acc_canmodinvoice" value="1"'; if ($row['acc_canmodinvoice']) echo ' CHECKED'; echo '>&nbsp;' . d_trad('canmodifyinvoices') .'</td></tr>';
  echo '<tr><td>&nbsp;</td><td><input type="checkbox" name="acc_canmodpayment" value="1"'; if ($row['acc_canmodpayment']) echo ' CHECKED'; echo '>&nbsp;' . d_trad('canmodifypayments') .'</td></tr>';
  
  echo '<tr><td colspan=2>&nbsp;</td></tr>';
  
  echo '<tr><td>' . d_trad('reportsaccess:') .'</td><td><input type="checkbox" name="reportsaccess" value="1"'; if ($row['reportsaccess']) echo ' CHECKED'; echo '></td></tr>';
  
  echo '<tr><td colspan=2>&nbsp;</td></tr>';
  
  $dp_itemname = 'employee'; $dp_description = 'Accès RH'; $dp_selectedid = $row['myemployeeid'];
  require('inc/selectitem.php'); echo ' &nbsp; Cet utilisateur correspond à quel employé?';
  
  echo '<tr><td><td><input type="checkbox" name="ishrsuperuser" value="1"';
  if ($row['ishrsuperuser']) echo ' CHECKED'; echo '> Super-utilisateur RH';
  
  echo '<tr><td colspan=2>&nbsp;</td></tr>';
  echo '<tr><td>Accès QR:<td><input type="checkbox" name="manage_qr" value="1"';
  if ($row['manage_qr_locations']) echo ' CHECKED'; echo '>';
  
  echo '<tr><td colspan=2>&nbsp;</td></tr>';

  echo '<tr><td>' . d_trad('adminaccess:');
  echo '</td><td><input type="checkbox" name="adminaccess" value="1"'; if ($row['adminaccess']) echo ' CHECKED'; echo '></td></tr>';
  
  echo '<tr><td colspan=2>&nbsp;</td></tr>';
  
  echo '<tr><td>' . d_trad('systemaccess:') .'</td><td><input type="checkbox" name="systemaccess" value="1"'; if ($row['systemaccess']) echo ' CHECKED'; echo '></td></tr>';
  
  echo '<tr><td colspan=2>&nbsp;</td></tr>';
  
  echo '<tr><td>' . d_trad('optionsaccess:') .'</td><td><input type="checkbox" name="optionsaccess" value="1"'; if ($row['optionsaccess']) echo ' CHECKED'; echo '></td></tr>';
  
  echo '<tr><td colspan=2>&nbsp;</td></tr>';

  echo '<tr><td>Accès Client:</td><td>';
  $clientid = $row['access_clientid']; $dp_nodescription = 1; $noautofocus = 1; require('inc/selectclient.php');
  echo ' &nbsp; <span class="alert">Peut uniquement avoir accès au "Accès Client" et "Options"</span>';
  
  echo '<tr><td colspan=2>&nbsp;</td></tr>';

  $monstart = mb_substr($row['monstart'],0,5); $monstop = mb_substr($row['monstop'],0,5);
  $tuestart = mb_substr($row['tuestart'],0,5); $tuestop = mb_substr($row['tuestop'],0,5);
  $wedstart = mb_substr($row['wedstart'],0,5); $wedstop = mb_substr($row['wedstop'],0,5);
  $thustart = mb_substr($row['thustart'],0,5); $thustop = mb_substr($row['thustop'],0,5);
  $fristart = mb_substr($row['fristart'],0,5); $fristop = mb_substr($row['fristop'],0,5);
  $satstart = mb_substr($row['satstart'],0,5); $satstop = mb_substr($row['satstop'],0,5);
  $sunstart = mb_substr($row['sunstart'],0,5); $sunstop = mb_substr($row['sunstop'],0,5);
  echo '<tr><td>' . d_trad('dayofweek1:') .'</td><td><input type=time name=monstart value="' . $monstart . '" size=5> à <input type=time name=monstop value="' . $monstop . '" size=5></td></tr>';
  echo '<tr><td>' . d_trad('dayofweek2:') .'</td><td><input type=time name=tuestart value="' . $tuestart . '" size=5> à <input type=time name=tuestop value="' . $tuestop . '" size=5></td></tr>';
  echo '<tr><td>' . d_trad('dayofweek3:') .'</td><td><input type=time name=wedstart value="' . $wedstart . '" size=5> à <input type=time name=wedstop value="' . $wedstop . '" size=5></td></tr>';
  echo '<tr><td>' . d_trad('dayofweek4:') .'</td><td><input type=time name=thustart value="' . $thustart . '" size=5> à <input type=time name=thustop value="' . $thustop . '" size=5></td></tr>';
  echo '<tr><td>' . d_trad('dayofweek5:') .'</td><td><input type=time name=fristart value="' . $fristart . '" size=5> à <input type=time name=fristop value="' . $fristop . '" size=5></td></tr>';
  echo '<tr><td>' . d_trad('dayofweek6:') .'</td><td><input type=time name=satstart value="' . $satstart . '" size=5> à <input type=time name=satstop value="' . $satstop . '" size=5></td></tr>';
  echo '<tr><td>' . d_trad('dayofweek7:') .'</td><td><input type=time name=sunstart value="' . $sunstart . '" size=5> à <input type=time name=sunstop value="' . $sunstop . '" size=5></td></tr>';
  
  echo '<tr><td colspan=2>&nbsp;</td></tr>';

  echo '<tr><td>' . d_trad('maxattempts:') .'</td><td><input type="value" name="maxattempts" value="' . $row['maxattempts'] . '" size=5></td></tr>';
  echo '<tr><td>' . d_trad('failedattempts:') .'</td><td><input type="value" name="attempts" value="' . $row['attempts'] . '" size=5></td></tr>';
  
  echo '<tr><td colspan=2>&nbsp;</td></tr>';
  
  echo '<tr><td>' . d_trad('generatenewpwd:') .'</td><td><input type="checkbox" name="newpasswd" value="1"></td></tr>';

  echo '<tr><td colspan=2>&nbsp;</td></tr>';
  
  echo '<tr><td>' . d_trad('deleteuser:') .'</td><td><input type="checkbox" name="deleted" value="1"'; if ($row['deleted'] == 1) echo ' CHECKED'; echo '></td></tr>';

  ?>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="2"><input type=hidden name="systemmenu" value="<?php echo $systemmenu; ?>">
  <?php echo '<input type=hidden name="userid" value="' . $_POST['userid'] . '"><input type=hidden name="saveme" value=1>'; ?>
  <input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
  </table></form><?php
}



if ($userid < 1)
{
  echo '<h2>' . d_trad('modifyuser') . '</h2>'; ?>
  <form method="post" action="system.php"><table>
  <tr><td><?php echo d_trad('namelogin:'); ?></td>
  <td><select name="userid"><?php
  $query = 'select userid,username,deleted from usertable order by deleted,username';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    echo '<option value="' . $query_result[$i]['userid'] . '">' . $query_result[$i]['username'];
    if ($query_result[$i]['deleted'] == 1) { echo ' [Supprimé]'; }
    echo '</option>';
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="systemmenu" value="<?php echo $systemmenu; ?>">
  <input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
  </table></form><?php
}
?>