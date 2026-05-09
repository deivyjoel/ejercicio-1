// Botón "Nuevo cancelar turno"
$(document).on("click", "#btncancelar", function cancelar() {
    var tur_id = $(this).data('tur-id');  
    Swal.fire({
        title: "¿Deseas cancelar turno?",
        text: "Esta acción cancelará el turno.",
        icon: "warning",
        confirmButtonText: "Sí, cancelar",
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../../controller/turnoController.php?op=cancelar", { tur_id:tur_id }, function(data) {
                try {
                    data = JSON.parse(data);
                    if (data.success) {

                        Swal.fire('Cancelado', data.message, 'success').then(() => {
                            window.location.href = "index.php";
                        });
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
})


