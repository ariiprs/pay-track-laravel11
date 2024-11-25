<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CustomerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CustomerResource\RelationManagers;
use Filament\Forms\Components\Repeater;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        //ini bagian form input
        return $form
            ->schema([

                Fieldset::make('Details')
                ->schema([
                    Select::make('debitur_id')
                    ->relationship('debitur', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                    TextInput::make('customer_name')
                    ->required()
                    ->maxLength(255),

                    TextInput::make('work')
                    ->required()
                    ->maxLength(255),

                    Textarea::make('address')
                    ->required(),
                ]),

                Fieldset::make('Foto Customer')
                ->schema([

                    Repeater::make('photos')
                    ->relationship('photos')
                    ->schema([
                        FileUpload::make('photo')
                        ->required(),
                    ]),

                ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //ini merupakan table yang akan menampilkan data kita nanti
                TextColumn::make('customer_name')
                ->searchable(),

                TextColumn::make('debitur.name'),

                TextColumn::make('work')


            ])
            ->filters([
                //ini merupakan filter yang akan digunakan untuk mencari data
                SelectFilter::make('debitur_id')
                ->label('Debitur')
                ->relationship('debitur', 'name')

            ])
            ->actions([
                //ini merupakan action yang akan digunakan untuk mengedit data,view data, dan bisa custom juga
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
