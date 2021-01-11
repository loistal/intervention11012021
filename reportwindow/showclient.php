<?php

# need refactor

$client = $_POST['client'];

if (!isset($client))
{
  $client = $_GET['client'];
}

require('inc/findclient.php');

if ($clientid < 1)
{
  echo '<form method="post" action="printwindow.php"><table><tr><td>';
  require('inc/selectclient.php');
  echo '</td></tr><tr><td colspan="2" align="center"><input type=hidden name="step" value="1">';
  echo '<input type=hidden name="ficheinfos" value="' . $_POST['ficheinfos'] . '">';
  echo '<input type=hidden name="report" value="showclient"><input type="submit" value="Valider"></td></tr></table></form>';
}
else
{
require('preload/employee.php');
require('preload/clientcategory.php');
require('preload/clientcategory2.php');
require('preload/clientcategory3.php');
require('preload/country.php');

$thisyear = mb_substr($_SESSION['ds_curdate'], 0, 4);
$lastyear = $thisyear - 1;
$fiveyearsago = $thisyear - 4;

$explain_points = (int) $_SESSION['ds_use_loyalty_points'];
$explain_points_text = '';

if ($_POST['ficheinfos'] < 2)
{
  $ficheinfos = '10 derniers';
}

if ($_POST['ficheinfos'] == 2)
{
  $ficheinfos = $thisyear;
}

if ($_POST['ficheinfos'] == 3)
{
  $ficheinfos = $lastyear;
}

if ($_POST['ficheinfos'] == 4)
{
  $ficheinfos = '5 ans';
}

$query = 'SELECT loyalty_start,use_loyalty_points,loyaltydate,clientcomment, clienthistory, deleted, clienthistory ,employeeid
, employeeid2, clientname, companytypename, address, quarter, postalcode, postaladdress, townname, telephone
, cellphone, fax, email, titu, domi, codebanque, guichet, account, clerib,clientcategoryid,clientcategory2id,clientcategory3id
, clienttermname, employeeid, blocked,clientfield1,clientfield2,clientfield3,clientfield4,clientfield5
,clientfield6,countryid,telephone3,telephone4,email2,email3,email4,town_name
FROM client, town, clientterm
WHERE client.townid = town.townid AND client.clienttermid=clientterm.clienttermid AND clientid = ?';
$query_prm = array($clientid);
require('inc/doquery.php');
$clientfield1 = $query_result[0]['clientfield1'];
$clientfield2 = $query_result[0]['clientfield2'];
$clientfield3 = $query_result[0]['clientfield3'];
$clientfield4 = $query_result[0]['clientfield4'];
$clientfield5 = $query_result[0]['clientfield5'];
$clientfield6 = $query_result[0]['clientfield6'];
$row = $query_result[0];
if ($row['countryid'] != 140) { $row['townname'] = $row['town_name']; }

$clientname = d_output(d_decode($row['clientname']));
$title = $clientname . ' (' . $clientid . ') - ' . datefix2($_SESSION['ds_curdate']);
showtitle($title);

$query = 'select invoiceid from invoicehistory where clientid=? and matchingid=0 and confirmed=1 and cancelledid=0 and isreturn=0
and paybydate<(DATE_SUB(?, INTERVAL 1 MONTH))
limit 1';
$query_prm = array($clientid, $_SESSION['ds_curdate']);
require('inc/doquery.php');
if ($num_results) { $clientname = '<span class="alert">'.$clientname.'</span>'; }
else
{
  $query = 'select invoiceid from invoicehistory where clientid=? and matchingid=0 and confirmed=1 and cancelledid=0 and isreturn=0
  and paybydate<?
  limit 1';
  $query_prm = array($clientid, $_SESSION['ds_curdate']);
  require('inc/doquery.php');
  if ($num_results) { $clientname = '<span style="color: orange">'.$clientname.'</span>'; }
}

$title = $clientname . ' (' . $clientid . ') - ' . datefix2($_SESSION['ds_curdate']);
echo '<h2>' . $title . '</h2>';

if ($row['deleted'] > 0)
{
  echo '<h2 class=alert>Compte fermé</h2>';
}
elseif ($row['blocked'] == 1)
{
  echo '<h2 class=alert>Compte Interdit</h2>';
}
elseif ($row['blocked'] == 2)
{
  echo '<h2 class=alert>Compte Suspendu</h2>';
}

echo '<table class=report style="width: 1200px">';
echo '<tr><td><b>'.$_SESSION['ds_term_clientcategory'].'<td>';
if (isset($clientcategoryA[$row['clientcategoryid']])) { echo $clientcategoryA[$row['clientcategoryid']]; }
echo '<td><b>'.$_SESSION['ds_term_clientcategory2'].'<td>';
if (isset($clientcategory2A[$row['clientcategory2id']])) { echo $clientcategory2A[$row['clientcategory2id']]; }
echo '<td><b>'.$_SESSION['ds_term_clientcategory3'].'<td>';
if (isset($clientcategory3A[$row['clientcategory3id']])) { echo $clientcategory3A[$row['clientcategory3id']]; }

if ($row['employeeid'] > 0 || $row['employeeid2'] > 0)
{
  require('preload/employee.php');

  if ($row['employeeid'] > 0)
  {
    echo '<tr><td><b>Employé ' . $_SESSION['ds_term_clientemployee1'] . '</b></td><td>' . $employeeA[$row['employeeid']] . '</td>';
  }
  else
  {
    echo '<tr><td colspan=2>&nbsp;</td>';
  }

  if ($row['employeeid2'] > 0)
  {
    echo '<td><b>Employé ' . $_SESSION['ds_term_clientemployee2'] . '</b></td><td>' . $employeeA[$row['employeeid2']] . '</td></tr>';
  }
  else
  {
    echo '<td colspan=2>&nbsp;</td></tr>';
  }
}

echo '<tr><td><b>Adresse ligne 1<td>'.d_output($row['address']);
echo '<td><b>Adresse ligne 2<td>'.d_output($row['postaladdress']);
echo '<td><b>Code Postal<td>'.d_output($row['postalcode']).' '.d_output($row['townname']);
echo '<tr><td><b>Pays<td>'.d_output($countryA[$row['countryid']]);
if ($row['quarter'] != '') { echo '<td><b>Adresse géo<td colspan=3>'.d_output($row['quarter']); }

echo '<tr><td><b>'.$_SESSION['ds_term_client_telephone'].'<td>'.d_output($row['telephone']);
echo '<td><b>'.$_SESSION['ds_term_client_cellphone'].'<td>'.d_output($row['cellphone']);
echo '<td><b>'.$_SESSION['ds_term_client_telephone3'].'<td>'.d_output($row['telephone3']);

echo '<tr><td><b>'.$_SESSION['ds_term_client_telephone4'].'<td>'.d_output($row['telephone4']);
if ($row['fax'] != '') { echo '<td><b>Fax<td>'.d_output($row['fax']); }

echo '<tr><td><b>'.$_SESSION['ds_term_client_email'].'<td>'.d_output($row['email']);
echo '<td><b>'.$_SESSION['ds_term_client_email2'].'<td>'.d_output($row['email2']);
echo '<td><b>'.$_SESSION['ds_term_client_email3'].'<td>'.d_output($row['email3']);

echo '<tr><td><b>'.$_SESSION['ds_term_client_email4'].'<td>'.d_output($row['email4']);

$dp_clientid = $clientid;
require('inc/clientbalance.php');
$balance = $dr_balance;
$balancetext = ' (Débit)';

if ($balance < 0)
{
  $balancetext = ' (Crédit)';
}

if ($balance == 0)
{
  $balancetext = '';
}

$balance = d_abs($balance);

echo '<tr><td><b>Compte</b></td><td>' . myfix($balance) . $balancetext . '</td><td><b>Paiement</td><td>' . d_output($row['clienttermname']) . '</td></tr>';

if ($clientfield1 != '' || $clientfield2 != '')
{
  echo '<tr><td valign=top><b>'.$_SESSION['ds_term_clientfield1'].':<td>' . d_output($clientfield1);
  echo '<td valign=top><b>'.$_SESSION['ds_term_clientfield2'].':<td>' . d_output($clientfield2);
}
if ($clientfield3 != '' || $clientfield4 != '')
{
  echo '<tr><td valign=top><b>'.$_SESSION['ds_term_clientfield3'].':<td>' . d_output($clientfield3);
  echo '<td valign=top><b>'.$_SESSION['ds_term_clientfield4'].':<td>' . d_output($clientfield4);
}
if ($clientfield5 != '' || $clientfield6 != '')
{
  echo '<tr><td valign=top><b>'.$_SESSION['ds_term_clientfield5'].':<td>' . d_output($clientfield5);
  echo '<td valign=top><b>'.$_SESSION['ds_term_clientfield6'].':<td>' . d_output($clientfield6);
}

if ($row['clientcomment'] != '')
{
  echo '<tr><td valign=top><b>Commentaires:</td><td colspan=4>' . d_output($row['clientcomment']) . '</td></tr>';
}

if ($row['clienthistory'] != '')
{
  echo '<tr><td valign=top><b>Historique:</td><td colspan=4>' . d_output($row['clienthistory']) . '</td></tr>';
}

### copy from invoicing.php
if ($_SESSION['ds_use_loyalty_points'] && $row['use_loyalty_points']) # $clientid > 0 
{  
  require('preload/taxcode.php');
  $points = $row['loyalty_start'];
  if ($explain_points)
  {
    require('preload/product.php');
    $explain_points_text .= '<tr><td colspan=3><b>Historique des points</b><td align=right>'.$row['loyalty_start'];
  }
  
  $query = 'select givenrebate,linetaxcodeid,lineprice,linevat,isreturn,rebate_type,invoiceitemhistory.invoiceid,invoiceitemhistory.productid,quantity
  from invoiceitemhistory,invoicehistory
  where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
  and clientid=? and cancelledid=0 and confirmed=1 and isreturn=0';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    if ($query_result[$i]['givenrebate'] > 0)
    {
      if ($query_result[$i]['rebate_type'] == 3)
      {
        $kladd = round($query_result[$i]['givenrebate'] + ($query_result[$i]['givenrebate'] * $taxcodeA[$query_result[$i]['linetaxcodeid']] / 100));
        if ($query_result[$i]['isreturn'] == 1)
        {
          $points += $kladd;
          if ($explain_points)
          {
            $explain_points_text .= '<tr><td>Avoir '.$query_result[$i]['invoiceid'];
            $explain_points_text .= '<td align=right>'.$query_result[$i]['quantity'];
            $explain_points_text .= '<td>'.$productA[$query_result[$i]['productid']];
            $explain_points_text .= '<td align=right>'.$kladd;
          }
        }
        else
        {
          $points -= $kladd;
          if ($explain_points)
          {
            $explain_points_text .= '<tr><td>Facture '.$query_result[$i]['invoiceid'];
            $explain_points_text .= '<td align=right>'.$query_result[$i]['quantity'];
            $explain_points_text .= '<td>'.$productA[$query_result[$i]['productid']];
            $explain_points_text .= '<td align=right>- '.$kladd;
          }
        }
      }
    }
    else
    {
      $kladd = round(($query_result[$i]['lineprice'] + $query_result[$i]['linevat']) * $_SESSION['ds_loyalty_points_percent'] / 100);
      if ($query_result[$i]['isreturn'] == 1)
      {
        $points -= $kladd;
        if ($explain_points)
        {
          $explain_points_text .= '<tr><td>Avoir '.$query_result[$i]['invoiceid'];
          $explain_points_text .= '<td align=right>'.$query_result[$i]['quantity'];
          $explain_points_text .= '<td>'.$productA[$query_result[$i]['productid']];
          $explain_points_text .= '<td align=right>- '.$kladd;
        }
      }
      else
      {
        $points += $kladd;
        if ($explain_points)
        {
          $explain_points_text .= '<tr><td>Facture '.$query_result[$i]['invoiceid'];
          $explain_points_text .= '<td align=right>'.$query_result[$i]['quantity'];
          $explain_points_text .= '<td>'.$productA[$query_result[$i]['productid']];
          $explain_points_text .= '<td align=right>'.$kladd;
        }
      }
    }
  }
  #if ($explain_points) { echo '<br>'; }

  $points = round($points);
  
  echo '<tr><td><b>Date Fidelité : <td>',datefix($row['loyaltydate']),'<td><b>Points de fidelité :<td>',myfix($points);
}
###

