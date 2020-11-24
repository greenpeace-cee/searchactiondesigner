{crmScope extensionKey='searchactiondesigner'}
{capture assign='configuration_title'}{ts}Configuration{/ts}{/capture}
{include file="Civi/ActionProvider/Utils/UserInterface/AddConfigToQuickform.tpl" title=$configuration_title}

{capture assign='parameter_mapping_title'}{ts}Parameter Mapping{/ts}{/capture}
{include file="Civi/ActionProvider/Utils/UserInterface/AddMappingToQuickForm.tpl" title=$parameter_mapping_title prefix=$parameter_mapping_prefix}

{capture assign='output_mapping_title'}{ts}Output mapping (when condition is not valid){/ts}{/capture}
{include file="Civi/ActionProvider/Utils/UserInterface/AddMappingToQuickForm.tpl" title=$output_mapping_title prefix=$output_mapping_prefix}
{/crmScope}
