<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('New Address', 'address-book') ?></h1>

    <form action="" method="post">
        <table class="form-table">
            <tbody>
                <tr class="row <?php echo $this->has_error( 'name' ) ? 'form-invalid' : '' ;?>">
                    <th scope="row">
                        <label for="name"><?php _e('Name', 'address-book') ?></label>
                    </th>
                    <td>
                        <input type="text" name="name" id="name" class="regular-text" value="<?php echo esc_attr($_POST['name'] ?? "") ?>">

                        <?php if ( $this->has_error( 'name' ) ) { ?>
                            <p class="description error"><?php echo $this->get_error( 'name' ) ?></p>
                        <?php } ?>
                    </td>
                </tr>
                <tr class="row <?php echo $this->has_error( 'name' ) ? 'form-invalid' : '' ;?>">
                    <th scope="row">
                        <label for="phone"><?php _e('Phone', 'address-book') ?></label>
                    </th>
                    <td>
                        <input type="text" name="phone" id="phone" class="regular-text" value="<?php echo esc_attr($_POST['phone'] ?? "") ?>">

                        <?php if ( $this->has_error( 'phone' ) ) { ?>
                            <p class="description error"><?php echo $this->get_error( 'phone' ) ?></p>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="address"><?php _e('Address', 'address-book') ?></label>
                    </th>
                    <td>
                        <textarea name="address" id="address" cols="10" rows="5" class="regular-text"></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php wp_nonce_field( 'new-address', '_wpnonce' ) ?>
        <?php submit_button( __('Add Address'), 'primary', 'submit_address') ?>
    </form>
</div>