<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'is_active',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'role' => UserRole::class,
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the URL for the user's profile in the AdminLTE sidebar.
     * This method is called by jeroennoten/laravel-adminlte when usermenu_profile_url is true.
     *
     * @return string
     */
    public function adminlte_profile_url()
    {
        return route('profile.show');
    }

    /**
     * Get the URL for the user's avatar in the AdminLTE sidebar.
     * This method is called by jeroennoten/laravel-adminlte when usermenu_image is true.
     *
     * @return string
     */
    public function adminlte_image()
    {
        // Return the path to the user's avatar, or a default image
        return $this->avatar ? asset('storage/'.$this->avatar) : asset('images/user2-160x160.jpg');
    }

    /**
     * Get the description for the user in the AdminLTE sidebar.
     * This method is called by jeroennoten/laravel-adminlte when usermenu_desc is true.
     *
     * @return string
     */
    public function adminlte_desc()
    {
        // Return the user's role name
        return $this->role ?? 'Utilisateur';
    }
}
