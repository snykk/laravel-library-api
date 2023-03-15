<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Overdue Notification of Rental</title>
</head>

<body>
  <p>Dear {{ $rental->user->name }},</p>

  <p>We regret to inform you that your rental of '{{ $rental->book->title }}' from {{ $rental->rental_date }} is
    currently
    overdue. Please return the book
    as
    soon as possible to avoid further charges.</p>

  <p>If you have already returned the item, please disregard this email and contact us to confirm the return.</p>

  <p>If you have any questions or concerns, please feel free to contact us at admin@admin.com or call us at 1234567890.
  </p>

  <p>Thank you for your cooperation.</p>

  <p>Sincerely,</p>
  <p>The PT XYZ Team</p>
</body>

</html>