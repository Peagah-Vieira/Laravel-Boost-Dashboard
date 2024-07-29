<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\HtmlString;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $navigationLabel = 'Pedidos';

    protected static ?string $modelLabel = 'Pedidos';

    protected static ?string $navigationGroup = 'Dummy Group1';

    protected static ?string $slug = 'pedidos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Pedido')
                        ->icon('heroicon-m-shopping-bag')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->schema([
                            Forms\Components\TextInput::make('rush_id')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\RichEditor::make('rush_description')
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\Select::make('rush_progress')
                                ->required()
                                ->options([
                                    'Não iniciada' => 'Não iniciada',
                                    'Em andamento' => 'Em andamento',
                                    'Concluido' => 'Concluido',
                                ]),
                            Forms\Components\TextInput::make('rush_value')
                                ->required()
                                ->numeric(),
                        ]),
                    Wizard\Step::make('Detalhes')
                        ->schema([
                            Forms\Components\Select::make('booster_id')
                                ->relationship(name: 'booster', titleAttribute: 'first_name')
                                ->required(),
                            Forms\Components\Select::make('booster2_id')
                                ->relationship(name: 'booster', titleAttribute: 'first_name')
                                ->required(),
                            Forms\Components\Select::make('booster3_id')
                                ->relationship(name: 'booster', titleAttribute: 'first_name')
                                ->required(),
                            Forms\Components\Select::make('booster4_id')
                                ->relationship(name: 'booster', titleAttribute: 'first_name')
                                ->required(),
                        ]),
                    Wizard\Step::make('Pagamento')
                        ->schema([
                            Forms\Components\Toggle::make('paid'),
                            Forms\Components\DatePicker::make('payment_date'),
                        ]),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rush_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rush_progress')
                    ->sortable(),
                Tables\Columns\TextColumn::make('rush_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booster_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booster2_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booster3_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booster4_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('paid')
                    ->boolean(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
