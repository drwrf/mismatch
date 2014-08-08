<?php

namespace Mismatch\ORM;

use Mismatch\ORM\Expression as Q;

function all($conds, $params = [])
{
    return new Q\All($conds, $params);
}

function any($conds, $params = [])
{
    return new Q\Any($conds, $params);
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
