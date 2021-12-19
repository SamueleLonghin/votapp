<?php
use yii\helpers\Html;
use app\models\LoginForm;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use kartik\datetime\DateTimePicker;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\file\FileInput;
use kartik\widgets\SwitchInput;
?>
<head>
    <style>
        rect {
            fill: #424242;
        }

        text {
        }
    </style>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

        aggiornamentoSomma()


        function aggiornamentoSomma() {
            $.getJSON('https://votapp.tk/index.php?r=site%2Fajaxdatachart', function (dato) {
                google.charts.load('current', {
                    'packages': ['corechart']
                });


                var temp = new Array();
                temp[0] = ['title', 'votes'];
                for (var i = 0; i < dato.length; i++) {
                    temp[i + 1] = [dato[i].title, parseInt(dato[i].votes)];
                }


                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {

                    var data = google.visualization.arrayToDataTable(temp);
                    let options = {
                        width: 900,
                        height: 700,
                        backgroundColor: '#424242',
                        color: 'white',
                        legend: {
                            textStyle: {
                                color: 'whitesmoke'
                            }
                        },
                        is3D: false
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                    chart.draw(data, options);
                }
            });
            setTimeout(aggiornamento, 1000);
        }


        function aggiornamentoMedia() {
            $.getJSON('https://votapp.tk/index.php?r=site%2Fajaxdatachart', function (dato) {
                google.charts.load('current', {
                    'packages': ['corechart']
                });


                var temp = new Array();
                temp[0] = ['title', 'votes'];
                for (var i = 0; i < dato.length; i++) {
                    temp[i + 1] = [dato[i].title, parseInt(dato[i].votes)];
                }


                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {

                    var data = google.visualization.arrayToDataTable(temp);
                    let options = {
                        width: 900,
                        height: 700,
                        backgroundColor: '#424242',
                        color: 'white',
                        legend: {
                            textStyle: {
                                color: 'whitesmoke'
                            }
                        },
                        is3D: false
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                    chart.draw(data, options);
                }
            });
            setTimeout(aggiornamento, 1000);
        }
    </script>

</head>

<body>

<?=
 SwitchInput::widget(['name'=>'status_1', 'value'=>true,],'pluginEvents' => [
    'change' => 'function(event) {
                alert("File changed");
            }'
]);
?>
    <h1>Voti</h1>
    <div id="piechart" style="width: 900px; height: 700px;"></div>
</body>