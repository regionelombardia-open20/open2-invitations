<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\invitations\widgets
 * @category   CategoryName
 */

namespace lispa\amos\invitations\widgets;

use lispa\amos\core\helpers\Html;
use lispa\amos\invitations\models\Invitation;
use lispa\amos\invitations\models\InvitationForm;
use lispa\amos\invitations\models\InvitationUser;
use lispa\amos\invitations\Module;
use yii\helpers\ArrayHelper;

/**
 * Class InviteUserWidget
 * @package lispa\amos\invitations\widgets
 */
class InviteUserWidget extends \yii\base\Widget
{

    public $btnOptions = [];

    public $btnLabel;

    public $layout = '{invitationBtn}{invitationModalForm}';

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
        if(is_null($this->btnLabel)){
            $this->btnLabel = Module::t('amosinvitations', '#new_invitation_btn');
        }
        $btnOptions = ArrayHelper::merge( [
            'class' => 'btn btn-administration-primary',
            'data-target' => '#invite-new-user-modal',
            'data-toggle' => 'modal'
        ], $this->btnOptions);

        $btn = Html::a($this->btnLabel,
            null,
            $btnOptions
        ) ;

        return $btn;

    }

    public function renderInvitationModalForm()
    {
         return $this->render('invite-user', ['invitation' => new Invitation(), 'invitationUser' => new InvitationUser()]);
    }


}