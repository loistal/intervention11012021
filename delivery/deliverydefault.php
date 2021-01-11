<?php

# TODO explain this module, how to use, link videos

if ($_SESSION['ds_usedelivery'] == 1)
{
  require('prepare.php');
}
else
{
  require('prepare_line.php');
}

?>