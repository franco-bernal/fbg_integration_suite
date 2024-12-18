<?php

// Importar la clase AppBase desde su namespace
use FbgIntegrationSuite\Classes\AppBase;

// Asegurarte de incluir el archivo si no se está autoloading
include_once _PS_MODULE_DIR_ . 'fbg_integration_suite/classes/AppBase.php';


class App1 extends AppBase
{
    public function __construct($module)
    {
        parent::__construct(
            $module,
            'App1',
            'Primera aplicación de prueba',
            '/suite/app1'
        );
    }

    // gestion controladores
    // gestionar assets
    // gestionar bd

    /**
     * Método principal de instalación para App1.
     * Combina métodos base con lógica adicional.
     */
    public function install()
    {
        // Instalar la app en la base de datos
        if (!$this->installApp()) {
            return false;
        }

        // Registrar hooks
        $hooks = ['displayHeader', 'displayFooter'];
        if (!$this->installHooks($hooks)) {
            return false;
        }

        // Instalación exitosa
        return true;
    }


    public function hookDisplayHeader($params)
    {
        echo '<h1 style="color: blue;">App1: hookHeader ejecutado</h1>';
    }

    public function hookDisplayFooter($params)
    {
        echo '<h1 style="color: green;">App1: hookDisplayFooter ejecutado</h1>';
    }

}
