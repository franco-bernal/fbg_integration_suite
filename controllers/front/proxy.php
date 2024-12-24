<?php
class Fbg_Integration_SuiteProxyModuleFrontController extends ModuleFrontController
{
    private $currentAppController;

    public function initContent()
    {

        // Verificar si se especifica el modo del proxy
        $proxyMode = Tools::getValue('proxy_mode'); // Valor predeterminado: 'delegate'
        if (!(isset($proxyMode) && $proxyMode === 'delegate'))
            parent::initContent();


        // Verificar si se especifica el tipo de respuesta

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
                    $this->currentAppController = new $controllerClass();
                    $this->currentAppController->context = $this->context;
                    // Modo TPL: configurar proxy y delegar initContent directamente
                    if (method_exists($this->currentAppController, 'setProxy')) {
                        $this->currentAppController->setProxy($this);
                    }

                    if (method_exists($this->currentAppController, 'initContent')) {
                        $this->currentAppController->initContent($parameters);
                        return;
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
        parent::setTemplate($template, $params, $locale);
    }

    public function __call($methodName, $arguments)
    {
        // Delegar la llamada al controlador de la app
        if (isset($this->currentAppController) && method_exists($this->currentAppController, $methodName)) {
            return call_user_func_array([$this->currentAppController, $methodName], $arguments);
        }

        // Manejar error si el método no existe
        $this->ajaxDie("El método '{$methodName}' no existe en el controlador de la aplicación.");
    }
}
