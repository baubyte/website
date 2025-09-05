<p>Alguien solicitó un restablecimiento de contraseña en esta dirección de correo electrónico para <?= site_url() ?>.</p>

<p>Para restablecer la contraseña, use este código o URL y siga las instrucciones..</p>

<p>Tu Código: <?= $hash ?></p>

<p>Visita el <a href="<?= site_url('reset-password') . '?token=' . $hash ?>">el Formulario para Recuperar.</a>.</p>

<br>

<p>Si no solicitó un restablecimiento de contraseña, puede ignorar este correo electrónico de manera segura.</p>
