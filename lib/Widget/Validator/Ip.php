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
 * @link http://php.net/manual/en/filter.filters.flags.php
 */
class Ip extends AbstractValidator
{
    protected $notAllowMessage = '%name% must be valid IP';
    
    protected $notMessage = '%name% must not be IP';
    
    /**
     * Allows the IP address to be ONLY in IPv4 format
     * 
     * @var bool
     */
    protected $ipv4 = false;
    
    /**
     * Allows the IP address to be ONLY in IPv6 format
     * 
     * @var bool
     */
    protected $ipv6 = false;
    
    /**
     * Not allows the IP address to be in private ranges
     * 
     * @var bool
     */
    protected $noPrivRange = false;
    
    /**
     * Not allows the IP address to be in reserved ranges
     * 
     * @var bool
     */
    protected $noResRange = false;
    
    public function __invoke($input, $options = array())
    {
        $options && $this->option($options);
        
        return $this->isValid($input);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        $flag = 0;
        if ($this->ipv4) {
            $flag = $flag | FILTER_FLAG_IPV4;
        }
        if ($this->ipv6) {
            $flag = $flag | FILTER_FLAG_IPV6;
        }
        if ($this->noPrivRange) {
            $flag = $flag | FILTER_FLAG_NO_PRIV_RANGE;
        }
        if ($this->noResRange) {
            $flag = $flag | FILTER_FLAG_NO_RES_RANGE;
        }

        if (!filter_var($input, FILTER_VALIDATE_IP, $flag)) {
            $this->addError('notAllow');
            return false;
        }
        
        return true;
    }
}
