<?php

class BasicTest extends PHPUnit_Framework_Testcase
{
	private $tpl;
	private $dom;
	
	public function setUp(){
		$this->tpl = new Yatp(__DIR__ . '/basic.tpl');
		$this->dom = new DomDocument();
	}
	
	public function tearDown(){
		$this->tpl = null;
		$this->dom = null;
	}
	
	public function html($str){
		// skip newlines
		$this->dom->loadHTML($str);
		$this->dom->preserveWhiteSpace = false;
		$this->dom->formatOutput = false;
		return preg_replace('/[\r\n]/', '', $this->dom->saveHTML());
	}
	
	public function testInitWithStr()
	{
		$str = '<strong>html string</strong>';
		$tpl = new Yatp($str);
		$this->assertEquals($tpl->render(false), $str);
	}
	
	public function testAssignMark()
	{
		$tpl = $this->tpl->block('basic')->assign(array(
			'b' => 'Hello World!'
		))->render(false);
		
		$this->assertEquals(
			$this->html('basic <br>Hello World!<br>'),
			$this->html($tpl)
		);
	}
	
	public function testAssignBlock()
	{
		$tpl = $this->tpl->block('basic')->assign(array(
			'b' => $this->tpl->block('code')
		))->render(false);
		
		$this->assertEquals(
			$this->html('basic <br>1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br><br>'),
			$this->html($tpl)
		);
	}
	
	public function testMultiAssign()
	{
		$tpl = $this->tpl->block('basic')->assign(array(
			'b' => array('new line!<br>', 'new line!<br>', 'new line!<br>')
		))->render(false);
		
		$this->assertEquals(
			$this->html('basic <br>new line!<br>new line!<br>new line!<br><br>'),
			$this->html($tpl)
		);
	}
	
	public function testRunCode()
	{
		$tpl = $this->tpl->block('code')->render(false);
		
		$this->assertEquals(
			$this->html('1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>'),
			$this->html($tpl)
		);
	}
	
	public function testDotOperation()
	{
		$tpl = $this->tpl->block('dot.c')->assign(array(
			'd.e' => 'd.e was replaced'
		))->render(false);
		
		$this->assertEquals(
			$this->html('3<br>4<br>d.e was replaced'),
			$this->html($tpl)
		);
	}
	
	public function testFullPath()
	{
		$tpl = $this->tpl->block('dot.a.b.c.d')->render(false);
		
		$this->assertEquals(
			$this->html('4<br>'),
			$this->html($tpl)
		);
	}
	
	public function testImplicitPath()
	{
		$tpl = $this->tpl->block('dot.d')->render(false);
		
		$this->assertEquals(
			$this->html('4<br>'),
			$this->html($tpl)
		);
	}
	
	public function testNest()
	{
		$tpl = $this->tpl->block('nest')->nest(array(
			array('text' => '1'),
			array('text' => '2'),
			array('text' => '3'),
			array('text' => '4'),
			array('text' => '5'),
		))->render(false);
		
		$this->assertEquals(
			$this->html('<h1>1</h1><h1>2</h1><h1>3</h1><h1>4</h1><h1>5</h1>'),
			$this->html($tpl)
		);
	}
	
	public function testMultiNest()
	{
		$tpl = $this->tpl->block('multi-nested')->assign(array(
			'a1.d.e' => 'this is a1.d.e',
			'a2.d.e' => 'this is a2.d.e',
		))->render(false);
		
		$this->assertEquals(
			$this->html('1<br>2<br>3<br>4this is a1.d.e<br>5<br>6<br>7<br>8this is a2.d.e<br>'),
			$this->html($tpl)
		);
	}
	
	public function testAssignScope()
	{
		$tpl = $this->tpl->block('scope')->assign(array(
			'a' => 'How do you turn this on',
			'c' => 'corrupted',
		))->render(false);
		
		$this->assertEquals(
			$this->html('How do you turn this oncorrupted'),
			$this->html($tpl)
		);
	}
	
	public function testMarkWithDigits()
	{
		$tpl = $this->tpl->block('script')->render(false);
		
		$this->assertEquals(
			$this->html("<br>'aaa'.replace(/a{3}/g, 'ccc');"),
			$this->html($tpl)
		);
	}
}