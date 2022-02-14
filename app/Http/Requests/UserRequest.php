<?php

namespace App\Http\Requests;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'last_name' => '',
            'name' => '',
            'middle_name' => '',
            'birthdate' => '',
            'email' => '',
            'phone' => '',
            'gender' => '',
            'department_id' => '',
            'job' => '',
            'component_columns' => '',
            'note' => '',
            'is_active' => '',
            'avatar' => '',
            'password' => '',
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
            ['field' => 'last_name', 'label' => 'Фамилия'],
            ['field' => 'name', 'label' => 'Имя'],
            ['field' => 'email', 'label' => 'E-mail (логин в систему)'],
            ['field' => 'department.name', 'label' => 'Отдел', 'filterOptions' => (object)['enabled' => true, 'placeholder' => 'все', 'filterDropdownItems' => Department::pluck('name')]],
            ['field' => 'phone', 'label' => 'Телефон'],
            ['field' => 'role', 'label' => 'Роль'],
            ['field' => 'gender', 'label' => 'Пол', 'filterOptions' => (object)['enabled' => true, 'placeholder' => 'все', 'filterDropdownItems' => [(object)['value' => 0, 'text' => 'женский'], (object)['value' => 1, 'text' => 'мужской']]]],
//            ['field' => 'is_active', 'label' => 'Активен', 'filterOptions' => (object)['enabled' => true, 'placeholder' => 'все', 'filterDropdownItems' => [(object)['value' => 0, 'text' => 'не активен'], (object)['value' => 1, 'text' => 'активен']]]],
        ];
    }
}
