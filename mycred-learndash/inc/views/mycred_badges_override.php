<table class="widefat">
    <tbody>
        <tr>
            <td><label><?php _e("Select a badge To assign", "mycred-learndash"); ?></label></td>
            <td>
                <?php if (mycred_get_badge_ids()): ?>
                    <select name="myCred_badges_override">
                        <option value=""><?php _e("Select a badge", "mycred-learndash"); ?></option>
                        <?php foreach (mycred_get_badge_ids() as $id): ?>
                            <option value="<?php echo $id; ?>" <?php echo $id == $badge ? "selected" : "" ?>><?php echo mycred_get_badge($id)->title; ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </td>
        </tr>
    </tbody>
</table>