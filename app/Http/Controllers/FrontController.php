<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Debt;
use App\Services\FrontService;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    protected $frontService;

    public function __construct(FrontService $frontService)
    {
        $this->frontService = $frontService;
    }

    public function index()
    {
        $data = $this->frontService->getFrontPageData();
        return view('front.index', $data);
    }

    public function details(Debt $debt)
    {
        return view('front.details', compact('debt'));
    }


    public function category(Category $category)
    {
        return view ('front.category', compact('category'));
    }


    //ini adalah halaman untuk memulai pemilihan order 
    public function chooseOrder(Debt $debt)
    {
        return view('front.chooseOrder', compact('debt'));
    }
}
