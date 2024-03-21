<?php
namespace App\Controllers;

class ConsoleController extends Controller {

    public function runCommand($request) {
        if (!isset($_SESSION['user']) || $_SESSION['user']->is_admin == false) exit();
        $params = $request->getParams();
        if (isset($params['q'])) unset($params['q']);
        $command = $_SESSION['app']['settings']['commands'][$params['command']];
        $command = new $command();
        unset($params['command']);
        $command->command($params);
    }

}