echo '</table>';

if ($explain_points)
{
  echo '<br><table class=report style="width: 1200px">',$explain_points_text,'<tr><td colspan=3>Point actuels:<td align=right>',$points,'</table>';
}

if ($row['titu'] != "" || $row['account'] != "")
{
  echo '<br><table class="report" style="width: 1200px"><tr><td align=center colspan=4><i>Coordonnées bancaire</td></tr>';
  echo '<tr><td><b>Titulaire</td><td>' . d_output($row['titu']) . '</td><td colspan=2>&nbsp;</td></tr>';
  echo '<tr><td><b>Domiciliation</td><td>' . d_output($row['domi']) . '</td><td colspan=2>&nbsp;</td></tr>';
  echo '<tr><td><b>Code Banque</td><td>' . d_output($row['codebanque']) . '</td><td><b>Code guichet</td><td>' . d_output($row['guichet']) . '</td></tr>';
  echo '<tr><td><b>No Compte</td><td>' . d_output($row['account']) . '</td><td><b>Clé RIB</td><td>' . d_output($row['clerib']) . '</td></tr>';
  echo '</table>';
}

if ($_SESSION['ds_userrepresentsclientid'] == 0)
{
  echo '<br>';
  $calendar_clientid = $clientid;
  require('reportwindow/calendar.php');

  $query = 'SELECT clientactionid,actiondate, name, clientactioncatname, actionname, clientaction.employeeid
            FROM clientaction, usertable, clientactioncat
            WHERE clientaction.userid = usertable.userid AND clientaction.clientactioncatid = clientactioncat.clientactioncatid';

  $query = $query . ' and clientaction.deleted=0 and clientaction.clientid="' . $clientid . '"';

  if ($_SESSION['ds_allowedclientlist'] != '')
  {
    $query = $query . ' and clientaction.clientid in ' . $_SESSION['ds_allowedclientlist'];
  }

  if ($_POST['ficheinfos'] == 2)
  {
    $query = $query . ' and year(actiondate)="' . $thisyear . '"';
  }

  if ($_POST['ficheinfos'] == 3)
  {
    $query = $query . ' and year(actiondate)="' . $lastyear . '"';
  }

  if ($_POST['ficheinfos'] == 4)
  {
    $query = $query . ' and year(actiondate)>="' . $fiveyearsago . '"';
  }

  $query = $query . ' union ';
  $query = $query . 'select clientactionid,actiondate,name,"&nbsp;" as clientactioncatname,actionname,clientaction.employeeid from clientaction,usertable where clientaction.userid=usertable.userid';
  $query = $query . ' and clientaction.deleted=0 and clientaction.clientid="' . $clientid . '"';

  if ($_SESSION['ds_allowedclientlist'] != '')
  {
    $query = $query . ' and clientaction.clientid in ' . $_SESSION['ds_allowedclientlist'];
  }

  $query = $query . ' and clientaction.clientactioncatid=0';

  if ($_POST['ficheinfos'] == 2)
  {
    $query = $query . ' and year(actiondate)="' . $thisyear . '"';
  }

  if ($_POST['ficheinfos'] == 3)
  {
    $query = $query . ' and year(actiondate)="' . $lastyear . '"';
  }

  if ($_POST['ficheinfos'] == 4)
  {
    $query = $query . ' and year(actiondate)>="' . $fiveyearsago . '"';
  }

  $query = $query . ' order by actiondate desc,clientactionid desc';

  if ($_POST['ficheinfos'] < 2)
  {
    $query = $query . ' LIMIT 10';
  }

  $query_prm = array();
  require('inc/doquery.php');

  if ($num_results > 0)
  {
    echo '<br><table class="report" style="width: 1200px"><tr><td align=center colspan=5><b>Évènements (' . $ficheinfos . ')</td></tr>';
    echo '<tr><td><b>Action</td><td><b>Catégorie</td><td><b>Date</td><td><b>Utilisateur</td><td><b>Employé</td></tr>';

    for ($i = 0; $i < $num_results; $i++)
    {
      $row = $query_result[$i];
      $employeename = '&nbsp;';
      if ($row['employeeid'] > 0)
      {
        $employeename = $employeeA[$row['employeeid']];
      }
      echo '<tr><td align=right class="breakme">' . $row['actionname'] . '</td><td align=right>' . $row['clientactioncatname'] . '</td><td>' . datefix2($row['actiondate']) . '</td><td>' . $row['name'] . '</td><td align=right>' . $employeename . '</td></tr>';
    }
    echo '</table>';
  }
}

