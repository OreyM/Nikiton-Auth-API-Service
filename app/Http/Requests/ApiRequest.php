<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class ApiRequest extends FormRequest
{
    public function failedValidation(Validator $validator): void
    {
        $response = response()->json([
            'success' => false,
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'Validation failed.',
            'errors' => $validator->errors(),
        ]);

        throw new HttpResponseException($response);
    }
}
