<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\invitations
 * @category   CategoryName
 */

use lispa\amos\core\forms\ActiveForm;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\core\views\DataProviderView;
use lispa\amos\invitations\Module;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \lispa\amos\invitations\models\search\InvitationSearch $model
 */

$this->title = Yii::t('amosinvitations', 'My invitations');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="invitation-index">
    <h4><?= Module::t('amosinvitations', '#introduction_invitation') ?></h4>
    <?= $this->render('_search', ['model' => $model,]); ?>

    <?php
    $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]);
    ?>

    <?= $this->render('_modal') ?>

    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'name',
                'surname',
                'invitationUser.email',
                'send_time:datetime',
                [
                    'class' => 'lispa\amos\core\views\grid\ActionColumn',
                    'template' => '{view}{update}{delete}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            /** @var \lispa\amos\invitations\models\Invitation $model */
                            $btn = Html::a(
                                AmosIcons::show('email'),
                                ['update', 'id' => $model->id],
                                [
                                    'model' => $model,
                                    'class' => 'btn btn-tools-secondary',
                                    'title' => Yii::t('amosinvitations', 'Update and send invitation...'),
                                ],
                                true
                            );
                            return $btn;
                        }
                    ]
                ],
            ],
        ],
    ]); ?>

    <?php ActiveForm::end(); ?>
</div>
