<?php

namespace app\models;


use Yii;
use yii\base\Model;
use yii\helpers\Url;

class Location extends Model
{
    public $Id;
    public $Nome;
    public $Indirizzo;

    public $coordinates;

    public function rules()
    {
        return [
            [['Id', 'Nome', 'Indirizzo'], 'required'],
            //  [['Id', 'Nome'], 'required'],
        ];
    }

    public function Salva()
    {

//        $c = explode($this->coordinates, ',');
//        $this->Latitudine = $c[0];
//        $this->Longitudine = $c[1];
        $quanti = Yii::$app->db->createCommand("Select Count(*) From Location Where Id=:Id LIMIT 1 ")->bindValue(':Id', $this->Id)->queryOne();
        if ($this->Id == -1 || $quanti['Count(*)'] == "0") {
            Yii::$app->db->createCommand("INSERT INTO `Location` ( `Nome`, `Indirizzo`) VALUES ( :Nome, :Indirizzo)")
                ->bindValue(':Nome', $this->Nome)
                ->bindValue(':Indirizzo', $this->Indirizzo)
                ->query();
            Yii::$app->session->setFlash('success', ("Location <i>$this->Nome</i> creata con successo "));

//            var_dump($this);
//            die("In");
            return true;
        } else {
            //echo "<pre>";
            Yii::$app->db->createCommand("UPDATE `Location` Set  Nome = :Nome,Nome=:Nome,Indirizzo= :Indirizzo WHERE `Id` = :Id")
                ->bindValue(':Nome', $this->Nome)
                ->bindValue(':Indirizzo', $this->Indirizzo)
                ->bindValue(':Id', $this->Id)
                ->query();
            Yii::$app->session->setFlash('success', ("Location <i>$this->Nome</i> modificata con successo "));

            // var_dump($this);
            //die("Update");
            return true;
        }
        return false;
    }

    public static function getQueryById($Id)
    {
        return Yii::$app->db->createCommand("Select * From Location Where Id=:Id ")->bindValue(':Id', $Id)->queryOne();
    }

    public static function getAllforSelect2()
    {
        $eventi = Yii::$app->db->createCommand("Select Id,Nome From Location ")->queryAll();
        $data = [];
        foreach ($eventi as $item) {
            $data[$item['Id']] = $item['Nome'];
        }
        return $data;
    }

    public static function getById($Id)
    {
        $model = new Location();
        $queryResult = self::getQueryById($Id);
        if ($queryResult) {
            foreach ((array)self::getQueryById($Id) as $key => $value) {
                $model[$key] = $value;
            }
            return $model;
        }
        return new Location();
    }

    public static function disegna($model)
    {
        ?>
        <div class="gallery-space-item">
            <a href="<?= Url::toRoute(['site/gestionelocation', 'Id' => $model['Id']]) ?>">
                <div class="sub-gallery-text" style="
                        background:
                        linear-gradient(
                        rgba(0, 0, 0, 0.65),
                        rgba(0, 0, 0, 0.5));
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
                        <div class="title col-md-8 col-md-offset-2">
                            <h3><?= $model['Nome'] ?></h3>
                            <p>Indirizzo: <?= $model['Indirizzo'] ?></p>
                        </div>
                    </div>
                    <div style="flex-grow: 3;"></div>
                    <div class="row">
                        <!-- <div class="titlee col-md-2 ">
                            <p><?/*= $model['Proiezione'] */ ?></p>
                        </div>
                        <div class="titlee col-md-2 col-md-offset-8">
                            <p>Durata: <?/*= $model['Durata'] */ ?></p>
                        </div>-->
                    </div>

                </div>
            </a>

        </div>
        <?php

    }
}