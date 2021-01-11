<?php
function d_output($ourstring, $allowtags = FALSE)
{
  $ourstring = htmlspecialchars($ourstring, ENT_NOQUOTES, "UTF-8", FALSE);
  if ($allowtags)
  {
    $ourstring = str_replace('&lt;br&gt;', '<br>', $ourstring);
    $ourstring = str_replace('&lt;b&gt;', '<b>', $ourstring);
    $ourstring = str_replace('&lt;/b&gt;', '</b>', $ourstring);
    $ourstring = str_replace('&lt;p&gt;', '<p>', $ourstring);
    $ourstring = str_replace('&lt;/p&gt;', '</p>', $ourstring);
    $ourstring = str_replace('&lt;h1&gt;', '<h1>', $ourstring);
    $ourstring = str_replace('&lt;/h1&gt;', '</h1>', $ourstring);
    $ourstring = str_replace('&lt;h2&gt;', '<h2>', $ourstring);
    $ourstring = str_replace('&lt;/h2&gt;', '</h2>', $ourstring);
    $ourstring = str_replace('&lt;h3&gt;', '<h3>', $ourstring);
    $ourstring = str_replace('&lt;/h3&gt;', '</h3>', $ourstring);
    $ourstring = str_replace('&lt;ul&gt;', '<ul>', $ourstring);
    $ourstring = str_replace('&lt;/ul&gt;', '</ul>', $ourstring);
    $ourstring = str_replace('&lt;ol&gt;', '<ol>', $ourstring);
    $ourstring = str_replace('&lt;/ol&gt;', '</ol>', $ourstring);
    $ourstring = str_replace('&lt;li&gt;', '<li>', $ourstring);
    $ourstring = str_replace('&lt;/li&gt;', '</li>', $ourstring);
    $ourstring = str_replace('&lt;strong&gt;', '<strong>', $ourstring);
    $ourstring = str_replace('&lt;/strong&gt;', '</strong>', $ourstring);
  }
  return $ourstring;
}

function myround($number, $precision = 0)
{
  if ($number < 0)
  {
    $roundvalue = '-0.';
  }
  else
  {
    $roundvalue = '0.';
  }
  if ($precision > 0)
  {
    if ($precision > 6)
    {
      $precision = 6;
    }
    for ($i = 0; $i < $precision; $i++)
    {
      $roundvalue = $roundvalue . '0';
    }
  }
  $roundvalue = $roundvalue . '5';
  return bcadd($number, $roundvalue, $precision);
}

function myfix($number, $precision = 0)
{
  if ($number == 0)
  {
    $number = '&nbsp;';
  }
  else
  {
    $number = myround($number, $precision);
    $number = number_format($number, $precision, ',', $_SESSION['ds_decimalmark']);
  }
  return $number;
}

function multi_br($number)
{
  for ($i = 0; $i < $number; $i++)
  {
    echo '<br>';
  }
}

function multi_br_variable($number)
{
  $multi_br = '';
  for ($i = 0; $i < $number; $i++)
  {
    $multi_br .= '<br>';
  }

  return $multi_br;
}

?>

<?php
if ($_SESSION['ds_customname'] != 'ANIMALICE')
{
?>

<!DOCTYPE html>
<head>
  <title> Print check </title>
  <style>
    * {
      -webkit-box-sizing: border-box;
      -moz-box-sizing: border-box;
    }

    @page {
      size: auto;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: monospace, sans-serif;
      font-size: 16pt;
      padding: 0;

      float: left;
      margin: 280px 0 0 15px;
    }

    .text {
      font-family: monospace, sans-serif;
      font-size: 14pt;
      font-weight: bold;
    }

    .text2 {
      font-family: monospace, sans-serif;
      font-size: 11pt;
      font-weight: bold;
    }

    .text3 {
      font-family: monospace, sans-serif;
      font-size: 9pt;
      font-weight: bold;
    }

    .pricecheckletter {
      width: 430px;
      font-family: sans-serif;
      display: inline-block;
      margin: 0;
      padding: 0;
      padding-left: 5px;
      float: right;
      line-height: 2px;
      margin-left: 3px;
      margin-top: 110px;


      /* Safari */
      -webkit-transform: rotate(-180deg);

      /* Firefox */
      -moz-transform: rotate(-180deg);

      /* IE */
      -ms-transform: rotate(-180deg);

      /* Opera */
      -o-transform: rotate(-180deg);

      /* Internet Explorer */
      filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
    }

    .pricechecknumber {
      display: inline-block;
      margin: 0;
      padding: 0;
      max-width: 180px;
      float: right;
      line-height: 2px;

      /* Safari */
      -webkit-transform: rotate(-180deg);

      /* Firefox */
      -moz-transform: rotate(-180deg);

      /* IE */
      -ms-transform: rotate(-180deg);

      /* Opera */
      -o-transform: rotate(-180deg);

      /* Internet Explorer */
      filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
    }

    .signature {
      float: right;
      max-width: 300px;
      text-align: center;
      display: inline-block;
      margin-top: 30px;

      /* Safari */
      -webkit-transform: rotate(-180deg);

      /* Firefox */
      -moz-transform: rotate(-180deg);

      /* IE */
      -ms-transform: rotate(-180deg);

      /* Opera */
      -o-transform: rotate(-180deg);

      /* Internet Explorer */
      filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
    }
  </style>
</head>
<body>
<?php
}
?>
<?php
#Retrieves parameters for check
$company_name = $_SESSION['ds_customname'];
$amount = (int) $_GET['amount'];

require('inc/fulltextcurrency_func.php');
$letter_amount = convertir($amount);
$letter_amount1 = '';
$letter_amount2 = '';

