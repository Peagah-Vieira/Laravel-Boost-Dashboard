<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Resources\OrderResource\Pages\ListOrders;

class OrderOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListOrders::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Quantidade de Pedidos', $this->getPageTableQuery()->count()),
            Stat::make('Pedidos Concluidos', $this->getPageTableQuery()->where('rush_progress', 'Concluido')->count()),
            Stat::make('Valor dos Pedidos Concluidos', '$' . $this->getPageTableQuery()->where('rush_progress', 'Concluido')->sum('rush_value')),
        ];
    }
}
