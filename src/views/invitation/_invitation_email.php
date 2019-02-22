<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\invitations
 * @category   CategoryName
 */

/**
 * @var yii\web\View $this
 * @var lispa\amos\invitations\models\Invitation $invitation
 */

?>

<div>
    <?php echo Yii::t('amosinvitations', '#hi') ?> <?= $invitation->getNameSurname()?>,
</div>

<?php
$profileSender = \lispa\amos\admin\models\UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();
$url = Yii::$app->urlManager->createAbsoluteUrl(['/admin/security/register', 'name' => $invitation->name, 'surname' => $invitation->surname, 'email' => $invitation->invitationUser->email]); ?>
<div style="font-weight: normal">
    <p><?= $profileSender->nomeCognome ." ". Yii::t('amosinvitations', '#text_email_invitation0') ?></p>
    <div style="color:green"><strong><?= $invitation->message ?></strong> </div>
    <p style="text-align: center"> <a href="<?=$url?>"><strong><?= Yii::t('amosinvitations', '#registration_page') ?></strong></a> </p>
    <p><?= Yii::t('amosinvitations', "#text_email_invitation1") ?></p><br>
    <p style="color:green"><strong><?= Yii::t('amosinvitations', '#text_email_invitation2') ?></strong></p><br>
    <p><?= Yii::t('amosinvitations', "#text_email_invitation3") ?></p>

</div>

<div>
    <?= Yii::t('amosinvitations', '#invitation-email-end', ['site' => Yii::$app->name]) ?>
</div>
