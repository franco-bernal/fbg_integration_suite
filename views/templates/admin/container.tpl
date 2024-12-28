{* Contenedor principal para las vistas del admin de la suite *}

<div class="panel">
    <h3>{l s='Administración' mod='fbg_integration_suite'}</h3>
    
    {* Renderiza la plantilla específica de la app *}
    {if isset($app_template)}
        {include file=$app_template}
    {else}
        <p>{l s='No se encontró la plantilla de la aplicación.' mod='fbg_integration_suite'}</p>
    {/if}
</div>
