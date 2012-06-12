<?php
    require_once("../../config.php");
    $id = required_param('id', PARAM_INT);
    header("Location: {$CFG->wwwroot}/mod/connector/index.php?id=$id&controller=user");
    exit;
?>
