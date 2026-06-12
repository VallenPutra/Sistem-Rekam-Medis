<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes; 

    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'password', 'role', 'no_hp', 'spesialis', 'aktif'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];


    public function isAdmin(): bool
    {
        return strtolower($this->role) === 'admin';
    }

    public function isDokter(): bool
    {
        return strtolower($this->role) === 'dokter';
    }

    public function kunjungan()
    {
        return $this->hasMany(Kunjungan::class, 'dokter_id');
    }

    public function rawatInap()
    {
        return $this->hasMany(RawatInap::class, 'dokter_id');
    }
}