<?php


namespace app\models;


use Yii;
use yii\base\Model;
use yii\helpers\Url;

class Evento extends Model
{
    public $Id;
    public $Nome;
    public $Descrizione;
    public $Films;
    public $Codice;
    public $Inizio;
    public $Posizione;
    public $Fine;
    public $Visibilita;
    public $Winner;
    public $Colore;


    public function rules()
    {
        return [
            [['Id', 'Nome', 'Descrizione', 'Inizio', 'Posizione', 'Codice', 'Fine', 'Visibilita', 'Winner', 'Colore'], 'required'],
            // [['Id', 'Nome', 'Descrizione','Inizio','Fine'], 'required'],
            ['Codice', 'string', 'length' => 5]
        ];
    }

    public function attributeLabels()
    {
        return [
            'Id' => \Yii::t('app', 'Id'),
            'Nome' => \Yii::t('app', 'Nome'),
            'Descrizione' => \Yii::t('app', 'Descrizione'),
            'Inizio' => \Yii::t('app', 'Inizio'),
            'Posizione' => \Yii::t('app', 'Posizione'),
            'Codice' => \Yii::t('app', 'Codice Serata'),
            'Fine' => \Yii::t('app', 'Fine'),

        ];
    }

    public function Salva()
    {
        $quanti = Yii::$app->db->createCommand("Select Count(*) From Eventi Where Id=:Id LIMIT 1 ")->bindValue(':Id', $this->Id)->queryOne();
        if ($this->Id == -1 || $quanti['Count(*)'] == "0") {
            Yii::$app->db->createCommand("INSERT INTO `Eventi` ( `Nome`, `Descrizione`, Inizio,Posizione,Codice,Fine, Visibilita,Winner,Colore) VALUES ( :Nome, :Descrizione, :Inizio,:Posizione,:Codice,:Fine, :Visibilita,:Winner,:Colore)")
                ->bindValue(':Nome', $this->Nome)
                ->bindValue(':Inizio', $this->Inizio)
                ->bindValue(':Descrizione', $this->Descrizione)
                ->bindValue(':Posizione', $this->Posizione)
                ->bindValue(':Codice', $this->Codice)
                ->bindValue(':Fine', $this->Fine)
                ->bindValue(':Visibilita', $this->Visibilita)
                ->bindValue(':Winner', $this->Visibilita)
                ->bindValue(':Colore', $this->Visibilita)
                ->query();
            Yii::$app->session->setFlash('success', ("Evento <i>$this->Nome</i> creato con successo "));
            return true;
        } else {
            Yii::$app->db->createCommand("UPDATE `Eventi` Set  Nome = :Nome,Descrizione=:Descrizione,Inizio= :Inizio,Posizione=:Posizione,Codice=:Codice,Fine=:Fine,Visibilita = :Visibilita,Winner=:Winner,Colore=:Colore WHERE `Id` = :Id")
                ->bindValue(':Nome', $this->Nome)
                ->bindValue(':Inizio', $this->Inizio)
                ->bindValue(':Descrizione', $this->Descrizione)
                ->bindValue(':Id', $this->Id)
                ->bindValue(':Posizione', $this->Posizione)
                ->bindValue(':Codice', $this->Codice)
                ->bindValue(':Fine', $this->Fine)
                ->bindValue(':Visibilita', $this->Visibilita)
                ->bindValue(':Winner', $this->Visibilita)
                ->bindValue(':Colore', $this->Visibilita)
                ->query();
            Yii::$app->session->setFlash('success', ("Evento <i>$this->Nome</i> modificato con successo"));
            return true;
        }
        return false;
    }

