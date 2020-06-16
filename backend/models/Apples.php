<?php

namespace backend\models;

use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use Throwable;

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

    // Будет съедено на процент %
    public function EatApple($percent)
    {
        $health = $this->health;
        $this->health = $health - ($health / 100 * $percent);
        $this->save();

    }

    // Падает на землю
    public function FallToGround()
    {
        $status = Status::find()->where(['name' => 'Упавшее'])->one();
        $this->status = $status->id;
        $this->fallen = time();
        $this->save();
    }

    // Возвращает статус - на дереве висит или нет
    public function OnTree()
    {
        $status = Status::find()->where(['name' => 'Висит на дереве'])->one();
        if ($this->status === $status->name) {
            return true;
        } else {
            return false;
        }
    }

    // Удаление яблока из базы когда съедено
    public function DeleteApple()
    {
        if ($this->health === 0) {
            try {
                $this->delete();
            } catch (StaleObjectException $e) {
            } catch (Throwable $e) {
            }
            return true;
        } else {
            return false;
        }
    }
}
