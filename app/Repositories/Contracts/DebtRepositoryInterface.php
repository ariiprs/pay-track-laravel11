<?php

namespace App\Repositories\Contracts;

interface DebtRepositoryInterface
{
    public function createTransaction(array $data);

    public function find($id);

    public function saveToSession(array $data);

    public function updateSessionData(array $data);

    public function getOrderDataFromSession();

}