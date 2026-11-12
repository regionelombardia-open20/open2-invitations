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

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\invitations\Module;
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var open20\amos\invitations\models\Invitation $model
 */

/** @var Module $invitationsModule */
$invitationsModule = Module::instance();

$this->title = Module::t('amosinvitations', '#invite_view_title', ['nameSurname' => $model->getNameSurname()]);
$this->params['breadcrumbs'][] = $this->title;

$attributes = [
    'name',
    'surname',
];
if ($invitationsModule->enableFiscalCode) {
    $attributes[] = 'fiscal_code';
}
if ($invitationsModule->enableInviteMessage) {
    $attributes[] = 'message:html';
}
$attributes[] = 'send_time:datetime';
$attributes[] = 'send:boolean';
$attributes[] = 'invitationUser.email';
$attributes[] = 'invitationUser.numberNotificationSended';
$attributes[] = 'invitationUser.numberNotificationSendedByMe';
$attributes[] = 'created_at:datetime';

?>
<div class="invitation-view col-xs-12">
    <div class="row">
        <div class="col-xs-12 m-t-5 m-b-5">
            <?= ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => $model->getFullUpdateUrl(),
                'actionDelete' => $model->getFullDeleteUrl()
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => $attributes,
            ]); ?>
        </div>
    </div>
    <div class="btnViewContainer pull-right">
        <?= Html::a(Module::t('amosinvitations', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>
    </div>
</div>
