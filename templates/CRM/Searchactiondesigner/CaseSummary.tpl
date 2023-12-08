{if $taskCount}
{foreach from=$actions item=tasks}
    {foreach from=$tasks item=task}
      <a class="crm-hover-button action-item no-popup {$task.class}" href="{$task.href}">{$task.title}</a>
    {/foreach}
{/foreach}
{/if}
<script type="text/javascript">
    {literal}
    cj(function() {
      var searchActionDesigner = cj('#searchactiondesigner .value').detach();
      cj('#searchactiondesigner').detach();
      searchActionDesigner.children().each(function (index, el) {
        cj('.case-control-panel > div:last > p').append(el);
      })
    });
    {/literal}
</script>
