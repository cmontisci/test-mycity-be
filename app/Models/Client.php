<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class Client extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'secret_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'secret_id',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'secret_id' => 'hashed',
        ];
    }

    public function getAuthPassword()
    {
        return $this->secret_id;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Find the user instance for the given email.
     */
    public function findForPassport(string $email): User
    {
        return $this->where('client_id', $email)->first();
    }

    public function validateForPassportPasswordGrant(string $secret_id): bool
    {
        return Hash::check($secret_id, $this->secret_id);
    }
}
