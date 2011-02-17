{include file="ezcomments_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='EZComments' src='admin.gif' alt='' }</div>
    <h2>{gt text="Comment statistics"} - {gt text="Module"}: {$name|safetext}</h2>
    <table class="z-admintable">
        <thead>
            <tr>
                <th>{gt text="Item ID"}</th>
                <th>{gt text="Total comments"}</th>
                <th>{gt text="Approved"}</th>
                <th>{gt text="Pending"}</th>
                <th>{gt text="Rejected"}</th>
                <th>{gt text="Options"}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$commentstats item=commentstat}
            <tr class="{cycle values=z-odd,z-even}">
                <td><a href="{$commentstat.url|urldecode|safetext}">{$commentstat.objectid|safetext}</a></td>
                <td>{$commentstat.totalcomments|safetext}</td>
                <td>{$commentstat.approvedcomments|safetext}</td>
                <td>{$commentstat.pendingcomments|safetext}</td>
                <td>{$commentstat.rejectedcomments|safetext}</td>
                <td>
                    <a href="{modurl modname=EZComments type=admin func=deleteitem mod=$name objectid=$commentstat.objectid}">{img modname='core' set='icons/extrasmall' src='14_layer_deletelayer.png' __title='Delete' __alt='Delete'}</a>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>