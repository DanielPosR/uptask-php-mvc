const mobileMenuBtn = document.querySelector('#mobile-menu');
const cerrarMenuBtn = document.querySelector('#cerrar-menu');
const sidebar = document.querySelector('.sidebar');

if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', () => {

        sidebar.classList.add('mostrar');

    })
}

if (cerrarMenuBtn) {
    cerrarMenuBtn.addEventListener('click', () => {

        sidebar.classList.add('ocultar');

        setTimeout(() => {
            sidebar.classList.remove('mostrar');
            sidebar.classList.remove('ocultar');
        }, 1000)
    })
}


//* ELIMINA LA CLASE DE MOSTRAR EN EL TAMAÑO DE TABLET Y MAYORES

const anchoPantalla = document.body.clientWidth;

window.addEventListener('resize', function () {
    const anchoPantalla = document.body.clientWidth;
    if (anchoPantalla >= 768) {
        sidebar.classList.remove('mostrar');
    }
})