# FINANCE TOOLS

##### Powered by FinOps: 
[![N|Solid](https://www.linkaja.id/assets/linkaja/img/brand/logo_header.svg)](https://www.linkaja.id/)


> **Financetools** adalah aplikasi finance berbasis web yang diperuntukan untuk kebutuhan internal **LinkAja**, dalam hal ini merupakan solusi sementara dalam mempermudah proses bisnis di linkup Direktorat Keuangan **LinkAja** sebelum implementasi ERP.

### Table of Contents
  - Features
  - Technology
  - Installation
  - Demo
  - Developer and Support

## Features
##### Administrator:
  - Users
  - User Groups
  - Menu Management
##### Master Data:
  - Directorate, Divison & Unit
  - Employee, Jabatan & Approval
  - COA, Group, & Group Rpt
  - Vendor
  - Bank
  - Rate
  - PPh & PPN
##### Finance Transaction:
  - Upload RKAP
  - Budget Information and History of Budget PerDate
  - Budget Relocation and Redistribution
  - Onlne Justification
  - FPJP, PR & PO
  - Settlement Payment (Upload Invoice, Batch Payment, Post Clearing)
  - Journal (Upload Journal, Journal Manual, Journal AR)
##### Financial Statement:
  - Trial Balance YTD & PTD
  - Balance Sheet
  - SHE
  - Profit and Loss


## Technology

* [Apache](https://www.apache.org/) - Apache Web Server 2.1.
* [CodeIginiter](https://codeigniter.com/user_guide/index.html) - CodeIgniter Framework 3.1.1.
* [PHP](https://www.php.net/) - PHP 7.2.
* [MariaDB](https://mariadb.com/) - Maria DB 10.3.
* [Bootstrap](https://getbootstrap.com/docs/3.3/getting-started/) - Bootstrap Framework 3.3.6.
* [JQuery](https://jquery.com/) - Jquery Library 2.1.
* [PHP Excel](http://www.codeplex.com/PHPExcel) - PHP Excel for Generate Excel File (.xls).
* [MPdf](https://mpdf.github.io/) - MPdf for Generate PDF.

## Installation

  - Import DB with **fintools.sql** file in root folder.
  - Put this project to root directory.

Open **config.php** in */application/config* and edit this part following by site domain.

```php
$config['base_url'] = 'https://financetools.id/';
```
Open **database.php** in */application/config* and edit this part following by database host, username, password and DB name.

```php
'hostname' => 'localhost',
'username' => '',
'password' => '',
'database' => 'fintools',
```

**Environment setting:**\
Open .htaccess in root folder and edit this part.

```htaccess
SetEnv CI_ENV development
```


## Demo
For testing go to this link: [https://testing.financetools.id](https://testing.financetools.id)\
Username: **admin**\
Password: **admin1721**

## Developer and Support
Financetools is developed and supported by **FSS Team from Finance Operation Group at LinkAja** :
  - [Andi Suryadi](mailto:andi_suryadi@linkaja.id) as Leader and Business Analyst
  - [Nurul Huda](mailto:nurul_huda@linkaja.id) as Business and DB Analyst
  - [Badarudin Nento](mailto:badaruddin_nento@linkaja.id) as Fullstack Developer

If you think you've found a bug please Create an Issue.
If you need a customization or help implementing Finance Tools please [Email Us for Consulting Information](mailto:financetoolsupport@linkaja.id).

Thanks and Regards,\
-Badarudin Nento


