<!DOCTYPE html>
<html>
<head>
    <title>CuacaKu</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background: #333;
            color: white;
        }
    </style>
</head>
<body>

<h2>🌤️ Data Cuaca</h2>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Kota</th>
            <th>Suhu</th>
            <th>Kondisi</th>
            <th>Kelembapan</th>
            <th>Angin</th>
        </tr>
    </thead>
    <tbody id="weatherTable">
        <tr>
            <td colspan="6">Loading...</td>
        </tr>
    </tbody>
</table>

<script>
fetch('http://127.0.0.1:8000/api/weathers')
    .then(response => {
        if (!response.ok) {
            throw new Error("HTTP error " + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log(data);

        let table = document.getElementById('weatherTable');
        table.innerHTML = '';

        let items = data.data ? data.data : data;

        if (!items || items.length === 0) {
            table.innerHTML = `<tr><td colspan="6">Data kosong</td></tr>`;
            return;
        }

        items.forEach(item => {
            table.innerHTML += `
                <tr>
                    <td>${item.id}</td>
                    <td>${item.city}</td>
                    <td>${item.temperature}</td>
                    <td>${item.condition}</td>
                    <td>${item.humidity}%</td>
                    <td>${item.wind_speed}</td>
                </tr>
            `;
        });
    })
    .catch(error => {
        console.error("ERROR:", error);
        document.getElementById('weatherTable').innerHTML =
            `<tr><td colspan="6">Gagal mengambil data</td></tr>`;
    });
</script>

</body>
</html>
