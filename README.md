# Notes
A note saving platform that uses angular

#### 1) Make a database with the following tables:

###### a) Table: fridge
id | name | zindex | content | x | y | width | height
---|------|--------|---------|---|---|-------|-------|
###### b) Table: data
id | name | session | amount
---|------|---------|-------|
#### 2) Add your credentials in the index.php file
```javascript
$conn = new mysqli('localhost','username','password','database_name')
```
#### 3) Add your database name in index.html where the comments are placed:
```javascript
xhttp.open("POST", "index.php?id=yourdbname", true);
```
and here
```javascript
xhttp.open("GET", "index.php?id=yourdbname&name=" + data.name + "&init=" + data.init, true);
```