$query = 'select invoiceid,accountingdate,invoiceprice,name,confirmed,matchingid,cancelledid,proforma,isnotice,isreturn
,invoicegroupid,invoicedate,invoicetime
from invoice,usertable where invoice.userid=usertable.userid and clientid="' . $clientid . '"';
$query = $query . ' and cancelledid !=1';

if ($_SESSION['ds_allowedclientlist'] != '')
{
  $query = $query . ' and invoice.clientid in ' . $_SESSION['ds_allowedclientlist'];
}

if ($_SESSION['ds_confirmonlyown'] == 1)
{
  $queryadd = ' and (invoice.userid="' . $_SESSION['ds_userid'] . '"';

  if ($_SESSION['ds_myemployeeid'] > 0)
  {
    $queryadd .= ' or invoice.employeeid="' . $_SESSION['ds_myemployeeid'] . '"';
  }
  $query .= $queryadd . ')';
}

if ($_POST['ficheinfos'] == 2)
{
  $query = $query . ' and year(accountingdate)="' . $thisyear . '"';
}

if ($_POST['ficheinfos'] == 3)
{
  $query = $query . ' and year(accountingdate)="' . $lastyear . '"';
}

if ($_POST['ficheinfos'] == 4)
{
  $query = $query . ' and year(accountingdate)>="' . $fiveyearsago . '"';
}

