{if isset($configurationElements) && is_array($configurationElements)}
    {foreach from=$configurationElements item=element}
      <div class="crm-section">
        <div class="label">{$form.$element.label}</div>
        <div class="content">{$form.$element.html}</div>
        <div class="clear"></div>
      </div>
    {/foreach}
{/if}
