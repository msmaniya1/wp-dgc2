<?php

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}
/** @var string $value */
/** @var string $key */
$outputHelper = vchelper('Output');
?>
<style id="vcv-style-<?php echo esc_attr(vchelper('Str')->slugify($key)); ?>">
    <?php
    $outputHelper->printNotEscaped($value);
    ?>
</style>
