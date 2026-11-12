<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\invitations\views\invitation
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