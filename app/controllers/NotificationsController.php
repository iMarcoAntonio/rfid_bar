<?php

    class NotificationsController extends BaseController {

        function index($event_id = 0) {
        }

        public function checkNotifications() {
            if(Auth::user() -> user_type == 4){
                $notification = Notification::whereBand(1)->first();
                if (!is_null($notification)) {
                    $service = new Service();
                    $waiter = User::whereEpc($notification->employee_waiter_epc)->first();
                    $product = Product::whereId($notification->inventory_service_upc)->first();
                    $barman = User::whereEpc($notification->employee_waiter_epc)->first();
                    $waiter_name = ' ';
                    $service_name = ' ';
                    $barman_name = ' ';
                    if(!is_null($waiter)){
                        $waiter_name = $waiter->username;
                        $service->waiter_name = $waiter->username;
                    }
                    if(!is_null($product)){
                        $service_name = $product->product_name . ' - PRESENTACIÓN ' . $product->presentation . ' ml.';
                        $service->inventory_service_id = $product->id;
                    }
                    if(!is_null($barman)){
                        $barman_name = $barman->username;
                        $service->barman_name = $barman->username;
                    }

                    return Response::json(['service_name' => $service_name, 'service' => $service]);
                }
            }
            return App::abort(403, 'Item not found');
        }

        public function store() {
            return "HOLA";
            $input = Input::all();
            $validacion = Validator::make($input, 
            [
                'epcs'   => 'required'
            ]);
            if($validacion->fails()){
                return "NO SE PUEDEN GUARDAR LOS DATOS";
            }
            $notificationsCount = DB::table('notifications')->count();
            $notification = new Notification;
            $ev = JourneyEvent::active();
            if($notificationsCount < 1)
            {
               foreach ($input['epcs'] as $epc) {
                    $employee = Employee::whereEpc($epc)->first();
                    if(is_null($employee)){
                        $prodUpc = InventoryEpc::whereEpc($epc)->first();
                        if(!is_null($prodUpc)){
                            $producto = Product::whereUpc($prodUpc->upc)->first();
                            $notification->inventory_service_upc = $producto->upc;
                        }
                    }
                    else
                    {
                        if($employee->employee_position_id == 1){
                            $notification->employee_waiter_epc = $epc;
                        }
                        else
                        {
                            $notification->employee_barman_epc = $epc;
                        }
                    }
                }
                $notification->event_id = $ev->id;
                $notification->save();
                return $notification;
            }
            else
                return "YA EXISTEN NOTIFICACIONES.";
        }
    }
