<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExecuteQueryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'connection' => ['required', 'string', 'in:mysql,mysql_second'],
            'query' => ['required', 'string', 'max:10000'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $query = trim($this->input('query'));

            $allowedCommands = ['SELECT', 'SHOW', 'DESCRIBE', 'EXPLAIN'];

            $firstWord = strtoupper(explode(' ', $query)[0]);

            if (!in_array($firstWord, $allowedCommands)) {
                $validator->errors()->add(
                    'query',
                    'Only SELECT, SHOW, DESCRIBE, and EXPLAIN commands are allowed. For write operations, admin role is required.'
                );
            }
        });
    }
}
