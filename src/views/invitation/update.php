<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\invitations
 * @category   CategoryName
 */

use lispa\amos\invitations\models\Invitation;
use lispa\amos\invitations\models\InvitationUser;
use yii\web\View;

/**
 * @var View $this
 * @var Invitation $invitation
 * @var InvitationUser $invitationUser
 */

$this->title = Yii::t('amosinvitations', '#send-invitation-titile');
?>
<div class="invitation-update">

    <?= $this->render('_form', [
        'invitation' => $invitation,
        'invitationUser' => $invitationUser,
    ]) ?>

</div>
