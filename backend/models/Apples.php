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
            [['fallen'], 'default', 'value' => null],
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

    public function init()
    {
        if ($this->isNewRecord) {
            $this->color_id = $this->choiceColor();
        }
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
        $this->status_id = $status->id;
        $this->fallen = time();
        $this->save();
    }

    // Возвращает статус - на дереве висит или нет
    public function OnTree()
    {
        $status = Status::find()->where(['name' => 'Висит на дереве'])->one();
        if ($this->isNewRecord) {
            return $status->id;
        }
        if ($this->status_id === $status->id) {
            return true;
        } else {
            return false;
        }
    }

    // Создание яблок в случайном количестве
    public function CreateApples()
    {
        $rand = Rand(1, 5);

        for ($i = 0; $i < $rand; $i++) {
            $model = new Apples();
            $model->health = 1;
            $model->status_id = $this->OnTree();

            // Случайное время появления, но не более 5,5 часов
            $time = time() + (rand(-20000, 0));

            $model->created = $time;
            $model->fallen = null;

            if ($model->save(false)) {

            } else {
                print_r($model->errors);
            }

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

    // Выбрать случайный цвет из базы цветов
    public function choiceColor()
    {
        //$this->color_id = Colors::find()->orderBy('RAND()')->limit(1)->one();
        $min = Colors::find()->min('id');
        $max = Colors::find()->max('id');
        $rand = Rand($min, $max);
        return Colors::find()->where(['in', 'id', $rand])->one()->id;
    }

    // Проверка условий задачи
    public function checkApple($id, $action, $percent = null)
    {
        //$array_stats = [];
        $model = $this::find()->where(['id' => $id])->one();
        switch ($action) {
            case 'Fall':
                $this->FallToGround();
                break;
            case 'Eat':
                if ($model->fallen !== null) {
                    $this->EatApple($percent);
                }
                break;
            case 'Bad':
                break;

        }


    }
}
