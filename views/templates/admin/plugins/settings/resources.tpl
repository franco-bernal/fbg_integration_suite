{* views/templates/admin/plugins/settings/resources.tpl *}
    <p class="menu_title">Recursos disponibles en css/front/<p>
        {if $resources.js|@count > 0}
            <h4>JavaScript Files:</h4>
            <ul class="list-group">
                {foreach from=$resources.js item=js_file}
                    <li class="list-group-item">{$js_file}</li>
                {/foreach}
            </ul>
        {else}
            <p>No JavaScript files available.</p>
        {/if}

        {if $resources.css|@count > 0}
            <h4>CSS Files:</h4>
            <ul class="list-group">
                {foreach from=$resources.css item=css_file}
                    <li class="list-group-item">{$css_file}</li>
                {/foreach}
            </ul>
        {else}
            <p>No CSS files available.</p>
        {/if}