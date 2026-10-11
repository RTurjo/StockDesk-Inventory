# StockDesk Inventory

A WordPress plugin for managing inventory from the admin dashboard. It covers products, suppliers, stock in/out, stock history and low-stock tracking.

## Features

- Overview dashboard with total, in-stock, low-stock and out-of-stock counts
- Recent stock activity on the dashboard
- Add and delete products (name, SKU, category, prices, stock, low-stock threshold, supplier)
- Add and delete suppliers
- Stock adjustments: Stock In, Stock Out and Set Stock
- Full stock history with the change, reason and notes for each entry
- Low-stock list based on each product's threshold

## Requirements

- WordPress 6.0 or later
- PHP 7.4 or later
- [Local](https://localwp.com/) (or any other local WordPress setup)

## Setup

1. Install Local and log in.
2. Create a new WordPress site in Local.
3. Open the site's WP Admin from Local.
4. Log in with the admin username and password you set for the site.
5. Go to **Plugins → Add New → Upload Plugin**.
6. Upload `stockdesk-inventory.zip`, then click **Install Now** and **Activate**.
7. Open **Inventory** from the admin menu.

## How to use

The plugin starts empty: no products, no suppliers, and every count on the Overview page is 0.

1. Add your suppliers under **Inventory → Suppliers**.
2. Add products under **Inventory → Products** and pick a supplier for each one.
3. Record stock movements under **Inventory → Stock Adjustment**.
4. Check **Stock History** for past changes and **Low Stock** for items that need restocking.

## Project structure

```
stockdesk-inventory/
├── stockdesk-inventory.php      Plugin entry point
├── includes/
│   ├── class-stockdesk-db.php         Database tables (created on activation)
│   ├── class-stockdesk-products.php   Add/delete products
│   ├── class-stockdesk-suppliers.php  Add/delete suppliers
│   ├── class-stockdesk-stock.php      Stock adjustments and logging
│   └── class-stockdesk-admin.php      Admin menu and page data
├── views/                       Admin page templates
└── assets/css/admin.css
```

## Future work

- Auto-generated SKUs
- Built-in POS (point of sale)
