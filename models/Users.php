<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $apellido
 * @property int $dni
 * @property int|null $edad
 * @property string|null $email
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dni'], 'required'],
            [['dni', 'edad'], 'integer'],
            [['nombre', 'apellido'], 'string', 'max' => 75],
            [['email'], 'email'],
            [['dni'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'dni' => 'Dni',
            'edad' => 'Edad',
            'email' => 'Email',
        ];
    }
}
