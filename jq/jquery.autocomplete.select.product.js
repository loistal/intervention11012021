$(function() {
    $('[id^="product_autocomplete"]').autocomplete({
        source: "autocomplete_product.php",
        minLength: 2,
        select: function(event, ui) {
            var url = ui.item.id;
            //if(url != '#') {
            //    location.href = '/blog/' + url;
            //}
        },

        html: true, // optional (jquery.ui.autocomplete.html.js required)

        // optional (if other layers overlap autocomplete list)
        open: function(event, ui) {
            $(".ui-autocomplete").css("z-index", 1000);
        }
    });
});