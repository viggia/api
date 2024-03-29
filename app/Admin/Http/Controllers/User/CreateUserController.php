<?php declare(strict_types=1);

namespace App\Admin\Http\Controllers\User;


use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\Registered;

use App\Structure\Http\Controllers\Controller;

use App\Admin\Http\Resources\User\UserResource;
use App\Admin\Http\Requests\User\CreateUserRequest;

use App\User\Models\User;
use App\User\Actions\CreateUser\CreateUser;

class CreateUserController extends Controller
{    

	/**
     * Creates a new user
     *
     * @param App\Admin\Http\Requests\User\CreateUserRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(CreateUserRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $user = dispatch_sync(new CreateUser(
            $request->name,
            $request->email,
			$request->password,
            null
        )); 
		
        event( new Registered($user) );
		
        return ( new UserResource($user) )->response($request);
    }
}
