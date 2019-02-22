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
use lispa\amos\invitations\models\Invitation;
use lispa\amos\invitations\Module;
use lispa\amos\invitations\utility\InvitationsUtility;
use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \lispa\amos\invitations\models\search\InvitationSearch $model
 */

$this->title = Module::t('amosinvitations', 'All invitations');
$this->params['breadcrumbs'][] = $this->title;

$js = <<<JS
 //--- SHOW the modal
    $(document).on('click','.re-send', function() {
        $('#modal_sent').modal('show')
            .find('#modal_content')
            .load($(this).attr('value'));
    });

JS;

$this->registerJs($js);
?>

<div class="invitation-index">
    <h4><?= Module::t('amosinvitations', '#introduction_invitation') ?></h4>
    <br>
    <?php
    echo $this->render('_search', [
        'model' => $model,
    ]); ?>

    <?php
    $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]);
    ?>
    <?= $this->render('_modal') ?>

    <?php
    try {
        echo DataProviderView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $model,
            'currentView' => $currentView,
            'gridView' => [
                'columns' => [
                    [
                        'class' => 'lispa\amos\core\views\grid\CheckboxColumn',
                        'name' => 'Invitation[selection]',
                        'header' => Module::t('amosinvitations', 'Selection'),
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                            /** @var Invitation $model */
                            if ($model->send) {
                                $retArray = InvitationsUtility::checkUserAlreadyPresent($model->invitationUser->email, true, true);
                                if ($retArray['present']) {
                                    return [
                                        'disabled' => true,
                                        'title' => $retArray['message'],
                                    ];
                                } else {
                                    return [
                                        'disabled' => true,
                                        'title' => Module::t('amosinvitations', 'Invitation already sended'),
                                    ];
                                }
                            } else {
                                $retArray = InvitationsUtility::checkUserAlreadyPresent($model->invitationUser->email, true, true);
                                if ($retArray['present']) {
                                    return [
                                        'disabled' => true,
                                        'title' => $retArray['message'],
                                    ];
                                }
                            }
                        }
                    ],
                    'name',
                    'surname',
                    'invitationUser.email',
                    'send_time:datetime',
                    'createdUserProfile.nomeCognome',
                    [
                        'class' => 'lispa\amos\core\views\grid\ActionColumn',
                        'template' => '{view}{update}{re-send}{delete}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                /** @var \lispa\amos\invitations\models\Invitation $model */
                                if (!$model->send) {
                                    $btn = Html::a(
                                        AmosIcons::show('email'),
                                        ['update', 'id' => $model->id],
                                        [
                                            'model' => $model,
                                            'class' => 'btn btn-tools-secondary',
                                            'title' => Module::t('amosinvitations', 'Update and send invitation...'),
                                        ],
                                        true
                                    );
                                    return $btn;
                                } else {
                                    return '';
                                }
                            },
                            're-send' => function ($url, $model) {
                                /** @var \lispa\amos\invitations\models\Invitation $model */
                                if ($model->send && \Yii::$app->user->can('INVITATIONS_ADMINISTRATOR')) {
                                    $retArray = InvitationsUtility::checkUserAlreadyPresent($model->invitationUser->email, true, true);
                                    $options = [
                                        'model' => $model,
                                        'value' => \Yii::$app->urlManager->createUrl(['/invitations/invitation/invitations-sent', 'id' => $model->id, 'ajax' => true]),
                                        'class' => 'btn btn-tools-secondary re-send',
                                        'title' => Module::t('amosinvitations', 'Re-send invitation'),
                                    ];
                                    if ($retArray['present']) {
                                        $options['disabled'] = true;
                                        $options['title'] = $retArray['message'];
                                        $options['class'] = 'btn btn-tools-secondary';
                                    }
                                    $btn = Html::a(AmosIcons::show('refresh-sync'), 'javascript:void(0)', $options);
                                    return $btn;
                                }
                                return '';
                            }
                        ]
                    ],
                ],
            ],
        ]);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    Modal::begin(['id' => 'modal_sent', 'size' => 'modal-lg', 'header' => Module::t('amosinvitations', 'Re-send invitation')]);
    echo "<div id='modal_content'></div>";
    Modal::end();

    ?>

    <?= Html::button(Module::t('amosinvitations', 'Send all selected'), [
        'class' => 'btn btn-primary pull-right',
        'value' => 'send-invitation',
        'type' => 'submit',
        'name' => 'submit-invitation',
        'data-confirm' => Module::t('amosinvitations', '#are-you-sure-send-all')
    ]);
    ?>


    <?php ActiveForm::end(); ?>
</div>
