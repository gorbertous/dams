(function ($) {
    "use strict";
    $.getJSON('/home/daily?callback=?', function (data) {
        // Construction of the left list
        $.each(data, function (i, rate) {
            var trend = "";
            var value = "";
            switch (rate.trend) {
                case 0:
                    trend = "<i class='fa fa-minus'></i>";
                    value = "<span class='badge badge-info'>" + rate.value + "</span>";
                    break;
                case 1:
                    trend = "<i class='fas fa-arrow-up'></i>";
                    value = "<span class='badge badge-success'>" + rate.value + "</span>";
                    break;
                case 2:
                    trend = "<i class='fas fa-arrow-down'></i>";
                    value = "<span class='badge badge-danger'>" + rate.value + "</span>";
                    break;
            }

            $("#rates").append("<tr><td>" + rate.currency + "</td><td>" + trend + "</td><td><a href='/home/jsonp?currency=" + rate.currency + "&callback=?'>" + value + "</a></td></tr>");
        });

        // adding the active class on the first element
        // this element will be use to load the first graph
        $("#rates tbody tr:first-child").addClass("active").append('<i class="fas fa-eye"></i>');

        // Loading the graph of the first top left list element
        $.getJSON($("#rates .active a").attr('href'), function (data) {
            window.chart = new Highcharts.StockChart(data);
        });

        $("#rates a").click(function (e) {
            e.preventDefault();
            $.getJSON($(this).attr('href'), function (data) {
                // Create the chart
                window.chart = new Highcharts.StockChart(data);
            });
            $("#rates .active i").removeClass("i fa-eye");
            $("#rates .active").removeClass("active");
           
            $(this).parent().parent().addClass("active").append('<i class="fas fa-eye"></i>');
        });
    });
})(jQuery);

