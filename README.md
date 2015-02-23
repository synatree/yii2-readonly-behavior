Read Only Behavior for ActiveRecord Models
==========================================
This behavior class allows you to specify an attribute which, upon setting or being set, prevents the model from being changed.  Think of it as a latch that locks the model once a particular value has been set and saved.

This is intended to act as a failsafe to prevent a fixed record from being updated even if you make a mistake in the controller and allow a change to be made.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require synatree/yii2-readonly-behavior "*"
```

or add

```
"synatree/yii2-readonly-behavior": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by adding the behavior to your ActiveRecord model:

```php
use synatree\behaviors\ReadOnlyBehavior;
...
public function behaviors(){
	return [
		'readonly' => [
			'class' => ReadonlyBehavior::className(),
			'attribute' => 'setmeonce',
			'onError' => function($param, $value){
				Yii::warning("Already Set, cannot set $param");
				throw new \yii\web\UnauthorizedHttpException("Already Set");
			}
		]
	];
}
```
Options
--------
```php
	'attribute' => 'modelattr', // your model attribute that will trigger a lock.  Lock will take effect when the record is loaded from or saved to the database.
	'onError' => ... // callable, either a function, function name, etc. per PHP manual is_callable
	'stopEvent' => true, // stop the AR event that tried to change an attribute.  If false, the db record will be updated, but the values will be the same as when locked.
```