<?php

namespace App\Http\Controllers\V1\Admin\Server;

use App\Http\Controllers\Controller;
use App\Services\ServerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageController extends Controller
{
    public function getNodes(Request $request)
    {
        $serverService = new ServerService();
        return response([
            'data' => $serverService->getAllServers()
        ]);
    }

    public function sort(Request $request)
    {
        ini_set('post_max_size', '1m');

        $jsonContent = request()->getContent() ?: json_encode($_POST);;
        $data = json_decode($jsonContent, true);
        $params = [
            'shadowsocks' => $data['shadowsocks'] ?? null,
            'vmess'       => $data['vmess'] ?? null,
            'vless'       => $data['vless'] ?? null,
            'trojan'      => $data['trojan'] ?? null,
            'hysteria'    => $data['hysteria'] ?? null,
        ];
        DB::beginTransaction();
        foreach ($params as $k => $v) {
            $model = 'App\\Models\\Server' . ucfirst($k);
            foreach($v as $id => $sort) {
                if (!$model::find($id)->update(['sort' => $sort])) {
                    DB::rollBack();
                    abort(500, '保存失败');
                }
            }
        }
        DB::commit();
        return response([
            'data' => true
        ]);
    }
}
