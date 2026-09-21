<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_course',
        'id_number',
        'phone',
        'is_approved',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_approved' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isCustodian(): bool
    {
        return $this->role === UserRole::CUSTODIAN;
    }

    public function isBorrower(): bool
    {
        return $this->role === UserRole::BORROWER;
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'borrower_id');
    }

    public function processedBorrowings()
    {
        return $this->hasMany(Borrowing::class, 'custodian_id');
    }
}
