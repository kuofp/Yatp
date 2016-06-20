<?php

include 'snake.class.php';

$tpl = new Snake('view.tpl');

$page = $tpl->block('style01');

//全域區塊
$page->assign(
		array(
			'head'    =>$tpl->block('jquery_bootstrap'),
			
			
			'title'   =>'測試樣版',
		)
	)
	->assign(
		array(
			'style01_banner'  =>$tpl->block('nav'),
			'style01_nav'     =>$tpl->block('banner'),
		)
	);

//左側區塊
$page->assign(
		array(
			'style01_left'    =>$tpl->block('left'),
		)
	);

	
$data = array(
			array(
				'src'=>'http://www.easytravel.com.tw/ehotel/hotelimg/himg/7326_pf_s.jpg',
				'title'=>'景點A',
				'content'=>'內容123'
			),
			array(
				'src'=>'http://www.easytravel.com.tw/ehotel/hotelimg/himg/8106_pf.jpg',
				'title'=>'景點B',
				'content'=>'內容456'
			),
			array(
				'src'=>'http://www.easytravel.com.tw/ehotel/hotelimg/himg/6790_pf.jpg',
				'title'=>'景點C',
				'content'=>'內容789'
			),
			array(
				'src'=>'http://www.easytravel.com.tw/ehotel/hotelimg/himg/6790_pf.jpg',
				'title'=>'景點C',
				'content'=>'內容789'
			),
			array(
				'src'=>'http://www.easytravel.com.tw/ehotel/hotelimg/himg/6790_pf.jpg',
				'title'=>'景點C',
				'content'=>'內容789'
			),
			array(
				'src'=>'http://www.easytravel.com.tw/ehotel/hotelimg/himg/6790_pf.jpg',
				'title'=>'景點C',
				'content'=>'內容789'
			),
		);
		
		
		
//景點區塊
foreach($data as $val){
	$page->assign(
		array(
			'style01_right'   =>$tpl->block('thumbnail')->assign(
				array(
					'src'    =>$val['src'],
					'title'  =>$val['title'],
					'content'=>$val['content'],
				)
			),
		)
	);
}

//分頁區塊
$page->assign(
		array(
			'style01_pagination'    =>$tpl->block('pagination'),
		)
	);


echo $page->render();


