<?php

/* For loggedin.php page */
if (!isset($_SESSION['ds_menustyle'])) # TODO remove these, should be default table values
{
  if (!isset($_SESSION['ds_user_font']))
  {
    $_SESSION['ds_user_font'] = 'Georgia';
  }
  if (!isset($_SESSION['ds_user_font_size']))
  {
    $_SESSION['ds_user_font_size'] = 'Medium';
  }
  if (!isset($_SESSION['ds_bgcolor']))
  {
    $_SESSION['ds_bgcolor'] = '#ffffff';
  }
  if (!isset($_SESSION['ds_fgcolor']))
  {
    $_SESSION['ds_fgcolor'] = '#000000';
  }
  if (!isset($_SESSION['ds_linkcolor']))
  {
    $_SESSION['ds_linkcolor'] = '#2a8a8f';
  }
  if (!isset($_SESSION['ds_menucolor']))
  {
    $_SESSION['ds_menucolor'] = '#36b0b6';
  }
  if (!isset($_SESSION['ds_alertcolor']))
  {
    $_SESSION['ds_alertcolor'] = 'ff0000';
  }
  if (!isset($_SESSION['ds_infocolor']))
  {
    $_SESSION['ds_infocolor'] = '#a9a9a9';
  }
  if (!isset($_SESSION['ds_formcolor']))
  {
    $_SESSION['ds_formcolor'] = '#f8f8ff';
  }
  if (!isset($_SESSION['ds_tablecolor']))
  {
    $_SESSION['ds_tablecolor'] = '#d5d7df';
  }
  if (!isset($_SESSION['ds_inputcolor']))
  {
    $_SESSION['ds_inputcolor'] = '#ffffff';
  }
  if (!isset($_SESSION['ds_emphasiscolor']))
  {
    $_SESSION['ds_emphasiscolor'] = '#ffffff';
  }
  if (!isset($_SESSION['ds_menubordercolor']))
  {
    $_SESSION['ds_menubordercolor'] = '#133e40';
  }
  if (!isset($_SESSION['ds_menufontcolor']))
  {
    $_SESSION['ds_menufontcolor'] = '#ffffff';
  }
  if (!isset($_SESSION['ds_tablecolor1']))
  {
    $_SESSION['ds_tablecolor1'] = '#9ac1df';
  }
  if (!isset($_SESSION['ds_tablecolor2']))
  {
    $_SESSION['ds_tablecolor2'] = '#9ac1df';
  }
  if (!isset($_SESSION['ds_tablecolorsub']))
  {
    $_SESSION['ds_tablecolorsub'] = '#5384c0';
  }
  if (!isset($_SESSION['ds_hovercolor']))
  {
    $_SESSION['ds_hovercolor'] = '#f8f8ff';
  }
}
?>
<style type="text/css">
  /*********************************************/
  /*        Responsive                         */
  /*********************************************/
  <?php $responsive = 0; ?>

  <?php if($responsive == 1): ?>
  /* Extra Small Devices and Phones */
  @media only screen and (max-width: 480px) {
    #mainprogram {
      width: 100%;
      max-width: 100%;
    }

    #leftmenu {
      width: 100%;
      max-width: 100%;

    }
  }
  <?php else: ?>
  #wrapper {
    min-width: 1200px;
  }
  <?php endif; ?>

  /*********************************************/
  /*          Base rules                       */
  /*********************************************/

  <?php if ($_SESSION['ds_style_image_id'] > 0) { ?>
  body {
    background: url('custom_available/<?php echo $dauphin_instancename ?>_backgroundimage<?php echo $_SESSION['ds_userid'] ?>') no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
  }

  <?php } ?>

  body {
  <?php if ($_SESSION['ds_style_image_id'] == 0) { ?> background-color: <?php echo $_SESSION['ds_bgcolor']; ?>;
  <?php } ?> color: <?php echo $_SESSION['ds_fgcolor']; ?>;
    font-family: <?php echo $_SESSION['ds_user_font']; ?>;
    font-size: <?php echo $_SESSION['ds_user_font_size']; ?>;
  }

  table {
    color: <?php echo $_SESSION['ds_fgcolor']; ?>;
  }

  table.report {
    background-color: <?php echo $_SESSION['ds_tablecolor']; ?>;
  }

  <?php if ($_SESSION['ds_style_image_id'] == 0) { ?>
  table {
    background-color: <?php echo $_SESSION['ds_formcolor']; ?>;
    color: <?php echo $_SESSION['ds_fgcolor']; ?>;
  }

  table.transparent {
    background-color: <?php echo $_SESSION['ds_bgcolor']; ?>;
  }

  <?php } ?>

  <?php
  if ($_SESSION['ds_usehovercolor'] == 2)
  {
  ?>
  table tr:hover, thead:hover td {
    background-color: <?php echo $_SESSION['ds_hovercolor']; ?>;
  }

  <?php
  } elseif($_SESSION['ds_usehovercolor'] == 1) { ?>
  .report tr:hover, thead:hover td {
    background-color: <?php echo $_SESSION['ds_hovercolor']; ?>;
  }

  <?php
  }
  ?>

  input, select, button {
    color: <?php echo $_SESSION['ds_fgcolor']; ?>;
    background-color: <?php echo $_SESSION['ds_inputcolor']; ?>;
    border: 1px solid <?php echo $_SESSION['ds_fgcolor']; ?>;
  }

  input:focus, select:focus {
    outline-color: <?php echo $_SESSION['ds_alertcolor']; ?>;
    border: 1px solid <?php echo $_SESSION['ds_alertcolor']; ?>;
  }

  form {
    border: 1px solid <?php echo $_SESSION['ds_menubordercolor']; ?>;
    background-color: <?php echo $_SESSION['ds_formcolor']; ?>;
    display: inline-block;    
  }

  form.loginbox {
    background-color: <?php echo $_SESSION['ds_bgcolor']; ?>;
  }

  form.mainlogin {
    background-color: <?php echo $_SESSION['ds_formcolor']; ?>;
  }

  fieldset.loginbox {
    border: 1px solid <?php echo $_SESSION['ds_fgcolor']; ?>;
    background-color: <?php echo $_SESSION['ds_formcolor']; ?>;
  }

  input, select, button {
    font-family: <?php echo $_SESSION['ds_user_font']; ?>;
    font-size: <?php echo $_SESSION['ds_user_font_size']; ?>;
  }

  input.readonly {
    color: <?php echo $_SESSION['ds_fgcolor']; ?>;
  }

  textarea {
    border: 1px solid <?php echo $_SESSION['ds_fgcolor']; ?>;
    background-color: <?php echo $_SESSION['ds_inputcolor']; ?>;
    color: <?php echo $_SESSION['ds_fgcolor']; ?>
  }

  a:link {
    color: <?php echo $_SESSION['ds_linkcolor']; ?>;
  }

  a:visited {
    color: <?php echo $_SESSION['ds_linkcolor']; ?>;
  }

  a:active {
    color: <?php echo $_SESSION['ds_linkcolor']; ?>;
  }

  a:hover {
    color: <?php echo $_SESSION['ds_alertcolor']; ?>;
  }

  a.button:hover {
    color: <?php echo $_SESSION['ds_alertcolor']; ?>;
  }

  h5 {
    background-color: <?php echo $_SESSION['ds_menucolor']; ?>;
  }

  h6 {
    background-color: <?php echo $_SESSION['ds_menucolor']; ?>;
  }

  /*********************************************/
  /*           Layout Rules                    */
  /*********************************************/
  <?php if ($_SESSION['ds_style_image_id'] == 0) { ?>
  #header {
    background-color: <?php echo $_SESSION['ds_bgcolor']; ?>;
  }

  #wrapper {
    background-color: <?php echo $_SESSION['ds_bgcolor']; ?>;
  }

  #mainprogram {
    background-color: <?php echo $_SESSION['ds_bgcolor']; ?>;
  }

  <?php } ?>

  <?php if($_SESSION['ds_nbtablecolors'] > 1) { ?>
  .trtablecolor1 {
    background-color: <?php echo $_SESSION['ds_tablecolor1']; ?>;
  }

  <?php } ?>

  .trtablecolor2 {
    background-color: <?php echo $_SESSION['ds_tablecolor2']; ?>;
  }

  <?php
 if($_SESSION['ds_usetablecolorsub'] == 1)
 {
 ?>

  .trtablecolorsub {
    background-color: <?php echo $_SESSION['ds_tablecolorsub']; ?>;;
  }

  <?php }  ?>

  /* these to items to fix autocomplete */
  input:-webkit-autofill {
    -webkit-box-shadow: 0 0 0 50px <?php echo $_SESSION['ds_inputcolor']; ?> inset;
    -webkit-text-fill-color: <?php echo $_SESSION['ds_fgcolor']; ?>;
  }

  input:-webkit-autofill:focus {
    -webkit-text-fill-color: <?php echo $_SESSION['ds_fgcolor']; ?>;
  }

  /*********************************************/
  /*           State Rules                    */
  /*********************************************/

  .alert {
    color: <?php echo $_SESSION['ds_alertcolor']; ?>;
  }

  .info {
    color: <?php echo $_SESSION['ds_fgcolor']; ?>;
  }

  .myblock {
    border: 3px solid <?php echo $_SESSION['ds_menubordercolor']; ?>;
  }
</style>