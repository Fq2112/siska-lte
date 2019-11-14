<?php

namespace App\Models;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getApplication()
    {
        return $this->hasMany(Applications::class, 'user_id');
    }

    public function getAttachments()
    {
        return $this->hasMany(Attachments::class, 'user_id');
    }

    public function getEducation()
    {
        return $this->hasMany(Education::class, 'user_id');
    }

    public function getExperience()
    {
        return $this->hasMany(Experience::class, 'user_id');
    }

    public function getOrganization()
    {
        return $this->hasMany(Organization::class, 'user_id');
    }

    public function getTraining()
    {
        return $this->hasMany(Training::class, 'user_id');
    }

    public function getLanguage()
    {
        return $this->hasMany(Languages::class, 'user_id');
    }

    public function getSkill()
    {
        return $this->hasMany(Skills::class, 'user_id');
    }

    public function socialProviders()
    {
        return $this->hasMany(SocialProvider::class, 'user_id');
    }

    public function scopeByActivationColumns(Builder $builder, $email, $verifyToken)
    {
        return $builder->where('email', $email)->where('verifyToken', $verifyToken);
    }

    /**
     * Sends the password reset notification.
     *
     * @param  string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPassword($token));
    }
}

class CustomPassword extends ResetPassword
{
    public function toMail($notifiable)
    {
        $data = $this->token;
        return (new MailMessage)
            ->from(env('MAIL_USERNAME'), env("APP_NAME") . ' | SISKA - Sistem Informasi Karier')
            ->subject('SISKA Account: Reset Password')
            ->view('emails.auth.recovery', compact('data'));
    }
}