# TrailNest: Free hosting with InfinityFree

## Step 1: Create the hosting account
1. Sign up at https://www.infinityfree.com
2. In the Client Area, click **New Account**, choose a free subdomain like `trailnest.free.nf`, click Create.
3. Wait until the account status says **Active**.

## Step 2: Create the database
1. Open Control Panel (VistaPanel) > **MySQL Databases**.
2. Create a database, e.g. `trail`. The full name gets a prefix: `if0_12345678_trail`.
3. Note DB name, DB host (like `sql123.infinityfree.com`), DB user, DB password.

## Step 3: Import the tables
1. Click **Admin** (phpMyAdmin) next to your database.
2. Select your database on the left, click **Import**, choose `database_live.sql` (not `database.sql`), click Import.

## Step 4: Edit includes/config.php
```php
define('DB_HOST', 'sql123.infinityfree.com');
define('DB_NAME', 'if0_12345678_trail');
define('DB_USER', 'if0_12345678');
define('DB_PASS', 'your-hosting-password');
```
Also change `ADMIN_PASSWORD`.

## Step 5: Upload the files
Upload everything inside `trailnest/` into `htdocs/` (via Online File Manager or FileZilla), so `index.php` sits directly in `htdocs`. Skip `database.sql`, `database_live.sql`, and the Dockerfile.

## Step 6: Test
Visit `http://trailnest.free.nf`, check every tab, save a trek, post a review, submit a booking, then check `/admin/`.

## Step 7: HTTPS
Request a free SSL certificate in the Client Area under **Free SSL Certificates**.
