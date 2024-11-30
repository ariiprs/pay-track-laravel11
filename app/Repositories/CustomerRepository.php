<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;

class CustomerRepository implements CustomerRepositoryInterface
{

    public function getAllNewCustomers(){
        return Customer::latest()->get();
    }

    public function searchByName(string $keyword){
        return Customer::where('name', 'LIKE', '%' . $keyword . '%')->get();
    }

    public function find($id){
        return Customer::find($id);
    }
}