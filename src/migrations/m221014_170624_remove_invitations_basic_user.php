<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    ebike\assets\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m200422_125711_add_ebike_assets_workflow_permissions_for_validation
 */
class m221014_170624_remove_invitations_basic_user
    extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            
            [
                'name' => 'INVITATIONS_BASIC_USER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruole adiminstrator',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['VALIDATED_BASIC_USER',]
                ]
            ],
        ];
    }
}
