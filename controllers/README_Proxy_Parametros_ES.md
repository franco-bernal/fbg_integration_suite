
# Controlador Proxy para `fbg_integration_suite`

## Descripción

El controlador proxy de `fbg_integration_suite` permite redirigir solicitudes a controladores específicos definidos en las aplicaciones (`apps`) dentro de la suite. Este proxy actúa como intermediario para delegar la ejecución de métodos o manejar las solicitudes directamente.

---

## Parámetros de URL

El controlador proxy soporta los siguientes parámetros para personalizar su comportamiento:

### 1. `app`
Este parámetro es obligatorio y especifica el nombre de la aplicación (`app`) que se desea invocar. El nombre debe coincidir con el directorio y la clase del controlador de la aplicación.

**Ejemplo**:
```url
https://test.alonsostore.cl/index.php?fc=module&module=fbg_integration_suite&controller=proxy&app=app1
```

### 2. `action`
Define una acción específica que debe ser manejada por el controlador de la aplicación. Este parámetro es opcional.

**Ejemplo**:
```url
https://test.alonsostore.cl/index.php?fc=module&module=fbg_integration_suite&controller=proxy&app=app1&action=customAction
```

Si no se especifica, se invocará el método `initContent` del controlador de la aplicación de forma predeterminada.

### 3. `proxy_mode`
Define cómo debe comportarse el proxy. Los valores aceptados son:

- **`delegate`** *(predeterminado)*: El proxy redirige la ejecución al controlador de la aplicación y no genera contenido propio. **Nota**: En este modo, no se ejecuta `parent::initContent()` en el controlador proxy.
  
  **Ejemplo**:
  ```url
  https://test.alonsostore.cl/index.php?fc=module&module=fbg_integration_suite&controller=proxy&proxy_mode=delegate&app=app1
  ```

- **`proxy`**: El proxy gestiona la solicitud directamente y puede usar una plantilla propia. En este modo, **sí se ejecuta `parent::initContent()`**.

  **Ejemplo**:
  ```url
  https://test.alonsostore.cl/index.php?fc=module&module=fbg_integration_suite&controller=proxy&proxy_mode=proxy
  ```

---

## Configuración

### Estructura esperada de las aplicaciones

Cada aplicación debe tener su controlador en la siguiente ruta:
```
/modules/fbg_integration_suite/suite/[nombre_de_app]/controllers/front/[nombre_de_app].php
```

Por ejemplo, para la aplicación `App1`, el controlador debe estar en:
```
/modules/fbg_integration_suite/suite/app1/controllers/front/app1.php
```

El controlador debe implementar al menos el método `initContent` para manejar las solicitudes delegadas:

```php
class App1Controller extends ModuleFrontController
{
    public function initContent($params = [])
    {
        parent::initContent();

        // Lógica personalizada
        $this->setTemplate('module:fbg_integration_suite/suite/app1/views/front/default.tpl');
    }
}
```

---

## Ejemplo de Uso

### Redirigir a una aplicación específica
```url
https://test.alonsostore.cl/index.php?fc=module&module=fbg_integration_suite&controller=proxy&app=app1
```

### Invocar una acción específica
```url
https://test.alonsostore.cl/index.php?fc=module&module=fbg_integration_suite&controller=proxy&app=app1&action=customAction
```

### Usar el modo `proxy`
```url
https://test.alonsostore.cl/index.php?fc=module&module=fbg_integration_suite&controller=proxy&proxy_mode=proxy
```

---

## Notas Importantes

- **Delegate vs Proxy**:
  - En `delegate`, el proxy no utiliza plantillas propias y delega completamente el control a la aplicación.
  - En `proxy`, el proxy puede generar contenido directamente y usar plantillas personalizadas.

- **Errores comunes**:
  - Asegúrate de que el nombre de la aplicación y la clase del controlador coincidan.
  - Si se utiliza `proxy_mode=proxy`, la plantilla debe estar correctamente configurada.

- **Integración con Hooks**:
  Los controladores de aplicaciones también pueden integrar hooks personalizados como parte de sus funciones estándar.

---

Cualquier duda o problema, no dudes en contactar al desarrollador.
