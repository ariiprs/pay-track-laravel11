<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\DebtRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DebtService
{
    protected $customerRepository;
    protected $categoryRepository;
    protected $debtRepository;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CustomerRepositoryInterface $customerRepository,
        DebtRepositoryInterface $debtRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->customerRepository = $customerRepository;
        $this->debtRepository = $debtRepository;
    }


    public function beginDebt(array $data){

        $orderData = [
            'customer_id' => $data['customer_id'],
            'category_id' => $data['category_id'],
        ];

        $this->debtRepository->saveToSession($orderData);
    }

    public function getDebtDetails()
    {
        $orderData = $this->debtRepository->getOrderDataFromSession();

        return $orderData;
    }

   /*  public function getDebtDetails()
    {
        $orderData = $this->debtRepository->getOrderDataFromSession();

        $customer = $this->customerRepository->find($orderData['customer_id']);

        $installmentNumber = isset($orderData['installment_number']) ? $orderData['installment_number'] : 1;
        $monthlyPayment = isset($orderData['monthly_payment']) ? $orderData['monthly_payment'] : 0;



                            $debtAmount = $debt ? $debt->debt_amount : 0;
                            $monthlyPayment = $debt ? $debt->monthly_payment : 0;

                            $installmentNumber = $get('installment_number') ?? 1;

                            $totalInstallment = $monthlyPayment * $installmentNumber;
                            $remainingAmount = $debtAmount - $totalInstallment;

                            $set('monthly_payment', $monthlyPayment);

                            $set('total_installment', $totalInstallment);
                            $set('remaining_amount', $remainingAmount);
    } */

    public function saveDebtData (array $data)
    {
        $this->debtRepository->saveToSession($data);
    }

    public function updateDebtData (array $data)
    {
        $this->debtRepository->updateSessionData($data);
    }


    /* pada function ini akan mengecek berdasarkan nama customer */
    public function debtDataConfirm()
    {
        // Mengambil data dari sesi
        $orderData = $this->debtRepository->getOrderDataFromSession();

        $debtId = null;

        try {
            DB::transaction(function () use (&$debtId, $orderData) {
                // Langsung gunakan $orderData sebagai data yang akan disimpan
                $debtData = [
                    'customer_id' => $orderData['customer_id'],
                    'category_id' => $orderData['category_id'],
                    'debt_amount' => $orderData['debt_amount'],
                    'monthly_payment' => $orderData['monthly_payment'],
                    'borrow_date' => $orderData['borrow_date'],
                    'deadline_payment_date' => $orderData['deadline_payment_date'],
                    'debt_status' => false, // Status default
                ];

                // Simpan ke database menggunakan repository
                $newDebt = $this->debtRepository->createTransaction($debtData);

                // Ambil ID dari data yang baru disimpan
                $debtId = $newDebt->id;
            });
        } catch (\Exception $e) {
            Log::error('Error in debt confirmation: ' . $e->getMessage());
            session()->flash('error', $e->getMessage());
            return null;
        }

        return $debtId;
    }

}