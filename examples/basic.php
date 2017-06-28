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
	'b' => array('new line!<br>', 'new line!<br>', 'new line!<br>')
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

//nest
$tpl->block('nest')->nest(array(
	array('text' => '1'),
	array('text' => '2'),
	array('text' => '3'),
	array('text' => '4'),
	array('text' => '5'),
))->render();

//redefine a scope
$tpl->block('scope')->assign(array(
	'a' => 'How do you turn this on',
	'c' => 'corrupted',
))->render();

//script contains {digits}
$tpl->block('script')->render();

//debug
$tpl->block('a_missing_block')->assign(array(
	'a_missing_mark' => '',
	'#wrong style' => ''
))->debug();

$tpl2 = new Yatp('<!-- @single -->');
$tpl2->debug()->render();

//you can prepend the debug function before the render function for convenience purposes
$tpl->block('a_missing_block')->debug()->render();


$tpl = new Yatp();

$tpl->block('a_missing_block')->assign(array(
	'a_missing_mark' => '',
	'#wrong style' => ''
))->debug()->render();