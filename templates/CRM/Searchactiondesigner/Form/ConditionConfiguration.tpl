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

{capture assign='parameter_mapping_title'}{ts}Parameter Mapping{/ts}{/capture}
{include file="Civi/ActionProvider/Utils/UserInterface/AddMappingToQuickForm.tpl" title=$parameter_mapping_title prefix=$parameter_mapping_prefix}

{capture assign='output_mapping_title'}{ts}Output mapping (when condition is not valid){/ts}{/capture}
{include file="Civi/ActionProvider/Utils/UserInterface/AddMappingToQuickForm.tpl" title=$output_mapping_title prefix=$output_mapping_prefix}
{/crmScope}
