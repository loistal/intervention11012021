<?php

# Build web page

$reportwindow = 1;
require ('inc/top.php');

$PA['report'] = '';
require('inc/readpost.php');

switch($report)
{
  case 'deliverylist':
  require('fenua ac cleaner_deliverylist.php');
  break;
  

  default:

  break;
}

require ('inc/bottom.php');

?>


