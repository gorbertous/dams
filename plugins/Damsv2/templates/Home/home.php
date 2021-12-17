<?php
$date_update = date("d/m/Y", (strtotime('now') - 86400));
?>
<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1 class="display-4">DAMS</h1>
        <hr>
        <p class="lead"><button data-toggle="collapse" data-target="#versioninfo"><i class="fas fa-plus"></i></button> Version 6.1</p>
        <div class="collapse" id="versioninfo">
            <p class="h4">Product:</p>
            <ul>
                <li>ESIF ERDF Greece template</li>
            </ul>

            <p class="h4">Improvement:</p>
            <ul>
                <li>Integrity check when editing GGE with the same data (transaction + gge modification data + gge additional amount)</li>
            </ul>
        </div>
    </div>
</div>

<div class='with-3d-shadow with-transitions'>

    <h4 class="text-center pb-3">Number of Supported SMEs* by EIF - situation as of <?= $date_update; ?></h4>

    <svg id="chart1" class="with-3d-shadow with-transitions" style="height:470px;"></svg>

</div>
<p class="small">* Please note that the chart above does not include EIF's securitisation business. This data is currently not in DAMS.</p>
<button type="button" class="btn btn-outline-secondary my-3" id="export_graph_data">Export data</button>


<h4 class="text-center pb-3">Key summary statistics by mandate - Situation as of <?= $date_update; ?></h4>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Mandate</th>
                <th>Total Principal Amount (&euro;)**</th>
                <th>Total Disbursed Amount (&euro;)**</th>
                <th>Number of Loans</th>
                <th>Number of Supported SMEs***</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($result_summary_table as $data) {
                echo "<tr><td>";
                echo $data->mandate;
                echo "</td><td>";
                echo !empty($data->total_principal_amount_eur) ? $this->Number->precision($data->total_principal_amount_eur, 2) : 0;
                echo "</td><td>";
                echo !empty($data->total_disbursed_amount_eur) ? $this->Number->precision($data->total_disbursed_amount_eur, 2) : 0;
                echo "</td><td>";
                echo !empty($data->number_of_loans) ? $this->Number->precision($data->number_of_loans, 0) : 0;
                echo "</td><td>";
                echo !empty($data->number_of_supported_SMEs) ? $this->Number->precision($data->number_of_supported_SMEs, 0) : 0;
                echo "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<p class="small">** In the case of counter guarantees, the FI guarantee rate has been applied on principal and disbursed amounts.</p>
<p class="small">*** Please note that an SME could have been supported by multiple mandates and thus the grand total of supported SMEs for all mandates in the table will slightly overstate the true total.</p>


<button type="button" class="btn btn-outline-secondary my-3" id="export_stat_data">Export data</button>

<div class="py-5">For enquiries, please contact EIF SAS Support by e-mail <a href="mailto:eifsas-support@eif.org">eifsas-support@eif.org</a> or phone 81800.</div>

<script>
    $(window).on('load', function() {
        <?php
        $result = array();
        foreach ($result_graph as $graph_data) {
            $date = intval(strtotime($graph_data->period_end_date) . "000");
            $cumul = intval($graph_data->total_nbr_of_SMEs);
            $result[] = array('x' => $date, 'y' => $cumul);
        }

        echo "var last_date = " . $date . ";"; //to differenciate real values and projections

        echo 'var histcatexplong = [{"key":"Total number of SMEs","area":true, "values":' . json_encode($result) . '}];';
        ?>

        var length = histcatexplong[0]['values'].length;

        var i = 0;
        var x_scale = new Array();
        while (i < length) {
            var date = new Date(histcatexplong[0]['values'][i]['x'] * 1);
            var quarter = Math.floor((date.getMonth() + 3) / 3);
            if (quarter == 1) {
                x_scale.push(new Date(histcatexplong[0]['values'][i]['x'] * 1));
            }
            i++;
        }
        // add projection values to x_scale
        x_scale.push(new Date((last_date * 1) + (7772400000 * 4)));

        function x_scale_func(d) {
            return x_scale;
        }

        nv.addGraph(function() {
            var axisTimeFormat = d3.time.format.multi([
                [".%L", function(d) {
                    return d.getMilliseconds();
                }],
                [":%S", function(d) {
                    return d.getSeconds();
                }],
                ["%H:%M", function(d) {
                    return d.getMinutes();
                }],
                ["%H:%M", function(d) {
                    return d.getHours();
                }],
                ["%a %d", function(d) {
                    return d.getDay() && d.getDate() != 1;
                }],
                ["%b %d", function(d) {
                    return d.getDate() != 1;
                }],
                ["%B", function(d) {
                    return d.getMonth();
                }],
                ["%Y", function() {
                    return true;
                }]
            ]);

            var xAxis = d3.svg.axis()
                .scale(x_scale_func)
                .orient("bottom")
                .tickFormat(axisTimeFormat);

            function XtickFormat(d) {
                var date = new Date(d * 1);
                //var quarter = Math.floor((date.getMonth() + 3) / 3);
                var year = d3.time.format('%Y')(date);
                return year; // X marks on timeline selection : years
            }
            var chart = nv.models.lineWithFocusChart();
            //chart.brushExtent([50,70]);// default selection on X
            chart.x2Axis.tickFormat(XtickFormat);
            chart.yTickFormat(d3.format(',d')); //format on Y coord, thousand separators and no decimals (',.2f') for 2 decimals
            //chart.xAxis.tickValues(x_scale);// old, no event
            d3.select(".axis--x1").call(chart.xAxis.tickValues(x_scale_func)); //scale at load, to update on timeline update
            chart.interactiveLayer.tooltip.contentGenerator(function(d) {
                var date = new Date(d.value * 1);
                var quarter = Math.floor((date.getMonth() + 3) / 3);
                var year = d3.time.format('%Y')(date);
                var header = 'Q' + quarter + ' ' + year;
                var headerhtml = "<thead><tr><td colspan='3'><strong class='x-value'>" + header + "</strong></td></tr></thead>";

                var bodyhtml = "<tbody>";
                var series = d.series;
                if ((d.value * 1) > new Date(last_date)) {
                    var j = 1;
                    var jmax = 2;
                    for (j = 1; j <= jmax; j++) //series.forEach(function (d)
                    {
                        var d = series[j];
                        var val = new Intl.NumberFormat("en-EN").format(d.value);
                        bodyhtml = bodyhtml + "<tr><td class='legend-color-guide'><div style='background-color: " + d.color + ";'></div></td><td class='key'>" + d.key + "</td><td class='value'>" + val + "</td></tr>";
                    }
                } else {
                    var x = series[0];
                    var val = new Intl.NumberFormat("en-EN").format(x.value);
                    bodyhtml = bodyhtml + "<tr><td class='legend-color-guide'><div style='background-color: " + x.color + ";'></div></td><td class='key'>" + x.key + "</td><td class='value'>" + val + "</td></tr>";
                }

                bodyhtml = bodyhtml + "</tbody>";
                return "<table>" + headerhtml + '' + bodyhtml + "</table>";
            });
            chart.xAxis.tickFormat(XtickFormat);
            chart.showLegend(false),
                chart.useInteractiveGuideline(true); //pop up with all values on given X
            //chart.margin({top: 30, right: 20, bottom: 50, left: 100});//to avoid cut on Y coordinates
            chart.focusEnable(false); //no time zoom selection
            d3.select('#chart1')
                .datum(histcatexplong).transition().duration(500)
                .call(chart);
            nv.utils.windowResize(chart.update);
            return chart;
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#export_graph_data").click(function() {
            window.open("/damsv2/ajax/export-graph-data");
        });

        $("#export_stat_data").click(function() {
            window.open("/damsv2/ajax/export-summary-data");
        });
    });
</script>