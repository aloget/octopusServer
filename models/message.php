<?php

//require_once("config.php");

/**
 * Класс для обработки сообщений
 */
class Message
{
    //Свойства

    /**
     * @var int ID сообщения из БД
     */
    public $id = null;

    /**
     * @var int ID пользователя-отправителя
     */
    public $senderId = null;


    /**
     * @var int ID пользователя-получателя
     */
    public $recipientId = null;


    /**
     * @var string текст сообщения
     */
    public $message = null;


    /**
     * @var int дата отправки сообщения (timestamp)
     */
    public $dispatchTimestamp = null;


    /**
     * Конструирует объект сообщения
     * @param assoc Optional массив значений
     */


    public function __construct($data = array())
    {
        if (isset($data['id'])) $this->id = (int)$data['id'];
        if (isset($data['senderId'])) $this->senderId = (int)$data['senderId'];
        if (isset($data['recipientId'])) $this->recipientId = (int)$data['recipientId'];
        if (isset($data['message'])) $this->message = (string)$data['message'];
        if (isset($data['dispatchTimestamp'])) $this->dispatchTimestamp = (int)$data['dispatchTimestamp'];
    }


    public function fillWithForm($formValues)
    {
        $this->__construct($formValues);


        if (isset($formValues['dispatchTimestamp'])) {
            $dispatchDatetime = explode('T', $formValues['dispatchTimestamp']);

            if (count($dispatchDatetime) == 2) {
                list ($date, $time) = $dispatchDatetime;
                $theDate = explode('-', $date);
                if (count($theDate) == 3) {
                    list($y, $m, $d) = $theDate;
                    $theTime = explode(':', $time);
                    if (count($theTime) == 3) {
                        list($hr, $min, $sec) = $theTime;
                        $this->dispatchTimestamp = mktime($hr, $min, $sec, $m, $d, $y);
                    }
                }
            }
        }
    }


    /**
     * Возвращает объект сообщения с значениями, переданными с клиента
     * Устанавливает текущую дату в качестве значения $this->dispatchTime
     *
     * @param int id пользователя-отправителя
     * @param int id пользователя-получателя
     * @param string текст сообщения
     * @return Message объект сообщения с соответствующими значениями
     */

    public static function withClientData($senderId, $recipientId, $message)
    {
        $instance = new self();

        $instance->senderId = $senderId;
        $instance->recipientId = $recipientId;
        $instance->message = $message;
        $instance->dispatchTimestamp = time();

        return $instance;
    }


    /**
     * Возвращаем объект сообщения соответствующий заданному ID
     *
     * @param int ID сообщения
     * @return Message|false Объект сообщения или false, если сообщение не найдено или произошла ошибка
     */

    public static function getByUsersAndMessageId($sender_id, $recipient_id, $id)
    {
        try {
            $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $query = "SELECT * FROM messages WHERE ((senderId = :senderId AND recipientId = :recipientId) OR (senderId = :recipientId AND recipientId = :senderId)) AND id > :id";
            $execute = $connection->prepare($query);
            $execute->bindValue(":senderId", $sender_id, PDO::PARAM_INT);
            $execute->bindValue(":recipientId", $recipient_id, PDO::PARAM_INT);
            $execute->bindValue(":id", $id, PDO::PARAM_INT);
            $execute->execute();

            $list = array();
            while ($row = $execute->fetch()) {
                $message = new Message ($row);
                $list[] = $message;
            }
            $connection = null;
            return $list;
        } catch (Exception $exc) {
            return false;
        }
    }

    public static function getById($id)
    {
        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $query = "SELECT * FROM messages WHERE id = :id";
        $execute = $connection->prepare($query);
        $execute->bindValue(":id", $id, PDO::PARAM_INT);
        $execute->execute();
        $row = $execute->fetch();
        $connection = null;
        if ($row) return new Message ($row);
        else return false;
    }

    /**
     * Возвращает все объекты сообщений БД
     *
     * @param string Optional Столбец по которому сортируются сообщения (по умолчанию date DESC)
     * @return Array|false Массив сообщений или false, если произошла ошибка
     */

    public static function getList($order = "date DESC")
    {
        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $query = "SELECT * FROM messages";
        $execute = $connection->prepare($query);
        $execute->execute();

        $list = array();
        while ($row = $execute->fetch()) {
            $message = new Message ($row);
            $list[] = $message;
        }

        $connection = null;
        return $list;
    }


    /**
     * Работа с БД
     */

    /**
     * Вставляем текущий объект сообщения в БД
     */

    public function insert()
    {
        if (!is_null($this->id)) {
            trigger_error("Message::insert(): Attempt to insert
				a Message object that already has its ID property set.", E_USER_ERROR);
        }

        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $query = "INSERT INTO messages (senderId, recipientId, message, dispatchTimestamp)
		VALUES (:senderId, :recipientId, :message, :dispatchTimestamp)";
        $execute = $connection->prepare($query);
        $execute->bindValue(":senderId", $this->senderId, PDO::PARAM_INT);
        $execute->bindValue(":recipientId", $this->recipientId, PDO::PARAM_INT);
        $execute->bindValue(":message", $this->message, PDO::PARAM_STR);
        $execute->bindValue(":dispatchTimestamp", $this->dispatchTimestamp, PDO::PARAM_INT);

        $execute->execute();
        $this->id = $connection->lastInsertId();
        $connection = null;
    }

    /**
     * Обновляем текущий объект сообщения в БД
     */

    public function update()
    {
        if (is_null($this->id)) {
            trigger_error("Message::update(): Attempt to update
				a Message object that does not have its ID property set.");
        }

        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $query = "UPDATE messages
		SET senderId=:senderId, recipientId=:recipientId, message=:message, dispatchTimestamp=:dispatchTimestamp 
		WHERE id=:id";
        $execute = $connection->prepare($query);
        $execute->bindValue(":senderId", $this->senderId, PDO::PARAM_INT);
        $execute->bindValue(":recipientId", $this->recipientId, PDO::PARAM_INT);
        $execute->bindValue(":message", $this->message, PDO::PARAM_STR);
        $execute->bindValue(":dispatchTimestamp", $this->dispatchTimestamp, PDO::PARAM_INT);
        $execute->bindValue(":id", $this->id, PDO::PARAM_INT);

        $execute->execute();
        $connection = null;
    }


    /**
     * Удаляем текущий объект сообщения из БД
     */

    public function delete()
    {
        if (is_null($this->id)) {
            trigger_error("Message::delete(): Attempt to delete
				a Message object that does not have its ID property set.");
        }

        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $query = "DELETE FROM messages WHERE id = :id LIMIT 1";
        $execute = $connection->prepare($query);
        $execute->bindValue(":id", $this->id, PDO::PARAM_INT);
        $execute->execute();
        $connection = null;
    }
}