$query = $query . ' union ';
$query = $query . 'select invoiceid,accountingdate,invoiceprice,name,confirmed,matchingid,cancelledid,proforma,isnotice,isreturn
,invoicegroupid,invoicedate,invoicetime
from invoicehistory,usertable where invoicehistory.userid=usertable.userid and clientid="' . $clientid . '"';
$query = $query . ' and cancelledid !=1';

if ($_SESSION['ds_allowedclientlist'] != '')
{
  $query = $query . ' and invoicehistory.clientid in ' . $_SESSION['ds_allowedclientlist'];
}

if ($_SESSION['ds_confirmonlyown'] == 1)
{
  $queryadd = ' and (invoicehistory.userid="' . $_SESSION['ds_userid'] . '"';

  if ($_SESSION['ds_myemployeeid'] > 0)
  {
    $queryadd .= ' or invoicehistory.employeeid="' . $_SESSION['ds_myemployeeid'] . '"';
  }

  $query .= $queryadd . ')';
}

if ($_POST['ficheinfos'] == 2)
{
  $query = $query . ' and year(accountingdate)="' . $thisyear . '"';
}

if ($_POST['ficheinfos'] == 3)
{
  $query = $query . ' and year(accountingdate)="' . $lastyear . '"';
}

if ($_POST['ficheinfos'] == 4)
{
  $query = $query . ' and year(accountingdate)>="' . $fiveyearsago . '"';
}

