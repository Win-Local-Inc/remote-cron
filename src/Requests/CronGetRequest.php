<?php

namespace WinLocal\RemoteCron\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Artisan;

class CronGetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'command' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (! array_key_exists($value, Artisan::all())) {
                        $fail("The {$attribute} {$value} doesn't exists.");
                    }
                },
            ],
            'parameters' => 'sometimes|nullable|json',
        ];
    }
}
