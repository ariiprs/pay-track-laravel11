<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAllDataRequest;
use App\Http\Requests\StoreDataDebtRequest;
use App\Http\Requests\StoreDebtRequest;
use App\Models\Customer;
use App\Models\Debt;
use Illuminate\Http\Request;
use App\Services\DebtService;

class DebtController extends Controller
{
    protected $debtService;

    public function __construct(DebtService $debtService)
    {
        $this->debtService = $debtService;

    }
    public function saveDebtCust(StoreDebtRequest $request, Customer $customer)
    {
        $validated  = $request->validated();

        $validated['customer_id'] = $customer->id;

        $this->debtService->beginDebt($validated);

        return redirect()->route('front.booking', $customer->slug);
    }


    public function booking()
    {
        $data = $this->debtService->getDebtDetails();

        return view('order.order', $data);
    }

    public function saveDataDebt(StoreDataDebtRequest $request)
{
    // Validasi data dari request
    $validated = $request->validated();

    // Simpan atau perbarui data hutang melalui service
    $this->debtService->updateDebtData($validated);

    // Konfirmasi dan simpan ke database
    $debtConfirm = $this->debtService->debtDataConfirm();

    // Periksa apakah penyimpanan berhasil
    if ($debtConfirm) {
        return redirect()->route('front.confirmation')->with('success', 'Debt data confirmed successfully!');
    } else {
        return redirect()->back()->with('error', 'Failed to confirm debt data. Please try again.');
    }
}


    // public function debtDataConfirm()
    // {
    //     // Memanggil fungsi service untuk menyimpan data ke database
    //     $debtId = $this->debtService->debtDataConfirm();

    //     // Periksa apakah penyimpanan berhasil
    //     if ($debtId) {
    //         // Redirect ke halaman konfirmasi jika berhasil
    //         return redirect()->route('front.confirmation')->with('success', 'Debt data confirmed successfully!');
    //     } else {
    //         // Redirect kembali dengan pesan error jika terjadi kesalahan
    //         return redirect()->back()->with('error', 'Failed to confirm debt data. Please try again.');
    //     }
    // }

    public function orderFinished(Debt $debt)
    {
        dd($debt);
    }

}
