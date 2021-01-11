<?php

# TODO remove $_POST (check all callers of this module!)   refactor

### input box for selecting or adding a single client

# internal use: $_POST['addclient']
# input: ($client or $clientid) $canaddclient $noautofocus $onlyaddclient $dp_nochangeclient $dp_addtoid $dp_allowpopup
#        $dp_description $dp_nodescription $dp_colspan $dp_style $dp_supplier
# output: $_POST['client'.$dp_addtoid] $clientid $clientname $num_clients

require('inc/autocomplete_client.php');

$err_clientname_dup_selectclient = false;
$err_clientname_empty_selectclient = false;
$was_added_selectclient = false;

if (isset($dp_style)) { $selectclient_style = ';' . $dp_style; }
else { $selectclient_style = ''; }
if (!isset($client))
{
  if (isset($clientid) && $clientid > 0) { $client = $clientid; }
  else { $client = ''; }
}
elseif (is_int($client) && $client == 0) { $client = ''; }
if (!isset($onlyaddclient)) { $onlyaddclient = 0; }
if (!isset($dp_addtoid)) { $dp_addtoid = ''; }
if (!isset($noautofocus)) { $noautofocus = 0; }
if (!isset($canaddclient)) { $canaddclient = 0; }
if (!isset($dp_nodescription)) {$dp_nodescription = 0;}
if (!isset($dp_description)) {$dp_description = 'Client :';}
if ($dp_nodescription) { $dp_description = ''; }

if (isset($_POST['addclient']) && $_POST['addclient'] == 1)
{
  //check if name already exist
  $clientname_modified = false;
  if($client == '')
  {
    $err_clientname_empty_selectclient = true;
  }
  else
  {
    $query = 'select clientid from client where clientname=?';
    $query_prm = array(d_encode($client));
    require('inc/doquery.php');
    if ($num_results > 0)
    {
      $err_clientname_dup_selectclient = true;
    }
  }

  if($err_clientname_dup_selectclient)
  {
    echo '<p class="alert">Le client ' . d_output($client) . ' existe déjà.</p>';
  }
  else if($err_clientname_empty_selectclient)
  {
    echo '<p class="alert">Le nom du client doit être renseigné.</span></p>';
  }
  else
  {
    $query = 'insert into client (clientname,clientcategoryid,clientcategory2id,clienttermid,vatexempt,blocked,employeeid,usedetail,outstandinglimit,townid,clientsectorid) values (?,'.$_SESSION['ds_defclientcatid'].','.$_SESSION['ds_defclientcat2id'].',1,0,0,0,0,0,1,1)';
    $query_prm = array(d_encode($client));
    require ('inc/doquery.php');
    $client = $query_insert_id;
    if($num_results > 0)
    {
     $was_added_selectclient = true;
    }
  }
}
require ('inc/findclient.php');

