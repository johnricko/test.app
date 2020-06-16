<?php

namespace backend\models;

use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "colors".
 *
 * @property int $id
 * @property string $name
 *
 * @property Apples[] $apples
 */
class Colors extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'colors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Apples]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApples()
    {
        return $this->hasMany(Apples::className(), ['color_id' => 'id']);
    }
}
