<style type="text/css">
  /*********************************************/
  /*          Base rules                       */
  /*********************************************/

  button, input[type=submit] {
    color: <?php echo $_SESSION['ds_fgcolor']; ?>;
    background-color: <?php echo $_SESSION['ds_infocolor']; ?>;
    border: 1px solid <?php echo $_SESSION['ds_fgcolor']; ?>;
  }

  button:hover, input[type=submit]:hover {
    color: <?php echo $_SESSION['ds_alertcolor']; ?>;
  }

  input.technicalsupport {
    color: <?php echo $_SESSION['ds_linkcolor']; ?>;
  }

  /*********************************************/
  /*          Modules                          */
  /*********************************************/

  #selectactionbar {
    background-color: <?php echo $_SESSION['ds_menucolor']; ?>;
  }

  /* Menu */
  .menu-button {
    border: 2px solid <?php echo $_SESSION['ds_menucolor']; ?>;
    background-color: <?php echo $_SESSION['ds_menucolor']; ?>;
    color: <?php echo $_SESSION['ds_linkcolor']; ?>;
    text-shadow: 1px 1px 3px <?php echo $_SESSION['ds_bgcolor']; ?>;
    background-image: -webkit-gradient(linear, 0% 0%, 0% 90%, from(<?php echo $_SESSION['ds_menucolor']; ?>), to(#ffffff));
    background-image: -moz-linear-gradient(top,<?php echo $_SESSION['ds_menucolor']; ?>, #ffffff);
    background-image: -o-linear-gradient(<?php echo $_SESSION['ds_menucolor']; ?>, rgb(255, 255, 255));
    -o-box-shadow: 0 8px 24px <?php echo $_SESSION['ds_menucolor']; ?>;
    -khtml-box-shadow: 0 8px 24px <?php echo $_SESSION['ds_menucolor']; ?>;
    -moz-box-shadow: 0 8px 24px <?php echo $_SESSION['ds_menucolor']; ?>;
    box-shadow: 0 8px 24px <?php echo $_SESSION['ds_menucolor']; ?>;
  }

  #menu-button-1:hover {
    border-color: <?php echo $_SESSION['ds_alertcolor']; ?>;
    background-color: <?php echo $_SESSION['ds_alertcolor']; ?>;
    background-image: -webkit-gradient(linear, 0% 0%, 0% 90%, from(<?php echo $_SESSION['ds_alertcolor']; ?>), to(#ffffff));
    background-image: -moz-linear-gradient(top,<?php echo $_SESSION['ds_alertcolor']; ?>, #ffffff);
    background-image: -o-linear-gradient(<?php echo $_SESSION['ds_alertcolor']; ?>, rgb(255, 255, 255));
    -o-box-shadow: 0 8px 24px <?php echo $_SESSION['ds_alertcolor']; ?>;
    -khtml-box-shadow: 0 8px 24px <?php echo $_SESSION['ds_alertcolor']; ?>;
    -moz-box-shadow: 0 8px 24px <?php echo $_SESSION['ds_alertcolor']; ?>;
    box-shadow: 0 8px 24px <?php echo $_SESSION['ds_alertcolor']; ?>;
  }

  .menu-button-current {
    border: 2px solid <?php echo $_SESSION['ds_menucolor']; ?>;
    background-color: <?php echo $_SESSION['ds_menucolor']; ?>;
    color: <?php echo $_SESSION['ds_alertcolor']; ?>;
    text-shadow: 1px 1px 3px <?php echo $_SESSION['ds_bgcolor']; ?>;
    background-image: -webkit-gradient(linear, 0% 0%, 0% 90%, from(<?php echo $_SESSION['ds_menucolor']; ?>), to(#ffffff));
    background-image: -moz-linear-gradient(top,<?php echo $_SESSION['ds_menucolor']; ?>, #ffffff);
    background-image: -o-linear-gradient(<?php echo $_SESSION['ds_menucolor']; ?>, rgb(255, 255, 255));
    -o-box-shadow: 0 8px 24px <?php echo $_SESSION['ds_menucolor']; ?>;
    -khtml-box-shadow: 0 8px 24px <?php echo $_SESSION['ds_menucolor']; ?>;
    -moz-box-shadow: 0 8px 24px <?php echo $_SESSION['ds_menucolor']; ?>;
    box-shadow: 0 8px 24px <?php echo $_SESSION['ds_menucolor']; ?>;
  }
</style>