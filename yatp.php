<?php
/*!
 * Yatp Template Engine
 * https://github.com/kuofp/Yatp
 * Version 1.3.1
 *
 * Copyright 2017, Frank Kuo
 * Released under the MIT license
 */
class Yatp{
	
	protected $raw;
	protected $tpl;
	protected $val;
	protected $tmp;
	protected $err;
	
	public function __construct($file = ''){
		
		// load file(if found) or html
		$this->raw = file_exists($file)? file_get_contents($file): $file;
		$this->tpl = [];
		$this->val = [];
		$this->tmp = [];
		$this->err = ['Debug info:'];
		
		// slice into blocks
		$this->slice();
	}
	
	protected function slice(){
		
		$tag = [];
		
		// type 1: <!-- @tag --> ... <!-- @tag -->
		// type 2: {tag}
		preg_match_all('/<!--[ ]*@([\w-]+)[ ]*-->()|{([\w-]*[a-zA-Z]+[\w-]*)}()/', $this->raw, $tag, PREG_OFFSET_CAPTURE);
		
		$stk = [];
		foreach($tag[1] as $key=>$arr){
			// php 7.4
			$o = (isset($tag[3][$key][1]) && $tag[3][$key][1] != -1)? 2: 0;
			
			$block_head = $tag[0][$key][1];
			$block_name = $tag[1 + $o][$key][0];
			$block_tail = $tag[2 + $o][$key][1];
			$mark = $o;
			
			if($mark){
				$list = implode('.', $stk);
				
			}else if(end($stk) != $block_name){
				$list = implode('.', $stk);
				$stk[] = $block_name;
				
			}else if(end($stk) == $block_name){
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
				$this->tpl[$idx] = [
					'head' => $block_head,
					'tail' => $mark? $block_tail: 0,
					'list' => $list . $block_name,
					'name' => $block_name,
					'mark' => $mark,
				];
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
		$multi = [];
		
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
		
		if(!isset($this->tmp[$block_name])){
			$block = $this->path($block_name);
			if($block){
				$this->tmp[$block_name] = new self($this->take($block));
			}else{
				$obj = new self();
				$obj->err[] = 'block "' . $block_name . '" is not found/invalid';
				return $obj;
			}
		}
		
		return clone $this->tmp[$block_name];
	}
	
	public function assign($arr){
		
		// set variables
		foreach($arr as $key=>$val){
			$blocks = $this->path($key, true);
			if($blocks){
				// remove redefined blocks
				foreach($blocks as $k=>$block){
					foreach($this->val as $param=>$any){
						// param is a parent for the block
						if(strpos($block, $param . '.') === 0){
							unset($blocks[$k]);
							continue;
						}
						// block is a parent for the param
						if(strpos($param, $block . '.') === 0){
							unset($this->val[$param]);
						}
					}
				}
				
				if(!is_array($val)){
					$val = [$val];
				}
				
				foreach($blocks as $block){
					foreach($val as $v){
						$this->val[$block][] = $v;
					}
				}
				
			}else{
				$this->err[] = 'block or mark "' . $key . '" is not found/invalid';
			}
		}
		return $this;
	}
	
	public function nest($data){
		
		$html = '';
		if(is_array($data)){
			foreach($data as $key=>$arr){
				$obj = clone $this;
				$html .= $obj->assign($arr)->render(false, false);
			}
		}
		
		$tmp = new self('{tar}');
		return $tmp->assign(['tar' => $html]);
	}
	
	public function render($print = true, $clean = true){
		
		$html = $this->raw;
		foreach($this->tpl as $key=>$val){
			if(isset($this->val[$key])){
				$str = '';
				foreach($this->val[$key] as $tmp){
					
					// deal with object
					if(is_a($tmp, get_class($this))){
						$tmp = $tmp->render(false, false);
					}
					$str .= $tmp;
				}
				$html = substr_replace($html, $str, $val['head'], $val['tail'] - $val['head']);
			}
		}
		
		// remove tags and some extra character before render by default
		if($clean){
			$patt = [
				'/<!--[ ]*@[\w-]+[ ]*-->/',
				'/{[\w-]*[a-zA-Z]+[\w-]*}/',
				'/[\t]+/',
			];
			$html = preg_replace($patt, '', $html);
			
			ob_start();
			eval('?>' . $html);
			$html = ob_get_contents();
			ob_end_clean();
		}
		
		// echo to screen by default
		if($print){
			echo $html;
		}
		
		return $html;
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
