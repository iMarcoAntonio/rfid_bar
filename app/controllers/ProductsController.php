<?php

    class ProductsController extends BaseController {

        public function productsList(){
            return View::make('products.index',array(
                "families"=>Family::get(),
            ));
        }

        public function productsDatatables() {
            
            $products = Product::join(DB::raw("(select id family_id,family_name from family) family"),"products.family_id","=","family.family_id")->select(array('id', 'upc', 'product_name','family_name', 'presentation', 'public_price','real_price', 'cup_milliliters', 'price_cup', 'empty_bottle_weight', 'filled_bottle_weight'));
            return  Datatables::of($products) -> make();
        }
        
        public function productsCSV() {
            $columns=array('id', 'upc', 'product_name', 'created_at', 'updated_at');
            $headers=array('id', 'upc', 'producto', 'creado', 'modificado');
            CSVGenerate::sendCSV($columns, $headers, "products");
        }

        public function inventory(){
            return View::make('products.inventory');
        }

        public function inventoryDatatables(){
            $products = DB::table(DB::raw('(SELECT COUNT(tags_mappings.upc) AS cnt, tags_mappings.upc, products.product_name FROM tags_mappings INNER JOIN products ON tags_mappings.upc = products.upc WHERE tags_mappings.deleted_at IS NULL GROUP BY upc ORDER BY products.product_name) inventory'))
                -> select(array('product_name', 'upc', 'cnt'));
            return  Datatables::of($products) -> make();
        }

        public function index(){
            return $products = Product::all() -> toJson();
        }

        public function store($id = 0) {
            $input = Input::All();

            $rules = array(
                'product_name' => array('required', 'regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/'), //, 'unique:products'
                'upc' => 'required|digits:12',//unique:products
                'product_presentation' => 'digits_between:3,5',
                'product_public_price' => array('regex:/^([0-9.,])+((\s*)+([0-9.,]*)*)+$/'),
                'product_real_price' => array('numeric'),
                'product_cup_milliliters' => 'integer',
                'product_price_cup' => 'numeric',
                'product_empty_weight' => 'integer',
                'product_density' => 'numeric'
            );

            $messages = array(
                'product_name.required'     => '¡NECESITAMOS SABER EL NOMBRE DEL PRODUCTO!',
                'product_name.regex'    => '¡EL NOMBRE DEL PRODUCTO DEBE CONTENER SÓLO LETRAS Y NÚMEROS',
                //'product_name.unique'  => '¡EL NOMBRE DEL PRODUCTO YA EXISTE EN LA BASE DE DATOS!',
                'upc.required'      => '¡NECESITAMOS SABER EL UPC DEL PRODUCTO!',
                'upc.digits'        => '¡EL UPC DEL PRODUCTO DEBE SER UN NÚMERO DE 12 DÍGITOS!',
                //'upc.unique'    => '¡EL UPC DEL PRODUCTO YA EXISTE EN LA BASE DE DATOS!',
                'product_presentation.digits_between'   => '¡LA PRESENTACIÓN DEL PRODUCTO DEBE ESPECIFICARSE CON UN NÚMERO ENTRE 3 y 5 DÍGITOS!',
                'product_public_price.regex'      => '¡EL PRECIO AL PÚBLICO DEL PRODUCTO DEBE SER UN VALOR NUMÉRICO!',
                'product_real_price.regex'      => '¡EL PRECIO REAL DEL PRODUCTO DEBE SER UN VALOR NUMÉRICO!',
                'product_cup_milliliters.integer'   => '¡EL CAMPO MILILITROS POR COPA DEBE SER UN VALOR ENTERO!',
                'product_price_cup.numeric' => '¡EL CAMPO PRECIO PARA COPEO DEBE SER UN VALOR NUMÉRICO!',
                'product_empty_weight.integer' => '¡EL PESO DE LA BOTELLA VACÍA DEBE SER UN VALOR ENTERO!',
                'product_density.numeric'   => '¡LA DENSIDAD DEL PRODUCTO DEBE SER UN VALOR NUMÉRICO!'
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
				$product = new Product();
			}
			else {
				$product = Product::find($id);
				if (!$product) {
					return App::abort(403, 'Item not found');
				}
			}
			$product -> product_name = $input['product_name'];
			$product -> upc = $input['upc'];
			$product -> description = $input['product_description'];
            $product -> family_id = $input['product_family'];
            $product -> presentation = $input['product_presentation'];
            $product -> public_price = str_replace(",","",$input['product_public_price']);
            $product -> real_price = str_replace(",","",$input['product_real_price']);
			$product -> color = $input['product_color'];
			$product -> type = $input['product_type'];
            $product -> cup_milliliters = $input['product_cup_milliliters'];
            $product -> price_cup = $input['product_price_cup'];
            $product -> empty_bottle_weight = $input['product_empty_weight'];
            $product -> density = $input['product_density'];
            $product -> filled_bottle_weight = $input['product_filled_weight'];
			$product -> save();

			return Response::json($product);
        }
		
		public function getProduct($id) {
			$p = Product::find($id);
			if ($p !== null) {
				return Response::json($p);
			}
			return App::abort(403, 'Item not found');
		}
		
		public function delete($id) {
			$p = Product::find($id);
			if ($p) {
				$p -> delete();
			}
			return Response::json(array('ok' => 'ok'));
		}

        public function register_weight(){
            $input = Input::all();
            $validacion = Validator::make($input, 
            [
                'upc'   => 'required',
                'epc'   => 'required',
                'kg'    => 'required'
            ]);
            if($validacion->fails()){
                return "NO SE PUEDEN GUARDAR LOS DATOS";
            }
            $product = Product::where('upc', $input['upc'])->first();
            if(isset($product)){
                if($product->filled_bottle_weight != NULL){
                    $product->filled_bottle_weight = $input['kg'];
                    $product->save();
                    return "LOS DATOS SE GUARDARON CORRECTAENTE."."                               PESO: ".$input['kg']." GRAMOS";
                }
                else return "YA SE REGISTRÓ EL PESO DE LA BOTELLA ANTERIORMENTE.";
            }
            else
                return "LA MARCA DE ESTA BOTELLA NO EXISTE EN LA BASE DE DATOS.";
        }
    }
