<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations
 * @category   CategoryName
 */

namespace open20\amos\invitations\rules;

use open20\amos\invitations\models\Invitation;
use yii\rbac\Rule;

class ReadOwnInvitationRule extends Rule
{
    public $name = 'readOwnInvitation';

    /**
     * @inheritdoc
     */
    public function execute($loggedUserId, $item, $params)
    {

        // If the key "model" non exist return false
        if (!isset($params['model'])) {
            return true;
        }

        /** @var Invitation $model */
        $model = $params['model'];
        if (!$model->id) {
            $post = \Yii::$app->getRequest()->post();
            $get = \Yii::$app->getRequest()->get();
            if (isset($get['id'])) {
                $model = $this->instanceModel($model, $get['id']);
            } elseif (isset($post['id'])) {
                $model = $this->instanceModel($model, $post['id']);
            }
        }

        if ($model->isNewRecord) {
            return true;
        }

        return ($model->created_by == $loggedUserId);
    }

    /**
     * @param Invitation $model
     * @param int $modelId
     * @return mixed
     */
    protected function instanceModel($model, $modelId)
    {
        $modelClass = $model->className();
        /** @var \open20\amos\invitations\models\Invitation $modelClass */
        $instancedModel = $modelClass::findOne($modelId);
        if (!is_null($instancedModel)) {
            $model = $instancedModel;
        }
        return $model;
    }
}