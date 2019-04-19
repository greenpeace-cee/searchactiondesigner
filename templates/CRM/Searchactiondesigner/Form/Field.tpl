{crmScope extensionKey='searchactiondesigner'}

{if $action eq 8}
  {* Are you sure to delete form *}
  <h3>{ts}Delete Field{/ts}</h3>
  <div class="crm-block crm-form-block">
    <div class="crm-section">{ts 1=$field.title}Are you sure to delete field '%1'?{/ts}</div>
  </div>

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
{elseif (!$snippet)}
  <h3>{ts}Add Field{/ts}</h3>
  <div class="crm-block crm-form-block crm-search-task_title-block">
    <div class="crm-section">
      <div class="label">{$form.type.label}</div>
      <div class="content">{$form.type.html}</div>
      <div class="clear"></div>
    </div>
    <div class="crm-section">
      <div class="label">{$form.title.label}</div>
      <div class="content">
        {$form.title.html}
        <span class="">
        {ts}System name:{/ts}&nbsp;
        <span id="systemName" style="font-style: italic;">{if ($field)}{$field.name}{/if}</span>
        <a href="javascript:void(0);" onclick="jQuery('#nameSection').removeClass('hiddenElement'); jQuery(this).parent().addClass('hiddenElement'); return false;">
          {ts}Change{/ts}
        </a>
        </span>
      </div>
      <div class="clear">
      </div>
    </div>
    <div id="nameSection" class="crm-section hiddenElement">
      <div class="label">{$form.name.label}</div>
      <div class="content">{$form.name.html}</div>
      <div class="clear"></div>
    </div>
    <div class="crm-section">
      <div class="label">{$form.is_required.label}</div>
      <div class="content">{$form.is_required.html}</div>
      <div class="clear"></div>
    </div>

    <div id="type_configuration">
      {if ($configuration_template)}
        {include file=$configuration_template}
      {/if}
    </div>

  </div>

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>

  <script type="text/javascript">
    {literal}
    CRM.$(function($) {
      var id = {/literal}{if ($field)}{$field.id}{else}false{/if}{literal};
      var search_task_id = {/literal}{$search_task_id}{literal};
      $('#type').on('change', function() {
        var type = $('#type').val();
        if (type) {
          var dataUrl = CRM.url('civicrm/searchactiondesigner/field', {type: type, 'search_task_id': search_task_id, 'id': id});
          CRM.loadPage(dataUrl, {'target': '#type_configuration'});
        }
      });

      $('#title').on('blur', function() {
        var title = $('#title').val();
        if ($('#nameSection').hasClass('hiddenElement') && !id) {
          CRM.api3('SearchTaskField', 'check_name', {
            'title': title,
            'search_task_id': search_task_id
          }).done(function (result) {
            $('#systemName').html(result.name);
            $('#name').val(result.name);
          });
        }
      });

      $('#type').change();
    });
    {/literal}
  </script>

{else}
  <div id="type_configuration">
    {if ($configuration_template)}
      {include file=$configuration_template}
    {/if}
  </div>
{/if}
{/crmScope}