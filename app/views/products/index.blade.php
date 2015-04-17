@extends('templates.base')

@section('css')
    <link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="/css/mydatatables.css">
@stop

@section('content')
<div class="tab-header">Productos</div>
<div class="content-container">
    <div class="table-responsive container" style="width: 100%; padding: 10px;">
        <button class="btn btn-sm" data-toggle="modal" data-target="#smwModal" id="add_product">Agregar Producto</button>
        <button class="btn btn-sm" style="float: right;" id="get_csv">CSV</button>
        <table class="table" id="reads">
            <caption style="font-size: 18px; font-weight: bold;">Listado de Productos</caption>
            <thead>
                <tr>
                    <th style="width:100px">&nbsp;</th>
                    <th>id</th>
                    <th>upc</th>
                    <th style="width:250px">producto</th>
                    <th>familia</th>
                    <th>Presentación en mililitros</th>
                    <th>precio a publico</th>
                    <th>precio real</th>
                    <th>Mililitros por copa</th>
                    <th>Precio para copeo</th>
                    <th>Peso vacía</th>
                    <th>Peso Llena</th>
                </tr>
            </thead>
        </table>
    </div>

    <div style="display: none;" id="add-product">
        <form role="form">
            <div class="form-group">
                <label for="product_name">* Nombre del Producto:</label>
                <input type="text" class="form-control" id="product_name" placeholder="Nombre del Producto">
            </div>
            <div class="form-group">
                <label for="product_upc">* UPC:</label>
                <input type="text" class="form-control" id="product_upc" placeholder="UPC">
            </div>
            <div class="form-group">
                <label for="product_family">* Familia:</label>
                <select id="product_family" class="form-control">
                    @foreach($families as $family)
                    <option value="{{ $family->id }}">{{ $family->family_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="product_real_price">* Presentación en mililitros</label>
                <input type="text" class="form-control" id="product_presentation" placeholder="Presentación en mililitros" />
            </div>
            <div class="form-group">
                <label for="product_public_price">* Precio al Publico:</label>
                <input type="text" class="form-control" id="product_public_price" placeholder="Precio al Publico" />
            </div>
            <div class="form-group">
                <label for="product_real_price">* Precio Real:</label>
                <input type="text" class="form-control" id="product_real_price" placeholder="Precio Real" />
            </div>
            <div class="form-group">
                <label for="product_real_price">* Mililitros por copa</label>
                <input type="text" class="form-control" id="product_cup_milliliters" placeholder="Mililitros por cada copa" />
            </div>
            <div class="form-group">
                <label for="product_real_price">* Precio para copeo</label>
                <input type="text" class="form-control" id="product_price_cup" placeholder="Precio de copeo" />
            </div>
            <div class="form-group">
                <label for="product_real_price">* Peso de botella vacía (gramos)</label>
                <input type="text" class="form-control" id="product_empty_weight" placeholder="Peso de botella vacía" />
            </div>
            <div class="form-group">
                <label for="product_real_price">* Peso de botella llena (gramos)</label>
                <input type="text" class="form-control" id="product_filled_weight" placeholder="Peso de botella llena" />
            </div>
            <div class="form-group">
                <label for="product_density">* Densidad:</label>
                <input type="text" class="form-control" id="product_density" placeholder="Densidad" />
            </div>
            <div class="form-group">
                <label for="product_description">Descripción del producto:</label>
                <textarea id="product_description" placeholder="Descripción del producto" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="product_color">Color:</label>
                <input type="color" class="form-control" id="product_color" placeholder="#FFFFFF">
            </div>
            <div class="form-group">
                <label for="product_type">Tipo de Producto:</label>
                <label for="product_type_1">
                <input type="radio" class="form-inline" name="product_type" id="product_type_1" value="1" checked="checked">Servicio
				</label>
                <label for="product_type_2">
                <input type="radio" class="form-inline" name="product_type" id="product_type_2" value="2">Barra
				</label>
            </div>
            <p style="text-decoration: underline;">* Indica campos obligatorios</p>
        </form>
    </div>
</div>

<div class="modal hide" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-header">
		<h1>Processing...</h1>
	</div>
	<div class="modal-body">
		<div class="progress progress-striped active">
			<div class="bar" style="width: 100%;"></div>
		</div>
	</div>
</div>	
@stop

@section('javascripts')
    <script type="text/javascript" src="/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="/js/datatables.js"></script>
    <script type="text/javascript" src="/js/accounting.min.js"></script>
@stop

@section('scripts')
    <script>
        
        $(document).ready(function() {
            var dt = $('#reads').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": '/products/datatable',
                //"sDom": "<'row'<'col-xs-6'T><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
                "sPaginationType": "bs_full",
                "fnDrawCallback" : (typeof dataTableDrawCallBack === 'undefined'?function(){}:dataTableDrawCallBack),
                "oLanguage": {
                    "sLengthMenu": "Mostrar _MENU_ registros por pagina",
                    "sZeroRecords": "No se encontraron registros",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sSearch": "Buscar",
                    "oPaginate": {
                        "sFirst":'<i class="glyphicon glyphicon-step-backward"></i>',
                        "sLast":'<i class="glyphicon glyphicon-step-forward"></i>',
                        "sNext":'<i class="glyphicon glyphicon-forward"></i>',
                        "sPrevious":'<i class="glyphicon glyphicon-backward"></i>'
                    }
                },
				"aoColumns": [
					{   
						"bSortable": false,
						"bSearchable": false,
						"mData": 0,
						"fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							var id = sData,
								o = $(nTd),
								bar = $('<div />').addClass('action-buttons').addClass('btn-group').append(
									$('<span />').addClass('product-id').hide().text(id)
								).append(
									$('<button />').addClass('btn btn-info btn-sm action-edit').append('<span class="glyphicon glyphicon-pencil" />')
								).append(
									$('<button />').addClass('btn btn-danger btn-sm action-delete').append('<span class="glyphicon glyphicon-remove" />')
								);
							o.html('').append(bar);
						}
					},
					{ "mData": 0 },
					{ "mData": 1 },
					{ "mData": 2 },
                    { "mData": 3 },
					{ "mData": 4 },
					{
                                            "mData": 5,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                                        {
                                            "mData": 6,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                    { "mData": 7 },
                    { "mData": 8 },
                    { "mData": 9 },
                    { "mData": 10}
				]
            });

            setActiveMenu('menu_products_list');
			
			function prepareModal(id) {
                var i = id;
                $('#smwModal').find('#modalTitle').html('Agregar Producto');
                $('#smwModal').find('.modal-body').html($('#add-product').html());
				if (id != 0) {
					$('#smwModal').find('.modal-body').html('Por favor espere...');
					$('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>');
					$.ajax({
						type: 'GET',
						url: '{{ URL::to('/products/get') }}' + '/' + id,
						dataType: 'json',
						success: function(d) {
							$('#smwModal').find('.modal-body').html($('#add-product').html())
									.find('#product_name').val(d.product_name).end()
                                    .find('#product_real_price').val(accounting.formatMoney(d.real_price, { symbol: "",  format: "%v" })).end()
                                    .find('#product_public_price').val(accounting.formatMoney(d.public_price, { symbol: "",  format: "%v" })).end()
									.find('#product_upc').val(d.upc).end()
                                    .find('#product_family').val(d.family_id).end()
                                    .find('#product_presentation').val(d.presentation).end()
									.find('#product_description').val(d.description).end()
									.find('#product_color').val(d.color).end()
									.find('input[name=product_type][value=' + d.type +']').prop('checked', 'checked').end()
                                    .find('#product_cup_milliliters').val(d.cup_milliliters).end()
                                    .find('#product_price_cup').val(d.price_cup).end()
                                    .find('#product_empty_weight').val(d.empty_bottle_weight).end()
                                    .find('#product_density').val(d.density).end()
                                    .find('#product_filled_weight').val(d.filled_bottle_weight).end()
									.data('id', d.id)
							;
							$('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" id="add-product-btn">Modificar Producto</button>');
						}
					});
				}
				else {
					$('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" id="add-product-btn">Agregar Producto</button>');
				}
                $('#smwModal').off('click', '#add-product-btn').on('click', '#add-product-btn', function() {
                    var data = {
                        product_name: $('#smwModal').find('#product_name').val(),
                        product_public_price: $('#smwModal').find('#product_public_price').val(),
                        product_real_price: $('#smwModal').find('#product_real_price').val(),
                        upc: $('#smwModal').find('#product_upc').val(),
                        product_family:$('#smwModal').find('#product_family').val(),
                        product_presentation:$('#smwModal').find('#product_presentation').val(),
                        product_description: $('#smwModal').find('#product_description').val(),
                        product_color: $('#smwModal').find('#product_color').val(),
						product_type: $('#smwModal').find('input[name=product_type]:checked').val(),
                        product_cup_milliliters: $('#smwModal').find('#product_cup_milliliters').val(),
                        product_price_cup: $('#smwModal').find('#product_price_cup').val(),
                        product_empty_weight: $('#smwModal').find('#product_empty_weight').val(),
                        product_density: $('#smwModal').find('#product_density').val(),
                        product_filled_weight: $('#smwModal').find('#product_filled_weight').val()
                    }, 
					id = i;
                    $.ajax({
                        type: "POST",
                        url: '{{ URL::to('/products') }}' + (typeof id !== 'undefined'?('/' + id):''),
                        data: data,
                        success: function(data, textStatus, jqXHR) {
                            if(typeof(data.success) != "undefined"){
                                var message = "";
                                if(typeof(data.errors.product_name) != "undefined")
                                    message += '* ' + data.errors.product_name +'\n\n';
                                if(typeof(data.errors.upc) != "undefined")
                                    message += '* ' + data.errors.upc +'\n\n';
                                if(typeof(data.errors.product_presentation) != "undefined")
                                    message += '* ' + data.errors.product_presentation +'\n\n';
                                if(typeof(data.errors.product_public_price) != "undefined")
                                    message += '* ' + data.errors.product_public_price +'\n\n';
                                if(typeof(data.errors.product_real_price) != "undefined")
                                    message += '* ' + data.errors.product_real_price +'\n\n';
                                if(typeof(data.errors.product_cup_milliliters) != "undefined")
                                    message += '* ' + data.errors.product_cup_milliliters +'\n\n';
                                if(typeof(data.errors.product_price_cup) != "undefined")
                                    message += '* ' + data.errors.product_price_cup +'\n\n';
                                if(typeof(data.errors.product_empty_weight) != "undefined")
                                    message += '* ' + data.errors.product_empty_weight;
                                if(typeof(data.errors.product_density) != "undefined")
                                    message += '* ' + data.errors.product_density;
                                alert(message);
                            }
                            else{
                                $('#smwModal').modal('hide');
                                dt.fnDraw();
                            }
                            },
                            error: function(jqXHR, textStatus, errorThrown){
                                alert("¡ESTO ES VERGONZOSO EN VERDAD! \n\n !TRANSACCIÓN ERRÓNEA!");
                            },
                            dataType: 'json'
                        });
                    });
            }

                $('#add_product').on('click', function() {
                                    prepareModal(0);
                });

                $('#reads').off('click', '.action-edit').on('click', '.action-edit', function(e) {
                        var o = $(this),
                        id = o.parents('div:first').find('span.product-id').text();
                        prepareModal(id);
                        $('#smwModal').modal();
                });

                $('#reads').off('click', '.action-delete').on('click', '.action-delete', function(e) {
                        var o = $(this),
                                id = o.parents('div:first').find('span.product-id').text();
                        if (!confirm('Desea borrar el Producto?')) {
                                return false;
                        }
                        
                        $.ajax({
                                type: "POST",
                                url: '{{ URL::to('/products/delete') }}' + '/' + id,
                                success: function(data, textStatus, jqXHR) {
                                        dt.fnDraw();
                                },
                                dataType: 'json'
                        });
                });



                $("#get_csv").click(function(){
                //console.log(dt.oApi._fnAjaxParameters( dt.fnSettings()) );
                    var oParams = dt.oApi._fnAjaxParameters( dt.fnSettings() );
                    window.location="/products/csv"+"?"+$.param(oParams);
                });
        });
    </script>
@stop