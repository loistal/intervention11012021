<?php
require('preload/bank.php');

$PA['paymentid'] = 'uint';
require('inc/readpost.php');

echo '<h2>Paiement numéro ' . $paymentid . '</h2>';
$query = 'select forinvoiceid,payment.clientid,clientname,paymentcomment,initials,paymentdate,paymenttime,value,chequeno,bankid,depositbankid,payer,matchingid,paymenttypename
from payment,client,usertable,paymenttype where payment.paymenttypeid=paymenttype.paymenttypeid
and payment.userid=usertable.userid and payment.clientid=client.clientid and paymentid=?';
$query_prm = array($paymentid);
require('inc/doquery.php');
if ($num_results < 1) { echo 'Non trouvé.'; }
else
{
  $row = $query_result[0];
  echo '<table class="report">';
  echo '<tr><td>Utilisateur:</td><td>' . $row['initials'] . '</td></tr>';
  echo '<tr><td>Date/heure:</td><td>' . datefix($row['paymentdate']) . ' ' . $row['paymenttime'] . '</td></tr>';
  echo '<tr><td>Client:</td><td>' . $row['clientid'] . ' ' . d_decode($row['clientname']) . '</td></tr>';
  echo '<tr><td>Pour facture:</td><td>' . $row['forinvoiceid'] . '</td></tr>';
  echo '<tr><td>Type de paiement:</td><td>' . $row['paymenttypename'] . '</td></tr>';
  echo '<tr><td>Montant:</td><td>' . myfix($row['value']) . '</td></tr>';
  echo '<tr><td>Commentaire:</td><td>' . $row['paymentcomment'] . '</td></tr>';
  echo '<tr><td>No chèque:</td><td>' . $row['chequeno'] . '</td></tr>';
  echo '<tr><td>Banque:</td><td>' . $bankA[$row['bankid']] . '</td></tr>';
  echo '<tr><td>Tireur:</td><td>' . $row['payer'] . '</td></tr>';
  $lettre = 'Non'; if ($row['matchingid'] > 0) { $lettre = 'Oui'; }
  echo '<tr><td>Lettré:</td><td>' . $lettre . '</td></tr>';
  echo '<tr><td>Banque de dépôt:</td><td>' . $bankA[$row['depositbankid']] . '</td></tr>';
  echo '</table>';
}
?>