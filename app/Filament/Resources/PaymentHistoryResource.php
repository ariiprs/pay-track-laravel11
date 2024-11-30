<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentHistoryResource\Pages;
use App\Filament\Resources\PaymentHistoryResource\RelationManagers;
use App\Models\Debt;
use App\Models\PaymentHistory;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentHistoryResource extends Resource
{
    protected static ?string $model = PaymentHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Select::make('debt_id')
                        ->relationship('debt', 'id')
                        ->getOptionLabelFromRecordUsing(fn (Debt $record) => $record->customer->customer_name)
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                            $debt = Debt::find($state);
                            
                            $debtAmount = $debt ? $debt->debt_amount : 0;
                            $monthlyPayment = $debt ? $debt->monthly_payment : 0;

                            $installmentNumber = $get('installment_number') ?? 1;

                            $totalInstallment = $monthlyPayment * $installmentNumber;
                            $remainingAmount = $debtAmount - $totalInstallment;

                            $set('monthly_payment', $monthlyPayment);

                            $set('total_installment', $totalInstallment);
                            $set('remaining_amount', $remainingAmount);
                        })








                        // ->afterStateHydrated(function (callable $get, callable $set, $state) {
                        //     $debt = Debt::find($state);
                        //     $monthlyPayment = $debt ? $debt->monthly_payment : 0;
                        //     $debtAmount = $debt ? $debt->debt_amount : 0;
                        //     $installmentNumber = $get('installment_number') ?? 0;
                        //     $totalInstallment = $monthlyPayment * $installmentNumber;
                        //     $remainingAmount = $debtAmount - $totalInstallment;

                        //     $set('monthly_payment', $monthlyPayment);
                        //     $set('total_installment', $totalInstallment);
                        //     $set('remaining_amount', $remainingAmount);
                        // })
                        ,

                    TextInput::make('installment_number')
                        ->numeric()
                        ->required()
                        ->label('Jumlah Angsuran')
                        ->prefix('Angsuran ke-')
                        ->live()
                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                            $monthlyPayment = $get('monthly_payment') ?? 0;  // Ensure monthly payment is set
                            $debt = Debt::find($get('debt_id'));  // Fetch current debt details dynamically
                            $debtAmount = $debt ? $debt->debt_amount : 0;

                            $installmentNumber = (int) $state;
                            $totalInstallment = $monthlyPayment * $installmentNumber;
                            $remainingAmount = max($debtAmount - $totalInstallment, 0);  // Prevent negative values

                            $set('total_installment', $totalInstallment);
                            $set('remaining_amount', $remainingAmount);
                        })
                        // ->afterStateHydrated(function (callable $get, callable $set, $state) {
                        //     $monthlyPayment = $get('monthly_payment') ?? 0;
                        //     $debtAmount = $get('debt_amount') ?? 0;
                        //     $totalInstallment = $monthlyPayment * $state;
                        //     $remainingAmount = $debtAmount - $totalInstallment;

                        //     $set('total_installment', $totalInstallment);
                        //     $set('remaining_amount', $remainingAmount);
                        // })
                        ,

                TextInput::make('total_installment')
                ->required()
                ->numeric()
                ->readOnly()
                ->prefix('IDR'),

                TextInput::make('remaining_amount')
                ->required()
                ->readOnly()
                ->numeric()
                ->prefix('IDR'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('debt.customer.customer_name')
                ->searchable(),

                TextColumn::make('installment_number'),
                TextColumn::make('total_installment'),

                TextColumn::make('remaining_amount'),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentHistories::route('/'),
            'create' => Pages\CreatePaymentHistory::route('/create'),
            'edit' => Pages\EditPaymentHistory::route('/{record}/edit'),
        ];
    }
}
