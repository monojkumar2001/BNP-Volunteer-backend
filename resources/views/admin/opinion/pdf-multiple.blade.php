<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opinions & Complaints Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.5;
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
            font-size: 22px;
            color: #2c3e50;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #666;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th {
            background-color: #2c3e50;
            color: #fff;
            padding: 10px;
            text-align: left;
            border: 1px solid #333;
            font-weight: bold;
        }
        .data-table td {
            padding: 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
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
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 10px;
        }
        .status-unread {
            background-color: #ffc107;
            color: #000;
        }
        .status-read {
            background-color: #28a745;
            color: #fff;
        }
        .message-preview {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Opinions & Complaints Report</h1>
        <p>Total Records: {{ $opinions->count() }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40px;">ID</th>
                <th style="width: 120px;">Name</th>
                <th style="width: 100px;">Phone</th>
                <th style="width: 150px;">Category</th>
                <th style="width: 200px;">Message Preview</th>
                <th style="width: 100px;">Location</th>
                <th style="width: 60px;">Status</th>
                <th style="width: 120px;">Submitted Date</th>
            </tr>
        </thead>
        <tbody>
            @php
                $categories = [
                    '1' => 'পরামর্শ/অভিমত (Advice/Opinion)',
                    '2' => 'অভিযোগ (Complaint)',
                    '3' => 'চাঁদাবাজি/সংঘর্ষ রিপোর্ট (Extortion/Conflict Report)',
                    '4' => 'অন্যান্য যোগাযোগ (Other Contact)'
                ];
            @endphp
            @foreach ($opinions as $opinion)
                <tr>
                    <td>{{ $opinion->id }}</td>
                    <td>{{ $opinion->name ?? 'N/A' }}</td>
                    <td>{{ $opinion->phone ?? 'N/A' }}</td>
                    <td>{{ $categories[$opinion->category] ?? $opinion->category }}</td>
                    <td class="message-preview">{{ mb_substr($opinion->message, 0, 100) }}{{ mb_strlen($opinion->message) > 100 ? '...' : '' }}</td>
                    <td>{{ $opinion->location ?? 'N/A' }}</td>
                    <td>
                        @if ($opinion->status == 0)
                            <span class="status-badge status-unread">Unread</span>
                        @else
                            <span class="status-badge status-read">Read</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($opinion->created_at)->format('d-M-Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on: {{ \Carbon\Carbon::now()->format('d-M-Y h:i A') }}</p>
    </div>
</body>
</html>
