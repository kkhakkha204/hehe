<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthorResource\Pages;
use App\Models\Author;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Giảng viên';

    protected static ?string $modelLabel = 'giảng viên';

    protected static ?string $pluralModelLabel = 'giảng viên';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->description('Thông tin cơ bản của giảng viên')
                    ->schema([
                        Forms\Components\FileUpload::make('avatar')
                            ->label('Ảnh đại diện')
                            ->image()
                            ->disk('public')
                            ->directory('authors/avatars')
                            ->visibility('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                            ])
                            ->maxSize(2048) // 2MB
                            ->helperText('Kích thước khuyến nghị: 400x400px. Tối đa 2MB.')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('name')
                            ->label('Tên giảng viên')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('VD: Nguyễn Văn A'),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('example@domain.com'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Kích hoạt')
                            ->default(true)
                            ->helperText('Hiển thị giảng viên trên website'),

//                        Forms\Components\TextInput::make('sort_order')
//                            ->label('Thứ tự sắp xếp')
//                            ->numeric()
//                            ->default(0)
//                            ->helperText('Số nhỏ hơn hiển thị trước'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Biography')
                    ->description('Giới thiệu về giảng viên')
                    ->schema([
                        Forms\Components\Textarea::make('bio')
                            ->label('Tiểu sử')
                            ->rows(4)
                            ->maxLength(1000)
                            ->placeholder('Giới thiệu ngắn về giảng viên, kinh nghiệm, chuyên môn...')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Social Links')
                    ->description('Liên kết mạng xã hội')
                    ->schema([
                        Forms\Components\TextInput::make('facebook')
                            ->label('Facebook')
                            ->url()
                            ->placeholder('https://facebook.com/username')
                            ->prefixIcon('heroicon-o-link'),

                        Forms\Components\TextInput::make('linkedin')
                            ->label('LinkedIn')
                            ->url()
                            ->placeholder('https://linkedin.com/in/username')
                            ->prefixIcon('heroicon-o-link'),

                        Forms\Components\TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->placeholder('https://yourwebsite.com')
                            ->prefixIcon('heroicon-o-globe-alt'),
                    ])
                    ->columns(3)
                    ->collapsed(), // Thu gọn mặc định
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('Avatar')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png'))
                    ->size(50),

                Tables\Columns\TextColumn::make('name')
                    ->label('Tên giảng viên')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Đã copy email!')
                    ->icon('heroicon-o-envelope')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Trạng thái')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('courses_count')
                    ->label('Số khóa học')
                    ->counts('courses')
                    ->badge()
                    ->color('primary'),

//                Tables\Columns\TextColumn::make('sort_order')
//                    ->label('Thứ tự')
//                    ->sortable()
//                    ->alignCenter()
//                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Trạng thái')
                    ->placeholder('Tất cả')
                    ->trueLabel('Đang hoạt động')
                    ->falseLabel('Đã ẩn'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListAuthors::route('/'),
            'create' => Pages\CreateAuthor::route('/create'),
            'view' => Pages\ViewAuthor::route('/{record}'),
            'edit' => Pages\EditAuthor::route('/{record}/edit'),
        ];
    }
}
