<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComboResource\Pages;
use App\Models\Combo;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ComboResource extends Resource
{
    protected static ?string $model = Combo::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Combo';

    protected static ?string $modelLabel = 'combo';

    protected static ?string $pluralModelLabel = 'combo';

    protected static ?string $navigationGroup = 'Nội dung';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(12)
                    ->schema([
                        Forms\Components\Group::make([
                            Forms\Components\Section::make('Thông tin chung')
                                ->schema([
                                    Forms\Components\TextInput::make('title')
                                        ->label('Tên combo')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (Forms\Set $set, ?string $state): void {
                                            if ($state) {
                                                $set('slug', Str::slug($state));
                                            }
                                        }),

                                    Forms\Components\TextInput::make('slug')
                                        ->label('Slug')
                                        ->required()
                                        ->unique(ignoreRecord: true),

                                    Forms\Components\Textarea::make('description')
                                        ->label('Mô tả ngắn')
                                        ->rows(3)
                                        ->columnSpanFull(),
                                ])
                                ->columns(2),

                            Forms\Components\Section::make('Khóa học trong combo')
                                ->schema([
                                    Forms\Components\Select::make('courses')
                                        ->label('Chọn khóa học')
                                        ->relationship('courses', 'title')
                                        ->multiple()
                                        ->preload()
                                        ->searchable()
                                        ->helperText('Chọn các khóa học sẽ hiển thị trong combo')
                                        ->columnSpanFull(),
                                ]),

                            Forms\Components\Section::make('Giá bán')
                                ->schema([
                                    Forms\Components\TextInput::make('price')
                                        ->label('Giá gốc (VNĐ)')
                                        ->default(0)
                                        ->afterStateHydrated(fn (Forms\Components\TextInput $component, $state) => $component->state(filled($state) ? number_format((int) $state, 0, '.', ',') : '0'))
                                        ->extraInputAttributes(['x-on:input' => 'const digits = $el.value.replace(/\D/g, ""); $el.value = digits ? Number(digits).toLocaleString("en-US") : "";'])
                                        ->dehydrateStateUsing(fn ($state) => filled($state) ? (int) str_replace(',', '', (string) $state) : 0)
                                        ->stripCharacters(',')
                                        ->required(),

                                    Forms\Components\TextInput::make('sale_price')
                                        ->label('Giá khuyến mãi (VNĐ)')
                                        ->afterStateHydrated(fn (Forms\Components\TextInput $component, $state) => $component->state(filled($state) ? number_format((int) $state, 0, '.', ',') : null))
                                        ->extraInputAttributes(['x-on:input' => 'const digits = $el.value.replace(/\D/g, ""); $el.value = digits ? Number(digits).toLocaleString("en-US") : "";'])
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
                                ])
                                ->columns(2),
                        ])->columnSpan(8),

                        Forms\Components\Group::make([
                            Forms\Components\Section::make('Ảnh Combo')
                                ->schema([
                                    Forms\Components\FileUpload::make('thumbnail')
                                        ->hiddenLabel()
                                        ->image()
                                        ->disk('public')
                                        ->visibility('public')
                                        ->directory('combos')
                                        ->imageEditor()
                                        ->imageEditorAspectRatios(['16:9'])
                                        ->helperText('Khuyến nghị: 1280x720px')
                                        ->columnSpanFull(),
                                ]),

                            Forms\Components\Section::make('Cài đặt hiển thị')
                                ->schema([
                                    Forms\Components\TextInput::make('sort_order')
                                        ->label('Thứ tự sắp xếp')
                                        ->numeric()
                                        ->default(0),

                                    Forms\Components\Toggle::make('is_active')
                                        ->label('Kích hoạt hiển thị')
                                        ->default(true),
                                ]),
                        ])->columnSpan(4),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Ảnh')
                    ->disk('public')
                    ->size(80),

                Tables\Columns\TextColumn::make('title')
                    ->label('Tên combo')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('courses_count')
                    ->label('Số khóa')
                    ->counts('courses')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Giá gốc')
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0, ',', '.') . 'đ')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sale_price')
                    ->label('Giá sale')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 0, ',', '.') . 'đ' : '—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Hiển thị'),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('5xl'),
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
            'index' => Pages\ListCombos::route('/'),
            'create' => Pages\CreateCombo::route('/create'),
            'edit' => Pages\EditCombo::route('/{record}/edit'),
        ];
    }
}
