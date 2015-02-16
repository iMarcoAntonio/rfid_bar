@extends('templates.base')

@section('css')
    <link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="/css/mydatatables.css">
@stop

@section('content')
<div class="tab-header">Copeo</div>
<div class="content-container">
    <div class="table-responsive container" style="width: 100%; padding: 10px;">
    <!--
        <button class="btn btn-sm" data-toggle="modal" data-target="#smwModal" id="add_product">Agregar Producto</button>
    -->
        <button class="btn btn-sm" style="float: right;" id="get_csv">CSV</button>

        <table class="table" id="reads">
            <caption style="font-size: 18px; font-weight: bold;">Listado de lecturas de copeo</caption>
            <thead>
                <tr>
                    <th>id</th>
                    <th>epc</th>
                    <th style="width:250px">Kilogramos</th>
                    <th style="width:170px">creado</thss>
                    <th style="width:170px">modificado</th>
                </tr>
            </thead>
        </table>
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
                "sAjaxSource": '/bulks/datatable',
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
					{ "mData": 0 },
					{ "mData": 1 },
					{ "mData": 2 },
                    { "mData": 3 },
                    { "mData": 4 }
				]
            });

            setActiveMenu('menu_bulk_list');

            $("#get_csv").click(function(){
            //console.log(dt.oApi._fnAjaxParameters( dt.fnSettings()) );
                var oParams = dt.oApi._fnAjaxParameters( dt.fnSettings() );
                window.location="/products/csv"+"?"+$.param(oParams);
            });
        });
    </script>
@stop
