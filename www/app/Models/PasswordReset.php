<?php
namespace App\Models;

class PasswordReset extends Model
{
	public static function getTableName(): string
	{
		return 'passwordreset';
	}

	 public static function getColumns(): array
	{
		return [
            'user_id',
            'token',
        ];//TODO: mettre les colonnes, ne pas mettre id et created_at
	}

    public static function getUnique(): string
    {
        return 'token';
    }



    public function user(){

    }
    public static function getNotMappedColumns(): array
    {
        return [];
    }

    public static function hasUser($val){
        self::belongsTo(User::class,$val);
    }

}