$query = $query . ' order by accountingdate desc, invoicedate desc, invoicetime desc';

if ($_POST['ficheinfos'] < 2)
{
  $query = $query . ' LIMIT 10';
}

$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

if ($num_results_main > 0)
{
  echo '<br><table class="report" style="width: 1200px"><tr><td align=center colspan=5><b>Factures (' . $ficheinfos . ')</td></tr>';
  echo '<tr><td><b>Numéro</td><td><b>Utilisateur</td><td><b>Date</td><td><b>Prix</td><td><b>Statut</td></tr>';
  for ($i = 0; $i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    $status = '&nbsp;';

    if ($row['confirmed'] == 0)
    {
      $status = 'Non confirmée';
    }

    if ($row['confirmed'] == 1)
    {
      $status = 'Confirmée';
    }

    if ($row['matchingid'] > 0)
    {
      $status = 'Lettrée';
    }

    if ($row['cancelledid'] > 0)
    {
      $status = 'Annulée';
    }
    
    if ($row['confirmed'] == 1 && $row['invoicegroupid'] > 0)
    {
      $query = 'select invoicegroupdate,invoicegrouptime from invoicegroup where invoicegroupid=?'; # TODO optimize
      $query_prm = array($row['invoicegroupid']);
      require('inc/doquery.php');
      $status .= '. Préparé le '.datefix($query_result[0]['invoicegroupdate'],'short').' '.substr($query_result[0]['invoicegrouptime'],0,5);
    }

    $showinvoiceid = $row['invoiceid'];

    if ($row['proforma'] == 1)
    {
      $showinvoiceid = '(Proforma) ' . $showinvoiceid;
    }

    if ($row['isnotice'] == 1)
    {
      $showinvoiceid = '(Bon) ' . $showinvoiceid;
    }

    if ($row['isreturn'] == 1)
    {
      $showinvoiceid = '(Avoir) ' . $showinvoiceid;
    }

    echo '<tr><td align=right><a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $row['invoiceid'] . '" target=_blank>' . $showinvoiceid . '</a></td><td>' . $row['name'] . '</td><td>' . datefix2($row['accountingdate']) . '</td><td align=right>' . myfix($row['invoiceprice']) . '</td><td>' . $status . '</td></tr>';

  }
  echo '</table>';
}

