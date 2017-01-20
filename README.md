## Yatp

> Yet another [TemplatePower](http://templatepower.codocad.com/)
> 
> As a Frontend-friendly PHP Template Engine, we do not consider providing functions in html files currently.


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




## Get Started

```php
// Require it with the correct path
require_once 'yatp.php';
```

And enjoy!
```php
// Support dot operation
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

## Methods

### Yatp::__construct

```php
// Initialize
$tpl = new Yatp('<strong>{str}</strong>');

// You can use a file(if exists).
$tpl = new Yatp('view.tpl');
```


### Yatp::render

```php
// Print to screen by default
$tpl = new Yatp('<strong>Hello World!</strong>');
$tpl->render();

// Output:
// <strong>Hello World!</strong>


// Get return value without printing
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

### Yatp::block

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

### Yatp::assign

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
$tpl = new Yatp('<strong>{str}</strong>');
$msg = new Yatp('<!-- @str -->Hello World!<!-- @str -->');
$tpl->assign(array(
	'str' => $msg->block('str')
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
    'a.c' => 'A.C'
))->render();

// Output:
// A.C b.c


// Affect to all descendants
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

## Example

See examples folder


