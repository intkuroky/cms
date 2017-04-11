<?php namespace App\Http\Filters;

/**
 * 筛选器基类。 用于列表筛选器的后台处理
 * Class BaseFilter
 * @package App\YHT\Filters
 */
abstract class BaseFilter
{
    /**
     * 筛选器参数的命名空间
     * 如 /order?f[name]=Mike&f[type]=pending 的命名空间为 f
     * 如 /order?name=Mike&type=pending 的命名空间为 null或''
     * @var null
     */
    public $namespace = null;

    /**
     * 定义筛选器要处理的列
     * @return mixed
     */
    abstract public function columns();

    /**
     * 应用本筛选器到一个Eloquent|Query builder上
     * @param $builder
     * @param array $parameters
     * @return mixed
     */
    public function filter($builder, $parameters = [])
    {
        $parameters = $this->normalizeParameters($parameters);
        $columns = $this->columns();
        foreach ($columns as $column) {
            $builder = $column->appendQuery($builder, $parameters);
        }
        return $builder;
    }

    private function normalizeParameters($parameters)
    {
        if ($this->namespace) {
            $parameters = isset($parameters[$this->namespace]) ? $parameters[$this->namespace] : [];
        }
        return $parameters;
    }
}