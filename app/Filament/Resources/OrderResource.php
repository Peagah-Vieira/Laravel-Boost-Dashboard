<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $navigationLabel = 'Pedidos';

    protected static ?string $modelLabel = 'Pedidos';

    protected static ?string $navigationGroup = 'Dummy Group1';

    protected static ?string $slug = 'pedidos';

    protected static ?string $recordTitleAttribute = 'Pedido';

    public static function getGloballySearchableAttributes(): array
    {
        return ['rush_id', 'rush_site', 'buyer_name', 'buyer_discord', 'buyer_battlenet'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->rush_id;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Site do Rush' => $record->rush_site,
            'Nome do Comprador' => $record->buyer_name,
            'Discord do Comprador' => $record->buyer_discord,
            'Battlenet do Comprador' => $record->buyer_battlenet,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Wizard::make([
                            Forms\Components\Wizard\Step::make('Pedido')
                                ->description('Configurações do Pedido')
                                ->completedIcon('heroicon-m-hand-thumb-up')
                                ->schema([
                                    Forms\Components\Select::make('rush_site')
                                        ->required()
                                        ->options([
                                            'Kingboost' => 'Kingboost',
                                            'Overgear' => 'Overgear',
                                            'Skycoach' => 'Skycoach',
                                        ])
                                        ->label('Site do Rush'),
                                    Forms\Components\TextInput::make('rush_id')
                                        ->required()
                                        ->label('Id do Rush'),
                                    Forms\Components\TextInput::make('rush_value')
                                        ->required()
                                        ->numeric()
                                        ->label('Valor do Rush'),
                                    Forms\Components\TextInput::make('rush_description')
                                        ->columnSpanFull()
                                        ->label('Descrição do Rush'),
                                    Forms\Components\Select::make('rush_progress')
                                        ->required()
                                        ->options([
                                            'Não iniciado' => 'Não iniciado',
                                            'Em andamento' => 'Em andamento',
                                            'Concluido' => 'Concluido',
                                        ])
                                        ->label('Progresso do Rush'),
                                    Forms\Components\FileUpload::make('rush_images')
                                        ->label('Imagens do Rush')
                                        ->image()
                                        ->multiple()
                                        ->openable()
                                ]),
                            Forms\Components\Wizard\Step::make('Detalhes - Comprador')
                                ->description('Dados do Comprador')
                                ->completedIcon('heroicon-m-hand-thumb-up')
                                ->schema([
                                    Forms\Components\TextInput::make('buyer_name')
                                        ->required()
                                        ->label('Nome do Comprador'),
                                    Forms\Components\TextInput::make('buyer_discord')
                                        ->label('Discord do Comprador'),
                                    Forms\Components\TextInput::make('buyer_battlenet')
                                        ->label('Battlenet do Comprador'),
                                ]),
                            Forms\Components\Wizard\Step::make('Detalhes - Boosters')
                                ->description('Dados dos Boosters')
                                ->completedIcon('heroicon-m-hand-thumb-up')
                                ->schema([
                                    Forms\Components\Tabs::make('Tabs')->tabs([
                                        Forms\Components\Tabs\Tab::make('Boosters Principais')
                                            ->schema([
                                                Forms\Components\Select::make('booster_id')
                                                    ->required()
                                                    ->relationship(name: 'booster', titleAttribute: 'first_name')
                                                    ->label('Booster 1'),
                                                Forms\Components\Select::make('booster2_id')
                                                    ->required()
                                                    ->relationship(name: 'booster', titleAttribute: 'first_name')
                                                    ->label('Booster 2'),
                                                Forms\Components\Select::make('booster3_id')
                                                    ->required()
                                                    ->relationship(name: 'booster', titleAttribute: 'first_name')
                                                    ->label('Booster 3'),
                                                Forms\Components\Select::make('booster4_id')
                                                    ->required()
                                                    ->relationship(name: 'booster', titleAttribute: 'first_name')
                                                    ->label('Booster 4'),
                                            ]),
                                        Forms\Components\Tabs\Tab::make('Boosters Reposição')
                                            ->schema([
                                                Forms\Components\Select::make('booster5_id')
                                                    ->relationship(name: 'booster', titleAttribute: 'first_name')
                                                    ->label('Booster 5'),
                                                Forms\Components\Select::make('booster6_id')
                                                    ->default('Select Option')
                                                    ->relationship(name: 'booster', titleAttribute: 'first_name')
                                                    ->label('Booster 6'),
                                                Forms\Components\Select::make('booster7_id')
                                                    ->relationship(name: 'booster', titleAttribute: 'first_name')
                                                    ->label('Booster 7'),
                                                Forms\Components\Select::make('booster8_id')
                                                    ->relationship(name: 'booster', titleAttribute: 'first_name')
                                                    ->label('Booster 8'),
                                                Forms\Components\Select::make('booster9_id')
                                                    ->relationship(name: 'booster', titleAttribute: 'first_name')
                                                    ->label('Booster 9'),
                                                Forms\Components\Select::make('booster10_id')
                                                    ->relationship(name: 'booster', titleAttribute: 'first_name')
                                                    ->label('Booster 10'),
                                            ]),
                                    ]),
                                ]),
                            Forms\Components\Wizard\Step::make('Pagamento')
                                ->description('Relacionado ao Pagamento')
                                ->completedIcon('heroicon-m-hand-thumb-up')
                                ->schema([
                                    Forms\Components\DateTimePicker::make('payment_date')
                                        ->label('Data do Pagamento'),
                                    Forms\Components\Toggle::make('paid')
                                        ->label('Pago'),
                                ]),
                        ])->skippable()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rush_progress')
                    ->label('Progresso do Rush')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Não iniciado' => 'gray',
                        'Em andamento' => 'primary',
                        'Concluido' => 'success',
                    }),
                Tables\Columns\TextColumn::make('rush_description')
                    ->label('Descrição do Rush')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rush_value')
                    ->label('Valor do Rush')
                    ->searchable()
                    ->sortable()
                    ->money('USD'),
                Tables\Columns\TextColumn::make('buyer_name')
                    ->label('Nome do Comprador')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('buyer_discord')
                    ->label('Discord do Comprador')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('buyer_battlenet')
                    ->label('Battlenet do Comprador')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rush_progress')
                    ->multiple()
                    ->options([
                        'Não iniciado' => 'Não iniciado',
                        'Em andamento' => 'Em andamento',
                        'Concluido' => 'Concluido',
                    ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()->exports([
                        ExcelExport::make('table')->fromTable(),
                        ExcelExport::make('form')->fromForm(),
                    ]),
                ]),
            ])
            ->emptyStateHeading('Nenhum pedido encontrado.');
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
