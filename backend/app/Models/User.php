<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Role;
use App\Enums\UserStatus;
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
        'name',
        'email',
        'password',
        'phone',
        'role',
        'status',
        'business_info',
        'approved_at',
        'approved_by',
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
            'role' => Role::class,
            'status' => UserStatus::class,
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === Role::ADMIN;
    }

    /**
     * Check if user is accommodation manager
     */
    public function isAccommodationManager(): bool
    {
        return $this->role === Role::ACCOMMODATION_MANAGER;
    }

    /**
     * Check if user is customer
     */
    public function isCustomer(): bool
    {
        return $this->role === Role::CUSTOMER;
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === UserStatus::ACTIVE;
    }

    /**
     * Check if user is pending approval
     */
    public function isPending(): bool
    {
        return $this->status === UserStatus::PENDING;
    }

    /**
     * Check if user has role
     */
    public function hasRole(Role $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Approve the user
     */
    public function approve(User $approver): void
    {
        $this->update([
            'status' => UserStatus::ACTIVE,
            'approved_at' => now(),
            'approved_by' => $approver->id,
        ]);
    }

    /**
     * Reject the user
     */
    public function reject(): void
    {
        $this->update([
            'status' => UserStatus::REJECTED,
        ]);
    }

    /**
     * Suspend the user
     */
    public function suspend(): void
    {
        $this->update([
            'status' => UserStatus::SUSPENDED,
        ]);
    }

    /**
     * Get the admin who approved this user
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
