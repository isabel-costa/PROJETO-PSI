<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use common\models\User;
use common\models\Paciente;

class AuthController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBasicAuth::class,
            'except' => ['login', 'registar'],
        ];

        return $behaviors;
    }

    /**
     * Registo de paciente
     */
    public function actionRegistar()
    {
        $request = Yii::$app->request->post();

        $camposObrigatorios = [
            'username',
            'email',
            'password',
            'nome_completo',
            'data_nascimento',
            'sexo',
            'numero_utente',
            'telemovel',
            'morada'
        ];

        foreach ($camposObrigatorios as $campo) {
            if (empty($request[$campo])) {
                return ['error' => "O campo '{$campo}' é obrigatório"];
            }
        }

        if (User::find()->where(['email' => $request['email']])->exists()) {
            return ['error' => 'O email inserido já existe'];
        }

        if (User::find()->where(['username' => $request['username']])->exists()) {
            return ['error' => 'O username inserido já existe'];
        }

        $user = new User();
        $user->username = $request['username'];
        $user->email = $request['email'];
        $user->setPassword($request['password']);
        $user->generateAuthKey();
        $user->status = 10;
        $user->created_at = time();
        $user->updated_at = time();

        if (!$user->save()) {
            return ['error' => $user->getErrors()];
        }

        // Atribuir role de paciente
        $auth = Yii::$app->authManager;
        $pacienteRole = $auth->getRole('pacient');
        $auth->assign($pacienteRole, $user->id);

        // Registo de paciente
        $paciente = new Paciente();
        $paciente->user_id = $user->id;
        $paciente->nome_completo = $request['nome_completo'];
        $paciente->data_nascimento = $request['data_nascimento'];
        $paciente->sexo = $request['sexo'];
        $paciente->numero_utente = $request['numero_utente'];
        $paciente->telemovel = $request['telemovel'];
        $paciente->morada = $request['morada'];
        $paciente->altura = $request['altura'] ?? null;
        $paciente->peso = $request['peso'] ?? null;
        $paciente->alergias = $request['alergias'] ?? null;
        $paciente->doencas_cronicas = $request['doencas_cronicas'] ?? null;
        $paciente->data_registo = date('Y-m-d H:i:s');

        if (!$paciente->save()) {
            $user->delete(); // Elimina o user se o paciente não for criado
            return ['error' => $paciente->getErrors()];
        }

        return [
            'success' => true,
            'message' => 'Conta de paciente criada com sucesso',
            'user_id' => $user->id,
            'paciente_id' => $paciente->id,
            'auth_key' => $user->auth_key,
        ];
    }

    /**
     * Login de paciente
     */
    public function actionLogin()
    {
        $request = Yii::$app->request->post();

        if (empty($request['username']) || empty($request['password'])) {
            return ['error' => 'Username e password são obrigatórios'];
        }

        $user = User::findOne(['username' => $request['username']]);

        if (!$user || !$user->validatePassword($request['password'])) {
            return ['error' => 'Username ou password inválidos'];
        }

        $roles = Yii::$app->authManager->getRolesByUser($user->id);
        if (!isset($roles['pacient'])) {
            return ['error' => 'Apenas pacientes podem aceder à app mobile'];
        }

        $paciente = $user->paciente;
        if (!$paciente) {
            return ['error' => 'Conta de paciente inválida'];
        }

        return [
            'success' => true,
            'message' => 'Login efetuado com sucesso',
            'user_id' => $user->id,
            'auth_key' => $user->auth_key,
            'paciente_id' => $paciente->id,
            'nome_completo' => $paciente->nome_completo,
        ];
    }
}