if ($onlyaddclient != 1)
{
  if ($num_clients < 1 || $num_clients > 20) #$_SESSION['ds_maxresults']
  {
    if($dp_nodescription == 0)
    {
      if ($dp_description != '')
      {
        echo $dp_description;
        if(isset($dp_colspan)) { echo '<td colspan=' . $dp_colspan . '>';}
        else {echo '<td>';}
      }
    }
    echo '<input';
    if (!$noautofocus) { echo ' autofocus'; }
    if ($client == '' && isset($_POST['client'.$dp_addtoid]) && $_POST['client'.$dp_addtoid] != '')
    {
      echo ' type="text" STYLE="text-align:right;color: ' . $_SESSION['ds_alertcolor'] . $selectclient_style . '" name="client'.$dp_addtoid.'" id="client_autocomplete' . $dp_addtoid . '" autocomplete="off" value="' . d_input($_POST['client'.$dp_addtoid]) . '" size=30>';
    }
    else
    {
      echo ' type="text" STYLE="text-align:right'.$selectclient_style.'" name="client'.$dp_addtoid.'" id="client_autocomplete' . $dp_addtoid . '" autocomplete="off" value="' . d_input($client) . '" size=30>';
    }
    if ($canaddclient == 1)
    {
      echo ' <input type="checkbox" name="addclient" value="1"';
      if (isset($_GET['checkaddclient']) && $_GET['checkaddclient'] == 1) { echo ' checked'; }
      echo '><font size=-1>Ajouter</font>';
    }
    if (isset($_POST['client'.$dp_addtoid]) && !empty($_POST['client'.$dp_addtoid]))
    {
      if ($num_clients < 1 && $dp_addtoid == '')
      {
        $dp_allowdeletedclients = 1;
        require ('inc/findclient.php');
        if ($clientid) { echo ' &nbsp; <span class="alert">Compte ' . $clientid . ' fermé ou interdit.</span>'; }
        elseif ($client != '') { echo ' &nbsp; <span class="alert">Aucun client trouvé.</span>'; }
        unset($clientname,$num_clients);
        $clientid = -1;
      }
      elseif($num_clients > 1) { echo ' &nbsp; <span class="alert">' . $num_clients . ' clients trouvés.</span>'; }
    }
  }
  elseif ($num_clients > 1)
  {
    if ($dp_addtoid == '')
    {
      if ($dp_description != '')
      {
        echo $dp_description;
        if(isset($dp_colspan)) { echo '<td colspan=' . $dp_colspan . '>';}
        else {echo '<td>';}
      }
    }
    echo '<select autofocus name="client'.$dp_addtoid.'">';
    for ($kladd_i=0;$kladd_i<$num_clients;$kladd_i++)
    {
      echo '<option value="' . $query_result[$kladd_i]['clientid'] . '">' . d_output(d_decode($query_result[$kladd_i]['clientname'])) . ' (' . $query_result[$kladd_i]['clientid'] . ')</option>';
    }
    echo '</select>';
    if($num_clients > 1) { echo ' &nbsp; <span class="alert">' . $num_clients . ' clients trouvés.</span>'; }
  }
  else
  {
    if ($dp_addtoid == '')
    {
      if ($dp_description != '')
      {
        echo $dp_description;
        if(isset($dp_colspan)) { echo '<td colspan=' . $dp_colspan . '>';}
        else {echo '<td>';}
      }
    }
    if (!isset($dp_nochangeclient) || !$dp_nochangeclient) { echo '<input type="text" STYLE="text-align:right'.$selectclient_style.'" name="client'.$dp_addtoid.'" id="client_autocomplete' . $dp_addtoid . '" autocomplete="off" value="' . $clientid . '" size=5> :'; }
    else { echo '<input type=hidden name="client'.$dp_addtoid.'" value="' . $clientid . '">'; }
    echo '<a href="reportwindow.php?report=showclient&usedefaultstyle=1&client=' . $clientid . '" target=_blank>' . d_output($clientname);
    if (!isset($dp_nochangeclient) || !$dp_nochangeclient) { echo ' (' . $clientid . ')'; }
    echo '</a>';
    if ($_SESSION['ds_badpayeralert'] && isset($dp_allowpopup) && $dp_allowpopup)
    {
      $dp_clientid = $clientid; $dp_payabledate = 1;
      require('inc/clientbalance.php');
      if ($dr_balance > 0)
      {
        ?><script type='text/javascript'>alert(' <?php echo 'Ce client a ' . str_replace('&nbsp;',' ',myfix($dr_balance)) . ' XPF impayé.'; ?> ');</script><?php
      }
    }
  }
}

unset($client);
unset($canaddclient);
unset($noautofocus);
unset($onlyaddclient);
unset($dp_nochangeclient);
unset($dp_addtoid);
unset($dp_allowpopup);
unset($dp_description, $dp_supplier);
unset($err_clientname_dup_selectclient, $err_clientname_empty_selectclient, $was_added_selectclient);
?>