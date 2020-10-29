## Yatp

> Yet another [TemplatePower](http://templatepower.codocad.com/)
> 
> A logic-less PHP template engine


## 格式

區塊 (Blocks)
```php
#合法名稱採用 html 註解形式並於區塊名稱前加上 @
<!-- @block_name -->
	...
<!-- @block_name -->

#前後可以自帶 0 至多個空白, 不可有其他文字
<!--@block_name-->
	...
<!-- @block_name     -->

#錯誤 區塊必須使用 2 個標籤
<!-- @block_name -->
```

標記 (Marks)
```php
{mark_name}
```
* **區塊或標記的正規表示式:** [A-Za-z0-9_-]+
* **標記必須包含 1 個字母** (update since V1.1)




## 開始使用

### 安裝

* 透過 composer

```
$ composer require kuofp/yatp
```

* 直接下載並載入
```php
// 直接載入至正確路徑
require_once 'yatp.php';
```



## 方法

### __construct(string $html_or_filepath)

```php
// 初始化
$tpl = new Yatp('<strong>{str}</strong>');

// 可以使用檔案來初始
$tpl = new Yatp('view.tpl');
```


### render([ bool $print = true ])

```php
// 預設會印出至畫面上
$tpl = new Yatp('<strong>Hello World!</strong>');
$tpl->render();

// Output:
// <strong>Hello World!</strong>


// 如果不想要自動印出請帶 false 參數
$html = $tpl->render(false);
echo $html;

// Output:
// <strong>Hello World!</strong>


// 樣板中可以使用原生 PHP 程式
$tpl = new Yatp('<?php echo "Hello World!"?>');
$tpl->render();

// Output:
// Hello World!
```

### block(string $target)

```php
// 支援 . (點)搜尋
$tpl = new Yatp('
	<!-- @a -->
		<!-- @c -->a.c<!-- @c -->
	<!-- @a -->
	<!-- @b -->
		<!-- @c -->b.c<!-- @c -->
	<!-- @b -->
');
$tpl->block('a.c')->render();

// Output:
// a.c


// 其中 . (點)搜尋可以跳過數個階層, 只要該樣板中可以找到區塊(block)或標記(mark)
$tpl = new Yatp('
	<!-- @a -->a
		<!-- @b -->b
			<!-- @c -->c
				<!-- @d -->d
				<!-- @d -->
			<!-- @c -->
		<!-- @b -->
	<!-- @a -->
');
// 等價寫法
// $tpl->block('a.b.c.d')->render();
// $tpl->block('c.d')->render();
$tpl->block('a.d')->render();

// Output:
// d


// 只能取得至多一個區塊(block), 重覆定義將優先使用第一個
$tpl = new Yatp('
	<!-- @a -->1<!-- @a -->
	<!-- @a -->2<!-- @a -->
	<!-- @a -->3<!-- @a -->
');
$tpl->block('a')->render();

// Output:
// 1
```

### assign(array $params)

```php
// 配置變數至標記(mark)上
$tpl = new Yatp('<strong>{str}</strong>');
$tpl->assign([
	'str' => 'Hello World!'
])->render();

// Output:
// <strong>Hello World!</strong>


// 可以配置多次, 將會有疊加效果
$tpl = new Yatp('<strong>{str}</strong>');

// 等價寫法
// $tpl->assign([
//    'str' => 'Hi!'
// ])->assign([
//    'str' => 'Hi!'
// ])->render();

$tpl->assign([
	'str' => ['Hi!', 'Hi!']
])->render();

// Output:
// <strong>Hi!Hi!</strong>


// 可以用相同方式配置給區塊(block)
$tpl = new Yatp('<strong><!-- @str --><!-- @str --></strong>');
$tpl->assign([
	'str' => 'Hello World!'
])->render();

// Output:
// <strong>Hello World!</strong>


// 可以將另一個區塊(block)指定為配置的參數
$tpl = new Yatp('<strong>{mark}</strong>');
$msg = new Yatp('<!-- @block -->Hello World!<!-- @block -->');
$tpl->assign([
	'mark' => $msg->block('block')
])->render();

// Output:
// <strong>Hello World!</strong>


// 配置同樣支援 . (點)搜尋
$tpl = new Yatp('
	<!-- @a -->
		<!-- @c -->a.c<!-- @c -->
	<!-- @a -->
	<!-- @b -->
		<!-- @c -->b.c<!-- @c -->
	<!-- @b -->
');
$tpl->assign([
	'a.c' => 'replaced'
])->render();

// Output:
// replaced b.c

```

### nest(array $params)

```php
$tpl = new Yatp('
	<ul>
	<!-- @li -->
		<li>{title}</li>
	<!-- @li -->
	</ul>
');

$data = [
	['title' => 'Lesson1'],
	['title' => 'Lesson2'],
];

// 等價寫法
// $tpl->assign([
//     'li' => [
//         $tpl->block('li')->assign($data[0]),
//         $tpl->block('li')->assign($data[1]),
//     ]
// ])->render();

$tpl->assign([
	'li' => $tpl->block('li')->nest($data)
])->render();

// Output:
// <ul>
//     <li>Lesson1: Hello World!</li>
//     <li>Lesson2: Functions</li>
// </ul>

```

### debug( void )
```php
$tpl = new Yatp();

$tpl->block('a_missing_block')->assign([
	'a_missing_mark' => '',
	'#wrong style' => ''
])->debug();

// Output:
// Array
// (
//     [0] => Debug info:
//     [1] => block "a_missing_block" is not found
//     [2] => block or mark "a_missing_mark" is not found
//     [3] => block or mark "#wrong style" is invalid
// )

```

## 範例

view.html
```html
<h1>{title}<h1>
<ul>
<!-- @li -->
	<li>{title}: {text}</li>
<!-- @li -->
</ul>
```
php code
```php
$tpl = new Yatp('view.html');

$data = [
	[
		'title' => 'Lesson1',
		'text'  => 'Hello World!'
	],
	[
		'title' => 'Lesson2',
		'text'  => 'Functions'
	],
];

$tpl->assign([
	'title' => 'Syllabus',
	'li'    => $tpl->block('li')->nest($data)
])->render();
```

Output:
```html
<h1>Syllabus<h1>
<ul>
	<li>Lesson1: Hello World!</li>
	<li>Lesson2: Functions</li>
</ul>
```