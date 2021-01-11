<?php

if (isset($_POST['go']) && $_POST['go'] == 1)
{
  if (is_uploaded_file($_FILES['imagefile']['tmp_name']))
  {
    $image = file_get_contents($_FILES['imagefile']['tmp_name']);
    if ($image)
    {
      $fp = fopen('custom_available/'.$dauphin_instancename.'_backgroundimage'.$_SESSION['ds_userid'], "w");
      fwrite($fp, $image);
      fclose($fp);
      $query = 'update usertable set style_image_id=1 where userid=?';
      $query_prm = array($_SESSION['ds_userid']);
      require('inc/doquery.php');
      $_SESSION['ds_style_image_id'] = 1;
    }
  }
  else
  {
     $query = 'update usertable set style_image_id=0 where userid=?';
     $query_prm = array($_SESSION['ds_userid']);
     require('inc/doquery.php');
     $_SESSION['ds_style_image_id'] = 0;
  }
  header("refresh:0;url=options.php?optionsmenu=$optionsmenu");
}

echo '<h2>Image fond d\'écran</h2>';
echo '<form enctype="multipart/form-data" method="post" action="options.php"><table>';
echo '<tr><td>Image: <input name="imagefile" type="file" size=50></td></tr>';
echo '<tr><td align="center"><input type=hidden name="go" value=1>';
echo '<input type=hidden name="optionsmenu" value="' . $optionsmenu . '"><input type="submit" value="Valider"></td></tr>';
echo '</table></form>';

?>