<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<table>
<tr>
    <th>remove</th>
    <th>Short code</th>
    <th>Description</th>
</tr>
<?php
    foreach ($kinds as $kind):
?>
<tr>
    <td>
        <input type="checkbox" name="tl_stream_delete[]" value="<?php echo $kind->id; ?>" />
    </td>
    <td>
        <input type="text" size="10" name="tl_stream_config[<?php echo $kind->id; ?>][short_code]" value="<?php echo $kind->short_code; ?>" />
    </td>
    <td>
        <input type="text" size="40" name="tl_stream_config[<?php echo $kind->id; ?>][description]" value="<?php echo $kind->description; ?>" />
    </td>
</tr>
<?php
    endforeach;
?>
<tr>
    <td>
        new
    </td>
    <td>
        <input type="text" size="10" name="tl_new_stream_config[short_code]" value="" />
    </td>
    <td>
        <input type="text" size="40" name="tl_new_stream_config[description]" value="" />
    </td>
</tr>
</table>

<input type="submit" value="Save" />

</form>


<?php




?>