<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
    <div class="p-4">
        <h1 class="display-4 mb-4">
            Тестовое задание
        </h1>
        <a class="btn btn-primary btn-lg" href="/comments" role="button">
            Комментарии
        </a>
    </div>

<?= $this->endSection() ?>
