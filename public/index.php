<?php
use App\Controllers\Controller;
use App\Database;
use App\Models\RecipeModel;
use App\Models\UserModel;

// Sökväg till grundmappen i projektet
$baseDir = __DIR__ . '/..';

// Ladda in Composers autoload-fil
require $baseDir . '/vendor/autoload.php';

// Ladda konfigurationsdata
$config = require $baseDir. '/config/config.php';

// Normalisera url-sökvägar
$path = function($uri) {
	return ($uri == "/") ? $uri : rtrim($uri, '/');
};

$dsn = "mysql:host=".$config['host'].";dbname=".$config['db'].";charset=".$config['charset'];
$pdo = new PDO($dsn, $config['user'], $config['password'], $config['options']);

//$db = new Database($dsn, $config['user'], $config['password'], $config['options']);
$db = new Database($pdo);
$recipeModel = new RecipeModel($db);

$db->create('recipes', [
	'name' => "Makaroner",
	'quantity' => 5,
	'recipe_difficulty' => "Easy",
	'user_id' => 1
]);

$recipe = $db->getById('recipes', 1);
$recipes = $db->getAll('recipes');

$recipe = $recipeModel->getById(1);
$recipes = $recipeModel->getAll();
$recipeModel->create([
	'name' => "Falukorv",
	'quantity' => 2,
	'recipe_difficulty' => "Hard",
	'user_id' => 1
]);

// Routing
$controller = new Controller($baseDir);
switch ($path($_SERVER['REQUEST_URI'])) {
	case '/':
		$controller->index();
	break;
	case 'create-recipe':
		$recipeModel = new RecipeModel($db);
		$controller->createRecipe($recipeModel, $_POST);
	break;
	default:
		header('HTTP/1.0 404 Not Found');
		echo 'Page not found';
	break;
}