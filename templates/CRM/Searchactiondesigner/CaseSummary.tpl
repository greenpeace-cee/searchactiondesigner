{crmScope extensionKey='searchactiondesigner'}
{if $taskCount}
    <select id="search_action_designer_task" class="crm-select2 crm-form-select fa-check-circle-o huge crm-search-result-actions ">
        <option value="">{ts}Actions{/ts}</option>
        {foreach from=$actions item=tasks}
          {foreach from=$tasks item=task}
            <option value="{$task.href}">{$task.title}</option>
          {/foreach}
        {/foreach}
    </select>
{/if}
<script type="text/javascript">
    {literal}
    cj(function() {
      var searchActionDesigner = cj('#searchactiondesigner .value').detach();
      cj('#searchactiondesigner').detach();
      searchActionDesigner.children().each(function (index, el) {
        cj('.case-control-panel > div:first > p').append(el);
      });
      cj('#search_action_designer_task').on('change', function() {
        var value = cj(this).val();
        if (value) {
          window.location = value;
        }
      });
    });
    {/literal}
</script>
{/crmScope}
