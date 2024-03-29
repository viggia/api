<?php declare(strict_types=1);

namespace App\Admin\Http\Controllers\Role;

use Illuminate\Http\Response;
use App\Structure\Http\Controllers\Controller;

use App\Role\Actions\DeleteRole\DeleteRole;

class DeleteRoleController extends Controller
{
    /**
	 * Delete role by id
     *
     * @param  int $roleId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $roleId): Response
    {
        $this->authorize('delete', auth()->user());

        dispatch_sync( new DeleteRole($roleId) );
        return response()->noContent();
    }
}
