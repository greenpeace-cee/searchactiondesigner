{crmScope extensionKey='searchactiondesigner'}

{if (!$snippet)}

  <h3>{ts}Condition{/ts}</h3>
  <div class="crm-block crm-form-block crm-search-task_title-block">
    <div class="crm-section">
      <div class="label">{$form.type.label}</div>
      <div class="content">{$form.type.html}</div>
      <div class="clear"></div>
    </div>

    <div id="type_configuration">
      {include file="CRM/Searchactiondesigner/Form/ConditionConfiguration.tpl"}
    </div>
  </div>

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>

  <script type="text/javascript">
    {literal}
    CRM.$(function($) {
      var search_task_id = {/literal}{$search_task_id}{literal};
      var id = {/literal}{if ($actionObject)}{$actionObject.id}{else}false{/if}{literal};

      $('#type').on('change', function() {
        var type = $('#type').val();
        if (type) {
          var dataUrl = CRM.url('civicrm/searchactiondesigner/condition', {type: type, 'search_task_id': search_task_id, 'id': id});
          CRM.loadPage(dataUrl, {'target': '#type_configuration'});
        } else {
          $('#type_configuration').html('');
        }
      });

      {/literal}{if !$isSubmitted}{literal}$('#type').change();{/literal}{/if}{literal}
    });
    {/literal}
  </script>
{else}
  <div id="type_configuration">
    {include file="CRM/Searchactiondesigner/Form/ConditionConfiguration.tpl"}
  </div>
{/if}
{/crmScope}
