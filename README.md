# CampusNest — Setup Guide

Your project now has a real MySQL database behind it instead of fake/hardcoded
data. Here's how to get it running.

## 1. Requirements

- A local server stack with PHP + MySQL — **XAMPP**, **WAMP**, or **MAMP** all work.
- Put this whole `Web tech project` folder inside your server's web root
  (e.g. `htdocs/` for XAMPP).

## 2. Import the database

1. Start Apache and MySQL from your XAMPP/WAMP control panel.
2. Open phpMyAdmin (usually `http://localhost/phpmyadmin`).
3. Click **Import**, choose `schema.sql` (in the project root), and run it.
   This creates the `campusnest` database with 4 tables (`users`, `listings`,
   `interest_requests`, `reports`) and a few sample rows.

If you'd rather use the command line instead of phpMyAdmin:

```
mysql -u root -p < schema.sql
```

## 3. Check your DB credentials

Open `Model/dbConnect.php`. By default it assumes the typical XAMPP setup:

```php
function connect() {
    $conn = mysqli_connect("localhost", "root", "", "campusnest");
    ...
}
```

If your MySQL root user has a password, or you used a different database
name, update these values to match.

## 4. Try it out

Visit `http://localhost/Web tech project/View/index.php` and log in with one
of the sample accounts (all use the password `password123`):

| Role   | Email                 |
|--------|-----------------------|
| Admin  | admin@campusnest.com  |
| Lister | rakibul@example.com   |
| Seeker | lia@example.com      |

You can also register a brand new account from the Sign Up page — it will be
saved for real in the `users` table now.

## What changed from the original project

- **register.php / login.php** — now insert/read real rows in the `users`
  table, with passwords stored securely using `password_hash()` (never
  plain text) and prepared statements everywhere (protects against SQL
  injection).
- **post-listing.php** — saves a new row in `listings`, uploads the chosen
  image into `View/Images/uploads/`.
- **manage-listing.php** + `View/lister/manage-listing.php` — the lister's
  own listings are pulled live from the database; Mark Occupied/Available
  and Delete actually update/delete the row.
- **interest-request.php** + `View/seeker/interest-request.php` — a seeker
  can express interest in a specific listing (`?id=` in the URL); saved to
  `interest_requests`.
- **respond-request.php** + `View/lister/respond-request.php** — listers see
  real pending requests for their own listings and can approve/decline them.
- **manage-users.php / reports.php / system-reports.php** (admin) — all now
  read/write real data instead of a static two-item demo list. System
  Reports shows live counts and lets you generate a filtered report by date
  range.
- **search-filter.php** — actually queries the `listings` table by location,
  price range, and room type, and shows matching results with an "Express
  Interest" button.
- **profile.php** — loads and updates the logged-in user's real row.
- Added **seeker-dashboard.php**, **lister-dashboard.php**,
  **admin-dashboard.php** — `login.php` was already redirecting to these
  pages, but they didn't exist in the original zip.

## Known limitations (kept simple on purpose)

- `forgot-password.php` checks whether the email exists but doesn't actually
  send a reset email — building that requires an SMTP/mail setup, which is
  outside what a database adds.
- Login/register error messages (e.g. `?error=email_exists`) are passed in
  the URL but the login/register HTML pages don't display them yet — you'd
  need a small bit of JavaScript or PHP to read `?error=` and show a message
  box. This was true in the original project too.
- The "Property Type" checkboxes on the Post Listing page (Furnished, Bachelor,
  etc.) aren't saved yet — only Location, Room Type, Description, Price,
  Contact, and Image are stored, matching what the original controller
  validated.
