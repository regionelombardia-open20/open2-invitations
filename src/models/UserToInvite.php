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
use yii\base\Model;

/**
 * Class UserToInvite
 * @package open20\amos\invitations\models
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
