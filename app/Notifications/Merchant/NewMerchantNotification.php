<?php

namespace App\Notifications\Merchant;

use App\Notifications\CustomMailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class NewMerchantNotification extends Notification
{
    use Queueable;

    private $details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return CustomMailMessage
     */
    public function toMail($notifiable)
    {

        $message = new CustomMailMessage();

        $message->subject($this->details['subject'] ?? 'WiFetch - New merchant')
        ->greeting($this->details['greeting'])
        ->line($this->details['body']);

        if(isset($this->details['actionText']) && isset($this->details['actionURL'])){
            $message->action($this->details['actionText'] , $this->details['actionURL']);
        }
        return $message;

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */

    public function toDatabase($notifiable)
    {
        return [
            'merchant_id' => $this->details['merchant_id']
        ];
    }

}
