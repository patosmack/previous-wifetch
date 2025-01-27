<?php

namespace App\Notifications\Order;

use App\Notifications\CustomMailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class OrderPlacedNotification extends Notification
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

        $message->subject($this->details['subject'] ?? 'WiFetch Order')->line($this->details['body']);

        if(array_key_exists('greeting', $this->details)){
            $message->greeting($this->details['greeting']);
        }


        if(isset($this->details['transaction_id']) && $this->details['transaction_id'] !== ''){
            $message->line(new HtmlString('<small>Order ID: : <strong>' . strtoupper($this->details['transaction_id']) . '</strong></small>'));
        }

        if(isset($this->details['actionText']) && isset($this->details['actionURL'])){
            $message->action($this->details['actionText'] , $this->details['actionURL']);
        }
        $message->line($this->details['thanks'] ?? '');
        if(isset($this->details['tableHeader']) && isset($this->details['tableBody'])){
            $message->table([
                'header' => $this->details['tableHeader'] ?? [],
                'body' => $this->details['tableBody'] ?? [],
            ]);
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
            'order_id' => $this->details['order_id']
        ];
    }

}
