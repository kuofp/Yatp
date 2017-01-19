<?php

include '../yatp.php';

$tpl = new Yatp('basic.tpl');

//initial with string
$tpl2 = new Yatp('<strong>html string</strong>');
$tpl->block('basic')->assign(array(
	'b' => $tpl2
))->render();

//basic
$tpl->block('basic')->assign(array(
	'b' => 'Hello World!'
))->render();

//more
$tpl->block('basic')->assign(array(
	'b' => 'new line!<br>',
))->assign(array(
	'b' => 'new line!<br>',
))->assign(array(
	'b' => 'new line!<br>',
))->render();

//render with some code
$tpl->block('code')->render();

//render with another block contents
$tpl->block('basic')->assign(array(
	'b' => $tpl->block('code')
))->render();

//dot operation
$tpl->block('dot.c')->assign(array(
	'd.e' => 'd.e was replaced',
))->render();

//find block with full path
$tpl->block('dot.a.b.c.d')->render();
//find block d correctly
$tpl->block('dot.d')->render();

//assign several part with the same tag
$tpl->block('multi-nested')->assign(array(
	'a1.d.e' => 'this is a1.d.e',
	'a2.d.e' => 'this is a2.d.e',
))->render();