<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Middleware handles auth
    }

    public function rules(): array
    {
        return [
            'site_name' => ['nullable', 'string', 'max:50'],
            'site_email' => ['nullable', 'email'],
            'currency_symbol' => ['nullable', 'string', 'max:10'],
            'shipping_origin_city' => ['nullable', 'string'],
            'rajaongkir_api_key' => ['nullable', 'string', 'max:100'],
            'midtrans_client_key' => ['nullable', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'max:1024'],
            'favicon' => ['nullable', 'image', 'max:512'],
        ];
    }

    public function attributes(): array
    {
        return [
            'site_name' => 'Nama Situs',
            'site_email' => 'Email Kontak',
            'rajaongkir_api_key' => 'RajaOngkir API Key',
            'midtrans_client_key' => 'Midtrans Client Key',
        ];
    }
}
