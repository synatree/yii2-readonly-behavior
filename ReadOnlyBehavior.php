<?php

namespace synatree\behavior;
use yii\db\ActiveRecord;
/**
 * Read Only Behavior
 * @property ActiveRecord $owner
 * @author David Baltusavich <david@synatree.com>
 */
class ReadOnlyBehavior extends \yii\base\Behavior
{
    private $locked = false;
	public $latchProperty;
	public $failedFunction; // callback
    /**
     * Set an attribute if unlocked, or else do nothing.
     * @inheritdoc
     */
    public function __set($param, $value)
    {
        if ($this->locked) {
			if( isset($this->failedFunction) && is_callable($this->failedFunction) )
				call_user_func($this->failedFunction, $param, $value);
            return;
        } else {
            parent::__set($param, $value);
        }
    }
	
	public function checkLock($event){
		if(	$this->owner->__get($this->latchProperty) )
				$this->locked = true;
		else
				$this->locked = false;
	}
	
	public function events()
	{
		return [
			ActiveRecord::EVENT_AFTER_INSERT => 'checkLock',
			ActiveRecord::EVENT_AFTER_UPDATE => 'checkLock',
			ActiveRecord::EVENT_AFTER_FIND => 'checkLock'
		];
	}
    
}
