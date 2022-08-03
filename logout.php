<?php

session_start();
session_destroy();
// redirige a esta locación
header("Location: index.php");