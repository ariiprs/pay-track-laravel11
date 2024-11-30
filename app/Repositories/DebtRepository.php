<?php

namespace App\Repositories;

use App\Models\Debt;
use App\Repositories\Contracts\DebtRepositoryInterface;
use Illuminate\Support\Facades\Session;

class DebtRepository implements DebtRepositoryInterface
{
    public function createTransaction(array $data){
        return Debt::create($data);
    }

    public function find($id){
        return Debt::find($id);
    }

    public function saveToSession(array $data){
        Session::put('order_data', $data);
    }

    public function getOrderDataFromSession(){
        return Session('order_data', []);
    }

    public function updateSessionData(array $data){
        $orderData = session('order_data', []);
        $orderData = array_merge($orderData, $data);
        session(['order_data' => $orderData ]);
    }

}