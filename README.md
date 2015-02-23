Read Only Behavior for ActiveRecord Models
==========================================
This behavior class allows you to specify an attribute which, upon setting or being set, prevents the model from being changed.  Think of it as a latch that locks the model once a particular value has been set and saved.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist synatree/yii2-readonly-behavior "*"
```

or add

```
"synatree/yii2-readonly-behavior": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \synatree\behavior\AutoloadExample::widget(); ?>```