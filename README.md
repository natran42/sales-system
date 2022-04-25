# sales-system
COSC 3380-MW-Team 7
Point of Sale Project

Website Link: https://sevensales.azurewebsites.net/

Login Information:

Admin:
Username: admin
Password: password
(Access to: Cash Register, Inventory, Transaction, Registration, Employees, Reports)

Employee:
Username: michj
Password: justdoit
(Access to: Cash Register, Inventory, Transaction, Registration)

Customers who use self-checkout have access to the cash register and registration only.

If hosting this app locally, php is required with the sqlsrv extension. A php_sqlsrv.rar file has been included that contains four different versions of the sqlsrv extension we used to connect to our DB. Please put the correct .dll file for your device into the php/ext folder and then add the line `extension=[File name of .dll used (leaving off .dll)]` to your php.ini

# Main
# (nav.php)
"Main" contains the code for the navigation bar that can be viewed from all pages to redirect you from one page to another. It also includes the use of user authentication where it differentiates between a manager's, an employee's, and a customer's (self-checkout) view.

# Login
# (login.php/css/jpeg)
The login page is the first page viewed by a user. To enter the application, a login from an employee or manager is required to obtain a certain level of access. A user/customer can also access the cash register through self-checkout without the need of any extra information.

# Cash Register
# (cashRegister.php/css, confirmation.php/css/jpeg, purchase.php/css, remove.php)
The cash register is where a user is able to place desired items into a virtual shopping cart from the database to then check them out once ready. A user enters items wanted through a drop down that asks for an item name, size, and an entry of the quantity. If a item is placed into the cart and is then no longer wanted, the user can remove the item by clicking on the remove button located next to the item. Upon checkout, a user has a choice to checkout as a guest, or as a registered member with the requirement of a phone number that is registered in the database. This when then prompt the user to a confirmation page to confirm the purchase and then redirect the user back to the cash register where further purchases can be made, or where the user can explore other pages.

# Inventory
# (delete.php, inventory.php, update.php)
The inventory stores all the stock and information of a given item. This includes its UPC, stock quantity, sold quantity, minimum quantity, name, description, price, category, size, and if it is active or not in being sold. A trigger can also be located in this area of the application, for a notification is given when an item's quantity reaches a certain threshold to notify an accessed member that an item should be stocked soon. The inventory page also has options of removing or updating an item in the database.

# Transactions
# (transactions.php/css)
The transactions page is a page accessed only by users with high authority, meaning customers are not able to view this page. Customer transactions can be viewed by entering a transaction number which will then display that given transaction and the details behind it i.e. if it was a self-checkout transaction, or a registered member who made a purchase. It will also display all items that were purchased in a transaction. An alert is given when an invalid transaction is entered.

# Registration
# (registration.php/css)
The registration page's sole purpose is to register customers who wish to be members. The page consists of a single form where basic information is asked for i.e. name, phone number, email, and address. Once the form is submitted with valid data, the information is stored into the database. Invalid information is checked before inserting occurs. All fields are required and there are constraints found in each input field to ensure correct information is being entered in the correct format. For instance, a name only accepts characters, the phone number is auto-completed and only allows numerical values to be pressed and  displayed onto the monitor, and the email must be in the format of example@some_domain.com.

# Employees
# (employees.php/css)
The employee page can be viewed only by a manager with accessed authority. Here, all workers or all workers in a given category who work in the work place can be displayed. An employee's information i.e. EID, position, name, phone number, email, and username to their login can be obtained. The page also allows for managers to new employees into the system alongside with their information. Once added, that new employee is able to login into the system using their login.

# Reports
# (customerJoined.php, employeeSales.php, topSeller.php, reportingstyle.css)
There are three reports that can be viewed: customers registered, employee sales, and top sellers. All categories allow you to view the statistics of each in a given time interval i.e. this week, this month, this year or given a custom range.

# Credits
Nathan Tran, Ngan Hoang, Christina Rodriguez, Gamaliel Medrano, Yanely Ayala
