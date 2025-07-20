<aside id="sidebar" class="miku-sidebar">
    <div class="sidebar-toggle">
        <span class="toggle-icon">☰</span>
    </div>

    <div class="sidebar-content">
        <div class="sidebar-header">
            <h3 class="sidebar-title">Menu</h3>
            <span class="close-sidebar">✕</span>
        </div>

        <div class="sidebar-widgets">

            <?php if (is_user_logged_in()) : ?>
            <div class="widget user-actions">
                <h4 class="widget-title">Actions utilisateur</h4>
                <div class="widget-content">
                    <ul class="sidebar-menu user-menu">
                        <li><a href="<?php echo home_url('/creer-une-question'); ?>" class="create-question">
                                <span class="menu-icon">❓</span>Créer une question
                            </a></li>
                        <li><a href="<?php echo home_url('/passer-un-quiz'); ?>" class="take-quiz">
                                <span class="menu-icon">🎓</span>Passer un quiz
                            </a></li>
                        <?php if (current_user_can('administrator')) : ?>
                        <li><a href="<?php echo home_url('/page-admin'); ?>" class="admin-articles">
                                <span class="menu-icon">⚙️</span>Articles Admin
                            </a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="sidebar-overlay"></div>
</aside>