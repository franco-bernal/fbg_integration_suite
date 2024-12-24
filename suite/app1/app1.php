<?php

// Importar la clase AppBase desde su namespace
use FbgIntegrationSuite\Classes\AppBase;

// Asegurarte de incluir el archivo si no se está autoloading
include_once _PS_MODULE_DIR_ . 'fbg_integration_suite/classes/AppBase.php';


class App1 extends AppBase
{
    protected $context;

    public function __construct($module)
    {
        parent::__construct(
            $module,
            'App1',
            'Primera aplicación de prueba',
            '/suite/app1'
        );
        $this->context = $this->getContext();


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
        $hooks = [
            'displayHeader',
            'displayFooter',
            'hookDisplayTop'
        ];
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
    public function hookDisplayTop($params)
    {
        echo "hola";
        // Obtener el contexto del módulo
        $this->context = $this->getContext();

        // Ruta de la plantilla
        $templatePath = _PS_MODULE_DIR_ . 'fbg_integration_suite/suite/' . strtolower($this->name) . '/views/front/default.tpl';

        // Verificar si la plantilla existe
        if (!file_exists($templatePath)) {
            return '<p>Error: No se encontró la plantilla en ' . $templatePath . '</p>';
        }

        // Validar que `smarty` esté configurado
        if (!isset($this->context->smarty)) {
            return '<p>Error: Smarty no está inicializado en el contexto.</p>';
        }

        // Asignar valores a Smarty
        $this->context->smarty->assign([
            'customVar' => 'Valor personalizado para la plantilla',
        ]);

        // Renderizar y devolver la plantilla con Smarty
        return $this->context->smarty->fetch($templatePath);
    }



}
