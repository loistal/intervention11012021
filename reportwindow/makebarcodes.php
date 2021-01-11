<?php

# not used?

$codelength = mb_strlen($_POST['ourtext']);
$ourtext = $_POST['ourtext'];
if ($codelength > 50)
{
  $codelength = 50;
  $ourtext = substr($ourtext,0,$codelength);
}
$height = $_POST['height']+0;
$width = $_POST['width']+0;

showtitle('Codes barre');

for ($i=0; $i < 100; $i++)
{
  $ourtextfinal = $ourtext + $i;
  $ourtextfinal = str_pad($ourtextfinal, $codelength, "0", STR_PAD_LEFT);
  $ourtype = 'code128';
  echo '<img src="showbarcode.php?barcode=' . $ourtextfinal . '&ourtype=' . $ourtype . '&height=' . $height . '&width=' . $width . '" alt="Code bar ' . $ourtype . ' invalide."><br><br>';
}
  
?>