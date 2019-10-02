<?php
include_once BASEPATH.'/utils/utils.php';

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'admin');
define('DB_PASSWORD', '123');
define('DB_NAME', 'test');

class DB {
    private static $instance ;
    public function __construct(){
      if (DB::$instance){
        exit("Instance on DBConnection already exists.") ;
      } else {
      	DB::$instance = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

      	if(DB::$instance->connect_errno) {
      		exit("Не удалось подключиться к MySQL: (" . DB::$instance->connect_errno . ") " . DB::$instance->connect_error);
      	}
      	DB::$instance->set_charset("utf8");
      	DB::$instance->query("SET lc_time_names = 'ru_RU';");
      }
    }

    public static function connector(){
      if (!DB::$instance){
        new DB() ;
      }
      return DB::$instance ;
    }
}
?>
