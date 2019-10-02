<?php

class TaskController extends Controller
{
	public function actionPost() {
		include_once BASEPATH.'/model/Tasks.php';
		$result = Tasks::post($_POST);
		if(isset($result['http_response_code'])) {
			http_response_code($result['http_response_code']);
		}
		echo json_encode($result);
	}

	public function actionUpdate() {
		include_once BASEPATH.'/model/Tasks.php';
		$result = Tasks::update($_POST);
		if(isset($result['http_response_code'])) {
			http_response_code($result['http_response_code']);
		}
		echo json_encode($result);
	}

	public function actionAccept() {
		include_once BASEPATH.'/model/Tasks.php';
		$result = Tasks::update($_POST);
		if(isset($result['http_response_code'])) {
			http_response_code($result['http_response_code']);
		}
		echo json_encode($result);
	}

	public function actionList() {
		include_once BASEPATH.'/model/Tasks.php';
		$current = @$_POST['current'] ? $_POST['current'] : 1;
		$rowCount = @$_POST['rowCount'] ? $_POST['rowCount'] : 3;
		$condition = $rowCount > 0 ? [ 'limit'=>$rowCount, 'offset'=>($current-1)*$rowCount ] : [];
		$condition['sort'] = @$_POST['sort'];
		$rows = Tasks::getAll($condition);
		if(@$_POST['id']=="bootgrid") {
			foreach ($rows as $row) {
				foreach ($row as $key => $value) {
					$row->$key = htmlentities($value);
				}
			}
		}
		$result = [
			"current" => $current,
			"rowCount" => $rowCount,
			"rows" => $rows,
			"total" => Tasks::getAll([ "select"=>"count(*) count" ])[0]->count
		];
		echo json_encode($result);
	}
}

?>
