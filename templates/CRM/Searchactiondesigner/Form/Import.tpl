{crmScope extensionKey='searchactiondesigner'}
  <h3>{ts}Import search task{/ts}</h3>
  <div class="crm-block crm-form-block">
    <h3>{ts}Code{/ts}</h3>
    {$form.code.html}
  </div>

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
{/crmScope}