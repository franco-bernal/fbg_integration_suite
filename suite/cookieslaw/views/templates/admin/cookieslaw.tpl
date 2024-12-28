<div class="panel">
    <h3>{l s='Editar Configuración del Popup' mod='fbg_integration_suite'}</h3>

    <form method="POST" action="{$actionUrl}" class="form-horizontal">
        <input type="hidden" name="save_popup_config" value="1">

        <!-- CONFIGURACIÓN -->
        <fieldset>
            <legend>{l s='Configuración del Popup' mod='fbg_integration_suite'}</legend>

            <div class="form-group">
                <label class="control-label col-lg-3">{l s='Retraso Inicial (ms)' mod='fbg_integration_suite'}</label>
                <div class="col-lg-6">
                    <input type="text" name="initialDelay" value="{$popup.configuration.initialDelay|default:0}"
                        class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3">{l s='Tiempo de Cierre (ms)' mod='fbg_integration_suite'}</label>
                <div class="col-lg-6">
                    <input type="text" name="closeDelay" value="{$popup.configuration.closeDelay|default:1800000}"
                        class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label
                    class="control-label col-lg-3">{l s='No Mostrar Durante (ms)' mod='fbg_integration_suite'}</label>
                <div class="col-lg-6">
                    <input type="text" name="neverShowDelay"
                        value="{$popup.configuration.neverShowDelay|default:604800000}" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label
                    class="control-label col-lg-3">{l s='Páginas Antes de Mostrar' mod='fbg_integration_suite'}</label>
                <div class="col-lg-6">
                    <input type="text" name="pagesBeforeShow" value="{$popup.configuration.pagesBeforeShow|default:0}"
                        class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label
                    class="control-label col-lg-3">{l s='Páginas Después de Cerrar' mod='fbg_integration_suite'}</label>
                <div class="col-lg-6">
                    <input type="text" name="pagesAfterClose" value="{$popup.configuration.pagesAfterClose|default:0}"
                        class="form-control">
                </div>
            </div>
        </fieldset>

        <!-- PRESENTACIÓN -->
        <fieldset>
            <legend>{l s='Presentación del Popup' mod='fbg_integration_suite'}</legend>

            <div class="form-group">
                <label class="control-label col-lg-3">{l s='Ícono del Popup' mod='fbg_integration_suite'}</label>
                <div class="col-lg-6">
                    <div class="input-group">
                        <input type="text" id="icon" name="icon"
                            value="{$popup.presentation.icon|escape:'html':'UTF-8'}" class="form-control">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default" id="chooseImage">
                                {l s='Seleccionar Imagen' mod='fbg_integration_suite'}
                            </button>
                        </span>
                    </div>
                    <div id="icon-preview">
                        {if $popup.presentation.icon}
                            <img src="{$popup.presentation.icon}" alt="Icono" style="max-width: 100px; margin-top: 10px;">
                        {/if}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label
                    class="control-label col-lg-3">{l s='Texto del Popup (HTML permitido)' mod='fbg_integration_suite'}</label>
                <div class="col-lg-6">
                    <textarea name="text" class="form-control"
                        rows="5">{$popup.presentation.text|escape:'html':'UTF-8'}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3">{l s='Color de Fondo' mod='fbg_integration_suite'}</label>
                <div class="col-lg-3">
                    <input type="text" name="backgroundcolor"
                        value="{$popup.presentation.backgroundcolor|default:'#ffffff'}" class="form-control jscolor">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3">{l s='Color del Texto' mod='fbg_integration_suite'}</label>
                <div class="col-lg-3">
                    <input type="text" name="color" value="{$popup.presentation.color|default:'#000000'}"
                        class="form-control jscolor">
                </div>
            </div>
            <div class="form-group">
                <label
                    class="control-label col-lg-3">{l s='Color del Texto del Botón' mod='fbg_integration_suite'}</label>
                <div class="col-lg-6">
                    <input type="text" name="buttonColor" value="{$popup.presentation.buttonColor}"
                        class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label
                    class="control-label col-lg-3">{l s='Color de Fondo del Botón' mod='fbg_integration_suite'}</label>
                <div class="col-lg-6">
                    <input type="text" name="buttonBackground" value="{$popup.presentation.buttonBackground}"
                        class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">{l s='Radio del Borde (px)' mod='fbg_integration_suite'}</label>
                <div class="col-lg-3">
                    <input type="text" name="border-radius" value="{$popup.presentation.border|default:'2'}"
                        class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3">{l s='Tema del Popup' mod='fbg_integration_suite'}</label>
                <div class="col-lg-6">
                    <input type="text" name="theme" value="{$popup.theme|default:'default'}" class="form-control">
                </div>
            </div>
        </fieldset>

        <div class="panel-footer text-right">
            <button type="submit" class="btn btn-primary">
                {l s='Guardar Configuración' mod='fbg_integration_suite'}
            </button>
        </div>
    </form>
</div>