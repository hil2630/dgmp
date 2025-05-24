<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RunResource\Pages;
use App\Filament\Resources\RunResource\RelationManagers;
use App\Models\Run;
use App\Services\DiscordNotificationService;
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
                Forms\Components\TextInput::make('time_taken_seconds')
                    ->required()
                    ->numeric()
                    ->label('Time Taken (seconds)')
                    ->minValue(1),
                Forms\Components\TextInput::make('warcraft_log_url')
                    ->label('Warcraft Log URL')
                    ->url()
                    ->placeholder('https://www.warcraftlogs.com/reports/...'),
                Forms\Components\Select::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'in_progress' => 'In Progress',
                    ])
                    ->required(),
                Forms\Components\Select::make('approval_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'denied' => 'Denied',
                    ])
                    ->default('pending')
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
                Tables\Columns\TextColumn::make('time_taken_seconds')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => gmdate('H:i:s', $state)),
                Tables\Columns\TextColumn::make('warcraft_log_url')
                    ->label('Warcraft Log')
                    ->url(fn ($record) => $record->warcraft_log_url)
                    ->openUrlInNewTab()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->warcraft_log_url),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'failed' => 'danger',
                        'in_progress' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('approval_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'denied' => 'danger',
                        'pending' => 'warning',
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
                Tables\Filters\SelectFilter::make('approval_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'denied' => 'Denied',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (Run $record) {
                        $record->update(['approval_status' => 'approved']);

                        // Send Discord notification
                        $discordService = new DiscordNotificationService();
                        $discordService->sendRunApprovedNotification($record);
                    })
                    ->visible(fn (Run $record) => $record->approval_status !== 'approved'),
                Tables\Actions\Action::make('deny')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(fn (Run $record) => $record->update(['approval_status' => 'denied']))
                    ->visible(fn (Run $record) => $record->approval_status !== 'denied'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulk_approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function ($records) {
                            $discordService = new DiscordNotificationService();

                            foreach ($records as $record) {
                                if ($record->approval_status !== 'approved') {
                                    $record->update(['approval_status' => 'approved']);
                                    $discordService->sendRunApprovedNotification($record);
                                }
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('bulk_deny')
                        ->label('Deny Selected')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['approval_status' => 'denied']);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
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
