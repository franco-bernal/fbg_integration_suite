{* views/templates/admin/plugins/settings/tabs.tpl *}
<p class="menu_title">Tabs (Controladores) Status</p>
{if $tabsStatus|@count > 0}
    <ul class="list-group">
        {foreach from=$tabsStatus item=data key=tab}
            <li class="list-group-item">
                {$tab}: <span class="{if $data.status == 'installed'}text-success{elseif $data.status == 'already installed'}text-info{elseif $data.status == 'uninstalled'}text-danger{else}text-warning{/if}">{$data.status}</span>
                {if $data.status == 'installed' || $data.status == 'already installed'}
                    <a href="{$data.link}" class="btn btn-primary btn-sm ml-2">Ir</a>
                {/if}
            </li>
        {/foreach}
    </ul>
{else}
    <p>No tabs status available.</p>
{/if}
