<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opinion Details - {{ $opinion->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 200px;
            background-color: #f5f5f5;
        }
        .message-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
        }
        .status-unread {
            background-color: #ffc107;
            color: #000;
        }
        .status-read {
            background-color: #28a745;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Opinion & Complaint Details</h1>
    </div>

    <table class="info-table">
        <tr>
            <td>ID</td>
            <td>{{ $opinion->id }}</td>
        </tr>
        <tr>
            <td>Name</td>
            <td>{{ $opinion->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Phone</td>
            <td>{{ $opinion->phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Category</td>
            <td>
                @php
                    $categories = [
                        '1' => ['bn' => 'আপনার পরামর্শ বা অভিমত জানান', 'en' => 'Share your advice or opinion'],
                        '2' => ['bn' => 'অভিযোগ জানান', 'en' => 'Report a complaint'],
                        '3' => ['bn' => 'চাঁদাবাজি, সংঘর্ষ বা আইন-শৃঙ্খলা সংক্রান্ত ইনসিডেন্ট রিপোর্ট করুন', 'en' => 'Report extortion, conflict or law and order incident'],
                        '4' => ['bn' => 'অন্য যে কোন বিষয়ে যোগাযোগ করুন', 'en' => 'Contact for any other matter']
                    ];
                    $cat = $categories[$opinion->category] ?? null;
                @endphp
                @if($cat)
                    {{ $cat['bn'] }} / {{ $cat['en'] }}
                @else
                    {{ $opinion->category }}
                @endif
            </td>
        </tr>
        @if($opinion->location)
        <tr>
            <td>Location</td>
            <td>{{ $opinion->location }}</td>
        </tr>
        @endif
        <tr>
            <td>Status</td>
            <td>
                @if ($opinion->status == 0)
                    <span class="status-badge status-unread">Unread</span>
                @else
                    <span class="status-badge status-read">Read</span>
                @endif
            </td>
        </tr>
        <tr>
            <td>Submitted Date</td>
            <td>{{ \Carbon\Carbon::parse($opinion->created_at)->format('d-M-Y h:i A') }}</td>
        </tr>
    </table>

    <div>
        <strong>Message:</strong>
        <div class="message-box">{{ $opinion->message }}</div>
    </div>

    <div class="footer">
        <p>Generated on: {{ \Carbon\Carbon::now()->format('d-M-Y h:i A') }}</p>
    </div>
</body>
</html>
