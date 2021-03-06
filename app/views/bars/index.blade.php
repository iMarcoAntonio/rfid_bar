@extends('templates.base')

@section('css')
<link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="/css/mydatatables.css">
@stop

@section('content')
<div class="tab-header">Barras</div>
<div class="content-container">
    <div class="table-responsive container" style="width: 100%; padding: 10px;">
        <button class="btn btn-sm" data-toggle="modal" data-target="#smwModal" id="add_bar">Agregar Barra</button>
        <button class="btn btn-sm" style="float: right;" id="get_csv">CSV</button>
        <table class="table" id="reads">
            <caption style="font-size: 18px; font-weight: bold;">Listado de Barras</caption>
            <thead>
            <tr>
                <th style="width:110px;">&nbsp;</th>
                <th style="width:50px;">id</th>
                <th>Barra</th>
            </tr>
            </thead>
        </table>
    </div>
	
    <div style="display: none;" id="add-bar">
        <form role="form" id="bar-form">
            <div class="form-group">
                <label for="event_name">* Nombre de la Barra:</label>
                <input type="text" class="form-control" id="bar_name" name="bar[name]" placeholder="Nombre de la Barra" value="">
            </div>
            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea id="description" name="bar[description]" placeholder="Descripción de la barra" class="form-control"></textarea>
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
            "sAjaxSource": '/bars/datatable',
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
								$('<span />').addClass('bar-id').hide().text(id)
							).append(
								$('<button />').addClass('btn btn-info btn-sm action-edit').append('<span class="glyphicon glyphicon-pencil" />')
							).append(
								$('<button />').addClass('btn btn-danger btn-sm action-delete').append('<span class="glyphicon glyphicon-remove" />')
							);
						o.html('').append(bar);
					}
				},
				{ "mData": 0 },
				{ "mData": 1 }
			]
        });

        setActiveMenu('menu_bars_list');
		
		function prepareModal(id) {
			var mod = $('#smwModal');
			mod.find('#modalTitle').html('Agregar Barra');
			if (id != 0) {
				mod
					.find('.modal-body').html('Por favor espere...').end()
					.find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>').end()
				;
				$.ajax({
					type: 'GET',
					url: '{{ URL::to('/bars/get') }}' + '/' + id,
					success: function(d) {
						$('#smwModal').find('.modal-body').html($('#add-bar').html())
								.find('#bar_name').val(d.name).end()
								.find('#description').val(d.description).end()
								.data('id', d.id)
						;
						$('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" id="add-bar-btn">Modificar Barra</button>');
					},
					dataType: 'json'
				});
			}
			else {
				mod
					.find('.modal-body').html($('#add-bar').html()).end()
					.find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" id="add-bar-btn">Agregar Barra</button>').end()
				;
			}
			mod.off('click', '#add-bar-btn').on('click', '#add-bar-btn', function() {
				//var data = $('#smwModal #bar-form').serialize();
				var data = {
                        bar_name: $('#smwModal').find('#bar_name').val(),
                        description: $('#smwModal').find('#description').val()
                    };
				$.ajax({
					type: "POST",
					url: '{{ URL::to('/bars') }}' + (typeof id !== 'undefined'?('/' + id):''),
					data: data,
					success: function(data, textStatus, jqXHR) {
						if(typeof(data.success) != "undefined"){
							var message = "";
                          	if(typeof(data.errors.bar_name) != "undefined")
                          		message += '* ' + data.errors.bar_name +'\n\n';
                          	alert(message);
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
			
		$('#add_bar').on('click', function() {
			prepareModal(0);
		});
			
		$('#reads').off('click', '.action-edit').on('click', '.action-edit', function(e) {
			var o = $(this),
			id = o.parents('div:first').find('span.bar-id').text();
			prepareModal(id);
			$('#smwModal').modal();
		});
		
			
		$('#reads').off('click', '.action-delete').on('click', '.action-delete', function(e) {
			var o = $(this),
				id = o.parents('div:first').find('span.bar-id').text();
			if (!confirm('Desea borrar la Barra?')) {
				return false;
			}

			$.ajax({
				type: "POST",
				url: '{{ URL::to('/bars/delete') }}' + '/' + id,
				success: function(data, textStatus, jqXHR) {
					dt.fnDraw();
				},
				dataType: 'json'
			});
		});
                $("#get_csv").click(function(){
                        //console.log(dt.oApi._fnAjaxParameters( dt.fnSettings()) );
                            var oParams = dt.oApi._fnAjaxParameters( dt.fnSettings() );
                            window.location="/bars/csv"+"?"+$.param(oParams);
//                            echo oParams.toString();
//                            die();
                        });
    });
</script>
@stop
