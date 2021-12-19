<html>

<head>
    <style>
        .body {
            width: max-content;
            background-color: #424242;
        }
    </style>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        function DrawGraph() {
            $.getJSON('https://votapp.tk/index.php?r=site%2Fajaxdatachartmedia', function(dato) {
                google.charts.load("current", {
                    packages: ["corechart"]
                });

                var temp = new Array();
                temp[0] = ['title', 'votes'];
                for (var i = 0; i < dato.length; i++) {
                    temp[i + 1] = [dato[i].title, parseInt(dato[i].votes)];
                }

                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data = google.visualization.arrayToDataTable(temp);

                    var options = {
                        backgroundColor: '#424242',
                        height: 1000,
                        pieSliceTextStyle: {
                            color: 'whitesmoke'
                        },
                        titleTextStyle: {
                            color: 'whitesmoke'
                        },
                        legend: {
                            position: 'labeled',
                            textStyle: {
                                color: 'whitesmoke'
                            },
                        },
                        pieHole: 0.3,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
                    chart.draw(data, options);
                }
            });
            setTimeout(DrawGraph, 1000);
        }
    </script>

</head>

<body style="background-color: #424242">
    <script>
        $(document).ready(function() {
            DrawGraph();
        });
    </script>
    <div id="donutchart"></div>
</body>

</html>