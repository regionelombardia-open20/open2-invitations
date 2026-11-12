<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\invitations\models\base
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

namespace open20\amos\invitations\models\base;

use open20\amos\core\record\Record;
use open20\amos\invitations\Module;

/**
 * Class InvitationUser
 *
 * This is the base-model class for table "invitation_user".
 *
 * @property integer $id
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\invitations\models\Invitation[] $invitations
 *
 * @package open20\amos\invitations\models\base
 */
class InvitationUser extends Record
{
    /**
     * @var Module $invitationsModule
     */
    protected $invitationsModule;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invitation_user';
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->invitationsModule = Module::instance();
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['email'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('amosinvitations', 'ID'),
            'email' => Module::t('amosinvitations', 'Email'),
            'created_at' => Module::t('amosinvitations', 'Created at'),
            'updated_at' => Module::t('amosinvitations', 'Updated at'),
            'deleted_at' => Module::t('amosinvitations', 'Deleted at'),
            'created_by' => Module::t('amosinvitations', 'Created by'),
            'updated_by' => Module::t('amosinvitations', 'Updated by'),
            'deleted_by' => Module::t('amosinvitations', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvitations()
    {
        return $this->hasMany(\open20\amos\invitations\models\Invitation::className(), ['invitation_user_id' => 'id']);
    }
}
