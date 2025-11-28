<?php

namespace App\Models;

use App\Notifications\CustomResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasUuids;

    protected $fillable = [ 'name', 'email', 'phone_number', 'password', 'role', 'google2fa_secret', 'security_phrase' ];
    protected $hidden = [ 'password', 'remember_token', 'google2fa_secret' ];

    protected function casts(): array
    {
        return [ 'email_verified_at' => 'datetime', 'password' => 'hashed' ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\QueuedVerifyEmail);
    }

    // --- Role Relationships ---
    public function student() { return $this->hasOne(Student::class); }
    public function academicAdvisor() { return $this->hasOne(AcademicAdvisor::class); }
    public function coordinator() { return $this->hasOne(Coordinator::class); }
    public function resourcePerson() { return $this->hasOne(ResourcePerson::class); }
    public function heaPersonnel() { return $this->hasOne(HeaPersonnel::class); }
    public function externalLecturer() { return $this->hasOne(ExternalLecturer::class); }
}
