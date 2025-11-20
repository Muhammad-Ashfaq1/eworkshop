<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
            padding: 20px;
        }
        
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header .meta {
            font-size: 9px;
            color: #666;
        }
        
        .filters {
            margin-bottom: 15px;
            font-size: 9px;
            color: #555;
        }
        
        .filters strong {
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table thead {
            background-color: #333;
            color: #fff;
        }
        
        table th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
            border: 1px solid #333;
        }
        
        table td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        table tbody tr:hover {
            background-color: #f5f5f5;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 8px;
            color: #666;
            text-align: center;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="meta">
            Generated on: {{ $generatedAt }}
        </div>
    </div>
    
    @if(!empty($filters) && (isset($filters['date_from']) || isset($filters['vehicle_id']) || isset($filters['location_id'])))
    <div class="filters">
        <strong>Filters Applied:</strong>
        @if(isset($filters['date_from']) && $filters['date_from'])
            <span>From: {{ $filters['date_from'] }}</span>
        @endif
        @if(isset($filters['date_to']) && $filters['date_to'])
            <span> | To: {{ $filters['date_to'] }}</span>
        @endif
        @if(isset($filters['vehicle_id']) && $filters['vehicle_id'])
            <span> | Vehicle ID: {{ $filters['vehicle_id'] }}</span>
        @endif
        @if(isset($filters['location_id']) && $filters['location_id'])
            <span> | Location ID: {{ $filters['location_id'] }}</span>
        @endif
    </div>
    @endif
    
    @if(empty($data))
        <div class="no-data">
            No data available for this report.
        </div>
    @else
        <table>
            <thead>
                <tr>
                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        @foreach($headers as $header)
                            <td>{{ $row[$header] ?? 'N/A' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="footer">
            <p>Total Records: {{ count($data) }}</p>
        </div>
    @endif
</body>
</html>

