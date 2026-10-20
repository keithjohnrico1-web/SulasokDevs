1. Install XAMPP (Apache + MySQL) and enable the `intl` and `mbstring` extensions in php.ini.
2. Run `composer install` in this folder.
3. In phpMyAdmin, create a database named `sunsons_db` and import `sunsons_db.sql`.
4. Copy `env` to `.env`, then set:
   - `CI_ENVIRONMENT = development`
   - `app.baseURL = 'http://localhost:8080/'`
   - `database.default.hostname = localhost`
   - `database.default.database = sunsons_db`
   - `database.default.username = root`
   - `database.default.password =`
   - `database.default.DBDriver = MySQLi`
5. Run `php spark serve` and open http://localhost:8080

Usable accounts
- Employee (admin): username `admin` / password `admin123`
- Customer: username `jempoyjoestar` / password `!Gaylord1`
Folders
- `app/Controllers`, `app/Models`, `app/Views`: application code
- `public/`: CSS and images