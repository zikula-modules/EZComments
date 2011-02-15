{include file="ezcomments_admin_menu.tpl"}
{ajaxheader ui=true}
{gt text="unknown" assign=lblunknown}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='EZComments' src='admin.gif' alt='' }</div>
    {if $showall neq 1}
    <h2>{gt text="The last %s comment" plural="The last %s comments" count=$itemsperpage tag1=$itemsperpage}</h2>
    {else}
    <h2>{gt text="All %s comments" tag1=$status|commentstatus"}</h2>
    {/if}
    <form class="z-form" action="{modurl modname=EZComments type=admin}" method="post">
        <fieldset>
            <label for="ezcomments_filter">{gt text="Filter by status"}</label>
            <select id="ezcomments_filter" name="status">
                <option value="-1"{if $status eq -1} selected="selected"{/if}>{gt text="All"}</option>
                <option value="0"{if $status eq 0} selected="selected"{/if}>{gt text="Approved"}</option>
                <option value="1"{if $status eq 1} selected="selected"{/if}>{gt text="Pending"}</option>
                <option value="2"{if $status eq 2} selected="selected"{/if}>{gt text="Rejected"}</option>
            </select>
            <label for="ezcomments_showall">{gt text="Show all comments"}</label>
            <input id="ezcomments_showall" type="checkbox" name="showall" value="1"{if $showall eq 1} checked="checked"{/if} />
            <input type="submit" value="{gt text="Filter"}" />
        </fieldset>
    </form>

    <form class="z-form" id="ezcomments_view" action="{modurl modname=EZComments type=admin func=processselected}" method="post">
        <div>
            <input type="hidden" name="authid" value="{insert name='generateauthkey' module='EZComments'}" />
            <table id="ezcomments_comments" class="z-admintable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="toggle_comments" /></th>
                        <th>{gt text="Status"}</th>
                        <th>{gt text="Date"}</th>
                        <th>
                            <span class="z-nowrap">{gt text="Commentator"}</span><br />
                            <span class="z-nowrap">{gt text="Owner"}</span>
                        </th>
                        <th>
                            <span class="z-nowrap">{gt text="Module"}</span><br />
                            <span class="z-nowrap">{gt text="Comment type"}</span>
                        </th>
                        <th>{gt text="Comment"}</th>
                        <th>{gt text="Options"}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$items item=item}
                    <tr class="{cycle values=z-odd,z-even}">
                        <td><input type="checkbox" name="comments[]" value="{$item.id}" class="comments_checkbox" /></td>
                        <td>
                            {if $item.status eq 0}{img modname="EZComments" src='green.gif'   __alt='Approved' __title='Approved'}{/if}
                            {if $item.status eq 1}{img modname="EZComments" src='yellow.gif'   __alt='Pending' __title='Pending'}{/if}
                            {if $item.status eq 2}{img modname="EZComments" src='red.gif'   __alt='Rejected' __title='Rejected'}{/if}
                        </td>
                        <td>
                            <span class="z-nowrap">{$item.date|dateformat:datebrief}</span>
                            <span class="z-nowrap">{$item.date|dateformat:timebrief}</span>
                        </td>
                        <td>
                            <span class="z-nowrap">
                                {if $item.uid neq 0}
                                {$item.uid|userprofilelink|default:$lblunknown}
                                {else}
                                {$item.anonname|safetext|default:$lblunknown}
                                {/if}
                            </span>
                            <br />
                            <span class="z-nowrap">
                                {if $item.owneruid ne "0"}
                                {$item.owneruid|userprofilelink|default:$lblunknown}
                                {else}
                                {$lblunknown}
                                {/if}
                            </span>
                        </td>
                        <td>
                            <a href="{$item.url|safetext}">{$item.mod|safetext}</a>
                            {if $item.type}{$item.type|safetext}{/if}
                        </td>
                        <td>
                            {if $item.subject}<em>{$item.subject|strip_tags}: </em>{/if}
                            {$item.comment|strip_tags|truncate:80}
                        </td>
                        <td>
                            {assign var="options" value=$item.options}
                            {section name=options loop=$options}
                            <a href="{$options[options].url|safetext}">{img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title}</a>
                            {/section}
                        </td>
                    </tr>
                    {foreachelse}
                    <tr class="z-admintableempty"><td colspan="7">{gt text="No items found"}</td></tr>
                    {/foreach}
                </tbody>
            </table>

            <script type="text/javascript">
                $('toggle_comments').observe('click', function(e){
                    Zikula.toggleInput('ezcomments_view');
                    e.stop()
                });
            </script>

            {pager show="page" rowcount=$pager.numitems limit=$pager.itemsperpage posvar=startnum shift=1}

            <fieldset>
                <label for="ezcomments_action">{gt text="With selected comments"}</label>
                <select id="ezcomments_action" name="action">
                    <option value="">{gt text="Choose action"}</option>
                    <option value="delete">{gt text="Delete"}</option>
                    <option value="approve">{gt text="Approve"}</option>
                    <option value="hold">{gt text="Hold"}</option>
                    <option value="reject">{gt text="Reject"}</option>
                </select>
                <input type="submit" value="{gt text="Submit"}" />
            </fieldset>
        </div>
    </form>

</div>
