{crmScope extensionKey='searchactiondesigner'}
    <div class="crm-submit-buttons">
        {include file="CRM/common/formButtons.tpl" location="top"}
    </div>

    <h3>{$searchTask.title}</h3>
    {if ($status)}
        <div class="messages status no-popup">
            {$status}
        </div>
    {/if}
    <div class="crm-block crm-form-block crm-searchactiondesigner-configuration-block">
        {if ($searchTask.help_text)}
            <div class="help">{$searchTask.help_text}</div>
        {/if}
        {foreach from=$fields item=field}
            {include file=$field.template field=$field}
        {/foreach}
    </div>

    <div class="crm-submit-buttons">
        {include file="CRM/common/formButtons.tpl" location="bottom"}
    </div>
{/crmScope}
