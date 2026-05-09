function init() {
    $("#servicio_form").on("submit", function(e) {
        guardaryeditar(e);
    });
}

$(document).ready(function() {
    $('#servicio_data').DataTable({
        responsive: true,
        "aProcessing": true,
        "aServerSide": true,
        "ajax": {
            url: "../../controller/servicioController.php?op=listar",
            type: "post",
            dataType: "json",
            error: function(e) {
                console.log("Error al cargar datos: ", e.responseText);
            }
        },
        "bDestroy": true,
        "bInfo": true,
        "iDisplayLength": 10,
        "order": [[0, "asc"]],
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
});

// Ver detalle del servicio
function ver(ser_id) {
    $.post("/controllers/servicio.php?op=mostrar", { ser_id: ser_id }, function(data) {
        try {
            data = JSON.parse(data);

            $('#view_ser_nom').text(data.ser_nom);
            $('#view_ser_dur_prom').text(data.ser_dur_prom + ' min');
            $('#view_ser_est').html(data.ser_est == 1
                ? '<span class="badge" style="font-size:1em; background-color:green;">ACTIVO</span>'
                : '<span class="badge" style="font-size:1em; background-color:red;">INACTIVO</span>'
            );

            $("#modalmantenimiento").modal('show');
        } catch (e) {
            Swal.fire('Error', 'No se pudo cargar la información del servicio.', 'error');
        }
    }).fail(function() {
        Swal.fire('Error', 'Error de conexión al servidor.', 'error');
    });
}

// Cerrar modal
$(document).on("click", "#btnclosemodal", function() {
    $("#modalmantenimiento").modal('hide');
});

// Reservar turno
function reservar(ser_id) {
    Swal.fire({
        title: '¿Reservar este servicio?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, reservar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../../controller/turnoController.php?op=reservar", { ser_id: ser_id }, function(data) {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Turno reservado',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        var table = $('#servicio_data').DataTable();
                        table.ajax.reload(function(json) {
                            window.location.href = '../Inicio/index.php';
                        }, false); 
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            }, "json").fail(function() {
                Swal.fire('Error', 'Error de conexión.', 'error');
            });
        }
    });
}



function guardaryeditar(e) {
    e.preventDefault();

    var formData = new FormData($("#servicio_form")[0]);

    $.ajax({
        url: "../../controller/servicioController.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            try {
                response = JSON.parse(response);

                if (response.success) {
                    Swal.fire('Éxito', response.message, 'success');
                    $("#servicio_form")[0].reset();
                    $("#modalmantenimiento").modal('hide');
                    $("#servicio_data").DataTable().ajax.reload();
                    $("#btnagregar").show();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Respuesta inesperada del servidor.', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Hubo un problema de conexión con el servidor.', 'error');
        }
    });
}


function editar(ser_id) {
    $("#lbltitulo").html('Editar servicio');
    $.post("../../controller/servicioController.php?op=mostrar", { ser_id: ser_id }, function(data) {
        try {
            data = JSON.parse(data);
            $("#id_servicio").val(data.ser_id);
            $("#view_ser_nom").val(data.ser_nom);
            $('#view_ser_dur_prom').val(data.ser_dur_prom);
            
        } catch (e) {
            Swal.fire('Error', 'No se pudo cargar la información del ítem.', 'error');
        }
    });

    $("#modalmantenimiento").modal('show');
}


// Eliminar ítem (desactivar)
function eliminar(ser_id) {
    Swal.fire({
        title: "¿Eliminar servicio?",
        text: "Esta acción desactivará el servicio.",
        icon: "warning",
        confirmButtonText: "Sí, eliminar",
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../../controller/servicioController.php?op=eliminar", { ser_id: ser_id }, function(data) {
                try {
                    data = JSON.parse(data);
                    if (data.success) {
                        $('#servicio_data').DataTable().ajax.reload();
                        Swal.fire('Eliminado', data.message, 'success');
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                } catch (e) {
                    Swal.fire('Error', 'Error al procesar la respuesta.', 'error');
                }
            }).fail(function() {
                Swal.fire('Error', 'Error de conexión al servidor.', 'error');
            });
        }
    });
}

// Botón "Nuevo servicio"
$(document).on("click", "#btnnuevo", function() {
    $("#lbltitulo").html('Nuevo servicio');
    $('#servicio_form')[0].reset();
    $('#id_servicio').val('');
    $('#view_ser_nom').val('');
    $('#view_ser_dur_prom').val('');
    $("#modalmantenimiento").modal('show');
    $("#btnagregar").show();
});

init();


