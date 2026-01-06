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
            'only' => ['view', 'update', 'alergias', 'doencas', 'create-alergias', 'update-alergias','delete-alergias', 'create-doencas', 'update-doencas', 'delete-doencas'],
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
     * POST /api/paciente/create-alergias
     * Adicionar novas alergias para o paciente autenticado
     */
    public function actionCreateAlergias()
    {
        $user = Yii::$app->user->identity;
        $paciente = $user->paciente;

        if (!$paciente) {
            return ['error' => 'Paciente não encontrado'];
        }

        $novaAlergia = Yii::$app->request->bodyParams['alergia'] ?? '';

        if ($novaAlergia === '') {
            return ['success' => true, 'message' => 'Nenhuma alergia adicionada'];
        }

        // Adiciona a nova alergia, separando com vírgula se já houver outras
        $paciente->alergias = $paciente->alergias ? $paciente->alergias . ', ' . $novaAlergia : $novaAlergia;

        $paciente->save();

        return ['success' => true, 'message' => 'Alergia adicionada com sucesso'];
    }


    /**
     * PUT /api/paciente/update-alergias
     * Substituir todas as alergias do paciente autenticado
     */
    public function actionUpdateAlergias()
    {
        $user = Yii::$app->user->identity;
        $paciente = $user->paciente;

        if (!$paciente) {
            return ['error' => 'Paciente não encontrado'];
        }

        // Pega o valor do JSON ou null se não existir
        $paciente->alergias = Yii::$app->request->bodyParams['alergias'] ?? null;

        $paciente->save();

        return ['success' => true, 'message' => 'Alergias atualizadas com sucesso'];
    }

    /**
     * DELETE /api/paciente/delete-alergias
     * Apagar todas as alergias do paciente autenticado
     */
    public function actionDeleteAlergias()
    {
        $user = Yii::$app->user->identity;
        $paciente = $user->paciente;

        if (!$paciente) {
            return ['error' => 'Paciente não encontrado'];
        }

        $paciente->alergias = null;
        $paciente->save();

        return ['success' => true, 'message' => 'Todas as alergias foram removidas'];
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

    /** POST /api/paciente/create-doencas */
    public function actionCreateDoencas()
    {
        $user = Yii::$app->user->identity;
        $paciente = $user->paciente;

        if (!$paciente) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Paciente não encontrado'];
        }

        $novaDoencaCronica = Yii::$app->request->bodyParams['doencas_cronicas'] ?? '';

        if ($novaDoencaCronica === '') {
            return ['success' => true, 'message' => 'Nenhuma doença crónica adicionada'];
        }

        $paciente->doencas_cronicas = $paciente->doencas_cronicas ? $paciente->doencas_cronicas . ', ' . $novaDoencaCronica : $novaDoencaCronica;

        if (!$paciente->save()) {
            Yii::$app->response->statusCode = 400;
            return ['error' => $paciente->getErrors()];
        }

        return ['success' => true, 'message' => 'Doença crónica adicionada com sucesso'];
    }

    /** PUT /api/paciente/update-doencas */
    public function actionUpdateDoencas()
    {
        $user = Yii::$app->user->identity;
        $paciente = $user->paciente;

        if (!$paciente) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Paciente não encontrado'];
        }

        $doencas = Yii::$app->request->bodyParams['doencas_cronicas'] ?? null;

        if ($doencas === null) {
            return ['error' => 'Campo doencas_cronicas não informado'];
        }

        $paciente->doencas_cronicas = $doencas;

        if (!$paciente->save()) {
            Yii::$app->response->statusCode = 400;
            return ['error' => $paciente->getErrors()];
        }

        return ['success' => true, 'message' => 'Doenças crónicas atualizadas com sucesso'];
    }

    /** DELETE /api/paciente/delete-doencas */
    public function actionDeleteDoencas()
    {
        $user = Yii::$app->user->identity;
        $paciente = $user->paciente;

        if (!$paciente) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Paciente não encontrado'];
        }

        $paciente->doencas_cronicas = null;

        if (!$paciente->save()) {
            Yii::$app->response->statusCode = 400;
            return ['error' => $paciente->getErrors()];
        }

        return ['success' => true, 'message' => 'Todas as doenças crónicas foram removidas'];
    }
}
