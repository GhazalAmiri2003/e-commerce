# PHP E-Commerce Prototype

A browser-based e-commerce prototype with customer and administrator interfaces, built using PHP and MySQL.

## Features
- Product browsing and customer registration/login workflows.
- Session-based shopping cart and inventory updates.
- Administrative product listing, addition, editing and deletion.
- Separate styles for authentication, product pages, cart and dashboard.

## Structure
| Directory | Purpose |
| --- | --- |
| `e_commerce/auth` | Authentication workflows |
| `e_commerce/user` | Customer pages and cart |
| `e_commerce/admin` | Product administration |
| `e_commerce/config` | Database connection |
| `e_commerce/assets` | Frontend assets |

## Technology
PHP · MySQL · JavaScript · HTML · CSS

## Security and local setup

The revised prototype uses password hashing, prepared statements, CSRF protection on the main write routes, image validation and transactional stock updates at demo checkout. Adding products to a cart does not reserve stock.

Provide the original MySQL schema and a local PHP/MySQL environment. See [Security migration](e_commerce/config/SECURITY-MIGRATION.md) before running the revised code, especially password resets, DB environment variables and reconciling previously reserved stock. This is a prototype, not a production payment or order system. Integration tests have not been executed.
