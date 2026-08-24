const escapeHtml = function (value) {
    return String(value === null || typeof value === 'undefined' ? '' : value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
};

// cunsulta de usuarios select2
let listAffiliate = function (url) {
    $('#customerCode').select2({
        placeholder: "Buscar un afiliado",
        minimumInputLength: 3,
        ajax: {
            url: url,
            dataType: 'json',
            delay: 300,
            data: function (term, page) {
                return {
                    q:  encodeURIComponent(term)
                };
            },
            results: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log('Error en la consulta AJAX: ' + errorThrown);
                // Mostrar un mensaje de error al usuario, por ejemplo, en un div de error
                $('#error-message').text('Error en la consulta. Intente nuevamente más tarde.');
            },
            cache: false
        }
    });
};

let enviarContrasena = function (urlPassword) {

    $(document).on("click", ".btnEnviarContrasena", function(e) {
        e.preventDefault()

        let id = this.id

        // Realizar la solicitud Ajax
        $.ajax({
            url: urlPassword,
            type: 'POST',
            data: {
                'user_id': id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Mostrar mensaje de éxito (o hacer cualquier otra acción después de enviar la contraseña)

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                    icon: 'success',
                    title: response[1]
                })
            },
            error: function(error) {
                // Mostrar mensaje de error (en caso de que ocurra algún problema)
                alert('Error al enviar la contraseña.');
            }
        });
    });
}

let getUserEliminated = function (urlGetUserElimin) {

    $(document).on('click', "#btnGetUserEliminated", function(e) {
        e.preventDefault();
        // Hacer la solicitud AJAX
        $.ajax({
            url: urlGetUserElimin,
            type: "GET",
            success: function(data) {
                // Construir la tabla
                console.log(data);
                var tableHtml = '<table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom w-100 tt">';
                tableHtml += '<thead><tr><th>ID</th><th>Email</th><th>Nombre</th><th>Fecha eliminación</th></tr></thead>';
                tableHtml += '<tbody>';
                data.forEach(function(user) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + escapeHtml(user.id) + '</td>';
                    tableHtml += '<td>' + escapeHtml(user.email) + '</td>';
                    tableHtml += '<td>' + escapeHtml(user.name) + '</td>';
                    tableHtml += '<td>' + escapeHtml(user.deleted_at) + '</td>';
                    tableHtml += '<td><button class="btn btn-primary btn-reactivate" data-user-id="' + escapeHtml(user.id) + '">Restaurar</button></td>';
                    tableHtml += '</tr>';
                });
                tableHtml += '</tbody></table>';
                // Insertar la tabla dentro del modal
                $('#deletedUsersModal .modal-body').html(tableHtml);
                // Mostrar el modal
                $('#deletedUsersModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

}

let reactivate = function (urlReactivate) {

    $(document).on('click', '.btn-reactivate', function() {
        var userId = $(this).data('user-id');
        // var encryptedUserId = btoa(userId);
        // Hacer la solicitud AJAX para reactivar el usuario
        $.ajax({
            url: urlReactivate,
            type: "POST",
            data: {
                "userId": userId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                if (response.success == true) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Restaurado',
                        text: 'Usuario restaurado correctament',
                    })

                    window.location.reload();
                } else {
                    alert('Error al reactivar el usuario');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Error al reactivar el usuario');
            }
        });
    });
}

let deletedUser = function (urlDeletedUser) {

    $(document).on("click", ".deletedUser", function(e) {
        e.preventDefault()
        let id = this.id
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: '¿Estás seguro que deseas eliminar este usuario?',
            text: "¡No podrás revertir esto, ten en cuenta que los usuarios asociados a este igulmente serán eliminados!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, Eliminarlo',
            cancelButtonText: 'No, Cancelar!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'DELETE',
                    url: urlDeletedUser,
                    data: {
                        "userId": id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response.data);
                        if (response.success == true) {
                            swalWithBootstrapButtons.fire(
                                '¡Eliminado!',
                                'El usuario ha sido eliminado',
                                'success'
                            )
                        }
                        window.location.reload();
                    }
                });

            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'El usuario está seguro :)',
                    'error'
                )
            }
        })
    });
}

