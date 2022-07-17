<?php declare(strict_types=1);

namespace App\UI\MyselfUser\Providers;

use App\Domain\User\Actions\GetUser\GetUserById\GetUserByIdCommand;
use App\Domain\User\Actions\GetUser\GetUserById\GetUserByIdHandler;

use App\Domain\User\Actions\GetUser\GetUserByEmail\GetUserByEmailCommand;
use App\Domain\User\Actions\GetUser\GetUserByEmail\GetUserByEmailHandler;

use App\Domain\User\Actions\UpdateUserPassword\UpdateUserPasswordCommand;
use App\Domain\User\Actions\UpdateUserPassword\UpdateUserPasswordHandler;

use App\Domain\User\Actions\UpdateUserProfilePhoto\UpdateUserProfilePhotoCommand;
use App\Domain\User\Actions\UpdateUserProfilePhoto\UpdateUserProfilePhotoHandler;

use App\Domain\User\Actions\DeleteUserProfilePhoto\DeleteUserProfilePhotoCommand;
use App\Domain\User\Actions\DeleteUserProfilePhoto\DeleteUserProfilePhotoHandler;

use App\Domain\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformationCommand;
use App\Domain\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformationHandler;

//use Illuminate\Support\Facades\Event;
//use App\Domain\User\Events\UserCreated;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class MyselfUserServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Inicia os serviços
     *
     * @return void
     */
    public function boot(): void
    {
        // register policies
        $this->registerPolicies();

        // map routes
        $this->map();

        // commands and handlers
        $this->registerCommandsAndHandlers();

        // events
        $this->registerEventsAndHandlers();
    }

    /**
     * Mapeia os comandos e handlers respectivos
     *
     * @return void
     */
    public function registerCommandsAndHandlers(): void
    {
        Bus::map([
            GetUserByIdCommand::class => GetUserByIdHandler::class,
            GetUserByEmailCommand::class => GetUserByEmailHandler::class,
            UpdateUserPasswordCommand::class => UpdateUserPasswordHandler::class,
            UpdateUserPersonalInformationCommand::class => UpdateUserPersonalInformationHandler::class,
            UpdateUserProfilePhotoCommand::class => UpdateUserProfilePhotoHandler::class,
            DeleteUserProfilePhotoCommand::class => DeleteUserProfilePhotoHandler::class
        ]);
    }


    /**
     * Mapeia os eventos e handlers respectivos
     *
     * @return void
     */
    public function registerEventsAndHandlers(): void
    {
        /*
        Event::listen(
            UserCreated::class,
            [CreatePersonalCompany::class, 'handle']
        );
        */
    }
    /**
     * Registra os serviços utilizados por esse provedor de serviços.
     *
     * @return void
     */
    public function register(): void {}

    /**
     * Define as rotas do provedor de serviços.
     *
     * @return void
     */
    public function map(): void
    {
        Route::middleware(['web'])
			->group(base_path('routes/myself-user/web.php'));

        Route::middleware(['api'])
            ->group(base_path('routes/myself-user/api.php'));

        require base_path('routes/myself-user/channels.php');
        require base_path('routes/myself-user/console.php');
    }
}
