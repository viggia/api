<?php declare(strict_types=1);

namespace App\Domain\Company\Actions;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\CompanyMember;

final class CompanyMemberInformationDto
{
    /**
     * Método construtor da classe
     *
     * @param readonly int $id
     * @param readonly int $userId
     * @param readonly int $companyId
     * @param readonly array $roles
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly int $id,
        public readonly int $userId,
        public readonly int $companyId,
        public readonly bool $isCompanyOwner,
        //public readonly array $roles
    ) {}
	
    public static function fromModel(CompanyMember $companyMember): self
    {
        return new self(
            $companyMember->id,
			$companyMember->user_id,
            $companyMember->owner_user_id,
            $companyMember->user_id === $companyMember->company->owner_user_id,
			//$companyMember->roles
        );
    }
}
