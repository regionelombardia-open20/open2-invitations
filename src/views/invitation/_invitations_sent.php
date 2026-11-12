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

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\helpers\Html;
use open20\amos\invitations\models\InvitationUser;
use open20\amos\invitations\Module;

/**
 * @var yii\web\View $this
 * @var $model \open20\amos\invitations\models\Invitation
 */

/** @var Module $invitationsModule */
$invitationsModule = \Yii::$app->getModule('invitations');

$moduleName = $model->module_name;
$contextModelId = $model->context_model_id;
$registerAction = $model->register_action;
$category = $model->category;
?>

<?php $form = ActiveForm::begin() ?>
<div class="col-lg-12">
    <?php echo "<strong>" . Module::t('amosinvitations', 'Name and surname:') . '</strong> ' . $model->nameSurname ?>
</div>
<div class="col-lg-12">
    <?php echo "<strong>" . Module::t('amosinvitations', 'Invitations sent:') . '</strong> ' . $model->invitationUser->numberNotificationSended; ?>
</div>
<div class="col-lg-12">
    <?php
    /** @var InvitationUser $invitationUserModel */
    $invitationUserModel = $invitationsModule->createModel('InvitationUser');
    $invitationUser = $invitationUserModel::getInvitationUserFromEmail($model->invitationUser->email);
    $invitation = $invitationUser->getInvitations()->orderBy('send_time DESC')->one()
    ?>
    <?php echo "<strong>" . Module::t('amosinvitations', 'Last invitation sent at:') . '</strong> ' . \Yii::$app->formatter->asDatetime($invitation->send_time) ?>
</div>
<div>
    <?= Html::a(Module::t('amosinvitations', 'Re-send'), [
        '/invitations/invitation/re-send',
        'id' => $model->id,
        'moduleName' => $moduleName,
        'contextModelId' => $contextModelId,
        'registerAction' => $registerAction,
        'category' => $category,
        'iid' => $invitation->id
    ], ['class' => 'btn btn-primary pull-right']) ?>
    <?php CloseSaveButtonWidget::widget(['model' => $model, 'buttonId' => 're-send-button', 'buttonSaveLabel' => Module::t('amosinvitations', 'Send')]); ?>
</div>
<?php ActiveForm::end(); ?>
