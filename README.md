# Dynamic Hook System
A simple dynamic hook system for PHP. Very useful.

------------
### Connecting:
```php
<?php

include('hooks.class.php');

$h = new Hooks();

?>
```

------------
### Using:
You can use hooks so easy! Look it.
#### 1) Add hook:

```php

//create hook (without args)
$h->here('namehook');

//create hook (with args)
$h->here('namehook1', array('arg1', 'arg2'));

```
#### 2) Set action:

##### 2.1 For function:
```php

//standard function
$h->register('namehook', 'default_function');

//will be added on hook
function default_function ($args)
{
           echo 'Hello!';
}

```

##### 2.2 For anonymous function:

```php

//anonymous function
$h->register('namehook', function ($args) {
          echo 'Hello!';
});

```

##### 2.3 For function in class:

```php

class Class {
	//standard function
	$h->register('namehook', 'default_function', $this);

	//will be added on hook
	function default_function ($args)
	{
		echo 'Hello!';
	}
}

```

#### 3) Delete function from hook:

##### 3.1 One function:
```php

//selected function
$h->remove('namehook', 'default_function');

```

##### 3.2 All functions:
```php

$h->remove('namehook');

```
