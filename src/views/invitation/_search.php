<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\invitations\views\invitation
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use \open20\amos\invitations\Module;

/**
* @var yii\web\View $this
* @var \open20\amos\invitations\models\search\InvitationSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="invitation-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><h2>Cerca per:</h2></div>

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);

    echo Html::hiddenInput("enableSearch", "1");
    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));
    ?>


    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'name') ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'surname') ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'email') ?>
    </div>
<!--    <div class="col-sm-6 col-lg-4">    -->
<!--        --><?php //echo $form->field($model, 'message') ?>
<!--    </div>-->
<!--    <div class="col-sm-6 col-lg-4">    -->
<!--        --><?php //echo $form->field($model, 'send_time') ?>
<!--    </div>    -->
    <?php // echo $form->field($model, 'send') ?>

    <?php // echo $form->field($model, 'invitation_user_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(Module::t('amosinvitations', 'Annulla'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Module::t('amosinvitations', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
<!--a><p class="text-center">Ricerca avanzata<br>
            < ?=AmosIcons::show('caret-down-circle');?>
        </p></a-->
    <?php ActiveForm::end(); ?>

</div>
