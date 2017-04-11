<?php namespace App\Http\Filters;

/**
 * 筛选器字段类。定义该字段的对应关系和筛选策略
 * Class Column
 * @package App\YHT\Filters
 */
class Column
{
    const LIKE = 'Like';
    const EQUAL = 'Equal';
    const NOT_EQUAL = 'NotEqual';
    const GREATER_THAN = 'GreaterThan';
    const LESS_THAN = 'LessThan';
    const BETWEEN = 'Between';
    const CALLBACK = 'Callback';
    const ABS_BETWEEN = 'ABSBetween';

    private $keyName = null;
    private $columnName = null;
    private $operator = null;
    private $callback = null;
    private $force = false;
    private $defaultValue = null;


    /**
     * Column constructor.
     * @param $keyName         参数字段名
     * @param null $columnName 数据库字段名
     * @param string $operator 筛选策略|操作符|回调函数
     * @param bool $force      true 必定触发, false 按需触发
     */
    function __construct($keyName, $columnName = null, $operator = self::EQUAL, $force = false)
    {
        $this->keyName = $keyName;
        $this->columnName = $columnName ?: $keyName;

        if (is_callable($operator)) {
            $this->operator = self::CALLBACK;
            $this->callback = $operator;
        } else {
            $this->operator = $operator ?: self::EQUAL;
        }

        $this->force = $force;
    }

    /**
     * 构建Column
     * @param $keyName
     * @param null $columnName
     * @param string $operator
     * @param bool $force
     * @return Column
     */
    public static function build($keyName, $columnName = null, $operator = self::EQUAL, $force = false)
    {
        $column = new self($keyName, $columnName, $operator, $force);
        return $column;
    }

    /**
     * 设置本列默认值
     * @param $defaultValue
     * @return $this
     */
    public function setDefaultValue($defaultValue)
    {
        if (is_callable($defaultValue)) {
            $defaultValue = $defaultValue();
        }
        $this->defaultValue = $defaultValue;
        return $this;
    }

    /**
     * @return null
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }


    /**
     * 应用该字段筛选策略到一个Eloquent|Query builder上
     * @param $builder
     * @param array $parameters
     * @return mixed
     * @throws \Exception
     */
    public function appendQuery($builder, $parameters = [])
    {
        if (!isset($parameters[$this->keyName]) && $this->defaultValue !== null) {
            $parameters[$this->keyName] = $this->defaultValue;
        }

        if(isset($parameters[$this->keyName]) && $parameters[$this->keyName] === ''){
            return $builder;
        }

        if (!isset($parameters[$this->keyName]) && $this->force == false) {
            return $builder;
        }
        $columnValue = isset($parameters[$this->keyName]) ? $parameters[$this->keyName] : null;
        $columnValue = $this->normalizeColumnValue($columnValue);

        $methodName = 'query' . $this->operator;
        if (!method_exists($this, $methodName)) {
            throw new \Exception("Bad operator: " . $this->operator);
        }

        $builder = $this->$methodName($builder, $columnValue);

        return $builder;
    }

    private function normalizeColumnValue($value)
    {
        if ($this->operator == self::BETWEEN || $this->operator == self::ABS_BETWEEN) {
            if (isset($value['start']) || isset($value['end'])) {
                $value = [$value];
            }
        } elseif ($this->operator != self::CALLBACK) {
            if (!is_array($value)) {
                $value = [$value];
            }
        }
        return $value;
    }

    /**
     * @return 参数字段名|null
     */
    public function getKeyName()
    {
        return $this->keyName;
    }

    /**
     * @param 参数字段名|null $keyName
     */
    public function setKeyName($keyName)
    {
        $this->keyName = $keyName;
    }

    /**
     * @return 参数字段名|null
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    /**
     * @param 参数字段名|null $columnName
     */
    public function setColumnName($columnName)
    {
        $this->columnName = $columnName;
    }

    /**
     * @return null|string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param null|string $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    /**
     * @return boolean
     */
    public function isForce()
    {
        return $this->force;
    }

    /**
     * @param boolean $force
     */
    public function setForce($force)
    {
        $this->force = $force;
    }

    private function queryLike($builder, $columnValues)
    {
        if (count($columnValues) == 1) {
            $builder = $builder->where($this->columnName, 'like', '%' . $columnValues[0] . '%');
        } else {
            $me = $this;
            $builder = $builder->where(function ($q) use ($me, $columnValues) {
                foreach ($columnValues as $value) {
                    $q->orWhere($me->columnName, 'like', '%' . $value . '%');
                }
            });
        }
        return $builder;
    }

