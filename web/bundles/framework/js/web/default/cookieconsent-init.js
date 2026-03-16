var LOREM_IPSUM = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
const COOKIES_EN = 'Cookies use';
const COOKIES_ES = 'Uso de las cookies.';
const COOKIES_NEEDED_EN = 'Without these cookies we could not save your settings and whenever you enter the web you would see the cookies banner.';
const COOKIES_NEEDED_ES = 'Sin estas cookies no podríamos guardar tu configuración y siempre que entrases a la web verías el banner de las cookies.';
const COOKIES_ANALYTICS_EN = 'We use Google products to be able to collect anonymous information about who visits the web.';
const COOKIES_ANALYTICS_ES = 'Utilizamos productos Google para poder recopilar información anónima de quiénes visitáis la web.';
const COOKIES_OPTIONAL_EN = 'If this category is deselected, <b>the page will reload when preferences are saved</b>... ';
const COOKIES_OPTIONAL_ES = 'Si se anula la selección de esta categoría, <b>la página se volverá a cargar cuando se guarden las preferencias</b>... ';
const COOKIES_MORE_EN = 'If you do not configure your preferences, since our cookies are activated by default, we will understand that you accept them only so that you can navigate correctly.';
const COOKIES_MORE_ES = 'Si no configuras tus preferencias, al estar nuestras cookies activadas por defecto, entenderemos que las aceptas tan sólo para que puedas navegar correctamente.';

// obtain cookieconsent plugin
var cc = initCookieConsent();

