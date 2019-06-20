<?php


namespace yiqiniu\library;


class Helper
{


    /**
     * 返回数组中指定多列
     * @param Array $input 需要取出数组列的多维数组
     * @param String $column_keys 要取出的列名，逗号分隔，如不传则返回所有列
     * @return Array
     */
    public function array_columns($input, $column_keys = null, $pk = null, $split = '_')
    {
        $result = array();
        $keys = empty($column_keys) || $column_keys == '*' ? [] : (is_array($column_keys) ? $column_keys : explode(',', $column_keys));
        if (count($keys) == 1) {
            $result = array_column($input, $keys[0], $pk);
        } else {
            if ($input) {
                foreach ($input as $k => $v) {
                    // 指定返回列
                    if ($keys && $v) {
                        $tmp = array();
                        foreach ($keys as $key) {
                            if ($sub_in = strpos($key, '|')) {
                                $tmp[substr($key, $sub_in + 1)] = isset($v[substr($key, 0, $sub_in)]) ? $v[substr($key, 0, $sub_in)] : '';
                            } elseif ($sub_in = strpos($key, '&')) {
                                list($v1, $v2) = explode('&', $key);
                                $tmp[$v1] = isset($v2) ? $v[$v2] . $split . $v[$v1] : $v[$v1];
                            } else {
                                $tmp[$key] = isset($v[$key]) ? $v[$key] : '';
                            }
                        }
                    } else {
                        $tmp = $v;
                    }
                    $result[$pk ? $v[$pk] : $k] = $tmp;
                }
            }
        }
        return $result;
    }

    /**
     * @param $info
     * @param null $column_keys
     * @return bool|mixed
     */
    public function info_columns($info, $column_keys = null)
    {
        $keys = empty($column_keys) || $column_keys == '*' ? [] : (is_array($column_keys) ? $column_keys : explode(',', $column_keys));
        $result = false;
        if ($info) {
            if (count($keys) == 1) {
                $result = $info[$keys[0]];
            } else {
                $tmp = false;
                foreach ($keys as $key) {
                    if ($sub_in = strpos($key, '|')) {
                        $tmp[substr($key, $sub_in + 1)] = isset($info[substr($key, 0, $sub_in)]) ? $info[substr($key, 0, $sub_in)] : '';
                    } else {
                        $tmp[$key] = isset($info[$key]) ? $info[$key] : '';
                    }
                }
                $result = $tmp;
            }
        }
        return $result;
    }

    /**
     * 把返回的数据集转换成Tree
     * @param array $list 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     * @return array
     */
    public function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = 'children', $root = 0)
    {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    /**
     * 将list_to_tree的树还原成列表
     * @param array $tree 原来的树
     * @param string $child 孩子节点的键
     * @param string $order 排序显示的键，一般是主键 升序排列
     * @param array $list 过渡用的中间数组，
     * @return array        返回排过序的列表数组
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function tree_to_list($tree, $child = 'children', $order = 'id', &$list = array())
    {
        if (is_array($tree)) {
            foreach ($tree as $key => $value) {
                $reffer = $value;
                if (isset($reffer[$child])) {
                    unset($reffer[$child]);
                    tree_to_list($value[$child], $child, $order, $list);
                }
                $list[] = $reffer;
            }
            // $list = list_sort_by($list, $order, $sortby='asc');
        }
        return $list;
    }

    /**
     * 对二维数组进行排序
     * @param $list array       要排序的数组
     * @param $field string     排序的字段
     * @param string $sortby 升序或降序
     * @return array|bool
     */
    public function list_sort_by($list, $field, $sortby = 'asc')
    {
        if (is_array($list)) {
            $refer = $resultSet = array();
            foreach ($list as $i => $data) {
                $refer[$i] = &$data[$field];
            }
            switch ($sortby) {
                case 'asc': // 正向排序
                    asort($refer);
                    break;
                case 'desc': // 逆向排序
                    arsort($refer);
                    break;
                case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ($refer as $key => $val) {
                $resultSet[] = &$list[$key];
            }
            return $resultSet;
        }
        return false;
    }

}