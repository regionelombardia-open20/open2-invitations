<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\invitations
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

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m180123_162351_invitation_permissions*/
class m180123_162351_invitation_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'INVITATIONS_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruole adiminstrator',
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'INVITATIONS_BASIC_USER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruole adiminstrator',
                'parent' => ['VALIDATED_BASIC_USER']
            ],
            [
                'name' => 'INVITATION_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Invitation',
                'parent' => ['INVITATIONS_ADMINISTRATOR', 'INVITATIONS_BASIC_USER']
            ],
            [
                'name' => 'INVITATION_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model Invitation',
                'parent' => ['INVITATIONS_ADMINISTRATOR']
            ],
            [
                'name' => 'INVITATION_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model Invitation',
                'parent' => ['INVITATIONS_ADMINISTRATOR']
            ],
            [
                'name' => \open20\amos\invitations\rules\ReadOwnInvitationRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model Invitation',
                'ruleName' => \open20\amos\invitations\rules\ReadOwnInvitationRule::className(),
                'parent' => ['INVITATIONS_BASIC_USER'],
                'children' => ['INVITATION_READ']
            ],
            [
                'name' => \open20\amos\invitations\rules\UpdateOwnInvitationRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model Invitation',
                'ruleName' => \open20\amos\invitations\rules\UpdateOwnInvitationRule::className(),
                'parent' => ['INVITATIONS_BASIC_USER'],
                'children' => ['INVITATION_UPDATE']
            ],
        ];
    }
}
