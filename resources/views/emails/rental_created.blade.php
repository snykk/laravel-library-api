<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>New Rental Created</title>
</head>

<body>
  <p>Dear {{ $rental->user->name }},</p>

  <p>We are writing to confirm that your rental of {{ $rental->book->title }} has been successfully created. Your rental
    details are as follows:</p>

  <ul>
    <li>Rental ID: {{ $rental->id }}</li>
    <li>Rental Book: {{ $rental->book->name }}</li>
    <li>Rental Start Date: {{ $rental->rental_date }}</li>
    <li>Rental Duration: {{ $rental->rental_duration }}</li>
  </ul>

  <p>Please note that this rental is valid for one week from the rental start date. If you keep the rental beyond the
    rental end date, you will be charged a late fee of Rp. 10.000 per day.</p>

  <p>If you have any questions or concerns, please feel free to contact us at admin@admin.com or call us at 1234567890.
  </p>

  <p>Thank you for choosing PT XYZ. We hope you enjoy your rental!</p>

  <p>Sincerely,</p>
  <p>The PT XYZ Team</p>
</body>

</html>