<?php

$list_side = array(
	array(
		'class' => 'active',
		'link'  => '#',
		'text'  => 'Home',
	),
	array(
		'class' => '',
		'link'  => '#',
		'text'  => 'Profile',
	),
	array(
		'class' => '',
		'link'  => '#',
		'text'  => 'Messages',
	),
	array(
		'class' => '',
		'link'  => '#',
		'text'  => 'About',
	),
);

$list_thum = array(
	array(
		'src'=>'http://www.easytravel.com.tw/ehotel/hotelimg/himg/7326_pf.jpg',
		'title'=>'Location',
		'content'=>'Contents'
	),
	array(
		'src'=>'http://www.easytravel.com.tw/ehotel/hotelimg/himg/8106_pf.jpg',
		'title'=>'Location',
		'content'=>'Contents'
	),
	array(
		'src'=>'http://www.easytravel.com.tw/ehotel/hotelimg/himg/6790_pf.jpg',
		'title'=>'Location',
		'content'=>'Contents'
	),
	array(
		'src'=>'http://www.easytravel.com.tw/ehotel/hotelimg/himg/6790_pf.jpg',
		'title'=>'Location',
		'content'=>'Contents'
	),
	array(
		'src'=>'http://www.easytravel.com.tw/ehotel/hotelimg/himg/6790_pf.jpg',
		'title'=>'Location',
		'content'=>'Contents'
	),
	array(
		'src'=>'http://www.easytravel.com.tw/ehotel/hotelimg/himg/6790_pf.jpg',
		'title'=>'Location',
		'content'=>'Contents'
	),
);

$list_banner_spin = array(
	array(
		'class' => 'active',
		'next'  => 0,
	),
	array(
		'class' => '',
		'next'  => 1,
	),
	array(
		'class' => '',
		'next'  => 2,
	),
	array(
		'class' => '',
		'next'  => 3,
	),
);

$list_banner_item = array(
	array(
		'class' => 'active',
		'src'   => 'http://www.jssor.com/img/home/02.jpg',
	),
	array(
		'class' => '',
		'src'   => 'http://www.jssor.com/img/home/02.jpg',
	),
	array(
		'class' => '',
		'src'   => 'http://www.jssor.com/img/home/02.jpg',
	),
	array(
		'class' => '',
		'src'   => 'http://www.jssor.com/img/home/02.jpg',
	),
);

$list_nav_left = array(
	array(
		'class' => 'active',
		'href'  => '#',
		'text'  => 'Link1',
	),
	array(
		'class' => '',
		'href'  => '#',
		'text'  => 'Link2',
	),
	// dropdown
	'Dropdown1' => array(
		array(
			'href'  => '#',
			'text'  => 'Action',
		),
		array(
			'href'  => '#',
			'text'  => 'Another action',
		),
		array(
			'href'  => '#',
			'text'  => 'Something else here',
		),
	),
	array(
		'class' => '',
		'href'  => '#',
		'text'  => 'Link3',
	),
	// dropdown
	'Dropdown2' => array(
		array(
			'href'  => '#',
			'text'  => 'Action',
		),
		array(
			'href'  => '#',
			'text'  => 'Another action',
		),
		array(
			'href'  => '#',
			'text'  => 'Something else here',
		),
	),
);

include '../snake.php';

$tpl = new Snake('view2.tpl');
$page = $tpl->block('html.type1');

// a bootstrap sample
$page->assign(array(
	'head'       => $tpl->block('jquery_bootstrap.default'),
	'title'      => 'template',
	'pagination' => $tpl->block('pagination'),
));

$nest = '';
/* complex sample
$nest = [];
foreach($list_nav_left as $key=>$arr){
	if(isset($arr['class'])){
		$nest[] = $tpl->block('main.left.link')->assign($arr);
	}else{
		$nest[] = $tpl->block('main.left.dropdown')->assign(array(
			'dropdown-text' => $key,
			'dropdown-link' => $tpl->block('main.left.dropdown.dropdown-link')->nest($arr)
		));
	}
}
*/

$page->assign(array(
	'nav' => $tpl->block('nav.type1')->assign(array(
		'brand' => 'Snake',
		'main.left' => $nest,
		'main.right' => '',
	)),
));

// top banner
$page->assign(array(
	'banner' => $tpl->block('banner.type1')->assign(array(
		'li'   => $tpl->block('banner.type1.li')->nest($list_banner_spin),
		'item' => $tpl->block('banner.type1.item')->nest($list_banner_item)
	))
));

// left nav
$page->assign(array(
	'left' => $tpl->block('nav.type2')->assign(array(
		'li' => $tpl->block('nav.type2.li')->nest($list_side)
	))
));

// right thumbnail
$page->assign(array(
	'right' => $tpl->block('thumbnail')->nest($list_thum)
));

$page->render();