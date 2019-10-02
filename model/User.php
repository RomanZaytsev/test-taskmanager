<?php

class User
{
	public static $currentUser;
	public $id;
	public $name;
	public $email;
	public $hash;

    public function create($params) {
    	$mysqli = DB::connector();
    		//	$result['textStatus'] = "Регистрация временно приостановлена!";
    		//	print(json_encode($result));
			//	exit(0);

    		if(sql_exist($mysqli,"SELECT * FROM user WHERE email='".$mysqli->real_escape_string($params['email'])."'")) {
	    		$result['textStatus'] = "E-mail busy";
	    		return $result;
    		}
    		$query = "INSERT INTO user(name,email,hash)
    							VALUES('".$mysqli->real_escape_string($params['name'])."',
										'".$mysqli->real_escape_string($params['email'])."',
	 									'".$mysqli->real_escape_string($params['hash'])."')";
			$result['query'] = $query;
    		if($mysqli->query($query)) {
    			$result['id'] = $mysqli->insert_id;
    			$result['textStatus'] = "Created";
    		} else {
    			$result['textStatus'] = "Ошибка при создании учётной записи: (".$mysqli->connect_errno.") ".$mysqli->connect_error;
    		}
    		return $result;
    	}
    public function getuser($params) {
    	$mysqli = DB::connector();
    		$result = [];
    		if($params['id']) {
				$query = "SELECT * FROM user WHERE id='".intval($params['id'])."'";
			} else {
				if($params['email']) {
					$query = "SELECT * FROM user WHERE email='".$mysqli->real_escape_string($params['email'])."'";
				} else {
					return $result;
				}
			}
			$queryResult = $mysqli->query($query);
			$row = $queryResult->fetch_object();
	    	$queryResult->close();
    		if(!$row) {
	    		$result['textStatus'] = "User was not found";
    		} else {
    			$result['profile'] = $row;
	    		$result['textStatus'] = "OK";
    		}
	    	return $result;
    }

    public static function current() {
    	$result = NULL;
    	if(self::$currentUser) {
    		$result = self::$currentUser;
    	}
		else{
    		$mysqli = DB::connector();
			$cookie = $_COOKIE;
			if(@$cookie['id'] != NULL AND @$cookie['hash'] != NULL) {
				$id = $cookie['id'];
				$hash = $cookie['hash'];
				$query = "
					SELECT
					a.*,b.name,b.email
					FROM session a
					JOIN user b
					ON a.user_id = b.id
					WHERE
						a.id = ".intval($id)."
					AND
						a.hash = '".$mysqli->real_escape_string($hash)."'";
				$queryResult = $mysqli->query($query);
				$row = $queryResult->fetch_object();
		    	$queryResult->close();
		    	if($row) {
					$result = new user();
					$result->id = $row->id;
					$result->name = $row->name;
					$result->email = $row->email;
					$result->hash = $row->hash;
					self::$currentUser = $result;
		    	}
			}
		}
		return $result;
    }
}

?>
