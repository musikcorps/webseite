<ul>
    <?php foreach ($this->items as $member): ?>
        <li><?= "$member->firstname $member->lastname ($member->instrument seit $member->active_since)" ?></li>
    <?php endforeach ?>
</ul>