    public function isAccavallato()
    {
        $quanti = Yii::$app->db->createCommand("Select * 
            From Eventi 
            where( (Id != :Id) 
                and ((Inizio > :StartDate and Inizio < :EndDate)
                OR (Fine > :StartDate and Fine < :EndDate)
                OR (Inizio < :StartDate and Fine >:EndDate))
            )")
            ->bindValue(':Id', $this->Id)
            ->bindValue(':StartDate', $this->Inizio)
            ->bindValue(':EndDate', $this->Fine)
            ->queryOne();
//        if ($quanti) \Yii::$app->session->setFlash('error', 'Controlla gli orari: Probabilmente stai sovraponendo gli eventi');
        return $quanti != 0;
    }

    public static function getQueryById($Id)
    {
        return Yii::$app->db->createCommand("Select * From Eventi Where Id=:Id ")->bindValue(':Id', $Id)->queryOne();
    }

    public static function getNomeInizio()
    {
        return Yii::$app->db->createCommand("Select Nome,Inizio From Eventi ")->queryAll();
    }

    public static function getAll()
    {
        return Yii::$app->db->createCommand("Select * From Eventi ")->queryAll();
    }

    public static function getAllforSelect2()
    {
        $eventi = Yii::$app->db->createCommand("Select Id,Nome From Eventi ")->queryAll();
        $data = [];
        foreach ($eventi as $item) {
            $data[$item['Id']] = $item['Nome'];
        }
        return $data;
    }
    public static function getAllforSelect2Index()
    {
        $eventi = Yii::$app->db->createCommand("Select Inizio,Nome From Eventi ")->queryAll();
        $data = [];
        foreach ($eventi as $item) {
            $data[$item['Inizio']] = $item['Nome'];
        }
        return $data;
    }

    public static function getById($Id)
    {
        $model = new Evento();
        $queryResult = self::getQueryById($Id);
        if ($queryResult) {
            foreach ((array)self::getQueryById($Id) as $key => $value) {
                $model[$key] = $value;
            }
            $model->Films = Film::getByEvento($Id);
            return $model;
        }
        return new Evento();
    }


    public static function getEventoGiorno($Giorno = false)
    {
        if (!$Giorno) {
            $Giorno = date_create('NOW', new \DateTimeZone('Europe/Rome'))->format('Y-m-d H:i:s');
        }
//        return Yii::$app->db->createCommand("SELECT Eventi.*,Location.Indirizzo From Eventi inner join Location on Location.Id = Posizione where (Inizio) <= STR_TO_DATE(:Giorno,'%d-%m-%Y %H:%i:%s') and Fine >= STR_TO_DATE(:Giorno,'%d-%m-%Y %H:%i:%s') and Visibilita = 1 Order by Inizio desc LIMIT 1")->bindValue(':Giorno', $Giorno)->queryOne();

        return Yii::$app->db->createCommand("SELECT Eventi.*,Location.Indirizzo From Eventi inner join Location on Location.Id = Posizione where date(Inizio) <= date(:Giorno) and date(Fine)>=date(:Giorno) AND Visibilita = 1 Order by Inizio desc LIMIT 1")->bindValue(':Giorno', $Giorno)->queryOne();
    }


    public static function getProssimoEvento($Giorno = false)
    {
//        try {
//            $Giorno = date_create_from_format('Y-m-d',$Giorno)->format('d-m-Y H:i:s');
//        }
//        catch (exception $e) {
//            $Giorno = date_create('NOW', new \DateTimeZone('Europe/Rome'))->format('d-m-Y H:i:s');
//        }
        if (!$Giorno) {
            $Giorno = date_create('NOW', new \DateTimeZone('Europe/Rome'))->format('d-m-Y H:i:s');
        }else{
            $Giorno = date_create_from_format('Y-m-d',$Giorno)->format('d-m-Y H:i:s');
        }
//        var_dump($Giorno);echo "<br>";
        return Yii::$app->db->createCommand("SELECT
    Eventi.*,
    Location.Indirizzo
FROM
    Eventi
INNER JOIN
    Location
ON
    Location.Id = Posizione
WHERE
    (
        Inizio > STR_TO_DATE(:Giorno, '%d-%m-%Y') OR(
            DATE(Inizio) <= DATE(
                STR_TO_DATE(:Giorno, '%d-%m-%Y')
            ) AND DATE(Fine) >= DATE(
                STR_TO_DATE(:Giorno, '%d-%m-%Y')
            )
        )
    ) AND Visibilita = 1
ORDER BY
    Inizio
LIMIT 1")->bindValue(':Giorno', $Giorno)->queryOne();
    }

//    public static function getUltimoFilmEvento()
//    {
//        return Yii::$app->db->createCommand("SELECT * From Film,Eventi where Eventi.Id=IdEvento and DAYOFYEAR(Eventi.Inizio) >= DAYOFYEAR(NOW()) Order by Ordine LIMIT 1")->queryOne();
//    }


    public static function getStatisticheFilmByIdEvento($Id)
    {
        return Yii::$app->db->createCommand("SELECT sum(Voto) as votes,Film.Titolo as title FROM Voti JOIN Film on Film.Id=Voti.IdFilm WHERE IdEvento like :IdEvento GROUP BY IdFilm ")->bindValue(':IdEvento', $Id)->queryAll();

        return Evento::getById($Id)->getStatisticheFilm();
    }

    public static function getStatisticheMediaByIdEvento($Id)
    {
        return Yii::$app->db->createCommand("SELECT sum(Voto)/count(Voto) as votes,Film.Titolo as title FROM Voti JOIN Film on Film.Id=Voti.IdFilm WHERE IdEvento like :IdEvento GROUP BY IdFilm ")->bindValue(':IdEvento', $Id)->queryAll();
    }


    public static function esisteCodiceevento($c)
    {
        $r = Yii::$app->db->createCommand("Select count(*) From Eventi where Codice like :Codice")->bindValue(':Codice', $c)->queryOne();

        return $r['count(*)'];
    }

    public static function controllaCodice($Codice)
    {
        $evento = Evento::getEventoGiorno();
        $giorno = date_create('NOW', new \DateTimeZone('Europe/Rome'))->format('d-m-Y');
        echo($evento['Codice'] == $Codice && false);
    }

    public static function disegna($model, $evidenzia = false)
    {
        ?>
        <div class="gallery-space-item <?= $evidenzia ? 'pulse' : '' ?>">
            <a href="<?= Url::toRoute(['gestioneevento', 'Id' => $model['Id']]) ?>">
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
                        <div class="titlee col-md-2 ">
                            <p><?= date("d-m-Y", strtotime($model['Inizio'])) ?></p>
                        </div>
                        <div class="title col-md-8">
                            <h3><?= $model['Nome'] ?></h3>
                            <p>Trama: <?= $model['Descrizione'] ?></p>
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
