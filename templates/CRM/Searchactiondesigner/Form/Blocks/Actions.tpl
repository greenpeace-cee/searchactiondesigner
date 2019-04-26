{crmScope extensionKey='searchactiondesigner'}
    <h3>{ts}Actions{/ts}</h3>

    <div class="crm-block crm-form-block crm-searchactiondesigner-actions-block">
        <table>
            <tr>
                <th>{ts}Title{/ts}</th>
                <th>{ts}System name{/ts}</th>
                <th>{ts}Type{/ts}</th>
                <th></th>
                <th></th>
            </tr>
            {foreach from=$actions item=action}
                {assign var="action_type" value=$action.type}
                <tr>
                    <td>
                        {$action.title}
                    </td>
                    <td>
                        <span class="description">{$action.name}</span>
                    </td>
                    <td>{$action_types.$action_type}</td>
                    <td>{if ($action.weight && !is_numeric($action.weight))}{$action.weight}{/if}</td>
                    <td>
                        <a href="{crmURL p="civicrm/searchactiondesigner/action" q="reset=1&action=update&search_task_id=`$action.search_task_id`&id=`$action.id`"}">{ts}Edit{/ts}</a>
                        <a href="{crmURL p="civicrm/searchactiondesigner/action" q="reset=1&action=delete&search_task_id=`$action.search_task_id`&id=`$action.id`"}">{ts}Remove{/ts}</a>
                    </td>
                </tr>
            {/foreach}
        </table>

        <div class="crm-submit-buttons">
            <a class="add button" title="{ts}Add Action{/ts}" href="{crmURL p="civicrm/searchactiondesigner/action" q="reset=1&action=add&search_task_id=`$search_task_id`"}">
                <span><div class="icon add-icon ui-icon-circle-plus"></div>{ts}Add Action{/ts}</span></a>
        </div>
    </div>
{/crmScope}