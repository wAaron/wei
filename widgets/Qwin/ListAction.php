<?php
/**
 * Index
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package     Widget
 * @subpackage  ListAction
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-10 14:16:45
 */

class Qwin_ListAction extends Qwin_Widget
{
    /**
     * @var array           默认选项
     * 
     *      -- get          用户请求的参数,默认为$_GET数组
     * 
     *      -- layout       布局
     * 
     *      -- row          每页显示数目
     * 
     *      -- display      是否显示视图
     */
    public $options = array(
        'grid'      => null,
        'order'     => array(),
        'row'       => 10,
        'layout'    => array(),
        'get'       => null,
        'display'   => true,
    );

    /**
     * 显示列表数据
     *
     * @param array $options 选项
     * @return mixed
     */
    public function call(array $options = array())
    {
        $this->option(&$options);
        $grid = $options['grid'];
        
        // 显示哪些域
        $grid->options['layout'] = $options['layout'];
        
        // 用户请求参数
        $get = $options['get'] ? $options['get'] : $_GET;
        if (!$grid->options['url']) {
            $grid->options['url'] = $this->url->build(array('json' => true) + $get);
        }

        // 不显示视图，直接返回数据
        if (!$options['display']) {
            return array(
                'result' => true,
                'data' => get_defined_vars(),
            );
        }

        // 加载列表视图
        $this->view
            ->assign(get_defined_vars())
            ->setElement('content', dirname(__FILE__) . '/ListAction/default.php');
    }
}