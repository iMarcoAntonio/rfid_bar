@extends('templates.base')

@section('css')
<link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="/css/mydatatables.css">
@stop

@section('content')
<div class="tab-header">Empleados</div>
<div class="content-container">
    <div class="table-responsive container" style="width: 100%; padding: 10px;">
        <button class="btn btn-sm" data-toggle="modal" data-target="#smwModal" id="add_user">Agregar Empleado</button>
        <button class="btn btn-sm" style="float: right;" id="get_csv">CSV</button>
        <table class="table" id="reads">
            <caption style="font-size: 18px; font-weight: bold;">Listado de Empleados</caption>
            <thead>
            <tr>
                <th style="width:100px">&nbsp;</th>
                <th>ID</th>
                <th>Nombre del Empleado</th>
                <th style="width:250px">Puesto</th>
                <th>Identidicador (epc)</th>
            </tr>
            </thead>
        </table>
    </div>

    <div style="display: none;" id="add-user">
        <form role="form">
            <div class="form-group">
                <label for="username">Nombre del empleado:</label>
                <input type="text" class="form-control" id="employee_name" placeholder="Nombre del empleado">
            </div>
            <div class="form-group">
                <label for="user_type">Puesto del empleado:</label>
                <select id="employee_position" class="form-control">
                    @foreach($employees_position as $position)
                    <option value="{{ $position -> id }}">{{ $position -> position }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="password">EPC</label>
                <input type="text" class="form-control" id="epc" placeholder="EPC">
            </div>
        </form>
    </div>
</div>


<div class="modal hide" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <h1>Procesando...</h1>
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
            "sAjaxSource": '/employees/datatable',
            "sDom": "<'row'<'col-xs-6'T><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
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
                                    $('<span />').addClass('user-id').hide().text(id)
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
                { "mData": 3 }
            ]
        });

        setActiveMenu('menu_employees_list');

        function prepareModal(id) {
            $('#smwModal').find('#modalTitle').html('Agregar Empleado');
            $('#smwModal').find('.modal-body').html($('#add-user').html());
            if (id != 0) {
                $('#smwModal').find('#modalTitle').html('Editar Empleado');
                $('#smwModal').find('.modal-body').html('Por favor espere...');
                $('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>');
                $.ajax({
                    type: 'GET',
                    url: '{{ URL::to('/employees/get') }}' + '/' + id,
                    dataType: 'json',
                    success: function(d) {
                        $('#smwModal').find('.modal-body').html($('#add-user').html())
                            .find('#employee_name').val(d.employee_name).end()
                            .find('#employee_position').val(d.employee_position_id).end()
                            .find('#epc').val(d.epc).end()
                            .data('id', d.id)
                        ;
                        $('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" id="add-user-btn">Modificar Empleado</button>');
                    }
                });
            }
            else {
                $('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" id="add-user-btn">Agregar Empleado</button>');
            }

            $('#smwModal').off('click', '#add-user-btn').on('click', '#add-user-btn', function() {
                var data = {
                        employee_name: $('#smwModal').find('#employee_name').val(),
                        employee_position: $('#smwModal').find('#employee_position').val(),
                        epc: $('#smwModal').find('#epc').val()
                    },
                    id = $('#smwModal').find('.modal-body').data('id');
                $.ajax({
                    type: "POST",
                    url: '{{ URL::to('/employees') }}' + (typeof id !== 'undefined'?('/' + id):''),
                    data: data,
                    success: function(data, textStatus, jqXHR) {
                        $('#smwModal').modal('hide');
                        dt.fnDraw();
                    },
                    dataType: 'json'
                });
            });
        }

        $('#add_user').on('click', function() {
            prepareModal(0);
        });

        $('#reads').off('click', '.action-edit').on('click', '.action-edit', function(e) {
            var o = $(this),
                id = o.parents('div:first').find('span.user-id').text();
            prepareModal(id);
            $('#smwModal').modal();
        });

        $('#reads').off('click', '.action-delete').on('click', '.action-delete', function(e) {
            var o = $(this),
                id = o.parents('div:first').find('span.user-id').text();
            if (!confirm('Desea borrar el Usuario?')) {
                return false;
            }

            $.ajax({
                type: "POST",
                url: '{{ URL::to('/users/delete') }}' + '/' + id,
                success: function(data, textStatus, jqXHR) {
                    dt.fnDraw();
                },
                dataType: 'json'
            });
        });

        $("#get_csv").click(function(){
            //console.log(dt.oApi._fnAjaxParameters( dt.fnSettings()) );
            var oParams = dt.oApi._fnAjaxParameters( dt.fnSettings() );
            window.location="/users/csv"+"?"+$.param(oParams);
        });

    });
</script>
@stop



