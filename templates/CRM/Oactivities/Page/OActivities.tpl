<div class="crm-block crm-content-block crm-oactivities-view-form-block">

  <table class="crm-info-panel">
    <thead>
    <tr>
      <th class="label">{ts}Contact{/ts}</th>
      <th class="label">{ts}Activity Subject{/ts}</th>
      <th class="label">{ts}Activity Type{/ts}</th>
      <th class="label">{ts}Activity Date{/ts}</th>
      <th class="label">{ts}Operations{/ts}</th>
    </tr>
    </thead>

    <tbody>
    {foreach from=$activities item=activity}
      {if $activity}
        <tr>
          <td><a href='{crmURL p='civicrm/contact/view' q="cid=`$activity.contact.contact_id`&reset=1"}'>{$activity.contact.display_name}</a>&nbsp;&nbsp;(ID:{$activity.contact.contact_id})</td>
          <td>{$activity.activity.subject}</td>
          <td>
            {crmAPI var='result' entity='OptionValue' action='get' return="name" id=$activity.activity.activity_type_id}
            {foreach from=$result.values item=optionvalue}
              {$optionvalue.name}
            {/foreach}
          </td>
          <td>{$activity.activity.activity_date_time}</td>
          <td><a href='{crmURL p='civicrm/activity' q="id=`$activity.activity.id`&reset=1&action=view"}'>{ts}View{/ts}</a></td>

        </tr>
      {/if}
    {/foreach}
    </tbody>

    </tr>
  </table>
</div>
