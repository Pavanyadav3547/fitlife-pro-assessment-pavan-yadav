<?php
/**
 * Custom Search Form Template for FitLife Pro
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label style="flex-grow: 1; margin: 0;">
        <span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'fitlife' ); ?></span>
        <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search keyword...', 'placeholder', 'fitlife' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    </label>
    <button type="submit" class="search-submit" aria-label="<?php echo esc_attr_x( 'Submit Search', 'submit button label', 'fitlife' ); ?>">
        <i class="fa-solid fa-magnifying-glass"></i>
    </button>
</form>
