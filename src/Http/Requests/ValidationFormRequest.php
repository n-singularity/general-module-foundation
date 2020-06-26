<?php


namespace Nsingularity\GeneralModule\Foundation\Http\Requests;

use Illuminate\Http\Request;
use Nsingularity\GeneralModule\Foundation\Exceptions\CustomMessagesException;

abstract class ValidationFormRequest extends Request
{
    public function __construct()
    {
        parent::__construct(
            request()->query->all(),
            request()->request->all(),
            request()->attributes->all(),
            request()->cookies->all(),
            request()->files->all(),
            request()->server->all(),
            request()->content);

        $this->validation();
    }

    abstract protected function rules(): array;

    abstract protected function messages(): array;

    protected function data(): array
    {
        return $this->all();
    }

    /**
     * @throws CustomMessagesException
     */
    protected function validation(): void
    {
        customValidation($this->data(), $this->rules(), $this->messages());
    }
}
