# MS SQL Server to MySQL Migration Instructions

This guide will help you migrate your MS SQL Server database to MySQL using MySQL Workbench.

## Connection Details

### Source MS SQL Server:
- Host: 65.108.2.70
- Port: 1433
- Database: TradeView
- Username: TradeView
- Password: yw^k188C3@Iii90g

### Target MySQL Server:
- Host: localhost
- Port: 3306
- Username: root
- Password: (blank)

## Step 1: Install MySQL Workbench

1. Download MySQL Workbench from the [official website](https://dev.mysql.com/downloads/workbench/).
2. Install MySQL Workbench following the installation wizard.

## Step 2: Install Required Drivers

1. Make sure you have the Microsoft ODBC Driver for SQL Server installed.
   - Download from [Microsoft's website](https://docs.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server).

## Step 3: Run the Migration Wizard

1. Open MySQL Workbench.
2. From the menu, select **Database > Migration Wizard**.
3. In the Overview page, click **Start Migration**.
4. In the Source Selection page:
   - Select **Microsoft SQL Server** as the RDBMS.
   - Enter the connection details for your SQL Server:
     - Hostname: 65.108.2.70
     - Port: 1433
     - Username: TradeView
     - Password: yw^k188C3@Iii90g
   - Click **Test Connection** to verify.
   - Click **Next**.
5. In the Target Selection page:
   - Select **MySQL** as the RDBMS.
   - Enter the connection details for your MySQL server:
     - Hostname: localhost
     - Port: 3306
     - Username: root
     - Password: (leave blank)
   - Click **Test Connection** to verify.
   - Click **Next**.
6. In the Schema Selection page:
   - Select the **TradeView** database.
   - Click **Next**.
7. In the Reverse Engineering page:
   - Wait for the wizard to analyze your SQL Server schema.
   - Click **Next** when complete.
8. In the Source Objects page:
   - Review the objects to be migrated.
   - Click **Next**.
9. In the Migration page:
   - Wait for the automatic migration to complete.
   - Click **Next**.
10. In the Manual Editing page:
    - Review and edit any migration issues if needed.
    - Click **Next**.
11. In the Target Creation Options page:
    - Select **Create schema in target RDBMS** and **Create tables in target RDBMS**.
    - Click **Next**.
12. In the Create Schemas page:
    - Click **Execute** to create the schema in MySQL.
    - Click **Next** when complete.
13. In the Create Target Results page:
    - Review the results.
    - Click **Next**.
14. In the Data Transfer Setup page:
    - Select **Online copy of table data to target RDBMS**.
    - Click **Next**.
15. In the Bulk Data Transfer page:
    - Click **Execute** to start the data transfer.
    - Wait for the process to complete.
    - Click **Next** when finished.
16. In the Migration Report page:
    - Review the migration report.
    - Click **Finish** to complete the migration.

## Alternative Method: Using Command Line Tools

If MySQL Workbench doesn't work for you, you can try using the SQL Server Migration Assistant (SSMA) for MySQL:

1. Download SSMA for MySQL from [Microsoft's website](https://docs.microsoft.com/en-us/sql/ssma/sql-server-migration-assistant).
2. Install and follow the wizard to connect to both databases and perform the migration.

## Manual Approach

If both automated tools fail, you can use the PHP script provided in this repository:

```
php database/mssql_to_mysql_export.php
```

This will generate SQL files in the `database/sql_export` directory that you can import into MySQL using:

```
cd database/sql_export
import_all.bat
```

## Verifying the Migration

After migration, verify that:
1. All tables were created correctly
2. All data was transferred
3. Primary keys and indexes are working properly
4. Run some test queries to ensure data integrity

## Updating Laravel Configuration

After successful migration, update your Laravel `.env` file to use the MySQL database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tradeview
DB_USERNAME=root
DB_PASSWORD=
```
