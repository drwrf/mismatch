<?php

namespace Mismatch\ORM\Expression;

class Composite implements ExpressionInterface
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
     * @var  bool
     */
    private $compiled = false;

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
        return $this->getExpression();
    }

    /**
     * Combines all expressions passed using an AND.
     *
     * @param  string|array  $expr
     * @param  array         $vals
     * @return $this
     */
    public function all($expr, array $vals = [])
    {
        $this->expr = array_merge($this->expr, $this->addConditions('AND', $expr, $vals));

        return $this;
    }

    /**
     * Combines all expressions passed using an AND.
     *
     * @param  string|array  $expr
     * @param  array         $vals
     * @return $this
     */
    public function any($expr, array $vals = [])
    {
        $this->expr = array_merge($this->expr, $this->addConditions('OR', $expr, $vals));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getExpression($column = null)
    {
        return $this->compile()[0];
    }

    /**
     * {@inheritDoc}
     */
    public function getValues()
    {
        return $this->compile()[1];
    }

    /**
     * Compiles the expression into a string and a set of params.
     *
     * @return [$expr, $vals]
     */
    private function compile()
    {
        if (!$this->compiled) {
            $expr = '';
            $vals = [];

            foreach ($this->expr as $part) {
                if ($expr) {
                    $expr .= ' ' . $part['type'] . ' ';
                }

                $expr .= $part['expr'];
                $vals = array_merge($vals, $part['vals']);
            }

            $this->compiled = [$expr, $vals];
        }

        return $this->compiled;
    }

    /**
     * @return  array
     */
    private function addConditions($type, $expr, array $vals)
    {
        $ret = [];

        // Ensure we mark the need for recompilation.
        $this->compiled = false;

        // Passing a simple string like 'foo.bar = ?', [$bar] should
        // work fine, so we can skip the complicated array syntax.
        if (is_string($expr)) {
            return [[
                'expr' => $expr,
                'vals' => $vals,
                'type' => $type,
            ]];
        }

        foreach ($expr as $column => $value) {
            // Allow passing a literal string, which is useful for
            // completely literal expressions (such as those used for joins).
            if (is_int($column)) {
                $ret[] = [
                    'expr' => $value,
                    'type' => $type,
                    'vals' => [],
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
                'vals' => $value->getValues(),
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
