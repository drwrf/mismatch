<?php

namespace Mismatch\ORM;

use Mismatch\ORM\Expression as Q;

function all($conds, $params = [])
{
    $expr = new Q\Composite();
    $expr->all($conds, $params);

    return $expr;
}

function any($conds, $params = [])
{
    $expr = new Q\Composite();
    $expr->any($conds, $params);

    return $expr;
}

function expr($expr, $binds = [])
{
    return new Q\Expression($expr, $binds);
}

function eq($value)
{
    return new Q\Eq($value);
}

function notEq($value)
{
    return new Q\NotEq($value);
}

function in($value)
{
    return new Q\In($value);
}

function notIn($value)
{
    return new Q\Not(new Q\In($value));
}
