{* En caso de querer una página vacía, quitar extends y block y para que los hooks no afecten la página quitar proxy_mode *}
{* Si se usará elementos como {extends file="page.tpl"} entonces en la url si es el caso, debe tener proxy_mode=proxy   *}

{extends file="page.tpl"}

{block name='page_content'}
    <div class="custom-response">
        <h2>{l s='Respuesta del Controlador' mod='fbg_integration_suite'}</h2>
        <p>{if $hasError}
                <span class="text-danger">{$message}</span>
            {else}
                <span class="text-success">{$message}</span>
            <ul>
                <li><strong>{l s='Nombre:' mod='fbg_integration_suite'}</strong> {$name|escape:'html':'UTF-8'}</li>
                <li><strong>{l s='Correo Electrónico:' mod='fbg_integration_suite'}</strong> {$email|escape:'html':'UTF-8'}</li>
            </ul>
        {/if}</p>
    </div>

{/block}