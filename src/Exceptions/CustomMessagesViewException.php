<?php


namespace Nsingularity\GeneralModule\Foundation\Exceptions;

use Exception;

class CustomMessagesViewException extends Exception
{
    private $realException;

    private $statusCode = 200;

    private $forceReport = false;

    private $view = false;

    /**
     * CustomMessagesViewException constructor.
     * @param $view
     * @param array $params
     * @param int $statusCode
     * @param Exception|null $realException
     * @param bool $forceReport
     * @param $messages
     * @param Exception|null $previous
     * @param int $code
     */
    public function __construct($view, $params = [], $statusCode = 200, Exception $realException = null, $forceReport = false, $messages = "", Exception $previous = null, $code = 0)
    {
        $this->forceReport = $forceReport;

        $this->view = view($view, $params);

        if ($realException instanceof Exception) {
            $this->realException = $realException;
        }

        $this->statusCode = $statusCode;
        parent::__construct($messages, $statusCode);
    }

    public function getMessages()
    {
        return json_decode($this->message);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @return bool
     */
    public function isForceReport(): bool
    {
        return $this->forceReport;
    }

    public function getRealException()
    {
        return $this->realException;
    }
}
