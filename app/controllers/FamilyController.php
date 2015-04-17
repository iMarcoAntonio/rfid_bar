<?php

class FamilyController extends \BaseController {

    public function getIndex() {
        return View::make('family.index');
    }
    
    /**
     * Gets the objects to display in a Datatable component.
     *
     * @return Response
     */
    public function getDatatable() {
        $es = Family::select('id', 'family_name', 'description');
        return Datatables::of($es)->make();
    }
    
    public function familyCSV() {
            $columns=array('id', 'family_name','description', 'created_at', 'updated_at');
            $headers=array('id', 'Familia','descripción', 'creado', 'modificado');
            CSVGenerate::sendCSV($columns, $headers, "family");
        }


        public function store($id = 0) {
            $input = Input::All();

            $rules = array(
				'family_name' => array('required', 'regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/', 'unique:family')
			);

			$messages = array(
				'family_name.required' 		=> '¡NECESITAMOS SABER EL NOMBRE DE LA FAMILIA!',
				'family_name.regex'		=> '¡EL NOMBRE DE LA FAMILIA DEBE CONTENER ÚNICAMENTE CARACTERES ALFANUMÉRICOS!',
				'family_name.unique'		=> '¡EL NOMBRE DE LA FAILIA YA EXISTE EN LA BASE DE DATOS!'
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
				$family = new Family();
			}
			else {
				$family = Family::find($id);
				if (!$family) {
					return App::abort(403, 'Item not found');
				}
			}
			$family -> family_name = $input['family_name'];
			$family -> description = $input['description'];	
			$family -> save();

			return Response::json($family);
        }

		public function getFamily($id) {
			$p = Family::find($id);
			if ($p !== null) {
				return Response::json($p);
			}
			return App::abort(403, 'Item not found');
		}
		
		public function delete($id) {
			$p = Family::find($id);
			if ($p) {
				$p -> delete();
			}
			return Response::json(array('ok' => 'ok'));
		}
}