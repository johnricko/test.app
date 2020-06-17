<?php

use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Apple test application';
?>
<div class="site-index">
    <div class="col-md-12">
        <? if (!empty($models)) { ?>
            <? $formatter = Yii::$app->formatter; ?>
            <? foreach ($models as $model) { ?>

                <?php
                // Выбираем цвет для яблока
                switch ($model->color->name) {
                    case 'Зеленое':
                        $color = 'text-success';
                        break;
                    case 'Желтое':
                        $color = 'text-warning';
                        break;
                    case 'Красное':
                        $color = 'text-danger';
                        break;
                }
                ?>

                <div class="well col-md-4">
                    <?= Html::tag('div', 'ID: ' . $model->id, ['class' => 'text-success']); ?>
                    <?= Html::tag('div', $model->health, ['class' => 'glyphicon glyphicon-heart text-danger']) ?>
                    <?= Html::tag('div', Html::tag('span', '', ['class' => 'glyphicon glyphicon-apple']) . $model->color->name, ['class' => $color]) ?>
                    <?= Html::tag('div', 'Время появления: ' . $formatter->asRelativeTime($model->created), ['class' => 'text-warning']) ?>
                    <?= Html::tag('div', $model->fallen === null ? 'Не упало еще' : 'Уже упало', ['class' => 'text-info']) ?>
            </div>
            <? } ?>
        <? } ?>
    </div>
</div>
