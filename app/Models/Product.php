<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const BLOCKED = 'blocked';

    protected $table = 'products';

    protected $fillable = [
        'id',
        'admin_id',
        'title',
        'description',
        'main_image',
        'status',
        'published_at',
    ];

    protected $dates = [
        'published_at',
        'deleted_at',
    ];

    public function admin(){
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function getAdminNameAttribute(){
        return $this->admin->name;
    }
}
