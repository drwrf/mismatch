<?php

namespace Mismatch\ORM;

use Mismatch\ORM\Expression\Composite;
use Doctrine\DBAL\Types\Type;
use Mockery;

class QueryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->conn = Mockery::mock('Doctrine\DBAL\Connection');
        $this->pk = 'id';

        $this->subject = new Query($this->conn, $this->pk);
        $this->subject->from(['authors' => 'author']);
    }

    public function test_raw()
    {
        $this->assertSelect(
            'SELECT author.* FROM authors AS author WHERE author.name = ?',
            ['test'], [\PDO::PARAM_STR]);

        $this->subject->raw(
            'SELECT author.* FROM authors AS author WHERE author.name = ?',
            ['test']);
    }

    public function test_bareAll()
    {
        $this->assertSelect(
            'SELECT author.* FROM authors AS author', [], []);

        $this->subject->all();
    }

    public function test_aggregateAll()
    {
        $this->subject->select(['COUNT(*)' => 'count']);

        $this->assertSelect(
            'SELECT COUNT(*) AS count FROM authors AS author', [], []);

        $this->subject->all();
    }

    public function test_find_id()
    {
        $this->assertSelect(
            'SELECT author.* FROM authors AS author '.
            'WHERE author.id = ? LIMIT 1',
            [1], [Type::INTEGER], [true]);
        $this->assertLimit(1);

        $this->subject->find(1);
    }

    /**
     * @expectedException  DomainException
     */
    public function test_find_missing()
    {
        $this->assertSelect(
            'SELECT author.* FROM authors AS author '.
            'WHERE author.id = ? LIMIT 1',
            [1], [Type::INTEGER]);
        $this->assertLimit(1);

        $this->subject->find(1);
    }

    public function test_where_withArray()
    {
        $this->subject->where(['name' => 'test']);

        $this->assertSelect(
            'SELECT author.* FROM authors AS author '.
            'WHERE author.name = ?',
            ['test'], [\PDO::PARAM_STR]);

        $this->subject->all();
    }

    public function test_where_withArrayMultiple()
    {
        $this->subject->whereAny(['name' => 'test', 'id' => 1]);

        $this->assertSelect(
            'SELECT author.* FROM authors AS author ' .
            'WHERE author.name = ? OR author.id = ?',
            ['test', 1], [\PDO::PARAM_STR, Type::INTEGER]);

        $this->subject->all();
    }

    public function test_where_withString()
    {
        $this->subject->where('name = ?', ['test']);

        $this->assertSelect(
            'SELECT author.* FROM authors AS author '.
            'WHERE name = ?',
            ['test'], [\PDO::PARAM_STR]);

        $this->subject->all();
    }

    public function test_having_withArray()
    {
        $this->subject->having(['COUNT(bonus)' => 1000]);

        $this->assertSelect(
            'SELECT author.* FROM authors AS author '.
            'HAVING COUNT(bonus) = ?',
            [1000], [Type::INTEGER]);

        $this->subject->all();
    }

    public function test_having_withArrayMultiple()
    {
        $this->subject->havingAny(['name' => 'test', 'id' => 1]);

        $this->assertSelect(
            'SELECT author.* FROM authors AS author '.
            'HAVING author.name = ? OR author.id = ?',
            ['test', 1], [\PDO::PARAM_STR, Type::INTEGER]);

        $this->subject->all();
    }

    public function test_having_withString()
    {
        $this->subject->having('name = ?', ['test']);

        $this->assertSelect(
            'SELECT author.* FROM authors AS author '.
            'HAVING name = ?',
            ['test'], [\PDO::PARAM_STR]);

        $this->subject->all();
    }

    public function test_limit()
    {
        $this->subject->limit(1);

        $this->assertSelect(
            'SELECT author.* FROM authors AS author LIMIT 1', [], []);
        $this->assertLimit(1);

        $this->subject->all();
    }

    public function test_order()
    {
        $this->subject->order([
            'name' => 'asc',
            'id' => 'desc',
       ]);

        $this->assertSelect(
            'SELECT author.* FROM authors AS author ' .
            'ORDER BY author.name ASC, author.id DESC', [], []);

        $this->subject->all();
    }

    public function test_group()
    {
        $this->subject->group(['name', 'id']);

        $this->assertSelect(
            'SELECT author.* FROM authors AS author ' .
            'GROUP BY author.name, author.id', [], []);

        $this->subject->all();
    }

    public function test_joins_defaultInnerJoin()
    {
        $this->subject->join('books b');

        $this->assertSelect(
            'SELECT author.* FROM authors AS author ' .
            'INNER JOIN books b', [], []);

        $this->subject->all();
    }

    public function test_joins_withArray()
    {
        $this->subject->join('books b', ['author.id' => 'b.author_id']);

        $this->assertSelect(
            'SELECT author.* FROM authors AS author ' .
            'INNER JOIN books b ON (author.id = b.author_id)', [], []);

        $this->subject->all();
    }

    public function test_joins_withExpression()
    {
        $expr = new Composite();
        $expr->all(['b.author_id' => true]);

        $this->subject->join('books b', $expr);

        $this->assertSelect(
            'SELECT author.* FROM authors AS author ' .
            'INNER JOIN books b ON (b.author_id = ?)',
            [true], [Type::BOOLEAN]);

        $this->subject->all();
    }

    public function test_joins_customJoin()
    {
        $this->subject->join('LEFT OUTER JOIN books b');

        $this->assertSelect(
            'SELECT author.* FROM authors AS author ' .
            'LEFT OUTER JOIN books b', [], []);

        $this->subject->all();
    }

    public function assertLimit($count)
    {
        $this->conn
            ->shouldReceive('getDatabasePlatform->modifyLimitQuery')
            ->andReturnUsing(function ($query) use ($count) {
                return $query . ' LIMIT ' . $count;
            });
    }

    public function assertSelect($sql, $params, $types, $result = [])
    {
        $stmt = Mockery::mock('Doctrine\DBAL\Driver\Statement');
        $stmt->shouldReceive('fetch')
            ->andReturn($result);

        $this->conn
            ->shouldReceive('executeQuery')
            ->with($sql, $params, $types)
            ->andReturn($stmt);
    }
}
