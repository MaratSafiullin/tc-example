<?php

namespace App\Http\Core\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    final public function authorize(): bool
    {
        return true;
    }
}
