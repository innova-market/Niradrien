<br />
<a class="btn" href="?m={$smarty.get.m}&c=list&p={$smarty.get.p}">{$lang.back}</a>
<br /><br />

<form id="sampleform" action="" method="post">
	<p>
		<label for="imember">{$lang.member}:</label><br/>
		{$data.member.username}
	</p>

	<p>
		<label for="iname">{$lang.name}:</label><br/>
		{$data.name}
	</p>

	<p>
		<label for="idesc">{$lang.description}:</label><br/>
		<textarea id="idesc" name="description" class="large full">{$data.description}</textarea>
	</p>

	<p>
		<label for="iprice">{$lang.price}:</label><br/>
		{$currency.symbol} <input id="iprice" type="text" name="price" value="{$smarty.post.price|escape}" class="half" />
	</p>

	<p>
		<label for="ifiles">{$lang.files}:</label><br/>
		<img src="{$data_server}/uploads/product/{$smarty.get.id}/{$data.thumbnail}" alt="" /> <br />
		<img src="{$data_server}/uploads/product/{$smarty.get.id}/preview.jpg" alt="" /> <br />

		<strong>{$lang.theme_preview}:</strong> <a href="{$data_server}/uploads/product/{$smarty.get.id}/{$data.theme_preview}">{$data.theme_preview}</a> <br />
		<strong>{$lang.main_file}:</strong> <a href="{$data_server}/uploads/product/{$smarty.get.id}/{$data.main_file}">{$data.main_file}</a> <br />
	</p>

	<p>
		<label for="icategory">{$lang.category}:</label><br/>
		{foreach from=$data.categories item=c name=foo}
			{$categories[$c].name} {if !$smarty.foreach.foo.last} &rsaquo; {/if}
		{/foreach}
	</p>

	<p>
		<label for="idemourl">{$lang.demo_url}:</label><br/>
		{if $data.demo_url != ''}
			<a href="{$data.demo_url}" target="_blank">{$data.demo_url}</a><br />
			<input id="idemourl" type="text" name="demo_url" value="{$smarty.post.demo_url|escape}" class="half" />
		{else}
			{$lang.none1}
		{/if}
	</p>

	{if $attributes}
	  	{foreach from=$attributes item=a}
			<p>
				<label>{$a.name}:</label><br />
				{if $a.type == 'select'}
					<select class="input big" id="custom_attributes_{$a.id}" name="attributes[{$a.id}]">
						{foreach from=$a.attributes item=ai}
							<option value="{$ai.id}" {if $smarty.post.attributes[$a.id] == $ai.id}selected="selected"{/if}>{$ai.name}</option>
						{/foreach}
					</select>
				{elseif $a.type == 'check'}
			  		{foreach from=$a.attributes item=ai}
	      				<input type="checkbox" name="attributes[{$a.id}][{$ai.id}]" value="{$ai.id}" {if isset($smarty.post.attributes[$a.id][$ai.id])}checked="checked"{/if} />{$ai.name}<br />
	      			{/foreach}
	      		{elseif $a.type == 'radio'}
			  		{foreach from=$a.attributes item=ai}
			  			<input type="radio" name="attributes[{$a.id}]" value="{$ai.id}" {if isset($smarty.post.attributes[$a.id]) && $smarty.post.attributes[$a.id] == $ai.id}checked="checked"{/if} /> {$ai.name}<br />
			  		{/foreach}
			  	{else}
			  		<input class="big input" type="text" name="attributes[{$a.id}]" value="{$smarty.post.attributes[$a.id]}" />
			  	{/if}
			</p>
		{/foreach}
	{/if}

	<p>
		<label for="itags">{$lang.tags}:</label>
		<br />
		<abbr>{$lang.usage_tags}:</abbr>
		{foreach from=$data.tags.usage item=t}
			{$t},
		{/foreach}
		<br />

		<abbr>{$lang.style_tags}:</abbr>
		{foreach from=$data.tags.style item=t}
			{$t},
		{/foreach}
		<br />

		<abbr>{$lang.tags}:</abbr>
		{foreach from=$data.tags item=t}
			{$t},
		{/foreach}
	</p>

	<p>
		<label for="ifreerequest">{$lang.want_to_be_freefile}:</label><br />
		{if $smarty.post.free_request == 'true'}
			<img src="{$data_server}/admin/images/icons/24x24/accept.png" alt="" />
		{else}
			<img src="{$data_server}/admin/images/icons/24x24/delete.png" alt="" />
		{/if}
		<input type="hidden" name="free_request" value="{$smarty.post.free_request}" />
	</p>

	<p>
		<label for="ifreefile">{$lang.free_file}:</label><br/>
		<input id="ifreefile" type="checkbox" name="free_file" value="true" {if $smarty.post.free_file == 'true'}checked="checked"{/if} /> {$lang.yes}<br />
	</p>

	<p>
		<label for="iweekly">{$lang.weekly_features_to}:</label><br/>
		<input type="date" id="datepick" name="weekly_to" value="{$smarty.post.weekly_to|escape}" />
	</p>

	<script src="{$data_server}/admin/js/jquery.datepick.pack.js"></script>
	<script src="{$data_server}/admin/js/jquery.datepick-en-GB.js"></script>

	{literal}
		<script>
			$(document).ready(function() {
				Administry.dateInput('#datepick');
			});
		</script>
	{/literal}

	<p class="box">
		<input class="btn btn-green big" type="submit" name="edit" value="{$lang.edit}" />
	</p>
</form>