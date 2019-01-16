<?php

namespace App\Models;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use App\Support\Role;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    protected $table = 'admins';

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

    /**
     * Check whether this user is root or not
     * @return bool
     */
    public function isRoot()
    {
        return ($this->role == Role::ROOT);
    }

    /**
     * Check whether this user is admin or not
     * @return bool
     */
    public function isAdmin()
    {
        return ($this->role == Role::ADMIN);
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
        $this->notify(new CustomPasswordAdmin($token));
    }
}

class CustomPasswordAdmin extends ResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from(env('MAIL_USERNAME'), env("APP_NAME") . ' | SISKA - Sistem Informasi Karier')
            ->subject('SISKA Account: Admin Reset Password')
            ->line('We are sending this email because we received a forgot password request.')
            ->action('Reset Password', url(route('password.reset', $this->token, false)))
            ->line('If you did not request a password reset, no further action is required. Please contact us if you did not submit this request.');
    }
}