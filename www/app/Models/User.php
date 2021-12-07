<?php
namespace App\Models;

class User extends Model
{
    private $user;
    public function __construct()
    {
       $this->user = [];
    }
	public static function getTableName(): string
	{
		return 'user';
	}

	 public static function getColumns(): array
	{
		return [
            'username',
            'email',
            'password',
        ];
	}

   public function setUsername(string $pseudo)
    {
        $this->user['username'] = $pseudo;
    }

    public function setEmail(string $email)
    {
        $this->user['email'] = $email;
    }

    public function setPassword(string $password)
    {
        $this->user['password'] = $password;
    }

    public function getUser(): array
    {
        return $this->user;
    }

}