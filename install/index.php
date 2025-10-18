<?php
session_start();

$step = isset($_GET['step']) ? (int) $_GET['step'] : 1;

if (PHP_VERSION_ID < 80200) {
  die("PHP 8.2 or higher is required. Current version: " . PHP_VERSION);
}

$extensions = [
  'cURL',
  'Fileinfo',
  'JSON',
  'PDO',
  'pdo_mysql',
  'Session',
  'mbstring',
  'openssl',
  'tokenizer',
  'dom',
  'filter',
  'pcre',
  'hash'
];

$dirs = ['../core/bootstrap/cache/', '../core/storage/', '../core/storage/app/', '../core/storage/framework/', '../core/storage/logs/'];

function checkRequirements()
{
  global $extensions, $dirs;

  $extensionChecks = [];
  $dirChecks = [];
  $allPassed = true;

  foreach ($extensions as $ext) {
    $status = extension_loaded($ext);
    $extensionChecks[] = ['name' => $ext, 'status' => $status];
    $allPassed = $allPassed && $status;
  }

  foreach ($dirs as $dir) {
    $status = is_dir($dir) && substr(sprintf('%o', fileperms($dir)), -4) >= '0775';
    $currentPerm = is_dir($dir) ? substr(sprintf('%o', fileperms($dir)), -4) : 'N/A';
    $dirChecks[] = ['path' => str_replace("../", "", $dir), 'status' => $status, 'current' => $currentPerm];
    $allPassed = $allPassed && $status;
  }

  return [
    'extensions' => $extensionChecks,
    'directories' => $dirChecks,
    'allPassed' => $allPassed
  ];
}

function appUrl()
{
  $fullUrl = (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  $parsedUrl = parse_url($fullUrl);
  return isset($parsedUrl['scheme']) && isset($parsedUrl['host']) ? htmlspecialchars($parsedUrl['scheme'] . '://' . $parsedUrl['host']) : '';
}

function redirectToStep($step)
{
  header("Location: ?step=$step");
  exit();
}

function importSQL($host, $dbname, $user, $pass, $filename)
{
  try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read the SQL file
    $sql = file_get_contents($filename);
    
    if ($sql === false) {
      return "Failed to read SQL file.";
    }
    
    // Remove comments and split by statements
    $sql = preg_replace('/--.*$/m', '', $sql); // Remove single-line comments
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql); // Remove multi-line comments
    
    // Split into individual statements
    $statements = array_filter(
      array_map('trim', explode(';', $sql)),
      function($stmt) {
        return !empty($stmt);
      }
    );
    
    // Execute each statement
    foreach ($statements as $statement) {
      if (!empty($statement)) {
        $db->exec($statement);
      }
    }
    
    return true;
    
  } catch (PDOException $e) {
    return "Database import failed: " . $e->getMessage();
  } catch (Exception $e) {
    return "An error occurred: " . $e->getMessage();
  }
}


function generateAppKey()
{
  return 'base64:' . base64_encode(random_bytes(32));
}

function createEnvFile($siteName, $siteUrl, $dbHost, $dbPort, $dbDatabase, $dbUsername, $dbPassword)
{
  $appKey = generateAppKey();

  $envContent = <<<EOL
APP_NAME="$siteName"
APP_ENV=production
APP_KEY=$appKey
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=$siteUrl

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_LEVEL=error
LOG_DEPRECATIONS_CHANNEL=null

DB_CONNECTION=mysql
DB_HOST=$dbHost
DB_PORT=$dbPort
DB_DATABASE=$dbDatabase
DB_USERNAME=$dbUsername
DB_PASSWORD="$dbPassword"

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=file
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS="your-email@domain.com"
MAIL_FROM_NAME="\${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="\${APP_NAME}"


EOL;

  // Define the path for the .env file
  $envFilePath = '../core/.env';

  // Write to the .env file
  if (file_put_contents($envFilePath, $envContent) === false) {
    throw new Exception("Failed to write to the .env file. Please check permissions.");
  }
}

