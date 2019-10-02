<?php
class Session
{
    public function login($params)
    {
      if(empty($params['login'])) {
        $result['textStatus'] = "Все поля обязательны для заполнения";
        $result['http_response_code'] = "200";
        return $result;
      }
    	$mysqli = DB::connector();
			$query = "SELECT * FROM user WHERE name='".$mysqli->real_escape_string($params['login'])."' OR name='".$mysqli->real_escape_string($params['login'])."'";
			$queryResult = $mysqli->query($query);
			$row = $queryResult->fetch_object();
	    	$queryResult->close();
    		if(!$row) {
	    		$result['textStatus'] = "Неверный логин";
    			$result['http_response_code'] = "200";
    			return $result;
    		}
    		if($row->hash !== $params['hash']) {
	    		$result['textStatus'] = "Неверный пароль";
    			return $result;
    		}

			$access_keys = "";
			$access_values = "";
			foreach ($row as $key => $value) {
				if(strpos($key,"access_") !== FALSE) {
					$access_keys .= ",".$key;
					$access_values .= ",".$value;
				}
			}
			$hashSession = sha1($params['hash'].gensalt(15));
    		$query = "INSERT INTO session(user_id,hash".$access_keys.")
    							VALUES(".$row->id.",
	 									'".$hashSession."'
	 									".$access_values.")";
			$result['query'] = $query;
    		if($mysqli->query($query)) {
    			$result['id'] = $mysqli->insert_id;
    			$result['hash'] = $hashSession;
    			$result['textStatus'] = "OK";
    			$result['http_response_code'] = "201";
    			return $result;
    		}
    		$result['textStatus'] = "Ошибка при созданииs авторизационной сессии: (".$mysqli->connect_errno.") ".$mysqli->connect_error;
			return $result;
    	}
    public function logout($params) {
    	$mysqli = DB::connector();
			$user = my_auth($mysqli, $_COOKIE);
			if(!$user) self::onError("Ошибка доступа!");
    		$query = "DELETE FROM session WHERE id=".intval($_COOKIE['id']);
			$result['query'] = $query;
    		if($mysqli->query($query)) {
    			$result['http_response_code'] = "201";
    			$result['textStatus'] = "Logged out";
    			return $result;
    		}
    		$result['textStatus'] = "Ошибка: (".$mysqli->connect_errno.") ".$mysqli->connect_error;
    		return $result;
    	}
    public static function onError($text) {
    	$result['textStatus'] = $text;
    	return $result;
    }
}

?>
