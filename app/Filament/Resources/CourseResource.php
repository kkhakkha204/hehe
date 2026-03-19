<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use App\Models\Category;
use App\Models\Author;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Get;
use Filament\Forms\Set;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Courses';

    protected static ?string $modelLabel = 'Course';

    protected static ?string $pluralModelLabel = 'Courses';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Course Details')
                    ->tabs([
                        // TAB 1: INFORMATION
                        Forms\Components\Tabs\Tab::make('Information')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Section::make('Basic Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Tên khóa học')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                                if (!$get('slug')) {
                                                    $set('slug', Str::slug($state));
                                                }
                                                if (!$get('seo_title')) {
                                                    $set('seo_title', $state);
                                                }
                                            }),

                                        Forms\Components\Select::make('level')
                                            ->label('Level khóa học')
                                            ->required()
                                            ->options([
                                                1 => 'Level 1',
                                                2 => 'Level 2',
                                                3 => 'Level 3',
                                            ])
                                            ->default(1)
                                            ->native(false),

                                        Forms\Components\Select::make('category_id')
                                            ->label('Danh mục')
                                            ->relationship('category', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->required(),
                                            ]),

                                        Forms\Components\Select::make('author_id')
                                            ->label('Giảng viên')
                                            ->relationship('author', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->required(),
                                            ]),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Media')
                                    ->schema([
                                        Forms\Components\FileUpload::make('thumbnail')
                                            ->label('Ảnh thumbnail')
                                            ->image()
                                            ->disk('public')
                                            ->directory('courses/thumbnails')
                                            ->visibility('public')
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '16:9',
                                            ])
                                            ->maxSize(3072)
                                            ->helperText('Khuyến nghị: 1280x720px (16:9). Tối đa 3MB.')
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Section::make('Pricing')
                                    ->schema([
                                        Forms\Components\TextInput::make('price')
                                            ->label('Giá gốc (VNĐ)')
                                            ->required()
                                            ->numeric()
                                            ->prefix('₫')
                                            ->default(0)
                                            ->helperText('Nhập 0 nếu khóa học miễn phí'),

                                        Forms\Components\TextInput::make('sale_price')
                                            ->label('Giá khuyến mãi (VNĐ)')
                                            ->numeric()
                                            ->prefix('₫')
                                            ->helperText('Để trống nếu không có giảm giá')
                                            ->lte('price'),

                                        Forms\Components\TextInput::make('duration')
                                            ->label('Thời lượng khóa học (phút)')
                                            ->numeric()
                                            ->default(0)
                                            ->suffix('phút'),
                                    ])
                                    ->columns(3),

                                Forms\Components\Section::make('Description')
                                    ->schema([
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
                                    ]),

                                Forms\Components\Section::make('Statistics')
                                    ->schema([
                                        Forms\Components\TextInput::make('current_students')
                                            ->label('Số học viên hiện tại')
                                            ->numeric()
                                            ->default(0)
                                            ->disabled()
                                            ->dehydrated()
                                            ->helperText('Tự động cập nhật khi có người mua'),

                                        Forms\Components\TextInput::make('views')
                                            ->label('Lượt xem')
                                            ->numeric()
                                            ->default(0)
                                            ->helperText('Có thể chỉnh sửa thủ công'),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Settings')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_published')
                                            ->label('Xuất bản')
                                            ->default(false)
                                            ->helperText('Hiển thị khóa học trên website'),

                                        Forms\Components\Toggle::make('is_featured')
                                            ->label('Nổi bật')
                                            ->default(false)
                                            ->helperText('Hiển thị ở mục khóa học nổi bật'),

//                                        Forms\Components\TextInput::make('sort_order')
//                                            ->label('Thứ tự sắp xếp')
//                                            ->numeric()
//                                            ->default(0),
                                    ])
                                    ->columns(3),

                                Forms\Components\Section::make('SEO')
                                    ->schema([
                                        Forms\Components\TextInput::make('seo_title')
                                            ->label('SEO Title')
                                            ->maxLength(60),

                                        Forms\Components\Textarea::make('seo_description')
                                            ->label('SEO Description')
                                            ->maxLength(160)
                                            ->rows(3),
                                    ])
                                    ->collapsed(),
                            ]),

                        // TAB 2: CURRICULUM (Nested Repeater)
                        Forms\Components\Tabs\Tab::make('Curriculum')
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

                                        // NESTED REPEATER - Lessons bên trong Chapter
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
                                                            ->label('Thời lượng (phút)')
                                                            ->numeric()
                                                            ->default(0)
                                                            ->suffix('phút')
                                                            ->columnSpan(1),

                                                        Forms\Components\Textarea::make('embed_code')
                                                            ->label('Bunny.net Embed Code')
                                                            ->rows(4)
                                                            ->placeholder('<iframe src="https://iframe.mediadelivery.net/embed/..." ...></iframe>')
                                                            ->helperText('Dán code <iframe> từ Bunny.net')
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
                    ->label('Thumbnail')
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
                    ->label('Level')
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
