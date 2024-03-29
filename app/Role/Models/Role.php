<?php declare(strict_types=1);

namespace App\Role\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Database\Factories\Role\RoleFactory;

class Role extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Search for a function by name and check if it exists
     *
     * @param string $role
     * @return bool
    */
    public function hasRoleByName(string $role): bool
    {
        return static::where('name', $role)->count() > 0;
    }

    /**
     * Retorna informações de determinada "role".
	 * A busca é feita por ID.
     *
     * @param  int $roleId
     * @return self
     */
    public function roleById($roleId): self
    {
        return $this->where('id', $roleId)->firstOrFail();
    }

    /**
     * Exclui determinada "role"
	 * A busca é feita por ID.
     *
     * @param  int $roleId
     * @return bool
     */
    public function removeRole($roleId): bool
    {
        return $this->roleById($roleId)->delete();
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
    */
    protected static function newFactory(): Factory
    {
        return RoleFactory::new();
    }
}
