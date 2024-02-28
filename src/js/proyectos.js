const eliminarButtons = document.querySelectorAll('.eliminar');

eliminarButtons.forEach(eliminarBtn => {

    eliminarBtn.addEventListener('click', () => {
        confirmarEliminarProyecto(eliminarBtn.id);
    })
})


function confirmarEliminarProyecto(id) {
    Swal.fire({
        title: "¿Deseas eliminar este proyecto?",
        showCancelButton: true,
        confirmButtonText: "Sí",
        cancelButtonText: `Cancelar`
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarProyecto(id);
        }
    });
}


async function eliminarProyecto(id) {

    const datos = new FormData();
    datos.append('id', id);

    try {
        const url = 'http://localhost:5000/eliminar-proyecto';
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });

        const resultado = await respuesta.json();

        if (resultado.eliminado) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Eliminado Correctamente",
                showConfirmButton: false,
                timer: 2000
              });

            setTimeout(() => {
                window.location.href = 'http://localhost:5000/dashboard';
            }, 2000);

        }
    } catch (error) {
        console.log(error);
    }
}