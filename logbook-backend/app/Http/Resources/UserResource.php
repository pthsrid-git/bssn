<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'guid' => $this->guid,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'fpid' => $this->fpid,
            'nip' => $this->nip,
            'pangkat' => $this->pangkat,
            'jabatan' => $this->jabatan,
            'unit_kerja' => $this->unit_kerja,
            'role' => $this->role,
            'roles' => $this->roles, // From accessor
            'permissions' => $this->getRolePermissions(), // ✅ Manual call method
            'parent_id' => $this->parent_id,
            'kode_unit_organisasi' => $this->kode_unit_organisasi,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
