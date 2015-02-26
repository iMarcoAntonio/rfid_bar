<?php

    class ProductsController extends BaseController {

        public function productsList(){
            return View::make('products.index',array(
                "families"=>Family::get(),
            ));
        }

        public function productsDatatables() {
            
            $products = Product::join(DB::raw("(select id family_id,family_name from family) family"),"products.family_id","=","family.family_id")->select(array('id', 'upc', 'product_name','family_name', 'presentation', 'public_price','real_price', 'cup_milliliters', 'price_cup', 'empty_bottle_weight', 'created_at', 'updated_at'));
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
			$product -> upc = $input['product_upc'];
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
                $weight_bottle = WeightBottle::where('epc', $input['epc'])->first();
                if(!isset($weight_bottle)){
                    $weight_bottle =  new WeightBottle;
                    $weight_bottle->product_id = $product->id;
                    $weight_bottle->filled_bottle_weight = $input['kg'];
                    $weight_bottle->milliliters = (int)(($weight_bottle->filled_bottle_weight-$product->empty_bottle_weight)/1);
                    $weight_bottle->cups = (int)($weight_bottle->milliliters/$product->cup_milliliters);
                    $weight_bottle->epc = $input['epc'];
                    $weight_bottle->save();
                    return "LOS DATOS SE GUARDARON CORRECTAENTE."."                               PESO: ".$input['kg']." GRAMOS";
                }
                else return "YA SE REGISTRÓ EL PESO DE LA BOTELLA ANTERIORMENTE.";
            }
            else
                return "LA MARCA DE ESTA BOTELLA NO EXISTE EN LA BASE DE DATOS.";
        }
    }
