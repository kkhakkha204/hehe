<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use App\Models\Course;
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

    protected static ?string $navigationLabel = 'Coupons';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Coupon Code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('SUMMER2026')
                            ->helperText('Mã giảm giá (tự động viết hoa)')
                            ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                            ->formatStateUsing(fn ($state) => strtoupper($state)),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(2)
                            ->maxLength(500)
                            ->placeholder('Giảm giá mùa hè 2026'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Bật/tắt mã giảm giá'),
                    ])->columns(1),

                Forms\Components\Section::make('Discount Settings')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Discount Type')
                            ->options([
                                'percentage' => 'Percentage (%)',
                                'fixed' => 'Fixed Amount (VNĐ)',
                            ])
                            ->required()
                            ->reactive()
                            ->default('percentage'),

                        Forms\Components\TextInput::make('value')
                            ->label('Discount Value')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->suffix(fn ($get) => $get('type') === 'percentage' ? '%' : 'VNĐ')
                            ->helperText(fn ($get) => $get('type') === 'percentage'
                                ? 'Nhập số từ 0-100 (VD: 20 = giảm 20%)'
                                : 'Nhập số tiền giảm (VD: 100000 = giảm 100.000đ)'
                            ),

                        Forms\Components\TextInput::make('max_discount')
                            ->label('Max Discount Amount')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('VNĐ')
                            ->helperText('Giảm tối đa (chỉ áp dụng cho Percentage)')
                            ->visible(fn ($get) => $get('type') === 'percentage'),

                        Forms\Components\TextInput::make('min_order')
                            ->label('Minimum Order Amount')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->suffix('VNĐ')
                            ->helperText('Đơn hàng tối thiểu để áp dụng mã'),
                    ])->columns(2),

                Forms\Components\Section::make('Scope & Limits')
                    ->schema([
                        Forms\Components\Select::make('scope')
                            ->label('Apply To')
                            ->options([
                                'all' => 'All Courses',
                                'specific' => 'Specific Courses',
                            ])
                            ->required()
                            ->reactive()
                            ->default('all'),

                        Forms\Components\Select::make('courses')
                            ->label('Select Courses')
                            ->multiple()
                            ->relationship('courses', 'title')
                            ->preload()
                            ->searchable()
                            ->visible(fn ($get) => $get('scope') === 'specific')
                            ->helperText('Chọn các khóa học được áp dụng mã giảm giá'),

                        Forms\Components\TextInput::make('usage_limit')
                            ->label('Total Usage Limit')
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('Unlimited')
                            ->helperText('Tổng số lần mã có thể được sử dụng (để trống = không giới hạn)'),

                        Forms\Components\TextInput::make('per_user_limit')
                            ->label('Per User Limit')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required()
                            ->helperText('Mỗi user được dùng tối đa bao nhiêu lần'),
                    ])->columns(2),

                Forms\Components\Section::make('Validity Period')
                    ->schema([
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('Start Date')
                            ->nullable()
                            ->helperText('Để trống = có hiệu lực ngay'),

                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Expiry Date')
                            ->nullable()
                            ->helperText('Để trống = không hết hạn'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'percentage' => 'success',
                        'fixed' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'percentage' => 'Percentage',
                        'fixed' => 'Fixed',
                    }),

                Tables\Columns\TextColumn::make('value')
                    ->label('Value')
                    ->formatStateUsing(fn ($record) =>
                    $record->type === 'percentage'
                        ? $record->value . '%'
                        : number_format($record->value) . '₫'
                    )
                    ->color('success')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('scope')
                    ->label('Scope')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'all' => 'info',
                        'specific' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'all' => 'All Courses',
                        'specific' => 'Specific',
                    }),

                Tables\Columns\TextColumn::make('usage_stats')
                    ->label('Usage')
                    ->formatStateUsing(function ($record) {
                        $used = $record->usage_count;
                        $limit = $record->usage_limit ?? '∞';
                        return "{$used} / {$limit}";
                    })
                    ->color(fn ($record) =>
                    $record->usage_limit && $record->usage_count >= $record->usage_limit
                        ? 'danger'
                        : 'success'
                    ),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->color(fn ($record) =>
                    $record->expires_at && $record->expires_at->isPast()
                        ? 'danger'
                        : 'success'
                    )
                    ->placeholder('No expiry'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'percentage' => 'Percentage',
                        'fixed' => 'Fixed',
                    ]),

                Tables\Filters\SelectFilter::make('scope')
                    ->options([
                        'all' => 'All Courses',
                        'specific' => 'Specific Courses',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->native(false),

                Tables\Filters\Filter::make('expired')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('expires_at')->where('expires_at', '<', now()))
                    ->label('Expired'),

                Tables\Filters\Filter::make('active_now')
                    ->query(fn (Builder $query): Builder =>
                    $query->where('is_active', true)
                        ->where(function($q) {
                            $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
                        })
                        ->where(function($q) {
                            $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                        })
                    )
                    ->label('Currently Active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),

                    Tables\Actions\Action::make('duplicate')
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
                        ->modalHeading('Duplicate Coupon')
                        ->modalDescription('Create a copy of this coupon with "-COPY" suffix'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate')
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
