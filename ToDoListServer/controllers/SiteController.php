<?php

namespace app\controllers;

use app\models\Users;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => ['http://localhost:4200', 'http://localhost'],
                'Access-Control-Request-Method' => ['*'],
                'Access-Control-Allow-Methods' => ['POST', 'PUT', 'OPTIONS', 'GET'],
                'Access-Control-Allow-Headers' => ['*'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'except' => ['login', 'signup', 'index'],
            'authMethods' => [
                HttpBearerAuth::class,
                QueryParamAuth::class,
                JwtHttpBearerAuth::class
            ],
        ];
        return $behaviors;
    }


    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }


    public function actionIndex()
    {
        return ['msg' => 'ok', 'info ' => 'It is working'];
    }

    public function actionLogin()
    {
        $data = (array)json_decode(yii::$app->request->getRawBody());
        if (!$data['email'] || !$data['password']) {
            return ['msg' => 'error', 'info' => 'There are missing parameters'];
        }
        $user = Users::findOne(['email' => trim($data['email'])]);
        if ($user) {
            if (!yii::$app->security->validatePassword(trim($data['password']), $user->password)) {
                return ['msg' => 'error', 'info' => 'This is incorrect password'];
            }
            $time = time();
            $accessToken = Yii::$app->jwt->getBuilder()
                ->issuedBy('http://nawlomuhammad.com')// Configures the issuer (iss claim)
                ->permittedFor('http://nawlomuhammad.org')// Configures the audience (aud claim)
                ->identifiedBy('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
                ->issuedAt($time)// Configures the time that the token was issue (iat claim)
                ->expiresAt($time + 3600 * 24)// Configures the expiration time of the token (exp claim)
                ->withClaim('user', ['name' => $user->username, '_id' => (string)$user->_id])// Configures a new claim, called "uid"
                ->getToken(); // Retrieves the generated token
            $user->accessToken = (string)$accessToken;
            $user->save();
            return ['msg' => 'ok', 'accessToken' => (string)$accessToken];
        } else {
            return ['msg' => 'error', 'info' => 'There is no user that has this email'];
        }
    }

    public function actionSignup()
    {
        $data = (array)json_decode(yii::$app->request->getRawBody());
        if (!$data['username'] || !$data['password'] || !$data['email']) {
            return ['msg' => 'error', 'info' => 'There are missing parameters'];
        }

        $user = Users::findOne(['email' => trim($data['email'])]);
        if ($user) {
            return ['msg' => 'error', 'info' => 'This email is already exist'];
        }

        $user = new Users();
        $user->username = trim($data['username']);
        $user->email = trim($data['email']);
        $user->password = Yii::$app->security->generatePasswordHash(trim($data['password']));
        if ($user->validate() && $user->save()) {
            return ['msg' => 'ok', 'user' => $user];
        } else {
            return ['msg' => 'error', 'info' => $user->getErrors()];
        }
    }

}
