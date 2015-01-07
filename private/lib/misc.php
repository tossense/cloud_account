<?php

function retError($ret, $info)
{
    $ret["status"] = "ERROR";
    $ret["info"] = $info;
    return $ret;
}
