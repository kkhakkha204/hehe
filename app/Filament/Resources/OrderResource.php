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

    protected static ?string $navigationLabel = 'Đơn hàng';

    protected static ?string $modelLabel = 'đơn hàng';

    protected static ?string $pluralModelLabel = 'đơn hàng';

    protected static ?string $navigationGroup = 'Bán hàng';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin đơn hàng')
                    ->schema([
                        Forms\Components\TextInput::make('order_code')
                            ->label('Mã đơn hàng')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'pending' => 'Chờ thanh toán',
                                'paid' => 'Đã thanh toán',
                                'cancelled' => 'Đã hủy',
                                'expired' => 'Hết hạn',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\DateTimePicker::make('paid_at')
                            ->label('Thanh toán lúc')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Hết hạn lúc')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),

                Forms\Components\Section::make('Khách hàng và khóa học')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Khách hàng')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('course_id')
                            ->label('Khóa học')
                            ->relationship('course', 'title')
                            ->searchable()
                            ->preload()
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),

                Forms\Components\Section::make('Chi tiết thanh toán')
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->label('Giá gốc')
                            ->disabled()
                            ->dehydrated(false)
                            ->suffix('₫')
                            ->numeric(),

                        Forms\Components\TextInput::make('discount_amount')
                            ->label('Số tiền giảm')
                            ->disabled()
                            ->dehydrated(false)
                            ->suffix('₫')
                            ->numeric(),

                        Forms\Components\TextInput::make('final_amount')
                            ->label('Số tiền cuối')
                            ->disabled()
                            ->dehydrated(false)
                            ->suffix('₫')
                            ->numeric(),

                        Forms\Components\Select::make('coupon_id')
                            ->label('Mã giảm giá đã dùng')
                            ->relationship('coupon', 'code')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('bank_transaction_id')
                            ->label('Mã giao dịch ngân hàng')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),

                Forms\Components\Section::make('Dữ liệu thanh toán')
                    ->schema([
                        Forms\Components\KeyValue::make('payment_data')
                            ->label('Dữ liệu webhook')
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
                    ->label('Mã đơn hàng')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Khách hàng')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->user->email),

                Tables\Columns\TextColumn::make('course.title')
                    ->label('Khóa học')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->course->title),

                Tables\Columns\TextColumn::make('final_amount')
                    ->label('Số tiền')
                    ->money('VND', locale: 'vi')
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->discount_amount > 0
                        ? 'Giảm: ' . number_format($record->discount_amount) . '₫'
                        : null),

                Tables\Columns\TextColumn::make('coupon.code')
                    ->label('Mã giảm giá')
                    ->badge()
                    ->color('success')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'cancelled' => 'danger',
                        'expired' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Chờ thanh toán',
                        'paid' => 'Đã thanh toán',
                        'cancelled' => 'Đã hủy',
                        'expired' => 'Hết hạn',
                    }),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Thanh toán lúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Hết hạn')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->color(fn ($record) => $record->status === 'pending' && $record->expires_at->isPast() ? 'danger' : null)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Chờ thanh toán',
                        'paid' => 'Đã thanh toán',
                        'cancelled' => 'Đã hủy',
                        'expired' => 'Hết hạn',
                    ])
                    ->native(false),

                Tables\Filters\SelectFilter::make('course')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('has_coupon')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('coupon_id'))
                    ->label('Có mã giảm giá'),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Từ ngày'),
                        Forms\Components\DatePicker::make('created_until')->label('Đến ngày'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $indicators[] = 'Từ ' . \Carbon\Carbon::parse($data['created_from'])->format('d/m/Y');
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators[] = 'Đến ' . \Carbon\Carbon::parse($data['created_until'])->format('d/m/Y');
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('mark_as_paid')
                        ->label('Đánh dấu đã thanh toán')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => $record->status === 'pending')
                        ->requiresConfirmation()
                        ->modalHeading('Đánh dấu đơn đã thanh toán')
                        ->modalDescription('Bạn có chắc muốn xác nhận thủ công đơn hàng này đã thanh toán?')
                        ->action(function ($record) {
                            $record->update([
                                'status' => 'paid',
                                'paid_at' => now(),
                            ]);

                            \App\Models\Enrollment::firstOrCreate([
                                'user_id' => $record->user_id,
                                'course_id' => $record->course_id,
                            ], [
                                'order_id' => $record->id,
                                'enrolled_at' => now(),
                            ]);

                            $record->course->increment('current_students');

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
                        ->successNotificationTitle('Đã cập nhật đơn hàng thành đã thanh toán'),

                    Tables\Actions\Action::make('cancel')
                        ->label('Hủy đơn hàng')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn ($record) => $record->status === 'pending')
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->update(['status' => 'cancelled']))
                        ->successNotificationTitle('Đã hủy đơn hàng'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('mark_as_expired')
                        ->label('Đánh dấu hết hạn')
                        ->icon('heroicon-o-clock')
                        ->color('gray')
                        ->action(fn ($records) => $records->each->update(['status' => 'expired']))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('10s');
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
