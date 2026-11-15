<?php
/**
 * Renders a PG listing card. $row is a tblpg row.
 */
function render_pg_card(array $row): void
{
    $id = (int) $row['ID'];
    $rent = is_numeric($row['RPmonth'] ?? null) ? number_format((float) $row['RPmonth']) : h($row['RPmonth'] ?? '');
    $features = [
        'AC'       => $row['AC'] ?? '',
        'Parking'  => $row['Parking'] ?? '',
        'Laundry'  => $row['Laundry'] ?? '',
        'Balcony'  => $row['Balcony'] ?? '',
        'Furnished' => $row['Furnished'] ?? '',
    ];
    ?>
    <a class="pg-card" href="pg-details.php?pid=<?= $id ?>">
        <div class="pg-card__media">
            <img src="owner/roomimages/<?= rawurlencode(basename((string) $row['Image'])) ?>"
                alt="<?= h($row['PGTitle']) ?>" loading="lazy" onerror="this.src='img/default-property.jpg'">
            <?php if (!empty($row['Type'])) { ?><span class="pg-card__tag"><?= h($row['Type']) ?></span><?php } ?>
            <span class="pg-card__price">&#8377;<?= $rent ?> <small>/ month</small></span>
        </div>
        <div class="pg-card__body">
            <h3 class="pg-card__title"><?= h($row['PGTitle']) ?></h3>
            <p class="pg-card__location"><span class="lnr lnr-map-marker"></span><?= h(trim(($row['CityName'] ?? '') . ', ' . ($row['StateName'] ?? ''), ', ')) ?></p>
            <ul class="pg-card__features">
                <?php foreach ($features as $label => $value) {
                    $yes = strcasecmp(trim((string) $value), 'Yes') === 0;
                    if (!$yes) {
                        continue;
                    } ?>
                    <li class="is-yes"><?= h($label) ?></li>
                <?php } ?>
            </ul>
        </div>
    </a>
    <?php
}
