<style>
@page 
{ 
  size: A4; 
  margin: 2.5cm;
}
.barcode_title
{
  font-family: Liberation Sans;
  font-size: 100;
  font-weight: bold;
}  
</style>
<form enctype="multipart/form-data" method="post" action="importbarcode_previewcatalogue.php" target="_blank">
<table>
<tr><td>File catalogue:</td><td><input type="hidden" name="MAX_FILE_SIZE" value="1000000"><input type="file" name="userfile" size=50></td></tr>
<tr><td colspan="2" align="left"><input type=hidden name="importme" value="1"><input type=hidden name="systemmenu" value="<?php echo $systemmenu; ?>"><input type="submit" value="Import"></form></td></tr></table><?php