    private function queryEqual($builder, $columnValues)
    {
        if (count($columnValues) == 1) {
            $builder = $builder->where($this->columnName, $columnValues[0]);
        } else {
            $me = $this;
            $builder = $builder->where(function ($q) use ($me, $columnValues) {
                foreach ($columnValues as $value) {
                    $q->orWhere($me->columnName, $value);
                }
            });
        }
        return $builder;
    }

    private function queryNotEqual($builder, $columnValues)
    {
        if (count($columnValues) == 1) {
            $builder->where($this->columnName, '<>', $columnValues[0]);
        } else {
            $me = $this;
            $builder = $builder->where(function ($q) use ($me, $columnValues) {
                foreach ($columnValues as $value) {
                    $q->where($me->columnName, '<>', $value);
                }
            });
        }
        return $builder;
    }

    private function queryGreaterThan($builder, $columnValues)
    {
        if (count($columnValues) == 1) {
            $builder->where($this->columnName, '>', $columnValues[0]);
        } else {
            $me = $this;
            $builder = $builder->where(function ($q) use ($me, $columnValues) {
                foreach ($columnValues as $value) {
                    $q->where($me->columnName, '>', $value);
                }
            });
        }
        return $builder;
    }

    private function queryLessThan($builder, $columnValues)
    {
        if (count($columnValues) == 1) {
            $builder->where($this->columnName, '<', $columnValues[0]);
        } else {
            $me = $this;
            $builder = $builder->where(function ($q) use ($me, $columnValues) {
                foreach ($columnValues as $value) {
                    $q->where($me->columnName, '<', $value);
                }
            });
        }
        return $builder;
    }

    private function queryBetween($builder, $columnValues)
    {
        if (count($columnValues) == 1) {
            if ($this->columnName == 'created_at' && $columnValues[0]['start'] <= $columnValues[0]['end']) {
                $columnValues[0]['end'] .= ' 23:59:59';
            }
            (!isset($columnValues[0]['start']) || $columnValues[0]['start'] == '') ?: $builder->where($this->columnName,
                '>=', $columnValues[0]['start']);
            (!isset($columnValues[0]['end']) || $columnValues[0]['end'] == '') ?: $builder->where($this->columnName,
                '<=', $columnValues[0]['end']);
        } else {
            $me = $this;
            $builder = $builder->where(function ($query) use ($me, $columnValues) {
                foreach ($columnValues as $value) {
                    $query->orWhere(function ($q) use ($me, $value) {
                        if ($me->columnName == 'created_at' && $value['start'] <= $value['end']) {
                            $value['end'] .= ' 23:59:59';
                        }
                        (!isset($value['start']) || $value['start'] == '') ?: $q->where($me->columnName, '>=',
                            $value['start']);
                        (!isset($value['end']) || $value['end'] == '') ?: $q->where($me->columnName, '<=',
                            $value['end']);
                    });
                }
            });
        }
        return $builder;
    }

    /**
     * 字段的绝对值区间检索
     * @param $builder
     * @param $columnValues
     * @return mixed
     */
    private function queryABSBetween($builder, $columnValues)
    {
        if (count($columnValues) == 1) {
            (!isset($columnValues[0]['start']) || $columnValues[0]['start'] == '') ?: $builder->whereRaw('ABS(' . $this->columnName . ') >= ' . $columnValues[0]['start']);
            (!isset($columnValues[0]['end']) || $columnValues[0]['end'] == '') ?: $builder->whereRaw('ABS(' . $this->columnName . ') <= ' . $columnValues[0]['end']);
        } else {
            $me = $this;
            $builder = $builder->where(function ($query) use ($me, $columnValues) {
                foreach ($columnValues as $value) {
                    $query->orWhere(function ($q) use ($me, $value) {
                        (!isset($value['start']) || $value['start'] == '') ?: $q->whereRaw('ABS(' . $me->columnName . ') >= ' . $value['start']);
                        (!isset($value['end']) || $value['end'] == '') ?: $q->whereRaw('ABS(' . $me->columnName . ') <= ' . $value['end']);
                    });
                }
            });
        }
        return $builder;
    }

    private function queryCallback($builder, $columnValues)
    {
        $callback = $this->callback;
        $builder = call_user_func($callback, $builder, $this, $columnValues);
        return $builder;
    }
}