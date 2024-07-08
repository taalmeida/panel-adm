<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
        protected static string $resource = UserResource::class;

        //redirect the given url after create
        protected function getRedirectUrl(): string
        {
            return $this->getResource()::getUrl('index');
        }

        //save with hash password
        // protected function mutateFormDataBeforeCreate(array $data): array
        // {
        //     $data['password'] = Hash::make($data['password']);

        //     return $data;
        // }

}
