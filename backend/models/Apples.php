<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "apples".
 *
 * @property int $id
 * @property int $color_id
 * @property int $status_id
 * @property int $health
 * @property int $created
 * @property int $fallen
 *
 * @property Colors $color
 * @property Status $status
 */
class Apples extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apples';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['color_id', 'status_id', 'health', 'created', 'fallen'], 'required'],
            [['color_id', 'status_id', 'health', 'created', 'fallen'], 'integer'],
            [['color_id'], 'exist', 'skipOnError' => true, 'targetClass' => Colors::className(), 'targetAttribute' => ['color_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color_id' => 'Color ID',
            'status_id' => 'Status ID',
            'health' => 'Health',
            'created' => 'Created',
            'fallen' => 'Fallen',
        ];
    }

    /**
     * Gets query for [[Color]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(Colors::className(), ['id' => 'color_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }
}
