<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\invitations
 * @category   CategoryName
 */

namespace lispa\amos\invitations\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use lispa\amos\invitations\models\Invitation;
use yii\db\ActiveQuery;

/**
 * InvitationSearch represents the model behind the search form about `lispa\amos\invitations\models\Invitation`.
 */
class InvitationSearch extends Invitation
{
    public $email;

    public function rules()
    {
        return [
            [['id', 'send', 'invitation_user_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'surname', 'message', 'send_time', 'email', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        /** @var ActiveQuery $query */
        $query = Invitation::find()
            ->andWhere(['created_by' => Yii::$app->user->id]);
        $query->orderBy('send ASC, send_time DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $scope = $this->getScope($params);
        if (!($this->load($params, $scope) && $this->validate())) {
            return $dataProvider;
        }
        if(!empty($this->email)) {
            $query->innerJoinWith('invitationUser')
                ->andFilterWhere(['like', 'email', $this->email]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'send_time' => $this->send_time,
            'send' => $this->send,
            'invitation_user_id' => $this->invitation_user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }

    public function searchAll($params)
    {
        /** @var ActiveQuery $query */
        $query = Invitation::find();
        $query->innerJoinWith('invitationUser');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'send' => SORT_ASC,
                    'send_time' => SORT_DESC,
                ],
                'attributes' => [
                    'invitationUser.email' => [
                        'asc' => ['invitation_user.email' => SORT_ASC],
                        'desc' => ['invitation_user.email' => SORT_DESC]
                        ],
                    'name',
                    'surname',
                    'send_time',
                    'send'
                ]
            ],
        ]);

        $scope = $this->getScope($params);

        if (!($this->load($params, $scope) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'send_time' => $this->send_time,
            'send' => $this->send,
            'invitation_user_id' => $this->invitation_user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ])->andFilterWhere(['like', 'email', $this->email]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }

    public function getScope($params)
    {
        $scope = $this->formName();
        if (!isset($params[$scope])) {
            $scope = '';
        }
        return $scope;
    }
}