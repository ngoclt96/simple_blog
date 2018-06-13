<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Post extends BaseModel
{
    
    const PAGE_RECORD = 5;
    const APPROVER= 1;
    const NON_APPROVER = 0;
    const DELETED = 1;
    const NOT_DELETE = 0;
    const APPROVER_STATUS = [
        self::APPROVER => 'approve',
        self::NON_APPROVER => 'none_approve'
    ];

    
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
                'data' => Post::APPROVER_STATUS
            ]
        ]
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function scopeAvailablePosts($query)
    {
        $permission = request()->session()->get('permission');
        if($permission == 1) {
            $query->where('deleted', self::NOT_DELETE);
        }
        else {
            $query->where([['deleted', self::NOT_DELETE], ['user_id', Auth::user()->id]]);
        }
        
        return $query;
        
    }
}
