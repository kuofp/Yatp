## Yatp

> Yet another [TemplatePower](http://templatepower.codocad.com/)
> 
> A logic-less PHP template engine
> 
> [中文說明](https://github.com/kuofp/Yatp/blob/master/README.zh-tw.md)


## Format

Blocks
```php
#valid
<!-- @block_name -->
	...
<!-- @block_name -->

#valid (with zero or many spaces)
<!--@block_name-->
	...
<!-- @block_name     -->

#invalid (must use pair blocks)
<!-- @block_name -->
```

Marks
```php
{mark_name}
```
* **A valid block_name/mark_name:** [A-Za-z0-9_-]+
* **A mark_name should contain at least one alphabet** (update since V1.1)




## Get Started

### Install

* Via composer

```
$ composer require kuofp/yatp
```

* Download directly
```php
// Require it with the correct path
require_once 'yatp.php';
```



## Methods

### __construct(string $html_or_filepath)

```php
// Initialize
$tpl = new Yatp('<strong>{str}</strong>');

// You can use a file (if exists).
$tpl = new Yatp('view.tpl');
```


### render([ bool $print = true ])

```php
// Print to screen by default
$tpl = new Yatp('<strong>Hello World!</strong>');
$tpl->render();

// Output:
// <strong>Hello World!</strong>


// Get result without a print
$html = $tpl->render(false);
echo $html;

// Output:
// <strong>Hello World!</strong>


// PHP code is allowed
$tpl = new Yatp('<?php echo "Hello World!"?>');
$tpl->render();

// Output:
// Hello World!
```

### block(string $target)

```php
// Support dot operation
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


// Search the most likely one
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


// First is adopted when block is redefined
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
// Assign to a mark
$tpl = new Yatp('<strong>{str}</strong>');
$tpl->assign(array(
	'str' => 'Hello World!'
))->render();

// Output:
// <strong>Hello World!</strong>


// Assign several times
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


// Assign a variable to block in the same way
$tpl = new Yatp('<strong><!-- @str --><!-- @str --></strong>');
$tpl->assign(array(
	'str' => 'Hello World!'
))->render();

// Output:
// <strong>Hello World!</strong>


// You can assign with another block
$tpl = new Yatp('<strong>{mark}</strong>');
$msg = new Yatp('<!-- @block -->Hello World!<!-- @block -->');
$tpl->assign(array(
	'mark' => $msg->block('block')
))->render();

// Output:
// <strong>Hello World!</strong>


// Support dot operation, too
$tpl = new Yatp('
	<!-- @a -->
		<!-- @c -->a.c<!-- @c -->
	<!-- @a -->
	<!-- @b -->
		<!-- @c -->b.c<!-- @c -->
	<!-- @b -->
');
$tpl->assign(array(
	'a.c' => 'replaced'
))->render();

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

$data = array(
	array('title' => 'Lesson1'),
	array('title' => 'Lesson2'),
);

// Equivalent
// $tpl->assign(array(
//     'li' => array(
//         $tpl->block('li')->assign($data[0]),
//         $tpl->block('li')->assign($data[1]),
//     )
// ))->render();

$tpl->assign(array(
	'li' => $tpl->block('li')->nest($data)
))->render();

// Output:
// <ul>
//     <li>Lesson1: Hello World!</li>
//     <li>Lesson2: Functions</li>
// </ul>

```

### debug( void )
```php
$tpl = new Yatp();

$tpl->block('a_missing_block')->assign(array(
	'a_missing_mark' => '',
	'#wrong style' => ''
))->debug();

// Output:
// Array
// (
//     [0] => Debug info:
//     [1] => block "a_missing_block" is not found
//     [2] => block or mark "a_missing_mark" is not found
//     [3] => block or mark "#wrong style" is invalid
// )

```

## Example

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

$tpl->assign(array(
	'title' => 'Syllabus',
	'li'    => $tpl->block('li')->nest($data)
))->render();
```

Output:
```html
<h1>Syllabus<h1>
<ul>
	<li>Lesson1: Hello World!</li>
	<li>Lesson2: Functions</li>
</ul>
```