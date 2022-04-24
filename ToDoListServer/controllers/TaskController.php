<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/9/2022
 * Time: 11:34 AM
 */

namespace app\controllers;


use app\models\Tasks;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\web\Controller;
use yii\web\Response;

class TaskController extends Controller
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
                'Access-Control-Allow-Credentials' => false,
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'except' => ['add', 'get-all', 'delete', 'index','update'],
            'authMethods' => [
                HttpBearerAuth::class,
                QueryParamAuth::class,
                JwtHttpBearerAuth::class
            ],
        ];
        return $behaviors;
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

    public function actionAdd()
    {
        $data = (array)json_decode(Yii::$app->request->getRawBody());
        $newTask = new Tasks();
        $newTask->title = $data['title'];
        $newTask->dateTime = $data['date'];
        $newTask->reminder = $data['reminder'];
        if ($newTask->validate() && $newTask->save()) {
            return ['msg' => 'ok', 'newTask' => $newTask];
        } else {
            return ['msg' => 'error', 'info' => $newTask->getErrors()];

        }
    }

    public function actionDelete($id)
    {
        $id = new \MongoDB\BSON\ObjectID($id);
        $task = Tasks::findOne(['_id' => $id]);
        if ($task->delete()) {
            return ['msg' => 'ok'];
        } else {
            return ['msg' => 'error', 'info' => $task->getErrors()];
        }
    }

    public function actionGetAll()
    {
        $tasks = Tasks::find()->all();
        $tasks = array_map(function ($task) {
            $task['_id'] = (string)$task['_id'];
            return $task;
        }, $tasks);

        return ['msg' => 'ok', 'tasks' => $tasks];
    }

    public function actionUpdate()
    {
        $data = (array)json_decode(Yii::$app->request->getRawBody());
        $id = new \MongoDB\BSON\ObjectID($data['id']);
        $task = Tasks::findOne(['_id' => $id]);
        $task->reminder = $data['reminder'];
        if ($task->save()) {
            return ['msg' => 'ok'];
        } else {
            return ['msg' => 'error', 'info' => $task->getErrors()];
        }


    }

}