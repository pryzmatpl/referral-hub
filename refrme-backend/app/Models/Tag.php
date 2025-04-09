<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

    protected $table = 'tags';

    public $timestamps = false;

    //protected $appends = ['exp', 'years'];

    public function users() {
        return $this->belongsToMany('App\Models\User');
    }

    public function jobs() {
        return $this->belongsToMany('App\Models\JobDesc');
    }

    public static function set($entity, $type, $tags) {
        //$tags = json_decode($tags, true);
        $attach = [];

        foreach($tags as $tag) {
            if (!isset($tag['name']) || $tag['name'] == '') continue;
            $tg = Tag::where([
                'name' => $tag['name'],
                'type' => $type
            ])->first();

            if (is_null($tg)) {
                $tg = new Tag();
                $tg->name = $tag['name'];
                $tg->type = $type;
                $tg->save();
            }

            $new_attach = [];
            if (isset($tag['exp'])) $new_attach['exp'] = $tag['exp'];
            if (isset($tag['years'])) $new_attach['years'] = $tag['years'];
            $attach[$tg->id] = $new_attach;
        }
        $detach = [];
        foreach ($entity->$type as $old_tag) {
            $detach[] = $old_tag->id;
        }
        $entity->$type()->detach($detach);
        $entity->$type()->attach($attach);
    }

}