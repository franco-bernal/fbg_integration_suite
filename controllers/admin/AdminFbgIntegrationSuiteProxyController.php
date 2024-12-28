<?php
class AdminFbgIntegrationSuiteProxyController extends ModuleAdminController
{
    private $appController;

    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;
    }

    public function initContent()
    {
        parent::initContent();

        $appName = Tools::getValue('app');
        $parameters = Tools::getAllValues();

        if ($appName) {
            // Ruta sin "Controller" al final
            $appClassPath = _PS_MODULE_DIR_ . 'fbg_integration_suite/suite/' . strtolower($appName) . '/controllers/admin/' . $appName . '.php';


            if (file_exists($appClassPath)) {
                require_once $appClassPath;

                $className = 'Admin' . ucfirst($appName);  // Ejemplo: AdminCookieslaw
                if (class_exists($className)) {
                    // Instanciar la clase como simple proveedor de lógica
                    $this->appController = new $className();

                    // Pasar el proxy a la app
                    $this->appController->setProxy($this);

                    // Obtener respuesta de initContent
                    $response = $this->appController->initContent($parameters);

                    // Manejar respuesta (template o ajax)
                    $this->handleResponse($response);
                } else {
                    $this->errors[] = "No se encontró la clase '{$className}'.";
                }
            } else {
                $this->errors[] = "No se encontró el archivo de la aplicación '{$appName}'.";
            }
        } else {
            $this->errors[] = "No se especificó ninguna aplicación.";
        }
    }


    // Procesar la respuesta de la app
    private function handleResponse($response)
    {
        if (isset($response['template'])) {
            if (isset($response['assign'])) {
                $this->context->smarty->assign($response['assign']);
            }

            // Asignar la ruta de la plantilla específica para que la cargue container.tpl
            $this->context->smarty->assign([
                'app_template' => _PS_MODULE_DIR_ . 'fbg_integration_suite/' . $response['template']
            ]);

            // Cargar container.tpl como plantilla principal
            $this->setTemplate('container.tpl');

        } elseif (isset($response['ajaxDie'])) {
            // Responder con AJAX
            $this->ajaxDie(json_encode($response['ajaxDie']));
        } else {
            $this->ajaxDie(json_encode(['error' => 'Respuesta inesperada del controlador de la aplicación.']));
        }
    }

    public function setTemplate($template)
    {
        parent::setTemplate('container.tpl');  // Siempre carga container.tpl
    }


    public function getContext()
    {
        return $this->context;
    }


}
