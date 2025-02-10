<?php

namespace App\Controllers;

use App\Models\Tag;
use App\Models\User;
use App\Models\Userweight;
use Exception;

class UserController extends Controller {

    public function get($request, $response, $args) {
        try {
            $data = sc($request->getParams());
            if ($_SESSION['user']->is_admin && isset($data['id'])) {
                $user = User::where(['id' => $data['id']])->with('skills', 'weights')->first();
            } else {
                $user = User::where(['id' => $_SESSION['user']->id])->with('skills', 'weights')->first();
            }
            return $response->withJson(cc($user->toArray()));
        } catch (Exception $e) {
            return json_encode($e);
        }
    }

    public function update($request, $response, $args) {
        try {
            $data = sc($request->getParams());
            if ($_SESSION['user']->is_admin && isset($data['id'])) {
                $user = User::find($data['id']);
            } else {
                $user = User::find($_SESSION['user']->id);
            }

            if (is_null($user)) throw new Exception('User not found.');

            if (isset($data['first_name'])) $user->first_name = $data['first_name'];
            if (isset($data['last_name'])) $user->last_name = $data['last_name'];
            if (isset($data['salary_expectation'])) $user->salary_expectation = $data['salary_expectation'];
            if (isset($data['notice_period'])) $user->notice_period = $data['notice_period'];
            if (isset($data['scheduling'])) $user->scheduling = $data['scheduling'];
            if (isset($data['exp'])) $user->exp = $data['exp'];
            if (isset($data['skills'])) {
                Tag::set($user, 'skills', $data['skills']);
            }
            if (isset($data['weights'])) {
                $weight = new Userweight();
                $weight->aone = $data['weights'][0];
                $weight->atwo = $data['weights'][1];
                $weight->athree = $data['weights'][2];
                $weight->afour = $data['weights'][3];
                $weight->afive = $data['weights'][4];
                $weight->asix = $data['weights'][5];
                $weight->aseven = $data['weights'][6];
                $weight->aeight = $data['weights'][7];
                $weight->anine = $data['weights'][8];
                $weight->aten = $data['weights'][9];
                $weight->aeleven = $data['weights'][10];
                $weight->userid = $_SESSION['user']->id;
                $weight->save();
            }
            $user->save();

            return $response->withJson(['message' => 'User updated.']);
        } catch (Exception $e) {
            return json_encode($e);
        }
    }
}