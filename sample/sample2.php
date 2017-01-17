<?php

include '../snake.class.php';

$tpl = new Snake('view2.tpl');
$page = $tpl->block('html.type1');

// a bootstrap sample
$page->assign(array(
	'head'   =>$tpl->block('jquery_bootstrap.default'),
	'title'  =>'template',
))->assign(array(
	'banner' =>$tpl->block('nav.type1'),
	'nav'    =>$tpl->block('banner.type1'),
));

$page->assign(array(
	'left'   =>$tpl->block('nav.type2'),
));

$data = array(
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


foreach($data as $val){
	$page->assign(array(
		'right'  =>$tpl->block('thumbnail')->assign(array(
			'src'    =>$val['src'],
			'title'  =>$val['title'],
			'content'=>$val['content'],
		)),
	));
}

$page->assign(array(
	'pagination' =>$tpl->block('pagination'),
));

$page->render();