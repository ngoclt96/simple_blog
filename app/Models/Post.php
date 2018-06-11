<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Post extends BaseModel
{
    protected $table = "posts";

    protected $fillable = [
        'user_id', 'title', 'content', 'approve', 'approver_id', 'deleted', 'deleted_at', 'approved_at'
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
                'type' => 'text'
            ]
        ],
        'title' => [
            'label' => 'Title',
            'default' => false,
            'sort' => 'alpha',
            'search' => [
                'type' => 'text'
            ]
        ],
        'approver_id' => [
            'label' => 'Approver_id',
            'default' => false,
            'sort' => 'numeric',
            'search' => [
                'type' => 'text'
            ]
        ],
        'approve' => [
            'label' => 'Approve',
            'default' => true,
            'search' => [
                'type' => 'selectbox',
                'placeholder' => '---',
                'data' => \App\AppConst\Constants::APPROVER_STATUS
            ]
        ]
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function pluckUser() {
        return DB::table('users')->pluck('name', 'id');
    }

    public $timestamps = true;

    public function scopeAvailablePosts($query)
    {
        $query->where('deleted', 0);
        return $query;
    }
}
