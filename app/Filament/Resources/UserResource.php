<?php

namespace App\Filament\Resources;


use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $modelLabel = 'Usuário';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
        //update and create
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nome Completo')
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true) 
                    ->maxLength(255),
              
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->label('Senha')
                    ->dehydrateStateUsing(fn($state) => Hash::make($state)) //create and  update hash password
                    ->dehydrated(fn($state)=>filled($state))
                    ->required(fn(string $context): bool => $context === 'create'), //require password only when creating a new record

                //defining belongs to many relationship between users and roles
                Forms\Components\Select::make('roles')
                    ->label('Função')
                    ->multiple()
                    ->preload() //load register datas
                    ->relationship('roles', 'name', fn(Builder $query) =>  auth()->user()->hasRole('Admin') ? null : $query->where('name', '!==', 'Admin') ), 
                    
                    //Manager cannot create a admin user 
           
                      
            ]);
    }

    public static function table(Table $table): Table
    {
        //show table
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Nome Completo'),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                

                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->label('Criado em'),

                
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Only admin can edit/delete/create another admin user
        return auth()->user()->hasRole('Admin')
        ? parent::getEloquentQuery()
        : parent::getEloquentQuery()->whereHas(
            'roles' , fn(Builder $query) => $query->where('name', '!==', 'Admin')
        );
    }
}
