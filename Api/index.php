<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guitar Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        form {
            margin-bottom: 20px;
        }
        form input {
            margin-right: 10px;
        }
        button {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <h1>Guitar Store</h1>
    <h2>Guitars</h2>
    <form id="guitarForm">
        <input type="hidden" id="guitarId" name="id">
        <input type="text" id="guitarBrand" name="brand" placeholder="Brand" required>
        <input type="text" id="guitarModel" name="model" placeholder="Model" required>
        <input type="number" id="guitarPrice" name="price" placeholder="Price" required>
        <button type="submit">Save</button>
    </form>
    <table id="guitarTable">
        <thead>
            <tr>
                <th>Brand</th>
                <th>Model</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="guitarList"></tbody>
    </table>

    <h2>Users</h2>
    <form id="userForm">
        <input type="hidden" id="userId" name="id">
        <input type="text" id="userName" name="name" placeholder="Name" required>
        <input type="email" id="userEmail" name="email" placeholder="Email" required>
        <input type="password" id="userPassword" name="password" placeholder="Password" required>
        <button type="submit">Save</button>
    </form>
    <ul id="userList"></ul>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const guitarForm = document.getElementById('guitarForm');
            const guitarTable = document.getElementById('guitarTable');
            const guitarList = document.getElementById('guitarList');
            const userForm = document.getElementById('userForm');
            const userList = document.getElementById('userList');

            const fetchGuitars = () => {
                fetch('api.php?request=guitars')
                    .then(response => response.json())
                    .then(data => {
                        guitarList.innerHTML = '';
                        data.forEach(guitar => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${guitar.brand}</td>
                                <td>${guitar.model}</td>
                                <td>$${guitar.price}</td>
                                <td>
                                    <button onclick="editGuitar(${guitar.id}, '${guitar.brand}', '${guitar.model}', ${guitar.price})">Edit</button>
                                    <button onclick="deleteGuitar(${guitar.id})">Delete</button>
                                </td>
                            `;
                            guitarList.appendChild(row);
                        });
                    });
            };

            const fetchUsers = () => {
                fetch('api.php?request=users')
                    .then(response => response.json())
                    .then(data => {
                        userList.innerHTML = '';
                        data.forEach(user => {
                            const li = document.createElement('li');
                            li.textContent = `${user.name} - ${user.email}`;
                            li.innerHTML += ` <button onclick="editUser(${user.id}, '${user.name}', '${user.email}')">Edit</button>`;
                            li.innerHTML += ` <button onclick="deleteUser(${user.id})">Delete</button>`;
                            userList.appendChild(li);
                        });
                    });
            };

            const saveGuitar = (event) => {
                event.preventDefault();
                const id = document.getElementById('guitarId').value;
                const brand = document.getElementById('guitarBrand').value;
                const model = document.getElementById('guitarModel').value;
                const price = document.getElementById('guitarPrice').value;

                const method = id ? 'PUT' : 'POST';
                fetch('api.php?request=guitars', {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id, brand, model, price }),
                }).then(fetchGuitars);

                guitarForm.reset();
            };

            const saveUser = (event) => {
                event.preventDefault();
                const id = document.getElementById('userId').value;
                const name = document.getElementById('userName').value;
                const email = document.getElementById('userEmail').value;
                const password = document.getElementById('userPassword').value;

                const method = id ? 'PUT' : 'POST';
                fetch('api.php?request=users', {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id, name, email, password }),
                }).then(fetchUsers);

                userForm.reset();
            };

            guitarForm.addEventListener('submit', saveGuitar);
            userForm.addEventListener('submit', saveUser);

            window.editGuitar = (id, brand, model, price) => {
                document.getElementById('guitarId').value = id;
                document.getElementById('guitarBrand').value = brand;
                document.getElementById('guitarModel').value = model;
                document.getElementById('guitarPrice').value = price;
            };

            window.deleteGuitar = (id) => {
                fetch('api.php?request=guitars', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id }),
                }).then(fetchGuitars);
            };

            fetchGuitars();
            fetchUsers();
        });
    </script>
</body>
</html>
