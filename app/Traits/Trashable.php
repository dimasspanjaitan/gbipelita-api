<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Trashable {
    public function scopeTrash(Builder $builder, $trashed){
        if($trashed == "only"){
            $builder->onlyTrashed();
        } else if($trashed == "with"){
            $builder->withTrashed();
        } 
        return $builder;
    }
}