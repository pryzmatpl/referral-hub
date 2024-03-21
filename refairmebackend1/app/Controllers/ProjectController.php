<?php

namespace App\Controllers;

use App\Models\Project;

class ProjectController extends Controller {

    public function getProjects($request, $response, $args) {
        try {
            //TODO If a project does not exist, it will return success. DB integrity, anyone -_-....
            $projects = Project::all();
            return $response->withJson(cc($projects->toArray()));
        } catch (Exception $e) {
            return json_encode($e);
        }
    }

    public function getProject($request, $response, $args){
        try {
            //TODO If a project does not exist, it will return success. DB integrity, anyone -_-....
            $project = Project::where('id',$args['id'])->get()[0];
            return $response->withJson(cc($project->toArray()));
        } catch (Exception $e) {
            return json_encode($e);
        }
    }

    public function getCompanyProjects($request, $response, $args) {
        try {
            //TODO If a project does not exist, it will return success. DB integrity, anyone -_-....
            //TODO - fix this
            $cid = $args['company'];
            $projects = Project::where('company_id', $cid)->get();
            return $response->withJson(cc($projects->toArray()));
        } catch (Exception $e) {
            return json_encode($e);
        }
    }

    public function add($request, $response, $args) {
        try {
            $data = sc(json_decode($request->getBody(),true)['data']);

            $project = new Project();
            $project->user_id = $_SESSION['user']->id;
            $project->company_id = $data['company_id'];
            $project->name = $data['name'];
            $project->description = $data['description'];
            $project->contract_type = $data['contract_type'];
            $project->breakdown = $data['breakdown'];
            $project->stack = $data['stack'];
            $project->methodology = $data['methodology'];
            $project->logo = $data['logo'];
            $project->project_type = $data['project_type'];
            $project->workload = $data['workload'];
            $project->required_skills = $data['required_skills'];
            $project->perks = $data['perks'];
            $project->stage = $data['stage'];
            $project->staff = $data['staff'];
            $project->save();

            return $response->withJson([
                'message'=>"Successfully added project for company #" . $data['data']['company_id'],
                'status' => "success",
                'project'=>  cc($project->toArray())
            ]);

        } catch (Exception $e) {
            return json_encode($e);
        }
    }

    public function update($request, $response, $args) {
        try {
            $data = sc(json_decode($request->getBody(),true)['data']);
            $id = $args['id'];

            $project = Project::where('id', $id);
            if (is_null($project)) throw new \Exception('');

            if (isset($data['staff'])) $project->staff = $data['staff'];
            if (isset($data['description'])) $project->description = $data['description'];
            if (isset($data['contract_type'])) $project->contract_type = $data['contract_type'];
            if (isset($data['name'])) $project->name = $data['name'];
            if (isset($data['stack'])) $project->stack = $data['stack'];
            if (isset($data['logo'])) $project->logo = $data['logo'];
            if (isset($data['project_type'])) $project->project_type = $data['project_type'];
            if (isset($data['workload'])) $project->workload = $data['workload'];
            if (isset($data['required_skills'])) $project->required_skills = $data['required_skills'];
            if (isset($data['perks'])) $project->perks = $data['perks'];
            if (isset($data['stage'])) $project->stage = $data['stage'];
            $project->save();

            return $response->withJson([
                'message'=>"Successfully modified project for company #" . $project->company_id,
                'status' => "success",
                'project'=> cc($project->toArray())
            ]);



        } catch (Exception $e) {
            return json_encode($e);
        }
    }

    public function delete($request, $response, $args) {
        try {
            //TODO If a project does not exist, it will return success. DB integrity, anyone -_-....
            $id = $args['id'];
            Project::where('id', $id)->delete();
            return $response->withJson([
                'message'=>"Successfully removed project " . $id,
                'status' => "success"
            ]);
        } catch (Exception $e) {
            return json_encode($e);
        }
    }

}