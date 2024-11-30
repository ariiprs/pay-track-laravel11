<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DebtResource\Pages;
use App\Filament\Resources\DebtResource\RelationManagers;
use App\Models\Debt;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DebtResource extends Resource
{
    protected static ?string $model = Debt::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Fieldset::make('Informasi Debitur')
                ->schema([
                    Select::make('customer_id')
                    ->relationship('customer','customer_name')
                    ->searchable()
                    ->preload()
                    ->required(),

                    Select::make('category_id')
                    ->relationship('category', 'name')
                    ->preload()
                    ->required(),

                ]),

                Fieldset::make('Tagihan')
                ->schema([
                        TextInput::make('debt_amount')
                        ->numeric()
                        ->required()
                        ->prefix('IDR'),

                        TextInput::make('monthly_payment')
                        ->numeric()
                        ->required()
                        ->prefix('IDR'),

                        TextInput::make('borrow_date')
                        ->required()
                        ->maxLength(255),

                        TextInput::make('deadline_payment_date')
                        ->required()
                        ->maxLength(255),

                        Select::make('debt_status')
                        ->options([
                            true => 'Lunas',
                            false => 'Belum Lunas',
                        ])
                        ->required(),
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.customer_name')
                ->searchable(),

                TextColumn::make('category.name')
                ->searchable(),

                TextColumn::make('debt_amount'),

                TextColumn::make('monthly_payment'),

                IconColumn::make('debt_status')
                ->boolean()
                ->trueColor('success')
                ->falseColor('danger')
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->label('Status Hutang'),


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
            'index' => Pages\ListDebts::route('/'),
            'create' => Pages\CreateDebt::route('/create'),
            'edit' => Pages\EditDebt::route('/{record}/edit'),
        ];
    }
}
