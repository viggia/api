<?php declare(strict_types=1);

namespace App\User\Models;

use App\User\Notifications\QueuedVerifyEmail;
use App\User\Notifications\QueuedResetPassword;

use App\Role\Traits\ToUserModel\RoleToUserForSystem;
use App\Role\Traits\ToUserModel\RoleToUserForCompany;
//use App\Role\Traits\ToUserModel\RoleToUserForCompanyBranch;

use App\Company\Traits\ToUserModel\CompaniesToUser;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Database\Factories\User\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    use CompaniesToUser;
	use RoleToUserForSystem;
	use RoleToUserForCompany;
	//use RoleToUserForCompanyBranch;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'current_company_id',
        'current_company_branch_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'can_be_impersonated' => 'boolean',
        'email_verified_at' => 'datetime',
        'password_changed_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_path',
    ];

    /**
     * Overrideen sendEmailVerificationNotification implementation
     * present in Illuminate\Auth\MustVerifyEmail trait
     *
     * @return void
     */
    /*
    public function sendEmailVerificationNotification(): void
    {
		// Configurar serviço de envio de e-mail antes de ativar isso aqui
        $this->notify(new QueuedVerifyEmail);
    }
    */

    /**
     * Overrideen sendPasswordResetNotification implementation
     * present in Illuminate\Auth\Passwords\CanResetPassword trait
     *
     * @return void
     */
    /*
    public function sendPasswordResetNotification($token): void
    {
		// Configurar serviço de envio de e-mail antes de ativar isso aqui
        $this->notify(new QueuedResetPassword($token));
    }
    */

    /**
     * Define custom factory to the model
     *
     * @return \Database\Factories\User\UserFactory
     */
    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

	/*
    |--------------------------------------------------------------------------
	| USER SECTION
	| Contain functions related to user
	| Related model: this
    |--------------------------------------------------------------------------
	*/

    /**
     * Find a user instance by the given email address.
     *
     * @param  string  $email
     * @return self
     */
    public static function findUserByEmail(string $email): self
    {
        return static::where('email', $email)->first();
    }

    /**
     * Find a user instance by the given ID.
     *
     * @param  int  $id
     * @return self
     */
    public static function findUserById(int $id): self
    {
        return static::where('id', $id)->first();
    }

    /**
     * Find a user instance by the given ID or fail.
     *
     * @param  int  $id
     * @return self
     */
    public static function findUserByIdOrFail(int $id): self
    {
        return static::where('id', $id)->firstOrFail();
    }

    /**
     * Find a user instance by the given email address or fail.
     *
     * @param  string  $email
     * @return self
     */
    public static function findUserByEmailOrFail(string $email): self
    {
        return static::where('email', $email)->firstOrFail();
    }

    /**
     * Exclui um usuário
	 * A busca é feita por ID.
     *
     * @param  int $id
     * @return bool
     */
    public function deleteUser($id): bool
    {
        return $this->findUserByIdOrFail($id)->delete();
    }
}
