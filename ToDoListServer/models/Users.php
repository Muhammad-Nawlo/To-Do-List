<?php

namespace app\models;

use Yii;

/**
 * This is the model class for collection "users".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $username
 * @property mixed $email
 * @property mixed $password
 * @property mixed $accessToken
 * @property mixed $authKey
 */
class Users extends \yii\mongodb\ActiveRecord implements \yii\web\IdentityInterface
{
    public static function collectionName()
    {
        return ['todolist', 'users'];
    }

    public function attributes()
    {
        return [
            '_id',
            'username',
            'email',
            'password',
            'accessToken',
            'authKey'
        ];
    }

    public function rules()
    {
        return [
            [['username', 'email', 'password', 'accessToken', 'authKey'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'accessToken' => 'Access Token',
            'authKey' => 'Auth Key',
        ];
    }

    public static function findIdentity($id)
    {
        return self::findOne(['_id' => $id]);
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['accessToken' => $token]);

    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->_id;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
}
