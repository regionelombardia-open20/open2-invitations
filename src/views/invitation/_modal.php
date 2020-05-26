<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @licence GPLv3
 * @licence https://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 *
 * @package amos-invitations
 * @category CategoryName
 * @author Lombardia Informatica S.p.A.
 */

use \yii\bootstrap\Modal;
use \open20\amos\core\helpers\Html;
use \kartik\widgets\FileInput;
use \yii\base\InvalidConfigException;

try {
    Modal::begin([
        'header' => '<h2>' . Yii::t('amosinvitations', 'Import invitations') . '</h2>',
        'size' => Modal::SIZE_LARGE,
        'id' => 'modalImport',
        'footer' => Html::button(
            Yii::t('amosinvitations', 'Import'),
            [
                'class' => 'btn btn-primary',
                'value' => 'import',
                'type' => 'submit',
                'name' => 'submit-import',
                'id' => 'submitImport'
            ]
        ),
    ]);

    $linkDownload = Html::a(Yii::t('amosinvitations', 'here'), ['download-import-template']);
    echo Yii::t('amosinvitations', '#message-import-row-1');
    echo '<ol>';
    echo '<li>' . Yii::t('amosinvitations', '#message-import-row-2', ['linkdownload' => $linkDownload]) . '</li>';
    echo '<li>' . Yii::t('amosinvitations', '#message-import-row-3') . '</li>';
    echo '<li>' . Yii::t('amosinvitations', '#message-import-row-4') . '</li>';
    echo '<li>' . Yii::t('amosinvitations', '#message-import-row-5') . '</li>';
    echo '<li>' . Yii::t('amosinvitations', '#message-import-row-6') . '</li>';
    echo '</ol>';

    echo '<label class="control-label">' . Yii::t('amosinvitations', 'Upload the file') . '</label>';
    echo FileInput::widget([
        'name' => 'import-file',
        'pluginOptions' => [
            'showPreview' => false,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false
        ]
    ]);
    
    if (!empty($model) && !empty($moduleName) && !empty($contextModelId)) {
        echo $form->field($model, 'moduleName')->hiddenInput(['value' => $moduleName])->label(false);
        echo $form->field($model, 'contextModelId')->hiddenInput(['value' => $contextModelId])->label(false);
    }
    
    Modal::end();

} catch (InvalidConfigException $e) {
} catch (Exception $e) {
}