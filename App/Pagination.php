<?php

namespace App;

use JasonGrimes\Paginator;

class Pagination
{
    /**
     * limit - какое количество записей мы хотим получить (10)
     * offset - отступ от записей (20) - page(GET)
     * Пропускаем 20 записей и берем 10
     *
     * 1. Узнать сколько записей в ообщем
     * 2. Узнать сколько записей нужно вывести
     **/

    protected $data;
    protected $paginator;

    public function __construct(QueryBuilder $qb, Paginator $paginator)
    {
        $this->data = $qb;
        $this->paginator = $paginator;
    }

    public function getSelectedData()
    {
        d($this->data->pagination(6));

    }
}