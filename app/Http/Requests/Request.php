<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

/**
 * Class Request
 * @package App\Http\Requests
 */
class Request extends FormRequest
{
    /**
     * Get request contain only input in rule.
     * @var bool
     */
    protected $strict = false;

    /**
     * Set new input.
     * @param $value
     * @param $name
     */
    public function set($name, $value)
    {
        $inputs = $this->all();
        Arr::set($inputs, $name, $value);
        $this->replace($inputs);
    }

    /**
     * Unset a input by name.
     * @param $name
     */
    public function remove($name)
    {
        $this->replace(Arr::except($this->all(), $name));
    }

    /**
     * Prepare the data for validation.
     * @return void
     */
    protected function prepareForValidation()
    {
        // Run set default input.
        $this->setDefaultInputs();

        // Run method sanitize if it exists.
        if (method_exists($this, 'sanitize')) {
            $this->sanitize($this);
        }

        // Apply strict mode.
        if ($this->strict) {
            $this->setStrictMode();
        }
    }

    /**
     * Set defaults input.
     */
    protected function setDefaultInputs()
    {
        $defaults = method_exists($this, 'defaults') ? $this->defaults($this) : [];

        foreach ($defaults as $input => $value) {
            if (!$this->has($input)) {
                $this[$input] = $value;
            }
        }
    }

    /**
     * Set strict mode.
     */
    protected function setStrictMode()
    {
        $allowInputs = [];

        if (method_exists($this, 'rules')) {
            $allowInputs = array_keys($this->rules());

            array_walk(
                $allowInputs,
                function (&$item) {
                    list($item) = explode('.', $item);
                }
            );
        }

        $this->replace(Arr::only($this->all(), $allowInputs));
    }

    /**
     * Get attribute name.
     *
     * @param $key
     * @return mixed
     */
    protected function getAttributeLabel($key)
    {
        if (!method_exists($this, 'attributes')) {
            return $key;
        }
        $attributes = $this->attributes();

        return $attributes[$key] ?? $key;
    }

    /**
     * Append validate logic.
     *
     * @param Validator $validator
     */
    public function withValidator(Validator $validator)
    {
        if (method_exists($this, 'extend')) {
            $validator->after(
                function (Validator $validator) {
                    $this->extend($validator);
                }
            );
        }
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            // Define rules.
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
