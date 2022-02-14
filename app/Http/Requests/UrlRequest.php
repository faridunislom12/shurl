<?php

namespace App\Http\Requests;

use App\Models\Url;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UrlRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => '',
            'short' => '',
            'long' => '',
            'is_active' => '',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            ['field' => 'user.name', 'label' => 'Создатель', 'filterOptions' => (object)['enabled' => true, 'placeholder' => 'все', 'filterDropdownItems' => User::pluck('name')]],
            ['field' => 'short', 'label' => 'Короткая'],
            ['field' => 'long', 'label' => 'Длинная'],
            ['field' => 'is_active', 'label' => 'Активен', 'filterOptions' => (object)['enabled' => true, 'placeholder' => 'все', 'filterDropdownItems' => [(object)['value' => 0, 'text' => 'не активен'], (object)['value' => 1, 'text' => 'активен']]]],
        ];
    }
}
