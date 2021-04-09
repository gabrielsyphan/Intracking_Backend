<php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
header('Accept: application/json');

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../Config.php';

use Source\Models\Attach;
use Source\Models\License;
use Source\Models\LicenseType;
use Source\Models\Neighborhood;
use Source\Models\Punishment;
use Source\Models\Role;
use Source\Models\User;
use Stonks\Router\Router;
use League\Plates\Engine;

use SimpleXMLElement;
use Source\Models\Agent;
use Source\Models\Company;
use Source\Models\Email;
use Source\Models\Notification;
use Source\Models\PagSeguro;
use Source\Models\Payment;
use Source\Models\Salesman;
use Source\Models\Zone;