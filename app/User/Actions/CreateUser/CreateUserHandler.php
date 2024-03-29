<?php declare(strict_types=1);

namespace App\User\Actions\CreateUser;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\User\Models\User;
use App\User\Actions\UserDto;
use App\User\Actions\CreateUser\CreateUser;

final class CreateUserHandler
{
    /**
     * Executa a ação
     *
     * @param \App\User\Actions\CreateUser\CreateUser $command
     * @return \App\User\Actions\UserDto
     */
    public function handle(CreateUser $command): UserDto
    {
        try {
            DB::beginTransaction();
            
				// Create user
				$user = User::forceCreate([
					'name' => $command->name,
					'email' => $command->email,
					'password' => Hash::make($command->password),
                    'password_changed_at' => $command->passwordChangedAt
				]);

				// Attach default system role
                foreach(config('roles.default_user_system_roles') as $key => $value) {
                    $user->addRoleToUserByName($value);
                }

				$user->save();
            DB::commit();
			return UserDto::fromModel($user);
		} catch(Exception $e) {
			DB::rollBack();
			throw new Exception( $e->getMessage(), $e->getCode() );
        }
    }
}
