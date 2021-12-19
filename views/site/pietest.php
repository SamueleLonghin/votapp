<style>
    .box {
        background-color: #212121;
        width: 400px;
        border: 15px #a1dff4;
        padding: 50px;
        margin: 20px;
        margin-left: 34%;
    }
</style>

<body>
    <script>
        $(document).ready(function() {
            DownloadChart();
        });

        function DownloadChart() {
            var tempOld = $.getJSON('https://votapp.tk/index.php?r=site%2Fajaxdatachartmedia', function(data) {

                var temp = ParseArray(data);

                temp.sort().reverse();

                for (var i = 0; i < temp.length; i++) {
                    document.getElementById("body").innerHTML += '<div class="box">' + '<h2 align="center">' + temp[i][1] + '</h2><br><h2 align="center">' + temp[i][0] + '</h2></div><br>';
                }
            });
            var oldTemp = ParseArray(tempOld.responseText);
        }


        function ParseArray(array) {
            console.log(array)
            var temp = new Array();
            for (var i = 0; i < array.length; i++) {
                temp[i] = [parseInt(array[i].votes), array[i].title];
            }
            return temp;
        }
    </script>

    <body>
        <p id="body">
        </p>
    </body>