<?php

if ($_SESSION['ds_userid'] < 1) { exit; }

require ('inc/standard.php');
require ('inc/top.php');

$PA['hiddenSigData'] = '';
$PA['invoiceid'] = 'uint';
require('inc/readpost.php');

if ($invoiceid > 0 && $hiddenSigData != '')
{
  #see https://stackoverflow.com/questions/41486345/jsignature-jquery-plugin-make-an-image-from-signature-stored-in-db-base30
  require_once ('jq/jSignature_Tools_Base30.php');
  function base30_to_png($base30_string)
  {
    $data = str_replace ( 'image/jsignature;base30,', '', $base30_string );
    $converter = new jSignature_Tools_Base30 ();
    $raw = $converter->Base64ToNative ( $data );
    // Calculate dimensions
    $width = 0;
    $height = 0;
    foreach ( $raw as $line ) {
        if (max ( $line ['x'] ) > $width)
            $width = max ( $line ['x'] );
        if (max ( $line ['y'] ) > $height)
            $height = max ( $line ['y'] );
    }

    // Create an image
    $im = imagecreatetruecolor ( $width + 20, $height + 20 );

    // Save transparency for PNG
    imagesavealpha ( $im, true );
    // Fill background with transparency
    $trans_colour = imagecolorallocatealpha ( $im, 255, 255, 255, 127 );
    imagefill ( $im, 0, 0, $trans_colour );
    // Set pen thickness
    imagesetthickness ( $im, 2 );
    // Set pen color to black
    $black = imagecolorallocate ( $im, 0, 0, 0 );
    // Loop through array pairs from each signature word
    for($i = 0; $i < count ( $raw ); $i ++) {
        // Loop through each pair in a word
        for($j = 0; $j < count ( $raw [$i] ['x'] ); $j ++) {
            // Make sure we are not on the last coordinate in the array
            if (! isset ( $raw [$i] ['x'] [$j] ))
                break;
            if (! isset ( $raw [$i] ['x'] [$j + 1] ))
                // Draw the dot for the coordinate
                imagesetpixel ( $im, $raw [$i] ['x'] [$j], $raw [$i] ['y'] [$j], $black );
            else
                // Draw the line for the coordinate pair
                imageline ( $im, $raw [$i] ['x'] [$j], $raw [$i] ['y'] [$j], $raw [$i] ['x'] [$j + 1], $raw [$i] ['y'] [$j + 1], $black );
        }
    }
    
    ob_start();
    imagepng($im);
    $image_content = ob_get_clean();
    return $image_content;
  }

  $query = 'insert into image (sig_invoiceid,image,imagetype) values (?,?,?)';
  $query_prm = array($invoiceid,base30_to_png ($_POST['hiddenSigData']),'png');
  require('inc/doquery.php');

  $link = '/printwindow.php?report=showinvoice&invoiceid='.$invoiceid;
  header("refresh:0; url='.$link.'");
}

require ('inc/bottom.php');

?>