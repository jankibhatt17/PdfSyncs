<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AgentScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $user = auth()->user();

            if(auth()->check() && request()->is('admin/*') && $user->roles->contains(2))
        {
         
            $builder->where('status_id', 1);
            
        }



        // if(auth()->check() && request()->is('admin/*') && $user->roles->contains(2))
        // {
         
        //     $builder->whereJsonContains('user', "$user->id");
            
        // }
    }
}