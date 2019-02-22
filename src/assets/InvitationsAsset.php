<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\community
 * @category   CategoryName
 */

namespace lispa\amos\invitations\assets;

use yii\web\AssetBundle;

/**
 * Class InvitationsAsset
 * @package lispa\amos\invitations\assets
 */
class InvitationsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/lispa/amos-invitations/src/assets/web';

    public $css = [
        'less/invitations.less',
    ];

    public $depends = [
    ];

    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');
        if (!empty($moduleL)) {
            $this->depends [] = 'lispa\amos\layout\assets\BaseAsset';
        } else {
            $this->depends [] = 'lispa\amos\core\views\assets\AmosCoreAsset';
        }
        parent::init();
    }

}