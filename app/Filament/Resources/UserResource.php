<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nguyen Van A'),

                        Forms\Components\TextInput::make('username')
                            ->label('Username')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('nguyenvana')
                            ->dehydrateStateUsing(fn ($state) => strtolower($state))
                            ->helperText('Chỉ chữ thường, không dấu, không khoảng trắng'),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('user@example.com'),

                        Forms\Components\TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('0123456789'),
                    ])->columns(2),

                Forms\Components\Section::make('Account Settings')
                    ->schema([
                        Forms\Components\Select::make('role')
                            ->label('Role')
                            ->options([
                                'admin' => 'Admin',
                                'student' => 'Student',
                            ])
                            ->required()
                            ->default('student')
                            ->native(false),

                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->placeholder('Enter password')
                            ->helperText(fn (string $context): string =>
                            $context === 'create'
                                ? 'Password is required for new users'
                                : 'Leave blank to keep current password'
                            ),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->dehydrated(false)
                            ->required(fn (string $context): bool => $context === 'create')
                            ->same('password')
                            ->placeholder('Confirm password'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => '@' . $record->username),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->placeholder('—')
                    ->copyable()
                    ->icon('heroicon-o-phone'),

                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'student' => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Admin',
                        'student' => 'Student',
                    }),

                Tables\Columns\TextColumn::make('enrollments_count')
                    ->label('Courses')
                    ->counts('enrollments')
                    ->sortable()
                    ->color('primary')
                    ->icon('heroicon-o-academic-cap'),

                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Orders')
                    ->counts('orders')
                    ->sortable()
                    ->color('warning')
                    ->icon('heroicon-o-shopping-cart'),

                Tables\Columns\TextColumn::make('total_spent')
                    ->label('Total Spent')
                    ->getStateUsing(fn ($record) =>
                    $record->orders()->where('status', 'paid')->sum('final_amount')
                    )
                    ->money('VND', locale: 'vi')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->withSum(['orders as total_spent' => fn ($query) =>
                        $query->where('status', 'paid')
                        ], 'final_amount')
                            ->orderBy('total_spent', $direction);
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Verified')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'student' => 'Student',
                    ])
                    ->native(false),

                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Email Verified')
                    ->nullable()
                    ->trueLabel('Verified')
                    ->falseLabel('Not Verified')
                    ->native(false),

                Tables\Filters\Filter::make('has_enrollments')
                    ->query(fn (Builder $query): Builder => $query->has('enrollments'))
                    ->label('With Courses'),

                Tables\Filters\Filter::make('has_orders')
                    ->query(fn (Builder $query): Builder => $query->has('orders'))
                    ->label('With Orders'),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Joined From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Joined Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('view_enrollments')
                        ->label('View Courses')
                        ->icon('heroicon-o-academic-cap')
                        ->color('success')
                        ->modalHeading(fn ($record) => $record->name . "'s Enrolled Courses")
                        ->modalContent(fn ($record) => view('filament.resources.user-resource.modals.enrollments', ['user' => $record]))
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Close'),

                    Tables\Actions\Action::make('view_orders')
                        ->label('View Orders')
                        ->icon('heroicon-o-shopping-cart')
                        ->color('warning')
                        ->modalHeading(fn ($record) => $record->name . "'s Orders")
                        ->modalContent(fn ($record) => view('filament.resources.user-resource.modals.orders', ['user' => $record]))
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Close'),

                    Tables\Actions\Action::make('toggle_role')
                        ->label(fn ($record) => $record->isAdmin() ? 'Demote to Student' : 'Promote to Admin')
                        ->icon(fn ($record) => $record->isAdmin() ? 'heroicon-o-arrow-down' : 'heroicon-o-arrow-up')
                        ->color(fn ($record) => $record->isAdmin() ? 'warning' : 'success')
                        ->requiresConfirmation()
                        ->modalHeading(fn ($record) => $record->isAdmin() ? 'Demote to Student?' : 'Promote to Admin?')
                        ->modalDescription(fn ($record) =>
                        $record->isAdmin()
                            ? 'This user will lose admin access.'
                            : 'This user will gain full admin access.'
                        )
                        ->action(fn ($record) => $record->update([
                            'role' => $record->isAdmin() ? 'student' : 'admin'
                        ]))
                        ->successNotificationTitle('Role updated'),

                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete User')
                        ->modalDescription('Are you sure? This will also delete all orders and enrollments.')
                        ->before(fn ($record) => $record->enrollments()->delete()),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('promote_to_admin')
                        ->label('Promote to Admin')
                        ->icon('heroicon-o-arrow-up')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['role' => 'admin']))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('demote_to_student')
                        ->label('Demote to Student')
                        ->icon('heroicon-o-arrow-down')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->update(['role' => 'student']))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount(['enrollments', 'orders']);
    }
}
