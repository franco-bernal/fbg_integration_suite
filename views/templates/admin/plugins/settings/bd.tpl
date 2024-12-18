<p class="menu_title">Database Tables Status</p>
{if $bdStatus|@count > 0}
    <ul class="list-group">
        {foreach from=$bdStatus item=isInstalled key=tableName}
            <li class="list-group-item">
                {$tableName}: <span
                    class="{if $isInstalled}text-success{else}text-danger{/if}">
                    {if $isInstalled}Installed{else}Not Installed{/if}
                </span>
            </li>
        {/foreach}
    </ul>
{else}
    <p>No database tables status available.</p>
{/if}

<!-- Botón para instalar las tablas -->
<a href="{$link->getAdminLink('AdminModules')}&configure={$module_name}&action=installTables&token={$token}" class="btn btn-primary mt-3">
    Install Tables
</a>