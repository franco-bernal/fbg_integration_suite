{* {if !$is_logged && !$cookielaw_accepted} *}
    <div id="fbg_cookielaw-popup" class="fbg_cookie-container" style="background-color: {$popupConfig.presentation.backgroundcolor}; 
            color: {$popupConfig.presentation.color}; 
            border-radius: {$popupConfig.presentation.border}px;">

        <div id="fbg_cookie-icon" class="fbg_cookie-icon"
            style="background: url({$popupConfig.presentation.icon}) no-repeat center center / contain;">
        </div>

        <div id="fbg_cookie-message" class="fbg_cookie-message">
            {$popupConfig.presentation.text nofilter}
            <div class="fbg_cookie-buttons">
                <button id="fbg_cookie-accept" style="background-color: {$popupConfig.presentation.buttonBackground}; 
                           color: {$popupConfig.presentation.buttonColor}; 
                           border-radius: {$popupConfig.presentation.border}px;">
                    {l s='Aceptar' mod='mycookiepopup'}
                </button>
            </div>
        </div>
    </div>


    <script>
        // Configuración personalizada del popup basada en JSON
        window.fbgPopupUserConfig = {
            initialDelay: {$popupConfig.configuration.initialDelay|default:0},
            closeDelay: {$popupConfig.configuration.closeDelay|default:1800000},
            neverShowDelay: {$popupConfig.configuration.neverShowDelay|default:604800000},
            pagesBeforeShow: {$popupConfig.configuration.pagesBeforeShow|default:0},
            pagesAfterClose: {$popupConfig.configuration.pagesAfterClose|default:0},
        };
    </script>


{* {/if} *}


<style>
    /* Contenedor principal */
    #fbg_cookielaw-popup {
        position: fixed;
        bottom: 1rem;
        left: 1rem;
        z-index: 5101;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        border: 2px solid #ccc;
        border-radius: 15px;
        background: #f0f0f0e0;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        opacity: 0;
        visibility: hidden;
        transform: scale(0);
        transition: transform 0.3s ease-out, opacity 0.3s ease-out, visibility 0.3s ease-out;
        max-width: 600px;
        padding: 5px 8px;
        backdrop-filter: blur(14px);
    }

    /* Mostrar cuando sea necesario */
    #fbg_cookielaw-popup.fbg_cookie-show {
        opacity: 1;
        visibility: visible;
        transform: scale(1);
    }

    /* Ícono de cookies */
    #fbg_cookie-icon {
        width: 63px;
        height: 76px;
        /* background: url(/modules/iqitcookielaw/cookies.png) no-repeat center center / contain; */
        background: url(/modules/fbg_integration_suite/suite/cookieslaw/views/img/PZ_SS25_WEB_COOKIES_ICONO.svg) no-repeat center center / contain;
        margin: 10px;
        /* cursor: pointer; */
        flex-shrink: 0;
    }

    /* Mensaje de cookies */
    #fbg_cookie-message {
        padding: 10px;
        font-size: 14px;
        color: #7C7C7C;
        flex-grow: 1;
        text-align: justify;
        line-height: 1.5;
        font-family: 'din-regular';
        font-weight: bold;
        letter-spacing: 0.2px;
    }

    #fbg_cookie-message a {
        font-weight: bolder;
        color: #434343;
    }

    #fbg_cookie-message a:hover {
        color: #999999;
    }


    /* Botón */
    .fbg_cookie-buttons {
        margin-top: 10px;
        font-weight: 100;
    }

    .fbg_cookie-buttons button {
        padding: 0px 15px;
        border: none;
        border-radius: 5px;
        background-color: #a2cc5e;
        color: white;
        font-size: 14px;
        width: 100%;
        opacity: 1;
    }

    @media(max-width: 767px) {
        #fbg_cookielaw-popup {
            max-width: 93%;
        }

        /* #fbg_cookie-message br {
            display: none;
        } */

        #fbg_cookie-message {
            letter-spacing: unset;
            text-align: justify;
            font-size: 12px;
            text-align-last: left;
            /* hyphens: auto; */
            overflow-wrap: break-word;
            line-height: 1.3;
            padding-right: 0;
        }

        .fbg_cookie-buttons {
            text-align: center !important;
            text-align-last: center !important;
            font-weight: 100;
        }

        #fbg_cookie-icon {
            margin: 0;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popup = document.getElementById('fbg_cookielaw-popup');
        const acceptButton = document.getElementById('fbg_cookie-accept');

        const defaultConfig = {
            initialDelay: 0, // Mostrar inmediatamente
            closeDelay: 1800000, // 30 minutos
            neverShowDelay: 604800000, // 7 días
            pagesBeforeShow: 0,
            pagesAfterClose: 0,
        };

        const userConfig = window.fbgPopupUserConfig || {};
        const config = { ...defaultConfig, ...userConfig };

        const now = Date.now();
        const cookieExists = document.cookie.includes('cookielaw_accepted=true');
        const localStorageExists = localStorage.getItem('cookielaw_accepted');

        // Sincronizar: Si la cookie existe pero no el localStorage, se crea el localStorage
        if (cookieExists && !localStorageExists) {
            localStorage.setItem('cookielaw_accepted', 'true');
            console.log('LocalStorage regenerado desde la cookie.');
        }

        // Sincronizar: Si el localStorage existe pero no la cookie, se crea la cookie
        if (!cookieExists && localStorageExists) {
            const expireDate = new Date();
            expireDate.setFullYear(expireDate.getFullYear() + 10); // +10 años
            document.cookie = "cookielaw_accepted=true; path=/; expires=" + expireDate.toUTCString();
            console.log('Cookie regenerada desde localStorage.');
        }

        // Si la cookie o el localStorage existen, el popup no se muestra
        if (cookieExists || localStorageExists) {
            console.log('Popup bloqueado por cookie o localStorage existente.');
            return;
        }

        // Mostrar el popup después del retraso inicial
        setTimeout(() => {
            popup.classList.add('fbg_cookie-show');
        }, config.initialDelay * 1000);

        // Cerrar el popup y guardar aceptación
        const closePopup = () => {
            popup.classList.remove('fbg_cookie-show');
            localStorage.setItem('fbg_popupLastClosed', Date.now().toString());
        };

        acceptButton.addEventListener('click', function() {
            closePopup();

            // Guardar en localStorage (sin límite de expiración)
            localStorage.setItem('cookielaw_accepted', 'true');

            // Guardar la cookie (máximo 400 días por limitación de navegadores)
            const expireDate = new Date();
            expireDate.setFullYear(expireDate.getFullYear() + 10); // +10 años (aunque Chrome la limite)
            document.cookie = "cookielaw_accepted=true; path=/; expires=" + expireDate.toUTCString();

            console.log("Cookie y LocalStorage guardados hasta: " + expireDate.toUTCString());

            // (Opcional) Guardar también con AJAX si se necesita persistencia en el servidor
            $.post('index.php?fc=module&module=mycookiepopup&controller=ajax', { action: 'accept_cookies' },
                function(response) {
                    console.log('Cookies aceptadas y enviadas al servidor.');
                });
        });
    });
</script>