<?php


namespace App\Models;


class User extends Model
{
    public function tableName(): string
    {
        return 'users';
    }

    public function attributes(): array
    {
        return [
            'username', 'password'
        ];
    }
}