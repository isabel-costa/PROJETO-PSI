<?php
namespace frontend\controllers;

use Yii;
use common\models\Medico;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class MedicoController extends Controller
{
    public function actionView($id)
    {
        $model = Medico::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('Médico não encontrado.');
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionPerfil()
    {   
        $userId = Yii::$app->user->id;

        // Pega o médico onde user_id = $userId
        $model = Medico::find()->where(['user_id' => $userId])->one();

        if (!$model) {
            throw new NotFoundHttpException('Médico não encontrado.');
        }

        return $this->render('view', ['model' => $model]);
    }

}