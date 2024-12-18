<?php
class Fbg_Integration_SuiteProxyModuleFrontController extends ModuleFrontController
{
    private $currentAppController;

    public function initContent()
    {
        // Verificar si se especifica el modo del proxy
        $proxyMode = Tools::getValue('proxy_mode', 'delegate'); // Valor predeterminado: 'delegate'

        if ($proxyMode === 'proxy') {
            // Modo proxy con contenido propio (usa parent::initContent)
            parent::initContent();
        }


        // Obtener el nombre de la app desde los parámetros de la URL
        $appName = Tools::getValue('app');
        $parameters = Tools::getAllValues(); // Todos los parámetros enviados

        if ($appName) {
            // Ruta esperada para el controlador de la app
            $appControllerPath = _PS_MODULE_DIR_ . 'fbg_integration_suite/suite/' . strtolower($appName) . '/controllers/front/' . strtolower($appName) . '.php';

            if (file_exists($appControllerPath)) {
                require_once $appControllerPath;

                $controllerClass = ucfirst($appName) . 'Controller';

                if (class_exists($controllerClass)) {
                    // Crear una instancia del controlador de la app
                    $this->currentAppController = new $controllerClass();
                    $this->currentAppController->context = $this->context;

                    // Pasar el proxy como referencia al controlador de la app
                    if (method_exists($this->currentAppController, 'setProxy')) {
                        $this->currentAppController->setProxy($this);
                    }

                    // Delegar al método initContent de la app
                    if (method_exists($this->currentAppController, 'initContent')) {
                        $this->currentAppController->initContent($parameters);
                        return; // Terminar aquí para evitar cualquier salida accidental del proxy
                    } else {
                        $this->ajaxDie("El método 'initContent' no existe en '{$controllerClass}'.");
                    }
                } else {
                    $this->ajaxDie("No se encontró la clase '{$controllerClass}'.");
                }
            } else {
                $this->ajaxDie("No se encontró el archivo del controlador para la aplicación '{$appName}'.");
            }
        } else {
            $this->ajaxDie("No se especificó ninguna aplicación.");
        }
    }

    public function setTemplate($template, $params = [], $locale = null)
    {
        // Asegúrate de llamar al método original para que Smarty pueda manejar la plantilla
        parent::setTemplate($template, $params, $locale);
    }

    public function __call($methodName, $arguments)
    {
        // Si hay un controlador actual y el método existe en él
        if (isset($this->currentAppController) && method_exists($this->currentAppController, $methodName)) {
            // Llamar al método del controlador de la app
            return call_user_func_array([$this->currentAppController, $methodName], $arguments);
        }

        // Si el método no existe, manejarlo como error o delegar al comportamiento predeterminado
        $this->ajaxDie("El método '{$methodName}' no existe en el controlador de la aplicación.");
    }
}
