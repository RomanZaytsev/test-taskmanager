<?php 
class Controller
{
	public $user = NULL;
	public function render($view, $parameters=[], $main_template = NULL) {
		if(!$main_template) $main_template = BASEPATH."/view/main.php";
		extract($parameters);
		ob_start();
		require($view);
		$_content = ob_get_clean();
		
		require($main_template);
	}
}
?>