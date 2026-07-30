<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Certificate Kehadiran</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f4f4f7; padding: 20px; }
        .certificate { background: #ffffff; border: 10px solid #4f46e5; padding: 40px; text-align: center; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .header { font-size: 28px; font-weight: 900; color: #4f46e5; text-transform: uppercase; letter-spacing: 2px; }
        .sub-header { font-size: 16px; color: #6b7280; margin-top: 5px; }
        .name { font-size: 32px; font-weight: bold; color: #1f2937; margin: 30px 0; border-bottom: 2px solid #e5e7eb; display: inline-block; padding-bottom: 10px; }
        .reason { font-size: 16px; color: #4b5563; line-height: 1.6; max-width: 600px; margin: 0 auto; }
        .event-title { font-size: 22px; font-weight: bold; color: #4f46e5; margin-top: 10px; }
        .footer { margin-top: 40px; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">SERTIFIKAT KEHADIRAN</div>
        <div class="sub-header">AmikomEventHub Official Certificate</div>
        
        <p style="margin-top: 30px; color: #6b7280;">Diberikan Secara Bangga Kepada:</p>
        <div class="name">{{ $transaction->customer_name }}</div>
        
        <div class="reason">
            Atas partisipasi dan kehadirannya sebagai peserta dalam acara:
            <div class="event-title">{{ $transaction->event->title ?? 'Event Amikom' }}</div>
            <p style="font-size: 14px; color: #6b7280;">Kode Tiket: <strong>{{ $transaction->order_id }}</strong></p>
        </div>

        <div class="footer">
            Diterbitkan secara otomatis oleh sistem <strong>AmikomEventHub</strong> pada {{ date('d F Y') }}.
        </div>
    </div>
</body>
</html>
