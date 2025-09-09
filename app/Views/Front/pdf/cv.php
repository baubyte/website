<!DOCTYPE html>
<html lang="<?= session('locale') ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?= $profile->fullName ?> - Curriculum Vitae</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<style type="text/css">
    @page {
        margin: 4cm;
        size: A4;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'DejaVu Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 14px;
        line-height: 1.6;
        color: #2c3e50;
        background: #ffffff;
    }

    .container {
        max-width: 100%;
        position: relative;
    }

    /* Header Section */
    .header {
        background: #2c3e50;
        color: white;
        padding: 40px 30px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(50%, -50%);
    }

    .profile-section {
        position: relative;
        z-index: 2;
    }

    .profile-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.3);
        float: right;
        margin-left: 30px;
        object-fit: cover;
    }

    .profile-info h1 {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }

    .profile-info .title {
        font-size: 18px;
        font-weight: 400;
        opacity: 0.9;
        margin-bottom: 20px;
    }

    .contact-info {
        display: block;
        margin-top: 15px;
    }

    .contact-item {
        display: inline-block;
        margin-right: 25px;
        margin-bottom: 8px;
        font-size: 13px;
        opacity: 0.9;
    }

    .contact-item a {
        color: inherit;
        text-decoration: none;
        border-bottom: 1px dotted rgba(255, 255, 255, 0.5);
    }

    .contact-item a:hover {
        border-bottom: 1px solid white;
    }

    .clear {
        clear: both;
    }

    /* Main Content */
    .main-content {
        padding: 0 30px;
    }

    /* Description Section */
    .description {
        background: #f8f9fa;
        padding: 25px;
        border-left: 4px solid #3498db;
        margin-bottom: 35px;
        border-radius: 0 8px 8px 0;
    }

    .description p {
        font-size: 15px;
        line-height: 1.7;
        color: #555;
        text-align: justify;
        margin: 0;
    }

    /* Section Styles */
    .section {
        margin-bottom: 35px;
        page-break-inside: avoid;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 8px;
        border-bottom: 2px solid #3498db;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 50px;
        height: 2px;
        background: #e74c3c;
    }

    /* Skills Section */
    .skills-grid {
        margin-top: 15px;
    }

    .skill-item {
        background: white;
        padding: 12px 18px;
        margin-bottom: 8px;
        border-radius: 6px;
        border-left: 3px solid #3498db;
        position: relative;
        display: block;
        width: 100%;
        clear: both;
    }

    .skill-name {
        font-weight: 500;
        color: #2c3e50;
        margin-bottom: 4px;
    }

    .skill-percentage {
        font-size: 12px;
        color: #7f8c8d;
        font-weight: 600;
    }

    .skill-bar {
        width: 100%;
        height: 3px;
        background: #ecf0f1;
        border-radius: 2px;
        margin-top: 6px;
        position: relative;
    }

    .skill-progress {
        height: 100%;
        background: #3498db;
        border-radius: 2px;
        position: relative;
    }

    /* Experience Section */
    .experience-item {
        margin-bottom: 25px;
        padding: 20px;
        background: white;
        border-radius: 8px;
        border-left: 4px solid #2c3e50;
        position: relative;
    }

    .experience-header {
        margin-bottom: 12px;
    }

    .company-name {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 4px;
    }

    .position-duration {
        font-size: 13px;
        color: #7f8c8d;
        font-weight: 500;
    }

    .position-title {
        color: #3498db;
        font-weight: 500;
        margin-right: 10px;
    }

    .experience-description {
        color: #555;
        line-height: 1.6;
        margin-top: 10px;
    }

    /* Education Section */
    .education-item {
        margin-bottom: 20px;
        padding: 18px;
        background: #f8f9fa;
        border-radius: 6px;
        border-left: 3px solid #e74c3c;
    }

    .education-institution {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 6px;
    }

    .education-degree {
        color: #555;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .education-duration {
        font-size: 12px;
        color: #7f8c8d;
        font-weight: 600;
    }

    /* Social Links Section */
    .social-links {
        margin-top: 15px;
    }

    .social-link-item {
        margin-bottom: 20px;
        padding: 15px;
        background: white;
        border-radius: 6px;
        border-left: 3px solid #3498db;
    }

    .social-link-item strong {
        color: #2c3e50;
        font-weight: 600;
    }

    .social-link-item a {
        color: #3498db;
        text-decoration: none;
        font-weight: 500;
    }

    .social-description {
        margin-top: 5px;
        font-size: 12px;
        color: #7f8c8d;
        font-style: italic;
        margin-bottom: 0;
    }

    /* Utilities */
    .text-center {
        text-align: center;
    }

    .mb-large {
        margin-bottom: 40px;
    }

    .mt-small {
        margin-top: 10px;
    }

    .mt-medium {
        margin-top: 20px;
    }

    .mt-large {
        margin-top: 40px;
    }

    /* Print Optimizations */
    @media print {
        .header {
            background: #2c3e50 !important;
        }

        .section {
            page-break-inside: avoid;
        }

        .experience-item {
            page-break-inside: avoid;
        }
    }
