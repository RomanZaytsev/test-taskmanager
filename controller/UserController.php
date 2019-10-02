<?php
class UserController extends Controller
{
	public function actionLogin() {
		if($this->user) {
			$this_section = &$display["index"];
			header("Location:"."/");
		}
		else {
			$this->render(BASEPATH."/view/user/login.php", [
					'title' => 'Вход',
					'jscripts' => array(),
					'user' => $this->user,
					'breadcrumbs' => ["Главная" => "/"],
			]);
		}
	}

	public function actionRegform() {
		if(User::current()) {
			$this_section = &$display["index"];
			header("Location:"."/");
		}
		else {
			$this->render(BASEPATH."/view/user/regform.php", [
					'title' => 'Регистрация',
					'jscripts' => array(),
					'user' => $this->user,
					'breadcrumbs' => ["Главная" => "/"],
			]);
		}
	}

	public function actionProfile() {
		if(!User::current()) {
			$this_section = &$display["index"];
			header("Location:"."/");
		}
		else {
			$this->render(BASEPATH."/view/user/profile.php", [
					'title' => 'Профиль',
					'jscripts' => array(),
					'user' => $this->user,
					'breadcrumbs' => ["Главная" => "/"],
			]);
		}
	}

	public function actionAccountCreate() {
		include_once(BASEPATH."/model/User.php");
		$model = new User();
		$result = $model->create($_POST);

		if(isset($result['http_response_code'])) {
			http_response_code($result['http_response_code']);
		}
		echo json_encode($result);
	}

	public function actionAuthorization() {
		include_once(BASEPATH."/model/Session.php");
		$model = new Session();
		$result = $model->login($_POST);

		if(isset($result['http_response_code'])) {
			http_response_code($result['http_response_code']);
		}
		echo json_encode($result);
	}

	public function actionAuthorizationLogout() {
		include_once(BASEPATH."/model/Session.php");
		$model = new Session();
		$result = $model->logout($_POST);

		if(isset($result['http_response_code'])) {
			http_response_code($result['http_response_code']);
		}
		echo json_encode($result);
	}
}
?>
