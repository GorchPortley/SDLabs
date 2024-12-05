<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Maicol07\SSO\Flarum;
use Wave\User as WaveUser;
use Illuminate\Notifications\Notifiable;
use Wave\Traits\HasProfileKeyValues;

class User extends WaveUser
{
    use Notifiable, HasProfileKeyValues;

    public $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'avatar',
        'password',
        'role_id',
        'verification_code',
        'verified',
        'trial_ends_at',
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

    protected static function boot()
    {
        parent::boot();

        // Listen for the creating event of the model
        static::creating(function ($user) {
            // Check if the username attribute is empty
            if (empty($user->username)) {
                // Use the name to generate a slugified username
                $username = Str::slug($user->name, '');
                $i = 1;
                while (self::where('username', $username)->exists()) {
                    $username = Str::slug($user->name, '') . $i;
                    $i++;
                }
                $user->username = $username;
            }
        });

        // Listen for the created event of the model
        static::created(function ($user) {
            // Remove all roles
            $user->syncRoles([]);
            // Assign the default role
            $user->assignRole( config('wave.default_user_role', 'registered') );
        });

        // SSO Registration Handler
        static::creating(function ($user) {
            try {
                $flarum = new Flarum([
                    'url' => env('FORUM_URL'),
                    'root_domain' => env('APP_URL'),
                    'api_key' => env('FORUM_API_KEY'),
                    'password_token' => env('FORUM_PASSWORD_TOKEN'),
                    'remember' => true,
                    'verify_ssl' => env('FORUM_VERIFY_SSL', true),
                ]);

                // Create user in Flarum
                $flarum_user = $flarum->user($user->email);
                $flarum_user->attributes->username = $user->username ?? $user->name;
                $flarum_user->attributes->email = $user->email;
                $flarum_user->attributes->password = $user->password;

                $flarum_user->signup();
            } catch (\Exception $e) {
                \Log::error('Flarum SSO Registration Error: ' . $e->getMessage());
                // Optionally, you might want to prevent user creation
                // return false;
            }
        });

        // SSO Login Handler
        static::updated(function ($user) {
            if ($user->wasChanged('password')) {
                try {
                    $flarum = new Flarum([
                        'url' => env('FORUM_URL'),
                        'root_domain' => env('APP_URL'),
                        'api_key' => env('FORUM_API_KEY'),
                        'password_token' => env('FORUM_PASSWORD_TOKEN'),
                        'remember' => true,
                        'verify_ssl' => env('FORUM_VERIFY_SSL', true),
                    ]);

                    // Update user password in Flarum
                    $flarum_user = $flarum->user($user->email);
                    $flarum_user->attributes->password = $user->password;
                    $flarum_user->save();
                } catch (\Exception $e) {
                    \Log::error('Flarum SSO Password Update Error: ' . $e->getMessage());
                }
            }
        });

        // SSO Deletion Handler
        static::deleting(function ($user) {
            try {
                $flarum = new Flarum([
                    'url' => env('FORUM_URL'),
                    'root_domain' => env('APP_URL'),
                    'api_key' => env('FORUM_API_KEY'),
                    'password_token' => env('FORUM_PASSWORD_TOKEN'),
                    'verify_ssl' => env('FORUM_VERIFY_SSL', true),
                ]);

                // Delete user from Flarum
                $flarum_user = $flarum->user($user->email);
                $flarum_user->delete();
            } catch (\Exception $e) {
                \Log::error('Flarum SSO Deletion Error: ' . $e->getMessage());
            }
        });
    }
    public function designs(): HasMany
    {
        return $this->hasMany(Design::class);
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }

    public function designPurchases(): HasMany
    {
        return $this->hasMany(DesignPurchase::class);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class)
            ->with('items');
    }
}
