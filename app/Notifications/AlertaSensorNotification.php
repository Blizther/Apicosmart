<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AlertaSensorNotification extends Notification
{
    use Queueable;

    public function __construct(
        public $dispositivo,
        public $lectura,
        public $alertas
    ) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Alerta de sensores')
            ->greeting('Hola!')
            ->line("El dispositivo {$this->dispositivo->id} reportó valores fuera de umbral.")
            ->line("Lectura ID: {$this->lectura->id}");

        foreach ($this->alertas as $a) {
            $mail->line("• {$a['campo']}: {$a['valor']} (umbral {$a['min']} - {$a['max']})");
        }

        return $mail->line('Revisa la app para más detalles.');
    }
}
