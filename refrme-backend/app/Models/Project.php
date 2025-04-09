<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

    ///There is no contractType
    //Not anymore
    //There never was
    //Never will be
    //except in the db
    protected $table = 'projects';

    /*public static function boot() {
        parent::boot();

        self::saving(function($model){
            if (is_array($model->exp)) $model->exp = json_encode($model->exp);
            if (is_array($model->fund)) $model->fund = json_encode($model->fund);
            if (is_array($model->keywords)) $model->keywords = join(',', $model->keywords);
            if (is_array($model->currency)) $model->currency = json_encode($model->currency);
            if (is_array($model->contract_type)) $model->currency = json_encode($model->contract_type);
        });
    }*/

    protected $casts = [
        'breakdown' => 'array',
        'methodology' => 'array',
        'perks' => 'array',
        'stack' => 'array',
        'contractType' => 'array',
        'projectType' => 'array',
        'workload'=> 'array',
        'requiredSkills'=> 'array',
    ];

    /*  public $id;
      public $staff;
      public $name;
      public $description;
      public $stage;
      public $stack;
      public $methodology;
      public $breakdown;
      public $posterId;
      public $companyId;
      public $contractType;

      //New fields from project.update.sql
      public $logo;
      public $projectType;
      public $workload;
      public $requiredSkills;
      public $perks;
    */

/*    protected $fillable = [
        'staff',
        'companyId',
        'name',
        'description',
        'contractType',
        'stage',
        'stack',
        'methodology',
        'breakdown',
        'posterId',
        'logo',
        'projectType',
        'workload',
        'requiredSkills',
        'perks'
    ];*/

}