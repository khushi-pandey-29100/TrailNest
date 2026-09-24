# TrailNest: Local Setup Guide (XAMPP)

## Step 1: Install XAMPP
Download from https://www.apachefriends.org and install with default options.

## Step 2: Start Apache and MySQL
Open the XAMPP Control Panel and click **Start** next to both Apache and MySQL until they turn green.

## Step 3: Copy the project
Copy the whole `trailnest` folder into `C:\xampp\htdocs\` (Mac: /Applications/XAMPP/htdocs/).
You should end up with `C:\xampp\htdocs\trailnest\index.php`.

## Step 4: Create the database
1. Open http://localhost/phpmyadmin
2. Click **Import**, choose `trailnest/database.sql`, click **Import**.
3. This creates `trailnest_db` with four tables: `treks`, `trek_images`, `reviews`, `bookings`.

## Step 5: Open the site
Go to **http://localhost/trailnest/**

Try this flow:
1. Home page > Treks tab.
2. Filter by difficulty (Easy / Moderate / Difficult).
3. Open a trek, look at the photo grid, scroll to reviews, submit a review.
4. Tap **Save** on a card, then open the "Saved for later" section at the bottom of the Treks page (this uses your browser's local storage, not the database).
5. Fill in the booking form and submit.
6. Check phpMyAdmin > trailnest_db > bookings, your entry is there.

## Step 6: Staff page
Go to http://localhost/trailnest/admin/ and log in with password `trail123`.
Change it in `includes/config.php` (ADMIN_PASSWORD) before showing anyone.

## Project structure
```
trailnest/
  index.php              Home page (hero + featured treks)
  treks.php              All treks with difficulty filter + saved list
  trek.php               Trek detail: gallery, itinerary, reviews, booking form
  about.php              About tab
  contact.php            Contact tab with a general booking form
  database.sql           Creates trailnest_db, tables, sample data
  admin/index.php        Staff login + bookings list
  includes/
    config.php            DB settings, site name, helper functions
    db.php                Connects PHP to MySQL (PDO)
    header.php / footer.php  Shared layout
    trek_card.php          One trek card, reused across pages
    booking_handler.php    Validates the booking form, saves to bookings table
  assets/
    css/style.css         Dark theme styling
    js/wishlist.js        Save-for-later feature (browser local storage)
    images/                Trek photos
```

## How this project differs from a typical version
- **Reviews and star ratings**: visitors post a review with a rating; the average is calculated live from the `reviews` table.
- **Difficulty filter**: `treks.php?difficulty=Easy` filters with a SQL `WHERE` clause.
- **Wishlist**: "Save" uses the browser's `localStorage`, not the database, so it's private per device and needs no login.
- **Photo grid gallery** instead of a slider.

## If something goes wrong
| Problem | Fix |
|---|---|
| "Database connection failed" | MySQL isn't started, or Step 4 was skipped. |
| 404 Not found | Folder isn't inside htdocs, or isn't named `trailnest`. |
| Saved list stays empty | It's per-browser. Saving in Chrome won't show in Firefox. |
