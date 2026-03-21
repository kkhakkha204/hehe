<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Banner';

    protected static ?string $navigationGroup = 'Nội dung';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin Banner')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Tiêu đề')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3)
                            ->maxLength(500),

                        Forms\Components\FileUpload::make('image')
                            ->label('Hình ảnh')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('banners')
                            ->required()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                            ])
                            ->helperText('Khuyến nghị: 1920x1080px (tỉ lệ 16:9)'),

                        Forms\Components\TextInput::make('button_text')
                            ->label('Text nút CTA')
                            ->placeholder('Ví dụ: Xem ngay, Tìm hiểu thêm')
                            ->maxLength(50),

                        Forms\Components\TextInput::make('link')
                            ->label('Đường dẫn')
                            ->url()
                            ->placeholder('https://...')
                            ->helperText('Link khi click vào banner hoặc nút CTA'),
                    ])->columns(2),

                Forms\Components\Section::make('Cài đặt hiển thị')
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Thứ tự')
                            ->numeric()
                            ->default(0)
                            ->helperText('Số nhỏ hiển thị trước'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Kích hoạt')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Hình ảnh')
                    ->disk('public')
                    ->size(100),

                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('button_text')
                    ->label('Nút CTA')
                    ->default('—'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Kích hoạt')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Trạng thái')
                    ->placeholder('Tất cả')
                    ->trueLabel('Đang kích hoạt')
                    ->falseLabel('Đã tắt'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
