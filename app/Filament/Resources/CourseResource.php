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

    public static function applySmartAdd(Forms\Components\Repeater $repeater): Forms\Components\Repeater
    {
        return $repeater
            ->addAction(fn (\Filament\Forms\Components\Actions\Action $action) => $action->hidden(fn ($state) => count($state ?? []) > 0))
            ->extraItemActions([
                \Filament\Forms\Components\Actions\Action::make('add_item')
                    ->icon('heroicon-m-plus')
                    ->tooltip('Thêm mục mới')
                    ->action(function ($component, array $arguments) {
                        $items = $component->getState() ?? [];
                        $newItems = [];
                        $newUuid = (string) Str::uuid();
                        foreach ($items as $uuid => $itemData) {
                            $newItems[$uuid] = $itemData;
                            if ($uuid === $arguments['item']) {
                                $newItems[$newUuid] = [];
                            }
                        }
                        $component->state($newItems);
                    })
            ]);
    }

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
                                                    ->extraAttributes(['style' => 'max-height: 300px; overflow-y: auto; resize: vertical;'])
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

                                                Forms\Components\Grid::make(4)
                                                    ->schema([
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

                                                        Forms\Components\Toggle::make('is_published')
                                                            ->label('Xuất bản')
                                                            ->default(false)
                                                            ->inline(false),

                                                        Forms\Components\Toggle::make('is_featured')
                                                            ->label('Nổi bật')
                                                            ->default(false)
                                                            ->inline(false),

                                                        Forms\Components\Toggle::make('landing_enabled')
                                                            ->label('Bat landing custom')
                                                            ->default(false)
                                                            ->inline(false),
                                                    ])
                                                    ->columnSpanFull(),
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
                                self::applySmartAdd(
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

                                        Forms\Components\Tabs::make('Nội dung chương')->tabs([
                                            Forms\Components\Tabs\Tab::make('Bài học')->schema([
                                                self::applySmartAdd(
                                                    Forms\Components\Repeater::make('lessons')
                                                        ->relationship()
                                            ->schema([
                                                Forms\Components\Grid::make(3)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('title')
                                                            ->label('Tên bài học')
                                                            ->required()
                                                            ->columnSpan(2),

                                                        Forms\Components\TextInput::make('duration')
                                                            ->label('Thời lượng')
                                                            ->numeric()
                                                            ->default(0)
                                                            ->suffix('phút')
                                                            ->columnSpan(1),

                                                        Forms\Components\FileUpload::make('thumbnail')
                                                            ->label('Thumbnail bài học')
                                                            ->image()
                                                            ->disk('public')
                                                            ->directory('lessons/thumbnails')
                                                            ->imageEditor()
                                                            ->imageEditorAspectRatios(['16:9'])
                                                            ->maxSize(2048)
                                                            ->columnSpan(1),

                                                        Forms\Components\Toggle::make('is_preview')
                                                            ->label('Cho phép học thử')
                                                            ->default(false)
                                                            ->inline(false)
                                                            ->columnSpan(2),

                                                        Forms\Components\Textarea::make('embed_code')
                                                            ->label('Mã nhúng Bunny.net')
                                                            ->rows(2)
                                                            ->placeholder('<iframe src="https://iframe.mediadelivery.net/embed/..." ...></iframe>')
                                                            ->columnSpan(3),

                                                        Forms\Components\RichEditor::make('content')
                                                            ->label('Nội dung bài học')
                                                            ->toolbarButtons([
                                                                'bold', 'italic', 'bulletList', 'orderedList', 'link',
                                                            ])
                                                            ->extraAttributes(['style' => 'max-height: 200px; overflow-y: auto; resize: vertical;'])
                                                            ->columnSpan(3),
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
                                            ->columnSpanFull()
                                        ),
                                            ]),

                                            Forms\Components\Tabs\Tab::make('Bài kiểm tra')->schema([
                                                self::applySmartAdd(
                                                    Forms\Components\Repeater::make('quizzes')
                                                        ->relationship()
                                            ->schema([
                                                Forms\Components\Grid::make(4)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('title')
                                                            ->label('Tên bài kiểm tra')
                                                            ->required()
                                                            ->columnSpan(2),
                                                            
                                                        Forms\Components\TextInput::make('time_limit_minutes')
                                                            ->label('Thời gian (phút)')
                                                            ->numeric()
                                                            ->placeholder('Không giới hạn')
                                                            ->columnSpan(1),
                                                            
                                                        Forms\Components\TextInput::make('pass_score')
                                                            ->label('Điểm qua bài (%)')
                                                            ->numeric()
                                                            ->default(50)
                                                            ->required()
                                                            ->columnSpan(1),

                                                        Forms\Components\Textarea::make('description')
                                                            ->label('Mô tả (tùy chọn)')
                                                            ->rows(1)
                                                            ->columnSpan(4),
                                                    ]),
                                                    
                                                Forms\Components\Actions::make([
                                                    Forms\Components\Actions\Action::make('download_template')
                                                        ->label('Tải file mẫu Excel')
                                                        ->icon('heroicon-o-document-arrow-down')
                                                        ->color('success')
                                                        ->action(function () {
                                                            $export = new class implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
                                                                public function collection()
                                                                {
                                                                    return collect([
                                                                        ['Thủ đô của VN là?', '0', 'Hà Nội', '1', 'HCM', '0', 'Đà Nẵng', '0', 'Huế', '0'],
                                                                        ['Màu nào là màu cơ bản?', '1', 'Đỏ', '1', 'Trắng', '0', 'Xanh lam', '1', 'Vàng', '1']
                                                                    ]);
                                                                }
                                                                public function headings(): array
                                                                {
                                                                    return ['Nội dung câu hỏi', 'Nhiều đáp án (1=Có, 0=Không)', 'Đáp án 1', 'Đúng? (1/0)', 'Đáp án 2', 'Đúng? (1/0)', 'Đáp án 3', 'Đúng? (1/0)', 'Đáp án 4', 'Đúng? (1/0)', '...', '...'];
                                                                }
                                                            };
                                                            return \Maatwebsite\Excel\Facades\Excel::download($export, 'mau_import_cau_hoi.xlsx');
                                                        }),
                                                        
                                                    Forms\Components\Actions\Action::make('import_questions')
                                                        ->label('Import từ Excel (XLSX)')
                                                        ->icon('heroicon-o-arrow-up-tray')
                                                        ->color('primary')
                                                        ->form([
                                                            Forms\Components\FileUpload::make('file')
                                                                ->label('File Excel')
                                                                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'text/csv'])
                                                                ->disk('local')
                                                                ->required(),
                                                        ])
                                                        ->action(function (array $data, \Filament\Forms\Set $set, \Filament\Forms\Get $get) {
                                                            $file = storage_path('app/private/' . $data['file']);
                                                            $collection = \Maatwebsite\Excel\Facades\Excel::toCollection(new \stdClass, $file)->first();
                                                            
                                                            $collection->shift(); // Bỏ qua dòng tiêu đề
                                                            
                                                            $state = $get('questions') ?? [];
                                                            
                                                            foreach ($collection as $row) {
                                                                if (!isset($row[0]) || empty($row[0])) continue;
                                                                
                                                                $question = [
                                                                    'title' => $row[0],
                                                                    'type' => (isset($row[1]) && $row[1] == 1) ? 'multiple' : 'single',
                                                                    'answers' => [],
                                                                ];
                                                                
                                                                for ($i = 2; $i < count($row); $i += 2) {
                                                                    if (isset($row[$i]) && $row[$i] !== null && $row[$i] !== '') {
                                                                        $question['answers'][(string) Str::uuid()] = [
                                                                            'text' => (string)$row[$i],
                                                                            'is_correct' => (isset($row[$i+1]) && $row[$i+1] == 1) ? true : false,
                                                                        ];
                                                                    }
                                                                }
                                                                $state[(string) Str::uuid()] = $question;
                                                            }
                                                            $set('questions', $state);
                                                        })
                                                ]),

                                                self::applySmartAdd(
                                                    Forms\Components\Repeater::make('questions')
                                                        ->relationship()
                                                        ->extraAttributes(['class' => 'question-repeater'])
                                                    ->schema([
                                                        Forms\Components\Placeholder::make('css')
                                                            ->hiddenLabel()
                                                            ->content(new \Illuminate\Support\HtmlString('
                                                                <style>
                                                                    .question-repeater { counter-reset: q_idx; }
                                                                    .question-repeater li { counter-increment: q_idx; }
                                                                    .question-repeater .answer-repeater { counter-reset: a_idx; }
                                                                    .question-repeater .answer-repeater li { counter-increment: a_idx 1 q_idx 0; }
                                                                    
                                                                    .question-repeater .fi-rep-item-header-title { font-size: 0 !important; line-height: 0; }
                                                                    .question-repeater .fi-rep-item-header-title > * { display: none !important; }
                                                                    .question-repeater .fi-rep-item-header-title::before { content: "Câu " counter(q_idx); font-size: 14px; font-weight: 600; color: inherit; line-height: normal; }
                                                                    
                                                                    .answer-repeater .fi-rep-item-header-title { font-size: 0 !important; line-height: 0; }
                                                                    .answer-repeater .fi-rep-item-header-title > * { display: none !important; }
                                                                    .answer-repeater .fi-rep-item-header-title::before { content: "Đáp án " counter(a_idx, upper-alpha) !important; font-size: 14px !important; color: #d97706; line-height: normal; }
                                                                </style>
                                                            ')),
                                                        Forms\Components\Grid::make(3)
                                                            ->schema([
                                                                Forms\Components\TextInput::make('title')
                                                                    ->label('Nội dung câu hỏi')
                                                                    ->required()
                                                                    ->columnSpan(2),
                                                                    
                                                                Forms\Components\Select::make('type')
                                                                    ->label('Loại câu hỏi')
                                                                    ->options([
                                                                        'single' => '1 đáp án đúng',
                                                                        'multiple' => 'Nhiều đáp án đúng',
                                                                    ])
                                                                    ->default('single')
                                                                    ->selectablePlaceholder(false)
                                                                    ->required()
                                                                    ->live()
                                                                    ->afterStateUpdated(function ($state, $component, $livewire) {
                                                                        if ($state === 'single') {
                                                                            $pathParts = explode('.', $component->getStatePath());
                                                                            array_pop($pathParts); // type
                                                                            $questionPath = implode('.', $pathParts);
                                                                            
                                                                            $answers = data_get($livewire, "{$questionPath}.answers", []);
                                                                            $foundFirst = false;
                                                                            foreach ($answers as $key => $answer) {
                                                                                if (!empty($answer['is_correct'])) {
                                                                                    if (!$foundFirst) {
                                                                                        $foundFirst = true;
                                                                                    } else {
                                                                                        data_set($livewire, "{$questionPath}.answers.{$key}.is_correct", false);
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    })
                                                                    ->columnSpan(1),
                                                            ]),
                                                            
                                                        self::applySmartAdd(
                                                            Forms\Components\Repeater::make('answers')
                                                                ->label('Các tùy chọn đáp án')
                                                            ->extraAttributes(['class' => 'answer-repeater'])
                                                            ->schema([
                                                                Forms\Components\TextInput::make('text')
                                                                    ->hiddenLabel()
                                                                    ->placeholder('Nhập nội dung...')
                                                                    ->required()
                                                                    ->columnSpan(3),
                                                                Forms\Components\Toggle::make('is_correct')
                                                                    ->hiddenLabel()
                                                                    ->live()
                                                                    ->afterStateUpdated(function ($state, $component, $livewire) {
                                                                        if ($state) {
                                                                            $pathParts = explode('.', $component->getStatePath());
                                                                            array_pop($pathParts); // is_correct
                                                                            $currentUuid = array_pop($pathParts); // item uuid
                                                                            array_pop($pathParts); // answers
                                                                            
                                                                            $questionPath = implode('.', $pathParts); // data...questions.uid
                                                                            $type = data_get($livewire, "{$questionPath}.type", 'single');
                                                                            
                                                                            if ($type === 'single') {
                                                                                $answers = data_get($livewire, "{$questionPath}.answers", []);
                                                                                foreach ($answers as $key => $answer) {
                                                                                    if ($key !== $currentUuid && !empty($answer['is_correct'])) {
                                                                                        data_set($livewire, "{$questionPath}.answers.{$key}.is_correct", false);
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    })
                                                                    ->columnSpan(1),
                                                            ])
                                                            ->itemLabel('Đáp án')
                                                            ->columns(4)
                                                            ->grid(2)
                                                            ->defaultItems(2)
                                                            ->addActionLabel('Thêm đáp án')
                                                            ->rule(function (\Filament\Forms\Get $get) {
                                                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                                                    $correctCount = collect($value)->where('is_correct', true)->count();
                                                                    if ($get('type') === 'single' && $correctCount !== 1) {
                                                                        $fail('Cần có một và CHỈ MỘT đáp án được bật xanh cho loại câu hỏi này.');
                                                                    } elseif ($get('type') === 'multiple' && $correctCount < 1) {
                                                                        $fail('Cần có ÍT NHẤT một đáp án được bật xanh cho loại câu hỏi này.');
                                                                    }
                                                                };
                                                            })
                                                            ->columnSpanFull()
                                                        ),
                                                    ])
                                                    ->itemLabel('Câu hỏi')
                                                    ->collapsed()
                                                    ->cloneable()
                                                    ->orderColumn('sort_order')
                                                    ->reorderableWithButtons()
                                                    ->collapsible()
                                                    ->defaultItems(0)
                                                    ->addActionLabel('Thêm câu hỏi')
                                                    ->columnSpanFull()
                                                ),
                                            ])
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Bài kiểm tra mới')
                                            ->collapsed()
                                            ->cloneable()
                                            ->orderColumn('sort_order')
                                            ->reorderableWithButtons()
                                            ->collapsible()
                                            ->defaultItems(0)
                                            ->addActionLabel('Thêm bài kiểm tra')
                                            ->columnSpanFull()
                                        ),
                                            ]),
                                        ])->columnSpanFull(),
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
                                    ->grid(1)
                                ),
                            ]),

                        Forms\Components\Tabs\Tab::make('Kết quả kiểm tra')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                Forms\Components\ViewField::make('quiz_results_tab')
                                    ->label('')
                                    ->view('admin.courses.quiz-results')
                                    ->columnSpanFull(),
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

                Tables\Columns\IconColumn::make('landing_enabled')
                    ->label('Landing')
                    ->boolean()
                    ->trueColor('primary')
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

                Tables\Filters\TernaryFilter::make('landing_enabled')
                    ->label('Landing custom'),
            ])
            ->actions([
                Tables\Actions\Action::make('landing_builder')
                    ->label('Landing')
                    ->icon('heroicon-o-window')
                    ->url(fn (Course $record): string => route('admin.courses.landing.edit', $record))
                    ->openUrlInNewTab(),
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
