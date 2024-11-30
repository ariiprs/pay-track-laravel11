<?php

namespace App\Repositories\Contracts;

interface CustomerRepositoryInterface
{
    public function getAllNewCustomers();

    public function searchByName(string $keyword);

    public function find ($id);

}