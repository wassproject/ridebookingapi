<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeRangeResource\Pages;
use App\Filament\Resources\TimeRangeResource\RelationManagers;
use App\Models\TimeRange;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TimeRangeResource extends Resource
{
    protected static ?string $model = TimeRange::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->label('Label')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TimePicker::make('start_time')
                    ->label('Start Time')
                    ->required(),

                Forms\Components\TimePicker::make('end_time')
                    ->label('End Time')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('start_time')->sortable(),
                Tables\Columns\TextColumn::make('end_time')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListTimeRanges::route('/'),
            'create' => Pages\CreateTimeRange::route('/create'),
            'edit' => Pages\EditTimeRange::route('/{record}/edit'),
        ];
    }
}
