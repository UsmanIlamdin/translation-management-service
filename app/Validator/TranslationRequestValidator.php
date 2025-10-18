<?php

namespace App\Validator;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TranslationRequestValidator
{
    protected array $data;
    protected ?int $id;

    public function __construct(array $data, ?int $id = null)
    {
        $this->data = $data;
        $this->id = $id;
    }

    /**
     * @return array
     * @throws ValidationException
     */
    public function validate(): array
    {
        $rules = [
            'locale' => $this->id ? 'sometimes|string|max:10' : 'required|string|max:10',
            'key' => $this->id
                ? ['sometimes','string','max:255', Rule::unique('translation')->ignore($this->id)]
                : 'required|string|max:255|unique:translation,key',
            'content' => $this->id ? 'sometimes|string' : 'required|string',
            'tags' => 'array',
            'tags.*' => 'string|max:50',
        ];

        $validator = Validator::make($this->data, $rules);

        if ($validator->fails()) {
            abort(response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422));
        }

        return $validator->validated();
    }
}
