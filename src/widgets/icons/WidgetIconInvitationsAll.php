<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\invitations
 * @category   CategoryName
 */

namespace lispa\amos\invitations\widgets\icons;

use lispa\amos\core\widget\WidgetIcon;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconInvitations
 * @package lispa\amos\invitations\widgets\icons
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
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-primary'
        ]));
    }

}