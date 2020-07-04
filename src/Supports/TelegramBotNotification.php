<?php

namespace Nsingularity\GeneralModule\Foundation\Supports;

use Exception;
use GuzzleHttp\Client;

class TelegramBotNotification
{
    private $title;
    private $text;

    /**
     * TelegramBotNotification constructor.
     * @param null $title
     */
    public function __construct($title = null)
    {
        $this->title = "error " . env("APP_ENV", '');
        if ($title) {
            $this->title = $title;
        }
    }

    public function send($text)
    {
        $this->text = $text;
        if (env('TELEGRAM_BOT_TOKEN') && env('TELEGRAM_GROUP_CHAT_ID')) {
            $client = new Client(['verify' => false]);
            try {
                $client->request("GET", "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
                    "query" =>
                        [
                            "text"       => "==============================================================\n" .
                                "```\n" . $text . "\n```",
                            "chat_id"    => env('TELEGRAM_GROUP_CHAT_ID'),
                            "parse_mode" => "markdown",
                        ]
                ]);
            } catch (Exception $e) {
                $e->getMessage();
            }
        }
    }

    public function sendException(Throwable $exception)
    {
        $traceArray    = $exception->getTrace();
        $filteredTrace = [];

        foreach ($traceArray as $trace) {
            if (strpos(@$trace["class"], "App\\") !== false) {
                $filteredTrace[] = @$trace["class"] . @$trace["type"] . @$trace["function"].(@$trace["line"]?":".$trace["line"]:null);
            }
        }

        $hideParams = ["password"];
        $param = request()->all();

        foreach ($hideParams as $hideParam){
            if(@$param[$hideParam]){
                $param[$hideParam] = "**********";
            }
        }

        $messages =
            $this->title . "\n" .
            "------------------------------------------\n" .
            "Messages: " . $exception->getMessage() . "\n" .
            "------------------------------------------\n" .
            "File: " . $exception->getFile() . "\n" .
            "------------------------------------------\n" .
            "Line: " . $exception->getLine() . "\n" .
            "------------------------------------------\n" .
            "URL: [" . request()->method() . "]" . request()->fullUrl() . "\n" .
            "------------------------------------------\n" .
            "PORT: ". request()->getPort() . "\n" .
            "------------------------------------------\n" .
            "IP: " . request()->getClientIp(). "\n" .
            "------------------------------------------\n" .
            "USER AGENT: " . request()->header("user-agent"). "\n" .
            "------------------------------------------\n" .
            "PARAM: " . json_encode($param) . "\n" .
            "------------------------------------------\n" .
            "CLASS TRACE:\n" . implode("\n", $filteredTrace) . "\n" .
            "------------------------------------------\n" .
            "EXCEPTION CLASS: ". get_class($exception);

        $this->send($messages);
    }

    public function sendArrayFormattedException($exceptionArrayFormatted)
    {
        $traceArray    = @$exceptionArrayFormatted["trace"]??[];
        $filteredTrace = [];
        foreach ($traceArray as $trace) {
            if (strpos(@$trace["class"], "App\\") !== false) {
                $filteredTrace[] = @$trace["class"] . @$trace["type"] . @$trace["function"].(@$trace["line"]?":".$trace["line"]:null);
            }
        }

        $hideParams = ["password"];
        $param = request()->all();

        foreach ($hideParams as $hideParam){
            if(@$param[$hideParam]){
                $param[$hideParam] = "**********";
            }
        }

        $messages =
            $this->title . "\n" .
            "------------------------------------------\n" .
            "Messages: " . @$exceptionArrayFormatted["message"] . "\n" .
            "------------------------------------------\n" .
            "File: " . @$exceptionArrayFormatted["file"] . "\n" .
            "------------------------------------------\n" .
            "Line: " . @$exceptionArrayFormatted["line"] . "\n" .
            "------------------------------------------\n" .
            "URL: [" . request()->method() . "]" . request()->fullUrl() . "\n" .
            "------------------------------------------\n" .
            "IP: " . request()->getClientIp(). "\n" .
            "------------------------------------------\n" .
            "USER AGENT: " . request()->header("user-agent"). "\n" .
            "------------------------------------------\n" .
            "PARAM:" . json_encode($param) . "\n" .
            "------------------------------------------\n" .
            "CLASS TRACE:\n" . implode("\n", $filteredTrace);

        $this->send($messages);
    }
}
