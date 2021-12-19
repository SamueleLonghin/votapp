<?php


namespace app\models;


use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;

class Voto extends Model
{
    public $IdFilm;
    public $Voto;
    public $IdMobile;
    public $Password;
    public $Codice;

    public function rules()
    {
        return [
            [['IdFilm', 'Voto', 'IdMobile', 'Password', 'Codice'], 'safe'],
            [['IdFilm', 'Voto', 'IdMobile', 'Password', 'Codice'], 'required'],

        ];
    }

//    public function AddVotoDatabase($check)
//    {
//        var_dump($check);
//        if ($check == false) {
//            Yii::$app->db->createCommand("INSERT INTO `Voti` (`IdFilm`, `Voto`, `IdMobile`) VALUES (:IdFilm, :Voto, :IdMobile)")->bindValue(':IdFilm', $this->IdFilm)->bindValue(':Voto', $this->Voto)->bindValue(':IdMobile', $this->IdMobile)->query();
//        } else {
//            //Yii::$app->db->createCommand("INSERT INTO `Voti` (`IdFilm`, `Voto`, `IdMobile`) VALUES (:IdFilm, :Voto, :IdMobile)")->bindValue(':IdFilm', $this->IdFilm)->bindValue(':Voto', $this->Voto)->bindValue(':IdMobile', $this->IdMobile)->query();
//        }
//    }

    public function Salva()
    {
        $film = Yii::$app->db->createCommand("Select IdEvento From Film Where Id=:IdFilm")->bindValue(':IdFilm', $this->IdFilm)->queryOne();
        $Evento = Yii::$app->db->createCommand("Select * From Eventi Where Codice like :Codice")->bindValue(':Codice', $this->Codice)->queryOne();
        date_default_timezone_set('Europe/Rome');
        $time = new \DateTime('now', new \DateTimeZone('Europe/Rome'));
        //var_dump($film);die();
        $inizioEvento = new \DateTime($Evento['Inizio']);
        $fineEvento = new \DateTime($Evento['Fine']);
        if ($time <= $inizioEvento) {
            echo "Evento non ancora iniziato, quindi non votabile";
            return true;
        }
        if ($time > $fineEvento) {
            echo "Evento finito, quindi non votabile";
            return true;
        }
//        echo"<pre>";
//        var_dump($Evento);
//        var_dump($film);
//        var_dump(Evento::getEventoGiorno());
//        var_dump($this);
//        die();
        if ($Evento['Id'] != $film['IdEvento'] || Evento::getEventoGiorno()['Codice'] != $this->Codice) {
            echo "C'è un problema nel codice che hai inserito, rieffettua il log-in";
            return true;
        }
        $quanti = Yii::$app->db->createCommand("Select Count(*) From Voti Where IdFilm=:IdFilm and IdMobile like :IdMobile")->bindValue(':IdFilm', $this->IdFilm)->bindValue(':IdMobile', $this->IdMobile)->queryOne();
        if ($quanti['Count(*)'] == "0") {
            Yii::$app->db->createCommand("INSERT INTO `Voti` (`IdFilm`, `Voto`, `IdMobile`) VALUES (:IdFilm, :Voto, :IdMobile)")->bindValue(':IdFilm', $this->IdFilm)->bindValue(':Voto', $this->Voto)->bindValue(':IdMobile', $this->IdMobile)->query();
            echo "Voto salvato: " . $this->Voto . "/5";
            return true;
        } else {
            Yii::$app->db->createCommand("UPDATE `Voti` Set  `Voto` = :Voto Where IdFilm=:IdFilm and IdMobile=:IdMobile")->bindValue(':IdFilm', $this->IdFilm)->bindValue(':Voto', $this->Voto)->bindValue(':IdMobile', $this->IdMobile)->query();
            echo "Voto aggiornato: " . $this->Voto . "/5";
            return true;
        }
        return false;
    }

    public static function estrai()
    {
        $evento = Evento::getEventoGiorno();
        if($evento['Winner']){
            return $evento['Winner'];
        }
        if (Yii::$app->user->isGuest) {
            return false;
        }
        $estratto = Yii::$app->db->createCommand("      SELECT * FROM Voti ORDER BY RAND() LIMIT 1")->queryOne();
        Yii::$app->db->createCommand("UPDATE `Eventi` Set  `Winner` = :W Where Id=:Identificativo")->bindValue(':W', $estratto['IdMobile'])->bindValue(':Identificativo', $evento['Id'])->query();
        return $estratto['IdMobile'];
    }

    public static function getIMEIAll()
    {
        $eventogiorno = Evento::getEventoGiorno();
        return Yii::$app->db->createCommand("SELECT IdMobile From Voti INNER JOIN Film on Film.Id =IdFilm where IdEvento=:IdEvento")->bindValue('IdEvento', $eventogiorno['Id'])->queryAll();

    }

    public static function getAll()
    {
        return Yii::$app->db->createCommand("SELECT * From Voti INNER JOIN Film on Film.Id =IdFilm")->queryAll();

    }

    public static function disegnaPiccolo($model)
    {
        //var_dump($model);die();
        ?>
        <div class="gallery-space-item">
            <div class="sub-gallery-text" style="
                    background:
                    linear-gradient(
                    rgba(0, 0, 0, 0.65),
                    rgba(0, 0, 0, 0.5)),
                    url('<?= $model['Image'] ?>');
                    background-size: cover;
                    background-repeat: no-repeat;
                    background-position: center;

                    display: flex;
                    flex-direction: column;
                    background-position-x: center !important;
                    background-position-y: center;
                    /*display: flex;*/
                    /*flex-direction: column;*/
                    ">
                <div class="row">
                    <div class="titlee col-md-2 " style="display:none;">
                        <p></p>
                    </div>
                    <div class="title col-md-7  col-md-offset-2"><!--   col-md-offset-2-->
                        <h3><?= $model['Titolo'] ?></h3>
                        <H4>Un film di: <?= $model['Autore'] ?></H4>
                    </div>
                    <div class="titlee col-md-2 " style="display:none;">
                        <p></p>
                    </div>
                    <div class="col-md-1 col-md-offset-2">
                        <a
                                href="<?= Url::toRoute(['site/inseriscifilm', 'Id' => $model['Id']]) ?>"
                                style="height: 100%;"
                                class="btn btn-default"
                        >
                                    <span class="glyphicon glyphicon-edit">
                                    </span>
                        </a>
                        <?= Html::a('<span class="glyphicon glyphicon-trash">
                                    </span>', ['delatefilm', 'Id' => $model['Id']], [
                            'class' => '  btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </div>
                </div>

            </div>
        </div>
        <?php
    }
}