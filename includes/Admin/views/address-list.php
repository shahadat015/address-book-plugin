<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Address Book', 'address-book') ?></h1>
    <a href="<?php echo admin_url( 'admin.php?page=address-book&action=new') ?>" class="page-title-action"><?php _e('Add New', 'address-book') ?></a>

    <?php if ( isset( $_GET['inserted'] ) ) { ?>
        <div class="notice notice-success">
            <p><?php _e('Address has been added successfully', 'address-book') ?></p>
        </div>
    <?php } ?>

    <?php if ( isset( $_GET['address-deleted'] ) ) { ?>
        <div class="notice notice-success">
            <p><?php _e('Address has been deleted successfully', 'address-book') ?></p>
        </div>
    <?php } ?>

    <form action="" method="post">
        <?php
            $table = new Address\Book\Admin\Address_List();
            $table->prepare_items();
            $table->search_box('search', 'search_id');
            $table->display();
        ?>
    </form>
</div>