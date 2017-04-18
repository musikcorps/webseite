<h3>Nächste Geburtstage:</h3>
<ul>
    <?php foreach ($this->birthdays as $b): ?>
        <li><?= "$b->birthday_f: $b->firstname $b->lastname" ?></li>
    <?php endforeach ?>
</ul>