<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank_card extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id','created_at','updated_at'];

    /**
     * 一对多反向关联用户表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function User()
    {
        return $this->belongsTo('App\models\User');
    }
}
