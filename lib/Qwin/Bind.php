<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

namespace Qwin;

/**
 * Bind
 * 
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Bind extends Widget
{
    /**
     * 绑定事件
     * 
     * @see Qwin_Event::add()
     * @param string $event 事件名称
     * @param mixed $callback 回调结构
     * @return void
     */
    public function __invoke($event, $callback, $priority = 10)
    {
        return $this->event->add($event, $callback, $priority);
    }
}
