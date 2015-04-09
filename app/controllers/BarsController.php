<?php

    class BarsController extends \BaseController {

        public function barsList(){
            return View::make('bars.index');
        }

        public function barsDatatables() {
            $bars = Bar::select(array('id', 'bar_name', 'created_at', 'updated_at'));
            return  Datatables::of($bars) -> make();
        }
		
        /**
         * Return specified item.
         *
         * @return Response
         */
        public function getBar($id) {
                $p = Bar::find($id);
                if ($p !== null) {
                        return Response::json($p);
                }
                return App::abort(403, 'Item not found');
        }

        /**
         * Store a resource in storage.
         *
         * @return Response
         */
        public function postIndex($id = 0) {
            $input = Input::All();
            $rules = array(
                'bar_name' => array('required', 'regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/', 'unique:bars')
            );
            $messages = array(
                'bar_name.required'      => '¡NECESITAMOS SABER EL NOMBRE DE LA FAMILIA!',
                'bar_name.regex'     => '¡EL NOMBRE DE LA FAMILIA DEBE CONTENER ÚNICAMENTE LETRAS Y NÚMEROS!',
                'bar_name.unique'        => '¡EL NOMBRE DE LA FAMILIA YA EXISTE EN LA BASE DE DATOS!'
            );
            $validation = Validator::make($input, $rules, $messages);

            if($validation->fails())
            {
                return Response::json(array(
                    'success' => false,
                    'errors'  => $validation->messages()->toArray()
                ));
            }

            if ($id == 0) {
                $bar = new Bar();
            }
            else {
                $bar = Bar::find($id);
                if (!$bar) {
                    return App::abort(403, 'Item not found');
                }
            }
            $bar -> bar_name = $input['bar_name'];
            $bar -> description = $input['description'];
            $bar -> save();
            return Response::json($bar);
        }

        /**
         * Perform a logical delete on an object.
         *
         * @return Response
         */
        public function postDelete($id) {
                $p = Bar::find($id);
                if ($p) {
                        $p -> delete();
                }
                return Response::json(array('ok' => 'ok'));
        }
		
                
        public function barsCSV() {
            $columns=array('id', 'bar_name', 'created_at', 'updated_at');
            $headers=array('id', 'Barra', 'creado', 'modificado');
            CSVGenerate::sendCSV($columns, $headers, "bars");
        }
    }