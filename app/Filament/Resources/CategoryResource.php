<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel = 'Danh mục';

    protected static ?string $modelLabel = 'danh mục';

    protected static ?string $pluralModelLabel = 'danh mục';

    protected static ?int $navigationSort = 1; // Hiển thị ở vị trí đầu tiên

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->description('Thông tin cơ bản của danh mục')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên danh mục')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true) // Cập nhật khi rời khỏi ô input
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                                // Tự động tạo slug khi nhập tên
                                if (!$get('slug')) {
                                    $set('slug', Str::slug($state));
                                }
                                // Tự động điền SEO Title nếu chưa có
                                if (!$get('seo_title')) {
                                    $set('seo_title', $state);
                                }
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->label('URL (Slug)')
                            ->required()
                            ->maxLength(255)
                            ->unique(Category::class, 'slug', ignoreRecord: true)
                            ->helperText('URL thân thiện SEO (VD: lap-trinh-web). Tự động tạo từ tên.')
                            ->disabled() // Không cho sửa trực tiếp, chỉ tự động
                            ->dehydrated(), // Vẫn lưu vào database dù disabled

                        Forms\Components\Toggle::make('is_active')
                            ->label('Kích hoạt')
                            ->default(true)
                            ->helperText('Bật/tắt hiển thị danh mục trên website'),

//                        Forms\Components\TextInput::make('sort_order')
//                            ->label('Thứ tự sắp xếp')
//                            ->numeric()
//                            ->default(0)
//                            ->helperText('Số nhỏ hơn sẽ hiển thị trước'),
                    ])
                    ->columns(2), // Chia 2 cột

                Forms\Components\Section::make('Cài đặt SEO')
                    ->description('Tối ưu hóa công cụ tìm kiếm')
                    ->schema([
                        Forms\Components\TextInput::make('seo_title')
                            ->label('Tên SEO (Meta Title)')
                            ->maxLength(60)
                            ->helperText('Tối đa 60 ký tự. Hiển thị trên Google Search.')
                            ->placeholder('Tự động lấy từ "Tên danh mục" nếu để trống'),

                        Forms\Components\Textarea::make('seo_description')
                            ->label('Mô tả SEO (Meta Description)')
                            ->maxLength(160)
                            ->rows(3)
                            ->helperText('Tối đa 160 ký tự. Mô tả ngắn gọn về danh mục.')
                            ->placeholder('VD: Khám phá các khóa học Lập trình Web từ cơ bản đến nâng cao...'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên danh mục')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('URL')
                    ->searchable()
                    ->copyable() // Cho phép copy
                    ->copyMessage('Đã copy!')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Trạng thái')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

//                Tables\Columns\TextColumn::make('sort_order')
//                    ->label('Thứ tự')
//                    ->sortable()
//                    ->alignCenter(),

                Tables\Columns\TextColumn::make('courses_count')
                    ->label('Số khóa học')
                    ->counts('courses')
                    ->badge()
                    ->color('primary'),

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
                    ->trueLabel('Kích hoạt')
                    ->falseLabel('Đã ẩn'),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
