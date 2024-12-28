<?php
class AdminCookieslaw
{
    private $proxy;
    private $jsonPath = _PS_MODULE_DIR_ . 'fbg_integration_suite/suite/cookieslaw/views/json/';

    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
    }


    // falta, poner la opción active
    // multistore, solo se ve en 16hrs
    // opcional: themes /que tenga varias plantillas.


    public function initContent($params = [])
    {
        $context = $this->proxy->getContext();
        $id_shop = (int) $context->shop->id;
        $jsonFile = $this->jsonPath . 'popups_' . $id_shop . '.json';

        // Si se envió el formulario, guardar datos
        if (Tools::isSubmit('save_popup_config')) {
            $this->savePopupConfig($jsonFile, $params, $id_shop);
        }

        // Leer datos actuales (si existen)
        $popupData = $this->loadPopupConfig($jsonFile);

        return [
            'template' => 'suite/cookieslaw/views/templates/admin/cookieslaw.tpl',
            'assign' => [
                'popup' => $popupData,
                'actionUrl' => $context->link->getAdminLink('AdminFbgIntegrationSuiteProxy') . '&app=cookieslaw',
            ]
        ];
    }

    private function savePopupConfig($file, $params, $id_shop)
    {
        $popupConfig = [
            'id_shop' => $id_shop,
            'theme' => Tools::getValue('theme', 'default'),
            'configuration' => [
                'initialDelay' => (int) Tools::getValue('initialDelay', 0),
                'closeDelay' => (int) Tools::getValue('closeDelay', 1800000),
                'neverShowDelay' => (int) Tools::getValue('neverShowDelay', 604800000),
                'pagesBeforeShow' => (int) Tools::getValue('pagesBeforeShow', 0),
                'pagesAfterClose' => (int) Tools::getValue('pagesAfterClose', 0),
            ],
            'presentation' => [
                'icon' => Tools::getValue('icon', ''),
                'text' => Tools::getValue('text', 'Texto del popup'),
                'backgroundcolor' => Tools::getValue('backgroundcolor', '#ffffff'),
                'color' => Tools::getValue('color', '#000000'),
                'border' => Tools::getValue('border-radius', '2'),
                'buttonColor' => Tools::getValue('buttonColor', '#ffffff'),  // Nuevo campo
                'buttonBackground' => Tools::getValue('buttonBackground', '#000000'),  // Nuevo campo
            ]
        ];

        // Crear el directorio si no existe
        if (!is_dir(dirname($file))) {
            mkdir(dirname($file), 0755, true);  // Crear directorio con permisos seguros
        }

        // Guardar el archivo JSON
        $result = file_put_contents($file, json_encode(['popups' => [$popupConfig]], JSON_PRETTY_PRINT));

        // Verificar si hubo algún error al guardar
        if ($result === false) {
            throw new Exception('Error al guardar la configuración del popup. Verifica permisos de escritura.');
        }

        // Asegurar permisos del archivo
        chmod($file, 0644);
    }


    private function loadPopupConfig($file)
    {
        $defaultConfig = [
            'theme' => 'default',
            'configuration' => [
                'initialDelay' => 0,
                'closeDelay' => 1800000,
                'neverShowDelay' => 604800000,
                'pagesBeforeShow' => 0,
                'pagesAfterClose' => 0,
            ],
            'presentation' => [
                'icon' => '',
                'text' => 'Texto del popup',
                'backgroundcolor' => '#ffffff',
                'color' => '#000000',
                'border' => '2',
                'buttonColor' => '#ffffff',  // Valor por defecto
                'buttonBackground' => '#000000',  // Valor por defecto
            ]
        ];

        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);

            if (json_last_error() === JSON_ERROR_NONE && isset($data['popups'][0])) {
                $popupConfig = $data['popups'][0];

                // Sobrescribir en lugar de fusionar recursivamente
                $popupConfig['configuration'] = array_merge($defaultConfig['configuration'], $popupConfig['configuration'] ?? []);
                $popupConfig['presentation'] = array_merge($defaultConfig['presentation'], $popupConfig['presentation'] ?? []);
                $popupConfig['theme'] = $popupConfig['theme'] ?? 'default';

                return $popupConfig;
            }
        }

        return $defaultConfig;
    }

}


// # RESPUESTAS TIPO AJAX
// public function initContent($params = [])
// {
//     $context = $this->proxy->getContext();

//     // Lógica personalizada
//     $message = 'Bienvenido a la administración de CookiesLaw';
//     $username = $context->employee->firstname;
//     $shopName = $context->shop->name;

//     // Simular lógica de error o éxito
//     if (isset($params['error'])) {
//         return [
//             'ajaxDie' => [
//                 'status' => 'error',
//                 'message' => 'Hubo un problema en la configuración.',
//             ]
//         ];
//     }

//     return [
//         'ajaxDie' => [
//             'status' => 'success',
//             'message' => 'Configuración guardada correctamente.',
//             'data' => [
//                 'username' => $username,
//                 'shop_name' => $shopName,
//             ]
//         ]
//     ];
// }
