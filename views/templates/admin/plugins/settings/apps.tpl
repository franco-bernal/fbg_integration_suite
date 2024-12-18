{* Encabezado de la página *}
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="display-4 text-center mb-4">Suite de Integración de Aplicaciones</h1>
            <p class="lead text-center">
                Aquí puedes visualizar y gestionar las aplicaciones activas y sus hooks.
            </p>
        </div>
    </div>

    {* Tabla de aplicaciones activas *}
    {if isset($appsData) && !empty($appsData)}
        <div class="row">
            <div class="col-12">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre de la Aplicación</th>
                            <th scope="col">URL</th>
                            <th scope="col">Hooks Registrados</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$appsData item=app key=index}
                            <tr>
                                <th scope="row">{$index+1}</th>
                                <td>{$app.name|escape:'html':'UTF-8'}</td>
                                <td>{$app.url|escape:'html':'UTF-8'}</td>
                                <td>
                                    {if isset($app.hooks) && !empty($app.hooks)}
                                        <ul class="list-unstyled">
                                            {foreach from=$app.hooks item=hook}
                                                <li>
                                                    <span class="badge badge-primary">{$hook|escape:'html':'UTF-8'}</span>
                                                </li>
                                            {/foreach}
                                        </ul>
                                    {else}
                                        <span class="text-muted">Sin hooks registrados</span>
                                    {/if}
                                </td>
                                <td>
                                    {if isset($app.active) && $app.active}
                                        <span class="badge badge-success">Activo</span>
                                    {else}
                                        <span class="badge badge-danger">Inactivo</span>
                                    {/if}
                                </td>
                                <td>
                                    {* Botón Desinstalar *}
                                    <form method="post" action="" style="display:inline-block;">
                                        <input type="hidden" name="uninstall_app" value="{$app.name|escape:'html':'UTF-8'}">
                                        <button type="submit" class="btn btn-danger btn-sm">Desinstalar</button>
                                    </form>

                                    {* Botón Desactivar *}
                                    {if $app.active}
                                        <form method="post" action="" style="display:inline-block;">
                                            <input type="hidden" name="deactivate_app" value="{$app.name|escape:'html':'UTF-8'}">
                                            <button type="submit" class="btn btn-warning btn-sm">Desactivar</button>
                                        </form>
                                    {else}
                                        <form method="post" action="" style="display:inline-block;">
                                            <input type="hidden" name="activate_app" value="{$app.name|escape:'html':'UTF-8'}">
                                            <button type="submit" class="btn btn-success btn-sm">Activar</button>
                                        </form>
                                    {/if}
                                </td>
                            </tr>

                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    {else}
        {* Mensaje si no hay aplicaciones activas *}
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning text-center" role="alert">
                    No hay aplicaciones activas configuradas actualmente.
                </div>
            </div>
        </div>
    {/if}
</div>

<h2 class="mt-5">Aplicaciones Disponibles</h2>
{if isset($availableApps) && !empty($availableApps)}
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Ruta</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$availableApps item=app key=index}
                <tr>
                    <td>{$index+1}</td>
                    <td>{$app.name|escape:'html':'UTF-8'}</td>
                    <td>{$app.description|escape:'html':'UTF-8'}</td>
                    <td>{$app.path|escape:'html':'UTF-8'}</td>
                    <td>
                        {if isset($app.installed) && $app.installed}
                            <span class="badge badge-success">Instalada</span>
                        {else}
                            <span class="badge badge-warning">No instalada</span>
                        {/if}
                    </td>

                    <td>
                        {if isset($app.installed) && !$app.installed}
                            <form method="post" action="">
                                <input type="hidden" name="install_app" value="{$app.class|escape:'html':'UTF-8'}">
                                <button type="submit" class="btn btn-primary btn-sm">Instalar</button>
                            </form>
                        {else}
                            <button class="btn btn-secondary btn-sm" disabled>Instalada</button>
                        {/if}
                    </td>

                </tr>
            {/foreach}

        </tbody>
    </table>
{else}
    <p>No se encontraron aplicaciones disponibles en la carpeta suite.</p>
{/if}