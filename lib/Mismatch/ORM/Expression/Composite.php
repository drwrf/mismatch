<?php

namespace Mismatch\ORM\Expression;

class Composite
{
    /**
     * @var  string
     */
    private $alias;

    /**
     * @var  array
     */
    private $expr = [];

    /**
     * @param  string  $alias
     */
    public function __construct($alias = null)
    {
        $this->alias = $alias;
    }

    /**
     * @return  string
     */
    public function __toString()
    {
        return current($this->compile());
    }

    /**
     * Compiles the expression into a string and a set of params.
     *
     * @return [$expr, $params]
     */
    public function compile()
    {
        $string = '';
        $params = [];

        foreach ($this->expr as $expr) {
            if ($string) {
                $string .= ' ' . $expr['type'] . ' ';
            }

            $string .= $expr['expr'];
            $params = array_merge($params, $expr['bind']);
        }

        return [$string, $params];
    }

    /**
     * Combines all expressions passed using an AND.
     *
     * @param  string|array  $conds
     * @param  array         $params
     * @return $this
     */
    public function all($conds, array $params = [])
    {
        $this->expr = array_merge($this->expr, $this->addConditions('AND', $conds, $params));

        return $this;
    }

    /**
     * Combines all expressions passed using an AND.
     *
     * @param  string|array  $conds
     * @param  array         $params
     * @return $this
     */
    public function any($conds, array $params = [])
    {
        $this->expr = array_merge($this->expr, $this->addConditions('OR', $conds, $params));

        return $this;
    }

    /**
     * @return  array
     */
    private function addConditions($type, $conds, array $params)
    {
        $ret = [];

        // Passing a simple string like 'foo.bar = ?', [$bar] should
        // work fine, so we can skip the complicated array syntax.
        if (is_string($conds)) {
            return [[
                'expr' => $conds,
                'bind' => $params,
                'type' => $type,
            ]];
        }

        foreach ($conds as $column => $value) {
            // Allow passing a literal string, which is useful for
            // completely literal expressions (such as those used for joins).
            if (is_int($column)) {
                $ret[] = [
                    'expr' => $value,
                    'type' => $type,
                    'bind' => [],
                ];

                continue;
            }

            // Try and provide a table alias if possible.
            $column = $this->columnize($column, $this->alias);

            // And automatically detect an IN if possible.
            if (is_array($value)) {
                $value = new In($value);
            }

            if (!($value instanceof Expression)) {
                $value = new Eq($value);
            }

            $ret[] = [
                'expr' => $value->getExpression($column),
                'bind' => $value->getValues(),
                'type' => $type,
            ];
        }

        return $ret;
    }

    private function columnize($column, $source)
    {
        if ($source && !strpos($column, '.') && !strpos($column, '(')) {
            return $source. '.' . $column;
        } else {
            return $column;
        }
    }
}
