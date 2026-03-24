<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\PhoneOtp;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Người dùng';

    protected static ?string $modelLabel = 'người dùng';

    protected static ?string $pluralModelLabel = 'người dùng';

    protected static ?string $navigationGroup = 'Quản lý người dùng';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin người dùng')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Họ và tên')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nguyễn Văn A'),

                        Forms\Components\TextInput::make('username')
                            ->label('Tên đăng nhập')
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
                            ->label('Số điện thoại')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('0123456789'),
                    ])->columns(2),

                Forms\Components\Section::make('Cài đặt tài khoản')
                    ->schema([
                        Forms\Components\Select::make('role')
                            ->label('Vai trò')
                            ->options([
                                'admin' => 'Quản trị viên',
                                'student' => 'Học viên',
                            ])
                            ->required()
                            ->default('student')
                            ->native(false),

                        Forms\Components\TextInput::make('password')
                            ->label('Mật khẩu')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->placeholder('Nhập mật khẩu')
                            ->helperText(fn (string $context): string => $context === 'create'
                                ? 'Mật khẩu là bắt buộc khi tạo người dùng mới'
                                : 'Để trống nếu muốn giữ mật khẩu hiện tại'),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Xác nhận mật khẩu')
                            ->password()
                            ->dehydrated(false)
                            ->required(fn (string $context): bool => $context === 'create')
                            ->same('password')
                            ->placeholder('Nhập lại mật khẩu'),
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
                    ->label('Tên')
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
                    ->label('Điện thoại')
                    ->searchable()
                    ->placeholder('—')
                    ->copyable()
                    ->icon('heroicon-o-phone'),

                Tables\Columns\TextColumn::make('role')
                    ->label('Vai trò')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'student' => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Quản trị viên',
                        'student' => 'Học viên',
                    }),

                Tables\Columns\TextColumn::make('enrollments_count')
                    ->label('Khóa học')
                    ->counts('enrollments')
                    ->sortable()
                    ->color('primary')
                    ->icon('heroicon-o-academic-cap'),

                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Đơn hàng')
                    ->counts('orders')
                    ->sortable()
                    ->color('warning')
                    ->icon('heroicon-o-shopping-cart'),

                Tables\Columns\TextColumn::make('total_spent')
                    ->label('Tổng chi tiêu')
                    ->getStateUsing(fn ($record) => $record->orders()->where('status', 'paid')->sum('final_amount'))
                    ->money('VND', locale: 'vi')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->withSum(['orders as total_spent' => fn ($query) => $query->where('status', 'paid')], 'final_amount')
                            ->orderBy('total_spent', $direction);
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tham gia')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Đã xác minh')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Quản trị viên',
                        'student' => 'Học viên',
                    ])
                    ->native(false),

                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Xác minh email')
                    ->nullable()
                    ->trueLabel('Đã xác minh')
                    ->falseLabel('Chưa xác minh')
                    ->native(false),

                Tables\Filters\Filter::make('has_enrollments')
                    ->query(fn (Builder $query): Builder => $query->has('enrollments'))
                    ->label('Có khóa học'),

                Tables\Filters\Filter::make('has_orders')
                    ->query(fn (Builder $query): Builder => $query->has('orders'))
                    ->label('Có đơn hàng'),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Từ ngày'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Đến ngày'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->modalWidth('5xl'),
                    Tables\Actions\EditAction::make()
                        ->modalWidth('5xl'),

                    Tables\Actions\Action::make('reset_otp_block')
                        ->label('Reset chặn OTP')
                        ->icon('heroicon-o-arrow-path')
                        ->color('info')
                        ->visible(fn ($record) => filled($record->phone))
                        ->requiresConfirmation()
                        ->modalHeading('Reset chặn OTP')
                        ->modalDescription('Thao tác này sẽ reset giới hạn gửi và xác thực OTP của người dùng này.')
                        ->action(function ($record): void {
                            PhoneOtp::query()
                                ->where('phone', $record->phone)
                                ->update([
                                    'sent_count' => 0,
                                    'attempts_count' => 0,
                                    'last_sent_at' => null,
                                    'verified_at' => null,
                                    'consumed_at' => null,
                                ]);

                            $phone = preg_replace('/\D+/', '', (string) $record->phone) ?? '';

                            if (str_starts_with($phone, '0') && strlen($phone) === 10) {
                                $phone = '84'.substr($phone, 1);
                            }

                            if ($phone !== '') {
                                Cache::forget('login-otp-block-until:'.sha1($phone));
                                Cache::forget('login-otp-wrong-daily:'.sha1($phone.'|'.now()->format('Y-m-d')));
                            }
                        })
                        ->successNotificationTitle('Đã reset chặn OTP'),

                    Tables\Actions\Action::make('view_enrollments')
                        ->label('Xem khóa học')
                        ->icon('heroicon-o-academic-cap')
                        ->color('success')
                        ->modalHeading(fn ($record) => 'Khóa học của ' . $record->name)
                        ->modalContent(fn ($record) => view('filament.resources.user-resource.modals.enrollments', ['user' => $record]))
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Đóng'),

                    Tables\Actions\Action::make('view_orders')
                        ->label('Xem đơn hàng')
                        ->icon('heroicon-o-shopping-cart')
                        ->color('warning')
                        ->modalHeading(fn ($record) => 'Đơn hàng của ' . $record->name)
                        ->modalContent(fn ($record) => view('filament.resources.user-resource.modals.orders', ['user' => $record]))
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Đóng'),

                    Tables\Actions\Action::make('toggle_role')
                        ->label(fn ($record) => $record->isAdmin() ? 'Hạ xuống học viên' : 'Nâng lên quản trị viên')
                        ->icon(fn ($record) => $record->isAdmin() ? 'heroicon-o-arrow-down' : 'heroicon-o-arrow-up')
                        ->color(fn ($record) => $record->isAdmin() ? 'warning' : 'success')
                        ->requiresConfirmation()
                        ->modalHeading(fn ($record) => $record->isAdmin() ? 'Hạ xuống học viên?' : 'Nâng lên quản trị viên?')
                        ->modalDescription(fn ($record) => $record->isAdmin()
                            ? 'Người dùng này sẽ mất quyền quản trị.'
                            : 'Người dùng này sẽ được cấp toàn bộ quyền quản trị.')
                        ->action(fn ($record) => $record->update([
                            'role' => $record->isAdmin() ? 'student' : 'admin',
                        ]))
                        ->successNotificationTitle('Đã cập nhật vai trò'),

                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Xóa người dùng')
                        ->modalDescription('Bạn có chắc không? Hành động này sẽ xóa cả đơn hàng và ghi danh liên quan.')
                        ->before(fn ($record) => $record->enrollments()->delete()),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('promote_to_admin')
                        ->label('Nâng lên quản trị viên')
                        ->icon('heroicon-o-arrow-up')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['role' => 'admin']))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('demote_to_student')
                        ->label('Hạ xuống học viên')
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
