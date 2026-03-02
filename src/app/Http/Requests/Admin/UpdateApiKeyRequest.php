<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class UpdateApiKeyRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'partner_name' => ['sometimes', 'required', 'string', 'max:255'],
            'partner_email' => ['nullable', 'email'],
            'plan_id' => ['sometimes', 'required', 'exists:plans,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:users:read,users:create,users:update,users:delete,stats:read'],
            'expires_at' => ['nullable', 'date'],
            'ip_whitelist' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function customMessages(): array
    {
        return [
            'partner_name.required' => 'Le nom du partenaire est requis.',
            'plan_id.required' => 'Le plan est requis.',
            'plan_id.exists' => 'Le plan sélectionné n\'existe pas.',
        ];
    }
}
