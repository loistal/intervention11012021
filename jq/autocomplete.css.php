<style type="text/css">
  /*********************************************/
  /*          Highlight result                 */
  /*********************************************/
  .ui-autocomplete span.hl_results {
    background-color: <?php echo $_SESSION['ds_bgcolor']; ?>;
  }

  .ui-widget {
    font-family: <?php echo $_SESSION['ds_user_font']; ?>;
  }

  .ui-autocomplete li {
    font-size: <?php echo $_SESSION['ds_user_font_size']; ?>;
  }

  /*********************************************/
  /*          Scroll results                   */
  /*********************************************/
  .ui-autocomplete {
    max-height: 250px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
    /* add padding for vertical scrollbar */
    padding-right: 5px;
  }

  /*********************************************/
  /*          Interaction states               */
  /*********************************************/
  .ui-state-default,
  .ui-widget-content .ui-state-default,
  .ui-widget-header .ui-state-default {
    border: none;
    background-color: <?php echo $_SESSION['ds_tablecolor1']; ?>;
    font-weight: bold;
    color: <?php echo $_SESSION['ds_fgcolor']; ?>;
  }

  .ui-state-default a,
  .ui-state-default a:link,
  .ui-state-default a:visited {
    color: <?php echo $_SESSION['ds_fgcolor']; ?>;
    text-decoration: none;
  }

  .ui-state-hover,
  .ui-widget-content .ui-state-hover,
  .ui-widget-header .ui-state-hover,
  .ui-state-focus,
  .ui-widget-content .ui-state-focus,
  .ui-widget-header .ui-state-focus {
    border: none;
    background: none;
    background-color: <?php echo $_SESSION['ds_tablecolor1']; ?>;
    font-weight: bold;
    color: <?php echo $_SESSION['ds_fgcolor']; ?>;
  }

  .ui-state-hover a,
  .ui-state-hover a:hover,
  .ui-state-hover a:link,
  .ui-state-hover a:visited,
  .ui-state-focus a,
  .ui-state-focus a:hover,
  .ui-state-focus a:link,
  .ui-state-focus a:visited {
    color: <?php echo $_SESSION['ds_fgcolor']; ?>;
    text-decoration: none;
  }

  .ui-state-active,
  .ui-widget-content .ui-state-active,
  .ui-widget-header .ui-state-active {
    border: none;
    background-color: <?php echo $_SESSION['ds_tablecolor1']; ?>;
    font-weight: bold;
    color: <?php echo $_SESSION['ds_fgcolor']; ?>;
  }

  .ui-state-active a,
  .ui-state-active a:link,
  .ui-state-active a:visited {
    color: <?php echo $_SESSION['ds_fgcolor']; ?>;
    text-decoration: none;
  }

  /* IE 6 doesn't support max-height
  * we use height instead, but this forces the menu to always be this tall
  */
  * html .ui-autocomplete {
    height: 250px;
  }
</style>