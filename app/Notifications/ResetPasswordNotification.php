<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    protected $resetUrl;

    public function __construct($resetUrl)
    {
        $this->resetUrl = $resetUrl;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reset Your Password')
            ->greeting('Dear User,')
            ->line('You have requested to reset your password. Please click the link below to reset your password:')
            ->action('Reset Password', $this->resetUrl, [
                'style' => 'background-color: #198754; color: white; border: none; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 5px;'
            ])
            ->line('If you did not request this, please contact your System Administrator.')
            ->salutation("Thank you!\n\nBest Regards,\n\nDirectorate of Art and Culture");
    }
}
