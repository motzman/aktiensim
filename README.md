# Aktiensim – Simple Stock Market Simulator  
A school project built with **PHP**, **MySQL (MariaDB)**, and **XAMPP**.  
This project simulates a very simple stock market:  
Users can register, log in, create their own stocks, buy shares, and watch prices change based on supply and demand.

### 1. The Market Logic
Instead of complex real-world data, this project uses a simplified algorithm to react to user activity:
* **Supply & Demand:** Every time a share is bought, the remaining supply drops.
* **Price Reactivity:** A purchase automatically triggers a price increase of **1%** (`price * 1.01`).
* **Liquidity:** You can only buy shares that are currently available in the pool.

### 2. User Features
* **Portfolio Management:** Track exactly how many shares you own of each stock.
* **Stock Creation:** Become a company owner by listing a new stock (defining name, total volume, and IPO price).
* **Wallet System:** Your balance updates instantly upon trading.

# Database Structure
The project uses **MariaDB** (via XAMPP).  

```mermaid
erDiagram
    USERS ||--o{ STOCKS : "creates"
    USERS ||--o{ USER_STOCKS : "owns"
    STOCKS ||--o{ USER_STOCKS : "is traded in"

    USERS {
        int id PK
        string username
        string password "Hashed"
        decimal balance "Default 1000.00"
    }

    STOCKS {
        int id PK
        string name
        int creator_id FK
        int total_shares
        int available_shares
        decimal price_per_share
        timestamp created_at
    }

    USER_STOCKS {
        int id PK
        int user_id FK
        int stock_id FK
        int quantity
    }
````
---

## Installation (XAMPP)

### Clone the repository
```bash
git clone https://github.com/motzman/aktiensim
````

### Move the folder

Move it into your XAMPP `htdocs` folder:

```
C:\xampp\htdocs\aktiensim
git clone [https://github.com/motzman/aktiensim](https://github.com/motzman/aktiensim)
```

### Start XAMPP

Enable:

* **Apache**
* **MySQL**

### Import the SQL

Go to:

```
http://localhost/phpmyadmin
```

1. Create a database named **aktiensim**
2. Import the SQL file from the repository (`aktiensim.sql`)

### Open the project

Open in browser:

```
http://localhost/aktiensim
```

## Purpose of the Project

This project was created as a **school assignment** to demonstrate:

* Basic backend development
* Working with sessions
* CRUD operations using PHP
* Database relations (foreign keys)
* Form handling
* Simple business logic

---

