<style>
    h1 {
        margin: 10px;
        font-size: 100px;
    }
</style>
    <script>
        function codice() {
            var idMobile = JSON.parse(<?php echo "'" . $lista . "'" ?>);
            document.getElementById("codicePosizione6").innerHTML = idMobile[Math.floor(Math.random() * idMobile.length)].IdMobile[5] + "</h1>";
            cont += 1;
            alert(cont);
            if (cont < 10) {
                setTimeout(Estrazione6, 5);
            } else {
                cont = 0;
                Estrazione3();
            }
            var idMobile = JSON.parse(<?php echo "'" . $lista . "'" ?>);
            document.getElementById("codicePosizione3").innerHTML = idMobile[Math.floor(Math.random() * idMobile.length)].IdMobile[2] + "</h1>";
            cont++;
        }
        if (cont < 10) {
            setTimeout(Estrazione3, 5);
        } else {
            cont = 0;
            Estrazione5();
        }
        var idMobile = JSON.parse(<?php echo "'" . $lista . "'" ?>);
        document.getElementById("codicePosizione5").innerHTML = idMobile[Math.floor(Math.random() * idMobile.length)].IdMobile[4] + "</h1>";
        cont++;
        }
        if (cont < 10) {
            setTimeout(Estrazione5, 5);
        } else {
            cont = 0;
            Estrazione2();
        }
        var idMobile = JSON.parse(<?php echo "'" . $lista . "'" ?>);
        document.getElementById("codicePosizione2").innerHTML = idMobile[Math.floor(Math.random() * idMobile.length)].IdMobile[1] + "</h1>";
        cont++;
        }
        if (cont < 10) {
            setTimeout(Estrazione2, 5);
        } else {
            cont = 0;
            Estrazione8();
        }
        var idMobile = JSON.parse(<?php echo "'" . $lista . "'" ?>);
        document.getElementById("codicePosizione8").innerHTML = idMobile[Math.floor(Math.random() * idMobile.length)].IdMobile[7] + "</h1>";
        cont++;
        }
        if (cont < 10) {
            setTimeout(Estrazione8, 5);
        } else {
            cont = 0;
            Estrazione();
        }
        var idMobile = JSON.parse(<?php echo "'" . $lista . "'" ?>);
        document.getElementById("codicePosizione1").innerHTML = idMobile[Math.floor(Math.random() * idMobile.length)].IdMobile[0] + "</h1>";
        cont++;
        if (cont < 10) {
            setTimeout(Estrazione6, 5);
        }
        }
        }
    </script>
<div>
    <button type="button" class="btn btn-default btn-lg btn-block" onclick="codice()">GENERA CODICE VINCITORE
    </button>
    <div class="row" style="margin-top: 25%">
        <div class="col-sm-3"></div>
        <div class="col-sm-8" style="display: flex; flex-direction: row;margin: auto;">
            <h1 id="codicePosizione1" align="center">0</h1>
            <h1 id="codicePosizione2" align="center">0</h1>
            <h1 id="codicePosizione3" align="center">0</h1>
            <h1 id="codicePosizione4" align="center">0</h1>
            <h1 id="codicePosizione5" align="center">0</h1>
            <h1 id="codicePosizione6" align="center">0</h1>
            <h1 id="codicePosizione7" align="center">0</h1>
            <h1 id="codicePosizione8" align="center">0</h1>
            <?php
            echo( $winner);
            ?>
        </div>
    </div>
    </h1>
</div>