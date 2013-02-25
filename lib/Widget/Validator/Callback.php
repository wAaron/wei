<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Callback extends AbstractValidator
{
    protected $invalidMessage = '%name% is not valid';
    
    /**
     * The callback to validate the input value
     * 
     * @var callback
     */
    protected $fn;
    
    public function __invoke($input, \Closure $fn = null)
    {
        $fn && $this->fn = $fn;
        
        return $this->isValid($input);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if (!call_user_func($this->fn, $input, $this, $this->widget)) {
            $this->addError('invalid');
            return false;
        }
        
        return true;
    }
}
