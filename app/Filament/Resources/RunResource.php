<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RunResource\Pages;
use App\Filament\Resources\RunResource\RelationManagers;
use App\Models\Run;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Laravel\Jetstream\Jetstream;

class RunResource extends Resource
{
    protected static ?string $model = Run::class;

    protected static ?string $navigationIcon = 'heroicon-o-play';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('season_id')
                    ->relationship('season', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('dungeon_name')
                    ->options(Run::DUNGEONS)
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('key_level')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Forms\Components\DateTimePicker::make('completed_at')
                    ->required(),
                Forms\Components\TextInput::make('time_taken_seconds')
                    ->required()
                    ->numeric()
                    ->label('Time Taken (seconds)')
                    ->minValue(1),
                Forms\Components\Select::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'in_progress' => 'In Progress',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('season.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('team.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dungeon_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('key_level')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time_taken_seconds')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => gmdate('H:i:s', $state)),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'failed' => 'danger',
                        'in_progress' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('key_level', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('season')
                    ->relationship('season', 'name'),
                Tables\Filters\SelectFilter::make('team')
                    ->relationship('team', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'in_progress' => 'In Progress',
                    ]),
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
            'index' => Pages\ListRuns::route('/'),
            'create' => Pages\CreateRun::route('/create'),
            'edit' => Pages\EditRun::route('/{record}/edit'),
        ];
    }
}
