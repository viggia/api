<?php declare(strict_types=1);

namespace App\Admin\Http\Controllers\User;

use Illuminate\Http\Response;
use App\Structure\Http\Controllers\Controller;

use App\User\Actions\DeleteUser\DeleteUser;

class DeleteUserController extends Controller
{
    /**
	 * Delete user by id
     *
     * @param  int $userId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $userId): Response
    {
        $this->authorize('delete', auth()->user());
        
        dispatch_sync( new DeleteUser($userId) );
        return response()->noContent();
    }
}
