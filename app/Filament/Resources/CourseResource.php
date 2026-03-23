<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Khóa học';

    protected static ?string $modelLabel = 'khóa học';

    protected static ?string $pluralModelLabel = 'khóa học';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Thông tin khóa học')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Thông tin')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Grid::make(12)
                                    ->schema([
                                        Forms\Components\Section::make('Thông tin cơ bản')
                                            ->schema([
                                                Forms\Components\TextInput::make('title')
                                                    ->label('Tên khóa học')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                                        if (! $get('slug')) {
                                                            $set('slug', Str::slug($state));
                                                        }

                                                        if (! $get('seo_title')) {
                                                            $set('seo_title', $state);
                                                        }
                                                    })
                                                    ->columnSpanFull(),

                                                Forms\Components\RichEditor::make('description')
                                                    ->label('Mô tả chi tiết')
                                                    ->toolbarButtons([
                                                        'bold',
                                                        'italic',
                                                        'underline',
                                                        'bulletList',
                                                        'orderedList',
                                                        'link',
                                                        'h2',
                                                        'h3',
                                                        'blockquote',
                                                    ])
                                                    ->columnSpanFull(),

                                                Forms\Components\Select::make('category_id')
                                                    ->label('Danh mục')
                                                    ->relationship('category', 'name')
                                                    ->preload()
                                                    ->searchable()
                                                    ->required()
                                                    ->createOptionForm([
                                                        Forms\Components\TextInput::make('name')->required(),
                                                    ]),

                                                Forms\Components\Select::make('author_id')
                                                    ->label('Giảng viên')
                                                    ->relationship('author', 'name')
                                                    ->preload()
                                                    ->searchable()
                                                    ->required()
                                                    ->createOptionForm([
                                                        Forms\Components\TextInput::make('name')->required(),
                                                    ]),

                                                Forms\Components\Select::make('level')
                                                    ->label('Cấp độ khóa học')
                                                    ->required()
                                                    ->options([
                                                        1 => 'Level 1',
                                                        2 => 'Level 2',
                                                        3 => 'Level 3',
                                                    ])
                                                    ->default(1)
                                                    ->native(false),

                                                Forms\Components\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\Toggle::make('is_published')
                                                            ->label('Xuất bản')
                                                            ->default(false),

                                                        Forms\Components\Toggle::make('is_featured')
                                                            ->label('Nổi bật')
                                                            ->default(false),
                                                    ])
                                                    ->columnSpan(1)
                                                    ->extraAttributes(['style' => 'margin-top: 36px;']),
                                            ])
                                            ->columns(2)
                                            ->columnSpan(8),

                                        Forms\Components\Group::make([
                                            Forms\Components\Section::make('Giá bán')
                                                ->schema([
                                                    Forms\Components\TextInput::make('price')
                                                        ->label('Giá gốc (VNĐ)')
                                                        ->required()
                                                        ->prefix('₫')
                                                        ->default(0)
                                                        ->extraInputAttributes(['x-on:input' => 'const digits = $el.value.replace(/\\D/g, ""); $el.value = digits ? Number(digits).toLocaleString("en-US") : "";'])
                                                        ->afterStateHydrated(fn (Forms\Components\TextInput $component, $state) => $component->state(filled($state) ? number_format((int) $state, 0, '.', ',') : '0'))
                                                        ->dehydrateStateUsing(fn ($state) => filled($state) ? (int) str_replace(',', '', (string) $state) : 0)
                                                        ->stripCharacters(','),

                                                    Forms\Components\TextInput::make('sale_price')
                                                        ->label('Giá khuyến mãi (VNĐ)')
                                                        ->prefix('₫')
                                                        ->extraInputAttributes(['x-on:input' => 'const digits = $el.value.replace(/\\D/g, ""); $el.value = digits ? Number(digits).toLocaleString("en-US") : "";'])
                                                        ->afterStateHydrated(fn (Forms\Components\TextInput $component, $state) => $component->state(filled($state) ? number_format((int) $state, 0, '.', ',') : null))
                                                        ->dehydrateStateUsing(fn ($state) => filled($state) ? (int) str_replace(',', '', (string) $state) : null)
                                                        ->stripCharacters(',')
                                                        ->rule(function (Get $get) {
                                                            return function (string $attribute, $value, Closure $fail) use ($get): void {
                                                                if (! filled($value)) {
                                                                    return;
                                                                }

                                                                $salePrice = (int) str_replace(',', '', (string) $value);
                                                                $price = (int) str_replace(',', '', (string) $get('price'));

                                                                if ($salePrice > $price) {
                                                                    $fail('Giá khuyến mãi không được lớn hơn giá gốc.');
                                                                }
                                                            };
                                                        }),

                                                    Forms\Components\TextInput::make('duration')
                                                        ->label('Thời lượng khóa học')
                                                        ->numeric()
                                                        ->default(0)
                                                        ->suffix('phút'),
                                                ])
                                                ->columns(1),

                                            Forms\Components\Section::make('Hình ảnh')
                                                ->schema([
                                                    Forms\Components\FileUpload::make('thumbnail')
                                                        ->label('Ảnh thumbnail')
                                                        ->image()
                                                        ->disk('public')
                                                        ->directory('courses/thumbnails')
                                                        ->visibility('public')
                                                        ->imageEditor()
                                                        ->imageEditorAspectRatios(['16:9'])
                                                        ->maxSize(3072),
                                                ])
                                                ->collapsible()
                                                ->collapsed(),
                                        ])->columnSpan(4),

                                        Forms\Components\Section::make('SEO')
                                            ->schema([
                                                Forms\Components\TextInput::make('seo_title')
                                                    ->label('Tiêu đề SEO')
                                                    ->maxLength(60),

                                                Forms\Components\Textarea::make('seo_description')
                                                    ->label('Mô tả SEO')
                                                    ->maxLength(160)
                                                    ->rows(3),
                                            ])
                                            ->collapsible()
                                            ->collapsed()
                                            ->columnSpan(8),

                                        Forms\Components\Section::make('Thống kê')
                                            ->schema([
                                                Forms\Components\TextInput::make('current_students')
                                                    ->label('Số học viên hiện tại')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->disabled()
                                                    ->dehydrated(),

                                                Forms\Components\TextInput::make('views')
                                                    ->label('Lượt xem')
                                                    ->numeric()
                                                    ->default(0),
                                            ])
                                            ->columns(2)
                                            ->collapsible()
                                            ->collapsed()
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Giáo trình')
                            ->icon('heroicon-o-book-open')
                            ->schema([
                                Forms\Components\Repeater::make('chapters')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Tên chương')
                                            ->required()
                                            ->columnSpanFull(),

                                        Forms\Components\Textarea::make('description')
                                            ->label('Mô tả chương')
                                            ->rows(2)
                                            ->columnSpanFull(),

                                        Forms\Components\Repeater::make('lessons')
                                            ->relationship()
                                            ->schema([
                                                Forms\Components\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('title')
                                                            ->label('Tên bài học')
                                                            ->required()
                                                            ->columnSpan(2),

                                                        Forms\Components\FileUpload::make('thumbnail')
                                                            ->label('Thumbnail bài học')
                                                            ->image()
                                                            ->disk('public')
                                                            ->directory('lessons/thumbnails')
                                                            ->imageEditor()
                                                            ->imageEditorAspectRatios(['16:9'])
                                                            ->maxSize(2048)
                                                            ->columnSpan(1),

                                                        Forms\Components\TextInput::make('duration')
                                                            ->label('Thời lượng')
                                                            ->numeric()
                                                            ->default(0)
                                                            ->suffix('phút')
                                                            ->columnSpan(1),

                                                        Forms\Components\Textarea::make('embed_code')
                                                            ->label('Mã nhúng Bunny.net')
                                                            ->rows(4)
                                                            ->placeholder('<iframe src="https://iframe.mediadelivery.net/embed/..." ...></iframe>')
                                                            ->helperText('Dán mã iframe từ Bunny.net')
                                                            ->columnSpan(2),

                                                        Forms\Components\RichEditor::make('content')
                                                            ->label('Nội dung bài học')
                                                            ->toolbarButtons([
                                                                'bold',
                                                                'italic',
                                                                'bulletList',
                                                                'orderedList',
                                                                'link',
                                                            ])
                                                            ->columnSpan(2),

                                                        Forms\Components\Toggle::make('is_preview')
                                                            ->label('Cho phép xem thử')
                                                            ->default(false)
                                                            ->inline(false)
                                                            ->columnSpan(2),
                                                    ]),
                                            ])
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Bài học mới')
                                            ->collapsed()
                                            ->cloneable()
                                            ->orderColumn('sort_order')
                                            ->reorderableWithButtons()
                                            ->collapsible()
                                            ->defaultItems(0)
                                            ->addActionLabel('Thêm bài học')
                                            ->columnSpanFull(),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Chương mới')
                                    ->collapsed()
                                    ->cloneable()
                                    ->orderColumn('sort_order')
                                    ->reorderableWithButtons()
                                    ->collapsible()
                                    ->defaultItems(0)
                                    ->addActionLabel('Thêm chương')
                                    ->columnSpanFull()
                                    ->grid(1),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Ảnh')
                    ->size(80),

                Tables\Columns\TextColumn::make('title')
                    ->label('Tên khóa học')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Giảng viên')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('level')
                    ->label('Cấp độ')
                    ->formatStateUsing(fn (?int $state): string => 'Level ' . ($state ?? 1))
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Giá')
                    ->money('VND', locale: 'vi')
                    ->sortable(),

                Tables\Columns\TextColumn::make('current_students')
                    ->label('Học viên')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Xuất bản')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Nổi bật')
                    ->boolean()
                    ->trueColor('warning')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Danh mục')
                    ->relationship('category', 'name')
                    ->preload(),

                Tables\Filters\SelectFilter::make('author_id')
                    ->label('Giảng viên')
                    ->relationship('author', 'name')
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Xuất bản')
                    ->placeholder('Tất cả')
                    ->trueLabel('Đã xuất bản')
                    ->falseLabel('Bản nháp'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Nổi bật'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('7xl'),
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
