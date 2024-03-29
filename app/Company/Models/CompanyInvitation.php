<?php declare(strict_types=1);

namespace App\Company\Models;

use App\User\Models\User;
use App\Company\Models\Company;

use Database\Factories\Company\CompanyInvitationFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyInvitation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_invitations';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'email',
        'roles',
        'token',
        'expires_in'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'roles' => 'array',
        'expired_at' => 'datetime'
    ];

    /**
     * Define custom factory to the model
     *
     * @return void
     */
    protected static function newFactory()
    {
        return CompanyInvitationFactory::new();
    }

	/**
     * Get the company of this member
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Retorna dados do usuário convidado, caso exista
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invitedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
    
    /**
     * Retorna informações de determinado convite.
	 * A busca é feita por ID.
     *
     * @param  int $inviteId
     * @return self
     */
    public function getInviteById(int $inviteId): self
    {
        return $this->where('id', $inviteId)->firstOrFail();
    }

    /**
     * Exclui determinado convite
	 * A busca é feita por ID.
     *
     * @param  int $inviteId
     * @return bool
     */
    public function removeInvite(int $inviteId): bool
    {
        return $this->getInviteById($inviteId)->delete();
    }
}