// Handle form submissions
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if ($step == 1) {
    $_SESSION['step_completed'] = 1;
    redirectToStep(2);
  } elseif ($step == 2) {
    $requirements = checkRequirements();
    if ($requirements['allPassed']) {
      $_SESSION['requirements_passed'] = true;
      $_SESSION['step_completed'] = 2;
      redirectToStep(3);
    } else {
      $error = "Some requirements are not met.";
    }
  } elseif ($step == 3) {
    if (!isset($_SESSION['step_completed']) || $_SESSION['step_completed'] < 2) {
      $error = "You must complete the requirements check first.";
      redirectToStep(2);
    }

    // Collect database configuration
    $_SESSION['db_host'] = strip_tags(trim($_POST['db_host']));
    $_SESSION['db_name'] = strip_tags(trim($_POST['db_name']));
    $_SESSION['db_user'] = strip_tags(trim($_POST['db_user']));
    $_SESSION['db_pass'] = strip_tags(trim($_POST['db_pass']));

    $filename = 'database.sql';
    $importResult = importSQL($_SESSION['db_host'], $_SESSION['db_name'], $_SESSION['db_user'], $_SESSION['db_pass'], $filename);
    if ($importResult === true) {
      $_SESSION['step_completed'] = 3;
      redirectToStep(4);
    } else {
      $error = $importResult;
    }
  } elseif ($step == 4) {
    if (!isset($_SESSION['step_completed']) || $_SESSION['step_completed'] < 3) {
      $error = "You must complete the database setup step first.";
      redirectToStep(3);
    }

    $siteName = strip_tags(trim($_POST['site_name']));
    $siteUrl = rtrim(strip_tags(trim($_POST['site_url'])), '/');
    $adminName = strip_tags(trim($_POST['admin_name']));
    $adminEmail = filter_var(trim($_POST['admin_email']), FILTER_SANITIZE_EMAIL);
    $adminPassword = password_hash(trim($_POST['admin_password']), PASSWORD_BCRYPT);

    if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
      $error = "Invalid email address.";
    } else {
      try {
        $pdo = new PDO("mysql:host={$_SESSION['db_host']};dbname={$_SESSION['db_name']}", $_SESSION['db_user'], $_SESSION['db_pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)")->execute([$adminName, $adminEmail, $adminPassword]);

        createEnvFile(
          $siteName,
          $siteUrl,
          $_SESSION['db_host'],
          '3306',
          $_SESSION['db_name'],
          $_SESSION['db_user'],
          $_SESSION['db_pass']
        );


        $_SESSION['installation_complete'] = true;
        redirectToStep(5);
      } catch (PDOException $e) {
        $error = $e->getMessage();
      }
    }
  }
}

$requirementsPassed = isset($_SESSION['requirements_passed']) && $_SESSION['requirements_passed'];

