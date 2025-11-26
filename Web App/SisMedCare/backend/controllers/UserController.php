<?php

namespace backend\controllers;

use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $auth = Yii::$app->authManager;

        // Vai buscar os IDs dos utilizadores que têm a role secretary -> função do authManager
        $secretary = $auth->getUserIdsByRole('secretary');

        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['id' => $secretary, 'status' => User::STATUS_ACTIVE]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->password)) {
                $model->setPassword($model->password);
            }
            $model->generateAuthKey();
            $model->status = User::STATUS_ACTIVE;

            if (!$model->save()) {
                Yii::$app->session->setFlash('error', 'Erro ao criar a conta da secretária.');
                return $this->redirect(['index']);
            }

            // Atribuir role
            $auth = Yii::$app->authManager;
            $role = $auth->getRole('secretary');

            if ($role) {
                if (!$auth->assign($role, $model->id)) {
                    Yii::$app->session->setFlash('error', 'Erro ao atribuir role à secretária.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Role "secretary" não encontrada.');
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->loadDefaultValues();

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {

            if (!$model->load($this->request->post())) {
                Yii::$app->session->setFlash('error', 'Erro ao carregar dados da secretária.');
                return $this->redirect(['view', 'id' => $model->id]);
            }

            if (!$model->save()) {
                Yii::$app->session->setFlash('error', 'Erro ao atualizar secretária.');
                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = User::STATUS_INACTIVE;

        if (!$model->save(false)) {
            Yii::$app->session->setFlash('error', 'Erro ao apagar a secretária.');
        } else {
            Yii::$app->session->setFlash('success', 'Secretária apagada com sucesso.');
        }

        return $this->redirect(['index']);
    }


    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
