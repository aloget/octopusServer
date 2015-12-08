<?php

//require_once("config.php");

/**
 * Класс для обработки пользователей
 */
class User
{
    //Свойства

    /**
     * @var int ID пользователя из БД
     */
    public $id = null;

    /**
     * @var string токен пользователя
     */
    public $token = null;


    /**
     * @var string имя пользователя
     */
    public $username = null;


    /**
     * @var string md5-шифрованный пароль пользователя
     */
    public $password = null;


    /**
     * Конструирует объект пользователя
     * @param assoc Optional массив значений
     */
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


    /**
     * Возвращает объект пользователя с значениями, переданными с клиента
     * Генерирует и устанавливает значение $this->token
     *
     * @param string имя пользователя
     * @param string пароль
     * @return User объект пользователя с соответствующими значениями имени пользователя и пароля
     */
    public static function withClientData($username, $password)
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

    /**
     * Возвращаем объект пользователя соответствующий заданному ID
     *
     * @param int ID пользователя
     * @return User|false Объект пользователя или false, если пользователь не найден или произошла ошибка
     */
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

    /**
     * Возвращаем объект пользователя соответствующий заданному UserName
     *
     * @param int ID пользователя
     * @return User|false Объект пользователя или false, если пользователь не найден или произошла ошибка
     */
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

    /**
     * Возвращает все объекты пользователей БД
     *
     * @param string Optional Столбец по которому сортируются пользователи (по умолчанию username ASC)
     * @return Array|false Массив пользователей или false, если произошла ошибка
     */
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


    /**
     * Работа с БД
     */

    /**
     * Вставляем текущий объект пользователя в БД
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

    /**
     * Обновляем текущий объект пользователя в БД
     */
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

    /**
     * Удаляем текущий объект пользователя из БД
     */
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