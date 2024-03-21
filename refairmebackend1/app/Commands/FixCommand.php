<?php

namespace App\Commands;

use App\Controllers\ProjectController;
use App\Models\Jobdesc;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use App\Models\Userweight;
use Illuminate\Database\Capsule\Manager as DB;

class FixCommand {

    public function command($args) {
        /*DB::table('users')->update([
            'is_candidate' => true,
            'is_recruiter' => false,
            'is_admin' => false,
            'current_role' => 'candidate'
        ]);

        User::whereIn('email', [
            'swistjan@gmail.com',
            'maciejbal90@gmail.com',
            'dermot@techsorted.com',
            'marcinhalupka@gmail.com',
            'stephen@techsorted.com',
            'piotroxp@gmail.com'
        ])->update([
            'is_recruiter' => true,
            'is_candidate' => true,
            'is_admin' => true,
            'current_role' => 'admin'
        ]);*/

        //$jobs = Jobdesc::all();
        /*foreach ($jobs as $job) {
            $user = User::where('email', '=', $job->posterId)->first();
            if (!is_null($user)) {
                $job->user_id = $user->id;
                $job->save();
            }
        }*/
        /*foreach ($jobs as $job) {
            $keywords = [];
            foreach ($job->skills as $skill) {
                $keywords[] = $skill['name'];
            }
            $job->keywords = implode(',', $keywords);
            $job->salary_min = $job->fund[0];
            $job->salary_max = $job->fund[1];
            $job->save();
        }*/

        /*$projects = Project::all();
        foreach ($projects as $project) {
            $new_perks = [];
            foreach ($project->perks as $perk) {
                if ($perk->available) $new_perks[] = $perk->name;
            }
            $project->perks = $new_perks;
            $project->save();
        }*/
//exit('test');
        foreach (User::with('weights')->get() as $user) {
            if (!isset($user->weights->keywords) || $user->weights->keywords === null) continue;
            $skills = explode(',', $user->weights->keywords);
            $tags = [];
            foreach ($skills as $skill) {
                $tags[] = ['name' => $skill];
            }
            //exit(var_dump($tags));
            Tag::set($user, 'skills', $tags);
        }
    }
}