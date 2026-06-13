<?php
session_start();

require_once 'includes/i18n.php';
require_once 'includes/auth.php';
require_once 'actions/media_actions.php';
require_once 'includes/db.php';

$tab = $_GET['tab'] ?? 'stats';

if (isset($_GET['heatmap_view'])) {
    require_once 'views/heatmap.php';
    exit;
}

require_once 'includes/header.php';

if ($tab === 'stats') {
    require_once 'views/tab_stats.php';
} elseif ($tab === 'landings') {
    require_once 'views/tab_landings.php';
} elseif ($tab === 'campaigns') {
    require_once 'views/tab_campaigns.php';
} elseif ($tab === 'leads') {
    require_once 'views/tab_leads.php';
} elseif ($tab === 'media') {
    require_once 'views/tab_media.php';
}

require_once 'includes/footer.php';
