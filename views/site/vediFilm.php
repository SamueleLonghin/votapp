<div class="image">
    <h1 style="text-align: center"><?= strtoupper($model['Titolo']) ?></h1>
    <img style="border-radius: 20px 20px 20px 20px;width: 100%; " src="<?= $model['Image'] ?>">
</div>


<div class="site-index">
    <h2 style="color: whitesmoke">Autore:</h2>
    <h4 style="color: whitesmoke"><?= $model['Autore'] ?></h4>
    <h2 style="margin-top: 20px">Descrizione:</h2>
    <p><?= $model['Descrizione'] ?></p>
</div>
<style>
    .container > img {
        width: 100%;
    }
</style>