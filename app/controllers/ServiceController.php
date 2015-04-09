<?php

    class ServiceController extends BaseController {

        function index($event_id = 0) {
            $ev = JourneyEvent::active();
            if ($event_id == 0) {
                if ($ev->count()) {
                    $event_id = $ev->id;
                }
            } 
            else {
                $ev = JourneyEvent::find($event_id);
            }
            $events = JourneyEvent::get();
            return View::make('services.index', array('events' => $events, 'event_id' => $event_id, 'event' => $ev));
        }

        public function store() {
            $input = Input::all();
            $validacion = Validator::make($input, 
            [
                'epcs'   => 'required'
            ]);
            if($validacion->fails()){
                return "NO SE PUEDEN GUARDAR LOS DATOS";
            }
            if(Count($input['epcs']) > 0)
            {
                return $input['epcs'];
            }
        }

        public function servicesDatatables($event_id) {
            $services = DB::table('services')->select(array('services.employee_waiter_id', 'services.employee_barman_id', 'services.inventory_service_id'));

             return Datatables::of($services)->make();
        }

        function show($event_id = 0) {

        }

        public function getEvent() {
        }
    }
