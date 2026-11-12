<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\invitations\widgets
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

namespace open20\amos\invitations\widgets;

use open20\amos\core\helpers\Html;
use open20\amos\invitations\models\Invitation;
use open20\amos\invitations\models\InvitationUser;
use open20\amos\invitations\Module;
use yii\helpers\ArrayHelper;

/**
 * Class InviteUserWidget
 * @package open20\amos\invitations\widgets
 */
class InviteUserWidget extends \yii\base\Widget
{
    /**
     * @var Module $invitationsModule
     */
    protected $invitationsModule;
    
    public $btnOptions = [];
    
    public $btnLabel;
    
    public $layout = '{invitationBtn}{invitationModalForm}';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->invitationsModule = Module::instance();
        parent::init();
    }
    
    public function run()
    {
        $content = preg_replace_callback("/{\\w+}/", function ($matches) {
            $content = $this->renderSection($matches[0]);
            
            return $content === false ? $matches[0] : $content;
        }, $this->layout);
        
        return $content;
    }
    
    /**
     * @inheritdoc
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{invitationBtn}':
                return $this->renderInvitationBtn();
            case '{invitationModalForm}':
                return $this->renderInvitationModalForm();
            default:
                return false;
        }
    }
    
    public function renderInvitationBtn()
    {
        if (is_null($this->btnLabel)) {
            $this->btnLabel = Module::t('amosinvitations', '#new_invitation_btn');
        }
        $btnOptions = ArrayHelper::merge([
            'class' => 'btn btn-administration-primary',
            'data-target' => '#invite-new-user-modal',
            'data-toggle' => 'modal'
        ], $this->btnOptions);
        
        $btn = Html::a($this->btnLabel,
            null,
            $btnOptions
        );
        
        return $btn;
        
    }
    
    public function renderInvitationModalForm()
    {
        /** @var Invitation $invitationModel */
        $invitationModel = $this->invitationsModule->createModel('Invitation');
        /** @var InvitationUser $invitationUserModel */
        $invitationUserModel = $this->invitationsModule->createModel('InvitationUser');
        return $this->render('invite-user', ['invitation' => $invitationModel, 'invitationUser' => $invitationUserModel]);
    }
}
