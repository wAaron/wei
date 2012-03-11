<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 */

/**
 * Query
 *
 * @package     Qwin
 * @subpackage  Application
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-07-05 01:18:32
 */
class Qwin_Query extends Doctrine_Query
{
    /**
     * 数据排序类型
     * @var array
     */
    protected static $_orderTypes = array(
        'DESC', 'ASC'
    );

    /**
     * 数据查询类型
     * @var array
     * @todo 是否使用%s替换
     */
    protected static $_searchTypes = array(
        'eq' => '=',
        'ne' => '<>',
        'lt' => '<',
        'le' => '<=',
        'gt' => '>',
        'ge' => '>=',
        'bw' => 'LIKE',
        'bn' => 'NOT LINK',
        'in' => 'IN',
        'ni' => 'NOT IN',
        'ew' => 'LIKE',
        'en' => 'NOT LIKE',
        'cn' => 'LIKE',
        'nc' => 'NOT LIKE',
    );

    /**
     * 查询对应的记录对象
     * @var Doctrine_Record
     */
    protected $_record;

    public function __construct(Doctrine_Connection $connection = null, Doctrine_Hydrator_Abstract $hydrator = null)
    {
        Qwin_Record::connect();
        parent::__construct($connection, $hydrator);
    }

    public function __invoke($module = null, $type = null)
    {
        $widget = Qwin::getInstance();
        $record = $widget->record($module, $type);
        $class = get_class($record);

        $query = Doctrine_Query::create(null, __CLASS__)->from($class);
        $query->_record = $record;
        return $query;
    }

    /**
     * 为Doctrine查询对象增加排序语句
     *
     * @param Doctrine_Query $query
     * @param array|null $order 排序配置
     * @return Com_Meta 当前对象
     * @todo 关联元数据的排序
     */
    public function addRawOrder($order = null)
    {
        if (empty($order)) {
            return $this;
        }

        // 排序只有一项,补全数组
        if (isset($order[0]) && (is_string($order[0])) || is_object($order[0])) {
            $order = array($order);
        }

        $alias = $this->getRootAlias();
        $alias && $alias .= '.';

        $columns = $this->_record->getTable()->getColumns();

        foreach ($order as $field) {
            if (!isset($field[0]) || !isset($columns[(string)$field[0]])) {
                continue;
            }
            $field[1] = strtoupper($field[1]);
            if (!in_array($field[1], self::$_orderTypes)) {
                $field[1] = $orderType[0];
            }
            $this->addOrderBy($alias . $field[0] . ' ' .  $field[1]);
        }
        return $this;
    }

    /**
     * 为Doctrine查询对象增加查找语句
     *
     * @param Doctrine_Query $query
     * @param array|null $addition 附加的排序配置
     * @return Com_Meta 当前对象
     * @todo 完善查询类型
     * @todo 复杂查询
     */
    public function addRawWhere($search = null)
    {
        if (is_string($search)) {
            $search = Qwin_Util_String::splitQuery($search);
        }
        $search = is_null($search) ? $this->_meta['where'] : $search;

        $alias = $this->getRootAlias();
        '' != $alias && $alias .= '.';

        foreach ($search as $fieldSet) {
            if (!isset($this->_record->options['fields'][$fieldSet[0]]) || !$this->_record->options['fields'][$fieldSet[0]]['dbField']) {
                continue;
            }
            if (!isset($fieldSet[2])) {
                $fieldSet[2] = 'eq';
            } else {
                $fieldSet[2] = strtolower($fieldSet[2]);
                !isset(self::$_searchTypes[$fieldSet[2]]) && $fieldSet[2] = 'eq';
            }
            switch ($fieldSet[2]) {
                case 'bw':
                case 'bn':
                    $value = '%' . $this->_escapeWildcard($fieldSet[1]);
                    break;
                case 'ew':
                case 'en':
                    $value = $this->_escapeWildcard($fieldSet[1]) . '%';
                    break;
                case 'cn':
                case 'nc':
                    $value = '%' . $this->_escapeWildcard($fieldSet[1]) . '%';
                    $value = '%' . $this->_escapeWildcard($fieldSet[1]) . '%';
                    break;
                /*case 'in':
                case 'ni':
                    $value = is_array($fieldSet[1]) ? $fieldSet[1] : array($fieldSet[1]);
                    break;
                /*case 'eq':
                case 'ne':
                case 'lt':
                case 'le':
                case 'gt':
                case 'ge':*/
                default:
                    $value = $fieldSet[1];
                    break;
            }
            if ('in' == $fieldSet[2] || 'ni' == $fieldSet[2]) {
                $valueSign = '(?)';
            } else {
                $valueSign = '?';
            }

            // null and not null
            if(null === $value || 'null' === strtolower($value)) {
                if ('eq' == $fieldSet[2]) {
                    $this->andWhere($alias . $fieldSet[0] . ' IS NULL');
                    continue;
                } elseif ('ne' == $fieldSet[2]) {
                    $this->andWhere($alias . $fieldSet[0] . ' IS NOT NULL');
                    continue;
                }
            }
            $this->andWhere($alias . $fieldSet[0] . ' ' . self::$_searchTypes[$fieldSet[2]] . ' ' . $valueSign, $value);
        }
        return $this;
    }

    /**
     * 转义LIKE语言中的通配符%和_
     *
     * @param string $value
     * @return string
     * @todo 其他通配符[]
     * @todo 其他数据库是否支持
     */
    protected function _escapeWildcard($value)
    {
        return strtr($value, array('%' => '\%', '_' => '\_'));
    }
}
