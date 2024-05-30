<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\SalesChart;
use App\Models\Sale;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Sales extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Sales';

    protected static ?string $navigationGroup = 'Sales';

    protected static string $view = 'filament.pages.sales';

    protected function getHeaderWidgets(): array
    {
        return [
            SalesChart::class,
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Sale::query()->orderBy('productId', 'asc'))
            ->columns([
                Tables\Columns\TextColumn::make('productId')
                    ->label('Id')
                    ->toggleable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('product')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('productSlug')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->badge()
                    ->color(fn ($state) => $state > 100 ? 'green' : ($state < 10 ? 'red' : 'blue'))
                    ->toggleable()
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->wrap()
                    ->toggleable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->toggleable()
                    ->color('zinc')
                    ->sortable(),

                Tables\Columns\ColorColumn::make('color')
                    ->sortable()
                    ->toggleable()
                    ->copyable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(fn () => Sale::distinct()->pluck('category', 'category')->toArray()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Statistics')
                    ->modalHeading('View Statistics')
                    ->form([
                        Forms\Components\TextInput::make('sales')
                            ->formatStateUsing(fn (string $state): string => number_format((int) $state)),

                        Forms\Components\TextInput::make('revenue')
                            ->formatStateUsing(fn (string $state): string => number_format((int) $state)),

                        Forms\Components\TextInput::make('lastSaleDate'),
                    ]),
            ]);
    }
}
