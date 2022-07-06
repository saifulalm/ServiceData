<?php

namespace App\Listeners\ServiceData;

use App\Events\ServiceData\CallbackEvent;
use App\Events\ServiceData\RequestEvent;
use App\Events\ServiceData\ResponseEvent;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class SendToLog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */

    private static function Logger($name, $log, $log_filename)
    {
        date_default_timezone_set("Asia/Jakarta");
        $dateFormat = "d-m-Y H:i:s";
        $output = "%datetime% , " . $name . ": %message% \n";
        $formatter = new LineFormatter($output, $dateFormat);
        $stream = new StreamHandler((storage_path($log_filename . date('d-M-Y') . '.log')), Logger::INFO);
        $stream->setFormatter($formatter);
        $orderLog = new Logger($name);
        $orderLog->pushHandler($stream);
        $orderLog->info($log);
    }

    public function handleRequestEvent(RequestEvent $event): void
    {

        $data = $event->data;
        $this->logger('Request', $data, '/logs/ServiceData/');

    }

    public function handleResponseEvent(ResponseEvent $event): void
    {
        $response = $event->response;
        $this->logger('Response', $response, '/logs/ServiceData/');

    }

    public function handleCallbackEvent(CallbackEvent $event): void
    {
        $callback = $event->callback;
        $this->logger('Callback', $callback, '/logs/ServiceData/');

    }

    public function ActivityServiceData($events): void
    {
        $events->listen(
            RequestEvent::class,
            'App\Listeners\Aco\SendToLog@handleRequestEvent'
        );

        $events->listen(
            ResponseEvent::class,
            'App\Listeners\Aco\SendToLog@handleResponseEvent'
        );

        $events->listen(
            CallbackEvent::class,
            'App\Listeners\Aco\SendToLog@handleCallbackEvent'
        );
    }
}
