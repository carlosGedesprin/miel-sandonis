/*
 * Menú hamburguesa (responsive, <= 768px)
 */
(function () {
    'use strict';

    var btn = document.getElementById('menu-btn');
    var nav = document.getElementById('nav');
    if ( !btn || !nav ) return;

    var mobileQuery = window.matchMedia('(max-width: 768px)');

    function setOpen( open ) {
        nav.classList.toggle('active', open);
        btn.classList.toggle('active', open);
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        btn.setAttribute('aria-label', open ? 'Cerrar menú de navegación' : 'Abrir menú de navegación');
    }

    function isOpen() {
        return nav.classList.contains('active');
    }

    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        setOpen( !isOpen() );
    });

    // Cerrar al pulsar un enlace del menú
    nav.addEventListener('click', function (e) {
        if ( e.target.closest('a') ) setOpen(false);
    });

    // Cerrar al pulsar fuera del menú
    document.addEventListener('click', function (e) {
        if ( isOpen() && !nav.contains(e.target) && !btn.contains(e.target) ) setOpen(false);
    });

    // Cerrar con la tecla Escape y devolver el foco al botón
    document.addEventListener('keydown', function (e) {
        if ( e.key === 'Escape' && isOpen() ) {
            setOpen(false);
            btn.focus();
        }
    });

    // Al pasar a escritorio, resetear el estado
    mobileQuery.addEventListener('change', function (e) {
        if ( !e.matches ) setOpen(false);
    });
})();
