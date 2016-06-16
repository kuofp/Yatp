<?php

class Snake{
	public $raw;
	public $tpl;
	public $val;
	
	public function __construct($file){
		
		$tpl = '';
		$this->init($file);
		
	}
	
	protected function init($file){
		//初次建立區塊列表, 若非檔案則直接視為html
		if(file_exists($file)){
			$this->raw = file_get_contents($file);
		}else{
			$this->raw = $file;
		}
		
		//確認檔案結構 check()...
		
		//建立區塊列表
		$this->slice($this->raw);
	}
	
	protected function slice($raw){
		
		//echo $this->tpl;
		$tag = array();
		preg_match_all('/<!--[ ]?B[ ]?:[ ]?([^- ]+)[ ]?-->()/', $raw, $tag, PREG_OFFSET_CAPTURE);
		
		foreach($tag[1] as $key=>$arr){
			
			$block_head = $tag[0][$key][1];
			$block_name = $tag[1][$key][0];
			$block_tail = $tag[2][$key][1];
			
			if(isset($this->tpl[$block_name])){
				$this->tpl[$block_name]['tail'] = $block_head;
			}else{
				$this->tpl[$block_name]['head'] = $block_tail;
				$this->tpl[$block_name]['tail'] = $block_tail;
			}
		}
	}
	
	protected function take($block_name){
		return substr($this->raw, $this->tpl[$block_name]['head'], $this->tpl[$block_name]['tail'] - $this->tpl[$block_name]['head']);
	}
	
	public function block($block_name){
		
		if(isset($this->tpl[$block_name])){
			
			//將片段建立為新的物件並回傳
			return new self($this->take($block_name));
			
		}else{
			//block not found
			return 0;
		}
	}
	
	public function show(){
		$this->d($this->tpl);
	}
	
	public function d($arr){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
	
	public function assign($arr){
		//設定變數
		foreach($arr as $key=>$val){
			if(isset($this->val[$key])){
				$this->val[$key] .= $val;
			}else{
				$this->val[$key] = $val;
			}
		}
		return $this;
	}
	
	public function render(){
		//產生畫面
		foreach($this->val as $key=>$val){
			if(isset($this->tpl[$key])){
				//區間標籤
				$this->raw = preg_replace('/<!--[ ]?B[ ]?:[ ]?' . $key . '[ ]?-->(.|[\r\n])*<!--[ ]?B[ ]?:[ ]?' . $key . '[ ]?-->/', $val, $this->raw);
				//單一標籤
				$this->raw = preg_replace('/<!--[ ]?B[ ]?:[ ]?' . $key . '[ ]?-->/', $val, $this->raw);
			}
			//變數標籤
			$this->raw = preg_replace('/{' . $key . '}/', $val, $this->raw);
		}
		return $this->raw;
	}
}