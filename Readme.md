# FBG Integration Suite

## Descripción

**FBG Integration Suite** es un módulo para PrestaShop que permite gestionar e integrar aplicaciones de manera dinámica en tu tienda. Este módulo facilita la instalación, activación y desactivación de aplicaciones personalizadas, así como el registro y ejecución de hooks asociados.

## Características

- Instalación y configuración automática de aplicaciones desde la carpeta `suite/`.
- Registro automático de hooks especificados por las aplicaciones.
- Panel de administración para gestionar aplicaciones instaladas y disponibles.
- Soporte para activación y desactivación de aplicaciones.
- Log de errores y validaciones integradas.

## Instalación

1. Clona este repositorio en la carpeta de módulos de tu instalación de PrestaShop:
   ```bash
   git clone <URL_DEL_REPOSITORIO> modules/fbg_integration_suite
   ```

2. Accede al panel de administración de PrestaShop.
3. Ve a **Módulos y Servicios** > **Módulos Instalados**.
4. Busca "FBG Integration Suite" y haz clic en **Instalar**.

## Configuración

1. Una vez instalado, accede al panel de configuración del módulo.
2. Gestiona las aplicaciones disponibles y activas:
   - **Aplicaciones Disponibles**: Muestra las aplicaciones detectadas en la carpeta `suite/`.
   - **Aplicaciones Activas**: Lista las aplicaciones instaladas y sus hooks registrados.
3. Usa los botones disponibles para instalar, activar o desinstalar aplicaciones según sea necesario.

## Estructura del Proyecto

```
modules/fbg_integration_suite/
├── classes/
│   └── AppBase.php  # Clase base para las aplicaciones
├── suite/
│   └── app1/       # Carpeta de una aplicación personalizada
│       └── app1.php # Archivo principal de la aplicación
├── views/
│   └── templates/admin/ # Plantillas del panel de administración
└── sql/
    └── install.php # Script para crear tablas necesarias
```

## Desarrollo de Aplicaciones

Para agregar una nueva aplicación:

1. Crea una carpeta en `suite/` con el nombre de la aplicación.
2. Dentro de esa carpeta, crea un archivo PHP con el mismo nombre que la carpeta.
3. Extiende la clase `AppBase` y define los hooks y la lógica personalizada de la aplicación.
   
Ejemplo:

```php
use FbgIntegrationSuite\Classes\AppBase;

class App1 extends AppBase
{
    public function __construct($module)
    {
        parent::__construct(
            $module,
            'App1',
            'Primera aplicación de prueba',
            '/suite/app1'
        );
    }

    public function install()
    {
        // Lógica de instalación personalizada
        if (!$this->installApp()) {
            return false;
        }

        $hooks = ['displayHeader', 'displayFooter'];
        return $this->installHooks($hooks);
    }

    public function hookDisplayHeader($params)
    {
        echo '<h1>App1: DisplayHeader ejecutado</h1>';
    }
}
```

## Base de Datos

El módulo utiliza dos tablas:

- **`ps_fbg_integration_suite_app`**: Almacena las aplicaciones instaladas.
- **`ps_fbg_integration_suite_hooks`**: Almacena los hooks registrados por cada aplicación.

## Notas

- Este módulo está diseñado para PrestaShop 1.7+.
- Asegúrate de que las aplicaciones cumplan con el estándar definido para garantizar la compatibilidad.

## Contribución

Si deseas contribuir al proyecto:

1. Haz un fork de este repositorio.
2. Crea una rama nueva para tus cambios.
3. Envía un pull request describiendo los cambios realizados.

---

**Autor:** Franco Bernal  
**Versión:** 1.0.0
