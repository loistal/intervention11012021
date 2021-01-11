<?php
if ($_POST['bankaccountid'] < 1)
{
  echo '<i>Erreur de banque depot</i>';
  exit;
}
else
{
  $query = 'SELECT bankaccountname, fullbankname, bankaccount.bankid
            FROM bank,bankaccount
            WHERE bankaccount.bankid = bank.bankid AND bankaccountid = ?';

  $query_prm = array();
  $query_prm[] = $_POST['bankaccountid'];
  require('inc/doquery.php');

  $row = $query_result[0];

  $account = $row['bankaccountname'];
  $fullbankname = $row['fullbankname'];
  $depositbankid = $row['bankid'];
}

require('preload/employee.php');

$query = 'SELECT beneficiary FROM globalvariables WHERE primaryunique = 1';

$query_prm = array();
require('inc/doquery.php');

$row = $query_result[0];

$beneficiary = $row['beneficiary'];
$overwrite = $_POST['overwrite'];

$datename = "startdate";
require('inc/datepickerresult.php');

$datename = "stopdate";
require('inc/datepickerresult.php');

?>

<title>Remise de Cheques</title>
</head>

<body style="margin: 20px !important;">
<center>
  <h2>Remise de cheques</h2>
  <?php if ($fullbankname != ""): ?>
  <?php print $fullbankname . ' - '; ?>
<?php endif; ?>

  <?php print datefix($_SESSION['ds_curdate']); ?></h2>

  <h3>Bénéficiaire: <?php print $beneficiary; ?> </h3>

  <h3>N
    <superscript>o</superscript>
    compte: <?php print $account; ?></h3>
</center>

<?php
$query = 'SELECT reimbursement ,paymentid, bankname, chequeno, payer, value, depositbankid, employeeid
          FROM payment,bank
          WHERE payment.bankid = bank.bankid and paymenttypeid = 2';

$query_prm = array();

if ($_POST['bankid'] != '0')
{
  $query .= ' AND payment.bankid = ?';
  $query_prm[] = $_POST['bankid'];
}

$query .= ' AND paymentdate >= ? and paymentdate <= ?';
$query_prm[] = $startdate;
$query_prm[] = $stopdate;

if ($_POST['time'] != 'D')
{
  if ($_POST['time'] == 'M')
  {
    $query = $query . ' AND paymenttime <= "12:00:00"';
  }

  if ($_POST['time'] == 'A')
  {
    $query = $query . ' AND paymenttime > "12:00:00"';
  }
}

if ($_POST['userid'] != '0')
{
  $query = $query . ' AND userid = ?';
  $query_prm[] = $_POST['userid'];
}

if ($_POST['employeeid'] >= 0)
{
  $query = $query . ' and payment.employeeid = ?';
  $query_prm[] = $_POST['employeeid'];
}

if ($overwrite == 0)
{
  $query = $query . ' AND payment.depositbankid = 0';
}

$query .= ' ORDER BY bankname, chequeno';

require('inc/doquery.php');

$bankname[0] = "NOTHINGHERE";
$chequeno[0] = "NOTHINGHERE";
$total = 0;

$informationsTable = '';

for ($i = 0; $i < $num_results; $i++)
{
  $row = $query_result[$i];

  $bankname[$i] = $row['bankname'];
  $chequeno[$i] = $row['chequeno'];
  $payer[$i] = $row['payer'];
  $employeeid[$i] = $row['employeeid'];
  $value[$i] = $row['value'];

  if ($row['reimbursement'] == 1)
  {
    $value[$i] = 0 - $row['value'];
  }

  $total = $total + $value[$i];

  if ($bankname[$i] == $bankname[$i - 1] && $chequeno[$i] == $chequeno[$i - 1])
  {
    $bankname[$i - 1] = "SKIPME";
    $value[$i] = $value[$i] + $value[$i - 1];
  }
}

$counter = 0;

for ($i = 0; $i < $num_results; $i++)
{
  if ($bankname[$i] != "SKIPME" && $value > 0)
  {
    $counter++;
    $informationsTable .= '<tr><td>' . $counter . '</td><td>' . $bankname[$i] . '</td><td>' . $chequeno[$i] . '</td><td>' . $payer[$i] . '</td><td>' . $employeeA[$employeeid[$i]] . '</td><td align=right>' . myfix($value[$i]) . '</td></tr>';
  }
}

$informationsTable .= '<tr><td colspan=5><b>Total:</b></td><td align=right><b>' . myfix($total) . '</b></td></tr>';
?>

<table class="report">
  <tr>
    <td></td>
    <td><b>Banque</b></td>
    <td><b>N
        <superscript>o</superscript>
        de cheque</b></td>
    <td><b>Tireur</b></td>
    <td><b>Employé</b></td>
    <td><b>Montant</b></td>
  </tr>
  <?php print $informationsTable; ?>
</table>

<?php
if ($_POST['userid'] != "0")
{
  $query = 'SELECT name FROM usertable WHERE userid= ?';
  $query_prm = array();
  $query_prm[] = $_POST['userid'];

  require('inc/doquery.php');
  $row = $query_result[0];

  $name = $row['name'] . ', ';
}
else
{
  $name = '';
}
?>

<br>
N <superscript>o</superscript>: <?php print date('ymdHis'); ?>

<br>
Caisse de: <?php print $name; ?>

<?php if ($_POST['time'] == 'M'): ?>
  Matin
<?php endif; ?>

<?php if ($_POST['time'] == 'A'): ?>
  Après-midi
<?php endif; ?>

<?php print datefix($startdate); ?>

<?php if ($stopdate != $startdate): ?>
  à  <?php print datefix($stopdate); ?>
<?php endif; ?>

<?php
$query = 'SELECT name FROM usertable WHERE userid = ?';
$query_prm = array();
$query_prm[] = $_SESSION['ds_userid'];
require('inc/doquery.php');

$row = $query_result[0];
?>

<br>
<br>
Nom de deposant: <?php print $row['name']; ?>

<?php
$query = 'INSERT INTO log_chequebank(userid, bankaccountid, startdate, stopdate, reporttime, chequebankid, chequeuserid, overwrite, logdate, logtime)
          VALUES (?,?,?,?,?,?,?,?,curdate(),curtime())';

$query_prm = array();

$query_prm = array(
  $_SESSION['ds_userid'],
  $_POST['bankaccountid'],
  $startdate,
  $stopdate,
  $_POST['time'],
  $_POST['bankid'],
  $_POST['userid'],
  $overwrite
);

require('inc/doquery.php');

$query = 'UPDATE payment
          SET depositbankid = ?, depositdate = curdate()
          WHERE paymenttypeid = 2';

$query_prm = array();
$query_prm[] = $depositbankid;

if ($_POST['bankid'] != '0')
{
  $query .= ' AND payment.bankid = ?';
  $query_prm[] = $_POST['bankid'];
}

$query = $query . ' AND paymentdate >= ? AND paymentdate <= ?';
$query_prm[] = $startdate;
$query_prm[] = $stopdate;

if ($_POST['time'] != 'D')
{
  if ($_POST['time'] == 'M')
  {
    $query .= ' AND paymenttime <= "12:00:00"';
  }
  if ($_POST['time'] == 'A')
  {
    $query .= ' AND paymenttime > "12:00:00"';
  }
}

if ($_POST['userid'] != '0')
{
  $query .= ' AND userid = ?';
  $query_prm[] = $_POST['userid'];
}

if ($overwrite != 2)
{
  $query = $query . ' AND payment.depositbankid = 0';
}

require('inc/doquery.php');
?>