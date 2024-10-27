<?php
namespace App\Controllers;

use App\Models\Cart;
use Exception;
use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Database\Capsule\Manager as DB;
use SlimSession\Helper as Session;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Carlosocarvalho\SimpleInput\Input\Input;

class AppointmentsController extends Controller
{
    public function get($request,$response, $args)
    {
        $params = $request->getParams();

        $like_columns = ['status'];

        $params = dotify($params);

        if ( empty($params) ) {
            $appointments = isset($with) ? Appointment::with($with)->get() : Appointment::get();
        } else {
            $logic = 'or';

            if ( isset($params['with']) ) {
                $with = explode(',' , $params['with']);
                unset($params['with']);
            }

            if ( isset($params['logic']) ) {
                $logic = $params['logic'];
                unset( $params['logic'] );
            }

            $appointments = Appointment::where( function($query) use ($like_columns, $params, $logic) {
                foreach ($params as $key => $value) {
                    if (in_array($key, $like_columns)) {
                        $operator = 'like';
                        $value = '%' . $value . '%';
                    } else {
                        if($key == 'date'){
                            $operator = 'between';
                            $value = '%'.$value['from'].'% AND %'.$value['to'].'%';
                            //Return Between here
                        }else if($key == 'candidate.id'){
                            $key = 'candidate_id';
                            $operator = '=';
                        }else if($key == 'recruiter.id'){
                            $key = 'recruiter_id';
                            $operator = '=';
                        }else{
                            $operator = '=';
                        }
                    }
                    $logic == 'or' ? $query->orWhere($key, $operator, $value) : $query->where($key,$operator,$value);
                }
            });

            $appointments = isset($with) ? $appointments->with($with) : $appointments;
        }

        return $response->withJson(cc($appointments->toArray()));
    }

    public function create($request,$response, $args)
    {
        $data = sc(json_decode($request->getBody(), true));
        $appointment = new Appointment();

        ( !is_null($data['candidate_id']) ) ? $appointment->candidate_id = $data['candidate_id'] : exit("No candidate id");
        ( !is_null($data['recruiter_id']) ) ? $appointment->recruiter_id = $data['recruiter_id'] : exit("No recruiter id");
        ( !is_null($data['appointment_start']) ) ? $appointment->appointment_start = $data['appointment']['from'] : exit("No date for appointment [from,to]");
        ( !is_null($data['appointment_end']) ) ? $appointment->appointment_end = $data['appointment']['to'] : exit("No date for appointment [from,to]");
        ( !is_null($data['status']) ) ? $appointment->appointment = $data['status'] : exit("No state []");
        $appointment->save();

        return $response->withJson(['message'=>"Succesfully added appointment ".$appointment->id,
            'status'=>"success",
            'appointment'=>$appointment]);
    }

    public function update($request,$response, $args)
    {
        $data = sc($request->getParams());
        $appointment = Appointment::find($args['id']);
        if (is_null($appointment)) throw new Exception('Appointment not found.');

        ( !is_null($data['candidate_id']) ) ? $appointment->candidate_id = $data['candidate_id'] : exit("No candidate id");
        ( !is_null($data['recruiter_id']) ) ? $appointment->recruiter_id = $data['recruiter_id'] : exit("No recruiter id");
        ( !is_null($data['appointment_start']) ) ? $appointment->appointment_start = $data['appointment']['from'] : exit("No date for appointment [from,to]");
        ( !is_null($data['appointment_end']) ) ? $appointment->appointment_end = $data['appointment']['to'] : exit("No date for appointment [from,to]");
        ( !is_null($data['status']) ) ? $appointment->status = $data['status'] : exit("No state []");

        $appointment->save();

        return $response->withJson(['message'=>"Succesfully updated appointment ".$appointment->id,
            'status'=>"success",
            'appointment'=>$appointment]);

    }

    public function delete($request,$response, $args)
    {
        $data = sc($request->getParams());

        $appointment = Appointment::find($args['id']);

        if (is_null($appointment)) throw new Exception('Appointment not found.');

        $appointment->delete();

        return $response->withJson(['message'=>"Sucessfull delete of appointment ".$appointment->id,
            'status'=>"Success" ]);
    }

}