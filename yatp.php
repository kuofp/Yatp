<?php
/*!
 * Yatp Template Engine
 * https://github.com/kuofp/Yatp
 * Version 1.0
 *
 * Copyright 2017, Frank Kuo
 * Released under the MIT license
 */
class Yatp{
	
	protected $raw;
	protected $tpl;
	protected $val;
	protected $err;
	
	public function __construct($file = ''){
		
		$this->raw = '';
		$this->tpl = array();
		$this->val = array();
		$this->err = array('Debug info:');
		$this->init($file);
	}
	
	protected function init($file){
		// load file(if found) or html
		if(file_exists($file)){
			$this->raw = file_get_contents($file);
		}else{
			$this->raw = $file;
		}
		
		// slice into blocks
		$this->slice($this->raw);
	}
	
	protected function check($tag){
		
		return preg_match('/^[\w-]+$/', $tag);
	}
	
	protected function slice($raw){
		
		$tag = array();
		
		// type 1: <!-- @tag --> ... <!-- @tag -->
		// type 2: {tag}
		preg_match_all('/<!--[ ]*@([\w-]+)[ ]*-->()|{([\w-]*)}()/', $raw, $tag, PREG_OFFSET_CAPTURE);
		
		$stk = array();
		foreach($tag[1] as $key=>$arr){
			
			$block_head = $tag[0][$key][1];
			$block_name = isset($tag[3][$key][0])? $tag[3][$key][0]: $tag[1][$key][0];
			$block_tail = isset($tag[4][$key][1])? $tag[4][$key][1]: $tag[2][$key][1];
			$mark = isset($tag[3][$key][0])? 1: 0;
			
			if($mark){
				$list = implode('.', $stk);
				
			}elseif(end($stk) != $block_name){
				$list = implode('.', $stk);
				$stk[] = $block_name;
				
			}elseif(end($stk) == $block_name){
				unset($stk[key($stk)]);
				$list = implode('.', $stk);
				
			}
			
			$list .= $list? '.': '';
			
			$idx = $list . $block_name . ($mark? '_' . rand(): '');
			if(isset($this->tpl[$idx])){
				// take first block when duplicate, while mark tags can be stored several times
				if(!$this->tpl[$idx]['tail']){
					$this->tpl[$idx]['tail'] = $block_tail;
				}
			}else{
				$this->tpl[$idx] = array(
					'head' => $block_head,
					'tail' => $mark? $block_tail: 0,
					'list' => $list . $block_name,
					'name' => $block_name,
					'mark' => $mark,
				);
			}
		}
		
		// assign order
		uasort($this->tpl, function($a, $b){
			$a = $a['tail'];
			$b = $b['tail'];
			if($a == $b){
				return 0;
			}
			return ($a > $b)? -1: 1;
		});
		
		foreach($this->tpl as $key=>$arr){
			if($arr['tail'] <= $arr['head']){
				$this->err[] = 'block "' . $key . '" is not closed';
			}
		}
	}
	
	protected function take($block_name){
		return substr($this->raw, $this->tpl[$block_name]['head'], $this->tpl[$block_name]['tail'] - $this->tpl[$block_name]['head']);
	}
	
	// support dot operation
	protected function path($block_name, $all = false){
		
		$short = explode('.', $block_name);
		$block = end($short);
		$multi = array();
		
		foreach($this->tpl as $key=>$arr){
			$match = 0;
			if(!$all && $arr['mark']){
				continue;
			}
			if($arr['name'] == $block){
				
				$tmp = explode('.', $arr['list']);
				foreach($tmp as $val){
					if($match < count($short) && $short[$match] == $val){
						$match++;
					}
				}
				
				// all matched
				if($match == count($short)){
					if($all){
						$multi[] = $key;
					}else{
						return $key;
					}
				}
			}
		}
		return count($multi)? $multi: 0;
	}
	
	public function block($block_name){
		
		$block = $this->path($block_name);
		
		if($block){
			// prepare a new object
			$obj = new self($this->take($block));
			return $obj;
			
		}else{
			// block not found, and skip this section
			$obj = new self();
			if($this->check($block_name)){
				$obj->err[] = 'block "' . $block_name . '" is not found';
			}else{
				$obj->err[] = 'block "' . $block_name . '" is invalid';
			}
			
			return $obj;
		}
	}
	
	public function assign($arr){
		
		// set variables
		foreach($arr as $key=>$val){
			$blocks = $this->path($key, true);
			if($blocks){
				if(!is_array($val)){
					$val = array($val);
				}
				foreach($blocks as $block){
					foreach($val as $v){
						$this->val[$block][] = $v;
					}
				}
				
			}else{
				if($this->check($key)){
					$this->err[] = 'block or mark "' . $key . '" is not found';
				}else{
					$this->err[] = 'block or mark "' . $key . '" is invalid';
				}
			}
		}
		return $this;
	}
	
	public function nest($data){
		
		$tpl = new self('{tar}');
		
		// prevent leaving {tar} mark when data is empty
		$blocks = array('');
		foreach($data as $key=>$arr){
			$obj = clone $this;
			$blocks[] = $obj->assign($arr);
		}
		$tpl->assign(array(
			'tar' => $blocks
		));
		
		return $tpl;
	}
	
	public function render($print = true){
		
		$html = $this->raw;
		foreach($this->tpl as $key=>$val){
			if(isset($this->val[$key])){
				$str = '';
				foreach($this->val[$key] as $tmp){
					
					// deal with object
					if(is_a($tmp, get_class($this))){
						$tmp = $tmp->render(false);
					}
					$str .= $tmp;
				}
				$html = substr_replace($html, $str, $val['head'], $val['tail'] - $val['head']);
			}
		}
		
		ob_start();
		eval('?> ' . $html);
		$content = ob_get_contents();
		ob_end_clean(); 
		
		// echo to screen by default
		if($print){
			echo $content;
		}
		
		return $content;
	}
	
	// debug tool
	public function debug(){
		
		//$this->d($this->tpl);
		$this->d($this->err);
		
		return $this;
	}
	
	public function d($arr){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}
