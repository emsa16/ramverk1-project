<footer>
    <hr />
    <p>Copyright &copy; 2018 - Emil Sandberg</p>
    <?php if ($this->di->session->has("admin")) : ?>
        <p><a href="<?= $this->url('admin') ?>">Admin</a></p>
    <?php endif; ?>
</footer>
