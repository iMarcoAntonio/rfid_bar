@extends('templates.base')

@section('css')
    <link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="/css/mydatatables.css">
@stop

@section('content')
<div class="tab-header">Familias</div>
<div class="content-container">
    <div class="table-responsive container" style="width: 100%; padding: 10px;">
        <button class="btn btn-sm" data-toggle="modal" data-target="#smwModal" id="add_family">Agregar Familia</button>
        <button class="btn btn-sm" style="float: right;" id="get_csv">CSV</button>
        <table class="table" id="reads">
            <caption style="font-size: 18px; font-weight: bold;">Familias</caption>
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>id</th>
                    <th style="width:240px;">Familia</th>
                    <th>Descripción</th>
                    <th style="width:170px;">creado</th>
                    <th style="width:170px;">modificado</th>
                </tr>
            </thead>
        </table>
        {{$errors->first('family_name')}}
    </div>

    <div style="display: none;" id="add-family">
        <form role="form" id="family-form">
            <div class="form-group">
                <label for="family_name">* Nombre de la Familia:</label>
                <input type="text" class="form-control" id="family_name" name="family[family_name]" placeholder="Nombre de la Familia" value="">
                {{$errors->first('family_name')}}
            </div>
            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea id="description" name="family[description]" placeholder="Descripción de la familia" class="form-control"></textarea>
            </div>
            <p style="text-decoration: underline;">* Indica campos obligatorios</p>
        </form>
    </div>
</div>
@stop

@section('javascripts')
    <script type="text/javascript" src="/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="/js/datatables.js"></script>
@stop

@section('scripts')
    <script>
        $(document).ready(function() {
            var dt = $('#reads').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": '/family/datatable',
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
								bar = $('<div />').addClass('action-buttons').append(
									$('<span />').addClass('family-id').hide().text(id)
								).append(
									$('<button />').addClass('btn btn-info btn-sm action-edit').append('<span class="glyphicon glyphicon-pencil" />')
								);
							o.html('').append(bar);
						}
					},
                                        { 
						"bSortable": false,
						"bSearchable": false,
						"mData": 0,
						"fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							var id = sData,
								o = $(nTd),
								bar = $('<div />').addClass('action-buttons').append(
									$('<span />').addClass('family-id').hide().text(id)
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
					{ "mData": 4 }
				]
            });

            setActiveMenu('menu_family');

			function prepareModal(id) {
				var mod = $('#smwModal');
                mod.find('#modalTitle').html('Agregar Familia');
				if (id != 0) {
					mod
						.find('.modal-body').html('Por favor espere...').end()
						.find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>').end()
					;
					$.ajax({
						type: 'GET',
						url: '{{ URL::to('/family/get') }}' + '/' + id,
						success: function(d) {
							$('#smwModal').find('.modal-body').html($('#add-family').html())
									.find('#family_name').val(d.family_name).end()
									.find('#description').val(d.description).end()
									.find('input[name=family\\[active\\]][value='+ d.active +']').prop('checked', 'checked').end()
									.data('id', d.id)
							;
							$('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" id="add-family-btn">Modificar Familia</button>');
						},
						dataType: 'json'
					});
				}
				else {
					mod
						.find('.modal-body').html($('#add-family').html()).end()
						.find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" id="add-family-btn">Agregar Familia</button>')
					;
				}
                mod.off('click', '#add-family-btn').on('click', '#add-family-btn', function() {
                    //var data = $('#smwModal #family-form').serialize();
                    var data = {
                        family_name: $('#smwModal').find('#family_name').val(),
                        description: $('#smwModal').find('#description').val()
                    };

                    $.ajax({
                        type: "POST",
						url: '{{ URL::to('/family') }}' + (typeof id !== 'undefined'?('/' + id):''),
                        data: data,
                        success: function(data, textStatus, jqXHR) {
                        	if(typeof(data.success) != "undefined"){
    							alert(data.errors.family_name);
                        	}
                        	else{
                        		$('#smwModal').modal('hide');
	                        	dt.fnDraw();
                        	}
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                        	alert("¡ESTO ES VERGONZOSO EN VERDAD! !TRANSACCIÓN ERRÓNEA!");
                        },
                        dataType: 'json'
                    });
                });
			}
			
            $('#add_family').on('click', function() {
				prepareModal(0);
            });
			
			$('#reads').off('click', '.action-edit').on('click', '.action-edit', function(e) {
				var o = $(this),
				id = o.parents('div:first').find('span.family-id').text();
				prepareModal(id);
				$('#smwModal').modal();
			});
			
			$('#reads').off('click', '.action-delete').on('click', '.action-delete', function(e) {
				var o = $(this),
					id = o.parents('div:first').find('span.family-id').text();
				if (!confirm('Desea borrar la familia?')) {
					return false;
				}

				$.ajax({
					type: "POST",
					url: '{{ URL::to('/family/delete') }}' + '/' + id,
					success: function(data, textStatus, jqXHR) {
						dt.fnDraw();
					},
					dataType: 'json'
				});
			});
                        
                        $("#get_csv").click(function(){
                        //console.log(dt.oApi._fnAjaxParameters( dt.fnSettings()) );
                            var oParams = dt.oApi._fnAjaxParameters( dt.fnSettings() );
                            window.location="/family/csv"+"?"+$.param(oParams);
                        });
                       
        });
    </script>
@stop
