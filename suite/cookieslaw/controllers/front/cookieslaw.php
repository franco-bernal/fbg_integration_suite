<?php


class CookieslawController extends ModuleFrontController
{
    private $proxy;

    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
    }

    public function initContent($params = [])
    {

        #testeo 
        #https://test.alonsostore.cl/index.php?fc=module&module=fbg_integration_suite&controller=proxy&app=app1&name=tunombre&email=tuemail@example.com&proxy_mode=proxy
        $name = Tools::getValue('name', '');
        $email = Tools::getValue('email', '');

        // Asignar parámetros a Smarty
        $this->proxy->context->smarty->assign([
            'name' => $name,
            'email' => $email,
            'message' => empty($name) || empty($email)
                ? 'Por favor, proporciona ambos parámetros "name" y "email".'
                : '¡Parámetros recibidos correctamente!',
            'hasError' => empty($name) || empty($email),
        ]);

        // Renderizar la plantilla

        $this->proxy->setTemplate('module:fbg_integration_suite/suite/app1/views/front/default.tpl');
        // $this->ajaxDie(json_encode([
        //     'status' => 'error',
        //     'message' => 'El parámetro "customParam" es obligatorio.',
        // ]));
    }
}