<?php
declare(strict_types=1);

namespace app\admin\controller;

use app\BaseController;
use think\facade\Config;

/**
 * Swagger文档控制器
 * @OA\Info(
 *     title="ThinkPHP8 API文档",
 *     version="1.0.0",
 *     description="基于ThinkPHP8的多应用分层架构系统API文档"
 * )
 * @OA\Server(url="http://localhost:8081", description="本地开发环境")
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class Swagger extends BaseController
{
    /**
     * 生成Swagger文档
     */
    public function index()
    {
        $config = Config::get('swagger');
        $openapi = \OpenApi\Generator::scan($config['scan_paths']);
        
        return json(json_decode($openapi->toJson(), true));
    }

    /**
     * Swagger UI
     */
    public function ui()
    {
        $html = <<<HTML
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>API文档</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5/swagger-ui.css">
</head>
<body>
    <div id="swagger-ui"></div>
    <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
    <script>
        window.onload = function() {
            SwaggerUIBundle({
                url: '/admin/swagger',
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIBundle.SwaggerUIStandalonePreset
                ]
            });
        };
    </script>
</body>
</html>
HTML;
        return response($html);
    }
}
