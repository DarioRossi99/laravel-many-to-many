<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    


    protected $fillable = ["name","type_id" , "description", "cover_img", "github_link" ];


    //un tipo può far parte su molti projects
    public function type() {

        return $this->belongsTo(Type::class);

    }

    public function technologies() {

        return $this->belongsToMany(Technology::class);
    }
}