$query = 'select bankid,payment.paymentid,paymentdate,name,value,paymentcomment,chequeno from payment,usertable where payment.userid=usertable.userid';
$query = $query . ' and payment.clientid="' . $clientid . '"';

if ($_SESSION['ds_allowedclientlist'] != '')
{
  $query = $query . ' and payment.clientid in ' . $_SESSION['ds_allowedclientlist'];
}

if ($_SESSION['ds_confirmonlyown'] == 1)
{
  $query .= ' and payment.userid="' . $_SESSION['ds_userid'] . '"';
}

if ($_POST['ficheinfos'] == 2)
{
  $query = $query . ' and year(paymentdate)="' . $thisyear . '"';
}

if ($_POST['ficheinfos'] == 3)
{
  $query = $query . ' and year(paymentdate)="' . $lastyear . '"';
}
if ($_POST['ficheinfos'] == 4)
{
  $query = $query . ' and year(paymentdate)>="' . $fiveyearsago . '"';
}

$query = $query . ' order by paymentdate desc';

if ($_POST['ficheinfos'] < 2)
{
  $query = $query . ' LIMIT 10';
}

$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result;
$num_results_main = $num_results;

unset($query_result, $num_results);

if ($num_results_main > 0)
{
  echo '<br><table class="report" style="width: 1200px"><tr><td align=center colspan=6><b>Paiement (' . $ficheinfos . ')</td></tr>';
  echo '<tr><td><b>Numéro</td><td><b>Utilisateur</td><td><b>Date</td><td><b>Montant</td><td><b>N<sup>o</sup> Cheque</td><td><b>Info</td></tr>';

  for ($i = 0; $i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    $chequeno = $row['chequeno'];

    if ($chequeno != '')
    {
      $query = 'select bankname from bank where bankid=?';
      $query_prm = array($row['bankid']);
      require('inc/doquery.php');
      $chequeno = $query_result[0]['bankname'] . ' ' . $chequeno;
    }
    echo '<tr><td align=right>' . $row['paymentid'] . '</td><td align=right>' . $row['name'] . '</td><td>' . datefix2($row['paymentdate']) . '</td><td align=right>' . myfix($row['value']) . '</td><td align=right>' . $chequeno . '</td><td align=right>' . $row['paymentcomment'] . '</td></tr>';
  }
  echo '</table>';
}

if ($_SESSION['ds_customname'] != "" && $_SESSION['ds_clientaccess'] == 0)
{
  $customfilename = mb_strtolower('custom/' . $_SESSION['ds_customname']) . 'clientfile.php';
  if (file_exists($customfilename))
  {
    require($customfilename);
  }
}

$query = 'select imageid,image,imagetext,imageorder,imagetype from image where clientid=? order by imageorder,imageid';
$query_prm = array($clientid);
require('inc/doquery.php');

for ($i = 0; $i < $num_results; $i++)
{
  if ($query_result[$i]['imagetype'] == 'pdf')
  {
    echo '<object type="text/html" codetype="application/pdf" data="viewpdf.php?image_id=' . $query_result[$i]['imageid'] . '" width="100%" height="500px"></object>';
  }
  else
  {
    echo '<img src="viewimage.php?image_id=' . $query_result[$i]['imageid'] . '"><br>';
  }
}
}
?>