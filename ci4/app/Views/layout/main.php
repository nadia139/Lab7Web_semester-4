<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? 'Website' ?></title>
    <link rel="stylesheet" href="<?= base_url('/style.css');?>">
</head>
<body>

<header>
    <h1>My Website</h1>
</header>

<nav>
    <a href="<?= base_url('/') ?>">Home</a>
    <a href="<?= base_url('/artikel') ?>">Artikel</a>
</nav>

<div style="display:flex">
    <div style="width:70%">
        <?= $this->renderSection('content') ?>
    </div>

    <div style="width:30%">
        <?= view_cell('App\\Cells\\ArtikelTerkini::render') ?>
    </div>
</div>

<footer>
    <p>Footer</p>
</footer>

</body>
</html>