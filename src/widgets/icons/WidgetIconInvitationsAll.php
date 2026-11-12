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

namespace open20\amos\invitations\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconInvitationsAll
 * @package open20\amos\invitations\widgets\icons
 */
class WidgetIconInvitationsAll extends WidgetIcon
{
    public function init()
    {
        parent::init();
        
        $this->setLabel(\Yii::t('amosinvitations', '#admin_invitations'));
        $this->setDescription(Yii::t('amosinvitations', 'To manage all invitations to the platform'));
        $this->setIcon('notifications');
        $this->setIconFramework('am');
        $this->setUrl(Yii::$app->urlManager->createUrl(['/invitations/invitation/index-all']));
        $this->setModuleName('amos_invitations');
        $this->setNamespace(__CLASS__);
        
        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                [
                    'bk-backgroundIcon',
                    'color-primary'
                ]
            )
        );
    }
}
