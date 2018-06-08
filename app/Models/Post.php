<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Post extends BaseModel
{
    protected $table = "posts";

    protected $fillable = [
        'user_id', 'title', 'content', 'approver_id', 'deleted', 'deleted_at', 'approved_at'
    ];

    protected $searchAble = [
        'id' => [
            'label' => 'ID',
            'default' => false,
            'sort' => 'numeric',
            'search' => [
                'type' => ''
            ]
        ],
        'user_id' => [
            'label' => 'User_id',
            'default' => false,
            'sort' => 'numeric',
            'search' => [
                'type' => ''
            ]
        ],
        'title' => [
            'label' => 'Title',
            'default' => false,
            'sort' => 'alpha',
            'search' => [
                'type' => ''
            ]
        ],
        'approver_id' => [
            'label' => 'Approver_id',
            'default' => false,
            'sort' => 'numeric',
            'search' => [
                'type' => ''
            ]
        ]
    ];

    public function users() {
        return $this->belongsTo('App\Models\User');
    }

    public function pluckUser() {
        return DB::table('users')->pluck('name', 'id');
    }

    public $timestamps = true;

    public function scopeAvailablePosts($query, $params)
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
