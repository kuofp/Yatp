## Yatp

> Yet another [TemplatePower](http://templatepower.codocad.com/)
> 
> 我們盡可能地將畫面與程式分離, 因此暫不考慮提供樣板自帶任何程式化功能


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

#錯誤 區塊必須使用 2 的標籤
<!-- @block_name -->
```

標記 (Marks)
```php
{mark_name}
```
* **區塊或標記的正規表示式:** [A-Za-z0-9_-]+




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

### 準備工作完成!
```php
// 支援 . (點)搜尋
$tpl = new Yatp('
  <!-- @dot -->
    <!-- @a -->1<br>
      <!-- @b -->2<br>
        <!-- @c -->3<br>{e}
          <!-- @d -->4<br>{e}
          <!-- @d -->
        <!-- @c -->
      <!-- @b -->
    <!-- @a -->
  <!-- @dot -->
');

$tpl->block('dot.c')->assign(array(
	'd.e' => 'd.e was replaced',
))->render();

// Output:
// 3
// {e} 4
// d.e was replaced

```

## 方法

### Yatp::__construct

```php
// 初始化
$tpl = new Yatp('<strong>{str}</strong>');

// 你也可以使用檔案來初始
$tpl = new Yatp('view.tpl');
```


### Yatp::render

```php
// 預設會印出至畫面上
$tpl = new Yatp('<strong>Hello World!</strong>');
$tpl->render();

// Output:
// <strong>Hello World!</strong>


// 如果你不想要自動印出請帶 false 參數
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

### Yatp::block

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
// Equivalent
// $tpl->block('a.b.c.d')->render();
// $tpl->block('c.d')->render();
$tpl->block('a.d')->render();

// Output:
// d


// 你只能取得至多一個區塊(block), 重覆定義將優先使用第一個
$tpl = new Yatp('
    <!-- @a -->1<!-- @a -->
    <!-- @a -->2<!-- @a -->
    <!-- @a -->3<!-- @a -->
');
$tpl->block('a')->render();

// Output:
// 1
```

### Yatp::assign

```php
// 配置變數至標記(mark)上
$tpl = new Yatp('<strong>{str}</strong>');
$tpl->assign(array(
	'str' => 'Hello World!'
))->render();

// Output:
// <strong>Hello World!</strong>


// 你可以配置多次, 那麼將會有疊加效果
$tpl = new Yatp('<strong>{str}</strong>');

// Equivalent
// $tpl->assign(array(
//    'str' => 'Hi!'
// ))->assign(array(
//    'str' => 'Hi!'
// ))->render();

$tpl->assign(array(
    'str' => array('Hi!', 'Hi!')
))->render();

// Output:
// <strong>Hi!Hi!</strong>


// 你可以用相同方式配置給區塊(block)
$tpl = new Yatp('<strong><!-- @str --><!-- @str --></strong>');
$tpl->assign(array(
	'str' => 'Hello World!'
))->render();

// Output:
// <strong>Hello World!</strong>


// 你可以將另一個區塊(block)指定為配置的參數
$tpl = new Yatp('<strong>{str}</strong>');
$msg = new Yatp('<!-- @str -->Hello World!<!-- @str -->');
$tpl->assign(array(
	'str' => $msg->block('str')
))->render();

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
$tpl->assign(array(
    'a.c' => 'A.C'
))->render();

// Output:
// A.C b.c


// 但是你必須注意, 配置將會影響該區域所有符合規則的項目
$tpl = new Yatp('
    <!-- @a -->
        <!-- @c -->a.c<!-- @c -->
    <!-- @a -->
    <!-- @b -->
        <!-- @c -->b.c<!-- @c -->
    <!-- @b -->
    {c} {c}
');
$tpl->assign(array(
    'c' => 'C'
))->render();

// Output:
// C C C C
```

### Yatp::nest

```php
$tpl = new Yatp('
	<!-- @ul -->
    <h1>{title}<h1>
    <ul>{li}</ul>
    <!-- @ul -->
    
    <!-- @li -->
    <li>{title}: {text}</li>
    <!-- @li -->
');

$data = array(
    array(
        'title' => 'Lesson1',
        'text'  => 'Hello World!'
    ),
    array(
        'title' => 'Lesson2',
        'text'  => 'Functions'
    ),
);

// Equivalent
// $tpl->block('ul')->assign(array(
//     'title' => 'Syllabus',
//     'li'    => array(
//                    $tpl->block('li')->assign($data[0]),
//                    $tpl->block('li')->assign($data[1]),
//                ),
// ))->render();

$tpl->block('ul')->assign(array(
    'title' => 'Syllabus',
    'li'    => $tpl->block('li')->nest($data)
))->render();

// Output:
// <h1>Syllabus</h1>
// <ul>
//     <li>Lesson1: Hello World!</li>
//     <li>Lesson2: Functions</li>
// </ul>

```

### Yatp::debug
```php
$tpl = new Yatp();

$tpl->block('a_missing_block')->assign(array(
	'a_missing_mark' => '',
	'#wrong style' => ''
))->debug();
```

## 範例

請參考 examples 資料夾


