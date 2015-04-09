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
            $bulk_inventory = Bulk::whereEpc($input['epc'])->first();
            $product = Product::whereUpc($input['upc'])->first();
            $weight_bottle = WeightBottle::whereEpc($input['epc'])->first();
            $ev = JourneyEvent::active();

            if(!isset($ev)) return "ERROR AL VINCULAR INVENTARIO CON EVENTO. INTENTE OTRA VEZ!";
            else{
                if(!isset($product)) return "ERROR AL VINCULAR EL PRODUCTO CON LA BASE DE DATOS. INTENTE OTRA VEZ!";
                else{
                    if(!isset($weight_bottle)) return "NO SE ENCUENTRA EL PESO DE LA BOTELLA LLENA EN LA BASE DE DATOS. INTENTE OTRA VEZ O INTENTE CAPTURARLO!";
                    else{
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
                                $gLiq = $weight_bottle->filled_bottle_weight - $product->empty_bottle_weight;
                                $wxcup = ($product->cup_milliliters*$gLiq)/$weight_bottle->milliliters;
                                $gLiqRest = $gLiq-($weight_bottle->filled_bottle_weight-$input['kg']);
                                $bulk->initial_cups = (int)($gLiqRest/$wxcup);
                                if(!isset($bulk_inventory)){
                                    $bulk->cups_sold = 0;
                                    $bulk->money_obtained = 0;
                                    $bulk->save();
                                    return '¡LOS DATOS SE GUARDARON CORRECTAMENTE!              PRODUCTO: '.$product->product_name.'           NÚMERO DE COPAS: '.(int)$bulk->initial_cups;
                                }
                                else{
                                    $bulk_inventory->cups_sold = $bulk_inventory->initial_cups - $bulk->initial_cups;
                                    $bulk_inventory->money_obtained = $product->price_cup * $bulk_inventory->cups_sold;
                                    $bulk_inventory->save();
                                    return '¡CORRECTO!                         PRODUCTO: '.$product->product_name.'           NÚMERO DE COPAS: '.(int)$bulk->initial_cups; 
                                } 
                            }
                            else{
                                if(isset($bulk_inventory)){
                                    if(!isset($bulk_inventory->final_cups)){
                                        $mxcup = $product->cup_milliliters;
                                        $gLiq = $weight_bottle->filled_bottle_weight - $product->empty_bottle_weight;
                                        $wxcup = ($mxcup*$gLiq)/$weight_bottle->milliliters;
                                        $gLiqRest = $gLiq-($weight_bottle->filled_bottle_weight-$input['kg']);
                                        $bulk_inventory->final_cups = (int)($gLiqRest/$wxcup);
                                        $bulk_inventory->cups_sold = $bulk_inventory->initial_cups-$bulk_inventory->final_cups;
                                        $bulk_inventory->money_obtained = $product->price_cup * $bulk_inventory->cups_sold;
                                        $bulk_inventory->save();
                                        return '¡LOS DATOS SE GUARDARON CORRECTAMENTE!              PRODUCTO: '.$product->product_name.'           NÚMERO DE COPAS: '.(int)$bulk_inventory->final_cups;
                                    }
                                    else return "YA EXISTE INVENTARIO FINAL DE ESTE PRODUCTO.";
                                }
                                else return "NO SE HA HECHO INVENTARIO INICIAL PARA ESTE PRODUCTO.";
                            }                            
                        }
                    }
                }
            }
        }

        public function bulksDatatables($event_id) {
           $bulks = DB::table('bulks')->join('journey_events', 'bulks.event_id', '=', 'journey_events.id')
                                       ->join('products', 'bulks.product_id', '=', 'products.id')
                                       ->join('weight_bottle', 'bulks.product_id', '=', 'weight_bottle.product_id')
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
    }
