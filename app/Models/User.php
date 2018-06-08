<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Notifiable, Authenticatable, Authorizable, CanResetPassword;
    public static $check_attemp = false;
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'permission', 'deleted_at'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];
    public $timestamps = true;
    protected $searchAble = [
        'id' => [
            'label' => 'ID',
            'default' => true,
            'sort' => 'numeric',
            'search' => [
                'type' => ''
            ]
        ],
        'email' => [
            'label' => 'Email',
            'default' => true,
            'sort' => 'alpha',
            'search' => [
                'type' => 'text',
            ]
        ],
        'permission' => [
            'label' => 'Permission',
            'default' => false,
            'sort' => 'alpha',
            'search' => [
                'type' => 'numeric',
                'placeholder' => ''
            ]
        ]
    ];
    /**
     * Get all children department of current user
     */
    public function scopeAvailableUsers($query, $params)
    {
        $query->where('deleted', 0);
        if (isset($params['sort']) && $params['sort']) {
            $key = strtolower(array_keys(getSort())[0]);
            if (array_key_exists($key, $this->searchAble)) {
                unset($params['sort']);
                $query = $query->orderBy("posts." . $key, getSort($key));
            }
        }


        return $query;
    }
}