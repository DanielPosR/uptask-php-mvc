(function () {
    obtenerTareas();
    let tareas = [];
    let filtradas = [];

    //* Boton para mostrar el modal de agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', () => {
        mostrarFormulario();
    });







    //* FILTROS DE BUSQUEDA DE LAS TAREAS
    const filtros = document.querySelectorAll('#filtros input[type="radio"]');
    filtros.forEach(filtro => {

        filtro.addEventListener('input', filtrarTareas);
    })

    function filtrarTareas(e) {

        const filtro = e.target.value;

        if (filtro !== '') {

            filtradas = tareas.filter(tarea => tarea.estado === filtro);

        } else {
            filtradas = [];
        }

        mostrarTareas()
    }







    //* OBTIENE LAS TAREAS DE CADA PROYECTO MEDIANTE LA API
    async function obtenerTareas() {

        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?id=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            tareas = resultado.tareas;

            mostrarTareas();

        } catch (error) {
            console.log(error)
        }
    }






    //* MUESTRA LAS TAREAS QUE SE LE PASAN DESDE LA FUNCION OBTENER TAREAS
    function mostrarTareas() {

        limpiarTareas();
        totalPendientes();
        totalCompletas();

        const arrayTareas = filtradas.length ? filtradas : tareas;

        if (arrayTareas.length === 0) {
            const contenedorTareas = document.querySelector('#listado-tareas');
            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No hay tareas para mostrar';
            textoNoTareas.classList.add('no-tareas');
            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        const estados = {
            0: 'Pendiente',
            1: 'Completa'
        }

        arrayTareas.forEach(tarea => {

            const { id, nombre, estado } = tarea;

            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = id;
            contenedorTarea.classList.add('tarea');

            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = nombre;
            nombreTarea.ondblclick = () => {
                mostrarFormulario(true, { ...tarea });
            }

            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            //* BOTONES
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[estado];
            btnEstadoTarea.dataset.estadoTarea = estado;
            btnEstadoTarea.ondblclick = function () {
                cambiarEstadoTarea({ ...tarea });
            }

            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.onclick = function () {
                confirmarEliminarTarea({ ...tarea });
            }


            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listadoTareas = document.querySelector('#listado-tareas');
            listadoTareas.appendChild(contenedorTarea);
        })
    }







    
    //* CALCULAR CUANTAS TAREAS HAY PENDIENTES O COMPLETAS PARA DESHABILITAR LOS INPUTS RADIO EN CASO DE QUE NO HAYA TAREAS PARA MOSTRAR
    function totalPendientes() {

        const totalPendientes = tareas.filter(tarea => tarea.estado === '0');
        const pendientesRadio = document.querySelector('#pendientes');

        if (totalPendientes.length === 0) {
            pendientesRadio.disabled = true;
        } else {
            pendientesRadio.disabled = false;
        }

    }

    function totalCompletas() {

        const totalCompletas = tareas.filter(tarea => tarea.estado === '1');
        const completadasRadio = document.querySelector('#completadas');

        if (totalCompletas.length === 0) {
            completadasRadio.disabled = true;
        } else {
            completadasRadio.disabled = false;
        }

    }








    //* CREA EL HTML PARA MOSTRAR LA VENTANA MODAL CON EL FORMULARIO PARA AGREGAR TAREA
    function mostrarFormulario(editar = false, tarea = {}) {

        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">

                <legend>${editar ? 'Editar Tarea' : 'Añadir Nueva Tarea'}</legend>

                <div class="campo">
                    <input
                        type="text"
                        name="tarea"
                        placeholder="${editar ? 'Nuevo nombre de tarea' : 'Añadir tarea al proyecto actual'}"
                        id="tarea"
                        value="${tarea.nombre ? tarea.nombre : ''}"
                    />
                </div>

                <div class="opciones">
                    <input
                        type="submit"
                        class="submit-nueva-tarea"
                        value="${editar ? "Guardar Cambios" : "Añadir Tarea"}"
                    />
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
        `;


        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);


        //* DELEGATION
        modal.addEventListener('click', (e) => {
            e.preventDefault();

            if (e.target.classList.contains('cerrar-modal')) {
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');

                setTimeout(() => {
                    modal.remove();
                }, 500);
            }

            if (e.target.classList.contains('submit-nueva-tarea')) {
                const nombreTarea = document.querySelector('#tarea').value.trim();


                // Valida que el nombre de la tarea no este vacio
                if (nombreTarea === '') {
                    //* Mostrar alerta de error
                    mostrarAlerta('El nombre de la tarea es obligatorio', 'error', document.querySelector('.formulario legend'));

                    return;
                }

                if (editar) {
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                } else {
                    agregarTarea(nombreTarea);
                }

            }
        })

        document.querySelector('.dashboard').appendChild(modal);
    }










    //* MUESTRA ALERTAS DE ERROR
    function mostrarAlerta(msj, tipo, referencia) {
        const alertaPrevia = document.querySelector('.alerta');

        if (!alertaPrevia) {
            const alerta = document.createElement('DIV');
            alerta.classList.add('alerta', tipo);
            alerta.textContent = msj;

            referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

            setTimeout(() => {
                alerta.remove();
            }, 4000);
        }
    }








    //* CONSULTA EL SERVIDOR PARA AÑADIR UNA NUEVA TAREA AL PROYECTO ACTUAL
    async function agregarTarea(tarea) {

        // Construir la peticion
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoId', obtenerProyecto());


        try {

            const url = 'http://localhost:5000/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            })

            const resultado = await respuesta.json();


            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));

            if (resultado.tipo === 'exito') {
                const modal = document.querySelector('.modal');

                setTimeout(() => {
                    modal.remove();
                }, 1500);

                // Agregar el objeto de tarea la global de tareas
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyetoId: resultado.proyectoId
                }

                tareas = [...tareas, tareaObj];

                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }
    }







    //* CAMBIA EL ESTADO DE LAS TAREAS
    function cambiarEstadoTarea(tarea) {

        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEstado;

        actualizarTarea(tarea);
    }









    //* ACTUALIZA LA TAREA CON EL NUEVO ESTADO
    async function actualizarTarea(tarea) {

        const { estado, id, nombre } = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());


        /* como acceder al fromdata para revisar lo que estamos enviando al servidor
        for(let valor of datos.values()) { 
            console.log(valor)
        }*/

        try {
            const url = 'http://localhost:5000/api/tarea/actualizar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            })
            const resultado = await respuesta.json();
            console.log(resultado);
            if (resultado.respuesta.tipo === 'exito') {
                Swal.fire(
                    '¡Éxito!',
                    resultado.respuesta.mensaje,
                    'success'
                );

                const modal = document.querySelector('.modal');

                if (modal) {
                    modal.remove();
                }


                tareas = tareas.map(tareaMemoria => {

                    if (tareaMemoria.id === id) {
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }

                    return tareaMemoria;
                })

                mostrarTareas();
            }
        } catch (error) {
            console.log(error)
        }
    }








    //* VERIFICA SI EL USUARIO DESEA ELIMINAR LA TAREA ANTES DE REALIZAR LA ACCION
    function confirmarEliminarTarea(tarea) {

        Swal.fire({
            title: "¿Deseas eliminar esta tarea?",
            showCancelButton: true,
            confirmButtonText: "Sí",
            cancelButtonText: `Cancelar`
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            }
        });

    }







    //* ELIMINA LA TAREA DE MANERA DEFINITIVA
    async function eliminarTarea(tarea) {
        const { estado, id, nombre } = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = 'http://localhost:5000/api/tarea/eliminar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            })
            const resultado = await respuesta.json();

            if (resultado.resultado) {
                Swal.fire('¡Eliminado!', resultado.mensaje, 'success');

                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== id);
                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }
    }








    //* OBTENER PROYECTO MEDIANTE LA URL
    function obtenerProyecto() {
        // Acceder a los parametros de la URL para obtener el id
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());

        return proyecto.id;
    }









    //* LIMPIA LAS TAREAS PREVIAS PARA EVITAR DUPLICADOS
    function limpiarTareas() {

        const listadoTareas = document.querySelector('#listado-tareas');
        while (listadoTareas.firstChild) {
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }

})();
