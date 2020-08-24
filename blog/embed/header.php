<header>
    <div class="logo imglink">
        <a href="<?= SITE_URL ?>" title="Estella Event Photography Logo">
            <img src="<?= $img['logo']['src'] ?>" alt="<?= $img['logo']['alt'] ?> ">
        </a>
    </div>
    <nav>
        <div class="menu-button uppercase fc-blue js-menu-button-open">
            <span class="fc-alt-blue">+</span> menu
        </div>
        <div class="submenu">
            <div class="submenu-title">
                NAVIGATE
                <div class="submenu-title-border"></div>
            </div>
            <div class="submenu-close js-menu-button-close">&#10005;</div>
            <nav class="submenu-links">
                <a href="<?= $href['about']['href'] ?>" title="<?= $href['about']['title'] ?>">
                    About
                </a>
                <a href="<?= SITE_URL ?>/gallery.php" title="Estella Photography Gallery">
                    Photos
                </a>
                <a class="contact-info" href="#" title="">
                    Contact
                </a>
                <a href="<?= SITE_URL ?>/blog/" title="Estella Photography Blog">
                    Blog
                </a>
            </nav>
            <div class="submenu-connect">
                <div>Connect with us:</div>
                <div class="submenu-connect-icons">
                    <a href="<?= $facebook_link ?>" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                    <a href="<?= $twitter_link ?>" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                    <a href="<?= $pinterest_link ?>" target="_blank"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
                    <a href="<?= $instagram_link ?>" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </nav>
</header>