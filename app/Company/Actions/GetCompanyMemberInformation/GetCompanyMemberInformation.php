<?php declare(strict_types=1);

namespace App\Company\Actions\GetCompanyMemberInformation;

use Illuminate\Foundation\Bus\Dispatchable;

final class GetCompanyMemberInformation
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $companyId
     * @param readonly int $companyMemberId
     *
     * @return void (implicit)
     */
    public function __construct(
		public readonly int $companyId,
		public readonly int $companyMemberId
	) {}
}
