<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

namespace open20\amos\invitations\controllers\api;

/**
* This is the class for REST controller "InvitationController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class InvitationController extends \yii\rest\ActiveController
{
public $modelClass = 'open20\amos\invitations\models\Invitation';
}
