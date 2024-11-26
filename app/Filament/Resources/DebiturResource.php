<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DebiturResource\Pages;
use App\Filament\Resources\DebiturResource\RelationManagers;
use App\Models\Debitur;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DebiturResource extends Resource
{
    protected static ?string $model = Debitur::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                    TextInput::make('email')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->searchable(),

                TextColumn::make('email')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListDebiturs::route('/'),
            'create' => Pages\CreateDebitur::route('/create'),
            'edit' => Pages\EditDebitur::route('/{record}/edit'),
        ];
    }
}
