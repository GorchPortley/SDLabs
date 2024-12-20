<?php

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\{Columns\ToggleColumn,
    Table,
    Concerns\InteractsWithTable,
    Contracts\HasTable,
    Actions\Action,
    Actions\CreateAction,
    Actions\DeleteAction,
    Actions\EditAction,
    Actions\ViewAction,
    Columns\TextColumn
};
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;
use App\Models\Driver;
use Maatwebsite\Excel\Facades\Excel;
use function Laravel\Folio\{middleware, name};
use Illuminate\Support\Collection;
use App\Exports\SpecificationsExport;

middleware('auth');
name('dashboard.drivers');


new class extends Component implements HasForms, Tables\Contracts\HasTable {
    use InteractsWithForms, InteractsWithTable;

    public ?array $data = [];

    public function viewAction(): Action
    {
        $driver_id = '';

        return Action::make('View Driver')
            ->url(fn($driver_id): string => route('driver', ['id' => $driver_id]));
    }

    function getfrqpath($model)
    {
        if (!$model) {
            return 'files/lost files/drivers/frequency files';
        }

        $userId = auth()->id();

        return "files/{$userId}/Drivers/{$model}/Frequency";
    }

    function getzpath($model)
    {
        if (!$model) {
            return 'files/lost files/drivers/frequency files';
        }

        $userId = auth()->id();

        return "files/{$userId}/Drivers/{$model}/Impedance";
    }

    function getotherpath($model)
    {
        if (!$model) {
            return 'files/lost files/drivers/frequency files';
        }

        $userId = auth()->id();

        return "files/{$userId}/Drivers/{$model}/Other";
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Driver::query()->where('user_id', auth()->id()))
            ->heading('Drivers')
            ->description('Manage your Drivers here!')
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        if (auth()->user()->hasRole('manufacturer')) {
                            $data['official'] = 1;
                        };
                        return $data;
                    })
                    ->form([
                        Toggle::make('active'),
                        TextInput::make('brand')
                            ->datalist(DB::table('drivers')->distinct()->orderBy('brand', 'asc')->pluck('brand')),
                        TextInput::make('model')
                            ->live(),
                        TextInput::make('tag'),
                        Select::make('category')
                            ->options(
                                ['Subwoofer' => 'Subwoofer', 'Woofer' => 'Woofer', 'Tweeter' => 'Tweeter', 'Compression Driver' => 'Compression Driver', 'Exciter' => 'Exciter', 'Other' => 'Other']),
                        TextInput::make('size')
                            ->numeric(),
                        TextInput::make('impedance')
                            ->numeric(),
                        TextInput::make('power')
                            ->numeric(),
                        TextInput::make('price')
                            ->numeric(),
                        TextInput::make('link')
                            ->url(),
                        FileUpload::make('card_image')
                            ->label('Driver Image')
                            ->preserveFilenames()
                            ->directory('attachments'),
                        Section::make('Driver File Uploads')
                            ->description('Upload the working files used to design your speaker')
                            ->collapsed()
                            ->collapsible()
                            ->schema([
                                FileUpload::make('frequency_files')
                                    ->label('Frequency Measurements')
                                    ->multiple()
                                    ->preserveFilenames()
                                    ->directory(function ($get) {
                                        $model = $get('model');
                                        return $this->getfrqpath($model);
                                    }),

                                FileUpload::make('impedance_files')
                                    ->label('Impedance Measurements')
                                    ->multiple()
                                    ->preserveFilenames()
                                    ->directory(function ($get) {
                                        $model = $get('model');
                                        return $this->getzpath($model);
                                    }),

                                FileUpload::make('other_files')
                                    ->label('Other Files')
                                    ->multiple()
                                    ->preserveFilenames()
                                    ->directory(function ($get) {
                                        $model = $get('model');
                                        return $this->getotherpath($model);
                                    }),]),

                        TextInput::make('summary'),
                        TipTapEditor::make('description')
                            ->directory('attachments'),
                        KeyValue::make('factory_specs')
                            ->default([
                                'Re' => '',
                                'Fs' => '',
                                'Qms' => '',
                                'Qes' => '',
                                'Qts' => '',
                                'Rms' => '',
                                'Mms' => '',
                                'Cms' => '',
                                'Vas' => '',
                                'Sd' => '',
                                'BL' => '',
                                'Xmax' => '',
                                'Le' => '',
                                'SPL' => '',
                                'EBP' => '',
                                'Vd' => '',
                                'Mmd' => '',
                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->editableKeys(false)
                            ->keyLabel('Parameter')
                            ->valueLabel('Value'),
                    ])
                    ->after(function () {
                        Notification::make()
                            ->success()
                            ->title('Driver created')
                            ->send();
                    })
            ])
            ->columns([
                ToggleColumn::make('active')
                    ->onColor('success')
                    ->sortable(),
                TextColumn::make('brand')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('model')
                    ->searchable()
                    ->sortable()
                    ->description(fn(Driver $record): string => $record->tag),
                TextColumn::make('category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('size')
                    ->label('Size in inches')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('impedance')
                    ->label('Impedance')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created On')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Action::make('createDriverSnapshot')
                    ->label('New Snapshot')
                    ->icon('heroicon-m-camera')
                    ->color('primary')
                    ->form([
                        TextInput::make('version')
                            ->label('Snapshot Version')
                            ->default('v' . now()->format('YmdHis'))
                            ->required()
                            ->placeholder('Enter version (e.g., v1.0)')
                    ])
                    ->action(function (array $data, ?Driver $record) {
                        $driver = $record;
                        $version = $data['version'];
                        $driver->createDriverSnapshot($driver, $version);
                    }),
                Action::make('spec_export')
                    ->name('Collect Data')
                    ->icon('phosphor-table')
                    ->action(function ($record) {
                        $components = $record->designs()->get();
                        $driverBrand = $record->brand; // Replace with the actual column or relation for driver brand
                        $driverModel = $record->model; // Replace with the actual column or relation for driver model
                        $currentDate = now()->format('Y-m-d'); // Format the current date (e.g., 2024-12-09)

                        $fileName = "{$driverBrand} {$driverModel}-SDLabs-export-{$currentDate}.xlsx";

                        $data = $components->map(function ($component) {
                            return array_merge(
                                ['position' => $component->position,
                                 'quantity' => $component->quantity,
                                'low_frequency' => $component->low_frequency,
                                    'high_frequency' => $component->high_frequency,
                                    'air_volume' => $component->air_volume,
                                    'Re' => $component->specifications['Re'] ?? null,
                                    'Fs' => $component->specifications['Fs'] ?? null,
                                    'Qms' => $component->specifications['Qms'] ?? null,
                                    'Qes' => $component->specifications['Qes'] ?? null,
                                    'Qts' => $component->specifications['Qts'] ?? null,
                                    'Rms' => $component->specifications['Rms'] ?? null,
                                    'Mms' => $component->specifications['Mms'] ?? null,
                                    'Cms' => $component->specifications['Cms'] ?? null,
                                    'Vas' => $component->specifications['Vas'] ?? null,
                                    'Sd' => $component->specifications['Sd'] ?? null,
                                    'BL' => $component->specifications['BL'] ?? null,
                                    'Xmax' => $component->specifications['Xmax'] ?? null,
                                    'Le' => $component->specifications['Le'] ?? null,
                                    'SPL' => $component->specifications['SPL'] ?? null,
                                    'EBP' => $component->specifications['EBP'] ?? null,
                                    'Vd' => $component->specifications['Vd'] ?? null,
                                    'Mmd' => $component->specifications['Mmd'] ?? null,
                                ]
                            );
                        });
                        return Excel::download(new SpecificationsExport($data), $fileName);
                    })
                    ->visible(fn() => auth()->user()->hasRole('manufacturer')),
                Action::make('View')
                    ->icon('phosphor-eye')
                    ->url(fn($record): string => route('driver', ['id' => $record->id])),
                EditAction::make()
                    ->form([
                        Toggle::make('active'),
                        TextInput::make('brand')
                            ->datalist(DB::table('drivers')->distinct()->orderBy('brand', 'asc')->pluck('brand')),
                        TextInput::make('model')
                            ->live(),
                        TextInput::make('tag'),
                        Select::make('category')
                            ->options(
                                ['Subwoofer' => 'Subwoofer', 'Woofer' => 'Woofer', 'Tweeter' => 'Tweeter', 'Compression Driver' => 'Compression Driver', 'Exciter' => 'Exciter', 'Other' => 'Other']),
                        TextInput::make('size')
                            ->numeric(),
                        TextInput::make('impedance')
                            ->numeric(),
                        TextInput::make('power')
                            ->numeric(),
                        TextInput::make('price')
                            ->numeric(),
                        TextInput::make('link')
                            ->url(),
                        FileUpload::make('card_image')
                            ->label('Driver Image')
                            ->directory('attachments'),
                        Section::make('Driver File Uploads')
                            ->description('Upload the working files used to design your speaker')
                            ->collapsed()
                            ->collapsible()
                            ->schema([
                                FileUpload::make('frequency_files')
                                    ->label('Frequency Measurements')
                                    ->multiple()
                                    ->preserveFilenames()
                                    ->directory(function ($get) {
                                        $model = $get('model');
                                        return $this->getfrqpath($model);
                                    }),

                                FileUpload::make('impedance_files')
                                    ->label('Impedance Measurements')
                                    ->multiple()
                                    ->preserveFilenames()
                                    ->directory(function ($get) {
                                        $model = $get('model');
                                        return $this->getzpath($model);
                                    }),

                                FileUpload::make('other_files')
                                    ->label('Other Files')
                                    ->multiple()
                                    ->preserveFilenames()
                                    ->directory(function ($get) {
                                        $model = $get('model');
                                        return $this->getotherpath($model);
                                    }),]),

                        TextInput::make('summary'),
                        TipTapEditor::make('description')
                            ->directory('attachments'),
                        KeyValue::make('factory_specs')
                            ->default([
                                'Re' => '',
                                'Fs' => '',
                                'Qms' => '',
                                'Qes' => '',
                                'Qts' => '',
                                'Rms' => '',
                                'Mms' => '',
                                'Cms' => '',
                                'Vas' => '',
                                'Sd' => '',
                                'BL' => '',
                                'Xmax' => '',
                                'Le' => '',
                                'SPL' => '',
                                'EBP' => '',
                                'Vd' => '',
                                'Mmd' => '',
                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->editableKeys(false)
                            ->keyLabel('Parameter')
                            ->valueLabel('Value'),
                    ]),
            ])
            ->filters([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('active'),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('tag')
                    ->maxLength(255),
                Select::make('category')
                    ->options(['Subwoofer' => 'Subwoofer', 'Full-Range' => 'Full-Range', 'Two-Way' => 'Two-Way'
                        , 'Three-Way' => 'Three-Way', 'Four-Way+', 'Four-Way+', 'Portable' => 'Portable', 'Esoteric' => 'Esoteric']),
                TextInput::make('price')
                    ->numeric(),
                TextInput::make('build_cost')
                    ->numeric(),
                TextInput::make('impedance')
                    ->numeric(),
                TextInput::make('power')
                    ->numeric(),
                Textarea::make('summary')
                    ->columns(2),
                MarkdownEditor::make('description'),
                KeyValue::make('bill_of_materials'),
            ])
            ->statePath('data');
    }

}
?>


<x-layouts.app>
    @volt('dashboard.drivers')
    {{ $this->table }}
    @endvolt
</x-layouts.app>

