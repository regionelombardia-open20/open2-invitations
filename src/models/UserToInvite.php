<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    UserToInvite.php
 * @category   CategoryName
 */

namespace lispa\amos\invitations\models;


use lispa\amos\invitations\Module;
use yii\base\Model;

/**
 * Class UserToInvite
 * @package lispa\amos\invitations\models
 */
class UserToInvite extends Model
{

    public $name = '';

    public $surname = '';

    public $displayName = '';

    public $email = '';

    public $photoUrl = '';

    public $selected = false;

    public $sentInvitations = 0;

    public $invitationUserId;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Module::t('amosinvitations', 'Name'),
            'surname' => Module::t('amosinvitations', 'Surname'),
            'displayName' => Module::t('amosinvitations', 'Name'),
            'email' => Module::t('amosinvitations', 'Email'),
            'photoUrl' => Module::t('amosinvitations', 'Image'),
            'sentInvitations' => Module::t('amosinvitations', 'Sent Invitations'),
        ];
    }

}
