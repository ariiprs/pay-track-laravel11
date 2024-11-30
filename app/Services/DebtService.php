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
    public function debtDataConfirm(array $validated)
    {
        $orderData = $this->debtRepository->getOrderDataFromSession();

        $debtId = null;


        try {
            DB::transaction(function () use ($validated, &$debtId, $orderData)
            {
                $validated['customer_id'] = $orderData['customer_id'];
                $validated['customer_name'] = $orderData['customer_name'];
                $validated['debitur_id'] = $orderData['debitur_id'];
                $validated['category_id'] = $orderData['category_id'];
                $validated['debt_amount'] = $orderData['debt_amount'];
                $validated['monthly_payment'] = $orderData['monthly_payment'];
                $validated['borrow_date'] = $orderData['borrow_date'];
                $validated['deadline_payment_date'] = $orderData['deadline_payment_date'];
                $validated['work'] = $orderData['work'];
                $validated['address'] = $orderData['address'];
                $validated['debt_status'] = false;

                $newDebt = $this->debtRepository->createTransaction($validated);

                $debtId = $newDebt->id;

            });

            /*  DB::transaction(function () use ($validated, &$productTransactionId, $orderData) {
                if(isset($validated['proof'])){
                    $proofPath = $validated['proof']->store('proofs', 'public');
                    //yang disimpan hanya nama file nya saja, kalo fotonya di simpan di database
                    $validated['proof'] = $proofPath;
                } */
        }catch (\Exception $e) {
            Log::error( 'Error in payment confirmation: ' . $e->getMessage());
            session()->flash('error', $e->getMessage());
            return null;
        }

        return $debtId;
    }
}