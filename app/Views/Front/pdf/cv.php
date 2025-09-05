<!DOCTYPE html>
<html lang="<?= session('locale') ?>">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<style type="text/css">
    @page {
        margin-left: 0.5cm;
        margin-right: 0;
    }

    * {
        margin: 0;
        padding: 0;
    }

    body {
        font: 16px Helvetica, Sans-Serif;
        line-height: 24px;
    }

    .clear {
        clear: both;
    }

    #page-wrap {
        width: 800px;
        margin: 40px auto 60px;
    }

    #pic {
        float: right;
        margin: -30px 0 0 0;
        width: 200px;
        height: 200px;
    }

    h1 {
        margin: 0 0 16px 0;
        padding: 0 0 16px 0;
        font-size: 42px;
        font-weight: bold;
        letter-spacing: -2px;
        border-bottom: 1px solid #378C3F;
    }

    h2 {
        font-size: 20px;
        margin: 0 0 6px 0;
        position: relative;
        color: #378C3F;
    }

    h2 span {
        font-style: italic;
        font-family: Georgia, Serif;
        font-size: 16px;
        color: #378C3F;
        font-weight: normal;
    }

    p {
        margin: 0 0 16px 0;
    }

    a {
        color: #378C3F;
        text-decoration: none;
        border-bottom: 1px dotted #378C3F;
    }

    a:hover {
        border-bottom-style: solid;
        color: black;
    }

    ul {
        margin: 0 0 32px 17px;
    }

    #objective {
        width: 500px;
        float: left;
    }

    #objective p {
        font-family: Georgia, Serif;
        font-style: italic;
        font-size: small;
        color: #666;
        text-align: justify;
    }

    dt {
        font-style: italic;
        font-weight: bold;
        font-size: 18px;
        text-align: right;
        padding: 0 26px 0 0;
        width: 150px;
        float: left;
        height: 100px;
        border-right: 1px solid #378C3F;
        color: #378C3F;
    }

    dd ul {
        font-size: 18px;
        margin: 0 0 6px 0;
        position: relative;
        color: #378C3F;
    }

    li {
        list-style: none;
    }

    ul {
        column-count: 3;
    }

    dd {
        width: 600px;
        float: right;
    }

    dd.clear {
        float: none;
        margin: 0;
        height: 15px;
    }

    .fn {
        color: #378C3F;
    }
</style>

<body>
    <div id="page-wrap">

        <img src="<?php echo base_url('uploads/profile/images/' . $profile->avatar) ?>" alt="Photo of Cthulu" id="pic" />

        <div id="contact-info" class="vcard">
            <h1 class="fn"><?= $profile->fullName ?></h1>
            <h2>
                <?= $profile->{lang('App.specialty')} ?>
            </h2>
            <p>
                Email: <a class="email" href="mailto:<?= $profile->email_contact ?>"><?= $profile->email_contact ?></a>
            </p>
            <p>
                <?=lang('App.language_title')?>: <a class="email" href="#"><?= $profile->{lang('App.language')} ?></a>
            </p> 
        </div>

            <div id="objective">
                <p>
                    <?= $profile->{lang('App.description')} ?>
                </p>
            </div>

            <div class="clear"></div>

            <dl>
                <dd class="clear"></dd>

                <dt>Skills</dt>
                <dd>
                    <ul>
                        <?php foreach ($skills as $skill) : ?>
                            <li><?= $skill->name ?> - <?= $skill->percentage ?>%</li>
                        <?php endforeach; ?>
                    </ul>

                </dd>

                <dd class="clear"></dd>

                <dt><?= lang('App.experience') ?></dt>
                <dd>
                    <?php foreach ($experiences as $experience) : ?>
                        <h2><?= ucwords(strtolower($experience->company)) ?> <span><?= $experience->{lang('App.specialty')} ?></span> <span> <?= $experience->start ?> - <?= $experience->end ?></span></h2>
                        <p><?= $experience->{lang('App.description')} ?></p>
                    <?php endforeach; ?>
                </dd>
                <dd class="clear"></dd>

                <dt><?=lang('App.education')?></dt>
                <dd>
                <?php foreach ($studies as $study):?>
                    <h2><?=$study->entity?></h2>
                    <p><strong><?=$study->{lang('App.education_title')}?></strong><br/>
                        <strong><?=$study->start?> - <?=$study->end?></strong>
                    </p>
                <?php endforeach;?>
                </dd>
                <dd class="clear"></dd>
            </dl>

            <div class="clear"></div>

        </div>
</body>

</html>