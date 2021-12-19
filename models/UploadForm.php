<?php


namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;


/**
 * UploadForm is the model behind the upload form.
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile file attribute
     */
    public $image;

    public function rules()
    {
        return [
            [['image'], 'safe'],
            [['image'], 'file', 'extensions'=>'jpg, gif, png'],
        ];
    }
}