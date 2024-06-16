<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'photo_filename',
        'blocked'
        ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    

    /**
     * Get the customer associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id', 'id')->withTrashed();
    }

    public function getPhotoFullUrlAttribute()
    {
        if ($this->photo_url && Storage::exists("public/photos/{$this->photo_url}")) {
            return asset("storage/photos/{$this->photo_url}");
        } else {
            return asset("storage/photos/anonymous.jpg");
        }
    }

    public function getTypeDescriptionAttribute()
    {
        return match ($this->type) {
            'A'       => "Administrative",
            'C'       => "Customer",
            default => '?'
        };
    }
}
