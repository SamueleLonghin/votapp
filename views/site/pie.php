<?php
use yii\helpers\Url;
?>
<style>
    rect {
        fill: #424242;
    }

    /*.switch {*/
    /*    position: relative;*/
    /*    display: inline-block;*/
    /*    width: 60px;*/
    /*    height: 34px;*/
    /*}*/

    /*.switch input {*/
    /*    opacity: 0;*/
    /*    width: 0;*/
    /*    height: 0;*/
    /*}*/

    /*.slider {*/
    /*    position: absolute;*/
    /*    cursor: pointer;*/
    /*    top: 0;*/
    /*    left: 0;*/
    /*    right: 0;*/
    /*    bottom: 0;*/
    /*    background-color: #ccc;*/
    /*    -webkit-transition: .4s;*/
    /*    transition: .4s;*/
    /*}*/

    /*.slider:before {*/
    /*    position: absolute;*/
    /*    content: "";*/
    /*    height: 26px;*/
    /*    width: 26px;*/
    /*    left: 4px;*/
    /*    bottom: 4px;*/
    /*    background-color: white;*/
    /*    -webkit-transition: .4s;*/
    /*    transition: .4s;*/
    /*}*/

    /*input:checked+.slider {*/
    /*    background-color: #2196F3;*/
    /*}*/

    /*input:focus+.slider {*/
    /*    box-shadow: 0 0 1px #2196F3;*/
    /*}*/

    /*input:checked+.slider:before {*/
    /*    -webkit-transform: translateX(26px);*/
    /*    -ms-transform: translateX(26px);*/
    /*    transform: translateX(26px);*/
    /*}*/

    /*!* Rounded sliders *!*/
    /*.slider.round {*/
    /*    border-radius: 34px;*/
    /*}*/

    /*.slider.round:before {*/
    /*    border-radius: 50%;*/
    /*}*/

    /*.label {*/
    /*    margin-left: 45%;*/
    /*}*/
</style>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    function DrawGraphSomma() {
        $.getJSON("<?=Url::toRoute ('ajaxdatachart')?>&Id=-1", function (dato) {
        //$.getJSON('https://votapp.space/index.php?r=site%2Fajaxdatachart&Id=<?//= $Id ?>//', function (dato) {
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
                    height: 800,
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

                var chart = new google.visualization.PieChart(document.getElementById('donutchartSomma'));
                chart.draw(data, options);
            }
        });
        setTimeout(DrawGraphSomma, 1000);
    }

    function DrawGraphMedia() {
        //$.getJSON('https://votapp.space/index.php?r=site%2Fajaxdatachartmedia&Id=<?//= $Id ?>//', function (dato) {
        $.getJSON("<?=Url::toRoute ('ajaxdatachartmedia')?>&Id=-1", function (dato) {
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
                    height: 800,
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

                var chart = new google.visualization.PieChart(document.getElementById('donutchartMedia'));
                chart.draw(data, options);
            }
        });
        setTimeout(DrawGraphMedia, 1000);
    }
</script>

<body>
    <span class="glyphicon glyphicon-fullscreen" id="fullScreen"></span>
    <label class="switch">
        <input type="checkbox" id="check" onclick="CheckBox()" checked="true">
        <span class="slider round"></span>
    </label>
    <script>
        $(document).ready(function () {
            CheckBox();
        });

        function CheckBox() {
            if (document.getElementById("check").checked == true) {
                document.getElementById("donutchartMedia").className = "collapse";
                document.getElementById("donutchartSomma").className = "";
                DrawGraphSomma();
            } else {
                document.getElementById("donutchartSomma").className = "collapse";
                document.getElementById("donutchartMedia").className = "";
                DrawGraphMedia();
            }
        }

        jQuery("#fullscreen").click(function () {
            var win = window.open();
            win.location = "<?=Url::toRoute ('piefullscreen').'&Id='.$Id?>";
            // win.location = 'https://votapp.space/index.php?r=site%2Fpiefullscreen';
            win.opener = null;
            win.blur();
            window.focus();
        });
    </script>
    <div id="donutchartSomma"></div>
    <div id="donutchartMedia"></div>
</body>