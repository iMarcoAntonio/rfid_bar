<?php

    class BulkController extends BaseController {

        function index() {
            return View::make('bulks.index',array(
                "families"=>Family::get(),
            ));
        }

        public function store() {
        	$input = Input::all();
        	$validacion = Validator::make($input, 
			[
				'epc'	=> 'required',
				'kg'	=> 'required'
			]);
			if($validacion->fails()){
				return "No se pueden guardar los datos";
			}
			
			$bulk = new Bulk;
			$bulk->epc 	= 	$input['epc'];
			$bulk->kg 	=	$input['kg'];
			$bulk->save();

			return "Los datos se guardaron correctamente";
        }

        public function bulksDatatables() {
      		$bulks = Bulk::select('id', 'epc', 'kg', 'created_at', 'updated_at');
        	return Datatables::of($bulks)->make();
        }
    }
