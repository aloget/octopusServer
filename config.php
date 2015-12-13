<?php

ini_set("display_errors", true);
ini_set('memory_limit', '-1');
date_default_timezone_set('Europe/Moscow');

define("DB_DSN", "mysql:host=octopuschat;dbname=octopus_db");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("ADMIN_USERNAME", "admin");
define("ADMIN_PASSWORD", "password");
define("MODELS", "models");
define("TEMPLATE_PATH", "templates");
define("USER", MODELS."/user.php");
define("MESSAGE", MODELS."/message.php");
require_once(USER);
require_once(MESSAGE);