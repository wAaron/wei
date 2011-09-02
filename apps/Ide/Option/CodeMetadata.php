<?php
/**
 * Code
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-20 20:47:21
 */

class Ide_Option_CodeMeta extends Com_Meta
{
    public function setMeta()
    {
        $this->merge(array(
            'field' => array(
                'value' => array(
                ),
                'name' => array(
                ),
                'color' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Ide_Option_Widget', 'get'),
                            array('css-color', 'null'),
                        ),
                    ),
                ),
                'style' => array(
                ),
            ),
        ));
    }

    public function getDynamicFieldForm($field, $key)
    {
        $form = $this->field[$field]['form'];
        $form['name'] = 'code[' . $key . '][' . $field . ']';
        return $form;
    }
}
