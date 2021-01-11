<?php

$width = 760;
$height = 1075;
$temlogo_offset = 0;

# TODO firefox messes everything up
#if(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false) { $width -= 30; $temlogo_offset = 30; }

?>
<style type="text/css">
/*********************************************/
/*          Base rules                       */
/*********************************************/
body {
  background-color: <?php echo $_SESSION['ds_tablecolor1']; ?>;
  font-family: <?php echo $_SESSION['ds_user_font_print']; ?>;
}

.logo-tem2 {
  position: absolute;
  bottom: 0;
  right: <?php echo (10+$temlogo_offset); ?>px;
}

div.mainwrap
{
  margin: 0 auto;
  width: <?php echo $width; ?>px;
  height: <?php echo $height; ?>px;
  border: 1px solid #3b495c;
}

div.logowrap
{
  margin: 0 auto;
  width: <?php echo ($width-10); ?>px;
  border: 1px solid #3b495c;
  margin-top: 1px;
}

td.header {
  vertical-align:bottom;
  font-weight: bold;
  font-size: xx-large;
}

span.subheader {
  vertical-align:bottom;
  font-weight: bold;
  font-size: x-large;
}

table.infos {
  margin: 10px;
  width: <?php echo ($width-30); ?>px;
}

td.tdborders {
  border: 2px solid #3b495c;
}

</style>