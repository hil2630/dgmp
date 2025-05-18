<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BracketResource\Pages;
use App\Filament\Resources\BracketResource\RelationManagers;
use App\Models\Bracket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Laravel\Jetstream\Jetstream;

class BracketResource extends Resource
{
    protected static ?string $model = Bracket::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('starts_at')
                    ->required(),
                Forms\Components\TextInput::make('time_limit_hours')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Forms\Components\Toggle::make('is_locked')
                    ->default(false),
                Forms\Components\Select::make('teams')
                    ->multiple()
                    ->relationship('teams', 'name')
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time_limit_hours')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_locked')
                    ->boolean(),
                Tables\Columns\TextColumn::make('teams_count')
                    ->counts('teams')
                    ->label('Teams'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListBrackets::route('/'),
            'create' => Pages\CreateBracket::route('/create'),
            'edit' => Pages\EditBracket::route('/{record}/edit'),
        ];
    }
}
