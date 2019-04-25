{crmScope extensionKey='searchactiondesigner'}
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="top"}
  </div>

{if $action eq 8}
  {* Are you sure to delete form *}
  <h3>{ts}Delete Search Task{/ts}</h3>
  <div class="crm-block crm-form-block crm-search-task_label-block">
    <div class="crm-section">{ts 1=$rule->label}Are you sure to delete search action: '%1'?{/ts}</div>
  </div>
{elseif $action eq 128}
  {* Export form *}
  <h3>{ts}Export Search Task{/ts}</h3>
  <div class="crm-block crm-form-block crm-search-task_label-block">
    <div class="crm-section">
      <textarea style="width:100%;" rows="30">{$export}</textarea>
    </div>
  </div>
{else}

  <h3>Search Action Designer</h3>
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
        <span id="systemName" style="font-style: italic;">{if ($searchTask)}{$searchTask.name}{/if}</span>
        <a href="javascript:void(0);" onclick="jQuery('#nameSection').removeClass('hiddenElement'); jQuery(this).parent().addClass('hiddenElement'); return false;">
          {ts}Change{/ts}
        </a>
        </span>
      </div>
      <div class="clear"></div>
    </div>
    <div id="nameSection" class="crm-section hiddenElement">
      <div class="label">{$form.name.label}</div>
      <div class="content">{$form.name.html}</div>
      <div class="clear"></div>
    </div>
    <div class="crm-section">
      <div class="label">{$form.description.label}</div>
      <div class="content">{$form.description.html}</div>
      <div class="clear"></div>
    </div>
    <div class="crm-section">
      <div class="label">{$form.help_text.label}</div>
      <div class="content">{$form.help_text.html}</div>
      <div class="clear"></div>
    </div>
    <div class="crm-section">
      <div class="label">{$form.success_message.label}</div>
      <div class="content">{$form.success_message.html}</div>
      <div class="clear"></div>
    </div>
    <div class="crm-section">
      <div class="label">{$form.is_active.label}</div>
      <div class="content">{$form.is_active.html}</div>
      <div class="clear"></div>
    </div>
    <div class="crm-section">
      <div class="label">{$form.records_per_batch.label}</div>
      <div class="content">{$form.records_per_batch.html}</div>
      <div class="clear"></div>
    </div>
  </div>

  {if $search_task_id}
    {include file="CRM/Searchactiondesigner/Form/Blocks/Fields.tpl"}
    {include file="CRM/Searchactiondesigner/Form/Blocks/Actions.tpl"}
  {/if}

  <script type="text/javascript">
    {literal}
    CRM.$(function($) {
      var id = {/literal}{if ($actionObject)}{$actionObject.id}{else}false{/if}{literal};

      $('#title').on('blur', function() {
        var title = $('#title').val();
        if ($('#nameSection').hasClass('hiddenElement') && !id) {
          CRM.api3('SearchTask', 'check_name', {
            'title': title
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

{/if}

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
{/crmScope}