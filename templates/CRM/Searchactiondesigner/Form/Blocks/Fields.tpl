{crmScope extensionKey='searchactiondesigner'}
    <h3>{ts}Fields{/ts}</h3>

    <div class="crm-block crm-form-block crm-searchactiondesigner-fields-block">
        <table>
            <tr>
                <th>{ts}Title{/ts}</th>
                <th>{ts}System name{/ts}</th>
                <th>{ts}Type{/ts}</th>
                <th></th>
                <th></th>
            </tr>
            {foreach from=$fields item=field}
                {assign var="field_type" value=$field.type}
                <tr>
                    <td>
                        {$field.title}
                        {if ($field.is_required)}
                            <span class="crm-marker">*</span>
                        {/if}
                    </td>
                    <td>
                        <span class="description">{$field.name}</span>
                    </td>
                    <td>{$field_types.$field_type}</td>
                    <td>{$field.weight}</td>
                    <td>
                        <a href="{crmURL p="civicrm/searchactiondesigner/field" q="reset=1&action=update&search_task_id=`$field.search_task_id`&id=`$field.id`"}">{ts}Edit{/ts}</a>
                        <a href="{crmURL p="civicrm/searchactiondesigner/field" q="reset=1&action=delete&search_task_id=`$field.search_task_id`&id=`$field.id`"}">{ts}Remove{/ts}</a>
                    </td>
                </tr>
            {/foreach}
        </table>

        <div class="crm-submit-buttons">
            <a class="add button" title="{ts}Add Field{/ts}" href="{crmURL p="civicrm/searchactiondesigner/field" q="reset=1&action=add&search_task_id=`$search_task_id`"}">
                <span><div class="icon add-icon ui-icon-circle-plus"></div>{ts}Add Field{/ts}</span></a>
        </div>
    </div>
{/crmScope}