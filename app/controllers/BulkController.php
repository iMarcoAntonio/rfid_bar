<?php

    class BulkController extends BaseController {

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
            return View::make('bulks.index', array('events' => $events, 'event_id' => $event_id, 'event' => $ev));
        }

        public function store() {
        	$input = Input::all();
        	$validacion = Validator::make($input, 
			[
				'upc'	=> 'required',
				'kg'	=> 'required',
                'inventory' => 'required',
                'epc'   => 'required'
			]);
			if($validacion->fails()){
				return "NO SE PUEDEN GUARDAR LOS DATOS";
			}

            $ev = JourneyEvent::active();
            $bulk_inventory = Bulk::where('epc',$input['epc'])->where('event_id', $ev->id)->first();
            $product = Product::whereUpc($input['upc'])->first();
            //return Response::json($bulk_inventory);

            if(!isset($ev)) return "ERROR AL VINCULAR INVENTARIO CON EVENTO. INTENTE OTRA VEZ!";
            else{
                if(!isset($product)) return "ERROR AL VINCULAR EL PRODUCTO CON LA BASE DE DATOS. INTENTE OTRA VEZ!";
                else{
                    //if(!isset($weight_bottle)) return "NO SE ENCUENTRA EL PESO DE LA BOTELLA LLENA EN LA BASE DE DATOS. INTENTE OTRA VEZ O INTENTE CAPTURARLO!";
                    //else{
                        $dateNow = Carbon\Carbon::now();
                        if($dateNow > $ev->finished_at) return "NO PUEDES HACER INVENTARIO DE COPEO PARA EL EVENTO ACTIVO PORQUE ESTÁ FUERA DE TIEMPO.";
                        else{
                            if($input['inventory'] == 0){
                                $bulk = new Bulk;
                                $bulk->epc  =   $input['epc'];
                                $bulk->upc  =   $input['upc'];
                                $bulk->kg   =   $input['kg'];
                                $bulk->event_id = $ev->id;
                                $bulk->product_id = $product->id;
                                $gLiq = $product->filled_bottle_weight - $product->empty_bottle_weight;
                                $wxcup = ($product->cup_milliliters*$gLiq)/$product->presentation;
                                //return $wxcup;
                                $gLiqRest = $input['kg']-$product->empty_bottle_weight;
                                $bulk->initial_cups = ($gLiqRest/$wxcup);
                                if(!isset($bulk_inventory)){
                                    $bulk->cups_sold = 0;
                                    $bulk->money_obtained = 0;
                                    $bulk->save();
                                    return '¡LOS DATOS SE GUARDARON CORRECTAMENTE!              PRODUCTO: '.$product->product_name.'           NÚMERO DE COPAS: '.round($bulk->initial_cups, 2);
                                }
                                else{
                                    $bulk_inventory->cups_sold = round($bulk_inventory->initial_cups - $bulk->initial_cups);
                                    $bulk_inventory->money_obtained = (int)($product->price_cup * $bulk_inventory->cups_sold);
                                    if(isset($bulk_inventory->final_cups)){
                                        $bulk_inventory->save(); 
                                    }
                                    else{}
                                    return '¡CORRECTO!                         PRODUCTO: '.$product->product_name.'           NÚMERO DE COPAS: '.round($bulk->initial_cups, 2);
                                } 
                            }
                            else{
                                if(isset($bulk_inventory)){
                                    if(!isset($bulk_inventory->final_cups)){
                                        $mxcup = $product->cup_milliliters;
                                        $gLiq = $product->filled_bottle_weight - $product->empty_bottle_weight;
                                        $wxcup = ($mxcup*$gLiq)/$product->presentation;
                                        $gLiqRest = $input['kg']-$product->empty_bottle_weight;
                                        $bulk_inventory->final_cups = $gLiqRest/$wxcup;
                                        $bulk_inventory->cups_sold = (int)($bulk_inventory->initial_cups-$bulk_inventory->final_cups);
                                        $bulk_inventory->money_obtained = (int)($product->price_cup * $bulk_inventory->cups_sold);
                                        $bulk_inventory->save();
                                        return '¡LOS DATOS SE GUARDARON CORRECTAMENTE!              PRODUCTO: '.$product->product_name.'           NÚMERO DE COPAS: '.round($bulk_inventory->final_cups, 2);
                                    }
                                    else return "YA EXISTE INVENTARIO FINAL DE ESTE PRODUCTO.";
                                }
                                else return "NO SE HA HECHO INVENTARIO INICIAL PARA ESTE PRODUCTO.";
                            }                            
                        }
                    //}
                }
            }
        }

        public function bulksDatatables($event_id) {
           $bulks = DB::table('bulks')->join('journey_events', 'bulks.event_id', '=', 'journey_events.id')
                                       ->join('products', 'bulks.product_id', '=', 'products.id')
                                       ->select(array('bulks.id', 'bulks.epc', 'products.product_name', 'products.presentation', 'bulks.initial_cups', 'bulks.final_cups', 'bulks.cups_sold', 'bulks.money_obtained'))->where('journey_events.id', '=', $event_id);
             return Datatables::of($bulks)->make();
        }

        function show($event_id = 0) {
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
            return View::make('bulks.index', array('events' => $events, 'event_id' => $event_id, 'event' => $ev));
        }

        public function getEvent() {
            $event = JourneyEvent::active();
            if(isset($event)){
                $dateNow = Carbon\Carbon::now();
                if($dateNow < $event->finished_at)
                    return $event->event_name;
                else
                    return "FUERA DE TIEMPO";
            }
            else
                return "NULL";
        }

        public function getupc()
        {
             $epc = new Epc('303424defc2e390000000004');
            return $epc->getUpc();
        }

       public function bulksCSV($event_id = 0) {
           $query= DB::table('bulks')->join('journey_events', 'bulks.event_id', '=', 'journey_events.id')
                                       ->join('products', 'bulks.product_id', '=', 'products.id')
                                       ->join('weight_bottle', 'bulks.product_id', '=', 'weight_bottle.product_id')
                                       ->select(array('journey_events.event_name', 'products.product_name', 'bulks.epc', 'bulks.upc', 'bulks.initial_cups', 'bulks.final_cups', 'bulks.cups_sold', 'bulks.money_obtained', 'bulks.created_at'))->where('journey_events.id', '=', $event_id); 
            $columns=array('journey_events.event_name', 'products.product_name', 'bulks.initial_cups', 'bulks.final_cups', 'bulks.cups_sold', 'bulks.money_obtained', 'bulks.created_at');
            $headers=array('EVENTO', 'PRODUCTO', 'COPAS INICIALES', 'COPAS FINALES', 'COPAS VENDIDAS', '$ MONTO OBTENIDO', 'FECHA DE REGISTRO');
            CSVGenerate::downloadCSV($columns, $headers, $query);
        }
    }
