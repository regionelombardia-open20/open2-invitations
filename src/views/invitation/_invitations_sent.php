<?php

use lispa\amos\core\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var $model \lispa\amos\invitations\models\Invitation
 **/
 ?>
<?php $form = \lispa\amos\core\forms\ActiveForm::begin()?>
    <div class="col-lg-12">
        <?php echo "<strong>" . \lispa\amos\invitations\Module::t('amosinvitations', 'Name and surname:') . '</strong> ' . $model->nameSurname ?>
    </div>
    <div class="col-lg-12">
        <?php echo "<strong>" . \lispa\amos\invitations\Module::t('amosinvitations', 'Invitations sent:') . '</strong> ' . $model->invitationUser->numberNotificationSended;?>
    </div>
    <div class="col-lg-12">
        <?php
        /** @var  $lastInvitationUser \lispa\amos\invitations\models\InvitationUser */
        $invitationUser = lispa\amos\invitations\models\InvitationUser:: getInvitationUserFromEmail($model->invitationUser->email);
        $invitation = $invitationUser->getInvitations()->orderBy('send_time DESC')->one()
        ?>
        <?php echo "<strong>" . \lispa\amos\invitations\Module::t('amosinvitations', 'Last invitation sent at:') . '</strong> ' . \Yii::$app->formatter->asDatetime($invitation->send_time) ?>
    </div>
    <div>
        <?= Html::a( \lispa\amos\invitations\Module::t('amosinvitations', 'Re-send') , ['/invitations/invitation/re-send', 'id' => $model->id],['class' => 'btn btn-primary pull-right'])?>
        <?php \lispa\amos\core\forms\CloseSaveButtonWidget::widget(['model' => $model, 'buttonId' => 're-send-button', 'buttonSaveLabel' => \lispa\amos\invitations\Module::t('amosinvitations', 'Send')]); ?>
    </div>
<?php \lispa\amos\core\forms\ActiveForm::end(); ?>
