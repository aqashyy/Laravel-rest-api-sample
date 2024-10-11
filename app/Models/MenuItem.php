<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'alias',
        'linkaddress',
        'target',
        'viewtype',
        'type',
        'parent_id',
        'ordering',
        'menuorderid',
        'state'
    ];

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'type', 'id');
    }
    public function pageitem()
    {
        return $this->belongsTo(PageItem::class, 'linkaddress', 'id');
    }
}
