
# Parámetros en el Controlador Proxy y Aplicaciones

Este archivo describe cómo funcionan los parámetros dentro del controlador proxy y las aplicaciones asociadas en el módulo `fbg_integration_suite`.

## Parámetros del Proxy

El controlador `Fbg_Integration_SuiteProxyModuleFrontController` es responsable de redirigir las solicitudes a las aplicaciones. A continuación, se explican los principales parámetros que maneja.

### Parámetros Clave

1. **app**: Define la aplicación objetivo. Este parámetro es obligatorio y se obtiene mediante:
   ```php
   $appName = Tools::getValue('app');
   ```

2. **parameters**: Captura todos los parámetros enviados a través de la solicitud. Se recopilan usando:
   ```php
   $parameters = Tools::getAllValues();
   ```
   Esto incluye tanto valores de GET como de POST.

### Ejemplo de URL

```plaintext
https://mi-ecommerce.com/index.php?fc=module&module=fbg_integration_suite&controller=proxy&app=App1&action=customAction
```

### Lógica de Delegación

El proxy utiliza el nombre de la aplicación (`app`) para localizar el controlador correspondiente y delegar la lógica. Si se encuentra el archivo y clase adecuados, se invoca el método `initContent` del controlador de la aplicación.

---

## Parámetros en las Aplicaciones

Cada aplicación define su propio controlador en la ruta:
```
modules/fbg_integration_suite/suite/{nombre_app}/controllers/front/{nombre_app}.php
```

### Método `initContent`

Las aplicaciones pueden definir un método `initContent` para manejar las solicitudes redirigidas desde el proxy. Este método puede recibir parámetros directamente:

```php
public function initContent($params = [])
{
    // Lógica personalizada usando los parámetros
    $this->ajaxDie(json_encode(['status' => 'success', 'params' => $params]));
}
```

### Reutilización del Contexto

El proxy pasa el contexto al controlador de la aplicación para garantizar que las funciones como `setTemplate` y `ajaxDie` funcionen correctamente.

---

## Ejemplo Completo de Flujo

1. **Solicitud desde el Navegador**
   ```plaintext
   https://mi-ecommerce.com/index.php?fc=module&module=fbg_integration_suite&controller=proxy&app=App1&key=value
   ```

2. **En el Proxy**
   - Identifica la aplicación (`App1`).
   - Redirige la solicitud al controlador `App1Controller`.

3. **En la Aplicación**
   - Procesa la solicitud en `App1Controller::initContent`.
   - Devuelve la salida (ya sea mediante `ajaxDie` o una plantilla).

---

## Importante

- Los nombres de las aplicaciones y sus controladores deben ser consistentes.
- El proxy valida que los archivos y clases existan antes de delegar la solicitud.
- Las aplicaciones tienen la responsabilidad de manejar correctamente los parámetros recibidos.

---

## Personalización

Puedes extender la funcionalidad del proxy o las aplicaciones según tus necesidades. Recuerda usar `Tools::getValue` para capturar parámetros específicos.
