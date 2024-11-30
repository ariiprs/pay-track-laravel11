<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\CustomerRepositoryInterface;

class FrontService
{
    protected $categoryRepository;
    protected $customerRepository;

    public function __construct( CategoryRepositoryInterface $categoryRepository, CustomerRepositoryInterface $customerRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->customerRepository = $customerRepository;
    }

    public function getFrontPageData()
    {
        $categories = $this->categoryRepository->getAllCategories();
        $newCustomers = $this->customerRepository->getAllNewCustomers();

        return compact('categories', 'newCustomers');
    }

    public function searchCustomers(string $keyword)
    {
        return $this->customerRepository->searchByName($keyword);
    }


}