
<p class="menu_title">Hooks status</p>
{if $hooksStatus|@count > 0}
    <ul class="list-group">
        {foreach from=$hooksStatus item=status key=hook}
            <li class="list-group-item">
                {$hook}: <span
                    class="{if $status == 'installed'}text-success{elseif $status == 'already installed'}text-info{elseif $status == 'uninstalled'}text-danger{else}text-warning{/if}">{$status}</span>
            </li>
        {/foreach}
    </ul>
{else}
    <p>No hooks status available.</p>
{/if}