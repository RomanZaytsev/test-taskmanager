<?php

class Tasks
{
    public static function getAll($condition=[]) {
    	$mysqli = DB::connector();
    	$result = [];
			$query = "SELECT ";
			if(isset( $condition['select'] )) {
				$query .= $mysqli->real_escape_string($condition['select']);
			} else {
				$query .= "*";
			}
			$query .= " FROM tasks";
			if(is_array( @$condition['sort'] )) {
				$query .= " ORDER By ";
				foreach ($condition['sort'] as $key => $value) {
					$query .= $key." ".$mysqli->real_escape_string($value);
				}
			}
			if(isset( $condition['limit'] )) {
				$query .= " LIMIT ".$mysqli->real_escape_string($condition['limit']);
			}
			if(isset( $condition['offset'] )) {
				$query .= " OFFSET ".$mysqli->real_escape_string($condition['offset']);
			}
			$queryResult = $mysqli->query($query);
			while($row = $queryResult->fetch_object()) {
				$result[] = $row;
			};
	    return $result;
    }
    public static function getById($id) {
    	$mysqli = DB::connector();
    	$result = null;
			$query = "SELECT * FROM tasks WHERE id=".intval($id);
			$queryResult = $mysqli->query($query);
			while($row = $queryResult->fetch_object()) {
				return $row;
			};
	    return $result;
    }

    public static function post($params) {
			$result = ['validate'=>[
				'username'=>empty(@$params['username']) ? "не заполнено" : true,
				'email'=>empty(@$params['email']) ? "не заполнено" : ((!filter_var(@$params['email'], FILTER_VALIDATE_EMAIL)) ? "email не валиден" : true),
				'text'=>empty(@$params['text']) ? "не заполнено" : true,
			]];
			foreach ($result['validate'] as $key => $value) {
				if($value !== true) {
	    		return $result;
				}
			}

    	$mysqli = DB::connector();
    	$query = "INSERT INTO tasks(`username`,`email`,`text`)
    							VALUES('".$mysqli->real_escape_string($params['username'])."',
										'".$mysqli->real_escape_string($params['email'])."',
	 									'".$mysqli->real_escape_string($params['text'])."')";
    	$result['query'] = $query;
    	if($mysqli->query($query)) {
    		$result['id'] = $mysqli->insert_id;
    		$result['status'] = "OK";
    	} else {
    		$result['status'] = "Ошибка при добавлении: (".$mysqli->connect_errno.") ".$mysqli->connect_error;
    	}
    	return $result;
    }

    public static function update($params) {
    	$user = User::current();
    	if(@$user->name != 'admin') {
    		$result['status'] = "Нет прав для редактирования";
        $result['redirect'] = "/user/login";
    		return $result;
    	}
      $old = Tasks::getById($params['id']);
      if($old->text != $params['text'] || $old->username != $params['username'] || $old->email != $params['email']) {
        $params['checked'] = 1;
      }
    	$mysqli = DB::connector();
    	$setvalue = "";
    	foreach ($params as $key=>$value) {
    		if($setvalue) $setvalue .= ",";
    		$setvalue .=  $key . "='".$mysqli->real_escape_string($value)."'";
    	}
    	$query = "UPDATE tasks SET ".$setvalue." WHERE id=".$mysqli->real_escape_string($params['id']);
    	$result['query'] = $query;
    	if($mysqli->query($query)) {
    		$result['status'] = "OK";
    	} else {
    		$result['status'] = "Ошибка при обновлении: (".$mysqli->connect_errno.") ".$mysqli->connect_error;
    	}
    	return $result;
    }
}

?>
