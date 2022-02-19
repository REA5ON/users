<?php

namespace App;

use Aura\SqlQuery\QueryFactory;
use PDO;

class QueryBuilder extends \PDO
{
    private $pdo, $queryFactory;

    public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory; //new QueryFactory('mysql');
    }

    public function getAll($table, $cols = "*")
    {
        $select = $this->queryFactory->newSelect();
        $select->cols([$cols])
            ->from($table);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function pagination($limit)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from('user_data')
            ->setPaging($limit)
            ->page($_GET['page'] ?? 1);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getOne($table, $id, $cols = '*')
    {
        $select = $this->queryFactory->newSelect();

        $select->cols([$cols])
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($table, $data)
    {
        $insert = $this->queryFactory->newInsert();

        $insert->into($table)             // insert into this table
        ->cols($data);

        $sth = $this->pdo->prepare($insert->getStatement());
        $sth->execute($insert->getBindValues());
        $name = $insert->getLastInsertIdName('id');
        return $this->pdo->lastInsertId($name);
    }


    public function update($table, $data, $id)
    {
        $update = $this->queryFactory->newUpdate();

        $update
            ->table($table)
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $id);

        $sth = $this->pdo->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }

    public function delete($table, $id)
    {
        $delete = $this->queryFactory->newDelete();

        $delete
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);

        $sth = $this->pdo->prepare($delete->getStatement());
        $sth->execute($delete->getBindValues());

    }
}