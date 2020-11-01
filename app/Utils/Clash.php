<?php

namespace App\Utils;


class Clash
{
    public static function buildShadowsocks($uuid, $server)
    {
        $array = [];
        $array['name'] = $server->name;
        $array['type'] = 'ss';
        $array['server'] = $server->host;
        $array['port'] = $server->port;
        $array['cipher'] = $server->cipher;
        $array['password'] = $uuid;
        $array['udp'] = true;
        return $array;
    }

    public static function buildVmess($uuid, $server)
    {
        $array = [];
        $array['name'] = $server->name;
        $array['type'] = 'vmess';
        $array['server'] = $server->host;
        $array['port'] = $server->port;
        $array['uuid'] = $uuid;
        $array['alterId'] = 2;
        $array['cipher'] = 'auto';
        $array['udp'] = true;
        if ($server->tls) {
            $tlsSettings = json_decode($server->tlsSettings);
            $array['tls'] = true;
            if (!empty($tlsSettings->allowInsecure)) $array['skip-cert-verify'] = ($tlsSettings->allowInsecure ? true : false );
            if (!empty($tlsSettings->serverName)) $array['servername'] = $tlsSettings->serverName;
        }
        if ($server->network == 'ws') {
            $array['network'] = $server->network;
            if ($server->networkSettings) {
                $wsSettings = json_decode($server->networkSettings);
                if (isset($wsSettings->path)) $array['ws-path'] = $wsSettings->path;
                if (isset($wsSettings->headers->Host)) $array['ws-headers'] = [
                    'Host' => $wsSettings->headers->Host
                ];
            }
        }
        return $array;
    }

    public static function buildTrojan($password, $server)
    {
        $array = [];
        $array['name'] = $server->name;
        $array['type'] = 'trojan';
        $array['server'] = $server->host;
        $array['port'] = $server->port;
        $array['password'] = $password;
        $array['udp'] = true;
        if (!empty($server->server_name)) $array['sni'] = $server->server_name;
        if (!empty($server->allow_insecure)) $array['skip-cert-verify'] = ($server->allow_insecure ? true : false );
        return $array;
    }
}
