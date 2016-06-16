<?php

include 'snake.class.php';

$view = new Snake('view.tpl');
//echo $view->block('head')->render();

$h = $view->block('html')
	->assign(
		array(
			'include' =>$view->block('head')->render(),
			'body'    =>$view->block('nav')->render(),
			'dropdown'=>$view->block('php')->assign(
							array(
								'time'=>date('Y-m-d H:i:s')
							)
						)->render(),
		)
	)
	->assign(array('dropdown'=>$view->block('php')->assign(array('time'=>date('Y-m-d H:i:s')))->render()))
	->assign(array('dropdown'=>$view->block('php')->assign(array('time'=>date('Y-m-d H:i:s')))->render()))
	->assign(array('dropdown'=>$view->block('php')->assign(array('time'=>date('Y-m-d H:i:s')))->render()))
	->assign(array('dropdown'=>$view->block('php')->assign(array('time'=>date('Y-m-d H:i:s')))->render()))
	->assign(array('dropdown'=>$view->block('php')->assign(array('time'=>date('Y-m-d H:i:s')))->render()))
	->assign(array('dropdown'=>$view->block('php')->assign(array('time'=>date('Y-m-d H:i:s')))->render()))
	->assign(array('dropdown'=>$view->block('php')->assign(array('time'=>date('Y-m-d H:i:s')))->render()))
	->render();

echo $h;