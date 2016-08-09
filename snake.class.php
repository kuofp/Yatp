<?php

class Snake{
	protected $raw;
	protected $tpl;
	protected $val;
	
	public function __construct($file){
		
		$this->raw = '';
		$this->tpl = '';
		$this->val = array();
		$this->init($file);
		
	}
	
	protected function init($file){
		// load file(if found) or html
		if(file_exists($file)){
			$this->raw = file_get_contents($file);
		}else{
			$this->raw = $file;
		}
		
		// check()...
		$this->check($this->raw);
		
		// slice into blocks
		$this->slice($this->raw);
	}
	
	protected function check($block_name){
		return true;
	}
	
	protected function slice($raw){
		
		$tag = array();
		preg_match_all('/<!--[ ]*@([^ ]+)[ ]*-->()/', $raw, $tag, PREG_OFFSET_CAPTURE);
		
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
			
			// prepare a new object
			return new self($this->take($block_name));
			
		}else{
			// block not found, and skip this section
			return '';
		}
	}
	
	public function assign($arr){
		// set variables
		foreach($arr as $key=>$val){
			
			if(is_a($val, get_class($this))){
				$arr[$key] = $arr[$key]->render(false);
			}
			
			if(isset($this->val[$key])){
				$this->val[$key] .= $arr[$key];
			}else{
				$this->val[$key] = $arr[$key];
			}
		}
		return $this;
	}
	
	public function render($toScreen = true){
		// replace tags, we have 3 types as follows
		// type 1: <!-- @tag --> ... <!-- @tag -->
		// type 2: <!-- @tag -->
		// type 3: {tag}
		
		foreach($this->val as $key=>$val){
			if(isset($this->tpl[$key])){
				
				$this->raw = preg_replace('/<!--[ ]*@' . $key . '[ ]*-->.*<!--[ ]*@' . $key . '[ ]*-->/s', $val, $this->raw);
				$this->raw = preg_replace('/<!--[ ]*@' . $key . '[ ]*-->/', $val, $this->raw);
			}
			
			$this->raw = preg_replace('/{' . $key . '}/', $val, $this->raw);
		}
		
		ob_start();
		
		eval('?> ' . $this->raw);
		$content = ob_get_contents();
		
		ob_end_clean(); 
		
		// echo to screen by default
		if($toScreen){
			echo $content;
		}
		
		return $content;
	}
	
	// debug tool
	public function show(){
		$this->d($this->tpl);
	}
	
	public function d($arr){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}