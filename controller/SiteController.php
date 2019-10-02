<?php
class SiteController extends Controller
{
	public function actionIndex() {
		include_once BASEPATH.'/model/Tasks.php';
		$this->render(BASEPATH."/view/index.php", [
				'title' => 'Главная страница',
				'jscripts' => array(),
				'user' => $this->user,
				'breadcrumbs' => [],
		]);
	}
}
?>
