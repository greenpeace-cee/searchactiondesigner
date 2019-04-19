{crmScope extensionKey='searchactiondesigner'}
<div class="crm-accordion-wrapper">
  <div class="crm-accordion-header">
    {ts}Configuration{/ts}
  </div>
  <div class="crm-accordion-body">
    {foreach from=$actionProviderElementNames item=prefixedElements key=prefix}
      {foreach from=$prefixedElements item=elementName}
        <div class="crm-section">
          <div class="label">{$form.$elementName.label}</div>
          <div class="content">
            {$form.$elementName.html}
            {if ($actionProviderElementDescriptions.$elementName)}
              <br /><span class="description">{$actionProviderElementDescriptions.$elementName}</span>
            {/if}
          </div>
          <div class="clear"></div>
        </div>
      {/foreach}
    {/foreach}
  </div>
</div>

  <div class="crm-accordion-wrapper">
    <div class="crm-accordion-header">
      {ts}Parameter mapping{/ts}
    </div>
    <div class="crm-accordion-body">
      {foreach from=$actionProviderMappingFields item=elementName}
        <div class="crm-section">
          <div class="label">{$form.$elementName.label}</div>
          <div class="content">
            {$form.$elementName.html}
            {if ($actionProviderElementDescriptions.$elementName)}
              <br /><span class="description">{$actionProviderElementDescriptions.$elementName}</span>
            {/if}
          </div>
          <div class="clear"></div>
        </div>
      {/foreach}
    </div>
  </div>
{/crmScope}