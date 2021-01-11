<style type="text/css">
  /*********************************************/
  /*          Base rules                       */
  /*********************************************/
  button, input[type=submit] {
    color: <?php echo $_SESSION['ds_fgcolor']; ?>;
    text-shadow: 0 0.1em 0 <?php echo $_SESSION['ds_bgcolor']; ?>;
    border-color: <?php echo $_SESSION['ds_infocolor']; ?>;
    background-image: -moz-linear-gradient(<?php echo $_SESSION['ds_infocolor']; ?>, <?php echo $_SESSION['ds_bgcolor']; ?>);
    background-image: -webkit-linear-gradient(<?php echo $_SESSION['ds_infocolor']; ?>, <?php echo $_SESSION['ds_bgcolor']; ?>);
    background-image: linear-gradient(<?php echo $_SESSION['ds_infocolor']; ?>, <?php echo $_SESSION['ds_bgcolor']; ?>);
  }

  button:active, input[type=submit]:active {
    border-color: <?php echo $_SESSION['ds_infocolor']; ?>;
    background-image: -moz-linear-gradient(<?php echo $_SESSION['ds_infocolor']; ?>, <?php echo $_SESSION['ds_bgcolor']; ?>);
    background-image: -webkit-linear-gradient(<?php echo $_SESSION['ds_infocolor']; ?>, <?php echo $_SESSION['ds_bgcolor']; ?>);
    background-image: linear-gradient(<?php echo $_SESSION['ds_infocolor']; ?>, <?php echo $_SESSION['ds_bgcolor']; ?>);
  }

  input.technicalsupport {
    color: <?php echo $_SESSION['ds_linkcolor']; ?>;
  }

  /*********************************************/
  /*          Modules                          */
  /*********************************************/

  /* CSS Menu */
  #cssmenu {
    border: 1px solid <?php echo $_SESSION['ds_menubordercolor']; ?>;
    background-color: <?php echo $_SESSION['ds_menucolor']; ?>;
    background-image: -moz-linear-gradient(top, <?php echo $_SESSION['ds_menucolor']; ?> 0%, <?php echo $_SESSION['ds_linkcolor']; ?> 100%);
    background-image: -webkit-linear-gradient(top, <?php echo $_SESSION['ds_menucolor']; ?> 0%, <?php echo $_SESSION['ds_linkcolor']; ?> 100%);
    background-image: linear-gradient(top, <?php echo $_SESSION['ds_menucolor']; ?> 0%, <?php echo $_SESSION['ds_linkcolor']; ?> 100%);
  }

  #cssmenu li a {
    color: <?php echo $_SESSION['ds_menufontcolor']; ?>;
  }

  #cssmenu li.active {
    border: 1px solid <?php echo $_SESSION['ds_menucolor']; ?>;
  }

  #cssmenu li.active a {
    border: 1px solid <?php echo $_SESSION['ds_menubordercolor']; ?>;
    -moz-box-shadow: inset 0 5px 10px <?php echo $_SESSION['ds_menubordercolor']; ?>;
    -webkit-box-shadow: inset 0 5px 10px <?php echo $_SESSION['ds_menubordercolor']; ?>;
    box-shadow: inset 0 5px 10px <?php echo $_SESSION['ds_menubordercolor']; ?>;
  }

  #cssmenu li:hover {
    border: 1px solid <?php echo $_SESSION['ds_menucolor']; ?>;
  }

  #cssmenu li:hover a {
    -moz-box-shadow: inset 0 5px 10px <?php echo $_SESSION['ds_menubordercolor']; ?>;
    -webkit-box-shadow: inset 0 5px 10px <?php echo $_SESSION['ds_menubordercolor']; ?>;
    box-shadow: inset 0 5px 10px <?php echo $_SESSION['ds_menubordercolor']; ?>;
  }
</style>