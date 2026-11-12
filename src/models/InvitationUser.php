<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\invitations\models
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

namespace open20\amos\invitations\models;

use open20\amos\invitations\Module;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class InvitationUser
 * This is the model class for table "invitation_user".
 *
 * @property integer numberNotificationSended
 * @property integer numberNotificationSendedByMe
 *
 * @package open20\amos\invitations\models
 */
class InvitationUser extends \open20\amos\invitations\models\base\InvitationUser
{
    public function getEditFields()
    {
        $labels = $this->attributeLabels();
        
        return [
            [
                'slug' => 'email',
                'label' => $labels['email'],
                'type' => 'string'
            ],
        ];
    }
    
    /**
     * @inheridoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'numberNotificationSended' => Yii::t('amosinvitations', 'Number of invitation to ths email'),
            'numberNotificationSendedByMe' => Yii::t('amosinvitations', 'Number of invitation to ths email by me')
        ]);
    }
    
    /**
     * @inheridoc
     */
    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }
    
    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }
    
    /**
     * @inheridoc
     */
    public function attributeHints()
    {
        return [
        ];
    }
    
    public function getNumberNotificationSended()
    {
        if (!empty($this->id)) {
            return self::numberNotificationsSended($this->id);
        } else {
            return 0;
        }
    }
    
    public function getNumberNotificationSendedByMe()
    {
        if (!empty($this->id)) {
            return self::numberNotificationsSendedByUserId($this->id, Yii::$app->user->id);
        } else {
            return 0;
        }
    }
    
    public static function getInvitationUserFromEmail($email)
    {
        return self::find()->andWhere(['email' => $email])->one();
    }
    
    /**
     * @param $invitationUserId
     * @return int
     */
    public static function numberNotificationsSended($invitationUserId = null)
    {
        $invitationUser = self::findOne($invitationUserId);
        if (!empty($invitationUser)) {
            /** @var Invitation $invitationModel */
            $invitationModel = Module::instance()->createModel('Invitation');
            return count($invitationModel::find()->andWhere(['invitation_user_id' => $invitationUserId, 'send' => true])->all());
        } else {
            return 0;
        }
    }
    
    /**
     * @param $invitationUserId
     * @param null $userId
     * @return int
     */
    public static function numberNotificationsSendedByUserId($invitationUserId = null, $userId = null)
    {
        $invitationUser = self::findOne($invitationUserId);
        if (!empty($invitationUser)) {
            /** @var Invitation $invitationModel */
            $invitationModel = Module::instance()->createModel('Invitation');
            return count($invitationModel::find()->andWhere([
                'invitation_user_id' => $invitationUserId,
                'send' => true,
                'created_by' => $userId,
            ])->all());
        } else {
            return 0;
        }
    }
}
