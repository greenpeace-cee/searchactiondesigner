{crmScope extensionKey='searchactiondesigner'}
{capture assign='configuration_title'}{ts}Configuration{/ts}{/capture}
{include file="Civi/ActionProvider/Utils/UserInterface/AddConfigToQuickform.tpl" title=$configuration_title}
{capture assign='parameter_mapping_title'}{ts}Parameter Mapping{/ts}{/capture}
{include file="Civi/ActionProvider/Utils/UserInterface/AddMappingToQuickForm.tpl" title=$parameter_mapping_title prefix='parameter_'}
{/crmScope}
