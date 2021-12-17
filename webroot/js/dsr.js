(function ($) {
    "use strict";
    var eagerFilter = false;
    // Toggle the side navigation
    $("#sidebarToggle").on("click", function (e) {
        e.preventDefault();
        $("body").toggleClass("sb-sidenav-toggled");
    });

    // code for popups
    $('[data-toggle="popover"]').popover({
        html: true
    });

    // Handle left menu active/collapse state
    var current = location.pathname;

    // expand the menu
    function collapseMenu(item) {
        var chapterNavid = item;
        var targetDiv = document.getElementById(chapterNavid).getElementsByClassName("collapse");
        var targetLink = document.getElementById(chapterNavid).getElementsByClassName("nav-link");
        for (var i = 0; i < targetDiv.length; i++) {
            targetDiv.item(i).classList.add("show");
        }
        for (var i = 0; i < targetLink.length; i++) {
            targetLink.item(i).classList.remove("collapsed");
        }
    }
    //is action link in the array
    function isInArrayList(arraylist) {
        // current action link
        const action_link = current.split("/").slice(-1).join("/");
        const analytics_links = arraylist.find(element => element === action_link);
        return analytics_links;
    }
    function setMenuItemActive() {
        switch (true) {
            case current.indexOf("dsr-view") !== - 1:
                $("#view_list").addClass("active");
                break;
            case current.indexOf("import") !== - 1:
                $("#report_import").addClass("active");
                collapseMenu("dsrreports-items")
                break;
            case current.indexOf("reports") !== - 1:
                $("#report_list").addClass("active");
                collapseMenu("dsrreports-items")
                break;
            case current.indexOf("portfolios") !== - 1:
                $("#portfolio_list").addClass("active");
                break;
            case current.indexOf("products") !== - 1:
                $("#product_list").addClass("active");
                break;
            case current.indexOf("loans") !== - 1:
                $("#loan_list").addClass("active");
                break;
            case current.indexOf("dictionaries") !== - 1 || current.indexOf("dico-values") !== - 1:
                $("#dico_list").addClass("active");
                break;
            default:
                $("#home").addClass("active");
        }
    }
    //append active class to menu item
    setMenuItemActive();

    //filters submit on change
    $(".filters").change(function () {
        if (!$(this).hasClass("filtersLive")) {
            $(this).closest("form").submit();
        }
    });

    $(".filtersLive").on('input', function () {
        eagerFilter = true;
        $(this).closest("form").submit();
    });
   
    //JQuery date pickeer
    $(".datepicker").datepicker({'dateFormat': 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $( ".formAjax" ).submit(function( event ) {
        if (eagerFilter) {
            eagerFilter = false;
            var actionURL = $( this ).attr('action').split('?')[0] + "?" + $( this ).serialize();
            var divID = $( this ).attr('id') + "_data";
            $.ajax({
                url: actionURL,
                dataType: "html",
                success: function(data) {
                    $('#'+divID).html(data);
                },
                error: function(e) {
                    console.log(e);
                }
            });
            event.preventDefault();
            return false;
        }
        return true;
    });
      

})(jQuery);
