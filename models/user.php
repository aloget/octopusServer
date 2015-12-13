<?php

class User
{

    public $id = null;
    public $token = null;
    public $username = null;
    public $password = null;//md5



    public function __construct($data = array())
    {
        if (isset($data['id'])) $this->id = (int)$data['id'];
        if (isset($data['token'])) $this->token = (string)$data['token'];
        if (isset($data['username'])) $this->username = (string)$data['username'];
        if (isset($data['password'])) $this->password = (string)$data['password'];
    }

    public function fillWithForm($formValues)
    {
        $this->__construct($formValues);
    }



//возвращает новый объект User или false, если такой пользователь уже существует
    public static function newUserWithClientData($username, $password)
    {
        $user = User::getByUserName($username);
        if ($user == 0) {
            $instance = new self();

            $instance->username = $username;
            $instance->password = md5($password);

            $instance->token = md5(uniqid($username, true));

            return $instance;
        }
        return false;
    }



    public static function getById($id)
    {
        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $query = "SELECT * FROM users WHERE id = :id";
        $execute = $connection->prepare($query);
        $execute->bindValue(":id", $id, PDO::PARAM_INT);
        $execute->execute();
        $row = $execute->fetch();
        $connection = null;
        if ($row) return new User ($row);
        else return false;
    }


    public static function getByToken($token)
    {
        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $query = "SELECT * FROM users WHERE token = :token";
        $execute = $connection->prepare($query);
        $execute->bindValue(":token", $token, PDO::PARAM_INT);
        $execute->execute();
        $row = $execute->fetch();
        $connection = null;
        if ($row) return new User ($row);
        else return false;
    }


    public static function getByUserName($userName)
    {
        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $query = "SELECT * FROM users WHERE username = :username";
        $execute = $connection->prepare($query);
        $execute->bindValue(":username", $userName, PDO::PARAM_INT);
        $execute->execute();
        $row = $execute->fetch();
        $connection = null;
        if ($row) return new User ($row);
        else return false;
    }

    public static function getList($order = "username ASC")
    {
        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $query = "SELECT * FROM users";
        $execute = $connection->prepare($query);
        $execute->execute();

        $list = array();
        while ($row = $execute->fetch()) {
            $user = new User ($row);
            $list[] = $user;
        }

        $connection = null;
        return $list;
    }

    public static function getUsersBesidesToken($token)
    {
        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $query = "SELECT * FROM users WHERE token != :token";
        $execute = $connection->prepare($query);
        $execute->bindValue(":token", $token, PDO::PARAM_STR);
        $execute->execute();

        $list = array();
        while ($row = $execute->fetch()) {
            $user = new User ($row);
            $list[] = $user;
        }
        $connection = null;
        return $list;
    }

    /**
     * Работа с БД
     */

    public function insert()
    {
        if (!is_null($this->id)) {
            trigger_error("User::insert(): Attempt to insert
				a User object that already has its ID property set.", E_USER_ERROR);
        }

        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $query = "INSERT INTO users (username, password, token)
		VALUES (:username, :password, :token)";
        $execute = $connection->prepare($query);
        $execute->bindValue(":username", $this->username, PDO::PARAM_STR);
        $execute->bindValue(":password", $this->password, PDO::PARAM_STR);
        $execute->bindValue(":token", $this->token, PDO::PARAM_STR);
        $execute->execute();
        $this->id = $connection->lastInsertId();
        $connection = null;
    }


    public function update()
    {
        if (is_null($this->id)) {
            trigger_error("User::update(): Attempt to update
				a User object that does not have its ID property set.");
        }

        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $query = "UPDATE users
		SET username=:username, password=:password, token=:token
		WHERE id=:id";
        $execute = $connection->prepare($query);
        $execute->bindValue(":username", $this->username, PDO::PARAM_STR);
        $execute->bindValue(":password", $this->password, PDO::PARAM_STR);
        $execute->bindValue(":token", $this->token, PDO::PARAM_STR);
        $execute->bindValue(":id", $this->id, PDO::PARAM_INT);
        $execute->execute();
        $connection = null;
    }


    public function delete()
    {
        if (is_null($this->id)) {
            trigger_error("User::delete(): Attempt to delete
				a User object that does not have its ID property set.");
        }

        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $query = "DELETE FROM users WHERE id = :id LIMIT 1";
        $execute = $connection->prepare($query);
        $execute->bindValue(":id", $this->id, PDO::PARAM_INT);
        $execute->execute();
        $connection = null;
    }
}