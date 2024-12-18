<div class="tab-wrapper fbg_menu_tab">
    <ul class="nav nav-tabs">
        <li class="nav-item active">
            <a class="nav-link active" data-toggle="tab" href="#tab-form">Configuration</a>
        </li>
        {* <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-search-form">Tab2</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" style="color:gray">Tab desactivado ejemplo</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-configure">tag4</a>
        </li> *}
        {* <li class="nav-item">
            <a class="nav-link" href="{$link->getAdminLink('AdminLeoProductSearchStatusReindex')}">
                Reindexar Productos
            </a>
        </li> *}

    </ul>

    <div class="tab-content">
        <div id="tab-form" class="tab-pane fade active in panel">
            {* <a class="btn btn-success" href="{$link->getAdminLink('AdminCjglobalFileManager')}">
                Ir a File Manager
            </a> *}
            <div class="container-fluid">
                <div class="row settings-menu">
                    <div class="col-md-6">
                        <div>
                            <div class="col-md-6">
                                <div>
                                    {include file="module:{$module_name}/views/templates/admin/plugins/settings/apps.tpl"
                                    appsData=$appsData}
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            {* {include file="module:{$module_name}/views/templates/admin/plugins/settings/hooks.tpl"
                            hooksStatus=$hooksStatus} *}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            {* {include file="module:{$module_name}/views/templates/admin/plugins/settings/tabs.tpl"
                            tabsStatus=$tabsStatus} *}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            {* {include file="module:{$module_name}/views/templates/admin/plugins/settings/bd.tpl"
                            bdStatus=$bdStatus} *}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {* <div id="tab-search-form" class="tab-pane fade"> *}
        {* {$formAddRelationOutput} *}
        {* </div> *}
        {* 
        <div id="tab-import" class="tab-pane fade">
            {$formImportOutput}
        </div>
        *}
        {* <div id="tab-configure" class="tab-pane fade in panel">
        </div>  *}
    </div>
</div>