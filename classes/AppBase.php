<?php
namespace FbgIntegrationSuite\Classes;

use Db;
use PrestaShopLogger;

abstract class AppBase
{
    protected $module;

    public $name;
    public $description;
    public $url;

    public function __construct($module, $name, $description, $url)
    {
        $this->module = $module;
        $this->name = $name;
        $this->description = $description;
        $this->url = $url;
    }

    public function getContext()
    {
        return $this->module->getContext();
    }


    /**
     * Validar si la aplicación ya está instalada.
     */
    public function isInstalled()
    {
        $db = Db::getInstance();
        $result = $db->getValue('SELECT COUNT(*) FROM `' . _DB_PREFIX_ . 'fbg_integration_suite_app` WHERE name = "' . pSQL($this->name) . '"');
        return (int) $result > 0;
    }

    /**
     * Método para instalar la app en la base de datos.
     */
    protected function installApp()
    {
        if ($this->isInstalled()) {
            PrestaShopLogger::addLog("La aplicación {$this->name} ya está instalada.", 1);
            return true; // Evitar duplicados
        }

        $db = Db::getInstance();
        $result = $db->insert('fbg_integration_suite_app', [
            'name' => pSQL($this->name),
            'active' => 1,
            'url' => pSQL($this->url),
        ]);

        if ($result) {
            PrestaShopLogger::addLog("La aplicación {$this->name} se instaló correctamente.", 1);
        } else {
            PrestaShopLogger::addLog("Error al instalar la aplicación {$this->name}.", 3);
        }

        return $result;
    }

    /**
     * Método para instalar hooks en la base de datos.
     */
    protected function installHooks(array $hooks)
    {
        $db = Db::getInstance();
        $appId = $this->getAppId(); // Obtener el ID de la aplicación actual

        if (!$appId) {
            \PrestaShopLogger::addLog("No se pudo obtener el ID de la aplicación {$this->name}.", 3);
            return false;
        }

        // Iterar sobre cada hook
        foreach ($hooks as $hook) {
            // 1. Registrar el hook en PrestaShop si no está registrado
            if (!$this->module->isRegisteredInHook($hook)) {
                if (!$this->module->registerHook($hook)) {
                    \PrestaShopLogger::addLog("Error al registrar el hook {$hook} para la app {$this->name}.", 3);
                    return false; // Detener si falla el registro en PrestaShop
                }
            }
        }

        // 2. Guardar los hooks en la tabla de base de datos
        $hooksJson = json_encode($hooks);

        $result = $db->insert('fbg_integration_suite_hooks', [
            'id_app' => (int) $appId,
            'hooks' => pSQL($hooksJson),
        ]);

        if (!$result) {
            \PrestaShopLogger::addLog("Error al insertar los hooks para la app {$this->name} en la base de datos.", 3);
            return false;
        }

        \PrestaShopLogger::addLog("Hooks registrados correctamente para la app {$this->name}.", 1);
        return true;
    }



    /**
     * Obtener el ID de la app recién instalada.
     */
    protected function getAppId()
    {
        $db = Db::getInstance();
        return (int) $db->getValue('SELECT id_app FROM `' . _DB_PREFIX_ . 'fbg_integration_suite_app` WHERE name = "' . pSQL($this->name) . '"');
    }

    /**
     * Placeholder para instalación de controladores.
     */
    protected function installControllers()
    {
        PrestaShopLogger::addLog("Controladores instalados para {$this->name}", 1);
        return true;
    }

    /**
     * Placeholder para instalación de assets.
     */
    protected function installAssets()
    {
        PrestaShopLogger::addLog("Assets instalados para {$this->name}", 1);
        return true;
    }
}
