<?php

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

// Get Active Tab
$activeTab = '';
$requestHelper = vchelper('Request');
if ($requestHelper->exists('page')) {
    $activeTab = esc_attr($requestHelper->input('page'));
}
$outputHelper = vchelper('Output');
$activeClass = $activeTab === $slug ? 'vcv-dashboards-section-content--active' : '';
$pageTitle = empty($page['subTitle']) ? $page['title'] : $page['subTitle'];
$pageDescription = empty($page['description']) ? '' : $page['description'];
?>
<div class="vcv-dashboards-section-content vcv-dashboards-section--standalone <?php echo esc_attr($activeClass); ?>" data-section="<?php echo esc_attr($slug); ?>">
    <?php
    if ($pageTitle) {
        echo '<h1 style="' . (isset($page['hideTitle']) && $page['hideTitle'] ? 'display:none;' : '') . '">' . esc_html($pageTitle) . '</h1>';
    }
    if ($pageDescription) {
        echo '<p class="description">' . esc_html($pageDescription) . '</p>';
    }
    $outputHelper->printNotEscaped($content);
    ?>
</div>