// run plugin with config object
cc.run({
    current_lang: 'es',
    autoclear_cookies: true,                    // default: false
    cookie_name: 'accedeme_cookie_consent',     // default: 'cc_cookie'
    cookie_expiration: 365,                     // default: 182
    page_scripts: true,                         // default: false
    force_consent: false,                        // default: false

    auto_language: 'browser',                     // default: null; could also be 'browser' or 'document'
    // autorun: true,                           // default: true
    // delay: 0,                                // default: 0
    // hide_from_bots: false,                   // default: false
    // remove_cookie_tables: false              // default: false
    // cookie_domain: location.hostname,        // default: current domain
    // cookie_path: '/',                        // default: root
    // cookie_same_site: 'Lax',
    // use_rfc_cookie: false,                   // default: false
    // revision: 0,                             // default: 0

    gui_options: {
        consent_modal: {
            layout: 'bar',                    // box,cloud,bar
            position: 'bottom center',          // bottom,middle,top + left,right,center
            transition: 'slide'                 // zoom,slide
        },
        settings_modal: {
            layout: 'bar',                      // box,bar
            position: 'left',                   // right,left (available only if bar layout selected)
            transition: 'slide'                 // zoom,slide
        }
    },

    onFirstAction: function(){
        //console.log('onFirstAction fired');
    },

    onAccept: function (cookie) {
        //console.log('onAccept fired!')
    },

    onChange: function (cookie, changed_preferences) {
        //console.log('onChange fired!');

        // If analytics category is disabled => disable google analytics
        if (!cc.allowedCategory('analytics')) {
            typeof gtag === 'function' && gtag('consent', 'update', {
                'analytics_storage': 'denied'
            });
        }
    },

    languages: {
        'en': {
            consent_modal: {
                title: 'Hello traveller, it\'s cookie time!',
                description: 'Our website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it. The latter will be set only after consent. <a href="#privacy-policy" class="cc-link">Privacy policy</a>',
                primary_btn: {
                    text: 'Accept all',
                    role: 'accept_all'      //'accept_selected' or 'accept_all'
                },
                secondary_btn: {
                    text: 'Preferences',
                    role: 'settings'       //'settings' or 'accept_necessary'
                },
                revision_message: '<br><br> Dear user, terms and conditions have changed since the last time you visisted!'
            },
            settings_modal: {
                title: 'Cookie settings',
                save_settings_btn: 'Save current selection',
                accept_all_btn: 'Accept all',
                reject_all_btn: 'Reject all',
                close_btn_label: 'Close',
                cookie_table_headers: [
                    {col1: 'Name'},
                    {col2: 'Domain'},
                    {col3: 'Expiration'}
                ],
                blocks: [
                    {
                        title: 'Cookie usage',
                        description: COOKIES_EN + ' <a href="#" class="cc-link">Privacy Policy</a>.'
                    }, {
                        title: 'Strictly necessary cookies',
                        description: COOKIES_NEEDED_EN,
                        toggle: {
                            value: 'necessary',
                            enabled: true,
                            readonly: true  //cookie categories with readonly=true are all treated as "necessary cookies"
                        },
                        cookie_table: [
                            {
                                col1: 'PHPSESSID',
                                col2: 'accedeme.com',
                                col3: 'Identifies server session.',
                                is_regex: true
                            },
                            {
                                col1: 'PHPSESSID',
                                col2: 'cdn.access-me.software',
                                col3: 'Identifies accessibility server session.',
                                is_regex: true
                            },
                            {
                                col1: 'ACCSSME_Login',
                                col2: 'cdn.access-me.software',
                                col3: 'Identifies your accessibility preferences.',
                                is_regex: true
                            },
                        ]
                    }, {
                        title: 'Analytics & Performance cookies',
                        description: COOKIES_ANALYTICS_EN,
                        toggle: {
                            value: 'analytics',
                            enabled: true,
                            readonly: false
                        },
                        cookie_table: [
                            {
                                col1: '^_ga',
                                col2: 'accedeme.com',
                                col3: 'Google analytics',
                                is_regex: true
                            },
                            {
                                col1: '_gid',
                                col2: 'accedeme.com',
                                col3: 'Google analytics',
                            }
                        ]
                    }, {
                        title: 'Targeting & Advertising cookies',
                        description: COOKIES_OPTIONAL_EN,
                        toggle: {
                            value: 'targeting',
                            enabled: true,
                            readonly: false,
                            reload: 'on_disable'            // New option in v2.4, check readme.md
                        },
                        cookie_table: [
                            {
                                col1: '^_cl',               // New option in v2.4: regex (microsoft clarity cookies)
                                col2: 'accedeme.com',
                                col3: 'These cookies are set by microsoft clarity',
                                // path: '/',               // New option in v2.4
                                is_regex: true              // New option in v2.4
                            }
                        ]
                    }, {
                        title: 'More information',
                        description: COOKIES_MORE_EN + ' <a class="cc-link" href="/contactus">Contact us</a>.',
                    }
                ]
            }
        },
        'es': {
            consent_modal: {
                title: '¡Eh! ¡Lo de las cooooookies!',
                //description: 'Nuestro sitio web utiliza cookies esenciales para garantizar el correcto funcionamiento y cookies de seguimiento para comprender cómo interactúas con él. Estas últimas se activarán sólo después del consentimiento. <a href="/cookiespolicy" class="cc-link">Política de cookies</a>',
                description: 'La vida es eso que pasa entre aceptación y aceptación de cookies. Pero es lo que hay: las necesitamos para saber si presentamos bien lo que hacemos (para que puedas entendernos mejor). Controla o cámbialas en la página de preferencias o lee más sobre nuestra <a href="/cookiespolicy" class="cc-link">Política de cookies</a>',
                primary_btn: {
                    text: 'Aceptar todo',
                    role: 'accept_all'      //'accept_selected' or 'accept_all'
                },
                secondary_btn: {
                    text: 'Preferencias',
                    role: 'settings'       //'settings' or 'accept_necessary'
                },
                revision_message: '<br><br> Estimado usuario, ¡los términos y condiciones han cambiado desde la última vez que nos visitó!'
            },
            settings_modal: {
                title: 'Selección de Cookies',
                save_settings_btn: 'Guardar la selección',
                accept_all_btn: 'Aceptar todo',
                reject_all_btn: 'Descartar todo',
                close_btn_label: 'Cerrar',
                cookie_table_headers: [
                    {col1: 'Nombre'},
                    {col2: 'Dominio'},
                    {col3: 'Expiración'}
                ],
                blocks: [
                    {
                        title: 'Uso de las Cookies',
                        description: COOKIES_ES + ' <a href="/cookiespolicy" class="cc-link">Política de cookies</a>.'
                    }, {
                        title: 'Estrictamente necesarias',
                        description: COOKIES_NEEDED_ES,
                        toggle: {
                            value: 'necessary',
                            enabled: true,
                            readonly: true  //cookie categories with readonly=true are all treated as "necessary cookies"
                        },
                        cookie_table: [
                            {
                                col1: 'PHPSESSID',
                                col2: 'accedeme.com',
                                col3: 'Identifica la sesión en el servidor',
                                is_regex: true
                            },
                            {
                                col1: 'PHPSESSID',
                                col2: 'cdn.access-me.software',
                                col3: 'Identifica la sesión en el servidor de accesibilidad.',
                                is_regex: true
                            },
                            {
                                col1: 'ACCSSME_Login',
                                col2: 'cdn.access-me.software',
                                col3: 'Identifica las preferencias de accesibilidad.',
                                is_regex: true
                            },
                        ]
                    }, {
                        title: 'Análisis & Rendimiento',
                        description: COOKIES_ANALYTICS_ES,
                        toggle: {
                            value: 'analytics',
                            enabled: true,
                            readonly: false
                        },
                        cookie_table: [
                            {
                                col1: '^_ga',
                                col2: 'accedeme.com',
                                col3: 'Google analytics',
                                is_regex: true
                            },
                            {
                                col1: '_gid',
                                col2: 'accedeme.com',
                                col3: 'Google analytics',
                            }
                        ]
                    }, {
                        title: 'Marketing & Publicidad',
                        description: COOKIES_OPTIONAL_ES,
                        toggle: {
                            value: 'targeting',
                            enabled: true,
                            readonly: false,
                            reload: 'on_disable'            // New option in v2.4, check readme.md
                        },
                        cookie_table: [
                            {
                                col1: '^_cl',               // New option in v2.4: regex (microsoft clarity cookies)
                                col2: 'accedeme.com',
                                col3: 'Esta es para Microsoft clarity',
                                // path: '/',               // New option in v2.4
                                is_regex: true              // New option in v2.4
                            }
                        ]
                    }, {
                        title: 'Más información',
                        description: COOKIES_MORE_ES + ' <a class="cc-link" href="/contactus">Contáctanos</a>.',
                    }
                ]
            }
        }
    }
});