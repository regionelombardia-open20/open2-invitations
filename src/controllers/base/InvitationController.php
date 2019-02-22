<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\invitations\controllers\base
 * @category   CategoryName
 */

namespace lispa\amos\invitations\controllers\base;

use lispa\amos\admin\AmosAdmin;
use lispa\amos\core\controllers\CrudController;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\core\utilities\Email;
use lispa\amos\invitations\models\Invitation;
use lispa\amos\invitations\models\InvitationUser;
use lispa\amos\invitations\models\search\InvitationSearch;
use lispa\amos\invitations\Module;
use lispa\amos\invitations\utility\InvitationsUtility;
use Yii;
use yii\helpers\Url;
use yii\validators\EmailValidator;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class InvitationController
 * InvitationController implements the CRUD actions for Invitation model.
 * @package lispa\amos\invitations\controllers\base
 */
class InvitationController extends CrudController
{
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->setModelObj(new Invitation());
        $this->setModelSearch(new InvitationSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => Module::t('amosinvitations', '{iconaTabella}' . Html::tag('p', Module::t('amosinvitations', 'Table')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
        ]);

        parent::init();
    }

    /**
     * Lists all Invitation models.
     * @param string|null $layout
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();

        $ret = $this->importInvitationsAction();
        $this->handleImportResult($ret);
        $this->sendSelectedInvitationsAction();

        /* add params */
        $this->setCreateNewBtnLabel();

        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex();
    }

    /**
     * @param array $ret
     */
    private function handleImportResult($ret)
    {
        if (!is_null($ret)) {
            if ($ret['error'] != '') {
                Yii::$app->getSession()->addFlash('danger', Module::t('amosinvitations', 'Import error: {message}', ['message' => $ret['error']]));
            } else {
                if ($ret['num_imp'] > 0) {
                    Yii::$app->getSession()->addFlash('success', Module::t('amosinvitations', 'Properly imported {num} invitations!', ['num' => $ret['num_imp']]));
                }
                if ($ret['num_no_imp'] > 0) {
                    $message = Module::t('amosinvitations', 'Not imported {num} invitations!', ['num' => $ret['num_no_imp']]);
                    if (strlen($ret['imp_mail_error']) > 0) {
                        $message .= '<br>' . $ret['imp_mail_error'];
                    }
                    Yii::$app->getSession()->addFlash('warning', $message);
                }
            }
        }
    }

    /**
     * @return array|null
     * @throws \yii\db\Exception
     */
    private function importInvitationsAction()
    {
        $ret = [
            'error' => '',
            'num_imp' => 0,
            'num_no_imp' => 0,
            'num_not_valid_mails' => 0,
            'num_users_already_present' => 0,
            'imp_mail_error' => '',
        ];
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $submitImport = Yii::$app->request->post('submit-import');
            if (!empty($submitImport)) {
                if ((isset($_FILES['import-file']['tmp_name']) && (!empty($_FILES['import-file']['tmp_name'])))) {
                    $inputFileName = $_FILES['import-file']['tmp_name'];
                    $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
                    $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFileName);

                    $sheet = $objPHPExcel->getSheet(0);
                    $highestRow = $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();
                    $validator = new EmailValidator();
                    $notValidEmails = [];
                    $usersAlreadyPresentEmails = [];
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                            NULL,
                            TRUE,
                            FALSE);
                        $invitationArray = $rowData[0];
                        $email = $invitationArray[0];
                        $name = $invitationArray[1];
                        $surname = $invitationArray[2];
                        $message = $invitationArray[3];
                        if ($validator->validate($email)) {
                            if (InvitationsUtility::checkUserAlreadyPresent($email, true)) {
                                $usersAlreadyPresentEmails[] = "'$email'";
                                $ret['num_no_imp']++;
                                $ret['num_users_already_present']++;
                            } else {
                                //find invitation user
                                $invitationUser = InvitationUser::getInvitationUserFromEmail($email);
                                if (empty($invitationUser)) {
                                    $invitationUser = new InvitationUser();
                                    $invitationUser->email = $email;
                                    $invitationUser->save(false);
                                }
                                /** @var Invitation $invitation */
                                $invitation = new Invitation();
                                $invitation->invitation_user_id = $invitationUser->id;
                                $invitation->name = $name;
                                $invitation->surname = $surname;
                                $invitation->message = $message;
                                $invitation->save(false);
                                $ret['num_imp']++;
                            }
                        } else {
                            $notValidEmails[] = "'$email'";
                            $ret['num_not_valid_mails']++;
                            $ret['num_no_imp']++;
                        }
                    }
                    if ($ret['num_not_valid_mails'] > 0) {
                        $ret['imp_mail_error'] .= Module::t('amosinvitations', '#import_invitations_not_valid_mails') . ': ' . implode(', ', $notValidEmails);
                    }
                    if ($ret['num_users_already_present'] > 0) {
                        if (strlen($ret['imp_mail_error']) > 0) {
                            $ret['imp_mail_error'] .= '<br>';
                        }
                        $ret['imp_mail_error'] .= Module::t('amosinvitations', '#import_invitations_users_already_present') . ': ' . implode(', ', $usersAlreadyPresentEmails);
                    }
                }
            } else {
                $ret = null;
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $ret['error'] = $e->getMessage();
            $transaction->rollBack();
            return $ret;
        }
        return $ret;
    }

    /**
     *
     */
    private function sendSelectedInvitationsAction()
    {
        $i = 0;
        $submitInvitation = Yii::$app->request->post('submit-invitation');
        if (!empty($submitInvitation)) {
            $selection = Yii::$app->request->post('Invitation');
            if (!empty($selection) && isset($selection['selection'])) {
                foreach ($selection['selection'] as $id) {
                    $invitation = Invitation::findOne($id);
                    if (!empty($invitation)) {
                        if (!InvitationsUtility::checkUserAlreadyPresent($invitation->invitationUser->email)) {
                            $this->sendMailInvitation($invitation);
                            $i++;
                        }
                    }
                }
            } else {
                Yii::$app->getSession()->addFlash('warning', Module::t('amosinvitations', 'No invitation sent'));
            }
        }
        if ($i == 1) {
            Yii::$app->getSession()->addFlash('success', Module::t('amosinvitations', 'An invitation has been sent'));
        } elseif ($i > 1) {
            Yii::$app->getSession()->addFlash('success', Module::t('amosinvitations', '{num} invitations were sent', ['num' => $i]));
        }
    }

    /**
     * @param Invitation $invitation
     * @return Invitation
     */
    protected function sendMailInvitation($invitation)
    {
        if (!empty($invitation)) {
            if (isset(Yii::$app->params['email-assistenza'])) {
                //use default platform email assistance
                $from = Yii::$app->params['email-assistenza'];
            } else {
                $assistance = isset(Yii::$app->params['assistance']) ? Yii::$app->params['assistance'] : [];
                $from = isset($assistance['email']) ? $assistance['email'] : '';
            }
            $tos = [$invitation->invitationUser->email];
            $subject = Module::t('amosinvitations', '#subject-invite');
            $text = $this->renderPartial('_invitation_email', ['invitation' => $invitation]);
            $invitation->send = (int)Email::sendMail($from, $tos, $subject, $text, [], [], [], 0, false);
            $invitation->send_time = date('Y-m-d H:i:s');
            $invitation->save(false);
        }
        return $invitation;
    }

    /**
     * Lists all Invitation models.
     * @param string|null $layout
     * @return mixed
     */
    public function actionIndexAll($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));

        $ret = $this->importInvitationsAction();
        $this->handleImportResult($ret);
        $this->sendSelectedInvitationsAction();

        /* add params */
        $this->setCreateNewBtnLabel();

        $this->setUpLayout('list');
        if ($layout) {
            $this->setUpLayout($layout);
        }
        return $this->render('index_all', [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : NULL,
            'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }

    /**
     * Displays a single Invitation model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new Invitation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $invitation = new Invitation();
        $invitationUser = new InvitationUser();

        if (!Yii::$app->request->isAjax) {
            if ($invitation->load(Yii::$app->request->post()) && $invitationUser->load(Yii::$app->request->post())) {
                if (InvitationsUtility::checkUserAlreadyPresent($invitationUser->email)) {
                    return $this->render('create', [
                        'invitation' => $invitation,
                        'invitationUser' => $invitationUser,
                    ]);
                }
            }
        }

        return $this->sendInvitation('create', $invitation, $invitationUser);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionReSend($id)
    {
        $this->setUpLayout('form');
        $invitation = Invitation::findOne($id);
        $email = $invitation->invitationUser->email;

        if (InvitationsUtility::checkUserAlreadyPresent($email)) {
            if (Yii::$app->user->can('ADMIN')) {
                return $this->redirect(['index-all']);
            } else {
                return $this->redirect(['index']);
            }
        }

        $newInvitation = new Invitation();
        $newInvitation->invitation_user_id = $invitation->invitation_user_id;
        $newInvitation->message = $invitation->message;
        $newInvitation->name = $invitation->name;
        $newInvitation->surname = $invitation->surname;
        $newInvitation->save();
        $invitation = $this->sendMailInvitation($newInvitation);
        if ($invitation->save()) {
            Yii::$app->getSession()->addFlash('success', Module::t('amosinvitations', 'Item sended'));
        } else {
            Yii::$app->getSession()->addFlash('danger', Module::t('amosinvitations', 'Item not sended, check data'));
        }

        return $this->redirect(['index-all']);
    }

    /**
     * @param $actionView
     * @param Invitation $invitation
     * @param InvitationUser $invitationUser
     * @return string|\yii\web\Response
     */
    protected function sendInvitation($actionView = null, $invitation, $invitationUser)
    {
        $message = '';
        if ($invitationUser->load(Yii::$app->request->post())) {
            $retCheckUser = InvitationsUtility::checkUserAlreadyPresent($invitationUser->email, true, true);
            if ($retCheckUser['present']) {
                if (!is_null($actionView)) {
                    \Yii::$app->getSession()->addFlash('danger', $retCheckUser['message']);
                    return $this->render($actionView, [
                        'invitation' => $invitation,
                        'invitationUser' => $invitationUser,
                    ]);
                }
                if (Yii::$app->user->can('ADMIN')) {
                    return $this->redirect(['index-all']);
                } else {
                    return $this->redirect(['index']);
                }
            }
            // check if email is unique... if not i find this email in database, and i use this to create notification
            if ($invitationUser->validate('email')) {
                if (!$invitationUser->save()) {
                    $message = Module::t('amosinvitations', 'Error to save invitation email');
                    if ($actionView) {
                        Yii::$app->getSession()->addFlash('danger', $message);
                        return $this->render($actionView, [
                            'invitation' => $invitation,
                            'invitationUser' => $invitationUser,
                        ]);
                    }
                }
            } else {
                $invitationUser = InvitationUser::findOne(['email' => $invitationUser->email]);
            }
        }

        $invitation->invitation_user_id = $invitationUser->id;
        if ($invitation->load(Yii::$app->request->post()) && $invitation->validate()) {
            $invitation = $this->sendMailInvitation($invitation);
            if ($invitation->save()) {
                $message = Module::t('amosinvitations', 'Item sended');
                if ($actionView) {
                    Yii::$app->getSession()->addFlash('success', $message);
                    if (Yii::$app->user->can('ADMIN')) {
                        return $this->redirect(['index-all']);
                    } else {
                        return $this->redirect(['index']);
                    }
                }
            } else {
                $message = Module::t('amosinvitations', 'Item not sended, check data');
                if ($actionView) {
                    Yii::$app->getSession()->addFlash('danger', $message);
                    return $this->render($actionView, [
                        'invitation' => $invitation,
                        'invitationUser' => $invitationUser,
                    ]);
                }
            }
        } else if ($actionView) {
            return $this->render($actionView, [
                'invitation' => $invitation,
                'invitationUser' => $invitationUser,
            ]);
        }

        return $message;
    }

    /**
     * Updates an existing Invitation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionUpdate($id)
    {
        $this->layout = "@vendor/lispa/amos-core/views/layouts/form";
        $invitation = Invitation::findOne($id);
        if (empty($invitation)) {
            throw new BadRequestHttpException();
        }
        $invitationUser = $invitation->invitationUser;

        if (Yii::$app->request->post()) {
            if (InvitationsUtility::checkUserAlreadyPresent($invitationUser->email)) {
                return $this->render('update', [
                    'invitation' => $invitation,
                    'invitationUser' => $invitationUser,
                ]);
            }
        }
        return $this->sendInvitation('update', $invitation, $invitationUser);
    }

    /**
     * Deletes an existing Invitation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            //si può sostituire il  delete() con forceDelete() in caso di SOFT DELETE attiva
            //In caso di soft delete attiva e usando la funzione delete() non sarà bloccata
            //la cancellazione del record in presenza di foreign key quindi
            //il record sarà cancelleto comunque anche in presenza di tabelle collegate a questo record
            //e non saranno cancellate le dipendenze e non avremo nemmeno evidenza della loro presenza
            //In caso di soft delete attiva è consigliato modificare la funzione oppure utilizzare il forceDelete() che non andrà
            //mai a buon fine in caso di dipendenze presenti sul record da cancellare
            if ($model->delete()) {
                Yii::$app->getSession()->addFlash('success', Module::t('amosinvitations', 'Item deleted'));
            } else {
//                Yii::$app->getSession()->addFlash('danger', Module::t('amosinvitations', 'Item not deleted because of dependency'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', Module::t('amosinvitations', 'Item not found'));
        }
        return $this->redirect(['index']);
    }


    /**
     * Set a view param used in \lispa\amos\core\forms\CreateNewButtonWidget
     */
    private function setCreateNewBtnLabel()
    {
        $importInvite = Html::button(Module::t('amosinvitations', 'Import invitations'), [
            'class' => 'btn btn-primary',
            'data-toggle' => 'modal',
            'data-target' => '#modalImport',
        ]);

        $session = Yii::$app->session;
        if ($session->has(AmosAdmin::GOOGLE_CONTACTS_NOT_PLATFORM)) {
            $contactsNotPlatform = $session->get(AmosAdmin::GOOGLE_CONTACTS_NOT_PLATFORM);
            if (!empty($contactsNotPlatform)) {
                $inviteFromGoogle = Html::a(AmosIcons::show('google') . '&nbsp;' . Module::t('amosinvitations', '#invite_google_btn'),
                    'invite-google', ['class' => 'btn btn-primary']);
            }
        }

        $createNewBtnParams = [];
        $createNewBtnParams = yii\helpers\ArrayHelper::merge($createNewBtnParams, [
            'layout' => "{buttonCreateNew}" . $importInvite . (isset($inviteFromGoogle) ? $inviteFromGoogle : '')
        ]);

        Yii::$app->view->params['createNewBtnParams'] = $createNewBtnParams;
    }
}