let proveedor = function (urlProveedor, urlProveedorLocal) {

    $(document).on("click", ".proveedor", function(e) {
        e.preventDefault()
        let id = this.id

        plantillaBody = '';

        $.ajax({
            type: 'POST',
            url: urlProveedor,
            data: {
                "userId": id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                let data = response.data;
                if (response.success == true) {
                    if (data != null) {
                        $('#dataProveedor').html('')
                        plantillaBody = `
                        <form>
                            <div class="float-left">
                                <h6 class="mb-0 p-2"> <b>Nombre Completo:</b> ${data.name}</h6>
                                <h6 class="mb-0 p-2"> <b>Numero Identificacion:</b> ${data.number_id}</h6>
                                <h6 class="mb-0 p-2"> <b>Correo:</b> ${data.email}</h6>
                                <h6 class="mb-0 p-2"> <b>Telefono:</b> ${data.phone}</h6>
                            </div>
                        </form>

                        `
                        $('#dataProveedor').append(plantillaBody)

                        $('#exampleModalProveedor').modal('show');
                    } else if (data == null) {
                        Swal.fire({
                            title: 'Advertencia!',
                            text: "El usuario no se ecuentra asignado a ningun proveedor, desea asignarlo a un proveedor!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, Asignar!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                (async () => {
                                    const {
                                        value: number
                                    } = await Swal.fire({
                                        title: 'Ingrese numero de identificacion del proveedor',
                                        input: 'text',
                                        inputLabel: 'Proveedor a asignar',
                                        inputPlaceholder: 'Ingrese identificacion'
                                    })

                                    $.ajax({
                                        type: 'GET',
                                        url: urlProveedorLocal,
                                        data: {
                                            "number_id": number,
                                            id: id
                                        },
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        success: function(response) {
                                            let data = response.data;

                                            if (response.success ==
                                                true) {
                                                Swal.fire(
                                                    `Usuario asociado al proveedor: ${data[0].name}`
                                                    )
                                            }
                                            if (response.success ==
                                                false) {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Oops...',
                                                    text: data,
                                                })
                                            }
                                        }
                                    })

                                    // if (number) {
                                    // Swal.fire(`Entered email: ${number}`)
                                    // }
                                })()
                            }
                        })
                    }
                }
                if (response.success == false) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data,
                    })
                }
            }

        });
    });
}

// load
let Loader = function() {
    Swal.fire({
        title: 'Espere un momento, estamos consultando la información!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
            const b = Swal.getHtmlContainer().querySelector('b')
        },
    })
}
// Fin

$(document).on('click', "#consultAfiliado", function(e) {
    Loader();
});

$(document).on("click", "#consultFacturas", function(e) {
    Loader();
});

//? cargamos la imagen en el modal
let alinks = document.getElementsByClassName('aImg')

for (const alink of alinks) {
    alink.addEventListener('click', function(e) {
        e.preventDefault()
        if (!this.children[0] || !this.children[0].attributes || !this.children[0].attributes[0]) {
            return;
        }
        let url = this.children[0].attributes[0].nodeValue
        document.getElementById('foto').attributes[1].nodeValue = url
    })
}

let alinksPdf = document.getElementsByClassName('aPdf')

for (const alinkPdf of alinksPdf) {
    alinkPdf.addEventListener('click', function(e) {
        e.preventDefault()
        let url = this.dataset && this.dataset.url ? this.dataset.url : null;
        const pdfEmbed = document.getElementById('pdfdoc');
        if (!url) {
            Swal.fire({
                icon: 'warning',
                title: 'Archivo no disponible',
                text: 'No se encontró la ruta del PDF.',
            });
            return;
        }
        pdfEmbed.setAttribute('src', '');
        pdfEmbed.setAttribute('src', url);
    })
}
