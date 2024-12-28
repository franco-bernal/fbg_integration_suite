<?php

// Importar la clase AppBase desde su namespace
use FbgIntegrationSuite\Classes\AppBase;

// Asegurarte de incluir el archivo si no se está autoloading
include_once _PS_MODULE_DIR_ . 'fbg_integration_suite/classes/AppBase.php';


class Cookieslaw extends AppBase
{
    protected $context;

    public function __construct($module)
    {
        parent::__construct(
            $module,
            'Cookieslaw',
            'Primera aplicación de prueba',
            '/suite/cookieslaw'
        );
        $this->context = $this->getContext();


    }

    // gestion controladores
    // gestionar assets
    // gestionar bd

    /**
     * Método principal de instalación para Cookieslaw.
     * Combina métodos base con lógica adicional.
     */
    public function install()
    {
        // Instalar la app en la base de datos
        if (!$this->installApp()) {
            return false;
        }

        // Registrar hooks
        $hooks = [
            // 'displayHeader',
            'displayFooter',
            // 'hookDisplayTop'
        ];
        if (!$this->installHooks($hooks)) {
            return false;
        }

        // Instalación exitosa
        return true;
    }


    public function hookDisplayFooter($params)
    {
        $cookielaw_accepted = $this->context->session->get('cookielaw_accepted', false);
        $is_logged = $this->context->customer->isLogged();
        $id_shop = (int) $this->context->shop->id;
        $jsonPath = _PS_MODULE_DIR_ . 'fbg_integration_suite/suite/cookieslaw/views/json/popups_' . $id_shop . '.json';

        // Verificar si existe el archivo JSON para la tienda actual
        if (file_exists($jsonPath)) {
            $popupConfig = json_decode(file_get_contents($jsonPath), true)['popups'][0] ?? [];
        } else {
            // Configuración por defecto si no existe el archivo JSON
            $popupConfig = [
                'configuration' => [
                    'initialDelay' => 0,
                    'closeDelay' => 1800000,
                    'neverShowDelay' => 604800000,
                    'pagesBeforeShow' => 0,
                    'pagesAfterClose' => 0,
                ],
                'presentation' => [
                    'icon' => '/modules/fbg_integration_suite/suite/cookieslaw/views/img/PZ_SS25_WEB_COOKIES_ICONO.svg',
                    'text' => 'Usamos cookies para mejorar tu experiencia.',
                    'backgroundcolor' => '#f0f0f0e0',
                    'color' => '#7C7C7C',
                    'border' => '2',
                ]
            ];
        }

        // Pasar datos al Smarty
        $this->context->smarty->assign([
            'cookielaw_accepted' => $cookielaw_accepted,
            'is_logged' => $is_logged,
            'popupConfig' => $popupConfig,
        ]);

        $templatePath = _PS_MODULE_DIR_ . 'fbg_integration_suite/suite/cookieslaw/views/templates/front/displayFooter.tpl';

        if (!file_exists($templatePath)) {
            return '<p>Error: No se encontró la plantilla en ' . $templatePath . '</p>';
        }

        return $this->context->smarty->fetch($templatePath);
    }



    public function hookHeader()
    {
        $this->context->controller->registerStylesheet('mycookiepopup-css', 'modules/' . $this->name . '/views/css/front.css');
        $this->context->controller->registerJavascript('mycookiepopup-js', 'modules/' . $this->name . '/views/js/front.js');
    }


    // echo "hola";
    //     // Obtener el contexto del módulo
    //     $this->context = $this->getContext();

    //     // Ruta de la plantilla
    //     $templatePath = _PS_MODULE_DIR_ . 'fbg_integration_suite/suite/' . strtolower($this->name) . '/views/front/default.tpl';

    //     // Verificar si la plantilla existe
    //     if (!file_exists($templatePath)) {
    //         return '<p>Error: No se encontró la plantilla en ' . $templatePath . '</p>';
    //     }

    //     // Validar que `smarty` esté configurado
    //     if (!isset($this->context->smarty)) {
    //         return '<p>Error: Smarty no está inicializado en el contexto.</p>';
    //     }

    //     // Asignar valores a Smarty
    //     $this->context->smarty->assign([
    //         'customVar' => 'Valor personalizado para la plantilla',
    //     ]);

    //     // Renderizar y devolver la plantilla con Smarty
    //     return $this->context->smarty->fetch($templatePath);





}
