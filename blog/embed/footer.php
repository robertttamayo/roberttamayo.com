<footer>
    <div class="logo imglink">
        <a href="<?= SITE_URL ?>" title="Estella Event Photography Logo">
            <img src="<?= $img['logo']['src'] ?>" alt="<?= $img['logo']['alt'] ?> ">
        </a>
    </div>
    <div class="footer-nav">
        <div class="footer-nav-item">
            <a href="<?= SITE_URL ?>">Home</a>
        </div>
        <div class="footer-nav-item">
            <a href="<?= $href['about']['href'] ?>">About</a>
        </div>
        <div class="footer-nav-item">
            <a href="<?= SITE_URL ?>/gallery.php" title="Estella Photography Gallery">Portfolio</a>
        </div>
        <div class="footer-nav-item">
            <a href="<?= SITE_URL ?>/blog/" title="Estella Photography Blog">Blog</a>
        </div>
        <div class="footer-nav-item">
            <a class="contact-info" href="#">Contact</a>
        </div>
    </div>
</footer>
<div class="contact-form" style="display: none;">
    <div class="contact-email">
        <div class="contact-tagline">
            Want more info? 
            <br>
            Send me an email!
        </div>
        <a href="mailto:info@estellaphoto.com" title="Email info@estellaphoto.com">info@estellaphoto.com</a>
    </div>
    <div class="contact-close">x</div>
</div>