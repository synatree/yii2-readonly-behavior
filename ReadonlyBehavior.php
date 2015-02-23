<?php

namespace synatree\behavior;
use yii\db\ActiveRecord;
use yii\base\Behavior;
use Yii;
/**
 * Read Only Behavior
 * @property ActiveRecord $owner
 * @author David Baltusavich <david@synatree.com>
 */
class ReadonlyBehavior extends Behavior
{
	public $attribute;
	public $onError; // callback
    private $locked = false;
	private $locker = [];
	private $stopEvent = true;
	
	public function checkLock($event){
		if(	$this->owner->__get($this->attribute) )
			$this->grabAllAttributes();
		else
			$this->releaseAllAttributes();
	}
	
	private function grabAllAttributes()
	{
		if(! $this->locked)
		{
			foreach($this->owner->attributes as $name=>$value)
			{
					$this->locker[$name] = $value;
			}
		}
		$this->locked = true;
	}
	
	private function releaseAllAttributes()
	{
		$this->locker = [];
		$this->locked = false;
	}
	
	public function restoreIfLocked($event)
	{
		if($this->locked)
		{
			if($this->stopEvent)
				$event->handled = true;
			foreach($this->owner->attributes as $name=>$value)
			{
				if( isset($this->locker[$name]) && $this->locker[$name] != $value )
				{
					$this->owner->$name = $this->locker[$name];
					call_user_func( $this->onError, $name, $value);
				}
			}
		}
	}

	
	public function events()
	{
		return [
			ActiveRecord::EVENT_AFTER_INSERT => 'checkLock',
			ActiveRecord::EVENT_AFTER_UPDATE => 'checkLock',
			ActiveRecord::EVENT_AFTER_FIND => 'checkLock',
			ActiveRecord::EVENT_BEFORE_INSERT => 'restoreIfLocked',
			ActiveRecord::EVENT_BEFORE_UPDATE => 'restoreIfLocked',
		];
	}
    
}
