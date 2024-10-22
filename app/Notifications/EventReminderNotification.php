<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Carbon\Carbon;

class EventReminderNotification extends Notification
{
    use Queueable;

    protected $event;

/**
    * Create a new notification instance.
    *
    * @param $event イベント情報を渡す
    */    
    
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; // メールで通知
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('イベントリマインダー')
                    ->greeting('こんにちは。')
                    ->line('あなたが登録したイベント  "' . $this->event->name . '" が間もなく開始されます。')
                    ->line('イベント開始日:' . Carbon::parse($this->event->start_date)->format('Y-m-d 00:00:00'))
                    ->action('イベント詳細を見る', url('/events/' . $this->event->id))
                    ->line('お見逃しなく。');
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
            'event_id' => $this->event->id,
            'event_name' => $this->event->name,
        ];
    }
}
