<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Mã giảm giá';

    protected static ?string $modelLabel = 'mã giảm giá';

    protected static ?string $pluralModelLabel = 'mã giảm giá';

    protected static ?string $navigationGroup = 'Bán hàng';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Mã giảm giá')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('SUMMER2026')
                            ->helperText('Mã giảm giá sẽ tự động viết hoa')
                            ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                            ->formatStateUsing(fn ($state) => strtoupper($state)),

                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(2)
                            ->maxLength(500)
                            ->placeholder('Giảm giá mùa hè 2026'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Kích hoạt')
                            ->default(true)
                            ->helperText('Bật hoặc tắt mã giảm giá'),
                    ])->columns(1),

                Forms\Components\Section::make('Thiết lập giảm giá')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Loại giảm giá')
                            ->options([
                                'percentage' => 'Phần trăm (%)',
                                'fixed' => 'Số tiền cố định (VNĐ)',
                            ])
                            ->required()
                            ->reactive()
                            ->default('percentage'),

                        Forms\Components\TextInput::make('value')
                            ->label('Giá trị giảm')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateHydrated(fn (Forms\Components\TextInput $component, $state) => $component->state(filled($state) ? number_format((int) $state, 0, '.', ',') : null))
                            ->afterStateUpdated(fn (Forms\Set $set, $state) => $set('value', filled($state) ? number_format((int) str_replace(',', '', (string) $state), 0, '.', ',') : null))
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? (int) str_replace(',', '', (string) $state) : null)
                            ->stripCharacters(',')
                            ->minValue(0)
                            ->suffix(fn ($get) => $get('type') === 'percentage' ? '%' : 'VNĐ')
                            ->helperText(fn ($get) => $get('type') === 'percentage'
                                ? 'Nhập số từ 0-100, ví dụ 20 nghĩa là giảm 20%'
                                : 'Nhập số tiền giảm, ví dụ 100000 nghĩa là giảm 100.000đ'),

                        Forms\Components\TextInput::make('max_discount')
                            ->label('Giảm tối đa')
                            ->live(onBlur: true)
                            ->afterStateHydrated(fn (Forms\Components\TextInput $component, $state) => $component->state(filled($state) ? number_format((int) $state, 0, '.', ',') : null))
                            ->afterStateUpdated(fn (Forms\Set $set, $state) => $set('max_discount', filled($state) ? number_format((int) str_replace(',', '', (string) $state), 0, '.', ',') : null))
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? (int) str_replace(',', '', (string) $state) : null)
                            ->stripCharacters(',')
                            ->minValue(0)
                            ->suffix('VNĐ')
                            ->helperText('Chỉ áp dụng với mã giảm theo phần trăm')
                            ->visible(fn ($get) => $get('type') === 'percentage'),

                        Forms\Components\TextInput::make('min_order')
                            ->label('Đơn tối thiểu')
                            ->live(onBlur: true)
                            ->afterStateHydrated(fn (Forms\Components\TextInput $component, $state) => $component->state(filled($state) ? number_format((int) $state, 0, '.', ',') : '0'))
                            ->afterStateUpdated(fn (Forms\Set $set, $state) => $set('min_order', filled($state) ? number_format((int) str_replace(',', '', (string) $state), 0, '.', ',') : '0'))
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? (int) str_replace(',', '', (string) $state) : 0)
                            ->stripCharacters(',')
                            ->minValue(0)
                            ->default(0)
                            ->suffix('VNĐ')
                            ->helperText('Giá trị đơn hàng tối thiểu để áp dụng mã'),
                    ])->columns(2),

                Forms\Components\Section::make('Phạm vi và giới hạn')
                    ->schema([
                        Forms\Components\Select::make('scope')
                            ->label('Áp dụng cho')
                            ->options([
                                'all' => 'Tất cả khóa học',
                                'specific' => 'Khóa học cụ thể',
                            ])
                            ->required()
                            ->reactive()
                            ->default('all'),

                        Forms\Components\Select::make('courses')
                            ->label('Chọn khóa học')
                            ->multiple()
                            ->relationship('courses', 'title')
                            ->preload()
                            ->searchable()
                            ->visible(fn ($get) => $get('scope') === 'specific')
                            ->helperText('Chọn các khóa học được áp dụng mã giảm giá'),

                        Forms\Components\TextInput::make('usage_limit')
                            ->label('Giới hạn tổng lượt dùng')
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('Không giới hạn')
                            ->helperText('Để trống nếu không giới hạn tổng số lượt dùng'),

                        Forms\Components\TextInput::make('per_user_limit')
                            ->label('Giới hạn mỗi học viên')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required()
                            ->helperText('Mỗi học viên được dùng tối đa bao nhiêu lần'),
                    ])->columns(2),

                Forms\Components\Section::make('Thời gian hiệu lực')
                    ->schema([
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('Ngày bắt đầu')
                            ->nullable()
                            ->helperText('Để trống nếu muốn có hiệu lực ngay'),

                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Ngày hết hạn')
                            ->nullable()
                            ->helperText('Để trống nếu không giới hạn thời gian'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Mã')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Loại')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'percentage' => 'success',
                        'fixed' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'percentage' => 'Phần trăm',
                        'fixed' => 'Cố định',
                    }),

                Tables\Columns\TextColumn::make('value')
                    ->label('Giá trị')
                    ->formatStateUsing(fn ($record) => $record->type === 'percentage'
                        ? $record->value . '%'
                        : number_format($record->value) . '₫')
                    ->color('success')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('scope')
                    ->label('Phạm vi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'all' => 'info',
                        'specific' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'all' => 'Tất cả khóa học',
                        'specific' => 'Cụ thể',
                    }),

                Tables\Columns\TextColumn::make('usage_stats')
                    ->label('Lượt dùng')
                    ->formatStateUsing(function ($record) {
                        $used = $record->usage_count;
                        $limit = $record->usage_limit ?? '∞';

                        return "{$used} / {$limit}";
                    })
                    ->color(fn ($record) => $record->usage_limit && $record->usage_count >= $record->usage_limit ? 'danger' : 'success'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Kích hoạt')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Hết hạn')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->color(fn ($record) => $record->expires_at && $record->expires_at->isPast() ? 'danger' : 'success')
                    ->placeholder('Không hết hạn'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'percentage' => 'Phần trăm',
                        'fixed' => 'Cố định',
                    ]),

                Tables\Filters\SelectFilter::make('scope')
                    ->options([
                        'all' => 'Tất cả khóa học',
                        'specific' => 'Khóa học cụ thể',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Trạng thái kích hoạt')
                    ->boolean()
                    ->trueLabel('Chỉ đang bật')
                    ->falseLabel('Chỉ đang tắt')
                    ->native(false),

                Tables\Filters\Filter::make('expired')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('expires_at')->where('expires_at', '<', now()))
                    ->label('Đã hết hạn'),

                Tables\Filters\Filter::make('active_now')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('is_active', true)
                        ->where(function ($q) {
                            $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
                        })
                        ->where(function ($q) {
                            $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                        }))
                    ->label('Đang hoạt động'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->modalWidth('5xl'),
                    Tables\Actions\EditAction::make()
                        ->modalWidth('5xl'),
                    Tables\Actions\DeleteAction::make(),

                    Tables\Actions\Action::make('duplicate')
                        ->label('Nhân bản')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('warning')
                        ->action(function (Coupon $record) {
                            $newCoupon = $record->replicate();
                            $newCoupon->code = $record->code . '-COPY';
                            $newCoupon->usage_count = 0;
                            $newCoupon->save();

                            if ($record->scope === 'specific') {
                                $newCoupon->courses()->sync($record->courses->pluck('id'));
                            }

                            return redirect()->route('filament.admin.resources.coupons.edit', $newCoupon);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Nhân bản mã giảm giá')
                        ->modalDescription('Tạo bản sao của mã này với hậu tố "-COPY"'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('activate')
                        ->label('Kích hoạt')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Tắt kích hoạt')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
