{crmScope extensionKey='searchactiondesigner'}
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="top"}
  </div>

{if $action eq 8}
  {* Are you sure to delete form *}
  <h3>{ts}Delete Search Task{/ts}</h3>
  <div class="crm-block crm-form-block crm-search-task_label-block">
    <div class="crm-section">{ts 1=$rule->label}Are you sure to delete search task: '%1'?{/ts}</div>
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

  <h3>Search Task Builder</h3>
  <div class="crm-block crm-form-block crm-search-task_title-block">
    <div class="crm-section">
      <div class="label">{$form.type.label}</div>
      <div class="content">{$form.type.html}</div>
      <div class="clear"></div>
    </div>
    <div class="crm-section">
      <div class="label">{$form.title.label}</div>
      <div class="content">{$form.title.html}</div>
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

{/if}

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
{/crmScope}