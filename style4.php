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
    background-color: <?php echo $_SESSION['ds_menucolor']; ?>;
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
    background-color: <?php echo $_SESSION['ds_menubordercolor']; ?>;
  }

  #cssmenu li:hover {
    border: 1px solid <?php echo $_SESSION['ds_menucolor']; ?>;
  }

  #cssmenu li:hover a {
    -moz-box-shadow: inset 0 5px 10px <?php echo $_SESSION['ds_menubordercolor']; ?>;
    -webkit-box-shadow: inset 0 5px 10px <?php echo $_SESSION['ds_menubordercolor']; ?>;
    box-shadow: inset 0 5px 10px <?php echo $_SESSION['ds_menubordercolor']; ?>;
    background-color: <?php echo $_SESSION['ds_menubordercolor']; ?>;
  }
  
  a.leftmenu{
   display: inline-block;
   background-color: <?php echo $_SESSION['ds_linkcolor']; ?>;
   border: 1px solid <?php echo $_SESSION['ds_bgcolor']; ?>;
   border-radius: 6px;
   color: <?php echo $_SESSION['ds_menufontcolor']; ?>;
   padding: 5px 5px 5px 5px;
  }
  
  a.leftmenu:hover{
   background-color: <?php echo $_SESSION['ds_menubordercolor']; ?>;
   text-decoration: none;
  }
</style>