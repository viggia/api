<?php declare(strict_types=1);

namespace App\Domain\User\Actions;

use App\Domain\User\Models\User;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

final class UserDto
{
    /**
     * Método construtor da classe
     *
     * @param readonly int $id
     * @param readonly string $name
     * @param readonly string $email
     * @param readonly Carbon|null $emailVerifiedAt
     * @param readonly SupportCollection|EloquentCollection $systemRoles
     * @param readonly string $profilePhotoPath
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly Carbon|null $emailVerifiedAt,
        public readonly SupportCollection|EloquentCollection $systemRoles,
        public readonly string $profilePhotoPath,
    ) {}

    public static function fromModel(User $user): self
    {
        $storage = Storage::disk( (isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : 'public') );
        return new self(
            $user->id,
            $user->name,
            $user->email,
            $user->email_verified_at,
            $user->getSystemRoles(),
            ($user->profile_photo_path
                    ? Storage::url($user->profile_photo_path)
                    : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF')
        );
    }
}
