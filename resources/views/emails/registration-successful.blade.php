<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #0d6efd;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .content p {
            margin-bottom: 20px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table th,
        .details-table td {
            border: 1px solid #e4e4e4;
            padding: 10px;
            text-align: left;
        }
        .details-table th {
            background-color: #f7f7f7;
            width: 40%;
            font-weight: 600;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            background-color: #0d6efd;
            color: #ffffff;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
        }
        .footer {
            background-color: #f9f9f9;
            color: #888;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pendaftaran Berhasil!</h1>
        </div>
        <div class="content">
            <p>Halo, <strong>{{ $data['name'] }}</strong>,</p>
            <p>Pendaftaran perusahaan Anda (<strong>{{ $data['company_name'] }}</strong>) telah berhasil kami terima. Akun Anda saat ini sedang dalam proses peninjauan oleh Super Admin dan akan segera diaktifkan.</p>
            <p>Berikut adalah rincian data yang Anda daftarkan:</p>

            <table class="details-table">
                <tr><th colspan="2" style="text-align: center; background-color: #eee;">Data Penanggung Jawab</th></tr>
                <tr>
                    <td>Nama Lengkap</td>
                    <td>{{ $data['name'] }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $data['email'] }}</td>
                </tr>
                <tr>
                    <td>No. Handphone</td>
                    <td>{{ $data['no_hp'] }}</td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>{{ $data['nik'] ?? '-' }}</td>
                </tr>

                <tr><th colspan="2" style="text-align: center; background-color: #eee; padding-top: 15px;">Data Perusahaan & Bank</th></tr>
                <tr>
                    <td>Nama Perusahaan</td>
                    <td>{{ $data['company_name'] }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>{{ $data['alamat'] }}</td>
                </tr>
                <tr>
                    <td>Nama Bank</td>
                    <td>{{ $data['nama_bank'] }}</td>
                </tr>
                <tr>
                    <td>No. Rekening</td>
                    <td>{{ $data['no_rekening'] }}</td>
                </tr>
                
                <tr><th colspan="2" style="text-align: center; background-color: #eee; padding-top: 15px;">Data Akun Login</th></tr>
                <tr>
                    <td>Username</td>
                    <td><strong>{{ $data['username'] }}</strong></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><strong>{{ $data['password'] }}</strong></td>
                </tr>
            </table>

            <p style="font-weight: bold; color: #d9534f; text-align: center;">
                Harap simpan data login Anda dengan aman dan jangan berikan kepada siapa pun.
            </p>

            <div class="button-container">
                <a href="{{ $loginUrl }}" class="button" style="color: #ffffff !important;">Menuju Halaman Login</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $data['company_name'] }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>