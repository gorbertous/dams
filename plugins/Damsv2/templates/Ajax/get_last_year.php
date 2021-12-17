<?php
foreach ($years as $year)
{
    $year = $year['period_year'];
    ?>
    <option value="<?= $year; ?>"><?= $year; ?></option><?php
}