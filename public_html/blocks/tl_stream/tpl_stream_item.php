
    <div class="tl_stream_item">
        <img src="blocks/tl_stream/images/transparent.png" class="stream_icon stream_icon_<?php echo $item->short_code; ?>" />
        <h3><?php echo $stream_handler->display_title($item->title); ?></h3>
        <div class="tl_date"><?php echo $stream_handler->timeago($item->date_and_time); ?></div>
        <div style="clear:both;"></div>
        <div class="tl_contents">
        <?php echo $item->contents; ?>
        <?php if ($stream_handler->has_delete($item)): ?><label class="tl_stream_delete"><input type="checkbox" name="tl_stream_delete[]" class="tl_stream_delete" value="<?php echo $item->id; ?>" /> wis</label><?php endif; ?>
        <?php if ($stream_handler->has_read_more()): ?>
        <a class="tl_stream_read_more" href="<?php echo $stream_handler->get_read_more_link($item); ?>"><?php echo print_string('read_more', 'block_tl_stream'); ?> &gt;</a>
        <?php endif; ?>
        </div>
    </div>
