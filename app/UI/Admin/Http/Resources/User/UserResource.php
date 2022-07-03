<?php declare(strict_types=1);

namespace App\UI\Admin\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
			'id' => $this->id,
			'name' => $this->name,
			'email' => $this->email,
			'email_verified_at' => $this->emailVerifiedAt,
			'system_roles' => $this->systemRoles
		];
    }
}
