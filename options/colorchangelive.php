<script type="text/javascript">
  /* TODO menustyle 4 */
  $(document).ready(function () {
    $('select[name="bgcolor"], input[name="bgcolor"]').change(function () {
      $('body').css("backgroundColor", $(this).val());
      $('table.transparent').css("backgroundColor", $(this).val());
      $('form.loginbox').css("backgroundColor", $(this).val());
      $('#header').css("backgroundColor", $(this).val());
      $('#wrapper').css("backgroundColor", $(this).val());
      $('#mainprogram').css("backgroundColor", $(this).val());
    });

    $('select[name="fgcolor"], input[name="fgcolor"]').change(function () {
      $('body').css("color", $(this).val());
      $('input, select').css("color", $(this).val());
      $('input, select').css("border", "1px solid " + $(this).val());
      $('fieldset.loginbox').css("border", "1px solid " + $(this).val());
      $('input.readonly').css("color", $(this).val());
      $('textarea').css("border", "1px solid " + $(this).val());
      $('textarea').css("color", $(this).val());

      $('input:-webkit-autofill').css("-webkit-text-fill-color", $(this).val());
      $('input:-webkit-autofill:focus').css("-webkit-text-fill-color", $(this).val());

      $('.info').css("color", $(this).val());
    });

    $('select[name="tablecolor"], input[name="tablecolor"]').change(function () {
      $('table.report').css("backgroundColor", $(this).val());
    });

    $('select[name="hovercolor"], input[name="hovercolor"]').change(function () {
      /* Table report tr */
      $('table.report tr').mouseenter(function () {
        $(this).css("backgroundColor", $('select[name="hovercolor"], input[name="hovercolor"]').val());
      });

      /* Sub total */
      $('table.report tr.trtablecolorsub').mouseleave(function () {
        $(this).css("backgroundColor", $('select[name="tablecolorsub"], input[name="tablecolorsub"]').val());
      });

      /* Table color 1 */
      $('table.report tr.trtablecolor1').mouseleave(function () {
        $(this).css("backgroundColor", $('select[name="tablecolor1"], input[name="tablecolor1"]').val());
      });

      /* Table color 2 */
      $('table.report tr.trtablecolor2').mouseleave(function () {
        $(this).css("backgroundColor", $('select[name="tablecolor2"], input[name="tablecolor2"]').val());
      });

      /* Table head */
      $('table.report thead tr').mouseleave(function () {
        $(this).css("backgroundColor", $('select[name="tablecolor"], input[name="tablecolor"]').val());
      });
    });

    $('select[name="inputcolor"], input[name="inputcolor"]').change(function () {
      $('input, select').css("backgroundColor", $(this).val());
      $('textarea').css("backgroundColor", $(this).val());

      $('input:-webkit-autofill').css("-webkit-box-shadow", "0 0 0 50px " + $(this).val());
    });

    $('select[name="alertcolor"], input[name="alertcolor"]').change(function () {
      $('input:focus, select:focus').css("outlineColor", $(this).val());
      $('input:focus, select:focus').css("border", "1px solid " + $(this).val());

      $('a, a:button').hover(function () {
        $(this).css("color", $('select[name="alertcolor"], input[name="alertcolor"]').val());
      });

      $('.alert').css("color", $(this).val());
    });

    $('select[name="menubordercolor"], input[name="menubordercolor"]').change(function () {
      $('form').css("border", "1px solid " + $(this).val());
      $('.myblock').css("border", "3px solid " + $(this).val());
    });

    $('select[name="formcolor"], input[name="formcolor"]').change(function () {
      $('form').css("backgroundColor", $(this).val());
      $('form table tbody').css("backgroundColor", $(this).val());
      $('form.mainlogin').css("backgroundColor", $(this).val());
      $('fieldset.loginbox').css("backgroundColor", $(this).val());
    });

    $('select[name="linkcolor"], input[name="linkcolor"]').change(function () {
      $('a:link').css("color", $(this).val());
      $('a:visited').css("color", $(this).val());
      $('a:active').css("color", $(this).val());
    });

    $('select[name="menucolor"], input[name="menucolor"]').change(function () {
      $('h5').css("backgroundColor", $(this).val());
      $('h6').css("backgroundColor", $(this).val());


    });

    $('select[name="tablecolor1"], input[name="tablecolor1"]').change(function () {
      $('.trtablecolor1').css("backgroundColor", $(this).val());
    });

    $('select[name="tablecolor2"], input[name="tablecolor2"]').change(function () {
      $('.trtablecolor2').css("backgroundColor", $(this).val());
    });

    $('select[name="tablecolorsub"], input[name="tablecolorsub"]').change(function () {
      $('.trtablecolorsub').css("backgroundColor", $(this).val());
    });
  });
