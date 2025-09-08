<?php 
namespace Config\Baubyte;

use CodeIgniter\Config\BaseConfig;

class WebsiteConfig extends BaseConfig
{
    public $regPerPage = 27;
    public $projectName = "Roma2";
    public $padHash = 6;
    public $cacheTTL = 0; // 0 means no caching
    public $productNewDays = 20;
}