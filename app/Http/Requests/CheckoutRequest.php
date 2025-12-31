<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if (Auth::check()) {
            return [
                'shipping_address' => 'required|string',
                'phone'            => 'required|string',
            ];
        }

        return [
            'name'             => 'required|string',
            'email'            => 'required|email',
            'shipping_address' => 'required|string',
            'phone'            => 'required|string',
        ];
    }
}
