<?php

namespace App\controllers;
// Start a Session
if (!session_id()) @session_start();

use App\QueryBuilder;
use App\User;
use JasonGrimes\Paginator;
use League\Plates\Engine;


class UsersController
{
    protected $qb;
    protected $engine;
    protected $user;

    public function __construct(QueryBuilder $qb, Engine $engine, User $user)
    {
        $this->qb = $qb;
        $this->engine = $engine;
        $this->user = $user;
    }


    public function index($vars)
    {
        $this->user->isLoggedIn();
        /**
         * @param int $limit The number of items per page.
         * @param array $users Get data from DB with limit.
         * @param int $totalItems The total number of items.
         */
        $limit = 6;

        $users = $this->qb->pagination($limit);

        // Pagination
        $totalItems = count($this->qb->getAll('user_data'));
        $currentPage = $vars['page'] ?? 1;
        $urlPattern = '/page=(:num)';
        $paginator = new Paginator($totalItems, $limit, $currentPage, $urlPattern);

        echo $this->engine->render('users', ['users' => $users, 'paginator' => $paginator]);
    }
}