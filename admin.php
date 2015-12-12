<?php

require_once("config.php");

session_start();
$action = isset($_GET['action']) ? $_GET['action'] : "";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

if ($action != "login" && $action != "logout" && !$username) {
    login();
    exit;
}

switch ($action) {
    case 'login':
        login();
        break;
    case 'logout':
        logout();
        break;
    case 'newMessage':
        newMessage();
        break;
    case 'editMessage':
        editMessage();
        break;
    case 'deleteMessage':
        deleteMessage();
        break;
    case 'listMessages':
        listMessages();
        break;
    case 'newUser':
        newUser();
        break;
    case 'editUser':
        editUser();
        break;
    case 'deleteUser':
        deleteUser();
        break;
    default:
        listUsers();
}

function login()
{
    $results = array();
    $results['pageTitle'] = "Admin Login | Octopus";

    if (isset($_POST['login'])) {
        //Пользователь ввел данные в форму - попытка авторизации

        if ($_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD) {
            $_SESSION['username'] = ADMIN_USERNAME;
            header("Location: admin.php");
        } else {
            //Ошибка авторизации
            $results['errorMessage'] = "Неверный логин или пароль. Повторите попытку!";
            require(TEMPLATE_PATH . "/admin/loginForm.php");
        }
    } else {
        //Пользователь еще не получил форму: выводим форму авторизации
        require(TEMPLATE_PATH . "/admin/loginForm.php");
    }

}

function logout()
{
    unset($_SESSION['username']);
    header("Location: admin.php");
}

function newMessage()
{
    $results = array();
    $results['pageTitle'] = "New Message";
    $results['formAction'] = "newMessage";

    if (isset($_POST['saveChanges'])) {
        $message = new Message();
        $message->fillWithForm($_POST);
        $message->insert();
        header("Location: admin.php?action=listMessages&status=changesSaved");
    } elseif (isset($_POST['cancel'])) {
        //Пользователь сбросил результаты редактирования
        header("Location: admin.php?action=listMessages");
    } else {
        //Пользователь еще не получил форму - выводим ее
        require(TEMPLATE_PATH . "/admin/editMessage.php");
    }
}

function editMessage()
{
    $results = array();
    $results['pageTitle'] = "Edit Message";
    $results['formAction'] = "editMessage";

    if (isset($_POST['saveChanges'])) {
        //Пользователь получил форму редактирования: сохраняем изменения

        if (!$message = Message::getById((int)$_POST['messageId'])) {
            header("Location: admin.php?action=listMessages&error=messageNotFound");
            return;
        }

        $message->fillWithForm($_POST);
        $message->update();
        header("Location: admin.php?action=listMessages&status=changesSaved");
    } elseif (isset($_POST['cancel'])) {
        //Пользователь отказался от результатов редактирования
        header("Location: admin.php?action=listMessages");
    } else {
        //Пользователь еще не получил форму - выводим ее
        $results['message'] = Message::getById((int)$_GET['messageId']);
        require(TEMPLATE_PATH . "/admin/editMessage.php");
    }
}

function deleteMessage()
{
    if (!$message = Message::getById((int)$_GET['messageId'])) {
        header("Location: admin.php?action=listMessages&error=messageNotFound");
        return;
    }

    $message->delete();
    header("Location: admin.php?action=listMessages&status=messageDeleted");
}

function listMessages()
{
    $results = array();
    $data = Message::getList();
    $results['messages'] = $data;
    $results['pageTitle'] = "All Messages";

    if (isset($_GET['error'])) {
        if ($_GET['error'] == "messageNotFound") $results['errorMessage'] = "Error: Message not found.";
    }

    if (isset($_GET['status'])) {
        if ($_GET['status'] == "changesSaved") $results['statusMessage'] = "Your changes have been saved.";
        if ($_GET['status'] == "messageDeleted") $results['statusMessage'] = "Message deleted.";
    }

    require(TEMPLATE_PATH . "/admin/listMessages.php");
}

function newUser()
{
    $results = array();
    $results['pageTitle'] = "New User";
    $results['formAction'] = "newUser";

    if (isset($_POST['saveChanges'])) {
        $user = new User($_POST);
        $user->password = md5($user->password);
        $user->token = md5(uniqid($user->username, true));
        $user->insert();
        header("Location: admin.php?status=changesSaved");
    } elseif (isset($_POST['cancel'])) {
        //Пользователь сбросил результаты редактирования
        header("Location: admin.php");
    } else {
        //Пользователь еще не получил форму - выводим ее
        require(TEMPLATE_PATH . "/admin/editUser.php");
    }
}

function editUser()
{
    $results = array();
    $results['pageTitle'] = "Edit User";
    $results['formAction'] = "editUser";

    if (isset($_POST['saveChanges'])) {
        //Пользователь получил форму редактирования: сохраняем изменения

        if (!$user = User::getById((int)$_POST['userId'])) {
            header("Location: admin.php?error=userNotFound");
            return;
        }

        $user->fillWithForm($_POST);
        $user->password = md5($user->password);
        $user->token = md5(uniqid($user->username, true));
        $user->update();
        header("Location: admin.php?status=changesSaved");
    } elseif (isset($_POST['cancel'])) {
        //Пользователь отказался от результатов редактирования
        header("Location: admin.php");
    } else {
        //Пользователь еще не получил форму - выводим ее
        $results['user'] = User::getById((int)$_GET['userId']);
        require(TEMPLATE_PATH . "/admin/editUser.php");
    }
}

function deleteUser()
{
    if (!$user = User::getById((int)$_GET['userId'])) {
        header("Location: admin.php?error=userNotFound");
        return;
    }

    $user->delete();
    header("Location: admin.php?status=userDeleted");
}

function listUsers()
{
    $results = array();
    $data = User::getList();
    $results['users'] = $data;
    $results['pageTitle'] = "All Users";

    if (isset($_GET['error'])) {
        if ($_GET['error'] == "userNotFound") $results['errorMessage'] = "Error: User not found.";
    }

    if (isset($_GET['status'])) {
        if ($_GET['status'] == "changesSaved") $results['statusMessage'] = "Your changes have been saved.";
        if ($_GET['status'] == "userDeleted") $results['statusMessage'] = "User deleted.";
    }

    require(TEMPLATE_PATH . "/admin/listUsers.php");
}