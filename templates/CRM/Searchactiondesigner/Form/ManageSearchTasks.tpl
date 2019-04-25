{crmScope extensionKey='searchactiondesigner'}

  <div class="crm-content-block">

    <div class="crm-block crm-form-block crm-basic-criteria-form-block">
      <div class="crm-accordion-wrapper crm-search-task-builder_search-accordion collapsed">
        <div class="crm-accordion-header crm-master-accordion-header">{ts}Filter Search Actions{/ts}</div><!-- /.crm-accordion-header -->
        <div class="crm-accordion-body">
          <table class="form-layout">
            <tbody>
            <tr>
              <td style="width: 25%;">
                <label>{$form.title.label}</label><br>
                {$form.title.html}
              </td>
              <td style="width: 25%;">
                <label>{$form.type.label}</label><br>
                {$form.type.html}
              </td>
              <td style="width: 25%;"></td>
              <td style="width: 25%;"></td>
            </tr>
            <tr>
              <td>
                <label>{$form.description.label}</label><br>
                {$form.description.html}
              </td>
              <td style="width: 25%;">
                <label>{$form.is_active.label}</label><br>
                {$form.is_active.html}
              </td>
              <td></td>
              <td></td>
            </tr>
            </tbody>
          </table>
          <div class="crm-submit-buttons">
            {include file="CRM/common/formButtons.tpl"}
          </div>
        </div><!- /.crm-accordion-body -->
      </div><!-- /.crm-accordion-wrapper -->
    </div><!-- /.crm-form-block -->


    <div class="action-link">
      <a class="button" href="{crmURL p="civicrm/searchactiondesigner/edit" q="reset=1&action=add" }">
        <i class="crm-i fa-plus-circle">&nbsp;</i>
        {ts}Add Search Action{/ts}
      </a>
      <a class="button" href="{crmURL p="civicrm/searchactiondesigner/import" q="reset=1&action=add" }">
        <i class="crm-i fa-upload">&nbsp;</i>
        {ts}Import Search Action{/ts}
      </a>
    </div>

    <div class="clear"></div>

    <div class="crm-results-block">
      {include file="CRM/common/pager.tpl" location="top"}

      <div class="crm-search-results">
        <table class="selector row-highlight">
          <thead class="sticky">
          <tr>
            <th scope="col" >{ts}Title{/ts}</th>
            <th scope="col" >{ts}Description{/ts}</th>
            <th scope="col" >{ts}Available for{/ts}</th>
            <th scope="col" >{ts}Is active{/ts}</th>
            <th scope="col" >{ts}Status{/ts}</th>
            <th>&nbsp;</th>
          </tr>
          </thead>
          {foreach from=$search_tasks item=search_task}
            {assign var="search_task_type" value=$search_task.type}
            <tr>
              <td>{$search_task.title}</td>
              <td>{$search_task.description}</td>
              <td>{$types.$search_task_type}</td>
              {if $search_task.is_active eq 1}
                <td><span><a href="{crmURL p='civicrm/searchactiondesigner/edit' q="reset=1&action=disable&id=`$search_task.id`"}"
                             class="" title="{ts}Disable Search Task{/ts}">{ts}Enabled{/ts}</a></span></td>
              {else}
                <td><span><a href="{crmURL p='civicrm/searchactiondesigner/edit' q="reset=1&action=enable&id=`$search_task.id`"}"
                             class="" title="{ts}Enable Search Task{/ts}">{ts}Disabled{/ts}</a></span></td>
              {/if}
              <td>
                {$search_task.status_label}
                {if ($search_task.status eq 3)}
                  <span>
                      <a href="{crmURL p='civicrm/searchactiondesigner/edit' q="reset=1&action=revert&id=`$search_task.id`"}"  class="" title="{ts}Revert Search Task{/ts}">
                          {ts}Revert{/ts}
                      </a>
                  </span>
                {/if}
              </td>
              <td>
                  <span>
                  <a href="{crmURL p='civicrm/searchactiondesigner/edit' q="reset=1&action=update&id=`$search_task.id`"}"
                     class="action-item crm-hover-button" title="{ts}Edit Search Task{/ts}">{ts}Edit{/ts}</a>
                  <a href="{crmURL p='civicrm/searchactiondesigner/edit' q="reset=1&action=export&id=`$search_task.id`"}"
                     class="action-item crm-hover-button" title="{ts}Export Search Task{/ts}">{ts}Export{/ts}</a>
                  <a href="{crmURL p='civicrm/searchactiondesigner/edit' q="reset=1&action=delete&id=`$search_task.id`"}"
                     class="action-item crm-hover-button" title="{ts}Delete Search Task{/ts}">{ts}Delete{/ts}</a></span>

              </td>
            </tr>
          {/foreach}
        </table>
      </div>

      {include file="CRM/common/pager.tpl" location="bottom"}
    </div>
  </div>
{/crmScope}