if ($step > 1 && !isset($_SESSION['step_completed'])) {
  redirectToStep(1);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Easy Installer | Codoy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="shortcut icon" href="images/fav.jpg" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&display=swap" rel="stylesheet">
</head>
<style>
  body {
    font-family: "Inconsolata", monospace;
    font-variation-settings: "wdth" 100;
    background-color: #0f1117;
    color: #fff;
  }

  a {
    text-decoration: none;
  }

  .section-bg {
    background-color: #181a20;
  }

  .installation-section {
    padding: 2rem 0;
  }

  .install-card {
    background-color: rgba(233, 33, 61, 0.4);
    border-radius: 0.5rem;
  }

  .logo {
    max-width: 180px;
    width: 100%;
  }

  .install-card .card-header {
    min-height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #e9213d;
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
  }

  .install-card .card-header h3 {
    text-transform: uppercase;
    font-weight: 700;
  }

  .install-btn {
    background-color: #e9213d;
    border-radius: 5px;
    color: #fff;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px 30px;
    width: fit-content;
    border: none;
    transition: background-color 0.3s ease, transform 0.2s ease;
  }

  .install-btn:hover {
    background-color: #c61c30;
    transform: scale(1.05);
  }

  .form-control {
    background-color: #0f1117 !important;
    border: none;
    border-bottom: 2px solid #e9213d;
    color: #ffffff;
    max-height: 45px;
    height: 100%;
    padding: 10px;
    font-size: 16px;
    border-radius: 0;
    transition: border-color 0.3s ease;
  }

  .form-control:focus {
    outline: none;
    border-bottom: 2px solid #f8b400;
    background-color: #1a1e24 !important;
    color: #fff;
  }

  .form-control::placeholder {
    color: rgba(255, 255, 255, 0.7);
    opacity: 1;
  }

  .notification {
    border-radius: 5px;
    color: #fff;
    min-height: 65px;
    padding: 20px;
    font-weight: 800;
    border: 2px solid #fff;
  }

  .is-danger {
    background-color: #af1919;
  }

  .is-warning {
    background-color: #DBA800;
  }

  .list-group-item-success {
    background-color: #19af66;
    color: #fff;
  }

  .list-group-item-danger {
    background-color: #af1919;
    color: #fff;
  }
</style>

<body class="d-flex flex-column min-vh-100">

  <header class="section-bg py-3 text-center">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="logo">
        <img src="images/logo.png" class="img-fluid" alt="Codoy Logo">
      </div>
      <h3 class="title mb-0">Codoy Installer</h3>
    </div>
  </header>


  <main class="installation-section flex-grow-1 container">
    <div class="text-center">
      <h2 class="h5">Step <?php echo $step; ?> of 5</h2>
    </div>

    <?php if ($error): ?>
      <div class="notification is-danger">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <?php if ($step == 1): ?>
      <div class="install-card rounded shadow">
        <div class="card-header">
          <h3>Terms of Use</h3>
        </div>
        <div class="content mt-3 p-4">
          <div class="item mb-4">
            <h4 class="subtitle">License Limited to a Single Domain (Website)</h4>
            <p>The standard license permits usage on only one website or domain. Should you wish to utilize
              it across
              multiple websites or domains, additional licenses must be acquired (1 website = 1 license).
              This Regular
              License offers you a perpetual, non-exclusive, global right to utilize the item.</p>
          </div>
          <div class="item mb-4">
            <h5 class="subtitle font-weight-bold">Permitted Actions:</h5>
            <ul class="check-list">
              <li>Use it on a single (1) domain only.</li>
              <li>Customize or modify as desired.</li>
              <li>Translate into your preferred language(s).</li>
            </ul>
            <span class="text-warning d-block">
              <i class="fas fa-exclamation-triangle"></i> We are not liable for any issues or errors that
              may arise from
              modifications made to our code/database.
            </span>
          </div>
          <div class="item mb-4">
            <h5 class="subtitle font-weight-bold">Prohibited Actions:</h5>
            <ul class="check-list">
              <li class="no">You may not resell, distribute, or share this product with any third party.
              </li>
              <li class="no">It cannot be bundled with other products for sale on any marketplaces or
                affiliate sites.
              </li>
              <li class="no">Usage on more than one (1) domain is not allowed.</li>
            </ul>
          </div>
          <div class="item mb-4">
            <p class="info">For additional details, please refer to <a href="https://www.codester.com/info/licenses"
                target="_blank">the License FAQ</a>.</p>
          </div>
          <form method="POST">
            <button type="submit" class="install-btn">I Agree, Proceed to Next Step</button>
          </form>
        </div>
      </div>

    <?php elseif ($step == 2): ?>
      <div class="install-card rounded shadow mt-4">
        <div class="card-header">
          <h3>Required Extensions</h3>
        </div>
        <div class="content mt-3 p-4">
          <p class="subtitle">Please ensure the following PHP extensions are enabled:</p>
          <div class="row">
            <?php
            $requirements = checkRequirements();
            foreach ($requirements['extensions'] as $ext): ?>
              <div class="col-md-4 mb-3">
                <div class="list-group">
                  <div
                    class="list-group-item <?php echo $ext['status'] ? 'list-group-item-success' : 'list-group-item-danger'; ?>">
                    <?php echo htmlspecialchars($ext['name']); ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
            <?php foreach ($requirements['directories'] as $dir): ?>
              <div class="col-md-4 mb-3">
                <div class="list-group">
                  <div
                    class="list-group-item <?php echo $dir['status'] ? 'list-group-item-success' : 'list-group-item-danger'; ?>">
                    <?php echo htmlspecialchars($dir['path']); ?> (Current Perm:
                    <?php echo htmlspecialchars($dir['current']); ?>)
                    <?php if ($dir['current'] !== '0775'): ?>
                      <div class="text-white mt-2">
                        <strong>Note:</strong> The recommended permission is <code class="text-black">0775</code>.
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>

          </div>
          <p class="info mt-3">Make sure to enable the required extensions in your PHP configuration before proceeding.
          </p>
          <?php if ($requirements['allPassed']): ?>
            <p class="text-success mt-3">All requirements are met!</p>
            <form method="POST">
              <button type="submit" class="install-btn">Continue</button>
            </form>
          <?php else: ?>
            <div class="notification is-warning">
              Some requirements are not met. Please fix them to proceed.
            </div>
          <?php endif; ?>
        </div>
      </div>



    <?php elseif ($step == 3): ?>
      <div class="install-card rounded shadow mt-4">
        <div class="card-header">
          <h3>Database Information</h3>
        </div>
        <div class="content mt-3 p-4">
          <form method="POST">
            <div class="mb-3">
              <label for="db_host" class="form-label">Database Host</label>
              <input type="text" name="db_host" class="form-control" id="db_host" value="localhost" required>
            </div>
            <div class="mb-3">
              <label for="db_name" class="form-label">Database Name</label>
              <input type="text" name="db_name" class="form-control" id="db_name" required>
            </div>
            <div class="mb-3">
              <label for="db_user" class="form-label">Database Username</label>
              <input type="text" name="db_user" class="form-control" id="db_user" required>
            </div>
            <div class="mb-3">
              <label for="db_pass" class="form-label">Database Password</label>
              <input type="password" name="db_pass" class="form-control" id="db_pass">
            </div>
            <button type="submit" class="install-btn">Import Database</button>
          </form>
        </div>
      </div>
    <?php elseif ($step == 4): ?>
      <div class="install-card rounded shadow mt-4">
        <div class="card-header">
          <h3>Site Credentials</h3>
        </div>
        <div class="content mt-3 p-4">
          <form method="POST">
            <div class="mb-3">
              <label for="site_name" class="form-label">Site Name</label>
              <input type="text" name="site_name" class="form-control" id="site_name" required>
            </div>
            <div class="mb-3">
              <label for="site_url" class="form-label">Site URL</label>
              <input type="url" name="site_url" class="form-control" id="site_url" value="<?php echo appUrl(); ?>"
                required>
            </div>
            <div class="mb-3">
              <label for="admin_name" class="form-label">Admin Name</label>
              <input type="text" name="admin_name" class="form-control" id="admin_name" required>
            </div>
            <div class="mb-3">
              <label for="admin_email" class="form-label">Admin Email</label>
              <input type="email" name="admin_email" class="form-control" id="admin_email" required>
            </div>
            <div class="mb-3">
              <label for="admin_password" class="form-label">Admin Password</label>
              <input type="password" name="admin_password" class="form-control" id="admin_password" required>
            </div>
            <button type="submit" class="install-btn">Finish Installation</button>
          </form>
        </div>
      </div>
    <?php elseif ($step == 5): ?>
      <div class="install-card rounded shadow mt-4 text-center">
        <div class="card-header">
          <h3>Installation Complete</h3>
        </div>
        <div class="content mt-3 p-4">
          <h3 class="congra">Congratulations! The installation has been completed successfully.</h3>
          <p>Your application is now ready to use. Please remember to remove the install folder for security reasons.
          </p>
          <img src="images/1.png" alt="Installation Complete" class="img-fluid mb-4" style="max-width: 300px;">
          <div class="d-flex justify-content-center">
            <a href="<?php echo appUrl(); ?>/backend" class="install-btn mx-2">Go to Admin Panel</a>
            <a href="<?php echo appUrl(); ?>" class="install-btn mx-2">Go to Site Web</a>
          </div>
        </div>
      </div>

    <?php endif; ?>
  </main>

  <footer class="section-bg py-3 text-center">
    <div class="container">
      <p class="m-0 font-weight-bold">©<?php echo date("Y"); ?> - All Rights Reserved by Codoy</p>
    </div>
  </footer>

</body>

</html>