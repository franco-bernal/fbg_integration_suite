<?php
/**
 * 2007-2024 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2024 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Fbg_integration_suite extends Module
{
    private $appsConfig = [];
    private $apps = [];
    private $hooksPerApp = [];

    public function __construct()
    {
        $this->name = 'fbg_integration_suite';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Franco Bernal. ';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Suite de Integración de Aplicaciones');
        $this->description = $this->l('La Suite de Integración de Aplicaciones permite conectar y sincronizar múltiples aplicaciones externas con tu tienda PrestaShop. Mejora la gestión, automatiza procesos y amplía las funcionalidades del e-commerce.');

        $this->confirmUninstall = $this->l('Desintalar Módulo. Aviso:Esto no desinstalará tablas de BD ni las limpiará. ');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);


        // $this->init();
    }


    ######### INSTALL METHODS
    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        // Crear tablas directamente en el método install()
        $sql = array();

        // Crear tabla fbg_integration_suite_app
        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'fbg_integration_suite_app` (
            `id_app` INT NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `active` TINYINT(1) NOT NULL DEFAULT 1,
            `url` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id_app`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;';

        // Crear tabla fbg_integration_suite_hooks
        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'fbg_integration_suite_hooks` (
            `id_hook` INT NOT NULL AUTO_INCREMENT,
            `id_app` INT NOT NULL,
            `hooks` TEXT NOT NULL,
            PRIMARY KEY (`id_hook`),
            FOREIGN KEY (`id_app`) REFERENCES `' . _DB_PREFIX_ . 'fbg_integration_suite_app`(`id_app`) ON DELETE CASCADE
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;';

        // Ejecutar las consultas SQL
        foreach ($sql as $query) {
            if (!Db::getInstance()->execute($query)) {
                return false;  // Si alguna consulta falla, detener la instalación
            }
        }

        return true;
    }


    public function uninstall()
    {
        // SQL para eliminar las tablas relacionadas con el módulo
        $sql = array();

        // $sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'fbg_integration_suite_hooks`';
        // $sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'fbg_integration_suite_app`';
        $sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'fbg_integration_suite`';

        // Ejecutar las consultas SQL
        foreach ($sql as $query) {
            if (!Db::getInstance()->execute($query)) {
                return false; // Retorna false si alguna eliminación falla
            }
        }

        // Llamar al método original de desinstalación
        return parent::uninstall();
    }


    public function init()
    {
        $appsData = $this->getAppsDataFromDb();

        foreach ($appsData as $app) {
            if (isset($this->hooksPerApp[$app['name']])) {
                continue;
            }

            try {
                if (class_exists($app['className'])) {
                    $this->hooksPerApp[$app['name']] = $app['hooks'];
                    $this->apps[] = new $app['className']($this);
                } else {
                    throw new Exception("Clase {$app['className']} no encontrada");
                }
            } catch (Exception $e) {
                \PrestaShopLogger::addLog("Error al inicializar la app {$app['name']}: " . $e->getMessage(), 3);
            }
        }

    }
    public function getContext()
    {
        return $this->context;
    }



    /**
     * Método mágico para delegar hooks a las apps.
     */
    public function __call($hookName, $arguments)
    {
        $db = Db::getInstance();

        // Eliminar el prefijo "hook" del nombre del hook actual
        $normalizedHookName = preg_replace('/^hook/', '', $hookName);

        // Generar la búsqueda LIKE con el hook sin el prefijo
        $hookSearch = '%' . pSQL($normalizedHookName) . '%';

        // Query SQL para obtener las apps con el hook específico
        $query = 'SELECT a.name, a.url
                  FROM `' . _DB_PREFIX_ . 'fbg_integration_suite_hooks` h
                  JOIN `' . _DB_PREFIX_ . 'fbg_integration_suite_app` a 
                  ON h.id_app = a.id_app
                  WHERE a.active = 1 
                  AND h.hooks LIKE \'' . $hookSearch . '\'';

        // Ejecutar la consulta
        $appsWithHooks = $db->executeS($query);

        $output = ''; // Almacenar el contenido devuelto por los hooks

        foreach ($appsWithHooks as $appData) {
            $appFile = _PS_MODULE_DIR_ . $this->name . $appData['url'] . '/' . strtolower($appData['name']) . '.php';
            $className = ucfirst($appData['name']);

            if (file_exists($appFile)) {
                require_once $appFile;

                // Verificar si la clase existe
                if (class_exists($className)) {
                    $appInstance = new $className($this);

                    // Ejecutar el método con el nombre original que incluye "hook"
                    if (method_exists($appInstance, $hookName)) {
                        // Concatenar el contenido devuelto por cada hook
                        $output .= call_user_func_array([$appInstance, $hookName], $arguments);
                    }
                }
            }
        }

        return $output; // Devolver el contenido acumulado
    }




    public function getContent()
    {

        // $tab = new Tab();
        // $tab->class_name = 'AdminFbgIntegrationSuiteProxy';
        // $tab->module = $this->name;
        // $tab->id_parent = Tab::getIdFromClassName('IMPROVE'); // Sección "Mejorar"
        // $tab->name = [];
        // foreach (Language::getLanguages() as $lang) {
        //     $tab->name[$lang['id_lang']] = 'FBG Integration';
        // }
        // $tab->active = 1;
        //  $tab->add();
        // $tab = new Tab();
        // $tab->class_name = 'AdminCookieslawController';  // Nombre de la clase del controlador
        // $tab->module = $this->name;  // Nombre del módulo
        // $tab->id_parent = (int) Tab::getIdFromClassName('AdminParentModulesSf');  // Pestaña padre (Módulos)
        // $tab->name = [];
        // foreach (Language::getLanguages(true) as $lang) {
        //     $tab->name[$lang['id_lang']] = 'Cookies Law';
        // }
        // $tab->save();


        // Procesar instalación de una app
        if (Tools::isSubmit('install_app')) {
            $appClass = Tools::getValue('install_app');
            $this->installApp($appClass);
        }

        // Procesar desinstalación de una app
        if (Tools::isSubmit('uninstall_app')) {
            $appClass = Tools::getValue('uninstall_app');
            $this->uninstallApp($appClass);
        }

        // Procesar desactivación de una app
        if (Tools::isSubmit('deactivate_app')) {
            $appClass = Tools::getValue('deactivate_app');
            $this->deactivateApp($appClass);
        }

        // Activar la app
        if (Tools::isSubmit('activate_app')) {
            $appName = Tools::getValue('activate_app');
            $this->activateApp($appName);
        }

        // Cargar aplicaciones activas desde la base de datos
        $appsData = $this->getAppsDataFromDb();

        // Cargar aplicaciones disponibles en la carpeta suite
        $availableApps = $this->getAvailableApps();
        // Asignar variables a Smarty
        $this->context->smarty->assign([
            'appsData' => $appsData,
            'availableApps' => $availableApps,
            'token' => Tools::getAdminTokenLite('AdminModules'),
        ]);

        // Renderizar la plantilla
        return $this->display(__FILE__, 'views/templates/admin/menu.tpl');
    }

    private function installApp($appClass)
    {
        $suitePath = _PS_MODULE_DIR_ . $this->name . '/suite/' . strtolower($appClass) . '/' . strtolower($appClass) . '.php';

        // Verificar si el archivo de la app existe
        if (file_exists($suitePath)) {
            require_once $suitePath;

            // Verificar si la clase existe
            if (class_exists($appClass)) {
                $appInstance = new $appClass($this);

                // Verificar si el método install existe
                if (method_exists($appInstance, 'install')) {
                    if ($appInstance->install()) {
                        echo ('La aplicación ' . $appClass . ' se ha instalado correctamente.');
                    } else {
                        echo ('Error al instalar la aplicación ' . $appClass . '.');
                    }
                } else {
                    echo ('La aplicación ' . $appClass . ' no tiene un método install.');
                }
            } else {
                echo ('No se encontró la clase de la aplicación ' . $appClass . '.');
            }
        } else {
            echo ('No se encontró el archivo de la aplicación en ' . $suitePath . '.');
        }
    }

    private function uninstallApp($appName)
    {
        Db::getInstance()->delete('fbg_integration_suite_app', 'name = "' . pSQL($appName) . '"');
    }

    private function deactivateApp($appName)
    {
        Db::getInstance()->update('fbg_integration_suite_app', ['active' => 0], 'name = "' . pSQL($appName) . '"');
    }

    private function activateApp($appName)
    {
        Db::getInstance()->update('fbg_integration_suite_app', ['active' => 1], 'name = "' . pSQL($appName) . '"');
    }


    /**
     * Recupera las aplicaciones activas y sus hooks desde la base de datos.
     */
    private function getAppsDataFromDb()
    {
        $db = Db::getInstance();
        $appsData = [];

        // Consulta para obtener las aplicaciones activas e inactivas
        $apps = $db->executeS('SELECT id_app, name, url, active 
                               FROM `' . _DB_PREFIX_ . 'fbg_integration_suite_app`');

        foreach ($apps as $app) {
            // Generar el nombre de la clase dinámicamente
            $className = ucfirst($app['name']);

            // Verificar si el archivo correspondiente existe antes de agregarlo
            $filePath = _PS_MODULE_DIR_ . $this->name . $app['url'] . '/' . strtolower($app['name']) . '.php';

            // Recuperar hooks asociados a esta app
            $hooks = $db->getValue('SELECT hooks 
                                    FROM `' . _DB_PREFIX_ . 'fbg_integration_suite_hooks` 
                                    WHERE id_app = ' . (int) $app['id_app']);

            // Decodificar los hooks JSON
            $hooksArray = $hooks ? json_decode($hooks, true) : [];

            $appsData[] = [
                'name' => $app['name'],
                'url' => $app['url'],
                'hooks' => is_array($hooksArray) ? $hooksArray : [], // Cargar hooks si existen
                'className' => $className, // Nombre de la clase
                'active' => isset($app['active']) ? (bool) $app['active'] : false, // Estado activo/inactivo
            ];
        }

        return $appsData;
    }

    private function getAvailableApps()
    {
        $availableApps = [];
        $suitePath = _PS_MODULE_DIR_ . $this->name . '/suite/';

        // Recuperar aplicaciones instaladas desde la base de datos
        $installedApps = $this->getAppsDataFromDb();
        $installedAppNames = array_map('strtolower', array_column($installedApps, 'name')); // Nombres en minúsculas

        // Verificar si la carpeta suite existe
        if (is_dir($suitePath)) {
            // Obtener todas las carpetas dentro de suite
            $directories = array_filter(glob($suitePath . '*'), 'is_dir');

            foreach ($directories as $dir) {
                $appName = basename($dir); // Nombre de la carpeta
                $appFile = $dir . '/' . strtolower($appName) . '.php'; // Archivo PHP esperado

                // Comparar en minúsculas para evitar problemas de case
                if (in_array(strtolower($appName), $installedAppNames)) {
                    continue; // Saltar si ya está instalada
                }

                // Valores por defecto para apps no instaladas
                $availableApps[] = [
                    'name' => $appName,
                    'description' => 'Sin descripción',
                    'path' => $appFile,
                    'installed' => false,
                    'active' => false,
                    'class' => ucfirst($appName),
                ];
            }
        }

        return $availableApps;
    }





}

