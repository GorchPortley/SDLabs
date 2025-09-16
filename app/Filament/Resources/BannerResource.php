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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Internal Name')
                    ->nullable(),

                Forms\Components\FileUpload::make('image_path')
                    ->label('Banner Image')
                    ->image()
                    ->required()
                    ->imageResizeMode('contain')
                    ->imageCropAspectRatio('1920:480')
                    ->imageResizeTargetWidth(1920)
                    ->imageResizeTargetHeight(480),

                Forms\Components\Select::make('location')
                    ->options([
                        'front_page' => 'Front Page',
                        'design_page' => 'Design Page',
                        'driver_page' => 'Driver Page',
                        // Add more locations as needed
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('start_date')
                    ->label('Active From')
                    ->nullable(),

                Forms\Components\DatePicker::make('end_date')
                    ->label('Active Until')
                    ->nullable(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),

                Forms\Components\TextInput::make('priority')
                    ->numeric()
                    ->default(0)
                    ->label('Display Priority (higher number = higher priority)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\ImageColumn::make('image_path'),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\TextColumn::make('start_date'),
                Tables\Columns\TextColumn::make('end_date'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
