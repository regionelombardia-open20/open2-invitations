<?php

namespace lispa\amos\invitations\controllers\api;

/**
* This is the class for REST controller "InvitationController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class InvitationController extends \yii\rest\ActiveController
{
public $modelClass = 'lispa\amos\invitations\models\Invitation';
}
