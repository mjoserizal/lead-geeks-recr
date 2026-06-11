# Internal IT Ticket Dashboard

A modern, responsive, and high-fidelity internal web portal built for tracking and managing IT support tickets. This project was developed as a solution for the **Practical Technical Assessment (Case Option 1)**.

It uses a dark glassmorphism aesthetic with zero heavy CSS libraries, implementing all visual components and layouts with pure custom Vanilla CSS for maximum performance and flexibility.

---

## 🚀 Project Overview

The **Internal IT Ticket Dashboard** allows IT support staff to view cumulative ticket metrics, filter and search through support cases, manage individual ticket lifecycles (Create, Read, Update, Delete), and log progression notes/comments for specific issues.

---

## 🛠️ Technologies Used

- **Backend Framework**: Laravel 11.x (PHP 8.3+)
- **Database**: SQLite (configured out of the box, zero external engine configuration required)
- **Frontend Architecture**: Blade Templating Engine + Vanilla JavaScript
- **Styling**: Custom Vanilla CSS (incorporating modern CSS variables, responsive grid/flex layouts, glassmorphism templates, and status-specific color branding)
- **Testing**: PHPUnit / Laravel Feature Testing

---

## ✨ Features Implemented

### 1. Ticket Management (CRUD)
- **View Ticket List**: Displayed in a responsive, modern tabular format with clear category, status, priority, and date markers.
- **Add Ticket**: Handled via a user-friendly modal overlay directly on the dashboard.
- **Edit Ticket**: Full-page editor pre-populated with existing metadata.
- **Delete Ticket**: Secure deletion with deletion confirmation.
- **View Ticket Details**: Sleek layout presenting complete ticket logs.

### 2. Live Statistics Counters
The top section of the dashboard calculates and displays live counts for:
- **Total Tickets** (cumulative)
- **Open Tickets** (unaddressed issues)
- **In Progress Tickets** (ongoing resolutions)
- **High Priority Tickets** (urgent cases)

### 3. Advanced Filtering, Search & Sorting (Bonus Features)
- **Full-Text Search**: Dynamically searches across ticket titles, descriptions, and assigned persons.
- **Category Filter**: Filter records by Hardware, Software, Network, Security, or Other.
- **Priority Filter**: Filter by Low, Medium, or High.
- **Status Filter**: Filter by Open, In Progress, Resolved, or Closed.
- **Custom Sort**: Sort tickets by Created Date, Title, Category, Priority, or Status in either Descending or Ascending order.
- **Logical Sorting**: Statuses and Priorities sort logically (e.g., High -> Medium -> Low rather than alphabetically).

### 4. Status Color Indicators (Bonus Features)
- Cohesive color-coded badges for ticket statuses and priorities:
  - **Open**: Electric Blue / Cyan
  - **In Progress**: Amber / Orange
  - **Resolved**: Emerald / Green
  - **Closed**: Slate / Gray
  - **High Priority**: Red warning dot

### 5. Ticket Notes/Comments Log (Bonus Features)
- Support staff can add detailed timeline notes and comments to any ticket.
- Notes are linked to the parent ticket, displayed in a chronological timeline feed, and deleted automatically if the ticket is removed.

---

## 📦 Setup & Installation Instructions

To set up and run this application locally, ensure you have **PHP 8.2+** and **Composer** installed.

### 1. Clone & Navigate
If you have received this project as a ZIP or cloned repository:
```bash
cd lead-geeks-recr
```

### 2. Install Dependencies
Install the required PHP dependencies using Composer:
```bash
composer install
```

### 3. Set Up Environment File
Create the local environment file (if not already copied):
```bash
copy .env.example .env
```
*(By default, the `.env` is configured to use SQLite with `DB_CONNECTION=sqlite`.)*

### 4. Database Setup & Seeding
Create the SQLite database file and run the migrations alongside the sample seeder:
```bash
# Create the database file (if it does not exist)
type NUL > database/database.sqlite

# Run migrations and seed sample tickets
php artisan migrate --seed
```
*The database will be populated with 8 realistic IT tickets complete with assigned staff, description logs, and comments right out of the box.*

### 5. Run Local Server
Launch Laravel's local development server:
```bash
php artisan serve
```
By default, the application will be accessible at: [http://127.0.0.1:8000](http://127.0.0.1:8000)

### 6. Run Tests
To execute the automated test suites and verify all CRUD operations and calculations:
```bash
php artisan test
```
