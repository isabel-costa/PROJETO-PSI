<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use common\models\User;
use common\models\Paciente;

class PacienteController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBasicAuth::class,
            'only' => ['view', 'update', 'alergias', 'doencas'],
            'auth' => function ($username, $password) {
                $user = User::findOne(['username' => $username]);
                if ($user && $user->validatePassword($password)) {
                    return $user;
                }
                return null;
            },
        ];

        return $behaviors;
    }

    /**
     * GET /api/profile
     * Ver perfil do paciente autenticado
     */
    public function actionView()
    {
        $user = Yii::$app->user->identity;
        $paciente = $user->paciente;

        if (!$paciente) {
            return ['error' => 'Paciente não encontrado'];
        }

        return [
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'nome_completo' => $paciente->nome_completo,
            'data_nascimento' => $paciente->data_nascimento,
            'sexo' => $paciente->sexo,
            'numero_utente' => $paciente->numero_utente,
            'telemovel' => $paciente->telemovel,
            'morada' => $paciente->morada,
            'altura' => $paciente->altura,
            'peso' => $paciente->peso,
            'alergias' => $paciente->alergias,
            'doencas_cronicas' => $paciente->doencas_cronicas,
        ];
    }

    /**
     * PUT /api/profile
     * Atualizar dados do paciente
     */
    public function actionUpdate()
    {
        $user = Yii::$app->user->identity;
        $paciente = $user->paciente;

        if (!$paciente) {
            return ['error' => 'Paciente não encontrado'];
        }

        $request = Yii::$app->request->bodyParams;
        $fields = ['nome_completo', 'data_nascimento', 'sexo', 'telemovel', 'morada'];

        foreach ($fields as $field) {
            if (isset($request[$field])) {
                $paciente->$field = $request[$field];
            }
        }

        if (!$paciente->save()) {
            return ['error' => $paciente->getErrors()];
        }

        return ['success' => true, 'message' => 'Perfil atualizado com sucesso'];
    }

    /**
    * GET /api/paciente/alergias
    * Retorna apenas as alergias do paciente autenticado
    */
    public function actionAlergias()
    {
        $user = Yii::$app->user->identity;
        $paciente = $user->paciente;

        if (!$paciente) {
            return ['error' => 'Paciente não encontrado'];
        }

        return [
            'alergias' => $paciente->alergias
        ];
    }

    /**
    * GET /api/paciente/doencas
    * Retorna apenas as doenças crónicas do paciente autenticado
    */
    public function actionDoencas()
    {
        $user = Yii::$app->user->identity;
        $paciente = $user->paciente;

        if (!$paciente) {
            return ['error' => 'Paciente não encontrado'];
        }

        return [
            'doencas_cronicas' => $paciente->doencas_cronicas
        ];
    }
}
