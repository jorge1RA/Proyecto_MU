<?php
// logout.php

session_start();
session_unset();
session_destroy();

// Redirige al usuario a la página de inicio o a la página de login
header("Location: /modaurbana/index.php");
exit();
