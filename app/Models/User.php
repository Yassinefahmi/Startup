<?php


namespace App\Models;


class User extends Model
{
    public static function tableName(): string
    {
        return 'users';
    }

    public function primaryKey(): string
    {
        return 'id';
    }

    public function fillable(): array
    {
        return [
            'username', 'password'
        ];
    }
}