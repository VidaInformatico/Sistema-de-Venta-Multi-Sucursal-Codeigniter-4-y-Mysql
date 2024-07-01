<?php
// ubicado en "App/Helpers" o donde hayas definido tu helper

if (!function_exists('verificar')) {
    function verificar($valor, $datos = [])
    {
        return in_array($valor, $datos, true);
    }
}
