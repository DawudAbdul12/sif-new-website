<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\RecordsActivity;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, RecordsActivity, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
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
            'is_admin' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles->contains('slug', $role);
    }

    public function hasAssignedRoles(): bool
    {
        return $this->roles->isNotEmpty();
    }

    public function hasPermission(string $permission): bool
    {
        if (! $this->is_admin) {
            return false;
        }

        $this->loadMissing('roles.permissions');

        if (! $this->hasAssignedRoles()) {
            return true;
        }

        if ($this->hasRole('super-admin')) {
            return true;
        }

        return $this->roles->contains(fn (Role $role) => $role->hasPermission($permission));
    }

    /**
     * @return array<int, string>
     */
    public function permissionNames(): array
    {
        $this->loadMissing('roles.permissions');

        if ($this->hasRole('super-admin')) {
            return array_keys(config('admin_permissions.permissions', []));
        }

        return $this->roles
            ->flatMap(fn (Role $role) => $role->permissions->pluck('name'))
            ->unique()
            ->values()
            ->all();
    }
}
