
# Documentación del Controlador Proxy para `fbg_integration_suite`

Este archivo describe cómo usar los parámetros en la URL para interactuar con el módulo `fbg_integration_suite` a través de su controlador proxy.

## Parámetros Disponibles

### Parámetro `app`
- **Descripción**: Especifica la aplicación que se desea cargar o interactuar.
- **Formato**: `app=<nombre_de_la_app>`
- **Ejemplo**: `app=club`
- **Nota**: El nombre debe coincidir con el nombre de la carpeta y clase correspondiente a la aplicación.

### Parámetro `response_type`
- **Descripción**: Define el tipo de respuesta que devolverá el controlador.
- **Valores Permitidos**:
  - `ajax`: Para devolver una respuesta JSON. Ideal para formularios y peticiones asincrónicas.
  - `tpl`: Para renderizar una plantilla Smarty y devolver contenido HTML.
- **Formato**: `response_type=<ajax|tpl>`
- **Ejemplo**: `response_type=ajax`

### Parámetro `proxy_mode`
- **Descripción**: Indica si el proxy debe manejar directamente la solicitud o delegarla a la aplicación.
- **Valores Permitidos**:
  - `proxy`: El controlador proxy maneja la solicitud y muestra contenido propio.
  - `delegate`: El proxy delega la solicitud al controlador de la aplicación.
- **Formato**: `proxy_mode=<proxy|delegate>`
- **Ejemplo**: `proxy_mode=delegate`

### Parámetro `action`
- **Descripción**: Define una acción específica que debe realizar la aplicación.
- **Formato**: `action=<nombre_de_la_accion>`
- **Ejemplo**: `action=processForm`
- **Nota**: Este parámetro será procesado dentro del controlador de la aplicación correspondiente.

### Parámetros personalizados
- **Descripción**: Se pueden pasar parámetros adicionales según los requisitos de la aplicación.
- **Ejemplo**: `inputName=Franco&inputEmail=franco@example.com`

## Ejemplos de URL

### Renderizar una plantilla Smarty (TPL)
```
https://test.alonsostore.cl/index.php?fc=module&module=fbg_integration_suite&controller=proxy&app=club&response_type=tpl&proxy_mode=delegate
```

### Enviar un formulario con respuesta AJAX
```
https://test.alonsostore.cl/index.php?fc=module&module=fbg_integration_suite&controller=proxy&app=club&response_type=ajax&action=processForm&inputName=Franco&inputEmail=franco@example.com
```

### Mostrar contenido propio del proxy
```
https://test.alonsostore.cl/index.php?fc=module&module=fbg_integration_suite&controller=proxy&proxy_mode=proxy
```

## Notas Adicionales

- El parámetro `proxy_mode` tiene prioridad sobre `response_type` si se define como `proxy`.
- Asegúrate de que los controladores de las aplicaciones estén correctamente configurados para manejar las acciones y respuestas requeridas.
