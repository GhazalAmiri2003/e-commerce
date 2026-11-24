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

## Status and setup
A local PHP/MySQL environment and the required database schema are needed. The repository does not include a schema export. Review database credentials, authentication, authorization, input handling and transactional stock updates before deployment. This is a prototype; production readiness and runtime behaviour have not been independently verified.
