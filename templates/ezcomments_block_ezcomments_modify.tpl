<div class="z-formrow">
    <label for="ezcomments_numentries">{gt text="Number of comments to display" domain="module_ezcomments"}</label>
    <input id="ezcomments_numentries" type="text" name="numentries" value="{$numentries|safehtml}" size="5" maxlength="5" />
</div>
<div class="z-formrow">
    <label for="ezcomments_numdays">{gt text="Only comments posted in last number of days" domain="module_ezcomments"}</label>
    <input id="ezcomments_numdays" type="text" name="numdays" value="{$numdays|safehtml}" size="5" maxlength="5" />
    <em class="z-sub z-formnote">{gt text='Leave 0 for unlimited.'}</em>
</div>
<div class="z-formrow">
    <label for="ezcomments_modname">{gt text="Show comments for the following module" domain="module_ezcomments"}</label>
    <select id="ezcomments_modname" name="mod">
        <option label="*" value="*" {if $mod eq "*"}selected="selected"{/if}>{gt text="All" domain="module_ezcomments"}</option>
        {html_options values=$usermods output=$usermods selected=$mod}
    </select>
</div>
<div class="z-formrow">
    <label for="ezcomments_showusername">{gt text="Show username" domain="module_ezcomments"}</label>
    {if $showusername}
    <input id="ezcomments_showusername" type="checkbox" name="showusername" value="1" checked="checked" />
    {else}
    <input id="ezcomments_showusername" type="checkbox" name="showusername" value="1" />
    {/if}
</div>
<div class="z-formrow">
    <label for="ezcomments_linkusername">{gt text="Link username to profile" domain="module_ezcomments"}</label>
    {if $linkusername}
    <input id="ezcomments_linkusername" type="checkbox" name="linkusername" value="1" checked="checked" />
    {else}
    <input id="ezcomments_linkusername" type="checkbox" name="linkusername" value="1" />
    {/if}
</div>
<div class="z-formrow">
    <label for="ezcomments_showdate">{gt text="Show Date" domain="module_ezcomments"}</label>
    {if $showdate}
    <input id="ezcomments_showdate" type="checkbox" name="showdate" value="1" checked="checked" />
    {else}
    <input id="ezcomments_showdate" type="checkbox" name="showdate" value="1" />
    {/if}
</div>
<div class="z-formrow">
    <label for="ezcomments_showpending">{gt text="Show pending comments" domain="module_ezcomments"}</label>
    {if $showpending}
    <input id="ezcomments_showpending" type="checkbox" name="showpending" value="1" checked="checked" />
    {else}
    <input id="ezcomments_showpending" type="checkbox" name="showpending" value="1" />
    {/if}
</div>