#Set letter amount, company name and town to uppercase
$letter_amount = strtoupper($letter_amount);

$len_letter_amount = strlen($letter_amount);

$company_name = strtoupper($company_name);
$town = strtoupper('Papeete');

#We cut the amount at 49 carac
if ($len_letter_amount <= 65)
{
  $letter_amount1 = substr($letter_amount, 0, 35);

  #Find last occurence of " " in letter_amount_1
  $pos = strrpos($letter_amount1, " ");

  $letter_amount1 = substr($letter_amount, 0, $pos);
  $letter_amount2 = substr($letter_amount, ($pos + 1));

  $letter_amount1 = '<span class="text">&nbsp;' . $letter_amount1 . '</span>';
  $letter_amount2 = '<span class="text">&nbsp;&nbsp;&nbsp;' . $letter_amount2 . '</span>';
}

if ($len_letter_amount > 65 && $len_letter_amount <= 88)
{
  $letter_amount1 = substr($letter_amount, 0, 45);

  #Find last occurence of " " in letter_amount_1
  $pos = strrpos($letter_amount1, " ");

  $letter_amount1 = substr($letter_amount, 0, $pos);
  $letter_amount2 = substr($letter_amount, ($pos + 1));

  $letter_amount1 = '<span class="text2">&nbsp;&nbsp;' . $letter_amount1 . '</span>';
  $letter_amount2 = '<span class="text2">&nbsp;&nbsp;&nbsp;&nbsp;' . $letter_amount2 . '</span>';
}

if ($len_letter_amount > 88)
{
  $letter_amount1 = substr($letter_amount, 0, 50);

  #Find last occurence of " " in letter_amount_1
  $pos = strrpos($letter_amount1, " ");

  $letter_amount1 = substr($letter_amount, 0, $pos);
  $letter_amount2 = substr($letter_amount, ($pos + 1));

  $letter_amount1 = '<span class="text3">&nbsp;&nbsp;' . $letter_amount1 . '</span>';
  $letter_amount2 = '<span class="text3">&nbsp;&nbsp;&nbsp;&nbsp;' . $letter_amount2 . '</span>';
}

#Fix amount
$nb_carac = mb_strlen($amount);

$len = 11 - strlen($amount);

$day = mb_substr($_SESSION['ds_curdate'], 8, 2) + 0;
$month = mb_substr($_SESSION['ds_curdate'], 5, 2) + 0;
$year = mb_substr($_SESSION['ds_curdate'], 2, 4) + 0;

$showdate = $day . '/' . $month . '/' . $year;
if ($month < 10)
{
  $showdate = $day . '/0' . $month . '/' . $year;
}

if ($_SESSION['ds_customname'] == 'ANIMALICE')
{
  ?>
  <!DOCTYPE html>
<head>
  <title> Print check </title>
  <style>
  @page {
    size: auto;
    margin: 0;
    padding: 0;
  }

  body {
    font-family: monospace, sans-serif;
    font-size: 12pt;
    padding: 0;
    float: left;
  }
  
  div.la1 {
    position: absolute;
    top: 63px;
    left: 140px;
  }
  div.la2 {
    position: absolute;
    top: 87px;
    left: 60px;
  }
  div.cn {
    position: absolute;
    top: 107px;
    left: 60px;
  }
  div.am {
    position: absolute;
    top: 110px;
    left: 550px;
    font-size: 14pt;
  }
  div.to {
    position: absolute;
    top: 150px;
    left: 550px;
    font-size: 10pt;
  }
  div.sd {
    position: absolute;
    top: 165px;
    left: 550px;
    font-size: 10pt;
  }
  </style>
  </head>
  <body>
  <?php
  echo '<div class="la1">'.$letter_amount1.'</div>';
  echo '<div class="la2">'.$letter_amount2.'</div>';
  echo '<div class="cn">'.$company_name.'</div>';
  echo '<div class="am">'.$amount.'</div>';
  echo '<div class="to">'.$town.'</div>';
  echo '<div class="sd">'.$showdate.'</div>';
}
else
{

if ($_SESSION['ds_customname'] == 'Wing Chong')
{
  ?>
  <div class="signature">
    Wing Chong  <br>
    BT 20248001000 <br>
    BP 10350101016
  </div>
  <?php
}
?>

<div class="pricecheckletter">
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

  <?php echo $letter_amount1; ?>

  </span>

  <?php multi_br(25); ?>

  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

  <?php echo $letter_amount2; ?>

  </span>

  <?php multi_br(24); ?>

  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

  <?php print '&nbsp;' . $company_name; ?>
</div>

<div class="pricechecknumber">
  <?php multi_br(120); ?>

  <?php
  for ($i = 0; $i < $len; $i++)
  {
    echo '&nbsp;';
  }
  ?>

  <?php print myfix($amount);
  
  if ($_SESSION['ds_customname'] == 'ANIMALICE')
  {
    echo '&nbsp;';
    # 2018 07 04 new correction, huge left margin on printer? was 280px float     pricecheckletter was 430px    pricechecknumber was right
    echo '<style>

    body {
      float: left;
      margin: 15px 0 0 15px;
    }
    
    .pricecheckletter {
      width: 1130px;
    }
    
    .pricechecknumber {
      display: inline-block;
      margin: 0;
      padding: 0;
      max-width: 180px;
      float: left;
      line-height: 2px;
    }
    </style>';
  }
  ?>

  <?php multi_br(37); ?>

  &nbsp; &nbsp;

  <?php echo $town; ?>

  <?php multi_br(23); ?>

  &nbsp;&nbsp;&nbsp;

  <?php
  print $showdate;
  ?>
</div>

</body>
</html>
<?php
}
?>