</script>

<?php if ($_SESSION['ds_menustyle'] == 1)
{ ?>
  <script type="text/javascript">
    $(document).ready(function () {
      $('select[name="fgcolor"], input[name="fgcolor"]').change(function () {
        $('button, input[type=submit]').css("color", $(this).val());
        $('button, input[type=submit]').css("border", "1px solid " + $(this).val());
      });

      $('select[name="bgcolor"], input[name="bgcolor"]').change(function () {
        $('.menu-button').css("text-shadow", "1px 1px 3px " + $(this).val());
        $('.menu-button-current').css("tex-shadow", "1px 1px 3px " + $(this).val());
      });

      $('select[name="infocolor"], input[name="infocolor"]').change(function () {
        $('button, input[type=submit]').css("backgroundColor", $(this).val());
      });

      $('select[name="alertcolor"], input[name="alertcolor"]').change(function () {
        $('button, input[type=submit]').hover(function () {
          $(this).css("color", $('select[name="alertcolor"], input[name="alertcolor"]').val());
        });

        $('#menu-button-1').hover(function () {
          $(this).css("borderColor", $('select[name="alertcolor"], input[name="alertcolor"]').val());
          $(this).css("backgroundColor", $('select[name="alertcolor"], input[name="alertcolor"]').val());
          $(this).css("backgroundImage", "-webkit-gradient(linear, 0% 0%, 0% 90%, from(" + $('select[name="alertcolor"], input[name="alertcolor"]').val() + ", to(#ffffff))");
          $(this).css("backgroundImage", "-moz-linear-gradient(top, " + $('select[name="alertcolor"], input[name="alertcolor"]').val() + ", #ffffff)");
          $(this).css("backgroundImage", "-o-linear-gradient(" + $('select[name="alertcolor"], input[name="alertcolor"]').val() + ", rgb(255, 255, 255))");

          $(this).css("-o-box-shadow", "0 8px 24px " + $('select[name="alertcolor"], input[name="alertcolor"]').val());
          $(this).css("-khtml-box-shadow", "0 8px 24px " + $('select[name="alertcolor"], input[name="alertcolor"]').val());
          $(this).css("-moz-box-shadow", "0 8px 24px " + $('select[name="alertcolor"], input[name="alertcolor"]').val());
          $(this).css("box-shadow", "0 8px 24px " + $('select[name="alertcolor"], input[name="alertcolor"]').val());
          $(this).css("color", $('select[name="alertcolor"], input[name="alertcolor"]').val());
        });
      });

      $('select[name="linkcolor"], input[name="linkcolor"]').change(function () {
        $('input.technicalsupport').css("color", $(this).val());
        $('.menu-button').css("color", $(this).val());
      });

      $('select[name="menucolor"], input[name="menucolor"]').change(function () {
        $('#selectactionbar').css("backgroundColor", $(this).val());
        $('.menu-button').css("border", "2px solid " + $(this).val());
        $('.menu-button').css("backgroundColor", $(this).val());
        $('.menu-button').css("backgroundImage", "-webkit-gradient(linear, 0% 0%, 0% 90%, from(" + $(this).val() + ", to(#ffffff))");
        $('.menu-button').css("backgroundImage", "-moz-linear-gradient(top, " + $(this).val() + ", #ffffff)");
        $('.menu-button').css("backgroundImage", "-o-linear-gradient(" + $(this).val() + ", rgb(255, 255, 255))");

        $('.menu-button').css("-o-box-shadow", "0 8px 24px " + $(this).val());
        $('.menu-button').css("-khtml-box-shadow", "0 8px 24px " + $(this).val());
        $('.menu-button').css("-moz-box-shadow", "0 8px 24px " + $(this).val());
        $('.menu-button').css("box-shadow", "0 8px 24px " + $(this).val());

        $('.menu-button-current').css("border", "2px solid " + $(this).val());
        $('.menu-button-current').css("backgroundColor", $(this).val());

        $('.menu-button-current').css("backgroundImage", "-webkit-gradient(linear, 0% 0%, 0% 90%, from(" + $(this).val() + ", to(#ffffff))");
        $('.menu-button-current').css("backgroundImage", "-moz-linear-gradient(top, " + $(this).val() + ", #ffffff)");
        $('.menu-button-current').css("backgroundImage", "-o-linear-gradient(" + $(this).val() + ", rgb(255, 255, 255))");

        $('.menu-button-current').css("-o-box-shadow", "0 8px 24px " + $(this).val());
        $('.menu-button-current').css("-khtml-box-shadow", "0 8px 24px " + $(this).val());
        $('.menu-button-current').css("-moz-box-shadow", "0 8px 24px " + $(this).val());
        $('.menu-button-current').css("box-shadow", "0 8px 24px " + $(this).val());
      });
  </script>
<?php }
elseif ($_SESSION['ds_menustyle'] == 2 || $_SESSION['ds_menustyle'] == 4)
{ ?>
  <script type="text/javascript">
    $(document).ready(function () {
      $('select[name="fgcolor"], input[name="fgcolor"]').change(function () {
        $('button, input[type=submit]').css("color", $(this).val());
      });

      $('select[name="bgcolor"], input[name="bgcolor"]').change(function () {
        $('button, input[type=submit]').css("text-shadow", "0 0.1em 0 " + $(this).val());
      });

      $('select[name="infocolor"], input[name="infocolor"]').change(function () {
        $('button, input[type=submit]').css("borderColor", $(this).val());
        $('button, input[type=submit]').css("backgroundImage", "-moz-linear-gradient(" + $(this).val() + "," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + ")");
        $('button, input[type=submit]').css("backgroundImage", "-webkit-linear-gradient(" + $(this).val() + "," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + ")");
        $('button, input[type=submit]').css("backgroundImage", "linear-gradient(" + $(this).val() + "," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + ")");

        $('button:active, input[type=submit]:active').css("border-color", $(this).val());
        $('button:active, input[type=submit]:active').css("backgroundImage", "-moz-linear-gradient(" + $(this).val() + "," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + ")");
        $('button:active, input[type=submit]:active').css("backgroundImage", "-webkit-linear-gradient(" + $(this).val() + "," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + ")");
        $('button:active, input[type=submit]:active').css("backgroundImage", "linear-gradient(" + $(this).val() + "," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + ")");
      });

      $('select[name="linkcolor"], input[name="linkcolor"]').change(function () {
        $('input.technicalsupport').css("color", $(this).val());
      });

      $('select[name="menubordercolor"], input[name="menubordercolor"]').change(function () {
        $('#cssmenu').css("border", "1px solid " + $(this).val());
        $('#cssmenu li.active a').css("border", "1px solid " + $(this).val());

        $('#cssmenu li.active a').css("-moz-box-shadow", "inset 0 5px 10px " + $(this).val());
        $('#cssmenu li.active a').css("-webkit-box-shadow", "inset 0 5px 10px " + $(this).val());
        $('#cssmenu li.active a').css("box-shadow", "inset 0 5px 10px " + $(this).val());

        $('#cssmenu li').hover(function () {
          $(this).find('a').css("border", "1px solid " + $('select[name="menubordercolor"], input[name="menubordercolor"]').val());
          $(this).find('a').css("-moz-box-shadow", "inset 0 5px 10px " + $('select[name="menubordercolor"], input[name="menubordercolor"]').val());
          $(this).find('a').css("-webkit-box-shadow", "inset 0 5px 10px " + $('select[name="menubordercolor"], input[name="menubordercolor"]').val());
          $(this).find('a').css("box-shadow", "inset 0 5px 10px " + $('select[name="menubordercolor"], input[name="menubordercolor"]').val());
        });
      });

      $('select[name="menucolor"], input[name="menucolor"]').change(function () {
        $('#cssmenu').css("backgroundColor", $(this).val());

        $('#cssmenu').css("backgroundImage", "-moz-linear-gradient(top, " + $(this).val() + " 0%," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + " 100%)");
        $('#cssmenu').css("backgroundImage", "-webkit-linear-gradient(top, " + $(this).val() + " 0%," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + " 100%)");
        $('#cssmenu').css("backgroundImage", "linear-gradient(top, " + $(this).val() + " 0%," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + " 100%)");

        $('#cssmenu li.active').css("border", "1px solid " + $(this).val());

        $('#cssmenu li').hover(function () {
          $(this).css("border", "1px solid " + $('select[name="menucolor"], input[name="menucolor"]').val());
        });
      });

      $('select[name="menufontcolor"], input[name="menufontcolor"]').change(function () {
        $('#cssmenu li a').css("color", $(this).val());
      });
    });
  </script>
<?php }
elseif ($_SESSION['ds_menustyle'] == 3)
{ ?>
  <script type="text/javascript">
    $(document).ready(function () {
      $('select[name="bgcolor"], input[name="bgcolor"]').change(function () {
        $('table.transparent').css("backgroundColor", $(this).val());
        $('button, input[type=submit]').css("text-shadow", "0 0.1em 0 " + $(this).val());
      });

      $('select[name="fgcolor"], input[name="fgcolor"]').change(function () {
        $('button, input[type=submit]').css("color", $(this).val());
      });

      $('select[name="infocolor"], input[name="infocolor"]').change(function () {
        $('button, input[type=submit]').css("borderColor", $(this).val());
        $('button, input[type=submit]').css("backgroundImage", "-moz-linear-gradient(" + $(this).val() + "," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + ")");
        $('button, input[type=submit]').css("backgroundImage", "-webkit-linear-gradient(" + $(this).val() + "," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + ")");
        $('button, input[type=submit]').css("backgroundImage", "linear-gradient(" + $(this).val() + "," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + ")");

        $('button:active, input[type=submit]:active').css("border-color", $(this).val());
        $('button:active, input[type=submit]:active').css("backgroundImage", "-moz-linear-gradient(" + $(this).val() + "," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + ")");
        $('button:active, input[type=submit]:active').css("backgroundImage", "-webkit-linear-gradient(" + $(this).val() + "," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + ")");
        $('button:active, input[type=submit]:active').css("backgroundImage", "linear-gradient(" + $(this).val() + "," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + ")");
      });

      $('select[name="linkcolor"], input[name="linkcolor"]').change(function () {
        $('input.technicalsupport').css("color", $(this).val());
      });

      $('select[name="menubordercolor"], input[name="menubordercolor"]').change(function () {
        $('.selectaction').css("border", "1px solid " + $(this).val());
        $('.selectactionhomepage').css("border", "1px solid " + $(this).val());
        $('.dashboard').css("border", "1px solid " + $(this).val());
        $('#cssmenu').css("border", "1px solid " + $(this).val());
        $('#cssmenu li.active').css("border", "1px solid " + $(this).val());

        $('#cssmenu li.active a').css("border", "1px 1px 0 1px " + $(this).val());
        $('#cssmenu li.active a').css("-moz-box-shadow", "inset 0 5px 10px " + $(this).val());
        $('#cssmenu li.active a').css("-webkit-box-shadow", "inset 0 5px 10px " + $(this).val());
        $('#cssmenu li.active a').css("box-shadow", "inset 0 5px 10px " + $(this).val());

        $('#cssmenu li').hover(function () {
          $(this).css("border", "1px solid " + $('select[name="menubordercolor"], input[name="menubordercolor"]').val());
        });

        $('#cssmenu li').hover(function () {
          $(this).find('a').css("border", "1px solid " + $('select[name="menubordercolor"], input[name="menubordercolor"]').val());
          $(this).find('a').css("-moz-box-shadow", "inset 0 5px 10px " + $('select[name="menubordercolor"], input[name="menubordercolor"]').val());
          $(this).find('a').css("-webkit-box-shadow", "inset 0 5px 10px " + $('select[name="menubordercolor"], input[name="menubordercolor"]').val());
          $(this).find('a').css("box-shadow", "inset 0 5px 10px " + $('select[name="menubordercolor"], input[name="menubordercolor"]').val());
        });
      });

      $('select[name="tablecolor"], input[name="tablecolor"]').change(function () {
        $('.selectactiontitle').css("border", "1px solid " + $(this).val());
        $('#cssmenu li.active a').css("backgroundColor", $(this).val());
        $('#cssmenu li').hover(function () {
          $(this).find('a').css("backgroundColor", $('select[name="tablecolor"], input[name="tablecolor"]').val());
        });
      });

      $('select[name="menucolor"], input[name="menucolor"]').change(function () {
        $('#cssmenu').css("backgroundColor", $(this).val());

        $('#cssmenu').css("backgroundImage", "-moz-linear-gradient(top, " + $(this).val() + " 0%," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + " 100%)");
        $('#cssmenu').css("backgroundImage", "-webkit-linear-gradient(top, " + $(this).val() + " 0%," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + " 100%)");
        $('#cssmenu').css("backgroundImage", "linear-gradient(top, " + $(this).val() + " 0%," + $('select[name="bgcolor"], input[name="bgcolor"]').val() + " 100%)");
      });

      $('select[name="menufontcolor"], input[name="menufontcolor"]').change(function () {
        $('#cssmenu li a').css("color", $(this).val());
      });
    });
  </script>
<?php } ?>