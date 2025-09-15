<?php

namespace App\Filament\Resources\FarmerResource\Pages;

use App\Filament\Resources\FarmerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListFarmers extends ListRecords
{
    protected static string $resource = FarmerResource::class;

    protected function getHeaderActions(): array
    {
        // tombol Create hanya muncul untuk admin
        return Auth::user()?->hasRole('admin')
            ? [Actions\CreateAction::make()->label('Tambah Peternak')]
            : [];
    }
}
