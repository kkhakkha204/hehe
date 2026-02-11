<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Orders';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Information')
                    ->schema([
                        Forms\Components\TextInput::make('order_code')
                            ->label('Order Code')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'cancelled' => 'Cancelled',
                                'expired' => 'Expired',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\DateTimePicker::make('paid_at')
                            ->label('Paid At')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Expires At')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),

                Forms\Components\Section::make('Customer & Course')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('course_id')
                            ->label('Course')
                            ->relationship('course', 'title')
                            ->searchable()
                            ->preload()
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),

                Forms\Components\Section::make('Payment Details')
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->label('Original Amount')
                            ->disabled()
                            ->dehydrated(false)
                            ->suffix('₫')
                            ->numeric(),

                        Forms\Components\TextInput::make('discount_amount')
                            ->label('Discount Amount')
                            ->disabled()
                            ->dehydrated(false)
                            ->suffix('₫')
                            ->numeric(),

                        Forms\Components\TextInput::make('final_amount')
                            ->label('Final Amount')
                            ->disabled()
                            ->dehydrated(false)
                            ->suffix('₫')
                            ->numeric(),

                        Forms\Components\Select::make('coupon_id')
                            ->label('Coupon Used')
                            ->relationship('coupon', 'code')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('bank_transaction_id')
                            ->label('Bank Transaction ID')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),

                Forms\Components\Section::make('Payment Data')
                    ->schema([
                        Forms\Components\KeyValue::make('payment_data')
                            ->label('Webhook Data')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_code')
                    ->label('Order Code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->user->email),

                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->course->title),

                Tables\Columns\TextColumn::make('final_amount')
                    ->label('Amount')
                    ->money('VND', locale: 'vi')
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) =>
                    $record->discount_amount > 0
                        ? 'Giảm: ' . number_format($record->discount_amount) . '₫'
                        : null
                    ),

                Tables\Columns\TextColumn::make('coupon.code')
                    ->label('Coupon')
                    ->badge()
                    ->color('success')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'cancelled' => 'danger',
                        'expired' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                        'expired' => 'Expired',
                    }),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Paid At')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->color(fn ($record) =>
                    $record->status === 'pending' && $record->expires_at->isPast()
                        ? 'danger'
                        : null
                    )
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                        'expired' => 'Expired',
                    ])
                    ->native(false),

                Tables\Filters\SelectFilter::make('course')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('has_coupon')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('coupon_id'))
                    ->label('With Coupon'),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Until'),
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
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators[] = 'From ' . \Carbon\Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators[] = 'Until ' . \Carbon\Carbon::parse($data['created_until'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

//                    Tables\Actions\Action::make('view_customer')
//                        ->label('View Customer')
//                        ->icon('heroicon-o-user')
//                        ->url(fn ($record) => route('filament.admin.resources.users.edit', $record->user_id))
//                        ->openUrlInNewTab(),
//
//                    Tables\Actions\Action::make('view_course')
//                        ->label('View Course')
//                        ->icon('heroicon-o-academic-cap')
//                        ->url(fn ($record) => route('filament.admin.resources.courses.edit', $record->course_id))
//                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('mark_as_paid')
                        ->label('Mark as Paid')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => $record->status === 'pending')
                        ->requiresConfirmation()
                        ->modalHeading('Mark Order as Paid')
                        ->modalDescription('Are you sure you want to manually mark this order as paid?')
                        ->action(function ($record) {
                            $record->update([
                                'status' => 'paid',
                                'paid_at' => now(),
                            ]);

                            // Create enrollment
                            \App\Models\Enrollment::firstOrCreate([
                                'user_id' => $record->user_id,
                                'course_id' => $record->course_id,
                            ], [
                                'order_id' => $record->id,
                                'enrolled_at' => now(),
                            ]);

                            // Increment students count
                            $record->course->increment('current_students');

                            // If used coupon, increment usage
                            if ($record->coupon_id) {
                                \App\Models\CouponUsage::create([
                                    'coupon_id' => $record->coupon_id,
                                    'user_id' => $record->user_id,
                                    'order_id' => $record->id,
                                    'discount_amount' => $record->discount_amount,
                                ]);
                                $record->coupon->increment('usage_count');
                            }
                        })
                        ->successNotificationTitle('Order marked as paid'),

                    Tables\Actions\Action::make('cancel')
                        ->label('Cancel Order')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn ($record) => $record->status === 'pending')
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->update(['status' => 'cancelled']))
                        ->successNotificationTitle('Order cancelled'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('mark_as_expired')
                        ->label('Mark as Expired')
                        ->icon('heroicon-o-clock')
                        ->color('gray')
                        ->action(fn ($records) => $records->each->update(['status' => 'expired']))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('10s'); // Auto refresh mỗi 10 giây
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
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $pendingCount = static::getModel()::where('status', 'pending')->count();
        return $pendingCount > 0 ? 'warning' : null;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user', 'course', 'coupon']);
    }
}