</style>

<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <div class="profile-section">
                <img src="<?php echo base_url('uploads/profile/images/' . $profile->avatar) ?>" alt="<?= $profile->fullName ?>" class="profile-photo" />
                <div class="profile-info">
                    <h1><?= $profile->fullName ?></h1>
                    <div class="title"><?= $profile->{lang('App.specialty')} ?></div>
                    <div class="contact-info">
                        <span class="contact-item">Email: <a href="mailto:<?= $profile->email_contact ?>"><?= $profile->email_contact ?></a></span>
                        <span class="contact-item"><?= lang('App.language_title') ?>: <?= $profile->{lang('App.language')} ?></span>
                        <?php if (!empty($profile->github_url)): ?>
                            <span class="contact-item"><a href="<?= $profile->github_url ?>" target="_blank">GitHub</a></span>
                        <?php endif; ?>
                        <?php if (!empty($profile->linkedin_url)): ?>
                            <span class="contact-item"><a href="<?= $profile->linkedin_url ?>" target="_blank">LinkedIn</a></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="clear"></div>
            </div>
        </div>

        <div class="main-content">
            <!-- Description Section -->
            <div class="description">
                <p><?= $profile->{lang('App.description')} ?></p>
            </div>

            <!-- Skills Section -->
            <div class="section">
                <h2 class="section-title mt-medium">Skills</h2>
                <div class="skills-grid">
                    <?php foreach ($skills as $skill) : ?>
                        <div class="skill-item">
                            <div class="skill-name"><?= $skill->name ?></div>
                            <div class="skill-percentage"><?= $skill->percentage ?>%</div>
                            <div class="skill-bar">
                                <div class="skill-progress" style="width: <?= $skill->percentage ?>%;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Experience Section -->
            <div class="section">
                <h2 class="section-title mt-large"><?= lang('App.experience') ?></h2>
                <?php foreach ($experiences as $experience) : ?>
                    <div class="experience-item">
                        <div class="experience-header">
                            <div class="company-name"><?= ucwords(strtolower($experience->company)) ?></div>
                            <div class="position-duration">
                                <span class="position-title"><?= $experience->{lang('App.specialty')} ?></span>
                                <span><?= $experience->start ?> - <?= $experience->end ?></span>
                            </div>
                        </div>
                        <div class="experience-description">
                            <?= $experience->{lang('App.description')} ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Education Section -->
            <div class="section">
                <h2 class="section-title mt-large"><?= lang('App.education') ?></h2>
                <?php foreach ($studies as $study): ?>
                    <div class="education-item">
                        <div class="education-institution"><?= $study->entity ?></div>
                        <div class="education-degree"><?= $study->{lang('App.education_title')} ?></div>
                        <div class="education-duration"><?= $study->start ?> - <?= $study->end ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Social Links Section -->
            <?php if (!empty($profile->github_url) || !empty($profile->linkedin_url)): ?>
                <div class="section">
                    <h2 class="section-title mt-large">Perfiles Profesionales</h2>
                    <div class="social-links">
                        <?php if (!empty($profile->github_url)): ?>
                            <div class="social-link-item">
                                <strong>GitHub:</strong> <a href="<?= $profile->github_url ?>" target="_blank"><?= $profile->github_url ?></a>
                                <p class="social-description">Repositorios de código y proyectos de desarrollo</p>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($profile->linkedin_url)): ?>
                            <div class="social-link-item">
                                <strong>LinkedIn:</strong> <a href="<?= $profile->linkedin_url ?>" target="_blank"><?= $profile->linkedin_url ?></a>
                                <p class="social-description">Perfil profesional y red de contactos</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>